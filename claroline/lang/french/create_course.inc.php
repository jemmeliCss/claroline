<?php // $Id$
	/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.*
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+

 */
// create_course.php
$langCreateSite="Cr�er un site de cours";
$langFieldsRequ="Tous les champs sont obligatoires";
$langTitle="Intitul�";
$langEx="p. ex. <i>Histoire de la litt�rature</i>";
$langFac="Cat�gorie";
$langCode="Code cours";
$langTargetFac="Il s'agit de la facult�, du d�partement, de l'�cole... dans lesquels se donne le cours";
$langMax="max. 12 caract�res, p. ex.<i>ROM2121</i>";
$langDoubt="En cas de doute sur l'intitul� exact ou le code de votre cours, consultez le";
$langProgram="Programme des cours</a>. Si le site que vous voulez cr�er ne correspond pas � un code cours existant, vous pouvez en inventer un. Par exemple <i>INNOVATION</i> s'il s'agit d'un programme de formation en gestion de l'innovation";
$langProfessors="Titulaire(s)";
$langExplanation="Une fois que vous aurez cliqu� sur OK, un site contenant Forum, Liste de liens, Exercices, Agenda, Liste de documents... sera cr��. Gr�ce � votre identifiant, vous pourrez en modifier le contenu";
$langEmpty="Vous n'avez pas rempli tous les champs.\n<br>\nUtilisez le bouton de retour en arri�re de votre navigateur et recommencez.<br>Si vous ne connaissez pas le code de votre cours, consultez le programme des cours";
$langCodeTaken="Ce code cours est d�j� pris.<br>Utilisez le bouton de retour en arri�re de votre navigateur et recommencez";

// tables MySQL
$langFormula="Cordialement, votre professeur";
$langForumLanguage="french";	// other possibilities are english, spanish (this uses phpbb language functions)
$langTestForum="Forum d\'essais";
$langDelAdmin="A supprimer via l\'administration des forums";
$langMessage="Lorsque vous supprimerez le forum \"Forum d\'essai\", cela supprimera �galement le pr�sent sujet qui ne contient que ce seul message";
$langExMessage="Message exemple";
$langAnonymous="Anonyme";
$langExerciceEx="Exemple d\'exercice";
$langAntique="Histoire de la philosophie antique";
$langSocraticIrony="L\'ironie socratique consiste �...";
$langManyAnswers="(plusieurs bonnes r�ponses possibles)";
$langRidiculise="Ridiculiser son interlocuteur pour lui faire admettre son erreur.";
$langNoPsychology="Non. L\'ironie socratique ne se joue pas sur le terrain de la psychologie, mais sur celui de l\'argumentation.";
$langAdmitError="Reconna�tre ses erreurs pour inviter son interlocuteur � faire de m�me.";
$langNoSeduction="Non. Il ne s\'agit pas d\'une strat�gie de s�duction ou d\'une m�thode par l\'exemple.";
$langForce="Contraindre son interlocuteur, par une s�rie de questions et de sous-questions, � reconna�tre qu\'il ne conna�t pas ce qu\'il pr�tend conna�tre.";
$langIndeed="En effet. L\'ironie socratique est une m�thode interrogative. Le grec \"eirotao\" signifie d\'ailleurs \"interroger\".";
$langContradiction="Utiliser le principe de non-contradiction pour amener son interlocuteur dans l\'impasse.";
$langNotFalse="Cette r�ponse n\'est pas fausse. Il est exact que la mise en �vidence de l\'ignorance de l\'interlocuteur se fait en mettant en �vidence les contradictions auxquelles abouttisent ses th�ses.";

$langSampleLearnPath = "Exemple de parcours p&eacute;dagogique";
$langSampleLearnPathDesc = "Ceci est un exemple de parcours p&eacute;dagogique, il utilise l\'exemple d\'exercice et l\'exemple de document de l\'outil d\'exercice et l\'outil de document. Cliquez sur <b>Modifier</b> pour changer ce texte.";
$langSampleHandmade = "Exemple de module \'fait main\'";
$langSampleHandmadeDesc = "Vous pouvez fair un module \'fait main\' en utilisant des pages HTML, animations FLASH, vid&eacute;os...<br /><br /> Afin de permettre aux apprenants de voir le contenu de votre nouveau module, vous devrez d&eacute;finir une resource de d�marrage du module.";
$langSampleDocument = "document_exemple";
$langSampleDocumentDesc = "Vous pouvez utiliser n\'importe quel document de l\'outil de documents de ce cours.";
$langSampleExerciseDesc = "Vous pouvez utiliser n\'importe quel exercice de l\'outil d\'exercices de ce cours.";

// Home Page MySQL Table "accueil"
$langAgenda="Agenda";
$langLinks="Liens";
$langDoc="Documents et liens";
$langVideo="Video";
$langWorks="Travaux";
$langCourseProgram="Cahier des charges";
$langAnnouncements="Annonces";
$langUsers="Utilisateurs";
$langForums="Forums";
$langExercices="Exercices";
$langStatistics="Statistiques";
$langAddPageHome="D�poser page et lier � page d\'accueil";
$langLinkSite="Ajouter un lien sur la page d\'accueil";
$langModifyInfo="Propri�t�s du cours";
$langCourseDesc = "Description du cours";
$langLearningPath="Parcours p&eacute;dagogique";
$langEmail="Email";


// Other SQL tables
$langAgendaTitle="Mardi 11 d�cembre 14h00 : cours de philosophie (1) - Local : Sud 18";
$langAgendaText="Introduction g�n�rale � la philosophie et explication sur le fonctionnement du cours";
$langMicro="Micro-trottoir";
$langVideoText="Ceci est un exemple en RealVideo. Vous pouvez envoyer des vid�os de tous formats (.mov, .rm, .mpeg...), pourvu que vos �tudiants soient en mesure de les lire";
$langGoogle="Moteur de recherche g�n�raliste performant";
$langIntroductionText="Ceci est le texte d\'introduction de votre cours. Modifier ce texte r�guli�rement est une bonne fa�on d\'indiquer clairement que ce site est un lieu d\'interaction vivant et non un simple r�pertoire de documents.";

$langIntroductionTwo="Cette page est un espace de publication. Elle permet � chaque �tudiant ou groupe d\'�tudiants d\'envoyer un document (Word, Excel, HTML... ) vers le site du cours afin de le rendre accessible aux autres �tudiants ainsi qu\'au professeur.
Si vous passez par votre espace de groupe pour publier le document (option publier), l\'outil de travaux fera un simple lien vers le document l� o� il se trouve dans votre r�pertoire de groupe sans le d�placer.";
$langIntroductionLearningPath="<p>Ceci est le texte d\'introduction des parcours p&eacute;dagogique de votre cours.  Utiliser cet outils pour fournir � vos apprenants un parcours s&eacute;quentiel d&eacute;fini par vos soins entre des documents, exercices, pages HTML,... ou importer des contenus SCORM existants</p><p>Remplacez ce texte par votre propre introduction.<br></p>";
$langCourseDescription="Ecrivez ici la description qui appara�tra dans la liste des cours (Le contenu de ce champ ne s\'affiche actuellement nulle part et ne se trouve ici qu\'en pr�paration � une version prochaine de Claroline).";
$langProfessor="Responsables de cours";
$langAnnouncementExTitle = "Exemple d\'annonce";
$langAnnouncementEx="Ceci est un exemple d\'annonce.";
$langJustCreated="Vous venez de cr�er le site du cours";
$langEnter="Retourner � votre liste de cours";
$langMillikan="Exp&eacute;rience de Millikan";



// Groups
$langGroups="Groupes";
$langCreateCourseGroups="Groupes";

$langCatagoryMain = "G&eacute;n&eacute;ral";
$langCatagoryGroup = "Forums des Groupes";

$langChat ="Discuter";

$langRestoreCourse = "Restauration d'un cours";
$langAddedToCreator = "en plus de celui choisi  � la cr�ation";


$langOnly = "Seulement";
$langRandomLanguage = "S&eacute;lection al&eacute;atoire parmis toutes les langues";


// Dev tools : create many test courses
$langMinimum = "Minimum";
$langMaximum = "Maximum";

$langTipLang="Cette langue vaudra pour tous les visiteurs de votre site de cours.";
$langCourseAccess="Acc�s au cours";
$langPublic="Acc�s public (depuis la page d'accueil de iCampus sans identifiant)";
$langPrivate="Acc�s priv� (site r�serv� aux personnes figurant dans la liste <a href=../user/user.php>utilisateurs</a>)";
$langSubscription="Inscription";
$langConfTip="Par d�faut, votre cours n'est accessible
qu'� vous qui en �tes le seul utilisateur. Si vous souhaitez un minimum de confidentialit�, le plus simple est d'ouvrir
l'inscription pendant une semaine, de demander aux �tudiants de s'inscrire eux-m�mes
puis de fermer l'inscription et de v�rifier dans la liste des utilisateurs les intrus �ventuels.";

//Display
$langCreateCourse="Cours � cr�er";
$langQantity="Quantit�  : ";
$langPrefix="Prefix  : ";
$langStudent="�tudiants";
$langMin="Minimum : ";
$langMax="Maximum : ";
$langNumGroup="Nombre de groupe par cours";
$langMaxStudentGroup="Nombre maximum d'�tudiant par groupe";
$langAdmin ="administration";
$langNumGroupStudent="Nombre de groupe dont peut faire partie un �tudiant dans un cours";
?>
