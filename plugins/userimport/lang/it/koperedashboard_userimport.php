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

$string['back_mapping'] = 'Torna alla mappatura';
$string['cannotreadcsv'] = 'Impossibile leggere il file CSV.';
$string['cap_manage'] = 'Gestire le importazioni degli utenti';
$string['cap_manage_desc'] = 'Caricare file CSV, visualizzare l\'anteprima delle righe ed eseguire importazioni di utenti in Kopere Dashboard.';
$string['confirm_import'] = 'Importa utenti ora';
$string['course_not_found'] = 'Corso non trovato.';
$string['customfields'] = 'Campi personalizzati del profilo';
$string['filename'] = 'File';
$string['group_not_found'] = 'Gruppo non trovato nel corso selezionato.';
$string['idnumbercourse'] = 'Numero identificativo del corso';
$string['invalidcsv'] = 'Il file non sembra essere un CSV valido con intestazioni.';
$string['invalidemail'] = 'Indirizzo email non valido.';
$string['invalidfiletype'] = 'Sono accettati solo file .csv o .txt.';
$string['invalidtoken'] = 'Il token di importazione non è valido o è scaduto.';
$string['invalidusername'] = 'Valore del nome utente non valido.';
$string['manage_intro'] = 'Carica un file CSV per creare utenti, riutilizzare account esistenti, compilare i campi personalizzati del profilo e, facoltativamente, iscriverli a corsi e gruppi.';
$string['manualinstance_missing'] = 'Il corso {$a} non ha un\'istanza di iscrizione manuale abilitata.';
$string['manualplugin_missing'] = 'Il plugin di iscrizione manuale non è disponibile in questo sito Moodle.';
$string['mappingerror_email'] = 'Mappa la colonna email.';
$string['mappingerror_firstname'] = 'Mappa la colonna del nome (o la colonna del nome completo).';
$string['menu_desc'] = 'Importa utenti tramite CSV, visualizza l\'anteprima del risultato, crea account e, facoltativamente, iscrivili a corsi/gruppi.';
$string['menu_title'] = 'Importazione utenti';
$string['missingemail'] = 'Email mancante.';
$string['missingfirstname'] = 'Nome mancante.';
$string['missingtempfile'] = 'Il file CSV temporaneo non esiste più.';
$string['missingusername'] = 'Nome utente mancante.';
$string['pluginname'] = 'Importazione utenti';
$string['preview_intro'] = 'Rivedi le prime righe, mappa ogni colonna CSV ed esegui un\'anteprima prima dell\'importazione.';
$string['preview_title'] = 'Anteprima e mappatura';
$string['result_alreadyenrolled'] = 'Già iscritto';
$string['result_courseenrolled'] = 'Iscritto a {$a}';
$string['result_groupadded'] = 'Aggiunto al gruppo {$a}';
$string['result_usercreated'] = 'Utente creato';
$string['result_userexists'] = 'L\'utente esisteva già';
$string['run_preview'] = 'Esegui anteprima';
$string['saveuploaderror'] = 'Impossibile salvare il file caricato nello spazio temporaneo di Moodle.';
$string['select_column'] = 'Seleziona una colonna';
$string['separator'] = 'Separatore rilevato';
$string['separator_comma'] = 'Virgola (,)';
$string['separator_semicolon'] = 'Punto e virgola (;)';
$string['separator_tab'] = 'Tabulazione';
$string['shortnamecourse'] = 'Nome breve/nome completo del corso';
$string['start_over'] = 'Avvia una nuova importazione';
$string['status_created'] = 'Creato';
$string['status_error'] = 'Errore';
$string['status_existing'] = 'Utente esistente';
$string['status_ok'] = 'Pronto';
$string['status_willcreate'] = 'Verrà creato';
$string['summary_create'] = 'Verrà creato';
$string['summary_created'] = 'Utenti creati';
$string['summary_enrolled'] = 'Nuove iscrizioni';
$string['summary_errors'] = 'Errori';
$string['summary_existing'] = 'Esistenti';
$string['summary_total'] = 'Righe';
$string['summary_withcourse'] = 'Righe con corso';
$string['table_course'] = 'Corso';
$string['table_email'] = 'Email';
$string['table_firstname'] = 'Nome';
$string['table_group'] = 'Gruppo';
$string['table_lastname'] = 'Cognome';
$string['table_line'] = 'Riga';
$string['table_message'] = 'Messaggio';
$string['table_status'] = 'Stato';
$string['table_username'] = 'Nome utente';
$string['tip_detectseparator'] = 'Il plugin rileva automaticamente file separati da punto e virgola, virgola e tabulazione.';
$string['tip_existing'] = 'Gli utenti esistenti non vengono duplicati. Possono comunque ricevere dati dei campi personalizzati e iscrizioni ai corsi.';
$string['tip_headers'] = 'La prima riga del CSV viene trattata come riga di intestazione.';
$string['tip_password'] = 'Se un nuovo utente non ha una password o ne ha una molto breve, viene generata una password casuale e viene abilitato il cambio obbligatorio della password.';
$string['upload_csv'] = 'Carica file CSV';
$string['upload_submit'] = 'Continua';
$string['uploaderror'] = 'Invia un file CSV valido.';
