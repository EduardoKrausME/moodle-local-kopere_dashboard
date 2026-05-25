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
 * lib.php
 *
 * @package   koperedashboard_requests
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use koperedashboard_requests\service\permission;

/**
 * Serve request attachments.
 * Itemid is the request ID.
 */
function koperedashboard_requests_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []): bool {
    global $DB;

    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }

    if (!in_array($filearea, ["attachments", "messageattachments"])) {
        return false;
    }

    require_login();

    if (empty($args)) {
        return false;
    }

    $itemid = array_shift($args);

    if ($filearea == "attachments") {
        $request = $DB->get_record("koperedashboard_req_request", ["id" => $itemid], "*", MUST_EXIST);
    } else {
        $message = $DB->get_record("koperedashboard_req_message", ["id" => $itemid], "*", MUST_EXIST);
        $request = $DB->get_record("koperedashboard_req_request", ["id" => $message->requestid], "*", MUST_EXIST);
    }

    if (!permission::can_view_request($request)) {
        return false;
    }

    $relativepath = "/" . implode("/", $args);
    $fullpath = "/{$context->id}/koperedashboard_requests/{$filearea}/{$itemid}{$relativepath}";

    $fs = get_file_storage();
    $file = $fs->get_file_by_hash(sha1($fullpath));
    if (!$file || $file->is_directory()) {
        return false;
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
    return true;
}
