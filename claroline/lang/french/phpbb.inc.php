<?php // $Id$
/***************************************************************************
 *                           lang_english.php  -  description
 *                              -------------------
 *     begin                : Sat Dec 16 2000
 *	    copyright            : (C) 2001 The phpBB Group
 *  	 email                : support@phpbb.com
 *
 *     $Id$
 *
 *  ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/



// GENERIC

$langModify="Modifier"; // JCC
$langDelete="Effacer"; // JCC
$langTitle="Titre";
$langHelp="Aide"; // JCC
$langOk="Valider"; // JCC
$langBackList="Retour � la liste";



$langLoginBeforePost1 = "Pour publier des messages dans le forum, ";
$langLoginBeforePost2 = "vous devez d'abord ";
$langLoginBeforePost3 = "vous identifier sur le Campus";
$langNoPost = "Pas de message";

// page_header.php

$langNewTopic="Lancer un nouveau sujet";
$langBackTo="Retourner dans : ";

$langReply="R�pondre";
$langHelp="Aide"; // JCC
$langAdm="Administrer";
$langQuote="Citer"; // JCC
$langEditDel="Editer/Effacer"; // JCC
$langSeen="Vu";
$langLastMsg="Dernier message";
$langLastMsgs ="Derniers Messages";

$l_forum 	= "Forum";
$l_forums	= "Forums";
$l_topic	= "Sujet";
$l_topics 	= "Sujets";
$l_replies	= "R�ponses";
$l_poster	= "Initiateur";
$l_author	= "Auteur";
$l_views	= "Vus";
$l_post 	= "Message";
$l_posts 	= "Messages";
$l_message	= "Message";
$l_messages	= "Messages";
$l_subject	= "Sujet";
$l_body		= "$l_message";
$l_from		= "De";   // Message from
$l_moderator 	= "Mod�rateur";
$l_username 	= "Nom d'utilisateur";
$l_password 	= "Mot de passe";
$l_email 	= "E-mail"; // JCC
$l_emailaddress	= "Adresse e-mail"; // JCC
$l_preferences	= "Pr�f�rences";

$l_anonymous	= "Anonyme";  // Post
$l_guest	= "Invit�"; // Whosonline
$l_noposts	= "Pas de $l_posts";
$l_joined	= "Inscrit";
$l_gotopage	= "Va vers page";
$l_nextpage 	= "Page suivante";
$l_prevpage     = "Page pr�c�dente";
$l_go		= "Aller �";
$l_selectforum	= "S�lectionnez un $l_forum";

$l_date		= "Date";
$l_number	= "Nombre";
$l_name		= "Nom";
$l_options 	= "Options";
$l_submit	= "Poster";
$l_confirm 	= "Confirmer";
$l_enter 	= "Entrer";
$l_by		= "par"; // Post� par
$l_ondate	= "le"; // Ce message a �t� �dit� par: $username le $date
$l_new          = "Nouveaux";

$l_html		= "HTML";
$l_bbcode	= "BBcode";
$l_smilies	= "Smilies";
$l_on		= "On";
$l_off		= "Off";
$l_yes		= "Oui";
$l_no		= "Non";

$l_click 	= "Cliquez";
$l_here 	= "ici";
$l_toreturn	= "pour retourner";
$l_returnindex	= "$l_toreturn � l'index du forum";
$l_returntopic	= "$l_toreturn � la liste des sujets du forum.";

$l_error	= "Erreur";
$l_tryagain	= "Revenez en arri�re et r�essayez.";
$l_mismatch 	= "Les mots de passe ne correspondent pas.";
$l_userremoved 	= "Ce membre a �t� retir� de la base de donn�es.";
$l_wrongpass	= "Vous avez tap� un mauvais mot de passe.";
$l_userpass	= "Veuillez taper votre nom d'utilisateur et votre mot de passe."; // JCC
$l_banned 	= "Vous avez �t� banni de ce forum. Contactez l'administrateur syst�me si vous avez des questions.";
$l_enterpassword= "Vous devez taper votre mot de passe."; // JCC

$l_nopost	= "Vous n'avez pas la possibilit� de poster sur ce forum.";
$l_noread	= "Vous n'avez pas la possibilit� de lire ce forum.";

$l_lastpost 	= "Dernier $l_post";
$l_sincelast	= "depuis votre derni�re visite";
$l_newposts 	= "Nouveaux $l_posts $l_sincelast";
$l_nonewposts 	= "Pas de nouveaux $l_posts $l_sincelast";

// Index page
$l_indextitle	= "Index du forum";

// Members and profile
$l_profile	= "Profil";
$l_register	= "S'enregistrer"; // JCC
$l_onlyreq 	= "Demand� seulement si chang�";
$l_location 	= "De";
$l_viewpostuser	= "Voir les messages de ce membre";
$l_perday       = "$l_messages par jour";
$l_oftotal      = "du total";
$l_url 		= "URL"; // JCC
$l_icq 		= "ICQ";
$l_icqnumber	= "Num�ro ICQ";
$l_icqadd	= "Ajouter";
$l_icqpager	= "Pager";
$l_aim 		= "AIM";
$l_yim 		= "YIM";
$l_yahoo 	= "Yahoo Messenger";
$l_msn 		= "MSN";
$l_messenger 	= "MSN Messenger";
$l_website 	= "Adresse du Site Web";
$l_occupation 	= "Emploi";
$l_interests 	= "Loisirs";
$l_signature 	= "Signature";
$l_sigexplain 	= "C'est un texte qui peut �tre ajout� aux messages que vous postez.<BR>255 caract�res maximum !";
$l_usertaken	= "Le $l_username que vous avez choisi existe d�j�.";
$l_userdisallowed= "Le $l_username choisi n'a pas �t� autoris� par l'administrateur. $l_tryagain";
$l_infoupdated	= "Vos caract�ristiques sont mises � jour"; // JCC 
$l_publicmail	= "Autoriser les autres membres � voir votre $l_emailaddress";
$l_itemsreq	= "Les champs marqu�s par un * sont obligatoires";

// Viewforum
$l_viewforum	= "Voir le forum";
$l_notopics	= "Il n'y a pas de sujet sur ce forum. Vous pouvez en poster un.";
$l_islocked	= "$l_topic est ferm� (aucun nouveau $l_posts ne peut �tre post�)"; // JCC 
$l_moderatedby	= "Mod�r� par";

// Private forums
$l_privateforum	= "C'est un <b>forum priv�</b>.";
$l_private 	= "$l_privateforum<br>Note: vous devez autoriser les cookies pour utiliser les forums priv�s.";
$l_noprivatepost = "$l_privateforum Vous ne pouvez pas poster sur ce forum.";

// Viewtopic
$l_topictitle	= "Voir $l_topic";
$l_unregistered	= "Membre non enregistr�"; // JCC 
$l_posted	= "Post� le";
$l_profileof	= "Voir le profil de"; // JCC 
$l_viewsite	= "Voir le site web de";
$l_icqstatus	= "Etat $l_icq";  // Etat ICQ
$l_editdelete	= "Editer/Supprimer ce $l_post"; // JCC 
$l_replyquote	= "R�pondre en citant";
$l_viewip	= "Voir les IP (Mod�rateurs/Admins seulement)";
$l_locktopic	= "Fermer ce $l_topic";
$l_unlocktopic	= "Ouvrir ce $l_topic";
$l_movetopic	= "D�placer ce $l_topic";
$l_deletetopic	= "Effacer ce $l_topic";

// Functions
$l_loggedinas	= "Connect� sous";
$l_notloggedin	= "Non connect�";
$l_logout	= "D�connexion";
$l_login	= "Connexion";

// Page_header
$l_separator	= " > ";  // Included here because some languages have
		          // problems with high ASCII (Big-5 and the like).
$l_editprofile	= "Editer le profil"; // JCC 
$l_editprefs	= "Editer les $l_preferences";
$l_search	= "Rechercher";
$l_memberslist	= "Liste des membres"; // JCC 
$l_faq		= "FAQ";
$l_privmsgs	= "$l_messages priv�s"; // JCC 
$l_sendpmsg	= "Envoyer un message priv�"; // JCC 
$l_privnotify   = '<br>Vous avez %s1 <a href=\"%s2\">nouveau(x) message(s) priv�e(s)</a>.';

// Page_tail
$l_adminpanel	= "Panneau d'administration";
$l_poweredby	= "R�alis� gr�ce �"; 
$l_version	= "Version";

// Auth

// Register
$l_notfilledin	= "Erreur - vous n'avez pas rempli tous les champs requis.";

$l_welcomemail	=
"
$l_welcomesubj,

Veuillez conserver cet email dans vos archives.


Les informations sur votre compte sont les suivantes:

----------------------------
Nom de Membre: $username
Mot de Passe : $password
----------------------------

N'oubliez pas votre mot de passe, il est crypt� dans notre base de donn�es et nous ne pouvons le retrouver pour vous.
Cependant, si vous le perdiez, nous vous fournirions un script facile � utiliser, vous permettant de g�n�rer et de vous faire envoyer un nouveau mot de passe.

Merci de vous �tre enregistr�.

$email_sig
"; // JCC
$l_beenadded	= "Vous avez �t� ajout� � la base de donn�es.";
$l_thankregister= "Merci de vous �tre enregistr�!";
$l_useruniq	= "Doit �tre unique. Deux membres ne peuvent avoir le m�me nom d'utilisateur."; // JCC 
$l_storecookie	= "Mettez mon nom d'utilisateur dans un cookie pendant 1 an.";

// Prefs
$l_editprefs	= "Editez vos $l_preferences"; // JCC 
$l_themecookie	= "NOTE: Pour utiliser les th�mes vous DEVEZ activer les cookies.";
$l_alwayssig	= "Toujours attacher ma signature";
$l_alwaysdisable= "Toujours d�sactiver"; // Utilis� pour les 3 phrases suivantes
$l_alwayssmile	= "$l_alwaysdisable $l_smilies";
$l_alwayshtml	= "$l_alwaysdisable $l_html";
$l_alwaysbbcode	= "$l_alwaysdisable $l_bbcode";
$l_boardtheme	= "Th�me";
$l_boardlang    = "Langue";
$l_nothemes	= "Pas de th�mes dans la base de donn�es"; // JCC 
$l_saveprefs	= "Sauver mes $l_preferences";

// Search
$l_searchterms	= "Mots cl�s";
$l_searchany	= "Rechercher CHACUN de ces mots (par d�faut)"; // JCC 
$l_searchall	= "Rechercher TOUS les mots";
$l_searchallfrm	= "Rechercher dans tous les forums"; // JCC 
$l_sortby	= "Trier par";
$l_searchin	= "Rechercher dans";
$l_titletext	= "Titre et texte";
$l_search	= "Rechercher";
$l_nomatches	= "Aucun enregistrement ne correspond � votre demande. Affinez votre recherche.";

// Whosonline
$l_whosonline	= "Qui est en ligne ?"; // JCC
$l_nousers	= "Aucun membre n'est actuellement sur ces forums"; // JCC 


// Editpost
$l_notedit	= "Vous ne pouvez pas �diter un message qui n'est pas le v�tre."; // JCC 
$l_permdeny	= "Vous n'avez pas donn� le bon $l_password ou vous n'avez pas la permission d'�diter ce message. $l_tryagain";
$l_editedby	= "Ce $l_message a �t� �dit� par :"; // JCC 
$l_stored	= "Votre $l_message a �t� enregistr�.";
$l_viewmsg	= "pour voir votre $l_message.";
$l_deleted	= "Votre $l_post a �t� effac�.";
$l_nouser	= "Ce $l_username n'existe pas.";
$l_passwdlost	= "J'ai perdu mon mot de passe !"; // JCC 
$l_delete	= "Effacer ce message"; // JCC 

$l_disable	= "D�sactiver";
$l_onthispost	= "sur ce message"; // JCC 

$l_htmlis	= "$l_html est";
$l_bbcodeis	= "$l_bbcode est";

$l_notify	= "Notifier par e-mail quand des r�ponses sont post�es"; // JCC

// Newtopic
$l_emptymsg	= "Vous devez taper un $l_message � poster. Vous ne pouvez poster un $l_message vide.";
$l_aboutpost	= "Au sujet de l'envoi"; // JCC 
$l_regusers	= "Tous les membres <b>enregistr�s</b>"; // JCC 
$l_anonusers	= "Membres <b>anonymes</b>"; // JCC 
$l_modusers	= "<B>Mod�rateurs et administrateurs</b> seulement";
$l_anonhint	= "<br>(Pour poster anonymement ne donnez ni nom d'utilisateur ni mot de passe)"; // JCC
$l_inthisforum	= "peuvent poster de nouveaux sujets et des r�ponses sur ce forum";
$l_attachsig	= "Montrer la signature <font size=-2>(ceci peut �tre modifi� ou ajout� � votre profil)</font>";
$l_cancelpost	= "Annuler ce message"; // JCC 

// Reply
$l_nopostlock	= "Vous ne pouvez r�pondre sur ce sujet, il a �t� ferm�.";
$l_topicreview  = "Revue du sujet"; // JCC 
$l_notifysubj	= "Une r�ponse � votre sujet a �t� post�.";
$l_dear         = "Ch�r(e),";
$l_notifybody	= "Vous recevez cet e-mail parce qu\'un message
que vous avez post� sur nos forums a re�u une r�ponse, et que
vous avez choisi d\'en �tre inform�.

Vous pouvez voir le sujet �:

%s1

Ou voir l\'index du forum $sitename �

%s2

Merci d\'utiliser les forums $sitename.

Bonne journ�e.";


$l_quotemsg	= "[quote]\nLe %s1, %s2 a �crit:\n%s3\n[/quote]";

// Sendpmsg
$l_norecipient	= "Vous devez taper le nom d'utilisateur � qui vous d�sirez envoyer ce $l_message."; // JCC
$l_sendothermsg	= "Envoyer un autre message priv�"; // JCC 
$l_cansend	= "peut envoyer des $l_privmsgs";  // Tous les utilisateurs enregistr�s peuvent envoyer des MPs
$l_yourname	= "Votre $l_username";
$l_recptname	= "$l_username du destinataire"; // JCC 

// Replypmsg
$l_pmposted	= "R�ponse post�e, vous pouvez cliquer <a href=\"viewpmsg.php\">ici</a> pour voir vos $l_privmsgs";

// Viewpmsg
$l_nopmsgs	= "Vous n'avez pas de $l_privmsgs.";
$l_reply	= "R�ponse";

// Delpmsg
$l_deletesucces	= "Effacement r�ussi.";

// Smilies
$l_smilesym	= "Quoi �crire";
$l_smileemotion	= "Emotion";
$l_smilepict	= "Image";

// Sendpasswd
$l_wrongactiv	= "La cl� d'activation fournie n'est pas correcte. V�rifiez le $l_message e-mail que vous avez re�u et assurez-vous que vous avez copi� la cl� d'activation correctement."; // JCC 
$l_passchange	= "Votre mot de passe a �t� chang� avec succ�s. Vous pouvez maintenant aller sur votre <a href=\"bb_profile.php?mode=edit\">profil</a> et changer votre mot de passe.";
$l_wrongmail	= "L'adresse email fournie ne correspond pas � celle contenue dans notre base de donn�es."; // JCC 



$l_passsent	= "Votre mot de passe est chang� pour un nouveau, g�n�r� au hasard. V�rifiez vos e-mails pour savoir comment terminer la proc�dure de changement."; // JCC 
$l_emailpass	= "E-mail mot de passe perdu"; // JCC 
$l_passexplain	= "Veuillez remplir ce formulaire, un nouveau mot de passe va �tre envoy� � votre adresse d'e-mail"; // JCC 
$l_sendpass	= "Envoyer mot de passe"; // JCC 




// Groups Management Claroline

$langGroupSpaceLink="Espace du groupe";
$langGroupDocumentsLink="Documents du groupe";
$langMyGroup="mon groupe";
$langOneMyGroups="sous ma supervision";

?>
