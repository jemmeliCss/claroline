<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.* $Revision: 
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   Brazilian Portuguese Translation                                                |
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or      |
      |   modify it under the terms of the GNU General Public License        |
      |   as published by the Free Software Foundation; either version 2     |
      |   of the License, or (at your option) any later version.             |
      +----------------------------------------------------------------------+

      +----------------------------------------------------------------------+
      | Translator :                                                         |
      |          Marcelo R. Minholi <minholi@unipar.br>                |
      +----------------------------------------------------------------------+
*/


$iso639_2_code = "br";
$iso639_1_code = "brz";

$langNameOfLang['arabic']="�rabe";
$langNameOfLang['brazilian']="portugu�s do brasil";
$langNameOfLang['bulgarian']="b�lgaro";
$langNameOfLang['croatian']="croata";
$langNameOfLang['dutch']="holand�s";
$langNameOfLang['english']="ingl�s";
$langNameOfLang['finnish']="finland�s";
$langNameOfLang['french']="franc�s";
$langNameOfLang['german']="alem�o";
$langNameOfLang['greek']="grego";
$langNameOfLang['italian']="italiano";
$langNameOfLang['japanese']="japon�s";
$langNameOfLang['polish']="polon�s";
$langNameOfLang['simpl_chinese']="chin�s simplificado";
$langNameOfLang['spanish']="espanhol";
$langNameOfLang['swedish']="sueco";
$langNameOfLang['thai']="tailand�s";
$langNameOfLang['turkish']="turco";

$charset = 'iso-8859-1';
$text_dir = 'ltr'; // ('ltr' for left to right, 'rtl' for right to left)
$left_font_family = 'verdana, arial, helvetica, geneva, sans-serif';
$right_font_family = 'arial, helvetica, geneva, sans-serif';
$number_thousands_separator = ',';
$number_decimal_separator = '.';
$byteUnits = array('Bytes', 'KB', 'MB', 'GB');

$langDay_of_weekNames['init'] = array('D', 'S', 'T', 'Q', 'Q', 'S', 'S');
$langDay_of_weekNames['short'] = array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab');
$langDay_of_weekNames['long'] = array('Domingo', 'Segunda', 'Ter�a', 'Quarta', 'Quinta', 'Sexta', 'S�bado');

$langMonthNames['init']  = array('J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D');
$langMonthNames['short'] = array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez');
$langMonthNames['long'] = array('Janeiro', 'Fevereiro', 'Mar�o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

// Voir http://www.php.net/manual/en/function.strftime.php pour la variable
// ci-dessous

$dateFormatShort =  "%d de %b de %y";
$dateFormatLong  = '%A, %d de %B de %Y';
$dateTimeFormatLong  = '%A, %d de %B de %Y �s %H:%Mh';
$timeNoSecFormat = '%H:%Mh';

// GENERIC

$langYes="Sim";
$langNo="N�o";
$langBack="Voltar";
$langNext="Pr�ximo";
$langAllowed="Permitido";
$langDenied="Negado";
$langPropositions="Propostas para a melhoria do";
$langMaj="Atualizar";
$langModify="Modificar";
$langDelete="Apagar";
$langMove="Mover";
$langTitle="T�tulo";
$langHelp="Ajuda";
$langOk="Ok";
$langAdd="Adicionar";
$langAddIntro="Adicionar texto introdut�rio";
$langBackList="Voltar para a lista";
$langText="Texto";
$langEmpty="Vazio";
$langConfirmYourChoice="Por favor confirme a sua escolha";
$langAnd="e";
$langChoice="Sua escolha";
$langFinish="Finalizar";
$langCancel="Cancelar";
$langNotAllowed="Voc� n�o � permitido aqui";
$langManager="Gerente";
$lang_footer_CourseManager = "Gerente(s) do Curso";
$langPoweredBy="Movido por";
$langOptional="Opcional";
$langNextPage="Pr�xima p�gina";
$langPreviousPage="P�gina anterior";
$langUse="Uso";
$langTotal="Total";
$langTake="tomar";
$langOne="Um";
$langSeveral="V�rias";
$langNotice="Not�cia";
$langDate="Data";
$langAmong="atrav�s";

// banner

$langMyCourses="Minha lista de cursos";
$langModifyProfile="Modificar meu perf�l";
$langMyAgenda = "Minha agenda";
$langLogout="Logout";


//needed for student view
$langCourseManagerview = "Vis�o de Gerente de Curso";
$langStudentView = "Vis�o de Estudante";






$lang_this_course_is_protected = 'Esse curso est� protegido';
$lang_enter_your_user_name_and_password = 'Informe o seu usu�rio e senha';
$lang_if_you_dont_have_a_user_account_profile_on = 'Se voc� n�o tem uma conta de usu�rio no';
$lang_click_here = 'clique aqui';
$lang_your_user_profile_doesnt_seem_to_be_enrolled_to_this_course = "Seu perfil de usu�rio n�o parece estar inscrito para esse curso";
$lang_if_you_wish_to_enroll_to_this_course = "Se voc� deseja se inscrever nesse curso";
$langUserName = "Nome do Usu�rio";
$lang_password = "Senha";



// TOOLNAMES
$langCourseHome = "Site do Curso";
$langAgenda = "Agenda";
$langLink="Links";
$langDocument="Documentos e Links";
$langWork="Trabalhos";
$langAnnouncement="Avisos";
$langUsers="Usu�rios";
$langForums = "F�runs";
$langExercises ="Exerc�cios";
$langGroups ="Grupos";
$langChat ="Bate-papo";
$langLearnPath="Rotas de Aprendizagem";
$langDescriptionCours  = "Descri��o do Curso";
$langPlatformAdministration = "Administra��o da Plataforma";

?>
