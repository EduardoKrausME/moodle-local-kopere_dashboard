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
 * @package   koperedashboard_attest
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['actions_title'] = 'Azioni';
$string['already_has_valid'] = 'Ne esiste già una valida';
$string['attest:manage'] = 'Gestisci attestazioni';
$string['attest:view'] = 'Visualizza attestazioni';
$string['audit_issue_create'] = 'Attestazione generata.';
$string['audit_tpl_create'] = 'Modello creato.';
$string['audit_tpl_delete'] = 'Modello eliminato.';
$string['audit_tpl_update'] = 'Modello aggiornato.';
$string['available_desc'] = 'Solo attestazioni dei corsi con iscrizione.';
$string['available_title'] = 'Attestazioni disponibili';
$string['cap_manage'] = 'Gestore attestazioni';
$string['cap_manage_desc'] = 'Può creare e gestire i modelli di attestazione.';
$string['cap_view'] = 'Accesso alle attestazioni';
$string['cap_view_desc'] = 'Può visualizzare e generare le proprie attestazioni.';
$string['choose_course'] = 'Scegli un corso';
$string['choose_course_help'] = 'Seleziona prima il corso per vedere le attestazioni disponibili.';
$string['choose_course_placeholder'] = 'Seleziona un corso';
$string['close_modal'] = 'Chiudi';
$string['course_removed'] = 'Corso rimosso';
$string['delete_template'] = 'Elimina';
$string['edit_template'] = 'Modifica modello';
$string['empty_available'] = 'Non sono disponibili attestazioni per le tue iscrizioni.';
$string['empty_available_for_course'] = 'Non sono disponibili attestazioni per il corso selezionato.';
$string['empty_available_select_course'] = 'Seleziona un corso per elencare le attestazioni disponibili.';
$string['empty_courses'] = 'Al momento non sei iscritto ad alcun corso.';
$string['empty_issued'] = 'Non hai ancora generato alcuna attestazione.';
$string['field_active'] = 'Attivo';
$string['field_allcourses'] = 'Valido per tutti i corsi';
$string['field_courses'] = 'Corsi';
$string['field_courses_help'] = 'Seleziona i corsi per i quali questo modello può essere utilizzato. Se "Valido per tutti i corsi" è selezionato, questa impostazione verrà ignorata.';
$string['field_html'] = 'Modello HTML';
$string['field_html_help'] = 'Inserisci l\'HTML che verrà utilizzato per generare il PDF dell\'attestazione. Puoi usare i segnaposto disponibili per inserire dati dinamici di studente, corso, data e validità.';
$string['field_name'] = 'Nome';
$string['field_validmonths'] = 'Valido per (mesi)';
$string['footer_created'] = 'Creato il';
$string['footer_desc'] = 'Documento elettronico con convalida tramite codice QR o tramite il link sottostante.';
$string['footer_hash'] = 'Firma';
$string['footer_title'] = 'Firma digitale';
$string['footer_validuntil'] = 'Valido fino al';
$string['generate'] = 'Genera PDF';
$string['generate_new'] = 'Genera attestazione';
$string['generate_new_button'] = 'Genera nuova attestazione';
$string['home_kpi_subtitle'] = 'Documenti emessi e validi';
$string['home_kpi_title'] = 'Attestazioni valide';
$string['issued_desc'] = 'Visualizza le attestazioni già generate e verifica se sono ancora valide.';
$string['issued_title'] = 'Attestazioni generate';
$string['manage_title'] = 'Modelli di attestazione';
$string['menu_desc'] = 'Genera attestazioni PDF basate su modelli HTML.';
$string['menu_title'] = 'Attestazioni';
$string['new_template'] = 'Nuovo modello';
$string['open_attestation'] = 'Apri attestazione';
$string['open_valid'] = 'Apri attestazione valida';
$string['placeholders_title'] = 'Segnaposto disponibili';
$string['pluginname'] = 'Attestazioni';
$string['recreate_valid'] = 'Ricrea attestazione valida';
$string['status_expired'] = 'Scaduta';
$string['status_notgenerated'] = 'Non ancora generata';
$string['status_title'] = 'Stato';
$string['status_valid'] = 'Valida';
$string['status_valid_expiring'] = 'Valida e prossima alla scadenza';
$string['student_title'] = 'Le mie attestazioni';
$string['studentcard'] = 'Tessera studente';
$string['studentcard_back_placeholder_description'] = 'Testo esplicativo tradotto visualizzato sul lato di convalida.';
$string['studentcard_back_placeholder_fullname'] = 'Nome completo dello studente.';
$string['studentcard_back_placeholder_hashlabel'] = 'Etichetta tradotta visualizzata prima del codice di convalida.';
$string['studentcard_back_placeholder_qrcode'] = 'Codice QR di convalida come URI dati PNG Base64 incorporato.';
$string['studentcard_back_placeholder_sitefullname'] = 'Nome completo formattato del sito Moodle.';
$string['studentcard_back_placeholder_title'] = 'Titolo tradotto del lato di convalida.';
$string['studentcard_back_placeholder_userid'] = 'ID numerico dell’utente in Moodle.';
$string['studentcard_back_placeholder_validationcode'] = 'Codice pubblico di convalida generato per il tesserino dello studente.';
$string['studentcard_back_placeholder_verifyurl'] = 'URL pubblico utilizzato per convalidare il tesserino dello studente.';
$string['studentcard_back_placeholder_wwwroot'] = 'URL di base del sito Moodle.';
$string['studentcard_desc'] = '';
$string['studentcard_front_placeholder_coursefullname'] = 'Nome completo formattato del primo corso visibile al quale lo studente è iscritto.';
$string['studentcard_front_placeholder_courselabel'] = 'Etichetta del corso tradotta.';
$string['studentcard_front_placeholder_cpf'] = 'CPF ottenuto dal campo personalizzato del profilo, utilizzando il numero di identificazione come alternativa.';
$string['studentcard_front_placeholder_cpflabel'] = 'Etichetta del campo CPF.';
$string['studentcard_front_placeholder_email'] = 'Indirizzo e-mail dello studente.';
$string['studentcard_front_placeholder_fullname'] = 'Nome completo dello studente.';
$string['studentcard_front_placeholder_photo'] = 'Foto del profilo come URI dati immagine Base64 incorporato.';
$string['studentcard_front_placeholder_title'] = 'Titolo tradotto del tesserino dello studente.';
$string['studentcard_front_placeholder_userid'] = 'ID numerico dell’utente in Moodle.';
$string['studentcard_settings_back'] = 'Modello del retro';
$string['studentcard_settings_back_template'] = 'Mustache del retro';
$string['studentcard_settings_back_template_help'] = 'Mustache e HTML renderizzati da TCPDF nella pagina 2 del tesserino dello studente. Mantieni l’URL di convalida o il codice QR nel layout affinché il documento resti verificabile.';
$string['studentcard_settings_back_variables'] = 'Variabili disponibili sul retro';
$string['studentcard_settings_back_variables_desc'] = 'Queste variabili Mustache vengono sostituite durante la generazione del lato di convalida del PDF.';
$string['studentcard_settings_description'] = 'Modifica i due modelli Mustache/HTML utilizzati per generare il PDF del tesserino dello studente. Il fronte contiene l’identità dello studente e il corso; il retro contiene il codice, il link pubblico e il codice QR di convalida.';
$string['studentcard_settings_front'] = 'Modello del fronte';
$string['studentcard_settings_front_template'] = 'Mustache del fronte';
$string['studentcard_settings_front_template_help'] = 'Mustache e HTML renderizzati da TCPDF nella pagina 1 del tesserino dello studente. L’immagine deve essere mantenuta tramite la variabile della foto, fornita come URI dati incorporato.';
$string['studentcard_settings_front_variables'] = 'Variabili disponibili sul fronte';
$string['studentcard_settings_front_variables_desc'] = 'Queste variabili Mustache vengono sostituite durante la generazione del lato identificativo del PDF.';
$string['studentcard_settings_menu'] = 'Modello del tesserino dello studente';
$string['studentcard_settings_title'] = 'Modello del tesserino dello studente';
$string['studentcardgenerate'] = 'Genera PDF';
$string['studentcardnophoto'] = '<h5>Non hai una foto del profilo.</h5>Modifica il tuo profilo e aggiungi una foto per generare la tua tessera studente.';
$string['studentcardnovisiblecourses'] = 'La tessera studente è disponibile solo per gli utenti iscritti a corsi visibili.';
$string['studentcardsignaturedesc'] = 'Questa pagina contiene il codice di convalida e il link pubblico di convalida del PDF della tessera studente.';
$string['studentcardsignaturetitle'] = 'Firma digitale e validatore';
$string['studentcardvalidationinvalid'] = 'Codice di convalida non valido.';
$string['studentcardvalidationlabel'] = 'Validatore';
$string['studentcardvalidationtitle'] = 'Convalida tessera studente';
$string['studentcardvalidationvalid'] = 'Tessera studente valida.';
$string['template_removed'] = 'Modello rimosso';
$string['title_view'] = 'Attestazioni';
$string['verify_title'] = 'Verifica attestazione';
