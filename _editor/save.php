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
 * Editor.
 *
 * @package   local_kopere_dashboard
 * @copyright 2024 Eduardo kraus (http://eduardokraus.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../../config.php");

require_login();
require_capability("moodle/site:config", context_system::instance());

$page = required_param("page", PARAM_TEXT);
$id = required_param("id", PARAM_TEXT);
$link = optional_param("link", "", PARAM_TEXT);

define("MAX_FILE_LIMIT", 1024 * 1024 * 2);//2 Megabytes max html file size
define("ALLOW_PHP", false);//check if saved html contains php tag and don't save if not allowed
define("ALLOWED_OEMBED_DOMAINS", [
    "https://www.youtube.com/",
    "https://www.vimeo.com/",
    "https://www.x.com/",
    "https://x.com/",
    "https://publish.twitter.com/",
    "https://www.twitter.com/",
    "https://www.reddit.com/",
]);//load urls only from allowed websites for oembed

function sanitizeFileName($file, $allowedExtension = "html") {
    $basename = basename($file);
    $disallow = [".htaccess", "passwd"];
    if (in_array($basename, $disallow)) {
        showError("Filename not allowed!");
        return "";
    }

    //sanitize, remove double dot .. and remove get parameters if any
    $file = preg_replace('@\?.*$@', "", preg_replace('@\.{2,}@', "", preg_replace('@[^\/\\a-zA-Z0-9\-\._]@', "", $file)));

    if ($file) {
        $file = __DIR__ . DIRECTORY_SEPARATOR . $file;
    } else {
        return "";
    }

    //allow only .html extension
    if ($allowedExtension) {
        $file = preg_replace('/\.[^.]+$/', "", $file) . ".$allowedExtension";
    }
    return $file;
}

function showError($error) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404", true, 500);
    die($error);
}

function validOembedUrl($url) {
    foreach (ALLOWED_OEMBED_DOMAINS as $domain) {
        if (strpos($url, $domain) === 0) {
            return true;
        }
    }

    return false;
}

$html = "";
$file = "";
$action = "";

if (optional_param("startTemplateUrl", false, PARAM_RAW)) {
    $startTemplateUrl = sanitizeFileName(optional_param("startTemplateUrl", false, PARAM_RAW));
    $html = "";
    if ($startTemplateUrl) {
        $html = file_get_contents($startTemplateUrl);
    }
} else if (optional_param("html", false, PARAM_RAW)) {
    $html = substr(optional_param("html", false, PARAM_RAW), 0, MAX_FILE_LIMIT);
    if (!ALLOW_PHP) {
        //if (strpos($html, '<?php') !== false) {
        if (preg_match('@<\?php|<\? |<\?=|<\s*script\s*language\s*=\s*"\s*php\s*"\s*>@', $html)) {
            showError("PHP not allowed!");
        }
    }
    $html = str_replace("body >", "", $html);
}

if (optional_param("file", false, PARAM_RAW)) {
    $file = sanitizeFileName(optional_param("file", false, PARAM_RAW));
}

if (optional_param("action", false, PARAM_TEXT)) {
    $action = htmlspecialchars(strip_tags(optional_param("action", false, PARAM_TEXT)), ENT_COMPAT);
}

if ($action) {
    //file manager actions, delete and rename
    switch ($action) {
        case "delete":
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            preg_match('/pluginfile.php\/(\d+)\/local_kopere_dashboard\/(\w+)\/(\d+)\/(.*)/', $data->file, $filePartes);

            if (isset($filePartes[4])) {
                $contextid = $filePartes[1];
                $component = "local_kopere_dashboard";
                $filearea = $filePartes[2];
                $itemid = $filePartes[3];
                $filename = $filePartes[4];

                $fs = get_file_storage();
                $fs->delete_area_files($contextid, $component, $filearea, $itemid);
            }

            header("Content-Type: application/json");
            echo json_encode([
                "success" => 1,
                "message" => "Deleted successfully",
            ]);
            break;
        case "save":
            if ($page == "webpages") {
                $webpages = $DB->get_record("local_kopere_dashboard_pages", ["id" => $id]);
                $webpages->text = $html;
                $DB->update_record("local_kopere_dashboard_pages", $webpages);
            } else if ($page == "notification") {
                $events = $DB->get_record("local_kopere_dashboard_event", ["id" => $id]);
                $events->message = $html;
                $DB->update_record("local_kopere_dashboard_event", $events);
            } else if ($page == "settings") {
                set_config($id, $html, "local_kopere_dashboard");
            }
            echo json_encode([
                "success" => 1,
                "message" => "Saved successfully",
            ]);
            break;
        case "oembedProxy":
            $url = optional_param("url", false, PARAM_RAW) ?? "";
            if (validOembedUrl($url)) {
                $options = [
                    "http" => [
                        "method" => "GET",
                        "header" => "User-Agent: {$_SERVER["HTTP_USER_AGENT"]}\r\n"
                    ]
                ];
                $context = stream_context_create($options);
                header("Content-Type: application/json");
                echo file_get_contents($url, false, $context);
            } else {
                showError("Invalid url!");
            }
            break;
        default:
            showError("Invalid action '$action'!");
    }
} else {
    echo json_encode([
        "success" => 0,
        "message" => "Ops...",
    ]);
}
