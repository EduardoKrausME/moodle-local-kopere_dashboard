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

$string['action_download'] = 'Descargar';
$string['action_generate_database'] = 'Exportar base de datos';
$string['action_generate_moodledata'] = 'Generar respaldo de moodledata';
$string['cannotopenexportfile'] = 'No se pudo abrir el archivo de exportación: {$a}';
$string['cap_generate'] = 'Generar respaldos';
$string['cap_generate_desc'] = 'Permite a los usuarios generar archivos de respaldo de moodledata y respaldos de la base de datos.';
$string['cap_view'] = 'Ver centro de respaldos';
$string['cap_view_desc'] = 'Permite a los usuarios acceder al centro de respaldos y descargar los archivos generados.';
$string['col_actions'] = 'Acciones';
$string['col_created'] = 'Creado el';
$string['col_file'] = 'Archivo';
$string['col_size'] = 'Tamaño';
$string['col_type'] = 'Tipo';
$string['commandnotfound'] = 'No se encontró el comando del sistema requerido: {$a}.';
$string['current_source_label'] = 'Base de datos actual:';
$string['database_desc'] = 'Exporta la estructura y los datos de la base de datos en PHP, lo que permite elegir el formato de salida y, opcionalmente, separar los registros en su propio archivo.';
$string['database_success'] = 'Exportación de la base de datos generada correctamente: {$a}';
$string['database_title'] = 'Exportación de la base de datos';
$string['emptyfiles'] = 'Aún no se han generado archivos de respaldo.';
$string['exportscope_full'] = 'Base de datos completa';
$string['exportscope_logs'] = 'Solo registros';
$string['exportscope_main'] = 'Base de datos sin registros';
$string['filenotfound'] = 'Archivo de respaldo no encontrado: {$a}';
$string['format_mysql'] = 'MySQL / MariaDB';
$string['format_pgsql'] = 'PostgreSQL';
$string['gzipnotavailable'] = 'La extensión PHP zlib/gzip no está disponible en este servidor.';
$string['history_title'] = 'Archivos generados';
$string['home_kpi_empty_subtitle'] = 'Ningún respaldo generado';
$string['home_kpi_empty_value'] = '-';
$string['home_kpi_subtitle'] = 'Archivo más reciente: {$a}';
$string['home_kpi_title'] = 'Último respaldo';
$string['invalidaction'] = 'La acción solicitada no es válida.';
$string['invalidfilename'] = 'El nombre de archivo especificado no es válido.';
$string['invalidoutputformat'] = 'El formato de salida especificado no es válido: {$a}';
$string['menu_desc'] = 'Generación manual de respaldo de moodledata y exportación de la base de datos';
$string['menu_title'] = 'Respaldos';
$string['moodledata_desc'] = 'Genera un paquete completo de la carpeta moodledata, excluyendo solo la carpeta donde se almacenan los propios respaldos.';
$string['moodledata_success'] = 'Respaldo de moodledata generado correctamente: {$a}';
$string['moodledata_title'] = 'Respaldo de moodledata';
$string['notablesfound'] = 'No se encontraron tablas para exportar.';
$string['outputformat_desc'] = 'Puedes exportar al formato de la base de datos actual o convertir la salida entre MySQL/MariaDB y PostgreSQL.';
$string['outputformat_label'] = 'Formato de salida';
$string['page_title'] = 'Centro de respaldos';
$string['pluginname'] = 'Respaldos';
$string['processfailed'] = 'Error al ejecutar el proceso de respaldo: {$a}';
$string['processstartfailed'] = 'No se pudo iniciar el proceso de respaldo en el servidor.';
$string['separatelogs_desc'] = 'Cuando está habilitado, el sistema genera un paquete ZIP con un archivo para la base de datos principal y otro archivo solo para las tablas de registros.';
$string['separatelogs_label'] = '¿Deseas exportar los registros por separado?';
$string['storage_desc'] = 'Los archivos generados se guardan dentro del área protegida de moodledata.';
$string['storage_title'] = 'Ubicación de almacenamiento';
$string['type_database'] = 'Base de datos';
$string['type_moodledata'] = 'Moodledata';
$string['unknownprocesserror'] = 'El proceso devolvió un error, pero no proporcionó detalles.';
$string['unsupporteddbtype'] = 'Tipo de base de datos no compatible con este plugin de respaldos: {$a}';
$string['zipcreatefailed'] = 'No se pudo crear el archivo ZIP.';
