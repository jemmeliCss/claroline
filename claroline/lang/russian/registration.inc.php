<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.4.0 $Revision$
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
 */
// user management

// lang vars
$langAdminOfCourse		= "�������������";  //
$langSimpleUserOfCourse = "������������"; // strings for synopsis
$langIsTutor  			= "������"; //

$langCourseCode			= "����";	// strings for list Mode
$langParamInTheCourse 	= "��������� �����"; //

$langAddNewUser = "�������� ������������ � �������";
$langMember ="���������������";

$langDelete	="�������";
$langLock	= "�������������";
$langUnlock	= "��������������";

$langHaveNoCourse = "��� ������";

$langFirstname = "���";
$langLastname = "�������";
$langEmail = "����� ����������� �����";
$langRetrieve ="����� ��� ��������� �����������";
$langMailSentToAdmin = "����������� ��������� ���������� ��������������.";
$langAccountNotExist = "���� ������� �� ����������. <BR>".$langMailSentToAdmin." 
�� ���������� ����� �������. <BR><BR>";
$langAccountExist = "��� ������� ����������. <BR> ����������� ��������� ���������� ��������������. <BR><BR>";
$langWaitAMailOn = "����� ������ �� ������ ";
$langCaseSensitiveCaution = "������� ��������� �������� � ��������� �����.";
$langDataFromUser = "������, ����������� �������������";
$langDataFromDb = "��������������� ������ � ���� ������";
$langLoginRequest = "������� ����� ������������";
$langExplainFormLostPass = "������� ��� �����, ������� �� ����� �� ����� �����������.";
$langTotalEntryFound = "���������� ��������� ������";
$langEmailNotSent = "���-�� �� ���������, ��������� ��� ��������� ";
$langYourAccountParam = "��� ���� ��������� �����������";
$langTryWith ="���������� ";
$langInPlaceOf ="������";
$langParamSentTo = "���� ��������� ����������� ���������� �� �����";



// REGISTRATION - AUTH - inscription.php
$langRegistration="�����������";

$langSurname="���";
$langUserName="��� ������������";
$langPass="������";
$langConfirmation="�������������";
$langStatus="��������";
$langRegStudent="�������� ���� �� �����";
$langRegAdmin="������� ����� ������";
$langTitular = "�������������";
// inscription_second.php


$langRegistration = "�����������";
$langPassTwice    = "������, ��������� ���� ������, �� ���������. ����������� ������ ����� ������ ��������
� ��������� ��������.";

$langEmptyFields = "�� ��������� �� ��� ����.
����������� ������ ����� ������ �������� � ��������� ��������.";

$langPassTooEasy ="���� ������ ������� �������. �������� ������ ������, ��������: ";

$langUserFree    = "��������� ���� ��� ������������ ��� ������������. 
����������� ������ ����� ������ �������� � �������� ������ ���.";

$langYourReg                = "����� ����������� ��";
$langDear                   = "���������(��)";
$langYouAreReg              = "�� ���������������� �� ";
$langSettings               = "�� ���������� �����������: ��� ������������:";
$langAddress                = "�����";
$langIs                     = "-";
$langProblem                = "� ������ �������, ��������� � ����.";
$langFormula                = "� ���������";
$langManager                = "�������������";
$langPersonalSettings       = "���� ������ ������ �������� � ��� ��� ���������� ����������� ������
� ������������ ������ ����� ������������ � ������. </p>";
$langNowGoChooseYourCourses ="������ �� ������ ������� �����, � ������� �� ������ ����� ������.";
$langNowGoCreateYourCourse  = "������ �� ������ ������� ���� ����.";
$langYourRegTo              = "���� ���������";
$langIsReg                  = "���� ��������� ���������.";
$langCanEnter               = "������ �� ������ <a href=../../index.php>����� � ����������� �����������</a>";

// profile.php

$langModifProfile = "�������� ��� ���������";
$langPassTwo      = "������, ��������� ���� ������, �� ���������.";
$langAgain        = "������� �������!";
$langFields       = "�� ��������� �� ��� ����";
$langUserTaken    = "��������� ���� ��� ������������ ��� ������������.";
$langEmailWrong   = "��������� ���� ����������� ����� ������� ��� �������� ������������ �������. ";
$langProfileReg   = "���� ����� ��������� ���������.";
$langHome         = "��������� �� ������� ��������";
$langMyStats      = "��� ����������";


// user.php

$langUsers    = "������������";
$langModRight ="�������� �����: ";
$langNone     ="���";

$langNoAdmin            = "������ �� ����� <b>����� �������������� �����</b>";
$langAllAdmin           = "������ ����� <b>��� ����� ����������������� �����</b>";
$langModRole            = "�������� ���� ";
$langRole               = "����";
$langIsNow              = "������ ��������";
$langInC                = "����� �����";
$langFilled             = "�� ��������� �� ��� ����";
$langUserNo             = "��������� ���� ��� ������������";
$langTaken              = "��� ������������. �������� ������. ";
$langOneResp            = "���� �� ������������ �� ����";
$langRegYou             = "��������������� ��� �� ";
$langTheU               ="������������";
$langAddedU             ="��������. ���� �� ����� ��� �����, ��� ���������� ��������� � ������ ������������ ";
$langAndP               = "� �������.";
$langDereg              = "��� ������� �� ������� �����";
$langAddAU              = "�������� �������������";
$langStudent            = "�������";
$langBegin              = "������";
$langPreced50           = "50 ����������";
$langFollow50           = "50 ���������";
$langEnd                = "�����";
$langAdmR               = "�������������";
$langUnreg              = "��������";
$langAddHereSomeCourses = "<font size=2 face='arial, helvetica'><big>��� �����</big><br><br>
			�������� �����, � ������� �� ������ ����� ������. ������� ������� �������� ������, 
			� ������� �� �� ������ ����� ������ (�����, �� ������� �� ���������, ������ ����� ��� ��������).
			����� ������� �� � ����� ������.";

$langCanNotUnsubscribeYourSelf = "�� �� ������ ���������� �� �����, ���� ��������������� �� ���������. 
������ ������ ������������� ����� ����� ��� �������.";

$langGroup="������";
$langUserNoneMasc="-";

$langTutor                = "������";
$langTutorDefinition      = "������ (����� ��������� �� ���������� �����)";
$langAdminDefinition      = "������������� (����� �������� ���������� �����)";
$langDeleteUserDefinition = "�������� (������� �� ������ ������������� <b>�����</b> �����)";
$langNoTutor              = "�� �������� �������� ����� �����";
$langYesTutor             = "�������� �������� ����� �����";
$langUserRights           = "����� �������������";
$langOneByOne             = "�������� ������������ �������";
$langUserMany             = "������������� ������ ������������� �� ���������� �����";
$langNo                   = "���";
$langYes                  = "��";

$langUserAddExplanation   = "������ ������ ������������ �������� ����� ������ ��������� 5 �����: 
<b>������� ��� ��� ������������ ������ ����������� �����</b>
����������� �������� Tab � �������������� � ���� �������. 
������������ ������� �� ����������� ����� ��� ������������ � ������. ";

$langSend             = "���������";
$langDownloadUserList = "��������� ������";
$langUserNumber       = "���������� �������������";
$langGiveAdmin        = "������� ���������������";
$langRemoveRight      = "������ ����� �����";
$langGiveTutor        = "������� ��������";

$langUserOneByOneExplanation = "�� ������� �� ����������� ����� ��� ������������ � ������.";
$langBackUser                = "����� � ������ �������������";
$langUserAlreadyRegistered   = "������������, ������� �� �� ��� ������������ � ������, ��� ��������������� �� �����.";

$langAddedToCourse           = "��������������� �� ����� �����";

$langGroupUserManagement     = "���������� ��������";

$langIfYouWantToAddManyUsers = "���� �� ������ �������� ������ ������������� �� ��� ����, ��������� �
����� ��������������� ���������.";

$langCourses    = "�����.";
$langLastVisits = "��� ��������� ���������";
$langSee        = "�����������";
$langSubscribe  = "����������<br>��������&nbsp;=&nbsp;��";
$langCourseName = "�������� �����";
$langLanguage   = "����";

$langConfirmUnsubscribe = "����������� ������ ������� � ����� ����� ������������.";
$langAdded              = "���������";
$langDeleted            = "�������";
$langPreserved          = "���������";
$langDate               = "����";
$langAction             = "��������";
$langLogin              = "����, �����";
$langLogout             = "�����";
$langModify             = "��������";
$langUserName           = "��� ������������";
$langEdit               = "�������������";

$langCourseManager       = "�������� �����";
$langManage              = "���������� ��������";
$langAdministrationTools = "�������� �����������������";
$langModifProfile	     = "�������� ���������";
$langUserProfileReg	     = "��������� ������������";
$lang_lost_password      = "������ ������";

$lang_enter_email_and_well_send_you_password  = "������� ����� ����������� �����, ������� �� ������������ ��� �����������,
� �� ������ ��� ������. ";
$lang_your_password_has_been_emailed_to_you   = "��� ������ ������ ��� �� ����������� �����.";
$lang_no_user_account_with_this_email_address = "��� ������������ � ����� ����������� �������.";
$langCourses4User  = "����� ��� ����� ������������";
$langCoursesByUser = "�������� ����� �� �������� ������������";

?>
