<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.*
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004, 2003 Universite catholique de Louvain (UCL)|
      +----------------------------------------------------------------------+
      |   Este programa es software libre; usted puede redistribuirlo y/o    | 
      |   modificarlo bajo los t�rminos de la Licencia P�blica General (GNU) | 
      |   como fu� publicada por la Fundaci�n de Sofware Libre; desde la     |
      |   versi�n 2 de esta Licencia o (a su opci�n) cualquier versi�n       |
      |   posterior.                                                         |
      |   Este programa es distribu�do con la esperanza de que sea �til,     |
      |   pero SIN NINGUNA GARANTIA; sin ninguna garant�a impl�cita de       |
      |   MERCATIBILILIDAD o ADECUACI�N PARA PROPOSITOS PARTICULARES.        |
      |   Vea la Licencia P�blica General GNU por m�s detalles.              |
      |   Usted pudo haber recibido una copia de la Licencia P�blica         |
      |   General GNU junto con este programa; sino, escriba a la Fundaci�n  |
      |   de Sofware Libre : Free Software Foundation, Inc., 59 Temple Place |
      |   - Suite 330, Boston, MA 02111-1307, USA. La licencia GNU GPL       |
      |   tambi�n est� disponible a trav�s de la world-wide-web en la        |
      |   direcci�n  http://www.gnu.org/copyleft/gpl.html                    |
      +----------------------------------------------------------------------+
      | Autores: Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Hugues Peeters    <peeters@ipm.ucl.ac.be>                   |
      |          Christophe Gesch� <gesche@ipm.ucl.ac.be>                    |
      |          Olivier Brouckaert <oli.brouckaert@skynet.be>               |
      +----------------------------------------------------------------------+
      | Traducci�n :                                                         |
      |          Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Andrew Lynn       <Andrew.Lynn@strath.ac.uk>                |
      |          Olivier Brouckaert <oli.brouckaert@skynet.be>               |
      +----------------------------------------------------------------------+
      | Basado en la traducci�n al castellano de                             |
      |          Xavier Casassas Canals <xcc@ics.co.at>                      |
      | Adaptado al espa�ol latinoamericano en Agosto-2003 por               |
      |          Carlos Brys       <brys@fce.unam.edu.ar>                    |
      +----------------------------------------------------------------------+
 */

$englishLangName = "spanish";
$localLangName = "espa�ol";

$iso639_2_code = "es";
$iso639_1_code = "esp";

$langNameOfLang['arabic'		]="�rabe";
$langNameOfLang['brazilian'		]="portugu�s";
$langNameOfLang['bulgarian'		]="bulgarian";
$langNameOfLang['croatian'		]="croato";
$langNameOfLang['dutch'			]="dutch";
$langNameOfLang['english'		]="ingl�s";
$langNameOfLang['finnish'		]="finland�s";
$langNameOfLang['french'		]="franc�s";
$langNameOfLang['german'		]="alem�n";
$langNameOfLang['greek'			]="griego";
$langNameOfLang['italian'		]="italiano";
$langNameOfLang['japanese'		]="japon�s";
$langNameOfLang['polish'		]="polaco";
$langNameOfLang['simpl_chinese'	]="chino";
$langNameOfLang['spanish'		]="espa�ol";
$langNameOfLang['spanish_latin'	]="espa�ol latin";
$langNameOfLang['swedish'		]="sueco";
$langNameOfLang['thai'			]="thailand�s";
$langNameOfLang['turkish'		]="turco";


$charset = 'iso-8859-1';
$text_dir = 'ltr'; // ('ltr' para izq a der, 'rtl' para der a izq)
$left_font_family = 'verdana, helvetica, arial, geneva, sans-serif';
$right_font_family = 'helvetica, arial, geneva, sans-serif';
$number_thousands_separator = '.';
$number_decimal_separator = ',';
$byteUnits = array('Bytes', 'Kb', 'Mb', 'Gb');

$langDay_of_weekNames['init'] = array('D', 'L', 'M',' M', 'J', 'V', 'S');
$langDay_of_weekNames['short'] = array('Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab');
$langDay_of_weekNames['long'] = array('Domingo', 'Lunes', 'Martes', 'Mi�rcoles', 'Jueves', 'Viernes', 'S�bado');

$langMonthNames['init']  = array('E', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D');
$langMonthNames['short'] = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
$langMonthNames['long'] = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

// Voir http://www.php.net/manual/en/function.strftime.php pour la variable
// ci-dessous

$dateFormatShort =  "%b %d, %y";
$dateFormatLong  = '%A %B %d, %Y';
$dateTimeFormatLong  = '%B %d, %Y at %I:%M %p';
$timeNoSecFormat = '%I:%M %p';

// GENERIC 

$langYes="Si";
$langNo="No";
$langBack="Atr�s";
$langNext="siguiente";
$langAllowed="Permitido";
$langDenied="Denegado";
$langBackHome="Volver al inicio";
$langPropositions="Sugerencias para mejoras de";
$langMaj="Actualizar";
$langModify="Modificar";
$langDelete="Eliminar";
$langMove="Mover";
$langTitle="T�tulo";
$langHelp="Ayuda";
$langOk="Aceptar";
$langAdd="Agregar";
$langAddIntro="Agregar un texto introductorio";
$langBackList="Volver a la lista";
$langText="Texto";
$langEmpty="Vac�o";
$langConfirmYourChoice="Por favor, confirme su elecci�n";
$langCheckAll="Marcar todo";
$langAnd="y";
$langChoice="Su elecci�n";
$langFinish="Terminar";
$langCancel="Cancelar";
$langNotAllowed="Ud. no est� admitido aqu�";
$langManager="Administrador";
$langPlatform="Funciona con";
$langOptional="Opcional";
$langNextPage="Pr�xima p�gina";
$langPreviousPage="P�gina anterior";
$langUse="Usa";
$langTotal="Total";
$langTake="toma";
$langOne="Uno";
$langSeveral="Algunos";
$langNotice="Aviso";
$langDate="Fecha";

// banner

$langMyCourses="Lista de mis cursos";
$langModifyProfile="Modificar mi perfil";
$langMyStats = "Ver mis estad�sticas";
$langLogout="Salir";

// Tools names 

$langAgenda             = "Agenda";
$langDocument           = "Documentos";
$langWork               = "Trabajos de los Estudiantes";
$langAnnouncement       = "Anuncios";
$langUser               = "Usuarios";
$langForum              = "Foros";
$langExercise           = "Ejercicios";
$langStats              = "Estad�sticas";
$langGroups             = "Grupos";
$langChat               = "Charlar";
$langDescriptionCours   = "Descripci�n del Curso";

?>
