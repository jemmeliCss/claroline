<?php
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.3.0 $Revision$                             |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2002 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   $Id$   |
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or      |
      |   modify it under the terms of the GNU General Public License        |
      |   as published by the Free Software Foundation; either version 2     |
      |   of the License, or (at your option) any later version.             |
      |                                                                      |
      |   This program is distributed in the hope that it will be useful,    |
      |   but WITHOUT ANY WARRANTY; without even the implied warranty of     |
      |   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      |
      |   GNU General Public License for more details.                       |
      |                                                                      |
      |   You should have received a copy of the GNU General Public License  |
      |   along with this program; if not, write to the Free Software        |
      |   Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA          |
      |   02111-1307, USA. The GNU GPL license is also available through     |
      |   the world-wide-web at http://www.gnu.org/copyleft/gpl.html         |
      +----------------------------------------------------------------------+
      | Authors: Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Hugues Peeters    <peeters@ipm.ucl.ac.be>                   |
      |          Christophe Gesch� <gesche@ipm.ucl.ac.be>                    |
      +----------------------------------------------------------------------+
 */
// create_course.php
$langCreateSite="������� ���� �����";
$langFieldsRequ="��� ���� �����������";
$langTitle="��������";
$langEx="��������, <i>������� ����������</i>";
$langFac="������";
$langCode="��� �����";
$langTargetFac="���� ���� � ����������, �������, �����... � ������� ������� ����";
$langMax="�������� 12 ������, �������� <i>LIT2121</i>";
$langDoubt="� ������ �������� �� ������� �������� ����� ��� ��� ����, ������������������� ";
$langProgram="��������� �����</a>. ���� ����, ������� �� ������ �������, �� ����� ������������� ����, 
�� ������ ��� ���������. ��������, <i>INNOVATION</i>, ���� ���� ���� � ��������� �������� �� 
���������� �����������";
$langProfessors="�������������(�)";
$langExplanation="��� ������ �� �������� �� ��, ����� ������ ����, ���������� �����, 
��������� ������,  �����, ���������, ������� ��������� � �.�. �� ������� �������� ��� ����������, ��� ����� ���� ����� ����� ��� ����� �������.";
$langEmpty="�� ��������� �� ��� ����. \n<br>\n ����������� ������ ����� ������ �������� � ������� �������
<br>���� �� �� ������ ��� ������ �����, ���������� � ��������� ����� �� �������.";
$langCodeTaken="���� ��� ����� ��� �����.<br>����������� ������ ����� ������ ��������, ����� ��������� ����� � ��������� ��������";


// tables MySQL
$langFormula="�������� ��� �������������";
$langForumLanguage="russian";	// other possibilities are english, spanish (this uses phpbb language functions)
$langTestForum="������� �����";
$langDelAdmin="����� ������� ����� ����������������� �������";
$langMessage="����� �� ������� ����� ������� �����, ��� ����� ������ ������ ����, ������� �������� ������ ��� ���������";
$langExMessage="������ ���������";
$langAnonymous="������";
$langExerciceEx="������ �����";
$langAntique="������� �������� ���������";
$langSocraticIrony="������ ������� ����������� �...";
$langManyAnswers="(�������� ��������� ��������� ���������� �������)";
$langRidiculise="�������� ������ �����������, ����� ��������� ��� ������� ���� ������.";
$langNoPsychology="���. ������ ������� ��������� �� � ������� ����������, �� � ������� ����������������.";
$langAdmitError="�������� ���� ������, ����� �������� ����������� ������� �� �� �����.";
$langNoSeduction="���. ���� ���� �� � ��������� ���������� ����������������� ��� ������ �������.";
$langForce="��������� ����������� ��������, ��� �� �� ����� ����, ��� ���������� �����, � ������� ����� �������� � �����������.";
$langIndeed="�������������. ������ ������� ��� ����� ���������� ��������. ��������� ����� \"eirotao\" �������� \"���������\".";
$langContradiction="������������ ������� ��������������, ����� �������� ����������� � �����.";
$langNotFalse="���� ����� ������ �������� ������������. �����, ��� ��������� �������� ����������� ���������� ����� ��������� ������������, � ������� �������� ��� ������.";



// Home Page MySQL Table "accueil"
$langAgenda="���������";
$langLinks="������";
$langDoc="������� ���������";
$langVideo="�����";
$langWorks="�������";
$langCourseProgram="��������� �����";
$langAnnouncements="����������";
$langUsers="������������";
$langForums="������";
$langExercices="�����";
$langStatistics="����������";
$langAddPageHome="���������� �������� � ������� � ������� ��������� �����";
$langLinkSite="�������� ������ �� ������� �������� �����";
$langModifyInfo="�������� �����";
$langCourseDesc = "�������� �����";


// Other SQL tables
$langAgendaTitle="������� 11 ������� 14.00 : ���� ��������� (1) - ��������� : ����� ������, 18";
$langAgendaText="����� �������� � ��������� � ��������� ���������������� �����";
$langMicro="�����";
$langVideoText="��� ������ RealVideo. �� ������ ��������� ����� �� ���� �������� (.mov, .rm, .mpeg...), 
���� �� �������� ����� �� ��������";
$langGoogle="������ ��������� �������";
$langIntroductionText="��� ����� �������� � ��� ����. ��� ���������� ��������� - ������� ������ ��������, 
��� ���� �������� ����� � �������������, � �� ������� ���������� ����������.";

$langIntroductionTwo="��� �������� �������� ������ ����������. ��� ��������� ������� �������� ��� ������ ��������� ���������� �������� (Word, Excel, HTML... ) �� ����� �����, ����� �� ��� �������� ������ ��������� ��� �������������.
���� �� ������ ������������ �������� � ����� ������ (������� ������������), ������ �������
������ ������� ������ �� �������� - ����, ��� �� �������� � ����� �����, �� ���� �� ����� ���������.";

$langCourseDescription="������� ���� ��������, ������� �������� � ������ ������ 
(���������� ����� ���� � ������ ������ ����� �� ����� � ��������� ����� ��� ��������� ������ Claroline).";
$langProfessor="�������������";
$langAnnouncementEx="��� ������ ����������.";
$langJustCreated="��  ������ ��� ������� ���� �����";
$langEnter="��������� � ������ ������ ������";
$langMillikan="���� ���������";



// Groups
$langGroups="������";
$langCreateCourseGroups="������� ������";

$langCatagoryMain = "����� ������";
$langCatagoryGroup = "������ �����";

$langChat ="���";

$langRestoreCourse = "�������������� �����";
?>
