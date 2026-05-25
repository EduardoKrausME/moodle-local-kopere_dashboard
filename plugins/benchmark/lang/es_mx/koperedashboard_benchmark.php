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

$string['back_to_benchmark'] = 'Volver al benchmark';
$string['cap_run'] = 'Ejecutar benchmark';
$string['cap_run_desc'] = 'Puede ejecutar pruebas sintéticas de benchmark en Kopere Dashboard.';
$string['cap_view'] = 'Ver benchmark';
$string['cap_view_desc'] = 'Puede acceder al área de benchmark y ver recomendaciones de rendimiento.';
$string['check_backup_auto_active'] = 'Respaldo automático';
$string['check_cachejs'] = 'Caché JavaScript';
$string['check_debug'] = 'Nivel de depuración';
$string['check_debugdisplay'] = 'Mostrar mensajes de depuración';
$string['check_themedesignermode'] = 'Modo de diseñador de temas';
$string['debug_value'] = 'Activado ({$a})';
$string['environment_db'] = 'Base de datos';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'Detalles de OPcache';
$string['environment_opcache_warning'] = 'Mantenga OPcache activado en producción. Almacena scripts PHP compilados en memoria, reduce el uso de CPU y mejora el tiempo de respuesta.';
$string['environment_os'] = 'Sistema operativo';
$string['environment_os_windows_warning'] = 'Windows no se recomienda para entornos Moodle en producción. Prefiera Linux para obtener mejor compatibilidad, estabilidad y rendimiento. <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">Documentación de Moodle: el paquete completo de instalación para Windows no se recomienda para producción</a>.';
$string['environment_php'] = 'PHP';
$string['environment_title'] = 'Entorno';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = 'Mantenga X-Sendfile activado en producción. Permite que el servidor web entregue archivos directamente, reduciendo el uso de memoria de PHP y mejorando las descargas de archivos grandes.';
$string['execute_title'] = 'Ejecutar benchmark';
$string['help_recommendations'] = 'Estas recomendaciones ayudan a interpretar si el entorno está configurado para producción. No sustituyen un análisis detallado de la base de datos, Redis, cron, discos o caché inversa.';
$string['iterations'] = 'Iteraciones';
$string['label_disabled'] = 'Desactivado';
$string['label_enabled'] = 'Activado';
$string['manage_intro'] = 'Ejecute un conjunto corto de pruebas sintéticas para obtener una vista rápida del rendimiento general del servidor Moodle. Las pruebas miden lecturas simples de base de datos, ida y vuelta en disco, JSON, hash y procesamiento de cadenas.';
$string['manage_warning'] = 'Los resultados son comparativos. Lo ideal es ejecutarlos siempre en el mismo servidor y comparar antes/después de cambios en PHP, base de datos, disco, caché, Redis, Nginx o plugins.';
$string['menu_desc'] = 'Mide el tiempo de base de datos, disco y CPU con recomendaciones rápidas de producción.';
$string['menu_title'] = 'Benchmark';
$string['opcache_details_value'] = 'CLI: {$a->enablecli}<br>memoria: {$a->memory} MB<br>máx. archivos: {$a->maxfiles}<br>validar marcas de tiempo: {$a->timestamps}<br>frecuencia de revalidación: {$a->revalidate}';
$string['peakmemory'] = 'Pico de memoria';
$string['pluginname'] = 'Benchmark';
$string['recommend_backup_auto_active'] = 'Evite respaldos automáticos durante las horas pico. Prefiera ventanas fuera del horario de mayor uso.';
$string['recommend_cachejs'] = 'En producción, mantenga la caché JavaScript activada para reducir el procesamiento y la transferencia.';
$string['recommend_debug'] = 'La depuración activa aumenta el costo de procesamiento y el ruido. Manténgala desactivada en producción.';
$string['recommend_debugdisplay'] = 'Mostrar mensajes de depuración directamente en pantalla debe estar desactivado en producción.';
$string['recommend_themedesignermode'] = 'El modo de diseñador de temas debe estar desactivado en producción para evitar la recompilación de CSS y caídas de rendimiento.';
$string['recommendation'] = 'Recomendación';
$string['recommendations_title'] = 'Comprobaciones rápidas de configuración';
$string['result_status'] = 'Estado';
$string['results_title'] = 'Resultados de las pruebas';
$string['run_benchmark'] = 'Ejecutar benchmark';
$string['status_attention'] = 'Atención';
$string['status_fast'] = 'Rápido';
$string['status_slow'] = 'Lento';
$string['summary_title'] = 'Resumen';
$string['test_db_desc'] = 'Lecturas repetidas de pequeños registros de configuración de la base de datos.';
$string['test_db_name'] = 'Base de datos';
$string['test_files_desc'] = 'Escritura, lectura y eliminación de un archivo temporal local.';
$string['test_files_name'] = 'Sistema de archivos';
$string['test_hash_desc'] = 'Rondas repetidas de SHA-256 para medir el rendimiento bruto de la CPU.';
$string['test_hash_name'] = 'Hash / CPU';
$string['test_json_desc'] = 'Codificación y decodificación de estructuras JSON de tamaño medio.';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'Prueba';
$string['test_string_desc'] = 'Limpieza y análisis simple de contenido similar a HTML.';
$string['test_string_name'] = 'Cadenas / HTML';
$string['time_elapsed'] = 'Tiempo';
$string['total_time'] = 'Tiempo total';
$string['value'] = 'Valor';
$string['xsendfile_value'] = 'Activado ({$a->header}<br>alias: {$a->aliases})';
