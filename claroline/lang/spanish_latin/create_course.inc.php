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
      +----------------------------------------------------------------------+
      | Traducci�n :                                                         |
      |          Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Andrew Lynn       <Andrew.Lynn@strath.ac.uk>                |
      +----------------------------------------------------------------------+
      | Basado en la traducci�n al castellano de                             |
      |          Xavier Casassas Canals <xcc@ics.co.at>                      |
      | Adaptado al espa�ol latinoamericano en Agosto-2003 por               |
      |          Carlos Brys       <brys@fce.unam.edu.ar>                    |
      +----------------------------------------------------------------------+
 */

// add_course
$langNewCourse 			= "Nuevo curso";
$langAddNewCourse 		= "Agregar un nuevo curso";
$langRestoreACourse		= "Restaurar un curso";
$langOtherProperties  	        = "Autres propri�t�s trouv�es dans l'archive";
$langSysId 			= "Id Syst�me";
$langDescription  		= "Descripci�n";
$langDepartment	  		= "Departamento";
$langDepartmentUrl	  	= "URL";
$langScoreShow  		= "Mostar puntajes";
$langVisibility  		= "Visibilidad";
$langVersionDb  		= "Version de la base de donn�e lors de l'archivage";
$langVersionClaro  		= "Version de claroline lors de l'archivage";
$langLastVisit  		= "Ultima visita";
$langLastEdit  			= "Ultima contribuci�n";
$langExpire 			= "Expiraci�n";
$langChoseFile 			= "Selecccione el archivo";
$langFtpFileTips 		= "Si le fichier est sur un ordinateur tiers et accessible par ftp";
$langLocalFileTips		= "Si le fichier est sur l'espace de stockage des cours de ce campus";
$langHttpFileTips		= "Si le fichier est sur un ordinateur tiers et accessible par http";
$langPostFileTips		= "Si le fichier est sur  votre ordinateur";


// create_course.php
$langLn="Idioma";


$langCreateSite  = "Crear un sitio web de un curso";
$langFieldsRequ  = "Todos los campos son obligatorios";
$langTitle       = "Nombre del Curso";
$langEx          = "p. ej. <i>Historia de la literatura</i>";
$langFac         = \"Facultad/Carrera";
$langTargetFac   = \"Se trata de la Facultad en la que se realiza el curso";
$langCode        = \"C�digo del curso";
$langMax         = \"Max. 12 caracteres, p. ej.<i>ROM2121</i>";
$langDoubt       = \"En caso de dudas sobre el t�tulo exacto del curso o el c�digo que le corresponde , consultar el";
$langProgram     = \"Programa del curso</a>. Si el sitio web que usted quiere crear no corresponde con ning�n c�digo de curso existente, usted puede definir uno. Por ejemplo <i>INNOVACION</i> si se trata de un programa de formaci�n  sobre gesti�n de la innovaci�n";
$langProfessors  = "Profesor(es)";
$langExplanation = \"Una vez que usted haya pulsado OK, ser� creado in sitio web que incluir�: Foro, 
                   Lista de enlaces, Ejercicios, Agenda, Lista de documentos... Con su
                  identificaci�n de usuario, usted podr� modificar su contenido";
$langEmpty       = \"Usted no ha rellenado todos los campos.<br>Utilice el bot�n 'Atr�s' de su navegador y vuelva a empezar.<br>Si usted no conoce el c�digo de su curso, consulte el programa del curso";
$langCodeTaken   = \"Este c�digo de curso ya se utiliz� por otro curso.<br>Utilice el bot�n 'Atr�s' de su navegador y vuelva a empezar.";


// tables MySQL
$langFormula       = \"Cordialmente, el profesor";
$langForumLanguage = \"Espa�ol";	// other possibilities are english, spanish (this uses phpbb language functions)
$langTestForum     = "Foro de pruebas ";
$langDelAdmin      = \"A eliminar v�a la administraci�n de los foros";
$langMessage       = \"En el momento que usted suprima el foro \"Foro de pruebas\", igualmente se suprimir� el presente tema que no contiene m�s que este mensaje";
$langExMessage     = "Mensaje de ejemplo";
$langAnonymous     = \"An�nimo";
$langExerciceEx    = "Ejemplo de ejercicio";
$langAntique       = \"Historia de la filosof�a cl�sica";
$langSocraticIrony = \"La iron�a socr�tica consiste en...";
$langManyAnswers   = "(varias respuestas correctas son posibles)";
$langRidiculise    = "Ridiculizar al interlocutor para hacerle admitir su error.";
$langNoPsychology  = \"No. La iron�a socr�tica no se aplica al terreno de la psicolog�a, sino en el de la argumentaci�n.";
$langAdmitError    = "Reconocer los propios errores para invitar al interlocutor a hacer lo mismo.";
$langNoSeduction   = \"No. No se trata de una estrategia de seducci�n o de un m�todo por ejemplo.";
$langForce         = \"Forzar  al interlocutor, por medio de una serie de cuestiones y subcuestiones, para que reconozca que no sabe lo que pretende saber."\;
$langIndeed        = \"Correcto. La iron�a socr�tica es un met�do interrogativo. El t�rmino griego \"eirotao\" significa , por otro lado, \"interrogar\"."\;
$langContradiction = \"Utilizar el principio de no contradicci�n para llevar al interlocutor a un callej�n sin salida.";
$langNotFalse      = "Esta respuesta no es falsa. Es exacto que la puesta en evidencia de la ignorancia del interlocutor se realiza poniendo en evidencia las contradicciones en que desembocan sus tesis.";

// Home Page MySQL Table "Inicio"
$langAgenda        = "Agenda";
$langLinks         = "Enlaces";
$langDoc           = "Documentos";
$langVideo         = "Video";
$langWorks         = "Trabajos de Alumnos";
$langCourseProgram = "Programa del Curso";
$langAnnouncements = "Anuncios";
$langUsers         = "Usuarios";
$langForums        = "Foros";
$langExercices     = "Ejercicios";
$langStatistics    = "Estad�sticas";
$langAddPageHome   = "Enviar una p�gina y enlazarla con la p�gina principal";
$langLinkSite      = "Agregar un enlace a  la p�gina principal";
$langModifInfo     = "Modificar la informaci�ne del curso";



// Other SQL tables
$langAgendaTitle       = "Martes 11 diciembre 14h00 : curso de filosof�a (1) - Local : Sur 18";
$langAgendaText        = "Introducci�n general a la filosof�a y explicaci�n sobre el funcionamiento del curso";
$langMicro             = "Entrevistas de calle";
$langVideoText         = "Este un ejemplo de Real Video. Usted puede enviar videos en todos los formatos (.mov, .rm, .mpeg...), siempre que los estudiantes est�n condiciones de leerlos.";
$langGoogle            = "Potente motor de b�squeda";
$langIntroductionText  = "Este es el texto de introducci�n de su curso. Para modificarlo, haga click sobre \"modificar\".";
$langIntroductionTwo   = "Esta p�gina permite a cada estudiante o grupo de estudiantes colocar un documento en el sitio web del curso. Env�e documentos en formato HTML �nicamente si estos no contienen im�genes.";
$langCourseDescription = "Escriba aqu� la descripci�n que aparecer� en la lista de los cursos";
$langProfessor         = "Profesor";
$langAnnouncementEx    = "Este es un ejemplo de un anuncio.";
$langJustCreated       = "Usted acaba de crear el sitio web del curso";
$langEnter             = "Volver a mi lista de cursos";
$langMillikan          = "Experimento Millikan ";
$langCourseDesc        = "Descripci�n del Curso ";

 // Groups
$langGroups="Grupos";
$langCreateCourseGroups="Grupos";
$langCatagoryMain="Principal";
$langCatagoryGroup="Foros de grupos";
$langChat ="Chat";

?>
