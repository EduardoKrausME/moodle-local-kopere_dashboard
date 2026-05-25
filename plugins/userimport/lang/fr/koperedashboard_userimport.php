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

$string['back_mapping'] = 'Retour au mappage';
$string['cannotreadcsv'] = 'Impossible de lire le fichier CSV.';
$string['cap_manage'] = 'Gérer les importations d’utilisateurs';
$string['cap_manage_desc'] = 'Téléverser des fichiers CSV, prévisualiser les lignes et exécuter des importations d’utilisateurs dans Kopere Dashboard.';
$string['confirm_import'] = 'Importer les utilisateurs maintenant';
$string['course_not_found'] = 'Cours introuvable.';
$string['customfields'] = 'Champs de profil personnalisés';
$string['filename'] = 'Fichier';
$string['group_not_found'] = 'Groupe introuvable dans le cours sélectionné.';
$string['idnumbercourse'] = 'Numéro d’identification du cours';
$string['invalidcsv'] = 'Le fichier ne semble pas être un CSV valide avec des en-têtes.';
$string['invalidemail'] = 'Adresse e-mail non valide.';
$string['invalidfiletype'] = 'Seuls les fichiers .csv ou .txt sont acceptés.';
$string['invalidtoken'] = 'Le jeton d’importation est invalide ou a expiré.';
$string['invalidusername'] = 'Valeur du nom d’utilisateur invalide.';
$string['manage_intro'] = 'Téléversez un fichier CSV pour créer des utilisateurs, réutiliser des comptes existants, renseigner les champs de profil personnalisés et, éventuellement, les inscrire à des cours et des groupes.';
$string['manualinstance_missing'] = 'Le cours {$a} ne dispose pas d’une instance d’inscription manuelle activée.';
$string['manualplugin_missing'] = 'Le plugin d’inscription manuelle n’est pas disponible sur ce site Moodle.';
$string['mappingerror_email'] = 'Mappez la colonne e-mail.';
$string['mappingerror_firstname'] = 'Mappez la colonne du prénom (ou la colonne du nom complet).';
$string['menu_desc'] = 'Importez des utilisateurs par CSV, prévisualisez le résultat, créez des comptes et, éventuellement, inscrivez-les à des cours/groupes.';
$string['menu_title'] = 'Importation d’utilisateurs';
$string['missingemail'] = 'E-mail manquant.';
$string['missingfirstname'] = 'Prénom manquant.';
$string['missingtempfile'] = 'Le fichier CSV temporaire n’existe plus.';
$string['missingusername'] = 'Nom d’utilisateur manquant.';
$string['pluginname'] = 'Importation d’utilisateurs';
$string['preview_intro'] = 'Vérifiez les premières lignes, mappez chaque colonne CSV et exécutez une prévisualisation avant d’importer.';
$string['preview_title'] = 'Prévisualisation et mappage';
$string['result_alreadyenrolled'] = 'Déjà inscrit';
$string['result_courseenrolled'] = 'Inscrit à {$a}';
$string['result_groupadded'] = 'Ajouté au groupe {$a}';
$string['result_usercreated'] = 'Utilisateur créé';
$string['result_userexists'] = 'L’utilisateur existait déjà';
$string['run_preview'] = 'Exécuter la prévisualisation';
$string['saveuploaderror'] = 'Impossible d’enregistrer le fichier téléversé dans le stockage temporaire de Moodle.';
$string['select_column'] = 'Sélectionnez une colonne';
$string['separator'] = 'Séparateur détecté';
$string['separator_comma'] = 'Virgule (,)';
$string['separator_semicolon'] = 'Point-virgule (;)';
$string['separator_tab'] = 'Tabulation';
$string['shortnamecourse'] = 'Nom abrégé/nom complet du cours';
$string['start_over'] = 'Démarrer une nouvelle importation';
$string['status_created'] = 'Créé';
$string['status_error'] = 'Erreur';
$string['status_existing'] = 'Utilisateur existant';
$string['status_ok'] = 'Prêt';
$string['status_willcreate'] = 'Sera créé';
$string['summary_create'] = 'Sera créé';
$string['summary_created'] = 'Utilisateurs créés';
$string['summary_enrolled'] = 'Nouvelles inscriptions';
$string['summary_errors'] = 'Erreurs';
$string['summary_existing'] = 'Existants';
$string['summary_total'] = 'Lignes';
$string['summary_withcourse'] = 'Lignes avec cours';
$string['table_course'] = 'Cours';
$string['table_email'] = 'E-mail';
$string['table_firstname'] = 'Prénom';
$string['table_group'] = 'Groupe';
$string['table_lastname'] = 'Nom';
$string['table_line'] = 'Ligne';
$string['table_message'] = 'Message';
$string['table_status'] = 'Statut';
$string['table_username'] = 'Nom d’utilisateur';
$string['tip_detectseparator'] = 'Le plugin détecte automatiquement les fichiers séparés par point-virgule, virgule et tabulation.';
$string['tip_existing'] = 'Les utilisateurs existants ne sont pas dupliqués. Ils peuvent toujours recevoir des données de champs personnalisés et des inscriptions à des cours.';
$string['tip_headers'] = 'La première ligne du CSV est traitée comme ligne d’en-tête.';
$string['tip_password'] = 'Si un nouvel utilisateur n’a pas de mot de passe ou en a un très court, un mot de passe aléatoire est généré et le changement obligatoire du mot de passe est activé.';
$string['upload_csv'] = 'Téléverser un fichier CSV';
$string['upload_submit'] = 'Continuer';
$string['uploaderror'] = 'Envoyez un fichier CSV valide.';
