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
 * @package  local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license  http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['modulename'] = 'Tablero Kopere';
$string['pluginname'] = 'Tablero Kopere';
$string['kopere_dashboard:view'] = 'Ver Tablero Kopere';
$string['kopere_dashboard:manage'] = 'Mánager Tablero Kopere';
$string['dashboard'] = 'Tablero';
$string['settings'] = 'Configuraciones';
$string['close'] = 'Cerrar';
$string['crontask_tmp'] = 'Cron limpiar carpeta tmp';
$string['crontask_performance'] = 'Cron para guardar datos de rendimiento';

$string['kopere_dashboard_open'] = 'Abrir Kopere';
$string['kopere_dashboard_open_desc'] = '¿Qué forma desea abrir el Tablero Kopere';
$string['kopere_dashboard_open_internal'] = 'Abrir interno';
$string['kopere_dashboard_open_popup'] = 'En popup';
$string['kopere_dashboard_open_blank'] = 'En nueva ventana';
$string['kopere_dashboard_open_top'] = 'En la misma ventana';

$string['integracaoroot'] = 'Integraciones';

$string['messageprovider:kopere_dashboard_messages'] = 'Enviar Notificaciones';
$string['kopere_dashboard:emailconfirmsubmission'] = 'Enviar Notificaciones';

$string['open_dashboard'] = 'Abrir Tablero';

$string['dateformat'] = '%d %B %Y, %I:%M %p';
$string['datetime'] = '%d/%m/%Y, %H:%M';
$string['php_datetime'] = 'm/d/Y H:i';

$string['help_title'] = 'Ayuda con esta página';

// Html/tinymce.
$string['blocks'] = 'Bloques';
$string['blocks_paragraph'] = 'Párrafo';
$string['image_alignment'] = 'Alineación de imagen';
$string['image_alignment_left'] = 'Alinear a la Izquierda';
$string['image_alignment_right'] = 'Alinear a la Derecha';
$string['colors'] = 'Colores';
$string['background'] = 'Fondo';
$string['color_red'] = 'Rojo';
$string['color_blue'] = 'Azul';
$string['color_green'] = 'Verde';
$string['color_yellow'] = 'Amarillo';
$string['color_orange'] = 'Naranja';
$string['color_grey'] = 'Gris';
$string['color_purple'] = 'Morado';
$string['color_brown'] = 'Café';
$string['filemanager_title'] = 'Gestor de archivos';

// DataTables.
$string['datatables_sEmptyTable'] = 'Sin registros encontrados';
$string['datatables_sInfo'] = 'Mostrando _START_ a _END_ of _TOTAL_ registros';
$string['datatables_sInfoEmpty'] = 'Mostrando 0 a 0 de 0 registros';
$string['datatables_sInfoFiltered'] = '(Filtrado de _MAX_ records)';
$string['datatables_sInfoPostFix'] = '';
$string['datatables_sInfoThousands'] = '.';
$string['datatables_sLengthMenu'] = '_MENU_ resultados por página';
$string['datatables_sLoadingRecords'] = 'Cargando ...';
$string['datatables_sProcessing'] = 'Procesando ...';
$string['datatables_sZeroRecords'] = 'Sin registros encontrados';
$string['datatables_sSearch'] = 'Buscar:';
$string['datatables_oPaginate_sNext'] = 'Siguiente';
$string['datatables_oPaginate_sPrevious'] = 'Anterior';
$string['datatables_oPaginate_sFirst'] = 'Primero';
$string['datatables_oPaginate_sLast'] = 'Último';
$string['datatables_oAria_sSortAscending'] = ': Ordenar Columnas en orden Ascendente';
$string['datatables_oAria_sSortDescending'] = ': Ordenar Columnas en orden Descendiente';

// Util/navigation.
$string['navigation_page'] = 'Páginae {$a->atualPage} de {$a->countPages}';

// About.
$string['about_title'] = 'Acerca de';
$string['about_project'] = 'Proyecto de código abierto desarrollado y mantenido por';
$string['about_code'] = 'Código disponible en';
$string['about_help'] = 'Ayuda está en';
$string['about_bug'] = 'Si encontró algún problema o quisiera sugerir mejoras, abra un asunto';

// Backup.
$string['backup_title'] = 'Backup';
$string['backup_windows'] = '¡No disponible en servidor Windows!';
$string['backup_hours'] = '¡No correr respaldo en horas pico!';
$string['backup_sleep'] = 'El Respaldo puede tardar varios minutos para ejecutarse.';
$string['backup_newnow'] = 'Crear nuevo respaldo ahora';
$string['backup_newnow'] = 'Crear nuevo respaldo ahora';
$string['backup_noshell'] = 'shell_exec function is disabled!';
$string['backup_list'] = 'Lista de respaldos';
$string['backup_list_file'] = 'Archivo';
$string['backup_list_created'] = 'Creado en';
$string['backup_list_size'] = 'Tamaño';
$string['backup_list_action'] = 'Acción';
$string['backup_none'] = '¡No se encontraron respaldos!';
$string['backup_execute_success'] = '¡Respaldo creado exitosamente!';
$string['backup_execute_exec'] = 'Ejecución de Respaldo';
$string['backup_execute_date'] = 'Fecha de generación:';
$string['backup_execute_database'] = 'Base de datos:';
$string['backup_execute_table'] = 'Corriendo Respaldo de Tabla';
$string['backup_execute_structure'] = 'Estructura para tabla';
$string['backup_execute_dump'] = 'Datos de volcado de tabla';
$string['backup_execute_dump_error'] = 'Tabla de captura de errores';
$string['backup_execute_complete'] = '¡Respaldo completado!';
$string['backup_returnlist'] = 'Regresar a lista de Respaldos';
$string['backup_deletesucessfull'] = '¡Respaldo eliminado exitosamente!';
$string['backup_deleting'] = 'Excluyendo Respaldo';
$string['backup_delete_confirm'] = 'Eliminación de Respaldo';
$string['backup_delete_title'] = '¿Realmente desea eliminar el respaldo <strong>{$a}</strong>';
$string['backup_notound'] = '¡Archivo no encontrado!';

// Report_benchmark.
$string['benchmark_title'] = 'Prueba de desempeño';
$string['benchmark_based'] = 'Basado en plugin';
$string['benchmark_info'] = '<p>Esta prueba puede tardar hasta 1 minuto para ejecutarse.</p><p>Intente hacer más de una prueba para tener un promedio.</p><p>Y, no la corra en horas pico.</p>';
$string['benchmark_execute'] = 'Correr la prueba';
$string['benchmark_executing'] = 'Corriendo la prueba';
$string['benchmark_title2'] = 'Alojando prueba de desempeño';
$string['benchmark_timetotal'] = 'Tiempo total:';
$string['benchmark_decription'] = 'Descripción';
$string['benchmark_timesec'] = 'Tiempo, en segundos';
$string['benchmark_seconds'] = 'segundos';
$string['benchmark_max'] = 'Valor máximo aceptable';
$string['benchmark_critical'] = 'Límite crítico';
$string['benchmark_testconf'] = 'Probar Configuraciones Moodle';
$string['benchmark_testconf_problem'] = 'Problema';
$string['benchmark_testconf_status'] = 'Estatus';
$string['benchmark_testconf_description'] = 'Descripción';
$string['benchmark_testconf_action'] = 'Acción';

$string['cloadname'] = 'Tiempo de carga de Moodle';
$string['cloadmoreinfo'] = 'Correr el archivo de configuración &laquo;config.php&raquo;';
$string['processorname'] = 'Función llamada muchas veces';
$string['processormoreinfo'] = 'Una función es llamada en un bucle para probar la velocidad del procesador';
$string['filereadname'] = 'Leyendo archivos';
$string['filereadmoreinfo'] = 'Probar la velocidad para leer en carpeta temporal de Moodle';
$string['filewritename'] = 'Creando archivos';
$string['filewritemoreinfo'] = 'Probar la velocidad para escribir en carpeta temporal de Moodle';
$string['coursereadmoreinfo'] = 'Probar la velocidad de lectura para leer un curso';
$string['coursereadname'] = 'Leyendo curso';
$string['coursewritemoreinfo'] = 'Probar la velocidad de la base de datos para escribir un curso';
$string['coursewritename'] = 'Escribiendo curso';
$string['querytype1name'] = 'Solicitud compleja (n°1)';
$string['querytype1moreinfo'] = 'Probar la velocidad de la base de datos para ejecutar una solicitud compleja';
$string['querytype2name'] = 'Solicitud compleja (n°2)';
$string['querytype2moreinfo'] = 'Probar la velocidad de la base de datos para ejecutar una solicitud compleja';
$string['loginguestname'] = 'Tiempo para conectar con la cuenta de invitado';
$string['loginguestmoreinfo'] = 'Midiendo el tiempo para cargar la página de ingreso con la cuenta de invitado';
$string['loginusername'] = 'Tiempo para conectar con la cuenta de un usuario falso';
$string['loginusermoreinfo'] = 'Midiendo el tiempo para cargar la página de ingreso con la cuenta de un usuario falso';

// Performancemonitor.
$string ['performancemonitor_cpu'] = 'Uso del CPU';
$string ['performancemonitor_memory'] = 'Memoria';
$string ['performancemonitor_hd'] = 'Moodledata';
$string ['performancemonitor_performance'] = 'Rendimiento';
$string ['performancemonitor_min'] = '{$a} min:';

// Courses.
$string['courses_title'] = 'Cursos';
$string['courses_title1'] = 'Lista de Cursos';
$string['courses_name'] = 'Nombre del curso';
$string['courses_shortname'] = 'Nombre Corto';
$string['courses_visible'] = 'Visible';
$string['courses_invisible'] = 'Oculto';
$string['courses_enrol'] = 'Nº de estudiantes inscritos';
$string['courses_invalid'] = '¡ID de Curso Inválida!';
$string['courses_notound'] = '¡Curso no encontrado!';
$string['courses_sumary'] = 'Resumen';
$string['courses_edit'] = 'Editar';
$string['courses_acess'] = 'Acceso';
$string['courses_titleenrol'] = 'Estudiantes inscritos';
$string['courses_student_name'] = 'Nombre';
$string['courses_student_email'] = 'E-mail';
$string['courses_student_status'] = 'Estatus del registro';
$string['courses_page_title'] = 'Páginas ya creadas';
$string['courses_page_create'] = 'Crear página basada en este resumen';

// Reports.
$string['reports_title'] = 'Reportes';
$string['reports_download'] = 'Descargar estos datos';
$string['reports_selectcourse'] = 'Seleccione el curso para generar el reporte';
$string['reports_notfound'] = '¡Reporte no encontrado!';
$string['reports_reportcat_badge'] = 'Reporte de Insignias';
$string['reports_reportcat_courses'] = 'Reporte de Curso';
$string['reports_reportcat_enrol_cohort'] = 'Reporte de Cohorte';
$string['reports_reportcat_enrol_guest'] = 'Reporte de Visitantes';
$string['reports_reportcat_server'] = 'Reporte del Sistema';
$string['reports_reportcat_user'] = 'Reporte de Usuario';
$string['reports_report_badge-1'] = 'Todas las Insignias disponibles en Moodle';
$string['reports_report_badge-2'] = 'Todas las insignias contenidas por Usuarios';
$string['reports_report_courses-1'] = 'Progreso con porcentaje de finalización';
$string['reports_report_courses-2'] = 'Cursos donde están habilitados grupos';
$string['reports_report_courses-3'] = 'Reporte del acceso al curso';
$string['reports_report_courses-4'] = 'Reporte del acceso al curso con calificaciones';
$string['reports_report_courses-5'] = 'Último acceso al curso';
$string['reports_report_enrol_cohort-1'] = 'Cohortes y usuarios';
$string['reports_report_enrol_guest-1'] = 'Reporte de Ingresos de Invitado';
$string['reports_report_server-1'] = 'Disk Usage Report';
$string['reports_report_user-1'] = 'Número de estudiantes en cada curso';
$string['reports_report_user-2'] = 'Finalización del Curso con Criterios';
$string['reports_report_user-3'] = 'Reporte de acceso de usuario diario';
$string['reports_report_user-4'] = 'Reporte de ingresos de estudiante';
$string['reports_report_user-5'] = 'Usuarios que nunca han ingresado';
$string['reports_report_user-6'] = 'Usuarios que completaron curso';
$string['reports_report_user-7'] = 'Usuarios registrados, que no ingresan al curso';
$string['reports_report_user-8'] = 'Todos los usuarios';
$string['reports_timecreated'] = 'Registrado en';
$string['reports_coursesize'] = 'Archivos del Curso';
$string['reports_modulessize'] = 'Archivos de Módulos';
$string['reports_lastlogin'] = 'Ingresar a';
$string['reports_cohort'] = 'Nombre de Cohortes';
$string['reports_groupnode'] = 'Modo de Grupo';
$string['reports_groupname'] = 'Nombre del Grupo';
$string['reports_datastudents'] = 'Datos del Estudiante';
$string['reports_datacourses'] = 'Datos del Curso';
$string['reports_coursecreated'] = 'Fecha de inscripción';
$string['reports_activitiescomplete'] = 'Actividades Completadas';
$string['reports_activitiesassigned'] = 'Actividades Asignadas';
$string['reports_coursecompleted'] = '% Curso completado';
$string['reports_badgename'] = 'Insignia';
$string['reports_criteriatype'] = 'Criterios';
$string['reports_dateissued'] = 'En';
$string['reports_context'] = 'Contexto';
$string['reports_export'] = 'Exportar a Excel';
$string['reports_noneaccess'] = 'Sin acceso';
$string['reports_access_n'] = 'accesada {$a} veces';

$string['reports_settings_title'] = 'Editar reporte';
$string['reports_settings_form_title'] = 'Editar reporte';
$string['reports_settings_form_enable'] = 'Habilitado?';
$string['reports_settings_form_reportsql'] = 'Reporte SQL';
$string['reports_settings_form_prerequisit'] = 'Prerequisito antes de cargar el reporte';
$string['reports_settings_form_none'] = 'Ninguno';
$string['reports_settings_form_prerequisit_listCourses'] = 'Lista de cursos';
$string['reports_settings_form_prerequisit_badge_status_text'] = 'Cambiar el estado de la Insignia a Texto';
$string['reports_settings_form_prerequisit_badge_criteria_type'] = 'Cambiar el criterio de la Insignia a Texto';
$string['reports_settings_form_prerequisit_userfullname'] = 'Ejecutar nombre completo ($ user) en cada línea del reporte';
$string['reports_settings_form_prerequisit_courses_group_mode'] = 'Poner el modo del grupo en texto';
$string['reports_settings_form_foreach'] = 'Cambiando columnas SQL';
$string['reports_settings_form_colunas'] = 'Columnas';
$string['reports_settings_form_colunas_title'] = 'Título';
$string['reports_settings_form_colunas_key'] = 'columna SQL';
$string['reports_settings_form_colunas_type'] = 'Tipo de datos';
$string['reports_settings_form_colunas_type_int'] = 'Número';
$string['reports_settings_form_colunas_type_date'] = 'Datos';
$string['reports_settings_form_colunas_type_currency'] = 'Divisas';
$string['reports_settings_form_colunas_type_text'] = 'Texto';
$string['reports_settings_form_colunas_type_bytes'] = 'Bytes';
$string['reports_settings_form_colunas_extra'] = '¡Deje vacíos los de abajo si no los necesita!';
$string['reports_settings_savesuccess'] = '¿Guardado exitosamente!';
$string['reports_settings_form_save'] = 'Guardar reporte';

// Dashboard.
$string['dashboard_title_user'] = 'Usuarios / Recursos';
$string['dashboard_title_online'] = 'EnLínea / Última hora';
$string['dashboard_title_course'] = 'Cursos / Visible';
$string['dashboard_title_disk'] = 'Uso de Disco';
$string['dashboard_grade_title'] = 'Notas más recientes';
$string['dashboard_grade_inmod'] = 'en módulo <strong>{$a->itemname}</strong>en curso <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->coursename}</a>';
$string['dashboard_grade_incourse'] = 'en curso <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->coursename}</a>';
$string['dashboard_grade_of'] = 'de';
$string['dashboard_grade_text'] = 'Calificación recibida {$a->grade} en {$a->evaluation}';
$string['dashboard_grade_in'] = 'En';
$string['dashboard_enrol_title'] = 'Última inscripción';
$string['dashboard_enrol_inactive'] = 'la inscripción está inactiva';
$string['dashboard_enrol_active'] = 'la inscripción está activa';
$string['dashboard_enrol_text'] = 'Usted se ha inscrito en el curso <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->fullname}</a> y';
$string['dashboard_enrol_lastmodifield'] = 'Último cambio en';

// Notifications.
$string['notification_title'] = 'Notificaciones';
$string['notification_subtitle'] = '<p>Recibir notificaciones siempre que ocurra una acción en Moodle.</p>';
$string['notification_new'] = 'Nueva notificación';
$string['notification_testsmtp'] = 'Probar si las configuraciones SMTP son correctas.';
$string['notification_testsmtp_message'] = '<p> This is an Email submission test. </p>';
$string['notification_testsmtp_error'] = 'Moodle does not send email when recipient and sender are the same! <br> And you are the main administrator of this moodle. So to test you must log in with another administrator.';
$string['notification_testsmtp_subject'] = 'Testing Email Submission - ';
$string['notification_table_module'] = 'Módulo';
$string['notification_table_action'] = 'Acción';
$string['notification_table_subject'] = 'Asunto';
$string['notification_table_active'] = 'Activo';
$string['notification_table_empty'] = '¡Sin notificación!';
$string['notification_add_module'] = '¿De qué módulo desea Usted recibir notificaciones?';
$string['notification_add_moduledesc'] = '¡Módulos / Actividades No-usadas no aparecen!';
$string['notification_add_selectmodule'] = '¡Seleccionar Módulo!';
$string['notification_add_action'] = '¿De qué acción desea Usted recibir notificaciones?';
$string['notification_add_create'] = 'Crear notificación';
$string['notification_notound'] = '¡Notificación no encontrada!';
$string['notification_editing'] = 'Editando Notificación';
$string['notification_from'] = 'De';
$string['notification_fromdesc'] = '¿Quién será el remitente del mensaje?';
$string['notification_from_admin'] = 'Administrador del Sitio';
$string['notification_to'] = 'Para';
$string['notification_todesc'] = '¿Quién recibirá estos mensajes?';
$string['notification_todesc_admin'] = 'Administrador del sitio (Primario solamente)';
$string['notification_todesc_admins'] = 'Administradores del sitio (Todos los Administradores)';
$string['notification_todesc_teachers'] = 'Profesores del curso (Solamente si es dentro de un curso)';
$string['notification_todesc_student'] = 'El estudiante (Enviar al estudiante que hizo la acción)';
$string['notification_status'] = 'Estatus';
$string['notification_statusdesc'] = '¡Si Usted quier detener las notificaciones, marcar como "Idle" (ociosa) y guardar!';
$string['notification_status_active'] = 'Activo';
$string['notification_status_inactive'] = 'Inactivo';
$string['notification_subject'] = 'Asunto';
$string['notification_subjectdesc'] = 'Asunto del mensaje';
$string['notification_message_html'] = '<p>Hola {[to.fullname]},</p><p>&nbsp;</p><p>Att,<br>{[from.fullname]}.</p>';
$string['notification_message'] = 'Mensaje';
$string['notification_update'] = 'Actualizar alerta';
$string['notification_create'] = 'Crear alerta';
$string['notification_created'] = '¡Notificación creada!';
$string['notification_notfound'] = '¡Notificación no encontrada!';
$string['notification_delete_success'] = '¡Notificación eliminada exitosamente!';
$string['notification_delete_yes'] = '¿Realmente quiere eliminar esta Notificación?';
$string['notification_setting_config'] = 'Configuraciones de Email';
$string['notification_setting_template'] = 'Plantilla';
$string['notification_setting_templatelocation'] = 'Plantillas están en la carpeta';
$string['notification_setting_preview'] = 'Vista previa';
$string['notification_manager'] = 'Gestionar Mensajes';
$string['notification_core_course_category'] = 'Categoría de Curso';
$string['notification_core_course'] = 'Cursos';
$string['notification_core_user'] = 'Usuarios';
$string['notification_core_user_enrolment'] = 'Registro de Usuario';
$string['notification_local_kopere_dashboard'] = 'Tablero Kopere';
$string['notification_local_kopere_hotmoodle'] = 'HotMoodle Kopere';
$string['notification_local_kopere_moocommerce'] = 'MooCommerce Kopere';
$string['notification_local_kopere_dashboard_payment'] = 'Pago Kopere';
$string['notification_error_smtp'] = '<p>Para que los estudiantes reciban los mensajes, SMTP debe ser configurado.</p>
          <p><a href="https://moodle.eduardokraus.com/configurar-o-smtp-no-moodle"
             target="_blank">Leer aquí como configurar SMTP</a></p>
          <p><a href="{$a->wwwroot}/admin/settings.php?section={$a->mail}"
              target="_blank">Hacer click aquí para configurar la salida de Email</a></p>';

// Profile.
$string['profile_invalid'] = '¡ID de usuario inválida!';
$string['profile_notfound'] = '¡Usuario no encontrado!';
$string['profile_access'] = 'Acceder como';
$string['profile_access_first'] = 'Primer acceso en:';
$string['profile_access_last'] = 'Último acceso en:';
$string['profile_access_lastlogin'] = 'Último acceso en:';
$string['profile_access_title'] = 'Acceso';
$string['profile_courses_title'] = 'Cursos Registrados';
$string['profile_edit'] = 'Editar';
$string['profile_enrol_active'] = 'Registro está activo';
$string['profile_enrol_expires'] = 'Caduca en';
$string['profile_enrol_inactive'] = 'Registro está inactivo';
$string['profile_enrol_notexpires'] = 'y nunca caduca';
$string['profile_enrol_profile'] = 'Perfiles';
$string['profile_enrol_start'] = 'Iniciar en';
$string['profile_link_edit'] = 'Editar Perfil';
$string['profile_link_profile'] = 'Ver Perfil';
$string['profile_link_title'] = 'Enlaces Útiles';
$string['profile_notenrol'] = '¡El usuario no tiene registro!';
$string['profile_title'] = 'Usuarios';
$string['profile_userdate_title'] = 'Datos';

$string['color_blue'] = 'Azul';
$string['color_brown'] = 'Café';
$string['color_green'] = 'Verde';
$string['color_grey'] = 'Gris';
$string['color_orange'] = 'Naranja';
$string['color_purple'] = 'Morado';
$string['color_red'] = 'Rojo';
$string['color_yellow'] = 'Amarillo';
$string['colors'] = 'Colores';
$string['dashboard_enrol_active'] = 'el registro está activo';
$string['dashboard_enrol_inactive'] = 'el registro está inactivo';
$string['dashboard_enrol_lastmodifield'] = 'Úlltimo cambio en';
$string['dashboard_enrol_text'] = 'Usted se ha inscrito en el curso <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->fullname}</a> y';
$string['dashboard_enrol_title'] = 'Última inscripción';
$string['dashboard_grade_in'] = 'En';
$string['dashboard_grade_incourse'] = 'en curso <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->coursename}</a>';
$string['dashboard_grade_inmod'] = 'en módulo <a href="?classname=courses&method=details&courseid={$a->courseid}">{$a->coursename}</a>';
$string['dashboard_grade_of'] = 'de';
$string['dashboard_grade_text'] = 'Recibió nota/calificación {$a->grade} en {$a->evaluation}';
$string['dashboard_grade_title'] = 'Últimas calificaciones';
$string['dashboard_title_course'] = 'Cursos / Visible';
$string['dashboard_title_disk'] = 'Uso de Disco';
$string['dashboard_title_online'] = 'En-línea / Última hora';
$string['dashboard_title_user'] = 'Usuarios / Bienes';
$string['filemanager_title'] = 'Gestor de Archivos';
$string['loginguestmoreinfo'] = 'Midiendo el tiempo para cargar la página de ingreso con la cuenta de invitado';
$string['loginguestname'] = 'Tiempo para conectar con la cuenta de invitado';
$string['loginusermoreinfo'] = 'Midiendo el tiempo para cargar la página de ingreso con una cuenta falsa de usuario';
$string['loginusername'] = 'Tiempo para conectar con una cuenta falsa de usuario';
$string['navigation_page'] = 'Página {$a->atualPage} de {$a->countPages}';
$string['querytype1moreinfo'] = 'Probar la velocidad de la base de datos para ejecutar una solicitud compleja';
$string['querytype1name'] = 'Solicitud compleja (n°1)';
$string['querytype2moreinfo'] = 'Probar la velocidad de la base de datos para ejecutar una solicitud compleja';
$string['querytype2name'] = 'Solicitud compleja (n°2)';
$string['setting_saved'] = '¡configuraciones gruardadas!';
$string['user_table_celphone'] = 'Mobile';
$string['user_table_city'] = 'Ciudad';
$string['user_table_email'] = 'E-mail';
$string['user_table_fullname'] = 'Nombre';
$string['user_table_phone'] = 'Teléfono fijo';
$string['user_table_username'] = 'Nombre_de_usuario';
$string['user_title'] = 'Usuarios';
$string['userenrolment_created'] = 'Inscripción creada en';
$string['userenrolment_edit'] = 'Editar fecha de inscripción';
$string['userenrolment_notfound'] = '¡Inscripción de usuario no encontrada!';
$string['userenrolment_status'] = 'La inscripción está';
$string['userenrolment_status_active'] = 'Activa';
$string['userenrolment_status_inactive'] = 'Inactiva';
$string['userenrolment_timeend'] = 'La inscripción termina en';
$string['userenrolment_timeendstatus'] = 'Habilitar período de inscripción';
$string['userenrolment_timestart'] = 'La inscripción inicia en';
$string['userenrolment_updated'] = 'Última modificación de inscripción en';
$string['userenrolment_updatesuccess'] = '¡Inscripción cambiada exitosamente!';
$string['userimport_colname'] = 'Columna {$a}';
$string['userimport_colselect'] = '..::Seleccionar columna::..';
$string['userimport_courseenrol'] = 'Inscribir en un curso';
$string['userimport_courseenrol_desc'] = 'Si Usted quiere que el estudiante sea inscrito en un curso, seleccione la columna identificadora del curso.';
$string['userimport_cript'] = '--encriptado--';
$string['userimport_datanotok'] = 'No está OK, se me olvidó algo';
$string['userimport_dataok'] = 'Datos OK, Insertar en Moodle';
$string['userimport_date_desc'] = 'El sistema detecta automáticamente el formato principal de fecha.';
$string['userimport_empty'] = 'Si Usted no lo selecciona, se usará el valor por defecto de "{$a}"';
$string['userimport_event_import_course_enrol_message'] = '<p>Hola {[to.fullname]},</p> <p>Usted ha sido inscrito exitosamente en {[course.fullname]}. Ahora Usted puede ingresar al área de estudiante y comenzar a estudiar cuando y donde lo desee.</p> <p>Es con gran satisfacción que {[moodle.fullname]} le da la BienVenida.</p> <p>Acceda a {{course.link}}, y estudie.</p> <p>Si tiene dudas, con gusto se las resolveremos.</p> <p>Atentamente,<br> El Equipo de Soporte</p>';
$string['userimport_event_import_course_enrol_subject'] = 'Bienvenido BienVenido - {[course.fullname]}';
$string['userimport_event_import_user_created_and_enrol_message'] = '<p>Hola {[to.fullname]},</p> <p>Usted ha sido inscrito exitosamente en {[course.fullname]}. Ahora Usted puede ingresar al área de estudiante y comenzar a estudiar cuando y donde lo desee.</p> <p>Ahora, yo lo invito a que ingrese al  área de estudiantee con los datos siguientes:</p> <p><strong>Sitio:</strong> {[moodle.link]}<br> <strong>Nombre_de_usuario:</strong> {[to.username]}<br> <strong>Contraseña:</strong> {[to.password]}</p> <p>Si tiene dudas, con gusto se las resolveremos..</p> <p>Atentamente,<br> l Equipo de Soporte</p>';
$string['userimport_event_import_user_created_and_enrol_subject'] = 'BienVenido BienVenido - {[course.fullname]}';
$string['userimport_event_import_user_created_subject'] = '¡BienVenido! - {[moodle.fullname]}';
$string['userimport_filenotfound'] = 'No se encontró el archivo "{$a}" ¡Subir CSV nuevamente!';
$string['userimport_first10'] = 'Primeros 10 registros de su CSV';
$string['userimport_firstname'] = 'Nombre o nombre completo';
$string['userimport_import_course_enrol_name'] = 'Usuario importado fue inscrito en el Curso';
$string['userimport_import_user_created_and_enrol_name'] = 'Usuario importado, registrado en Moodle y curso';
$string['userimport_import_user_created_name'] = 'Usuario importado y registrado en Moodle';
$string['userimport_inserted'] = 'Usuario ingresado';
$string['userimport_linkall'] = 'Haga click aquí para ver todos los registros CSV';
$string['userimport_messages'] = 'Mensajes que los estudiantes recibirán durante la importación';
$string['userimport_moveuploadedfile_error'] = '¡ERROR al mover archivo!';
$string['userimport_noterror'] = 'No se encontró error';
$string['userimport_passcreate'] = '--Será creado--';

// Acessos dos usuários
$string['useraccess_title'] = 'Acceso del usuario';

$string['userimport_title'] = 'Importar Usuarios';
$string['userimport_title_proccess'] = 'Procesando archivo "{$a}"';
$string['userimport_upload'] = 'Arrastrar aquí archivos CSV o hacer click para abrir la caja de búsqueda.';
$string['userimport_userdata'] = 'Datos del Usuario';
$string['userimport_userfields'] = 'Campos extra del perfil';
$string['userimport_wait'] = 'Por favor espere a que los datos sean procesados. Después de procesarlos, estará disponible una hoja de cálculo con datos insertados.';
$string['useronline_settings_port'] = 'Puerto del servidor';
$string['useronline_settings_ssl'] = '¿Habilitar SSL?';
$string['useronline_settings_status'] = 'Habilitar Servidor de Sincronización de Usuarios En-línea';
$string['useronline_settings_url'] = 'URL del Servidor';
$string['useronline_subtitle'] = 'Abrir pestañas con Moodle';
$string['useronline_table_date'] = 'Datos';
$string['useronline_table_device'] = 'Dispositivo';
$string['useronline_table_focus'] = 'Foco';
$string['useronline_table_fullname'] = 'Nombre';
$string['useronline_table_navigator'] = 'Navegador';
$string['useronline_table_os'] = 'Sistema Operativo';
$string['useronline_table_page'] = 'Página';
$string['useronline_title'] = 'Usuarios En línea';
$string['webpages_allpages'] = 'Todas las páginas';
$string['webpages_menu_create'] = 'Crear nuevo Menú';
$string['webpages_menu_created'] = '¡Menú creado!';
$string['webpages_menu_delete'] = 'Excluyendo menú';
$string['webpages_menu_nodelete'] = 'No se puede eliminar un menú que tiene páginas registradas!';
$string['webpages_menu_deleted'] = '¡Menú eliminado exitosamente!';
$string['webpages_menu_edit'] = 'Editando Menú';
$string['webpages_menu_error'] = '¡Deben llenarse todos los datos!';
$string['webpages_menu_help'] = 'Ayuda con Menúes';
$string['webpages_menu_new'] = 'Nuevo Menú';
$string['webpages_menu_save'] = 'Guardar';
$string['webpages_menu_subtitle'] = 'Menú Estático';
$string['webpages_menu_title'] = 'Título del Menú';
$string['webpages_menu_link'] = 'Enlace del Menú';
$string['webpages_menu_updated'] = '¡Menú actualizado!';
$string['webpages_page_course'] = 'Curso Enlazado';
$string['webpages_page_crash'] = 'Si Usted cambia la URL de Moodle y la imagen le da un CRASH (se cae), haga click aquí';
$string['webpages_page_create'] = 'Crear nueva página';
$string['webpages_page_created'] = '¡Página creada!';
$string['webpages_page_delete'] = 'excluyendo Página';
$string['webpages_page_deleted'] = '¡Página eliminada exitosamente!';
$string['webpages_page_edit'] = 'Editar a página';
$string['webpages_page_menu'] = 'Menú';
$string['webpages_page_new'] = 'Nueva página';
$string['webpages_page_notfound'] = '¡Página no encontrada!';
$string['webpages_page_nomenudelete'] = '<p>Este menú tiene páginas internas y no se puede borrar!</p>';
$string['webpages_page_confirmdeletemenu'] = '<p>¿Desea realmente eliminar el menú <strong>{$a}</strong>?</p>';
$string['webpages_page_save'] = 'Guardar página';
$string['webpages_page_settigs'] = 'Configuraciones de Páginas Estáticas';
$string['webpages_page_title'] = 'Título';
$string['webpages_page_updated'] = '¡Página actualizada!';
$string['webpages_page_view'] = 'Ver página';
$string['webpages_subtitle'] = 'Menúes de Navegación';
$string['webpages_table_order'] = 'Orden';
$string['webpages_table_text'] = 'Texto';
$string['webpages_table_theme'] = 'Diseño';
$string['webpages_table_title'] = 'Título';
$string['webpages_table_visible'] = 'Visible';
$string['webpages_theme_base'] = 'El diseño sin los bloques';
$string['webpages_theme_frametop'] = 'Sin bloques y pié de página mínimo';
$string['webpages_theme_frontpage'] = 'Diseño de la página inicial del sitio.';
$string['webpages_theme_popup'] = 'Sin navegación, sin bloques, sin encabezado';
$string['webpages_theme_print'] = 'Solamente debe mostrar contenido y encabezados básicos';
$string['webpages_theme_report'] = 'El diseño de la página usada para reportes';
$string['webpages_theme_standard'] = 'Diseño por defecto con bloques';
$string['webpages_title'] = 'Páginas Estáticas';

$string['privacy:metadata'] = 'El complemento de Kopere Dashboard no almacena ningún dato personal.';
