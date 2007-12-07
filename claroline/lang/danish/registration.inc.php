<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.4.0 $Revision$
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
	  |   Danish Translation                                                |



      +----------------------------------------------------------------------+
      | Danish Translator :  Helle meldgaard <helle@iktlab.au.dk>            |
      |                                                                      |                        
      +----------------------------------------------------------------------+
 */

// userMAnagement
$langAdminOfCourse		= "admin";  //
$langSimpleUserOfCourse = "normal"; // strings for synopsis
$langIsTutor  			= "tutor"; //

$langCourseCode			= "Kursus";	// strings for list Mode
$langParamInTheCourse 	= "Status"; //

$langAddNewUser = "Tilf�j en bruger";
$langMember ="registreret";

$langDelete	="slet";
$langLock	= "l�se";
$langUnlock	= "�bne";
// $langOk

$langHaveNoCourse = "ingen kursus";


$langFirstname = "Efternavn"; // by moosh
$langLastname = "Fornavn"; // by moosh
$langEmail = "Email";// by moosh
$langRetrieve ="Genopret identifikationsinformationen";// by moosh
$langMailSentToAdmin = "En email er sendt til administrator.";// by moosh
$langAccountNotExist = "Kontoen er ikke fundet.<BR>".$langMailSentToAdmin." Han/hun vil s�ge manuelt.<BR>";// by moosh
$langAccountExist = "Denne konto eksisterer.<BR>".$langMailSentToAdmin."<BR>";// by moosh
$langWaitAMailOn = "En email kan sendes til ";// by moosh
$langCaseSensitiveCaution = "Systemet er case sensitivt.";// by moosh
$langDataFromUser = "Data sendt af brugeren";// by moosh
$langDataFromDb = "Data i denne database";// by moosh
$langLoginRequest = "Login foresp�rgsel til";// by moosh
$langExplainFormLostPass = "Skriv hvad du tror, du skrev i din registrering.";// by moosh
$langTotalEntryFound = "Indgang fundet";// by moosh
$langEmailNotSent = "Der er noget galt, send en email til ";// by moosh
$langYourAccountParam = "Dette er dine login data";// by moosh
$langTryWith ="Pr�v med";// by moosh
$langInPlaceOf ="og ikke med ";// by moosh
$langParamSentTo = "Identifikationsinformation er sendt til ";// by moosh

// REGISTRATION - AUTH - inscription.php
$langRegistration="Registrering";
$langName="Fornavn";
$langSurname="Efternavn";
$langUsername="Brugernavn";
$langPass="Adgangskode";
$langConfirmation="Bekr�ft adgangskode";
$langEmail="Email";
$langStatus="Handling";
$langRegStudent="F�lg kursus";
$langRegAdmin="Opret kursushjemmeside";

// inscription_second.php
$langPassTwice="Du skrev to forskellige adgangskoder. Brug din browsers tilbageknap og pr�v igen.";
$langEmptyFields="Du udfyldte ikke alle felter. Brug din browsers tilbageknap og pr�v igen.";
$langUserFree="Det valgte brugernavn bruges af en anden. Brug din browsers tilbageknap og v�lg et andet.";
$langYourReg="Din registrering til";
$langDear="K�re";
$langYouAreReg="Du er registreret p�";
$langSettings="med f�lgende brugernavn:";
$langAddress="Adressen p� ";
$langIs="er";
$langProblem="Du er velkommen til at kontakte os i tilf�lde af problemer.";
$langFormula="Med venlig hilsen";
$langManager="Support";
$langPersonalSettings="Dine personlige oplysninger er registreret og en email er afsendt til din emailadresse, s� du kan huske dit brugernavn og din adgangskode. 
<i>Husk at der er forskel p� store og sm� bogstaver (case sensitive)</i>, n�r du skal logge dig p�.</p>";
$langNowGoChooseYourCourses ="V�lg nu de kurser p� kursusoversigten som du �nsker adgang til.";
$langNowGoCreateYourCourse  ="Du kan nu oprette dine kurser";
$langYourRegTo="Du er registreret til";
$langIsReg="er blevet opdateret";
$langCanEnter="Du kan nu g� ind p�<a href=../../index.php>Mine Kurser</a>";


// profile.php

$langModifProfile="�ndre min profil";
$langPassTwo="Du har skrevet to forskellige adgangskoder";
$langAgain="Pr�v igen!";
$langFields="Du efterlod tomme felter";
$langUserTaken="Det valgte brugernavn bruges af en anden";
$langEmailWrong="Email adressen er mangelfuld eller indeholder ugyldige tegn";
$langProfileReg="Din nye brugerprofil er gemt";
$langHome="Tilbage til min kursusoversigt";
$langMyStats = "Se statistikkerne";

// user.php


$langUsers="Deltagere";
$langModRight="�ndre administrationsrettigheder";
$langNone="Ingen";
$langAll="Alle";
$langNoAdmin="har nu <b>INGEN administrationsrettigheder p� dette website</b>";
$langAllAdmin="har nu <b>ALLE administrationsrettigheder p� dette website</b>";
$langModRole="�ndre titel p�";
$langRole="Titel";
$langIsNow="er nu";
$langInC="p� dette kursus";
$langFilled="Du har efterladt nogle tomme felter.";
$langUserNo="Det brugernavn du valgte ";
$langTaken="er allerede i brug. V�lg et andet.";
$langOneResp="En af kursusadministratorerne";
$langRegYou="har registreret dig p� dette kursus";
$langTheU="Deltageren";
$langAddedU="er blevet tilf�jet. En email er blevet afsendt med deltagerens brugernavn ";
$langAndP="og adgangskode";
$langDereg="er blevet afmeldt fra dette kursus";
$langAddAU="Tilf�j en bruger";
$langStudent="studerende";
$langBegin="begynd.";
$langPreced50 = "Foreg�ende 50";
$langFollow50 = "N�ste 50";
$langEnd = "slut";
$langAdmR="Admin. rettigheder";
$langUnreg = "Afmeld";
$langAddHereSomeCourses = "<font size=2 face='Arial, Helvetica'><big>�ndre kursusoversigten</big><br><br>
Afkryds de kurser du �nsker at f�lge.<br>
Fjern afkrydsning p� de kurser, du ikke l�ngere �nsker at f�lge.<br> Klik derefter OK nederst p� denne oversigt";
$langTitular = "Ophavsmand";
$langCanNotUnsubscribeYourSelf = "Du kan ikke afmelde dig selv som administrator af et kursus, som du administrerer, kun en anden administtrator af kurset kan g�re dette.";

$langGroup="Gruppe";
$langUserNoneMasc="-";
$langTutor="Tutor";
$langTutorDefinition="Tutor (ret til at tilse grupper)";
$langAdminDefinition="Admin (ret til at �ndre kursushjemmesidens indhold)";
$langDeleteUserDefinition="Afmeld (slet fra deltagerlisten p� <b>dette</b> kursus)";
$langNoTutor = "er ikke tutor p� dette kursus";
$langYesTutor = "er tutor p� dette kursus";
$langUserRights="deltagernes retteigheder";
$langNow="nu";
$langOneByOne="Tilf�j deltagerne manuelt";
$langUserMany="Importere deltagerlisten ved hj�lp af tekstfil";
$langNo="nej";
$langYes="ja";
$langUserAddExplanation="hver linie i filen som sendes vil indeholde 5 felter vil indeholde
		5 felter: <b>Name&nbsp;&nbsp;&nbsp;Surname&nbsp;&nbsp;&nbsp;
		Login&nbsp;&nbsp;&nbsp;Password&nbsp;
		&nbsp;&nbsp;Email</b> adskilt med tabulator og i denne r�kkef�lge.
		Deltagerne modtager en email bekr�ftelse med login/adgangskode.";
$langSend="Send";
$langDownloadUserList="Opdater liste";
$langUserNumber="antal";
$langGiveAdmin="Giv adminrettighed";
$langRemoveRight="Fjern denne rettighed";
$langGiveTutor="Giv tutorrettighed";
$langUserOneByOneExplanation="Deltagerne vil modtage en email bekr�ftelse med login og adgangskode";
$langBackUser="Tilbage til deltagerlisten";
$langUserAlreadyRegistered="En deltager med samme fornavn/efternavn er allerede registreret
			 p� dette kursus.";

$langAddedToCourse="er blevet registreret p� dit kursus";
$langGroupUserManagement="Gruppestyring";
$langIsReg="Dine �ndringer er registreret";
$langPassTooEasy ="denne adgangskode er for nem. Brug en adgangskode som denne ";

$langIfYouWantToAddManyUsers="Hvis du �nsker at tilf�je en deltagerliste p� 
			dit kursus, venligst kontakt din egen webadministrator.";

$langCourses="Kurser";

$langLastVisits="Mit sidste bes�g";
$langSee		= "G� til";
$langSubscribe	= "Tilmeld";
$langCourseName	= "Kursusnavn";
$langLanguage	= "Sprog";

$langConfirmUnsubscribe = "Bekr�ft afmeldning af deltager";
$langAdded = "Tilf�jet";
$langDeleted = "Slettet";
$langPreserved = "Bevaret";

$langDate = "Dato";
$langAction = "Handling";
$langLogin = "Log In";

$langModify = "�ndre";

$langUserName = "Brugernavn";

$langEdit = "Rediger";
$langCourseManager = "Kursusadministrator";
$langManage				= "Styring af e-learning";
$langAdministrationTools = "Administrationsv�rkt�jer";
$langModifProfile	= "�ndre min profil";
$langUserProfileReg	= "opdateret";



$lang_lost_password = "Glemt din adgangskode";
$lang_enter_email_and_well_send_you_password ="Skriv den email adresse du brugte da du registrerede dig og vi vil sende dig din adgangskode.";
$lang_your_password_has_been_emailed_to_you = "Din adgangskode er blevet sendt til dig pr. email.";
$lang_no_user_account_with_this_email_address = "Der er ingen konto med denne email addresse.";
$langCourses4User = "Denne deltagers kurser";
$langCoursesByUser = "Deltagerens kurser";

?>
