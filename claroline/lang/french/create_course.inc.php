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
$langMaxSizeCourseCode = "max. 12 caract�res, p. ex.<i>ROM2121</i>"; // to change the ma
$langDoubt="En cas de doute sur l'intitul� exact ou le code de votre cours, consultez le";
$langProfessors="Titulaire(s)";
$langExplanation="Une fois que vous aurez cliqu� sur OK, un site contenant Forum, Liste de liens, Exercices, Agenda, Liste de documents... sera cr��. Gr�ce � votre identifiant, vous pourrez en modifier le contenu";
$langCodeTaken="Ce code cours est d�j� pris.<br>Utilisez le bouton de retour en arri�re de votre navigateur et recommencez";
$langBackToAdmin = "Retour � l'administration";
$langAnotherCreateSite = "Cr�er un autre cours";
$langAdministrationTools = "Administration";

// tables MySQL
$langFormula="Cordialement, votre professeur";
$langForumLanguage="french";	// other possibilities are english, spanish (this uses phpbb language functions)
$langTestForum="Forum d\'essais";
$langDelAdmin="A supprimer via l\'administration des forums";
$langMessage="Lorsque vous supprimerez le forum &quot;Forum d&rsquo;essai&quot;, cela supprimera �galement le pr�sent sujet qui ne contient que ce seul message";
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
$langIndeed="En effet. L\'ironie socratique est une m�thode interrogative. Le grec &quot;eirotao&quot; signifie d\'ailleurs &quot;interroger&quot;.";
$langContradiction="Utiliser le principe de non-contradiction pour amener son interlocuteur dans l\'impasse.";
$langNotFalse="Cette r�ponse n\'est pas fausse. Il est exact que la mise en �vidence de l\'ignorance de l\'interlocuteur se fait en mettant en �vidence les contradictions auxquelles aboutissent ses th�ses."; // JCC 

$langSampleLearnPath = "Exemple de parcours p�dagogique";
$langSampleLearnPathDesc = "Ceci est un exemple de parcours p�dagogique, il utilise l\'exemple d\'exercice et l\'exemple de document de l\'outil d\'exercices et l\'outil de documents. Cliquez sur <b>Modifier</b> pour changer ce texte."; // JCC 
$langSampleHandmade = "Exemple de module \'fait main\'";
$langSampleHandmadeDesc = "Vous pouvez faire un module \'fait main\' en utilisant des pages HTML, animations FLASH, vid�os...<br /><br /> Afin de permettre aux apprenants de voir le contenu de votre nouveau module, vous devrez d�finir une ressource de d�marrage du module."; // JCC 
$langSampleDocument = "document_exemple";
$langSampleDocumentDesc = "Vous pouvez utiliser n\'importe quel document de l\'outil de documents de ce cours.";
$langSampleExerciseDesc = "Vous pouvez utiliser n\'importe quel exercice de l\'outil d\'exercices de ce cours.";

// Home Page MySQL Table "accueil"
$langAgenda="Agenda";
$langDoc="Documents et liens";
$langVideo="Vid�o"; // JCC 
$langWorks="Travaux";
$langCourseProgram="Cahier des charges";
$langAnnouncements="Annonces";
$langUsers="Utilisateurs";
$langForums="Forums";
$langExercices="Exercices";
$langStatistics="Statistiques";
$langAddPageHome="D�poser une page et la lier � page d\'accueil"; // JCC 
$langLinkSite="Ajouter un lien sur la page d\'accueil";
$langModifyInfo="Propri�t�s du cours";
$langCourseDesc = "Description du cours";
$langLearningPath="Parcours p�dagogique";
$langEmail="E-mail"; // JCC


// create_course.php // JCC cette variable manquait
$langLn="Langue";


// Other SQL tables
$langAgendaTitle="Mardi 11 d�cembre 14h00 : cours de philosophie (1) - Local : Sud 18";
$langAgendaText="Introduction g�n�rale � la philosophie et explication sur le fonctionnement du cours";
$langMicro="Micro-trottoir";
$langVideoText="Ceci est un exemple en RealVideo. Vous pouvez envoyer des vid�os de tous formats (.mov, .rm, .mpeg...), pourvu que vos �tudiants soient en mesure de les lire";
$langGoogle="Moteur de recherche g�n�raliste performant";
//$langIntroductionText="Ceci est le texte d\'introduction de votre cours. Modifier ce texte r�guli�rement est une bonne fa�on d\'indiquer clairement que ce site est un lieu d\'interaction vivant et non un simple r�pertoire de documents.";

//$langIntroductionTwo="Cette page est un espace de publication. Elle permet � chaque �tudiant ou groupe d\'�tudiants d\'envoyer un document (Word, Excel, HTML... ) vers le site du cours afin de le rendre accessible aux autres �tudiants ainsi qu\'au professeur.
//Si vous passez par votre espace de groupe pour publier le document (option publier), l\'outil de travaux fera un simple lien vers le document l� o� il se trouve dans votre r�pertoire de groupe sans le d�placer.";
//$langIntroductionLearningPath="<p>Ceci est le texte d\'introduction des parcours p�dagogiques de votre cours.  Utilisez cet outil pour fournir � vos apprenants un parcours s�quentiel d�fini par vos soins entre des documents, exercices, pages HTML,... ou importer des contenus SCORM existants</p><p>Remplacez ce texte par votre propre introduction.<br></p>"; // JCC 
$langCourseDescription="Ecrivez ici la description qui appara�tra dans la liste des cours (Le contenu de ce champ ne s\'affiche actuellement nulle part et ne se trouve ici qu\'en pr�paration � une version prochaine de Claroline).";
$langProfessor="Responsable de cours"; // JCC 
$langAnnouncementExTitle = "Exemple d\'annonce";
$langAnnouncementEx="Ceci est un exemple d\'annonce.";
$langJustCreated="Vous venez de cr�er le site du cours";
$langBackToMyCourseList="Retourner � votre liste de cours";
$langMillikan="Exp�rience de Millikan";



// Groups
$langGroups="Groupes";
$langCreateCourseGroups="Groupes";

$langCatagoryMain = "G�n�ral";
$langCatagoryGroup = "Forums des Groupes";

$langChat ="Discuter";

$langRestoreCourse = "Restauration d'un cours";
$langAddedToCreator = "en plus de celui choisi  � la cr�ation";


$langOnly = "Seulement";
$langRandomLanguage = "S�lection al�atoire parmi toutes les langues"; // JCC 


// Dev tools : create many test courses
$langTipLang="Cette langue vaudra pour tous les visiteurs de votre site de cours.";
$langCourseAccess="Acc�s au cours";
$langPublic="Acc�s public (depuis la page d'accueil de Claroline sans identifiant)";
$langPrivate="Acc�s priv� (site r�serv� aux personnes figurant dans la liste <a href=../user/user.php>utilisateurs</a>)";
$langSubscription="Inscription";
$langConfTip="Par d�faut, votre cours n'est accessible
qu'� vous qui en �tes le seul utilisateur. Si vous souhaitez un minimum de confidentialit�, le plus simple est d'ouvrir
l'inscription pendant une semaine, de demander aux �tudiants de s'inscrire eux-m�mes
puis de fermer l'inscription et de v�rifier dans la liste des utilisateurs les intrus �ventuels.";

//Display
$langCreateCourse="Cours � cr�er";
$langQantity="Quantit�  : ";
$langPrefix="Pr�fixe  : "; // JCC 
$langStudent="�tudiants";
$langMin="Minimum : ";
$langMax="Maximum : ";
$langNumGroup="Nombre de groupes par cours"; // JCC 
$langMaxStudentGroup="Nombre maximum d'�tudiants par groupe"; // JCC 
$langAdmin ="administration";
$langNumGroupStudent="Nombre de groupes dont peut faire partie un �tudiant dans un cours"; // JCC 

$langLabelCanBeEmpty ="L'intitul� est obligatoire";
$langTitularCanBeEmpty ="Le champs titulaire doit �tre rempli";
$langEmailCanBeEmpty ="Le champs e-mail doit �tre rempli"; // JCC
$langCodeCanBeEmpty ="Le code cours doit �tre rempli";
$langEmailWrong = "L'e-mail n'est pas correct (corrigez-le, ou effacez-le)"; // JCC
$langCreationMailNotificationSubject = 'Cr�ation de cours';
$langCreationMailNotificationBody = 'Cours ajout� sur'; 
$langByUser = 'par l\'utilisateur';

?>
