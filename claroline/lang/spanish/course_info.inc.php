<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.*                              |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   $Id$              |
      |   castellana translation                                               |
      +----------------------------------------------------------------------+

      +----------------------------------------------------------------------+
      | Translator:                                                          |
      | Version castellana de los interfaces:                                |
      |          Xavier Casassas Canals <xcc@ics.co.at>                      |
      +----------------------------------------------------------------------+
      | Translation to Spanish v.1.4                                         |
      | e-learning dept CESGA <teleensino@cesga.es >                         |
      +----------------------------------------------------------------------|
      | Translation to Spanish v.1.5.1                                       |
      | Rodrigo Alejandro Parra Soto , Ing. (e) En Computaci�n eInformatica  |
      | Concepci�n, Chile  <raparra@gmail.com>                               |
      +----------------------------------------------------------------------|
 */

// GENERIC

$langModify   = "modificar";
$langDelete   = "borrar";
$langTitle    = "T&iacute;tulo";
$langHelp     = "ayuda";
$langOk       = "validar";
$langAddIntro = "A&ntilde;adir un texto de introducci&oacute;n";
$langBack     = "Volver a los par&aacute;metros del curso";
$langBackH    = "P&aacute;gina principal del curso";


// infocours.php

$langModifInfo        = "Modificar las Caracter&iacute;sticas del curso";
$langModifDone        = "Las caracter&iacute;sticas han sido modificadas";
$langHome             = "Volver a la p&aacute;gina principal";
$langCode             = "C&oacute;digo del curso";
$langDelCourse        = "Suprimir este curso";
$langProfessor="Profesor";
$langProfessors       = "Titulares";
$langTitle            = "Titulado";
$langFaculty          = "Facultad";
$langDescription      = "Descripci&oacute;n";
$langConfidentiality  = "Confidencialidad";
$langPublicAccess           = "Acceso p&uacute;blico (a partir de la p&acute;gina principal del Campus) sin identificaci&oacute;n";
$langPrivOpen         = "Acceso privado, inscripci&oacute;n abierta";
$langPrivateAccess          = "Acceso privado, inscripci&oacute;n cerrada (web reservada a las personas que aparecen en la lista <a href=../user/user.php>usuarios</a>)";
$langForbidden        = "Usted no est&aacute; registrado como responsable en este curso";
$langLanguage         = "Idioma";
$langConfTip          = "Por defecto, su curso solamente es accesible
para usted, usted es el &uacute;nico usuario. Si usted desea un m&iacute;nimo de confidencialidad, lo m&aacute;s simple es abrir
la inscripci&oacute;n durante una semana, pedir a los estudiantes que se inscriban ellos mismos,
despu&eacute;s cerrar la inscripci&oacute;n y verificar en la lista de los usuarios lo eventuales intrusos.";
$langTipLang          = "Este lenguaje ser&aacute; v&aacute;lido para todos los visitantes de la web de su curso.";


// Change Home Page
$langAgenda             = "Agenda";
$langLink               = "Enlaces";
$langDocument           = "Documentos";
$langVid                = "V&iacute;deo";
$langWork               = "Trabajos";
$langProgramMenu        = "Cuaderno de actividades";
$langAnnouncement       = "Anuncios";
$langUser               = "Usuarios";
$langForum              = "Foros";
$langExercise           = "Ejercicios";
$langStats              = "Estad&iacute;sticas";
$langGroups ="Grupos";
$langChat ="Debate";
$langUplPage            = "Introducir una p&aacute;gina y enlazarla a la principal";
$langLinkSite           = "A&ntilde;adir un enlace a  la web en la p&aacute;gina principal";
$langModifGroups="Grupos";
// delete_course.php


$langDelCourse   = "Suprimir la web de este curso";
$langCourse      = "El curso ";
$langHasDel      = "ha sido suprimido";
$langBackHomeOf    = "Volver a la p&aacute;gina principal de ";
$langByDel       = "Si suprime esta web, usted suprimir&aacute; todos los documentos que contiene y desinscribir&aacute; a todos los estudiantes que est&aacute;n inscritos al mismo. <p>Est&aacute; usted seguro de que quiere suprimir el curso";
$langY           = "SI";
$langN           = "NO";

$langDepartmentUrl = "URL del Departamento";
$langDepartmentUrlName = "Departamento";
$langDescriptionCours  = "Descripci&oacute;n del Curso";

$langArchiveCourse = "Backup del Curso";
$langCreatedIn = "creado en";
$langCreateMissingDirectories ="Creaci&oacute;n de los directorios que faltan";
$langCopyDirectoryCourse = "Copiar los archivos del curso";
$langDisk_free_space = "espacio libre";
$langBuildTheCompressedFile ="Creaci&oacute;n de backups de los archivos";
$langFileCopied = "archivo copiado";
$langArchiveLocation="Localizaci&oacute;n del archivo";
$langSizeOf ="tama�o de";
$langArchiveName ="Nombre del Archivo";
$langBackupSuccesfull = "Backup realizada";
$langBUCourseDataOfMainBase = "Backup de los datos del curso en la base de datos principal";
$langBUUsersInMainBase = "Backup de los datos de los usuarios en la base de datos principal";
$langBUAnnounceInMainBase="Backup de los datos de los anuncios en la base de datos principal";
$langBackupOfDataBase="Backup de la base de datos";
$langBackupCourse="Guarda este curso";

$langCreationDate = "Creado";
$langExpirationDate  = "Finalizado";
$langPostPone = "Aplazado";
$langLastEdit = "&Uacute;ltima edici&oacute;n";
$langLastVisit = "&Uacute;ltima Visita";

$langSubscription="Subscripci&oacute;n";
$langCourseAccess="Acceso al curso";

$langDownload="Descargar";
$langConfirmBackup="Realmente quieres regresar al curso?";



///////////////////////////////////////////////////////////////////////////////////////
//agregador por Rodrigo Parra Soto
$langEditToolList="Editar lista herramientas";
$langIntroCourse="Usted est� en la p�gina principal de los cursos.<br><br>En esta p�gina , Usted puede :
<li class=HelpText>Activar o desactivar herramientas(Presionar en el '".$langEditToolList."' bot�n del lado inferior izquierdo).
<li class=HelpText>Cambiar la configuraci�n o ver las estadisticas(Presionar el correspondiente enlace de la parte inferior).<BR><BR>
Ahora, para agregar un texto introductorio para presentar su curso a sus estudiantes, presione este bot�n";
$langEmail="E-mail";
$langArchive="Archivar";


// course_home_edit.php
$langIntroEditToolList="Seleccionar la herramienta que quiere que los usuarios puedan ver.
La herramienta invisible ser� vista en forma difuminada en su interfaz personal";
$langTools="Herramientas";
$langActivate="Activar";
$langAddExternalTool="Agregar un enlace externo";
$langAddedExternalTool="Enlace externo agregado.";
$langUnableAddExternalTool="Imposible de agregar una herramienta externa";
$langMissingValue="Valor perdido";
$langExternalToolName="Nombre del enlace";
$langExternalToolUrl="URL del enlace";
$langChangedTool="El acceso a la herramienta ha cambiado Tool accesses changed";
$langUnableChangedTool="Imposible de cambiar el acceso de la herramienta";
$langUpdatedExternalTool="Actualizacion de la herrramienta externa";
$langUnableUpdateExternalTool="Imposible actualizar la herramienta externa";
$langDeletedExternalTool='Herramienta externa eliminada';
$langUnableDeleteExternalTool='Imposible eliminar la herramienta externa';
$langAdministrationTools="Administraci�n";
$langRestoreCourse = "Restore a course";  // espa�ol por raparra
$langRestore="Recuperar";					// espa�ol por raparra
$langCreateSite="crear una p�gina web de un curso";
$langRestoreDescription="El curso est� en archivo archivado el cual puede seleccionar abajo.<br><br>
Cuando presione en &quot;Restore&quot;, El archivo ser� descomprimido y el curso recreado.";
$langRestoreNotice="Este script no permite ahun recobrar automaticamente a los usuarios, pero los datos guardados en &quot;users.csv&quot; son sificientes como para que el administrador pueda hacer el trabajo manualmente.";
$langAvailableArchives="Lista de archivos disponibles";
$langNoArchive="Ning�n archivo ha sido seleccionado";
$langArchiveNotFound="El archivo no ha sido encontrado";
$langArchiveUncompressed="El archivo ha sido descomprimido e instalado.";
$langCsvPutIntoDocTool="El archivo &quot;users.csv&quot; ha sido puesto dentro de la herramienta de documentos.";
$langSearchCours	= "Volver a la informaci�n del curso";
$langManage			= "Administrar Campus";
$langAreYouSureToDelete = "Est� seguro que lo desea eliminar ";
$langBackToAdminPage = "Volver a la p�gina de administraci�n";
$langToCourseSettings = "Volver a configuraci�n de cursos";
$langSeeCourseUsers = "Ver los usuarios de los cursos";
$langBackToCourseList = "Volver  a la lista de cursos";
$langBackToList = "Volver a la lista";
$langAllUsersOfThisCourse = "Miembros del curso";
$langViewCourse = "Ver curso";

?>
