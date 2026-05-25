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
 * @package   koperedashboard_benchmark
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['back_to_benchmark'] = 'Retour au benchmark';
$string['cap_run'] = 'Exécuter le benchmark';
$string['cap_run_desc'] = 'Peut exécuter des tests de benchmark synthétiques dans Kopere Dashboard.';
$string['cap_view'] = 'Voir le benchmark';
$string['cap_view_desc'] = 'Peut accéder à la zone de benchmark et consulter les recommandations de performance.';
$string['check_backup_auto_active'] = 'Sauvegarde automatique';
$string['check_cachejs'] = 'Cache JavaScript';
$string['check_debug'] = 'Niveau de débogage';
$string['check_debugdisplay'] = 'Afficher les messages de débogage';
$string['check_themedesignermode'] = 'Mode concepteur de thème';
$string['debug_value'] = 'Activé ({$a})';
$string['environment_db'] = 'Base de données';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'Détails d\'OPcache';
$string['environment_opcache_warning'] = 'Gardez OPcache activé en production. Il stocke en mémoire les scripts PHP compilés, réduit l\'utilisation du CPU et améliore le temps de réponse.';
$string['environment_os'] = 'Système d\'exploitation';
$string['environment_os_windows_warning'] = 'Windows n\'est pas recommandé pour les environnements Moodle en production. Préférez Linux pour une meilleure compatibilité, stabilité et performance. <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">Documentation Moodle : le package d\'installation complet pour Windows n\'est pas recommandé pour la production</a>.';
$string['environment_php'] = 'PHP';
$string['environment_title'] = 'Environnement';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = 'Gardez X-Sendfile activé en production. Il permet au serveur web de livrer directement les fichiers, réduisant l\'utilisation de mémoire PHP et améliorant les téléchargements de gros fichiers.';
$string['execute_title'] = 'Exécuter le benchmark';
$string['help_recommendations'] = 'Ces recommandations aident à interpréter si l\'environnement est configuré pour la production. Elles ne remplacent pas une analyse détaillée de la base de données, de Redis, du cron, des disques ou du cache inverse.';
$string['iterations'] = 'Itérations';
$string['label_disabled'] = 'Désactivé';
$string['label_enabled'] = 'Activé';
$string['manage_intro'] = 'Exécutez une courte série de tests synthétiques pour obtenir un aperçu rapide des performances générales du serveur Moodle. Les tests mesurent de simples lectures de base de données, un aller-retour disque, JSON, hash et traitement de chaînes.';
$string['manage_warning'] = 'Les résultats sont comparatifs. Idéalement, exécutez-les toujours sur le même serveur et comparez avant/après les changements de PHP, base de données, disque, cache, Redis, Nginx ou plugins.';
$string['menu_desc'] = 'Mesure les temps de base de données, disque et CPU avec des recommandations rapides pour la production.';
$string['menu_title'] = 'Benchmark';
$string['opcache_details_value'] = 'CLI : {$a->enablecli}<br>mémoire : {$a->memory} MB<br>fichiers max. : {$a->maxfiles}<br>valider les horodatages : {$a->timestamps}<br>fréq. de revalidation : {$a->revalidate}';
$string['peakmemory'] = 'Pic mémoire';
$string['pluginname'] = 'Benchmark';
$string['recommend_backup_auto_active'] = 'Évitez les sauvegardes automatiques pendant les heures de pointe. Préférez des créneaux en dehors des périodes de forte utilisation.';
$string['recommend_cachejs'] = 'En production, gardez le cache JavaScript activé pour réduire le traitement et le transfert.';
$string['recommend_debug'] = 'Le débogage actif augmente le coût de traitement et le bruit. Gardez-le désactivé en production.';
$string['recommend_debugdisplay'] = 'L\'affichage des messages de débogage directement à l\'écran doit être désactivé en production.';
$string['recommend_themedesignermode'] = 'Le mode concepteur de thème doit être désactivé en production afin d\'éviter la recompilation CSS et les baisses de performance.';
$string['recommendation'] = 'Recommandation';
$string['recommendations_title'] = 'Vérifications rapides de configuration';
$string['result_status'] = 'État';
$string['results_title'] = 'Résultats des tests';
$string['run_benchmark'] = 'Exécuter le benchmark';
$string['status_attention'] = 'Attention';
$string['status_fast'] = 'Rapide';
$string['status_slow'] = 'Lent';
$string['summary_title'] = 'Résumé';
$string['test_db_desc'] = 'Lectures répétées de petits enregistrements de configuration depuis la base de données.';
$string['test_db_name'] = 'Base de données';
$string['test_files_desc'] = 'Écriture, lecture et suppression d\'un fichier temporaire local.';
$string['test_files_name'] = 'Système de fichiers';
$string['test_hash_desc'] = 'Tours SHA-256 répétés pour mesurer les performances CPU brutes.';
$string['test_hash_name'] = 'Hash / CPU';
$string['test_json_desc'] = 'Encodage et décodage de structures JSON de taille moyenne.';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'Test';
$string['test_string_desc'] = 'Nettoyage et analyse simples de contenu similaire à HTML.';
$string['test_string_name'] = 'Chaînes / HTML';
$string['time_elapsed'] = 'Temps';
$string['total_time'] = 'Temps total';
$string['value'] = 'Valeur';
$string['xsendfile_value'] = 'Activé ({$a->header}<br>alias : {$a->aliases})';
