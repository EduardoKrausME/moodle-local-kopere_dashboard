<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * import_service.php
 *
 * @package   koperedashboard_userimport
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace koperedashboard_userimport\service;

use coding_exception;
use context_course;
use core_text;
use core_user;
use moodle_exception;
use stdClass;

/**
 * Class import_service
 */
class import_service {
    /** @var string */
    private const SESSIONKEY = "koperedashboard_userimport";

    /**
     * Store uploaded CSV in temp directory and return generated token.
     *
     * @param array $file
     * @return string
     */
    public static function store_uploaded_csv(array $file): string {
        global $CFG, $SESSION;

        if (empty($file["tmp_name"]) || !is_uploaded_file($file["tmp_name"])) {
            throw new moodle_exception("uploaderror", "koperedashboard_userimport");
        }

        $originalname = clean_param($file["name"] ?? "users.csv", PARAM_FILE);
        $extension = strtolower(pathinfo($originalname, PATHINFO_EXTENSION));
        if (!in_array($extension, ["csv", "txt"], true)) {
            throw new moodle_exception("invalidfiletype", "koperedashboard_userimport");
        }

        $tempdir = make_temp_directory("koperedashboard_userimport");
        $token = bin2hex(random_bytes(16));
        $filepath = $tempdir . DIRECTORY_SEPARATOR . $token . ".csv";

        if (!move_uploaded_file($file["tmp_name"], $filepath)) {
            throw new moodle_exception("saveuploaderror", "koperedashboard_userimport");
        }

        $separator = self::detect_separator($filepath);
        $headers = self::read_headers($filepath, $separator);
        if (count($headers) < 2) {
            throw new moodle_exception("invalidcsv", "koperedashboard_userimport");
        }

        if (!isset($SESSION->{self::SESSIONKEY})) {
            $SESSION->{self::SESSIONKEY} = [];
        }

        $SESSION->{self::SESSIONKEY}[$token] = [
            "token" => $token,
            "originalname" => $originalname,
            "filepath" => $filepath,
            "separator" => $separator,
            "headers" => $headers,
            "timecreated" => time(),
        ];

        return $token;
    }

    /**
     * Return stored import state.
     *
     * @param string $token
     * @return array
     */
    public static function get_state(string $token): array {
        global $SESSION;

        if (empty($SESSION->{self::SESSIONKEY}[$token])) {
            throw new moodle_exception("invalidtoken", "koperedashboard_userimport");
        }

        $state = $SESSION->{self::SESSIONKEY}[$token];
        if (empty($state["filepath"]) || !file_exists($state["filepath"])) {
            unset($SESSION->{self::SESSIONKEY}[$token]);
            throw new moodle_exception("missingtempfile", "koperedashboard_userimport");
        }

        return $state;
    }

    /**
     * Cleanup a stored import state.
     *
     * @param string $token
     * @return void
     */
    public static function cleanup(string $token): void {
        global $SESSION;

        if (!empty($SESSION->{self::SESSIONKEY}[$token])) {
            $state = $SESSION->{self::SESSIONKEY}[$token];
            if (!empty($state["filepath"]) && file_exists($state["filepath"])) {
                @unlink($state["filepath"]);
            }
            unset($SESSION->{self::SESSIONKEY}[$token]);
        }
    }

    /**
     * Return mapping fields for the UI.
     *
     * @return array
     */
    public static function get_mapping_definition(): array {
        global $DB;

        $fields = [
            ["name" => "username", "label" => get_string("username"), "required" => false],
            ["name" => "password", "label" => get_string("password"), "required" => false],
            ["name" => "idnumber", "label" => get_string("idnumber"), "required" => false],
            ["name" => "firstname", "label" => get_string("firstname"), "required" => true],
            ["name" => "lastname", "label" => get_string("lastname"), "required" => false],
            ["name" => "email", "label" => get_string("email"), "required" => true],
            ["name" => "phone1", "label" => get_string("phone1"), "required" => false],
            ["name" => "phone2", "label" => get_string("phone2"), "required" => false],
            ["name" => "address", "label" => get_string("address"), "required" => false],
            ["name" => "city", "label" => get_string("city"), "required" => false],
            ["name" => "country", "label" => get_string("country"), "required" => false],
            [
                "name" => "shortnamecourse", "label" => get_string(
                "shortnamecourse", "koperedashboard_userimport"
            ), "required" => false,
            ],
            [
                "name" => "idnumbercourse", "label" => get_string(
                "idnumbercourse", "koperedashboard_userimport"
            ), "required" => false,
            ],
            ["name" => "groupmembers", "label" => get_string("groupmembers", "group"), "required" => false],
            ["name" => "enroltimestart", "label" => get_string("enroltimestart", "enrol"), "required" => false],
            ["name" => "enroltimeend", "label" => get_string("enroltimeend", "enrol"), "required" => false],
        ];

        $customfields = [];
        $records = $DB->get_records("user_info_field", null, "sortorder ASC", "id,name,shortname");
        foreach ($records as $record) {
            $customfields[] = [
                "id" => (int) $record->id,
                "name" => format_string($record->name),
                "shortname" => s($record->shortname),
            ];
        }

        return [
            "fields" => $fields,
            "customfields" => $customfields,
        ];
    }

    /**
     * Build mapping UI context.
     *
     * @param array $state
     * @param array $mapping
     * @return array
     */
    public static function get_mapping_context(array $state, array $mapping = []): array {
        $definition = self::get_mapping_definition();
        $options = self::build_column_options($state["headers"]);
        $previewrows = self::get_preview_rows($state, 10);

        $fields = [];
        foreach ($definition["fields"] as $field) {
            $selected = $mapping["map"][$field["name"]] ?? "";
            $fieldoptions = self::mark_selected_options($options, $selected);
            $fields[] = [
                "name" => $field["name"],
                "label" => $field["label"],
                "required" => $field["required"],
                "options" => $fieldoptions,
            ];
        }

        $customfields = [];
        foreach ($definition["customfields"] as $field) {
            $selected = $mapping["custommap"][$field["id"]] ?? "";
            $customfields[] = [
                "id" => $field["id"],
                "name" => $field["name"],
                "shortname" => $field["shortname"],
                "options" => self::mark_selected_options($options, $selected),
            ];
        }

        return [
            "token" => $state["token"],
            "filename" => s($state["originalname"]),
            "separatorlabel" => self::get_separator_label($state["separator"]),
            "headers" => array_map(static function(string $header): array {
                return ["value" => $header];
            }, $state["headers"]),
            "previewrows" => $previewrows,
            "fields" => $fields,
            "customfields" => $customfields,
        ];
    }

    /**
     * Clean incoming mapping arrays.
     *
     * @param array $mapping
     * @param array $custommapping
     * @return array
     */
    public static function clean_mapping(array $mapping, array $custommapping): array {
        $clean = [
            "map" => [],
            "custommap" => [],
        ];

        foreach ($mapping as $field => $index) {
            $clean["map"][clean_param($field, PARAM_ALPHANUMEXT)] = self::clean_mapping_value($index);
        }

        foreach ($custommapping as $fieldid => $index) {
            $clean["custommap"][(int) $fieldid] = self::clean_mapping_value($index);
        }

        return $clean;
    }

    /**
     * Validate mapping before preview/import.
     *
     * @param array $mapping
     * @return array
     */
    public static function validate_mapping(array $mapping): array {
        $errors = [];

        $firstname = $mapping["map"]["firstname"] ?? "";
        $email = $mapping["map"]["email"] ?? "";

        if ($firstname === "") {
            $errors[] = get_string("mappingerror_firstname", "koperedashboard_userimport");
        }

        if ($email === "") {
            $errors[] = get_string("mappingerror_email", "koperedashboard_userimport");
        }

        return $errors;
    }

    /**
     * Build dry-run preview data.
     *
     * @param array $state
     * @param array $mapping
     * @return array
     */
    public static function preview_import(array $state, array $mapping): array {
        $rows = self::read_csv($state["filepath"], $state["separator"]);
        $summary = [
            "total" => 0,
            "create" => 0,
            "existing" => 0,
            "errors" => 0,
            "withcourse" => 0,
        ];
        $previewrows = [];

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }

            $analysis = self::analyse_row($row, $mapping);
            $summary["total"]++;
            if ($analysis["statuskey"] === "create") {
                $summary["create"]++;
            } else if ($analysis["statuskey"] === "existing") {
                $summary["existing"]++;
            } else {
                $summary["errors"]++;
            }

            if (!empty($analysis["course"])) {
                $summary["withcourse"]++;
            }

            if (count($previewrows) < 200) {
                $previewrows[] = $analysis;
            }
        }

        return [
            "summary" => $summary,
            "rows" => $previewrows,
        ];
    }

    /**
     * Execute import.
     *
     * @param array $state
     * @param array $mapping
     * @return array
     */
    public static function execute_import(array $state, array $mapping): array {
        global $CFG, $DB;

        require_once($CFG->dirroot . "/user/lib.php");
        require_once($CFG->dirroot . "/user/profile/lib.php");
        require_once($CFG->libdir . "/enrollib.php");
        require_once($CFG->dirroot . "/group/lib.php");

        $rows = self::read_csv($state["filepath"], $state["separator"]);
        $summary = [
            "total" => 0,
            "created" => 0,
            "existing" => 0,
            "enrolled" => 0,
            "errors" => 0,
        ];
        $results = [];

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }

            $summary["total"]++;
            $analysis = self::analyse_row($row, $mapping);
            $line = $index + 1;
            $username = $analysis["username"] !== "" ? $analysis["username"] : $analysis["email"];

            if ($analysis["statuskey"] === "error") {
                $summary["errors"]++;
                $results[] = [
                    "line" => $line,
                    "username" => $username,
                    "statusclass" => "danger",
                    "statuslabel" => get_string("status_error", "koperedashboard_userimport"),
                    "message" => implode(" ", $analysis["errors"]),
                ];
                continue;
            }

            $mapped = self::map_row($row, $mapping);
            $user = self::find_user($mapped);
            $creatednow = false;
            $messageparts = [];

            if (!$user) {
                $newuser = self::build_new_user($mapped);
                $newuser->password = self::prepare_password($mapped["password"] ?? "");

                try {
                    $newuserid = user_create_user($newuser, false, false);
                    set_user_preference("auth_forcepasswordchange", 1, $newuserid);
                    $user = core_user::get_user($newuserid, "*");
                    $creatednow = true;
                    $summary["created"]++;
                    $messageparts[] = get_string("result_usercreated", "koperedashboard_userimport");
                } catch (\Throwable $exception) {
                    $summary["errors"]++;
                    $results[] = [
                        "line" => $line,
                        "username" => $username,
                        "statusclass" => "danger",
                        "statuslabel" => get_string("status_error", "koperedashboard_userimport"),
                        "message" => $exception->getMessage(),
                    ];
                    continue;
                }
            } else {
                $summary["existing"]++;
                $messageparts[] = get_string("result_userexists", "koperedashboard_userimport");
            }

            self::save_custom_fields($user->id, $mapped, $mapping);

            $enrolmessage = self::enrol_row_user($user, $mapped);
            if ($enrolmessage["enrolled"]) {
                $summary["enrolled"]++;
            }
            if ($enrolmessage["message"] !== "") {
                $messageparts[] = $enrolmessage["message"];
            }

            $results[] = [
                "line" => $line,
                "username" => $user->username,
                "statusclass" => $creatednow ? "success" : "warning",
                "statuslabel" => $creatednow
                    ? get_string("status_created", "koperedashboard_userimport")
                    : get_string("status_existing", "koperedashboard_userimport"),
                "message" => implode(" · ", array_filter($messageparts)),
            ];
        }

        return [
            "summary" => $summary,
            "rows" => $results,
        ];
    }

    /**
     * Build row analysis for dry-run table.
     *
     * @param array $row
     * @param array $mapping
     * @return array
     */
    private static function analyse_row(array $row, array $mapping): array {
        $mapped = self::map_row($row, $mapping);
        $user = self::find_user($mapped);
        $errors = self::validate_row_data($mapped, $user === null);
        $course = null;
        $group = null;

        if (($mapped["shortnamecourse"] ?? "") !== "" || ($mapped["idnumbercourse"] ?? "") !== "") {
            $course = self::find_course($mapped);
            if (!$course) {
                $errors[] = get_string("course_not_found", "koperedashboard_userimport");
            }
        }

        if ($course && ($mapped["groupmembers"] ?? "") !== "") {
            $group = self::find_group((string) $mapped["groupmembers"], (int) $course->id);
            if (!$group) {
                $errors[] = get_string("group_not_found", "koperedashboard_userimport");
            }
        }

        if (!empty($errors)) {
            $statuskey = "error";
            $statusclass = "danger";
            $statuslabel = get_string("status_error", "koperedashboard_userimport");
        } else if ($user) {
            $statuskey = "existing";
            $statusclass = "warning";
            $statuslabel = get_string("status_existing", "koperedashboard_userimport");
        } else {
            $statuskey = "create";
            $statusclass = "success";
            $statuslabel = get_string("status_willcreate", "koperedashboard_userimport");
        }

        return [
            "line" => 0,
            "username" => s($mapped["username"] ?: $mapped["email"]),
            "firstname" => s($mapped["firstname"]),
            "lastname" => s($mapped["lastname"]),
            "email" => s($mapped["email"]),
            "course" => $course ? format_string($course->fullname) : "",
            "group" => $group ? format_string($group->name) : "",
            "statuskey" => $statuskey,
            "statusclass" => $statusclass,
            "statuslabel" => $statuslabel,
            "message" => implode(" · ", $errors ?: [get_string("status_ok", "koperedashboard_userimport")]),
            "errors" => $errors,
        ];
    }

    /**
     * Map CSV row to field values.
     *
     * @param array $row
     * @param array $mapping
     * @return array
     */
    private static function map_row(array $row, array $mapping): array {
        $mapped = [
            "username" => "",
            "password" => "",
            "idnumber" => "",
            "firstname" => "",
            "lastname" => "",
            "email" => "",
            "phone1" => "",
            "phone2" => "",
            "address" => "",
            "city" => "",
            "country" => "",
            "shortnamecourse" => "",
            "idnumbercourse" => "",
            "groupmembers" => "",
            "enroltimestart" => "",
            "enroltimeend" => "",
        ];

        foreach ($mapping["map"] as $field => $index) {
            if ($index === "") {
                continue;
            }

            $col = (int) $index;
            $mapped[$field] = isset($row[$col]) ? trim((string) $row[$col]) : "";
        }

        foreach ($mapping["custommap"] as $fieldid => $index) {
            if ($index === "") {
                continue;
            }

            $col = (int) $index;
            $mapped["__custom__{$fieldid}"] = isset($row[$col]) ? trim((string) $row[$col]) : "";
        }

        if ($mapped["username"] === "" && $mapped["email"] !== "") {
            $mapped["username"] = $mapped["email"];
        }

        if ($mapped["lastname"] === "" && $mapped["firstname"] !== "") {
            [$mapped["firstname"], $mapped["lastname"]] = self::split_name($mapped["firstname"]);
        }

        return $mapped;
    }

    /**
     * Validate row data.
     *
     * @param array $mapped
     * @param bool $creating
     * @return array
     */
    private static function validate_row_data(array $mapped, bool $creating): array {
        $errors = [];

        if ($mapped["firstname"] === "") {
            $errors[] = get_string("missingfirstname", "koperedashboard_userimport");
        }

        if ($mapped["email"] === "") {
            $errors[] = get_string("missingemail", "koperedashboard_userimport");
        } else if (!validate_email($mapped["email"])) {
            $errors[] = get_string("invalidemail", "koperedashboard_userimport");
        }

        if ($creating) {
            if ($mapped["username"] === "") {
                $errors[] = get_string("missingusername", "koperedashboard_userimport");
            } else {
                $cleanusername = core_user::clean_field(core_text::strtolower($mapped["username"]), "username");
                if ($cleanusername !== core_text::strtolower($mapped["username"])) {
                    $errors[] = get_string("invalidusername", "koperedashboard_userimport");
                }
            }
        }

        return $errors;
    }

    /**
     * Find user by username, email or idnumber.
     *
     * @param array $mapped
     * @return stdClass|null
     */
    private static function find_user(array $mapped): ?stdClass {
        global $DB, $CFG;

        if ($mapped["username"] !== "") {
            $user = $DB->get_record("user", [
                "username" => core_text::strtolower($mapped["username"]),
                "mnethostid" => $CFG->mnet_localhost_id,
            ]);
            if ($user) {
                return $user;
            }
        }

        if (empty($CFG->allowaccountssameemail) && $mapped["email"] !== "") {
            $user = $DB->get_record("user", ["email" => core_text::strtolower($mapped["email"])]);
            if ($user) {
                return $user;
            }
        }

        if ($mapped["idnumber"] !== "") {
            $user = $DB->get_record("user", ["idnumber" => $mapped["idnumber"]]);
            if ($user) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Build new user record.
     *
     * @param array $mapped
     * @return stdClass
     */
    private static function build_new_user(array $mapped): stdClass {
        global $CFG, $USER;

        $newuser = new stdClass();
        $newuser->auth = "manual";
        $newuser->confirmed = 1;
        $newuser->mnethostid = $CFG->mnet_localhost_id;
        $newuser->username = core_text::strtolower($mapped["username"]);
        $newuser->email = core_text::strtolower($mapped["email"]);
        $newuser->firstname = $mapped["firstname"];
        $newuser->lastname = $mapped["lastname"] !== "" ? $mapped["lastname"] : "-";
        $newuser->idnumber = $mapped["idnumber"];
        $newuser->phone1 = $mapped["phone1"];
        $newuser->phone2 = $mapped["phone2"];
        $newuser->address = $mapped["address"];
        $newuser->city = $mapped["city"];
        $newuser->country = $mapped["country"] !== "" ? $mapped["country"] : $USER->country;

        return $newuser;
    }

    /**
     * Save custom profile field data.
     *
     * @param int $userid
     * @param array $mapped
     * @param array $mapping
     * @return void
     */
    private static function save_custom_fields(int $userid, array $mapped, array $mapping): void {
        global $CFG, $DB;

        require_once($CFG->dirroot . "/user/profile/lib.php");

        if (empty($mapping["custommap"])) {
            return;
        }

        $fields = $DB->get_records_list("user_info_field", "id", array_keys($mapping["custommap"]), "", "id,shortname");
        $profiledata = new stdClass();
        $profiledata->id = $userid;

        foreach ($mapping["custommap"] as $fieldid => $index) {
            if ($index === "" || empty($fields[$fieldid])) {
                continue;
            }

            $shortname = $fields[$fieldid]->shortname;
            $profiledata->{"profile_field_{$shortname}"} = $mapped["__custom__{$fieldid}"] ?? "";
        }

        profile_save_data($profiledata);
    }

    /**
     * Enrol user if course fields were mapped.
     *
     * @param stdClass $user
     * @param array $mapped
     * @return array
     */
    private static function enrol_row_user(stdClass $user, array $mapped): array {
        global $CFG, $DB;

        require_once($CFG->libdir . "/enrollib.php");
        require_once($CFG->dirroot . "/group/lib.php");

        $course = self::find_course($mapped);
        if (!$course) {
            return ["enrolled" => false, "message" => ""];
        }

        $instance = $DB->get_record("enrol", [
            "courseid" => $course->id,
            "enrol" => "manual",
            "status" => ENROL_INSTANCE_ENABLED,
        ], "*", IGNORE_MULTIPLE);

        if (!$instance) {
            return [
                "enrolled" => false,
                "message" => get_string("manualinstance_missing", "koperedashboard_userimport", format_string($course->fullname)),
            ];
        }

        $plugin = enrol_get_plugin("manual");
        if (!$plugin) {
            return ["enrolled" => false, "message" => get_string("manualplugin_missing", "koperedashboard_userimport")];
        }

        $existing = $DB->record_exists("user_enrolments", [
            "enrolid" => $instance->id,
            "userid" => $user->id,
        ]);

        $timestart = self::parse_date_value($mapped["enroltimestart"] ?? "");
        if (!$timestart) {
            $timestart = time();
        }

        $timeend = self::parse_date_value($mapped["enroltimeend"] ?? "");
        if (!$timeend) {
            $timeend = 0;
        }

        if (!$existing) {
            $plugin->enrol_user($instance, $user->id, $instance->roleid, $timestart, $timeend, ENROL_USER_ACTIVE);
        }

        $groupmessage = "";
        if (($mapped["groupmembers"] ?? "") !== "") {
            $group = self::find_group((string) $mapped["groupmembers"], (int) $course->id);
            if ($group) {
                groups_add_member($group->id, $user->id);
                $groupmessage = get_string("result_groupadded", "koperedashboard_userimport", format_string($group->name));
            }
        }

        return [
            "enrolled" => !$existing,
            "message" => trim(
                get_string("result_courseenrolled", "koperedashboard_userimport", format_string($course->fullname)) .
                ($groupmessage ? " · {$groupmessage}" : "") .
                ($existing ? " · " . get_string("result_alreadyenrolled", "koperedashboard_userimport") : "")
            ),
        ];
    }

    /**
     * Find course by idnumber, shortname or fullname.
     *
     * @param array $mapped
     * @return stdClass|null
     */
    private static function find_course(array $mapped): ?stdClass {
        global $DB;

        if (($mapped["idnumbercourse"] ?? "") !== "") {
            $course = $DB->get_record("course", ["idnumber" => $mapped["idnumbercourse"]]);
            if ($course) {
                return $course;
            }
        }

        if (($mapped["shortnamecourse"] ?? "") !== "") {
            $course = $DB->get_record("course", ["shortname" => $mapped["shortnamecourse"]]);
            if ($course) {
                return $course;
            }

            $course = $DB->get_record("course", ["fullname" => $mapped["shortnamecourse"]]);
            if ($course) {
                return $course;
            }
        }

        return null;
    }

    /**
     * Find group by name or idnumber.
     *
     * @param string $value
     * @param int $courseid
     * @return stdClass|null
     */
    private static function find_group(string $value, int $courseid): ?stdClass {
        global $DB;

        if ($value === "") {
            return null;
        }

        $group = $DB->get_record("groups", ["name" => $value, "courseid" => $courseid]);
        if ($group) {
            return $group;
        }

        $group = $DB->get_record("groups", ["idnumber" => $value, "courseid" => $courseid]);
        if ($group) {
            return $group;
        }

        return null;
    }

    /**
     * Return first rows for CSV preview.
     *
     * @param array $state
     * @param int $limit
     * @return array
     */
    private static function get_preview_rows(array $state, int $limit): array {
        $rows = self::read_csv($state["filepath"], $state["separator"], $limit + 1);
        $output = [];

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }

            $cells = [];
            foreach ($state["headers"] as $columnindex => $header) {
                $cells[] = [
                    "value" => isset($row[$columnindex]) ? (string) $row[$columnindex] : "",
                    "empty" => !isset($row[$columnindex]) || trim((string) $row[$columnindex]) === "",
                ];
            }
            $output[] = ["cells" => $cells];
        }

        return $output;
    }

    /**
     * Read CSV rows.
     *
     * @param string $filepath
     * @param string $separator
     * @param int|null $limit
     * @return array
     */
    private static function read_csv(string $filepath, string $separator, ?int $limit = null): array {
        $rows = [];
        $handle = fopen($filepath, "r");
        if ($handle === false) {
            throw new moodle_exception("cannotreadcsv", "koperedashboard_userimport");
        }

        while (($data = fgetcsv($handle, 0, $separator)) !== false) {
            $data = array_map(static function($value): string {
                $value = (string) $value;
                return preg_replace('/^\xEF\xBB\xBF/', '', $value);
            }, $data);

            $rows[] = $data;
            if ($limit !== null && count($rows) >= $limit) {
                break;
            }
        }

        fclose($handle);

        return $rows;
    }

    /**
     * Return the header row.
     *
     * @param string $filepath
     * @param string $separator
     * @return array
     */
    private static function read_headers(string $filepath, string $separator): array {
        $rows = self::read_csv($filepath, $separator, 1);
        if (empty($rows[0])) {
            return [];
        }

        return array_map(static function(string $header): string {
            return trim((string) $header);
        }, $rows[0]);
    }

    /**
     * Guess the CSV separator.
     *
     * @param string $filepath
     * @return string
     */
    private static function detect_separator(string $filepath): string {
        $sample = (string) file_get_contents($filepath, false, null, 0, 4096);
        $candidates = [
            ";" => substr_count($sample, ";"),
            "," => substr_count($sample, ","),
            "\t" => substr_count($sample, "\t"),
        ];

        arsort($candidates);
        return (string) array_key_first($candidates);
    }

    /**
     * Return visible label for separator.
     *
     * @param string $separator
     * @return string
     */
    public static function get_separator_label(string $separator): string {
        if ($separator === ";") {
            return get_string("separator_semicolon", "koperedashboard_userimport");
        }
        if ($separator === ",") {
            return get_string("separator_comma", "koperedashboard_userimport");
        }
        if ($separator === "\t") {
            return get_string("separator_tab", "koperedashboard_userimport");
        }

        return s($separator);
    }

    /**
     * Build select options from CSV headers.
     *
     * @param array $headers
     * @return array
     */
    private static function build_column_options(array $headers): array {
        $options = [
            [
                "value" => "",
                "label" => get_string("select_column", "koperedashboard_userimport"),
                "selected" => true,
            ],
        ];

        foreach ($headers as $index => $header) {
            $label = self::column_letter($index) . " ({$header})";
            $options[] = [
                "value" => (string) $index,
                "label" => $label,
                "selected" => false,
            ];
        }

        return $options;
    }

    /**
     * Clone options and set selected state.
     *
     * @param array $options
     * @param string $selected
     * @return array
     */
    private static function mark_selected_options(array $options, string $selected): array {
        $cloned = [];
        foreach ($options as $option) {
            $option["selected"] = ((string) $option["value"] === (string) $selected);
            $cloned[] = $option;
        }

        return $cloned;
    }

    /**
     * Return Excel-style letter for a zero-based column.
     *
     * @param int $index
     * @return string
     */
    private static function column_letter(int $index): string {
        $index++;
        $letter = "";

        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $index = (int) floor(($index - 1) / 26);
        }

        return $letter;
    }

    /**
     * Parse date string to timestamp.
     *
     * @param string $value
     * @return int
     */
    private static function parse_date_value(string $value): int {
        $value = trim($value);
        if ($value === "") {
            return 0;
        }

        $timestamp = strtotime($value);
        return $timestamp ?: 0;
    }

    /**
     * Generate or validate password.
     *
     * @param string $password
     * @return string
     */
    private static function prepare_password(string $password): string {
        $password = trim($password);
        if (core_text::strlen($password) >= 5) {
            return $password;
        }

        return "#" . random_string(12);
    }

    /**
     * Split full name into first name and last name.
     *
     * @param string $fullname
     * @return array
     */
    private static function split_name(string $fullname): array {
        $fullname = trim(preg_replace("/\s+/", " ", $fullname));
        if ($fullname === "") {
            return ["", "-"];
        }

        $parts = explode(" ", $fullname);
        if (count($parts) === 1) {
            return [$fullname, "-"];
        }

        $lastname = array_pop($parts);
        return [implode(" ", $parts), $lastname];
    }

    /**
     * Clean mapping value.
     *
     * @param mixed $value
     * @return string
     */
    private static function clean_mapping_value($value): string {
        if ($value === null || $value === "") {
            return "";
        }

        return preg_match("/^\d+$/", (string) $value) ? (string) $value : "";
    }
}
