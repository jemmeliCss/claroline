<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.0 $Revision$
      +----------------------------------------------------------------------
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or
      |   modify it under the terms of the GNU General Public License
      |   as published by the Free Software Foundation; either version 2
      |   of the License, or (at your option) any later version.
      +----------------------------------------------------------------------+
      | Authors: Thomas Depraetere <depraetere@ipm.ucl.ac.be>
      |          Hugues Peeters    <peeters@ipm.ucl.ac.be>
      |          Christophe Gesch� <gesche@ipm.ucl.ac.be>
      +----------------------------------------------------------------------+
 */

/***************************************************************
*                   Language translation
****************************************************************
GOAL
****
Translate the interface in chosen language
*****************************************************************/

$langLabelCourseAdmin = "Administration du cours"; // JCC
$langModifInfo="Propri�t�s du cours";
$langModifDone="Les informations ont �t� modifi�es";
$langHome="Retourner � la page d'accueil";
$langCode="Code du cours";
$langDelCourse="Supprimer ce cours";
$langProfessor="Gestionnaire de cours";
$langProfessors="Gestionnaire(s) de cours";
$langCourseTitle="Titre du cours";
$langFaculty="Facult�";
$langDescription="Description";
$langConfidentiality="Confidentialit�";
$langPublicAccess="Acc�s public (depuis la page d'accueil de Claroline sans identifiant)";
$langPrivOpen="Acc�s priv�, inscription ouverte";
$langPrivateAccess="Acc�s priv� (site r�serv� aux personnes figurant dans la liste <a href=../user/user.php>utilisateurs</a>)";
$langForbidden="Acc�s non autoris�";
$langLanguage="Langue";
$langConfTip="Par d�faut, votre cours est accessible � tout le monde. Si vous souhaitez un minimum de confidentialit�, le plus simple est d'ouvrir
l'inscription pendant une semaine, de demander aux �tudiants de s'inscrire eux-m�mes
puis de fermer l'inscription et de v�rifier dans la liste des utilisateurs les intrus �ventuels.";
$langTipLang="Cette langue vaudra pour tous les visiteurs de votre site de cours.";
$langEditToolList="Modifier la liste d'outils";
$langIntroCourse="Bienvenue sur la page d'accueil du cours.<br /><br />Vous pouvez sur cette page :
<li class=HelpText>activer ou d�sactiver des outils (cliquer sur le bouton '".$langEditToolList."' dans le bas � gauche).
<li class=HelpText>changer les propri�t�s ou voir les statistiques (Cliquer sur les liens correspondants).<br /><br />
Pour pr�senter votre cours aux �tudiants, cliquer sur ce bouton.<br />";

// Change Home Page
$langUplPage="D�poser une page et la lier � l\'accueil"; // JCC 
$langLinkSite="Ajouter un lien sur la page d\'accueil";
$langVid="Vid�o";
$langProgramMenu="Cahier des charges";
$langStats="Statistiques";

// delete_course.php
$langDelCourse="Supprimer la totalit� du cours";
$langCourse="Le cours ";
$langHasDel="a �t� supprim�";
$langBackHomeOf="Retour � la page d'accueil de ";
$langByDel="En supprimant ce site, vous supprimerez tous les documents
qu'il contient et radierez tous les �tudiants qui y sont inscrits. <p>Voulez-vous r�ellement supprimer le cours"; // JCC 
$langY="OUI";
$langN="NON";

$langDepartmentUrl = "URL du d�partement"; // JCC
$langDepartmentUrlName = "D�partement";
$langEmail="E-mail"; // JCC

$langArchive="Archive";
$langArchiveCourse = "Archivage du cours";
$langRestoreCourse = "Restauration d'un cours";
$langRestore="Restaurer";
$langCreatedIn = "cr�� dans";
$langCreateMissingDirectories ="Cr�ation des r�pertoires manquants";
$langCopyDirectoryCourse = "Copie des fichiers du cours";
$langDisk_free_space = "Espace libre";
$langBuildTheCompressedFile ="Cr�ation du fichier compress�";
$langFileCopied = "fichier copi�";
$langArchiveLocation = "Emplacement de l'archive";
$langSizeOf ="Taille de";
$langArchiveName ="Nom de l'archive";
$langBackupSuccesfull = "Archiv� avec succ�s";
$langBUCourseDataOfMainBase = "Archivage des donn�es du cours dans la base de donn�es principale pour";
$langBUUsersInMainBase = "Archivage des donn�es des utilisateurs dans la base de donn�es principale pour";
$langBUAnnounceInMainBase="Archivage des donn�es des annonces dans la base de donn�es principale pour";
$langBackupOfDataBase="Archivage de la base de donn�es";
$langBackupCourse="Archiver ce cours";

$langCreationDate = "Cr��";
$langExpirationDate  = "Date d'expiration";
$langPostPone = "Post pone"; // JCC ???
$langLastEdit = "Derni�re �dition";
$langLastVisit = "Derni�re visite";

$langSubscription="Inscription";
$langCourseAccess="Acc�s au cours";

$langDownload="T�l�charger";
$langConfirmBackup="Voulez-vous vraiment archiver le cours";

$langCreateSite="Cr�er un site de cours";

$langRestoreDescription="Le cours se trouve dans une archive que vous pouvez s�lectionner ci-dessous.<br><br>
Lorsque vous aurez cliqu� sur 'Restaurer', l'archive sera d�compress�e et le cours recr��."; // JCC
$langRestoreNotice="Ce script ne permet pas encore la restauration automatique des utilisateurs, mais les donn�es sauvegard�es dans le fichier 'users.csv' sont suffisantes pour que l'administrateur puisse effectuer cette op�ration manuellement."; // JCC
$langAvailableArchives="Liste des archives disponibles";
$langNoArchive="Aucune archive n'a �t� s�lectionn�e";
$langArchiveNotFound="Archive introuvable";
$langArchiveUncompressed="L'archive a �t� d�compress�e et install�e.";
$langCsvPutIntoDocTool="Le fichier 'users.csv' a �t� plac� dans l'outil Documents."; // JCC

$langSearchCours	= "Revenir sur les informations du cours";
$langManage			= "Gestion du campus";

$langAreYouSureToDelete ="�tes-vous s�r de vouloir supprimer ";
$langBackToAdminPage = "Retour � la page d'administration";
$langToCourseSettings = "Retour aux propri�t�s du cours";
$langSeeCourseUsers = "Voir les utilisateurs du cours";
$langBackToCourseList = "Retour � la liste de cours";
$langBackToList = "Retour � la liste";
$langAllUsersOfThisCourse = "Utilisateurs de ce cours";
$langViewCourse = "Voir le cours";
$langIntroEditToolList="S�lectionner les outils que vous voulez activer.
Les outils invisibles seront gris�s dans votre page d'accueil du cours."; // JCC 
$langTools="Outils";
$langActivate="Activer";
$langAddExternalTool="Ajouter un lien externe.";
$langAddedExternalTool="Lien externe ajout�.";  
$langUnableAddExternalTool="Impossible d'ajouter cet outil";
$langMissingValue="Valeur manquante";
$langExternalToolName="Nom du  lien";
$langExternalToolUrl="URL du lien"; // JCC
$langChangedTool="L'acc�s au lien a �t� chang�";
$langUnableChangedTool="Impossible de changer l'acc�s au lien";
$langUpdatedExternalTool="Lien externe modifi�";
$langUnableUpdateExternalTool="Impossible de changer le lien externe";
$langDeletedExternalTool='Lien externe effac�';
$langUnableDeleteExternalTool='Impossible d\'effacer le lien externe';
$langAdministration="Administration";

?>
