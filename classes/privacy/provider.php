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
 * Privacy Subsystem implementation for local_kopere_dashboard.
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\privacy;

use context;
use context_module;
use moodle_recordset;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\helper;
use core_privacy\local\request\transform;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;
use coding_exception;
use context_user;
use dml_exception;
use stdClass;
use tool_dataprivacy\api;
use tool_dataprivacy\local\helper as tool_helper;

/**
 * The local_kopere_dashboard module does not store any data.
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\core_userlist_provider,
    \core_privacy\local\request\plugin\provider {

    /**
     * Returns metadata.
     *
     * @param collection $collection The initialised collection to add items to.
     *
     * @return collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection): collection {

        $collection->add_database_table(
            'local_kopere_dashboard_request',
            [
                'user_id' => 'privacy:metadata:user_id',
                'ip' => 'privacy:metadata:ip',
            ],
            'privacy:metadata'
        );
        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     *
     * @return contextlist $contextlist The contextlist containing the list of contexts used in this plugin.
     * @throws \Exception
     */
    public static function get_contexts_for_userid(int $userid): \core_privacy\local\request\contextlist {
        $sql = "SELECT id
                  FROM {context}
                 WHERE instanceid = :userid
                       AND contextlevel = :contextlevel";

        $contextlist = new contextlist();
        $contextlist->set_component('local_kopere_dashboard');
        $contextlist->add_from_sql($sql, ['userid' => $userid, 'contextlevel' => CONTEXT_USER]);
        return $contextlist;
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin
     *                           combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        if (!is_a($context, \context_user::class)) {
            return;
        }

        $params = [
            'contextlevel' => CONTEXT_USER,
            'contextid' => $context->id,
        ];

        $sql = "SELECT instanceid AS userid
                  FROM {context}
                 WHERE id = :contextid
                       AND contextlevel = :contextlevel";

        $userlist->add_from_sql('userid', $sql, $params);
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     *
     * @throws \Exception
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        if (empty($contextlist->count())) {
            return;
        }

        $user = $contextlist->get_user();
        $datarequests = api::get_data_requests($user->id);
        $context = context_user::instance($user->id);
        $contextdatatowrite = [];
        foreach ($datarequests as $request) {
            $record = $request->to_record();
            $data = new stdClass();

            // The user ID that made the request/the request is made for.
            if ($record->requestedby != $record->userid) {
                if ($user->id != $record->requestedby) {
                    // This request is done by this user for another user.
                    $data->userid = fullname($user);
                } else if ($user->id != $record->userid) {
                    // This request was done by another user on behalf of this user.
                    $data->requestedby = fullname($user);
                }
            }

            // Request type.
            $data->type = tool_helper::get_shortened_request_type_string($record->type);
            // Status.
            $data->status = tool_helper::get_request_status_string($record->status);
            // Creation method.
            $data->creationmethod = tool_helper::get_request_creation_method_string($record->creationmethod);
            // Comments.
            $data->comments = $record->comments;
            // The DPO's comment about this request.
            $data->dpocomment = $record->dpocomment;
            // The date and time this request was lodged.
            $data->timecreated = transform::datetime($record->timecreated);
            $contextdatatowrite[] = $data;
        }

        // User context / Privacy and policies / Data requests.
        $subcontext = [
            get_string('privacyandpolicies', 'admin'),
            get_string('datarequests', 'tool_dataprivacy'),
        ];
        writer::with_context($context)->export_data($subcontext, (object)$contextdatatowrite);

        // Write generic module intro files.
        helper::export_context_files($context, $user);
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param context $context The specific context to delete data for.
     *
     * @throws \Exception
     */
    public static function delete_data_for_all_users_in_context(context $context) {
        global $DB;

        if ($context->contextlevel != CONTEXT_USER) {
            return;
        }

        $where = ['userid' => $context->instanceid];
        $DB->delete_records_select('local_kopere_dashboard_login', 'userid = :userid', $where);
        $DB->delete_records_select('local_kopere_dashboard_acess', 'userid = :userid', $where);
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     *
     * @throws \Exception
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        $context = $contextlist->get_context();

        if ($context->contextlevel != CONTEXT_USER) {
            return;
        }

        $where = ['userid' => $context->instanceid];

        $DB->delete_records_select('local_kopere_dashboard_login', 'userid = :userid', $where);
        $DB->delete_records_select('local_kopere_dashboard_acess', 'userid = :userid', $where);
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     *
     * @throws \Exception
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();

        if ($context->contextlevel != CONTEXT_USER) {
            return;
        }

        $where = ['userid' => $context->instanceid];
        $DB->delete_records_select('local_kopere_dashboard_login', 'userid = :userid', $where);
        $DB->delete_records_select('local_kopere_dashboard_acess', 'userid = :userid', $where);
    }
}

