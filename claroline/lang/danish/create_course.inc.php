<?php
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.3.0 $Revision$                            |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2002 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   $Id$     |
	  |   Danish Translation                                                |
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
      | Translator :                                                         |
      |                  						     |           
		 Helle Meldgaard   <helle@iktlab.au.dk>                      |
      +----------------------------------------------------------------------+
 */
// create_course.php
$langLn="Sprog";


$langCreateSite="Opret kursushjemmeside";
$langFieldsRequ="Udfyld alle felter";
$langTitle="Kursustitel";
$langEx="f.eks. <i>Litteraturhistorie</i>";
$langFac="Fakultet";
$langTargetFac="Angiv p� hvilket Fakultet kurset udbydes"; 
$langCode="Kursuskode";
$langMax="max. 12 tegn, f.eks. <i>ROM2121</i>. Brug ikke <i>�, �, �</i>";
$langDoubt="Hvis du er i tvivl om koden p� dit kursus, kontakt, ";
$langProgram="Kursusprogram</a>. Hvis dit kursus ikke har en kode, s� skal du bare opfinde en. F.eks. <i>INNOVATION</i> hvis kurset drejer sig om Innovation Management";
$langProfessors="Underviser(e)";
$langExplanation="N�r du klikker ok, s� vil en hjemmeside med Diskussionforum, Kalender, Dokumenter osv. bliver oprettet. Dit login, som skaber af kursushjemmesiden, giver dig ret til at �ndre og tilpasse hjemmesiden efter dine egne �nsker";
$langEmpty="Du har ikke udfyldt alle felter.<br>brug<b>Tilbage</b> knappen p� din browser og pr�v igen.<br>Husk at anf�re en kursuskode";
$langCodeTaken="Den anf�rte kursuskode bruges af en andet kursus.  <br>Brug <b>Tilbage</b> knappen p� din browser og pr�v igen";


// tables MySQL
$langFormula="Med venlig hilsen, underviseren";
$langForumLanguage="english";	// other possibilities are english, spanish (this uses phpbb language functions)
$langTestForum="Testforum";
$langDelAdmin="Slet dette testforum ved hj�lp af administrationsv�rkt�jet";
$langMessage="N�r du sletter testforum, s� sletter du samtidig ogs� alle meddelelser i testforum.";
$langExMessage="Eksempel p� meddelelse";
$langAnonymous="Anonym";
$langExerciceEx="Eksempel p� �velse";
$langAntique="History of Ancient Philosophy";
$langSocraticIrony="Socratic irony is...";
$langManyAnswers="(more than one answer can be true)";
$langRidiculise="Ridiculise one\'s interlocutor in order to have him concede he is wrong.";
$langNoPsychology="No. Socratic irony is not a matter of psychology, it concerns argumentation.";
$langAdmitError="Admit one\'s own errors to invite one\'s interlocutor to do the same.";
$langNoSeduction="No. Socratic irony is not a seduction strategy or a method based on the example.";
$langForce="Compell one\'s interlocutor, by a series of questions and sub-questions, to admit he doesn\'t know what he claims to know.";
$langIndeed="Indeed. Socratic irony is an interrogative method. The Greek \"eirotao\" means \"ask questions\"";
$langContradiction="Use the Principle of Non Contradiction to force one\'s interlocutor into a dead end.";
$langNotFalse="This answer is not false. It is true that the revelation of the interlocutor\'s ignorance means showing the contradictory conclusions where lead his premisses.";



// Home Page MySQL Table "accueil"
$langAgenda="Kalender";
$langLinks="Links";
$langDoc="Dokumenter";
$langVideo="Multimedia";
$langWorks="Studerendes Opgaver";
$langCourseProgram="Kursusprogram";
$langAnnouncements="Meddelelser";
$langUsers="Deltagerliste";
$langForums="Diskussionsforum";
$langExercices="�velser";
$langStatistics="Statistikker";
$langAddPageHome="L�g et dokument ud p� kursushjemmesiden";
$langLinkSite="Tilf�j link til kursushjemmesiden";
$langModifyInfo="�ndre kursusinformation";



// Other SQL tables
$langAgendaTitle="Tuesday the 11th of December - First lesson : Newton 18";
$langAgendaText="General introduction to philosophy and methodology principles";
$langMicro="Street interviews";
$langVideoText="Dette er et eksempel p� en Video fil. Du kan l�gge enhver audio og video fil ud (.mov, .rm, .mpeg...), men husk at de studerende skal have den n�dvendige plug-in for at kunne afspille filen";
$langGoogle="Hurtig og st�rk s�gemaskine";
$langIntroductionText="Dette er introduktionsteksten til dit kursus. For at ersatte den med din egen tekst, Klik p� <b>�ndre</b>.";
$langIntroductionTwo="Her kan den studerende eller gruppen l�gge dokumenter ud p� kursushjemmesiden. Hvis formatet er HTML, m� filen ikke indeholde billeder.";
$langCourseDescription="skriv her den beskrivelse, som vil kunne ses p� kursuslisten.";
$langProfessor="Underviseren";
$langAnnouncementEx="Dette er et eksempel p� en meddelelse. Kun underviseren og andre tildelt administrationsrettigheder p� kurset kan skrive meddelelser.";
$langJustCreated="Du har nu oprettet kursushjemmesiden";
$langEnter="Tilbage til min kursusliste";
$langMillikan="Millikan experiment";
$langCourseDesc = "Kursusbeskrivelse";
 // Groups
$langGroups="Grupper";
$langCreateCourseGroups="Grupper";
$langCatagoryMain="�ben diskussionsliste";
$langCatagoryGroup="Gruppediskussionslister";
$langChat ="Chat";
?>