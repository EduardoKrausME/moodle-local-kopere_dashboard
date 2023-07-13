<?php
/**
 * User: Eduardo Kraus
 * Date: 18/05/2023
 * Time: 22:00
 */

require('../../config.php');

require_login();

$type = required_param("type", PARAM_TEXT);
$id = required_param("id", PARAM_TEXT);

switch ($type) {
    case "photo_user":
        \local_kopere_dashboard\util\photo::get_photo_user($id);
        break;
}
