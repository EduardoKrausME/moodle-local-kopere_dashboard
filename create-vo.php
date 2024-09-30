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

$xml = simplexml_load_file("db/install.xml");
$xml = json_decode(json_encode($xml), true);

foreach ($xml['TABLES']["TABLE"] as $table) {

    $tablename = $table["@attributes"]["NAME"];

    $metodos = "";
    foreach ($table["FIELDS"]["FIELD"] as $field) {
        $name = $field["@attributes"]["NAME"];
        $type = $field["@attributes"]["TYPE"];
        switch ($type) {
            case "char":
            case "text":
                $type = "string";
                break;
        }

        $metodos .= "\n
    /**
     * Var {$name}
     *
     * @var {$type}
     */
     public \${$name};\n";
    }

    echo "<textarea style=\"height:303px;width:100%;\"><?php

namespace local_kopere_bi\\vo;

/**
 * Class {$tablename}
 *
 * @package   local_kopere_bi
 * @copyright 2024 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class {$tablename} extends \stdClass { {$metodos} }
</textarea>";
}
