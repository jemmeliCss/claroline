<?php
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.4                                                |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2002, 2003 Universite catholique de Louvain (UCL)|
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


$langFirstname = "Nombre"; 
$langLastname = "Apellido";
$langEmail = "Email";
$langRetrieve ="Recuperar informaci�n de identififaci�n";
$langMailSentToAdmin = "Un mensaje se envi� al administrador.";
$langAccountNotExist = "No se encontr� la cuenta.<BR>".$langMailSentToAdmin." El puede buscar manualmente.<BR>";
$langAccountExist = "Esta cuenta existe.<BR>".$langMailSentToAdmin."<BR>";
$langWaitAMailOn = "Um mensaje puede ser enviado a  ";
$langCaseSensitiveCaution = "El sistema hace diferencias entre may�sculas y min�sculas.";
$langDataFromUser = "Datos enviado por el usuario";
$langDataFromDb = "Datos en la base de datos";
$langLoginRequest = "Requerimiento de conexi�n";
$langExplainFormLostPass = "Entrez ce que  vous pensez avoir  introduit comme donn�es lors de votre inscription.";
$langTotalEntryFound = "Se contr� la entrada";
$langEmailNotSent = "Algo no funciona, env�e por email esto a  ";
$langYourAccountParam = "Estos son su Usuario y Contrase�a de su cuenta";
$langTryWith ="Intente con";
$langInPlaceOf ="y no con  ";
$langParamSentTo = "Informaci�n de identificaci�n enviada a ";

// REGISTRATION - AUTH - inscription.php
$langRegistration  = "Inscripci�n";
$langName          = "Apellido";
$langSurname       = "Nombre";
$langUsername      = "Nombre de usuario";
$langPass          = "Contrase�a";
$langConfirmation  = "Confirmaci�n";
$langEmail         = "Correo electr�nico";
$langStatus        = "Acci�n";
$langRegStudent    = "Inscribirme en cursos (estudiante)";
$langRegAdmin      = "Crear sitios web de cursos (Profesor)";


// inscription_second.php
$langPassTwice     = "Ha escrito dos contrase�as diferentes. Use el bot�n  'Atr�s'  del navegador y vuelva a intentarlo.";
$langEmptyFields   = "Ha dejado algunos campos en blanco. Use el bot�n 'Atr�s' del navegador y vuelva a intentarlo.";
$langUserFree      = "El nombre de usuario que eligi� ya existe. Use el bot�n 'Atr�s'  del navegador y elija uno diferente.";
$langYourReg       = "Su inscripci�n en";
$langDear          = "Estimado(a)";
$langYouAreReg     = "Usted se ha inscripto en";
$langSettings      = "con los par�metros siguientes:\Nombre de usuario:";
$langAddress       = "La direcci�n de";
$langIs            = "es";
$langProblem       = "En caso de tener alg�n problema, no dude en contactarnos.";
$langFormula       = "Cordialmente";
$langManager       = "Responsable";
$langPersonalSettings = "Sus datos personales han sido registrados y ha sido enviado un correo electr�nico a su casilla para recordarle su nombre de usuario y su contrase�a.</p> Ahora seleccione de la lista los cursos a los que desea tener  acceso.";

$langNowGoChooseYourCourses ="Ahora Ud. puede seleccionar, en la lista, los cursos a los cuales desea acceder.";
$langNowGoCreateYourCourse  ="Ahora Ud. puede crear su curso";

$langYourRegTo     = "Usted est� inscripto en";
$langIsReg         = "ha sido actualizado";
$langCanEnter      = "Ahora usted puede <a href=../../index.php>entrar al campus</a>";

// profile.php

$langModifProfile  = "Modificar mi perfil";
$langPassTwo       = "Usted ha escrito dos contrase�as diferentes";
$langAgain         = "Intente de nuevo!";
$langFields        = "Ha dejado algunos campos sin completar";
$langUserTaken     = "El nombre de usuario que ha elegido ya existe";
$langEmailWrong    = "La direcci�n de correo electr�nico que ha escrito est� incompleta o contiene caracteres inv�lidos";
$langProfileReg    = "Su nuevo perfil de usuario ha sido registrado";
$langHome          = "Regresar a la p�gina de inicio";
$langMyStats       = "Ver mis estad�sticas";


// user.php

$langUsers         = "Usuarios";
$langModRight      = "Modificar los derechos de administraci�n de";
$langNone          = "ninguno";
$langAll           = "Todos";
$langNoAdmin       = "Ahora no tiene <b>ning�n derecho de administraci�n sobre este sitio";
$langAllAdmin      = "Ahora tiene <b>todos los derechos de administraci�n de este sitio";
$langModRole       = "Modificar el papel (rol) de";
$langRole          = " Papel (Rol)";
$langIsNow         = "es ahora";
$langInC           = "en este curso";
$langFilled        = "No ha llenado todos los campos.";
$langUserNo        = "El nombre de usuario que eligi�;";
$langTaken         = "ya existe. Elija uno diferente.";
$langOneResp       = "Uno de los administradores del curso";
$langRegYou        = "lo ha inscripto en este curso";
$langTheU          = "El usuario";
$langAddedU        = "ha sido agregado. Si ya escribi� su direccci�n de e-mail, se le enviar� un mensaje para comunicarle su nombre de usuario";
$langAndP          = "y su contrase�a";
$langDereg         = "ha sido dado de baja de este curso";
$langAddAU         = "Agregar un usuario";
$langStudent       = "estudiante";
$langBegin         = "inicio.";
$langPreced50      = "50 anteriores";
$langFollow50      = "50 siguientes";
$langEnd           = "fin";
$langAdmR          = "Derechos de administraci�n.";
$langUnreg         = "Dar de baja";
$langAddHereSomeCourses = "<font size=2 face='Arial, Helvetica'><big>Modificar la lista de cursos</big><br><br>Marque los cursos que desea seguir.<br>Deseleccione aquellos que no desea seguir m�s.<br> Luego haga clic en 'Aceptar' al final de la lista";
$langTitular = "Autor";
$langCanNotUnsubscribeYourSelf = "Usted no puede quitarse de un curso que administra, solamente otro administrador puede hacerlo.";

$langGroup="grupo";
$langUserNoneMasc="-";
$langTutor="Tutor";
$langTutorDefinition="Tutor (tiene derechos para supervisar grupos)";
$langAdminDefinition="Administrador (tiene derechos para modificar el contenido del sitio web del curso)";
$langDeleteUserDefinition="No registrado (borrado de la lsita de usuarios de  <b>este</b> curso)";
$langNoTutor = "no es tutor de este curso";
$langYesTutor = "es tutor de este curso";
$langUserRights="Derechos de usuarios";
$langNow="ahora";
$langOneByOne="Agregar usuarios manualmente";
$langUserMany="Importar una lista de usuarios desde un archivo de texto";
$langNo="no";
$langYes="si";
$langConfirmUnsubscribe = "Confirmar que quita al usuario";
$langUserAddExplanation="cada l�nea del archivo a enviar tendr� que inclu�r necesariamente 5 campos: <b>Nombre&nbsp;&nbsp;&nbsp;Apellido&nbsp;&nbsp;&nbsp;Usuario&nbsp;&nbsp;&nbsp;Contrase�a&nbsp;&nbsp;&nbsp;Email</b> separados por tabuladores y en ese orden. Los usuarios recibir�n un e-mail de conformaci�n con su nombre de usuario/contrase�a.";
$langSend="Enviar";
$langDownloadUserList="Actualizar lista";
$langUserNumber="n�mero";
$langGiveAdmin="Hacer administrador";
$langRemoveRight="Quitar este derecho";
$langGiveTutor="Hacer tutor";
$langUserOneByOneExplanation="El(ella) recibir� un e-mail de confirmaci�n con su nombre de usuario y contase�a";
$langBackUser="Volver a la lista de usuarios";
$langUserAlreadyRegistered="Un usuario con el mismo nombre/apellido ha sido inscripto en este curso. No puede registrarlo dos veces.";
$langAddedToCourse="est� registrado en el campus, pero no en este curso. Ahora lo est�. ";
$langGroupUserManagement="Aadministraci�n de grupos";
$langIsReg="Sus modificaciones han sido registradas";
$langPassTooEasy ="esa contrase�a es demasiado simple. Use una contrase�a como �sta ";
$langIfYouWantToAddManyUsers="Si desea agregar una lista de usuarios en su curso, por favor contacte con su administrador del sitio web.";
$langCourses="cursos.";

$langLastVisits="Mi �ltima visita";
$langSee		= "Ir A";
$langSubscribe	= "Inscribirse";
$langCourseName	= "Nombre del curso";
$langLanguage	= "Idioma";


$langConfirmUnsubscribe = "Confirme que borra a un usuario";
$langAdded = "Agregado";
$langDeleted = "Eliminado";
$langPreserved = "Preservado";

$langDate = "Fecha";
$langAction = "Acci�n";
$langLogin = "Ingreso";
$langLogout = "Desconexi�n";
$langModify = "Modificar";

$langUserName = "Nombre de usuario";


$langEdit = "Editar";
$langCourseManager = "Administrador del Curso";

?>
