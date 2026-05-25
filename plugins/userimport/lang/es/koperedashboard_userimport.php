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

$string['back_mapping'] = 'Volver al mapeo';
$string['cannotreadcsv'] = 'No se pudo leer el archivo CSV.';
$string['cap_manage'] = 'Gestionar importaciones de usuarios';
$string['cap_manage_desc'] = 'Subir archivos CSV, previsualizar filas y ejecutar importaciones de usuarios dentro de Kopere Dashboard.';
$string['confirm_import'] = 'Importar usuarios ahora';
$string['course_not_found'] = 'Curso no encontrado.';
$string['customfields'] = 'Campos personalizados del perfil';
$string['filename'] = 'Archivo';
$string['group_not_found'] = 'Grupo no encontrado en el curso seleccionado.';
$string['idnumbercourse'] = 'Número de identificación del curso';
$string['invalidcsv'] = 'El archivo no parece ser un CSV válido con encabezados.';
$string['invalidemail'] = 'Dirección de correo electrónico no válida.';
$string['invalidfiletype'] = 'Solo se aceptan archivos .csv o .txt.';
$string['invalidtoken'] = 'El token de importación no es válido o ha caducado.';
$string['invalidusername'] = 'Valor de nombre de usuario no válido.';
$string['manage_intro'] = 'Sube un archivo CSV para crear usuarios, reutilizar cuentas existentes, completar campos personalizados del perfil y, opcionalmente, matricularlos en cursos y grupos.';
$string['manualinstance_missing'] = 'El curso {$a} no tiene una instancia de matriculación manual habilitada.';
$string['manualplugin_missing'] = 'El plugin de matriculación manual no está disponible en este sitio Moodle.';
$string['mappingerror_email'] = 'Mapea la columna de correo electrónico.';
$string['mappingerror_firstname'] = 'Mapea la columna de nombre (o la columna de nombre completo).';
$string['menu_desc'] = 'Importa usuarios mediante CSV, previsualiza el resultado, crea cuentas y, opcionalmente, matricúlalos en cursos/grupos.';
$string['menu_title'] = 'Importación de usuarios';
$string['missingemail'] = 'Falta el correo electrónico.';
$string['missingfirstname'] = 'Falta el nombre.';
$string['missingtempfile'] = 'El archivo CSV temporal ya no existe.';
$string['missingusername'] = 'Falta el nombre de usuario.';
$string['pluginname'] = 'Importación de usuarios';
$string['preview_intro'] = 'Revisa las primeras filas, mapea cada columna del CSV y ejecuta una previsualización antes de importar.';
$string['preview_title'] = 'Previsualización y mapeo';
$string['result_alreadyenrolled'] = 'Ya matriculado';
$string['result_courseenrolled'] = 'Matriculado en {$a}';
$string['result_groupadded'] = 'Añadido al grupo {$a}';
$string['result_usercreated'] = 'Usuario creado';
$string['result_userexists'] = 'El usuario ya existía';
$string['run_preview'] = 'Ejecutar previsualización';
$string['saveuploaderror'] = 'No se pudo guardar el archivo subido en el almacenamiento temporal de Moodle.';
$string['select_column'] = 'Selecciona una columna';
$string['separator'] = 'Separador detectado';
$string['separator_comma'] = 'Coma (,)';
$string['separator_semicolon'] = 'Punto y coma (;)';
$string['separator_tab'] = 'Tabulación';
$string['shortnamecourse'] = 'Nombre corto/nombre completo del curso';
$string['start_over'] = 'Iniciar una nueva importación';
$string['status_created'] = 'Creado';
$string['status_error'] = 'Error';
$string['status_existing'] = 'Usuario existente';
$string['status_ok'] = 'Listo';
$string['status_willcreate'] = 'Se creará';
$string['summary_create'] = 'Se creará';
$string['summary_created'] = 'Usuarios creados';
$string['summary_enrolled'] = 'Nuevas matriculaciones';
$string['summary_errors'] = 'Errores';
$string['summary_existing'] = 'Existentes';
$string['summary_total'] = 'Filas';
$string['summary_withcourse'] = 'Filas con curso';
$string['table_course'] = 'Curso';
$string['table_email'] = 'Correo electrónico';
$string['table_firstname'] = 'Nombre';
$string['table_group'] = 'Grupo';
$string['table_lastname'] = 'Apellido';
$string['table_line'] = 'Línea';
$string['table_message'] = 'Mensaje';
$string['table_status'] = 'Estado';
$string['table_username'] = 'Nombre de usuario';
$string['tip_detectseparator'] = 'El plugin detecta automáticamente archivos separados por punto y coma, coma y tabulación.';
$string['tip_existing'] = 'Los usuarios existentes no se duplican. Aún pueden recibir datos de campos personalizados y matriculaciones en cursos.';
$string['tip_headers'] = 'La primera fila del CSV se trata como la fila de encabezados.';
$string['tip_password'] = 'Si un nuevo usuario no tiene contraseña o tiene una muy corta, se genera una contraseña aleatoria y se activa el cambio obligatorio de contraseña.';
$string['upload_csv'] = 'Subir archivo CSV';
$string['upload_submit'] = 'Continuar';
$string['uploaderror'] = 'Envía un archivo CSV válido.';
