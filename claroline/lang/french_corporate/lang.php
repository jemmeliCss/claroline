<?php
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.3.0 $Revision$                            |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2002 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   $Id$         |
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

/***************************************************************
*                   Language translation
****************************************************************
GOAL
****
Translate the interface in chosen language

*****************************************************************/



// GENERIC

$langModify="modifier";
$langDelete="effacer";
$langTitle="Titre";
$langHelp="aide";
$langOk="valider";
$langAddIntro="AJOUTER UN TEXTE D'INTRODUCTION";
$langBackList="Retour � la liste";





// index.php CAMPUS HOME PAGE

$langInvalidId="Cet identifiant n'est pas valide. Si vous n'�tes pas encore inscrit,
remplissez le <a href='claroline/auth/inscription.php'>formulaire d'inscription</a></font color>";
$langMyCourses="Mes sites";
$langCourseCreate="Cr�er un site pour les cadres";
$langModifyProfile="Modifier mon profil";
$langTodo="A faire";
$langWelcome="sites sont en libre acc�s ci-dessous. Les autres sites existants
n�cessitent un nom d'utilisateur et un mot de passe que l'on peut
obtenir en cliquant sur la mention 'inscription'. Il est possible aux
administrateurs et aux mod�rateurs de cr�er un nouveau site en cliquant sur la
mention 'inscription'.";
$langUserName="Nom d'utilisateur";
$langPass="Mot de passe";
$langEnter="Entrer";
$langHelp="Aide";
$langManager="Responsable";
$langPlatform="iCampus utilise la plate-forme";



// REGISTRATION - AUTH - INSCRIPTION
$langRegistration="Inscription";
$langName="Nom";
$langSurname="Pr�nom";

// COURSE HOME PAGE

$langAnnouncements="Annonces";
$langLinks="Liens";
$langWorks="Contributions des uns et des autres";
$langUsers="Utilisateurs";
$langStatistics="Statistiques";
$langCourseProgram="Cahier des charges";
$langAddPageHome="Ajouter page et lier � accueil";
$langLinkSite="Lien vers site depuis page d\'accueil";
$langModifyInfo="Modifier info cours";
$langDeactivate="d�sactiver";
$langActivate="activer";
$langInactiveLinks="Liens inactifs";
$langAdminOnly="R�serv� aux administrateurs";





// AGENDA

$langAddEvent="Ajouter un �v�nement";
$langDetail="D�tail";
$langHour="Heure";
$langLasting="Lieu";
$month_default="mois";
$january="janvier";
$february="f�vrier";
$march="mars";
$april="avril";
$may="mai";
$june="juin";
$july="juillet";
$august="ao�t";
$september="septembre";
$october="octobre";
$november="novembre";
$december="d�cembre";
$year_default="ann�e";
$year1="2001";
$year2="2002";
$year3="2003";
$hour_default="heure";
$hour1="08h30";
$hour2="09h30";
$hour3="10h45";
$hour4="11h45";
$hour5="12h30";
$hour6="12h45";
$hour7="13h00";
$hour8="14h00";
$hour9="15h00";
$hour10="16h15";
$hour11="17h15";
$hour12="18h15";
$lasting_default="dur�e";
$lasting1="30min";
$lasting2="45min";
$lasting3="1h";
$lasting4="1h30";
$lasting5="2h";
$lasting6="4h";





// DOCUMENT

$langDownloadFile= "T�l�charger sur le serveur le fichier";
$langDownload="t�l�charger";
$langCreateDir="Cr�er un r�pertoire";
$langName="Nom";
$langNameDir="Nom du nouveau r�pertoire";
$langSize="Taille";
$langDate="Date";
$langMove="D�placer";
$langRename="Renommer";
$langComment="Commentaire";
$langVisible="Visible/invisible";
$langCopy="Copier";
$langTo="vers";
$langNoSpace="Le t�l�chargement a �chou�. Il n'y a plus assez de place dans votre r�pertoire";
$langDownloadEnd="Le t�l�chargement est termin�";
$langFileExists="Impossible d'effectuer cette op�ration.<br>Un fichier portant ce nom existe d�j�.";
$langIn="en";
$langNewDir="nom du nouveau r�pertoire";
$langImpossible="Impossible d'effectuer cette op�ration";
$langAddComment="ajouter/modifier un commentaire �";
$langUp="remonter";



// WORKS

$langTooBig="Vous n'avez pas choisi de fichier � envoyer ou bien le fichier est trop gros.";
$langListDeleted="La liste a �t� compl�tement effac�e";
$langDocModif="Le document a �t� modifi�";
$langDocAdd="Le document a �t� ajout�";
$langDocDel="Le document a �t� effac�";
$langTitleWork="Titre du document en toutes lettres";
$langAuthors="Auteurs";
$langDescription="Description �ventuelle";
$langDelList="Effacer compl�tement la liste";



// ANNOUCEMENTS
$langAnnEmpty="Les annonces ont �t� vid�es compl�tement";
$langAnnModify="L'annonce a �t� modifi�e";
$langAnnAdd="L'annonce a �t� ajout�e";
$langAnnDel="L'annonce a �t� effac�e";
$langPubl="Publi�e le";
$langAddAnn="Ajouter une annonce";
$langContent="Contenu";
$langEmptyAnn="Vider compl�tement les annonces";




// OLD
$langAddPage="Ajouter une page";
$langPageAdded="La page a �t� ajout�e";
$langPageTitleModified="L'intitul� de la page a �t� modifi�";
$langAddPage="Ajouter une page";
$langSendPage="Page � envoyer";
$langCouldNotSendPage="Ce fichier n'est pas au format HTML et n'a pu �tre envoy�.
Si vous voulez envoyer vers le serveur des documents non HTML (PDF, Word, Power Point, Vid�o, etc.) utilisez <a href=../document/document.php>Documents</a>";
$langAddPageToSite="Ajouter une page au site";
$langNotAllowed="Vous n'�tes pas identifi� en tant que responsable de ce site";
$langExercices="Quizz";

?>