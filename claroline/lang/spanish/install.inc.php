<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.*                              |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   English Translation                                                |
      +----------------------------------------------------------------------+

      +----------------------------------------------------------------------+
      | Translator :                                                         |
      |          Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Andrew Lynn       <Andrew.Lynn@strath.ac.uk>                |
      +----------------------------------------------------------------------+
      | Translation to Spanish v.1.4                                         |
      | e-learning dept CESGA <teleensino@cesga.es >                         |
      +----------------------------------------------------------------------|
      | Translation to Spanish v.1.5.1                                       |
      | Rodrigo Alejandro Parra Soto , Ing. (e) En Computaci�n eInformatica  |
      | Concepci�n, Chile  <raparra@gmail.com>                               |
      +----------------------------------------------------------------------|
 */

$langDBHost			= "Host de Base de Datos";
$langDBLogin		= "Username de Base de Datos";
$langDBPassword 	= "Clave de Base de Datos";
$langMainDB			= "BBDD Principal de Claroline";
$langStatDB             = "BBDD de Seguimiento. &Uacute;si s&oacute;lo si hay varias BBDD";
$langEnableTracking     = "Permitir Seguimiento";
$langAllFieldsRequired	= "Requerir todos los campos";
$langPrintVers			= "Versi&oacute;n para Imprimir";
$langLocalPath			= "Ruta local correspondiente";
$langAdminEmail			= "Email del Administrador ";
$langAdminName			= "Nombre del Administrador";
$langAdminSurname		= "Apellidos del Administrador";
$langAdminLogin			= "Login del Administrator ";
$langAdminPass			= "Clave del Administrator";
$langEducationManager	= "Responsable Educativo";
$langHelpDeskPhone		= "Tel&eacute;fono de Ayuda";
$langCampusName			= "Nombre de Su Campus";
$langInstituteShortName = "Acr�nimo de la Instituci&oacute;n";
$langInstituteName		= "URL de la Instituci&oacute;n";


$langDBSettingIntro		= "
				El script de Instalaci�n crear� la BBDD principal de Claroline. Por favor, recuerde que Claroline 
				necesitar&aacute; crar varias BBDD. Si s&oacute;lo puede tener una BBDD
				en su proveedor, Claroline no funcionar&aacute;.";
$langStep1 			= "Paso 1 de 6 ";
$langStep2 			= "Paso 2 de 6 ";
$langStep3 			= "Paso 3 de 6 ";
$langStep4 			= "Paso 4 de 6 ";
$langStep5 			= "Paso 5 de 6 ";
$langStep6 			= "Paso 6 de 6 ";
$langCfgSetting		= "Par&aacute;metros de Configuraci&oacute;n";
$langDBSetting 		= "Par&aacute;metros de BBDD MySQL";
$langMainLang 		= "Idioma Principal";
$langLicence		= "Licencia";
$langLastCheck		= "&Uacute;ltima comprobaci&oacute;n antes de instalar";
$langRequirements	= "Requisitos";

$langDbPrefixForm	= "Prefijo MySQL";
$langDbPrefixCom	= "Dejar vacio si no se pide";
$langEncryptUserPass	= "Encriptar la clave de los usuarios en la Base de Datos";
$langSingleDb	= "Usar una o varias BBDD para Claroline";

//////////////////////////////////////////////////
//agregados por Rodrigo Parra Soto

$langDBConnectionParameters = "Parametros de conecci�n de Mysql";
$lang_Note_this_account_would_be_existing ="Nota : Esta cuenta podr�a existir";
$langDBNamesRules	= "Nombres de la base de datos";
$langPMADB			= "Extenciones de la BD de PhpMyAdmin";// show in multi DB
$langDbName			= "Nombre de la BD"; // show in single DB
$langDBUse			= "Uso de la base de datos";
$langDBSettingAccountIntro		= "
				Claroline Est� echo para trabajar con muchas bases de datos pero tambi�n puede trabajar con una sola BD,
				Para trabajar con m�ltiples bases de datos, su cuenta necesita que la base de datos est� bien creada.<BR>
				Si usted solo utiliza una 
				BD ya que su servidor de hosting solo le ofrece esta opci�n , Debe seleccionar la opci�n \"Uno\" que est� abajo.";
$langDBSettingNamesIntro		= "
				El script  de instalaci�n crear� la base de datos principal de Claroline. 
				Usted puede crear una base de datos diferente 
				para el seguimiento y la extenci�n PhpMyAdmin si ustede quiere
				o garantice que todas estas cosas esten en una sola base de datos, tal como lo quiere. 
				Despu�s, Claroline crear� una nueva base de datos para cada nuevo curso creado.. 
				Tambi�n puede especificar el prefijo de estas bases de datos.
				<p>
				Si usted solo est� autorizado para usar una sola base de datos, 
				debe volver a la p�gina anteriory seleccionar la opci�n\"una sola(Single)\"
				</p>
				";
$langDBSettingNameIntro		= "
				El script de instalaci�n crear� la tabla princip�l de Claroline, el seguimiento y la relaciones  PhpMyAdmin en su 
				�nica BD..
				Eliga un n�mbre para esa BD y el prefijo para futuras tablas de cursos.<BR>
				Si a ust�d se le permite crear varias BD, Regrese a la p�gina anterior y seleccione la opci�n\"varias (Several)\".
				Esta es realmente m�s conveniente de utilizar";
$langTbPrefixForm	= "Prefijo para los n�mbres de las tablas de cols cursos";
$langWarningResponsible = "Use este script solo despu�s de hacer un respaldo de su informaci�n.El equipo de  Claroline  no se hace responsable por informaci�n perdida o corrupta";
$langAllowSelfReg	=	"Permitir auto-registro";
$langAllowSelfRegProf =	"permitir auto_registro para  creadores de cursos";
$langRecommended	=	"(Recomendado)";
//////////////////////////////////////////////////

?>