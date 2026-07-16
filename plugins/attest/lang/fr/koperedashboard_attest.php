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

$string['actions_title'] = 'Actions';
$string['all_issues_button'] = 'Voir toutes les attestations';
$string['all_issues_desc'] = '{$a} attestation(s) trouvée(s).';
$string['all_issues_title'] = 'Toutes les attestations';
$string['already_has_valid'] = 'Une attestation valide existe déjà';
$string['attest:manage'] = 'Gérer les attestations';
$string['attest:view'] = 'Voir les attestations';
$string['audit_issue_create'] = 'Attestation générée.';
$string['audit_tpl_create'] = 'Modèle créé.';
$string['audit_tpl_delete'] = 'Modèle supprimé.';
$string['audit_tpl_update'] = 'Modèle mis à jour.';
$string['available_desc'] = 'Uniquement les attestations des cours avec inscription.';
$string['available_title'] = 'Attestations disponibles';
$string['back_to_templates'] = 'Retour aux modèles';
$string['cap_manage'] = 'Gestionnaire d\'attestations';
$string['cap_manage_desc'] = 'Peut créer et gérer les modèles d\'attestation.';
$string['cap_view'] = 'Accès aux attestations';
$string['cap_view_desc'] = 'Peut voir et générer ses propres attestations.';
$string['choose_course'] = 'Choisir un cours';
$string['choose_course_help'] = 'Sélectionnez d\'abord le cours pour voir les attestations disponibles.';
$string['choose_course_placeholder'] = 'Sélectionner un cours';
$string['close_modal'] = 'Fermer';
$string['course_removed'] = 'Cours supprimé';
$string['delete_template'] = 'Supprimer';
$string['edit_template'] = 'Modifier le modèle';
$string['empty_all_issues'] = 'Aucune attestation ne correspond aux filtres sélectionnés.';
$string['empty_available'] = 'Aucune attestation n\'est disponible pour vos inscriptions.';
$string['empty_available_for_course'] = 'Aucune attestation n\'est disponible pour le cours sélectionné.';
$string['empty_available_select_course'] = 'Sélectionnez un cours pour afficher les attestations disponibles.';
$string['empty_courses'] = 'Vous n\'êtes actuellement inscrit à aucun cours.';
$string['empty_issued'] = 'Vous n\'avez encore généré aucune attestation.';
$string['field_active'] = 'Actif';
$string['field_allcourses'] = 'Valable pour tous les cours';
$string['field_courses'] = 'Cours';
$string['field_courses_help'] = 'Sélectionnez les cours pour lesquels ce modèle peut être utilisé. Si « Valable pour tous les cours » est coché, ce paramètre sera ignoré.';
$string['field_html'] = 'Modèle HTML';
$string['field_html_help'] = 'Saisissez le HTML qui sera utilisé pour générer le PDF de l\'attestation. Vous pouvez utiliser les espaces réservés disponibles pour insérer des données dynamiques sur l\'étudiant, le cours, la date et la validité.';
$string['field_name'] = 'Nom';
$string['field_validmonths'] = 'Valable pendant (mois)';
$string['filter_all_courses'] = 'Tous les cours';
$string['filter_all_students'] = 'Tous les étudiants';
$string['filter_apply'] = 'Filtrer';
$string['filter_clear'] = 'Effacer';
$string['filter_course'] = 'Cours';
$string['filter_student'] = 'Étudiant';
$string['footer_created'] = 'Créé le';
$string['footer_desc'] = 'Document électronique avec validation par QR code ou par le lien ci-dessous.';
$string['footer_hash'] = 'Signature';
$string['footer_title'] = 'Signature numérique';
$string['footer_validuntil'] = 'Valable jusqu\'au';
$string['generate'] = 'Générer le PDF';
$string['generate_new'] = 'Générer une attestation';
$string['generate_new_button'] = 'Générer une nouvelle attestation';
$string['home_kpi_subtitle'] = 'Documents émis et valides';
$string['home_kpi_title'] = 'Attestations valides';
$string['issued_desc'] = 'Consultez les attestations déjà générées et vérifiez si elles sont encore valides.';
$string['issued_title'] = 'Attestations générées';
$string['manage_title'] = 'Modèles d\'attestation';
$string['menu_desc'] = 'Générer des attestations PDF à partir de modèles HTML.';
$string['menu_title'] = 'Attestations';
$string['new_template'] = 'Nouveau modèle';
$string['open_attestation'] = 'Ouvrir l\'attestation';
$string['open_valid'] = 'Ouvrir l\'attestation valide';
$string['placeholders_title'] = 'Espaces réservés disponibles';
$string['pluginname'] = 'Attestations';
$string['recreate_valid'] = 'Recréer l\'attestation valide';
$string['status_expired'] = 'Expirée';
$string['status_notgenerated'] = 'Pas encore générée';
$string['status_title'] = 'Statut';
$string['status_valid'] = 'Valide';
$string['status_valid_expiring'] = 'Valide et proche de l\'expiration';
$string['student_removed'] = 'Étudiant supprimé (#{$a})';
$string['student_title'] = 'Mes attestations';
$string['studentcard'] = 'Carte d\'étudiant';
$string['studentcard_back_placeholder_description'] = 'Texte explicatif traduit affiché au verso de validation.';
$string['studentcard_back_placeholder_fullname'] = 'Nom complet de l’étudiant.';
$string['studentcard_back_placeholder_hashlabel'] = 'Libellé traduit affiché avant le code de validation.';
$string['studentcard_back_placeholder_qrcode'] = 'Code QR de validation sous forme d’URI de données PNG Base64 intégrée.';
$string['studentcard_back_placeholder_sitefullname'] = 'Nom complet mis en forme du site Moodle.';
$string['studentcard_back_placeholder_title'] = 'Titre traduit du verso de validation.';
$string['studentcard_back_placeholder_userid'] = 'Identifiant numérique de l’utilisateur dans Moodle.';
$string['studentcard_back_placeholder_validationcode'] = 'Code public de validation généré pour la carte d’étudiant.';
$string['studentcard_back_placeholder_verifyurl'] = 'URL publique utilisée pour valider la carte d’étudiant.';
$string['studentcard_back_placeholder_wwwroot'] = 'URL de base du site Moodle.';
$string['studentcard_desc'] = '';
$string['studentcard_front_placeholder_coursefullname'] = 'Nom complet mis en forme du premier cours visible auquel l’étudiant est inscrit.';
$string['studentcard_front_placeholder_courselabel'] = 'Libellé traduit du cours.';
$string['studentcard_front_placeholder_cpf'] = 'CPF obtenu depuis le champ de profil personnalisé, avec le numéro d’identification comme solution de remplacement.';
$string['studentcard_front_placeholder_cpflabel'] = 'Libellé du champ CPF.';
$string['studentcard_front_placeholder_email'] = 'Adresse e-mail de l’étudiant.';
$string['studentcard_front_placeholder_fullname'] = 'Nom complet de l’étudiant.';
$string['studentcard_front_placeholder_photo'] = 'Photo de profil sous forme d’URI de données d’image Base64 intégrée.';
$string['studentcard_front_placeholder_title'] = 'Titre traduit de la carte d’étudiant.';
$string['studentcard_front_placeholder_userid'] = 'Identifiant numérique de l’utilisateur dans Moodle.';
$string['studentcard_settings_back'] = 'Modèle du verso';
$string['studentcard_settings_back_template'] = 'Mustache du verso';
$string['studentcard_settings_back_template_help'] = 'Mustache et HTML rendus par TCPDF sur la page 2 de la carte d’étudiant. Conservez l’URL de validation ou le code QR dans la mise en page afin que le document reste vérifiable.';
$string['studentcard_settings_back_variables'] = 'Variables disponibles au verso';
$string['studentcard_settings_back_variables_desc'] = 'Ces variables Mustache sont remplacées lors de la génération du verso de validation du PDF.';
$string['studentcard_settings_description'] = 'Modifiez les deux modèles Mustache/HTML utilisés pour générer le PDF de la carte d’étudiant. Le recto contient l’identité de l’étudiant et le cours ; le verso contient le code, le lien public et le code QR de validation.';
$string['studentcard_settings_front'] = 'Modèle du recto';
$string['studentcard_settings_front_template'] = 'Mustache du recto';
$string['studentcard_settings_front_template_help'] = 'Mustache et HTML rendus par TCPDF sur la page 1 de la carte d’étudiant. L’image doit être conservée au moyen de la variable de photo, fournie sous forme d’URI de données intégrée.';
$string['studentcard_settings_front_variables'] = 'Variables disponibles au recto';
$string['studentcard_settings_front_variables_desc'] = 'Ces variables Mustache sont remplacées lors de la génération du recto d’identification du PDF.';
$string['studentcard_settings_menu'] = 'Modèle de la carte d’étudiant';
$string['studentcard_settings_title'] = 'Modèle de la carte d’étudiant';
$string['studentcardgenerate'] = 'Générer le PDF';
$string['studentcardnophoto'] = '<h5>Vous n\'avez pas de photo de profil.</h5>Modifiez votre profil et ajoutez une photo pour générer votre carte d\'étudiant.';
$string['studentcardnovisiblecourses'] = 'La carte d\'étudiant est uniquement disponible pour les utilisateurs inscrits à des cours visibles.';
$string['studentcardsignaturedesc'] = 'Cette page contient le code de validation et le lien public de validation du PDF de la carte d\'étudiant.';
$string['studentcardsignaturetitle'] = 'Signature numérique et validateur';
$string['studentcardvalidationinvalid'] = 'Code de validation invalide.';
$string['studentcardvalidationlabel'] = 'Validateur';
$string['studentcardvalidationtitle'] = 'Validation de la carte d\'étudiant';
$string['studentcardvalidationvalid'] = 'Carte d\'étudiant valide.';
$string['template_removed'] = 'Modèle supprimé';
$string['title_view'] = 'Attestations';
$string['verify_title'] = 'Vérification de l\'attestation';
