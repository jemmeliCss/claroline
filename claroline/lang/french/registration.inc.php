<?php # $Id$
//----------------------------------------------------------------------
// CLAROLINE
//----------------------------------------------------------------------
// Copyright (c) 2001-2004 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

// lang vars
$langAdminOfCourse		= "admin";  //
$langSimpleUserOfCourse = "normal"; // strings for synopsis
$langIsTutor  			= "tuteur"; //

$langCourseCode			= "Cours";	// strings for list Mode
$langParamInTheCourse 	= "Statut"; //

$langSummaryTable = "Cette table liste les utilisateurs du cours.";
$langSummaryNavBar = "Barre de navigation";
$langAddNewUser = "Ajouter un utilisateur au syst�me";
$langMember ="inscrit";

$langDelete	="supprimer";
$langLock	= "bloquer";
$langUnlock	= "lib�rer"; // JCC 

$langHaveNoCourse = "Pas de cours"; // JCC 

$langFirstname = "Pr�nom"; // JCC 
$langLastname = "Nom";
$langEmail = "E-mail"; // JCC
$langAbbrEmail = "E-mail"; // JCC 
$langRetrieve ="Retrouver  mes param�tres d'identification";
$langMailSentToAdmin = "Un e-mail a �t� adress� � l'administrateur."; // JCC 
$langAccountNotExist = "Ce compte semble ne pas exister.<BR>".$langMailSentToAdmin." Il fera une recherche manuelle.<BR><BR>";
$langAccountExist = "Ce compte semble exister.<BR> Un e-mail a �t� adress� � l'administrateur. <BR><BR>"; // JCC 
$langWaitAMailOn = "Attendez vous � une r�ponse sur ";
$langCaseSensitiveCaution = "Le syst�me fait la diff�rence entre les minuscules et les majuscules.";
$langDataFromUser = "Donn�es envoy�es par l'utilisateur";
$langDataFromDb = "Donn�es correspondantes dans la base de donn�es"; // JCC 
$langLoginRequest = "Demande de login";
$langTotalEntryFound = " Nombre d'entr�es trouv�es"; // JCC 
$langEmailNotSent = "Quelque chose n'a pas fonctionn�, veuillez envoyer ceci �"; // JCC 
$langTryWith ="essayez avec ";
$langInPlaceOf ="au lieu de";
$langParamSentTo = "Vos param�tres de connection sont envoy�s sur l'adresse";

// lost password
$langLostPassword = "Mot de passe perdu";
$langExplainFormLostPass = "Tapez ce que vous pensez avoir introduit comme donn�es lors de votre inscription.";
$langEmailNotSent = "Quelque chose n'a pas fonctionn�, veuillez envoyer ceci � ";
$langYourAccountParam = "Voici vos param�tres de connexion";
$langPasswordHasBeenEmailed = "Votre mot depasse a �t� envoy� � l'adresse ";
$langEmailAddressNotFound = "Il n'existe aucun compte utilisateur li� � cette adresse.";
$langEnterMail = "Saisissez votre adresse email et confirmez.";


// REGISTRATION - AUTH - inscription.php
$langRegistration="Inscription";

$langSurname="Pr�nom";
$langUserName="Nom d'utilisateur";
$langPass="Mot de passe";
$langConfirmation="Confirmation";
$langStatus="Action";
$langRegStudent="M'inscrire � des cours";
$langRegAdmin="Cr�er des sites de cours";
$langPhone = "T�l�phone"; // JCC cette variable manquait
$langSaveChange ="Enregistrer les changements"; // JCC cette variable manquait
$langTitular = "Gestionnaire de cours";
$langRegister = "S'inscrire";
// inscription_second.php


$langRegistration = "Inscription";
$langPassTwice    = "Vous n'avez pas tap� deux fois le m�me mot de passe.
Utilisez le bouton de retour en arri�re de votre navigateur
et recommencez.";

$langEmptyFields = "Vous n'avez pas rempli tous les champs.
Utilisez le bouton de retour en arri�re de votre navigateur et recommencez.";

$langPassTooEasy ="Ce mot de passe est trop simple. Choisissez un autre mot de passe  comme par exemple : "; // JCC 

$langUserFree    = "Le nom d'utilisateur que vous avez choisi est d�j� pris.
Utilisez le bouton de retour en arri�re de votre navigateur
et choisissez-en un autre.";

$langYourReg                = "Votre inscription sur";
$langDear                   = "Cher(�re)";
$langYouAreReg              = "Vous �tes inscrit(e) sur";
$langSettings               = "avec les param�tre suivants:\nNom d'utilisateur:";
$langAddress                = "L'adresse de";
$langIs                     = "est";
$langProblem                = "En cas de probl�me, n'h�sitez pas � prendre contact avec nous";
$langFormula                = "Cordialement";
$langManager                = "Gestionnaire";
$langPersonalSettings       = "Vos coordonn�es personnelles ont �t� enregistr�es et un e-mail vous a �t� envoy�
pour vous rappeler votre nom d'utilisateur et votre mot de passe.</p>";
$langNowGoChooseYourCourses ="Vous  pouvez maintenant aller s�lectionner les cours auxquels vous souhaitez avoir acc�s.";
$langNowGoCreateYourCourse  = "Vous  pouvez maintenant aller cr�er votre cours";
$langYourRegTo              = "Vos modifications";
$langIsReg                  = "Vos modifications ont �t� enregistr�es";
$langCanEnter               = "Vous pouvez maintenant <a href=../../index.php>entrer dans le campus</a>";

// profile.php

$langModifProfile = "Modifier mon profil";
$langPassTwo      = "Vous n'avez pas tap� deux fois le m�me mot de passe";
$langAgain        = "Recommencez !"; // JCC 
$langFields       = "Vous n'avez pas rempli tous les champs";
$langUserTaken    = "Le nom d'utilisateur que vous avez choisi est d�j� pris";
$langEmailWrong   = "L'adresse d'e-mail que vous avez introduite n'est pas compl�te
ou contient certains caract�res non valides"; // JCC 
$langProfileReg   = "Votre nouveau profil a �t� enregistr�";
$langHome         = "Retourner � la page d'accueil";
$langMyStats      = "Voir mes statistiques";
$langReturnSearchUser="Revenir � l'utilisateur"; // JCC 


// user.php

$langUsers    = "Utilisateurs";
$langModRight ="Modifier les droits de : ";
$langNone     ="non";

$langNoAdmin            = "n'a d�sormais <b>aucun droit d'administration sur ce site</b>";
$langAllAdmin           = "a d�sormais <b>tous les droits d'administration sur ce site</b>";
$langModRole            = "Modifier le r�le de";
$langRole               = "R�le (facultatif)";
$langIsNow              = "est d�sormais";
$langInC                = "dans ce cours";
$langFilled             = "Vous n'avez pas rempli tous les champs.";
$langUserNo             = "Le nom d'utilisateur que vous avez choisi";
$langTaken              = "est d�j� pris. Choisissez-en un autre.";
$langOneResp            = "L'un des gestionnaires du cours";
$langRegYou             = "vous a inscrit sur";
$langTheU               ="L'utilisateur";
$langAddedU             ="a �t� ajout�. Si vous avez introduit son adresse, un message lui a �t� envoy� pour lui communiquer son nom d'utilisateur";
$langAndP               = "et son mot de passe";
$langDereg              = "a �t� radi� de ce cours"; // JCC 
$langAddAU              = "Ajouter un utilisateur";
$langStudent            = "�tudiant";
$langBegin              = "d�but";
$langPreced50           = "50 pr�c�dents";
$langFollow50           = "50 suivants";
$langEnd                = "fin";
$langAdmR               = "Admin";
$langUnreg              = "Radier"; // JCC 
$langAddHereSomeCourses = "<font size=2 face='arial, helvetica'><big>Mes cours</big><br><br>
			Cochez les cours que vous souhaitez suivre et d�cochez ceux que vous
			ne voulez plus suivre (les cours dont vous �tes gestionnaire
			ne peuvent �tre d�coch�s). Cliquez ensuite sur Ok en bas de la liste.";

$langCanNotUnsubscribeYourSelf = "Vous ne pouvez pas vous radier
				vous-m�me d'un cours dont vous �tes administrateur.
				Seul un autre administrateur du cours peut le faire."; // JCC 

$langGroup="Groupe";
$langUserNoneMasc="-";

$langTutor                = "Tuteur";
$langTutorDefinition      = "Tuteur (droit de superviser des groupes)";
$langAdminDefinition      = "Administrateur (droit de modifier le contenu du site)";
$langDeleteUserDefinition ="Radier (supprimer de la liste des utilisateurs de <b>ce</b> cours)"; // JCC 
$langNoTutor              = "n'est pas tuteur pour ce cours";
$langYesTutor             = "est tuteur pour ce cours";
$langUserRights           = "Droits des utilisateurs";
$langNow                  = "actuellement";
$langOneByOne             = "Ajouter manuellement un utilisateur";
$langUserMany             = "Importer une liste d'utilisateurs via un fichier texte";
$langNo                   = "non";
$langYes                  = "oui";

$langUserAddExplanation   = "Chaque ligne du fichier � envoyer
		contiendra n�cessairement et uniquement les
		5 champs <b>Nom&nbsp;&nbsp;&nbsp;Pr�nom&nbsp;&nbsp;&nbsp;
		Nom d'utilisateur&nbsp;&nbsp;&nbsp;Mot de passe&nbsp;
		&nbsp;&nbsp;E-mail</b> s�par�s par des tabulations
		et pr�sent�s dans cet ordre. Les utilisateurs recevront
		par e-mail leur nom d'utilisateur et leur mot de passe."; // JCC 

$langSend             = "Envoyer";
$langDownloadUserList = "Envoyer la liste";
$langUserNumber       = "nombre";
$langGiveAdmin        = "Rendre admin";
$langRemoveRight      = "Retirer ce droit";
$langGiveTutor        = "Rendre tuteur";

$langUserOneByOneExplanation = "Il recevra par e-mail son nom d'utilisateur et son mot de passe"; // JCC 
$langBackUser                = "Retour � la liste des utilisateurs";
$langUserAlreadyRegistered   = "Un utilisateur ayant m�mes nom et pr�nom est d�j� inscrit dans le cours.";

$langAddedToCourse           = "a �t� inscrit � votre cours";

$langGroupUserManagement     = "Gestion des groupes";

$langIfYouWantToAddManyUsers = "Si vous voulez ajouter une liste d'utilisateurs � votre cours, contactez votre web administrateur.";

$langLastVisits = "Mes derni�res visites";
$langSee        = "Voir";
$langSubscribe  = "M'inscrire<br>coch�&nbsp;=&nbsp;oui";
$langCourseName = "Nom du cours";
$langLanguage   = "Langue";

$langConfirmUnsubscribe = "Confirmez la radiation de cet utilisateur"; // JCC 
$langAdded              = "Ajout�s";
$langDeleted            = "Supprim�s";
$langPreserved          = "Conserv�s";
$langDate               = "Date";
$langAction             = "Action";
//$langLogout             = "Quitter";
$langModify             = "Modifier";
$langUserName           = "Nom d'utilisateur"; // JCC 
$langEdit               = "Editer";

$langCourseManager       = "Gestionnaire de cours";
$langManage              = "Gestion du campus";
$langAdministration = "Administration";
$langModifProfile	     = "Modifier le profil";
$langUserProfileReg	     = "La modification a �t� effectu�e";
$lang_lost_password      = "Mot de passe perdu";

$lang_enter_email_and_well_send_you_password  = "Tapez l'adresse d'e-mail que vous avez utilis�e pour vous enregistrer et nous vous enverrons votre mot de passe."; // JCC
$lang_your_password_has_been_emailed_to_you   = "Votre mot de passe vous a �t� envoy� par e-mail."; // JCC
$lang_no_user_account_with_this_email_address = "Il n'y a pas de compte utilisateur avec cette adresse d'e-mail."; // JCC
$langCourses4User  = "Cours pour cet utilisateur";
$langCoursesByUser = "Vue d'ensemble des cours par utilisateur";

$langAddImage = "Ajoutez une photo";
$langUpdateImage = "Changez de photo";
$langDelImage = "Retirez la photo";
$langOfficialCode = "Code officiel";

$langAuthInfo = "Param�tres de connection";
$langEnter2passToChange = "Tapez deux fois le nouveau mot de passe pour le changer, laissez vide pour garder l'ancien."; // JCC

$lang_SearchUser_ModifOk            = "Les modifications ont �t� effectu�es correctement";

$langNoUserSelected = "Aucun utilisateur n'a �t� selectionn�!";

// dialogbox messages

$langUserUnsubscribedFromCourse = "L'utilisateur a bien �t� radi� du cours"; // JCC 
$langUserNotUnsubscribedFromCourse = "Erreur!! vous ne pouvez pas radier un gestionnaire du cours";
?>
