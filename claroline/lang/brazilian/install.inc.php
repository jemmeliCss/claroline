<?php # $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.*
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2003 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      | Authors: Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Hugues Peeters    <peeters@ipm.ucl.ac.be>                   |
      |      	 Christophe Gesch�  <gesche@ipm.ucl.ac.be>                   |
      +----------------------------------------------------------------------+
	  |   Brazilian Portuguese Translation                                                |
      +----------------------------------------------------------------------+
      | Translator :                                                         |
      |          Marcelo R. Minholi <minholi@unipar.br>                |
      +----------------------------------------------------------------------+
 */

$langEG 			= "ex.";
$langDBConnectionParameters = "Par�metros de conex�o do mysql";
$lang_Note_this_account_would_be_existing ="Nota : essa conta deveria existir";
$langDBHost			= "Servidor de Banco de Dados";
$langDBLogin		= "Usu�rio do Banco de Dados";
$langDBPassword 	= "Senha do Banco de Dados";
$langDBNamesRules	= "Nomes da base de dados";
$langMainDB			= "BD principal do Claroline";
$langStatDB             = "BD de rastreamento. �til apenas com v�rios BD";
$langPMADB			= "DB para extens�o do PhpMyAdmin";// show in multi DB
$langDbName			= "Nome da BD"; // show in single DB
$langDBUse			= "Utiliza��o da base de dados";
$langEnableTracking     = "Habilitar Rastreamento";
$langAllFieldsRequired	= "todos os campos requeridos";
$langPrintVers			= "Vers�o imprim�vel";
$langLocalPath			= "Caminho local correspondente";
$langAdminEmail			= "E-mail do Administrador";
$langAdminName			= "Nome do Administrador";
$langAdminSurname		= "Sobrenome do Administrator";
$langAdminLogin			= "Login do Administrador";
$langAdminPass			= "Senha do Administrator";
$langEducationManager	= "Respons�vel Educacional";
$langHelpDeskPhone		= "Telefone do Helpdesk";
$langCampusName			= "Nome do seu campus";
$langInstituteShortName = "Nome fantasia da institui��o";
$langInstituteName		= "URL da sua institui��o";


$langDBSettingIntro		= "
				O script de instala��o ir� criar o banco de dados principal do claroline. Note que o Claroline ir� precisar criar v�rios BDs (a n�o ser que voc� selecione a op��o \"Um\" abaixo). Se voc� tiver permiss�o para apenas um BD para o seu website dada pelo servi�o de hospedagem, o Claroline n�o ir� funcionar.";
$langDBSettingAccountIntro		= "
				O Claroline foi concebido para trabalhar com v�rios DBs mas ele pode trabalhar com apenas um,
				Para trabalhar com v�rios DBs, sua conta precisa ter direitos de cria��o.<BR>
				Se tem permiss�o para apenas um 
				DB para o seu website no seu Servi�o de Hospedagem, Voc� precisa selecionar a op��o \"Um\" abaixo.";
$langDBSettingNamesIntro		= "
				O script de instala��o ir� criar a base de dados principal do claroline. 
				Voc� pode criar bases de dados diferentes
				para o rastreamento e para a extens�o do PhpMyAdmin se voc� quiser
				ou armazenar todas essas coisas em uma base de dados, como voc� quiser. 
				Posteriormente, o Claroline ir� criar uma nova base de dados para cada novo curso criado. 
				Voc� pode especificar o prefixo para esses nomes de bases de dados.
				<p>
				Se voc� tem permiss�o para usar apenas uma base de dados, 
				volte para a p�gina anterior e selecione a op��o \"�nico\"
				</p>
				";
$langDBSettingNameIntro		= "
				O script de instala��o ir� criar tabelas para o claroline, rastreamento e PhpMyAdmin na sua
				Base de Dados.
				Escolha o nome para essa Base de Dados e um prefixo para futuras tabelas de cursos.<BR>
				Se voc� tiver permiss�o para criar v�rios BD, volte para a p�gina anterior e selecione a op��o \"V�rios\".
				Isso lhe oferecer� um uso muito mais convidativo";
$langStep1 			= "Passo 1 de 7 ";
$langStep2 			= "Passo 2 de 7 ";
$langStep3 			= "Passo 3 de 7 ";
$langStep4 			= "Passo 4 de 7 ";
$langStep5 			= "Passo 5 de 7 ";
$langStep6 			= "Passo 6 de 7 ";
$langStep7 			= "Passo 7 de 7 ";
$langCfgSetting		= "Configura��es";
$langDBSetting 		= "Configura��es do banco de dados MySQL";
$langMainLang 		= "L�ngua principal";
$langLicence		= "Licen�a";
$langLastCheck		= "�ltima verifica��o antes da instala��o";
$langRequirements	= "Requisitos";

$langDbPrefixForm	= "Prefixo para o nome do BD dos cursos";
$langTbPrefixForm	= "Prefixo para o nome das tabelas dos cursos";
$langDbPrefixCom	= "ex. 'CL_'";
$langEncryptUserPass	= "Encriptar senhas de usu�rio no banco de dados";
$langSingleDb	= "Utilizar um ou muitos BD para o Claroline";


$langWarningResponsible = "Use esse script apenas depois de fazer backup. O time do Claroline n�o � respons�vel se voc� perder ou corromper dados";
$langAllowSelfReg	=	"Permitir auto-cadastramento";
$langAllowSelfRegProf =	"Permitir auto-cadastramento como criador de curso";
$langRecommended	=	"(recomendado)";


?>
