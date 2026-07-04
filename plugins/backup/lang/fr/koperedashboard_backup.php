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
 * @package   koperedashboard_backup
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['action_delete'] = 'Supprimer';
$string['action_download'] = 'Télécharger';
$string['action_generate_database'] = 'Exporter la base de données';
$string['action_generate_moodledata'] = 'Générer une sauvegarde de moodledata';
$string['cannotopenexportfile'] = 'Impossible d’ouvrir le fichier d’exportation : {$a}';
$string['cap_generate'] = 'Générer des sauvegardes';
$string['cap_generate_desc'] = 'Permet aux utilisateurs de générer des fichiers de sauvegarde de moodledata et des sauvegardes de la base de données.';
$string['cap_view'] = 'Voir le centre de sauvegarde';
$string['cap_view_desc'] = 'Permet aux utilisateurs d’accéder au centre de sauvegarde et de télécharger les fichiers générés.';
$string['col_actions'] = 'Actions';
$string['col_created'] = 'Créé le';
$string['col_file'] = 'Fichier';
$string['col_size'] = 'Taille';
$string['col_type'] = 'Type';
$string['commandnotfound'] = 'La commande système requise est introuvable : {$a}.';
$string['current_source_label'] = 'Base de données actuelle :';
$string['database_desc'] = 'Exporte la structure et les données de la base de données en PHP, ce qui permet de choisir le format de sortie et, éventuellement, de séparer les journaux dans leur propre fichier.';
$string['database_success'] = 'Exportation de la base de données générée avec succès : {$a}';
$string['database_title'] = 'Exportation de la base de données';
$string['delete_cancel'] = 'Annuler';
$string['delete_confirm_button'] = 'Oui, supprimer le fichier';
$string['delete_confirm_message'] = 'Voulez-vous vraiment supprimer ce fichier de sauvegarde généré ? Cette action est irréversible.';
$string['delete_confirm_title'] = 'Supprimer le fichier de sauvegarde';
$string['delete_success'] = 'Fichier de sauvegarde supprimé avec succès : {$a}';
$string['deletefailed'] = 'Impossible de supprimer le fichier de sauvegarde : {$a}';
$string['emptyfiles'] = 'Aucun fichier de sauvegarde n’a encore été généré.';
$string['exportscope_full'] = 'Base de données complète';
$string['exportscope_logs'] = 'Journaux uniquement';
$string['exportscope_main'] = 'Base de données sans journaux';
$string['filenotfound'] = 'Fichier de sauvegarde introuvable : {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['friendly_installation_alternative_not_desc'] = 'Installez et configurez <a href="https://moodle.org/plugins/local_alternative_file_system" target="_blank">Alternative File System</a> pour optimiser le temps de migration, conserver les fichiers Moodle dans un stockage distant, réduire la pression sur le disque local, simplifier les environnements en cluster, améliorer la résilience et utiliser éventuellement une diffusion via CDN. Sans lui, cette exportation inclura les fichiers locaux de moodledata dans le ZIP généré.';
$string['friendly_installation_alternative_not_title'] = 'Alternative File System non installé ou non configuré';
$string['friendly_installation_alternative_ok_desc'] = 'Vérifiez dans le <a href="{$a}" target="_blank">système de fichiers alternatif</a> que tous les fichiers sont dans le stockage distant avant d’effectuer la restauration.';
$string['friendly_installation_alternative_ok_title'] = 'Alternative File System installé';
$string['friendly_installation_desc'] = 'Exporte la base de données dans un format compatible avec le <a href="https://github.com/EduardoKrausME/moodle_friendly_installation" target="_blank">programme d’installation du logiciel Moodle™</a> et MoodleData.';
$string['friendly_installation_generate'] = 'Exporter';
$string['friendly_installation_success'] = 'Exportation générée avec succès !';
$string['friendly_installation_title'] = 'Exportation vers le programme d’installation du logiciel Moodle™';
$string['gzipnotavailable'] = 'L’extension PHP zlib/gzip n’est pas disponible sur ce serveur.';
$string['history_title'] = 'Fichiers générés';
$string['home_kpi_empty_subtitle'] = 'Aucune sauvegarde générée';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'Fichier le plus récent : {$a}';
$string['home_kpi_title'] = 'Dernière sauvegarde';
$string['invalidaction'] = 'L’action demandée n’est pas valide.';
$string['invalidfilename'] = 'Le nom de fichier spécifié n’est pas valide.';
$string['invalidoutputformat'] = 'Le format de sortie spécifié n’est pas valide : {$a}';
$string['menu_desc'] = 'Génération manuelle de sauvegarde de moodledata et exportation de la base de données';
$string['menu_title'] = 'Sauvegardes';
$string['moodledata_desc'] = 'Génère un paquet complet du dossier moodledata, en excluant uniquement le dossier où les sauvegardes elles-mêmes sont stockées.';
$string['moodledata_success'] = 'Sauvegarde de moodledata générée avec succès : {$a}';
$string['moodledata_title'] = 'Sauvegarde de moodledata';
$string['notablesfound'] = 'Aucune table n’a été trouvée pour l’exportation.';
$string['outputformat_desc'] = 'Vous pouvez exporter au format de la base de données actuelle ou convertir la sortie entre MySQL/MariaDB et PostgreSQL.';
$string['outputformat_label'] = 'Format de sortie';
$string['page_title'] = 'Centre de sauvegarde';
$string['pluginname'] = 'Sauvegardes';
$string['processfailed'] = 'Échec de l’exécution du processus de sauvegarde : {$a}';
$string['processstartfailed'] = 'Impossible de démarrer le processus de sauvegarde sur le serveur.';
$string['separatelogs_desc'] = 'Lorsque cette option est activée, le système génère un paquet ZIP avec un fichier pour la base de données principale et un autre fichier uniquement pour les tables de journaux.';
$string['separatelogs_label'] = 'Voulez-vous exporter les journaux séparément ?';
$string['type_database'] = 'Base de données';
$string['type_friendly_installation'] = 'Installateur du logiciel Moodle™';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'Le processus a renvoyé une erreur, mais n’a pas fourni de détails.';
$string['unsupporteddbtype'] = 'Type de base de données non pris en charge par ce plugin de sauvegarde : {$a}';
$string['zipcreatefailed'] = 'Impossible de créer le fichier ZIP.';
