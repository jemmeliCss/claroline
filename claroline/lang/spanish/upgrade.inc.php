<?php


/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.1                                              |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
     
      +----------------------------------------------------------------------+
      | Translation to Spanish v.1.5.1                                       |
      | Rodrigo Alejandro Parra Soto , Ing. (e) En Computaci�n eInformatica  |
      | Concepci�n, Chile  <raparra@gmail.com>                               |
      +----------------------------------------------------------------------|



*/



/* P�gina de Bienvenida */

$langTitleUpgrade = "<h2>Herramienta de Actualizaci�n de Claroline<br />\n
                     de 1.4.* a 1.5</h2>\n";

$langDone = "Paso realizado (echo)";
$langTodo = "Pasos por hacer (Steps todo)";
$langAchieved = "Actualizar el proceso de archivado";

/* Step 0 */

$langStep0 = "Confirmar el Respaldo";
$langMakeABackupBefore = "<p>La <em>Herramienta de Actualizaci�n de Claroline</em> respaldar� los datos de la versi�n anterior de la instalaci�n de Claroline
y los har� compatibles con la nueva versi�n de Claroline. Esta actualizaci�n se realizar� en 3 pasos:</p>\n
<ol>\n
<li>Obtendr� su configuraci�n previa de Claroline y las pondr� en los nuevos archivos de configuraci�n</li>\n
<li>Har� que las tablas de claroline (usuario, cursos categor�as, listas de cursos, ...) para hacerlos compatible con la nueva estructura de datos.</li>\n
<li>Actualizar� uno por uno cada datos del curso (directorios, tablas de la base de datos, ...)</li>\n
</ol>\n
<p>Antes de proceder con la actualizaci�n:</p>\n
<table>
<tbody>
<tr valign=\"top\"><td>-</td><td>Respalde Todos sus datos de toda su plataforma (Archivos y bases de datos)</td><td>%s</td></tr>\n
</tbody>
</table>
<p>A usted no se le permitir� comenzar con el proceso de actualizaci�n mientras no haya marcado este punto como 'Echo'.</p>
";
$langConfirm = "Echo";

/* Step 1 */

$langStep1 = "Paso 1 de 3: Configuraci�n de la plataforma principal";
$langIntroStep1 = "<p>La <em>herramienta de actualizaci�n de Claroline</em> est� a punto de actualizar la configuraci�n principal. 
                Esta configuraci�n ser� guardada dentro de claroline/include/config.inc.php dentro de la versi�n anterior de su plataforma.</p>";
$langLaunchStep1 = "<p><button onclick=\"document.location='%s';\">Actualizar la configuraci�n de la plataforma principal</button></p>";

/* Step 2 */

$langStep2 = "Paso 2 de 3: Actualizaci�n de las tablas de la plataforma principal.";
$langIntroStep2 = "<p>Ahora, la <em>herramienta de actualizaci�n de Claroline</em> est� a punto de actualizar los datos guardados dentro de las tablas principales de Claroline  
                    (usuarios, categor�as de cursos, lista de herramienta, ...) y las har� compatibles con la nueva versi�n de Claroline.</p>
                   <p class=\"help\">Nota: Dependiendo de la velocidad del servidor esta tarea podr�a tomar alg�n tiempo  
                   en ser ejecutada.</p>";
$langLaunchStep2 = "<p><button onclick=\"document.location='%s';\">Actualizar las tablas de la plataforma principal.</button></p>";
$langNextStep = "<p><button onclick=\"document.location='%s';\">Siguiente ></button></p>";

/* Step 3 */

$langStep3 = "Paso 3 de 3: Actualizaci�n de los cursos";
$langIntroStep3 = "<p>Ahora, la <em>herramienta de actualizaci�n de Claroline </em> est� a punto de actualizaci�n los datos de los cursos(directorios y tablas de la base de dato) uno por uno.
                   <p class=\"help\">Nota: Dependiendo de la velocidad del servidor esta tarea podr�a tomar alg�n tiempo
                    en ser ejecutada.</p>";
$langLaunchStep3 = "<p><button onclick=\"document.location='%s';\">Actualizar los datos de cursos</button></p>";
$langIntroStep3Run = "<p>La <em>herramienta de actualizaci�n de Claroline </em> proceder� con la actualizaci�n de los datos de los cursos</p>" ;
$langNbCoursesUpgraded = "<p style=\"text-align: center\"><strong>%s cursos de %s han sido actualizados.</strong><br /></p>";

/* stuff for all */

$langYes="si";
$langNo="no";
$langSucceed="Exito!";
$langFailed="<span style=\"color: red\">Fallo!</span>";
$langNextStep = "<p><button onclick=\"document.location='%s';\">Siguiente ></button></p>";

?>