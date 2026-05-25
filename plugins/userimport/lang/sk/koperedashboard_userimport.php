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
 * Lang file
 *
 * @package   koperedashboard_userimport
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['back_mapping'] = 'Späť na mapovanie';
$string['cannotreadcsv'] = 'Nepodarilo sa prečítať súbor CSV.';
$string['cap_manage'] = 'Spravovať importy používateľov';
$string['cap_manage_desc'] = 'Nahrávať súbory CSV, zobrazovať náhľad riadkov a spúšťať importy používateľov v Kopere Dashboard.';
$string['confirm_import'] = 'Importovať používateľov teraz';
$string['course_not_found'] = 'Kurz sa nenašiel.';
$string['customfields'] = 'Vlastné polia profilu';
$string['filename'] = 'Súbor';
$string['group_not_found'] = 'Skupina sa vo vybranom kurze nenašla.';
$string['idnumbercourse'] = 'Identifikačné číslo kurzu';
$string['invalidcsv'] = 'Súbor nevyzerá ako platný CSV súbor s hlavičkami.';
$string['invalidemail'] = 'Neplatná e-mailová adresa.';
$string['invalidfiletype'] = 'Akceptované sú iba súbory .csv alebo .txt.';
$string['invalidtoken'] = 'Importný token je neplatný alebo jeho platnosť vypršala.';
$string['invalidusername'] = 'Neplatná hodnota používateľského mena.';
$string['manage_intro'] = 'Nahrajte súbor CSV na vytvorenie používateľov, opätovné použitie existujúcich účtov, vyplnenie vlastných polí profilu a voliteľné zapísanie používateľov do kurzov a skupín.';
$string['manualinstance_missing'] = 'Kurz {$a} nemá povolenú inštanciu manuálneho zápisu.';
$string['manualplugin_missing'] = 'Plugin manuálneho zápisu nie je na tejto Moodle stránke dostupný.';
$string['mappingerror_email'] = 'Namapujte stĺpec e-mailu.';
$string['mappingerror_firstname'] = 'Namapujte stĺpec mena (alebo stĺpec celého mena).';
$string['menu_desc'] = 'Importujte používateľov cez CSV, zobrazte náhľad výsledku, vytvorte účty a voliteľne ich zapíšte do kurzov/skupín.';
$string['menu_title'] = 'Import používateľov';
$string['missingemail'] = 'Chýba e-mail.';
$string['missingfirstname'] = 'Chýba meno.';
$string['missingtempfile'] = 'Dočasný súbor CSV už neexistuje.';
$string['missingusername'] = 'Chýba používateľské meno.';
$string['pluginname'] = 'Import používateľov';
$string['preview_intro'] = 'Skontrolujte prvé riadky, namapujte každý stĺpec CSV a pred importom spustite náhľad.';
$string['preview_title'] = 'Náhľad a mapovanie';
$string['result_alreadyenrolled'] = 'Už zapísaný';
$string['result_courseenrolled'] = 'Zapísaný do {$a}';
$string['result_groupadded'] = 'Pridaný do skupiny {$a}';
$string['result_usercreated'] = 'Používateľ vytvorený';
$string['result_userexists'] = 'Používateľ už existoval';
$string['run_preview'] = 'Spustiť náhľad';
$string['saveuploaderror'] = 'Nepodarilo sa uložiť nahraný súbor do dočasného úložiska Moodle.';
$string['select_column'] = 'Vyberte stĺpec';
$string['separator'] = 'Zistený oddeľovač';
$string['separator_comma'] = 'Čiarka (,)';
$string['separator_semicolon'] = 'Bodkočiarka (;)';
$string['separator_tab'] = 'Tabulátor';
$string['shortnamecourse'] = 'Krátky názov/úplný názov kurzu';
$string['start_over'] = 'Začať nový import';
$string['status_created'] = 'Vytvorené';
$string['status_error'] = 'Chyba';
$string['status_existing'] = 'Existujúci používateľ';
$string['status_ok'] = 'Pripravené';
$string['status_willcreate'] = 'Bude vytvorený';
$string['summary_create'] = 'Bude vytvorený';
$string['summary_created'] = 'Vytvorení používatelia';
$string['summary_enrolled'] = 'Nové zápisy';
$string['summary_errors'] = 'Chyby';
$string['summary_existing'] = 'Existujúci';
$string['summary_total'] = 'Riadky';
$string['summary_withcourse'] = 'Riadky s kurzom';
$string['table_course'] = 'Kurz';
$string['table_email'] = 'E-mail';
$string['table_firstname'] = 'Meno';
$string['table_group'] = 'Skupina';
$string['table_lastname'] = 'Priezvisko';
$string['table_line'] = 'Riadok';
$string['table_message'] = 'Správa';
$string['table_status'] = 'Stav';
$string['table_username'] = 'Používateľské meno';
$string['tip_detectseparator'] = 'Plugin automaticky rozpozná súbory oddelené bodkočiarkou, čiarkou a tabulátorom.';
$string['tip_existing'] = 'Existujúci používatelia sa neduplikujú. Stále môžu dostať údaje vlastných polí a zápisy do kurzov.';
$string['tip_headers'] = 'Prvý riadok CSV sa považuje za riadok hlavičky.';
$string['tip_password'] = 'Ak nový používateľ nemá heslo alebo má veľmi krátke heslo, vygeneruje sa náhodné heslo a aktivuje sa vynútená zmena hesla.';
$string['upload_csv'] = 'Nahrať súbor CSV';
$string['upload_submit'] = 'Pokračovať';
$string['uploaderror'] = 'Odošlite platný súbor CSV.';
