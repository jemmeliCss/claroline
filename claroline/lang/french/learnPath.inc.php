<?php // $Id$

   
   $langCreateNewLearningPath="Cr�er un nouveau parcours p�dagogique";
   $langNoLearningPath = "Aucun parcours";
   $langimportLearningPath="Importer un parcours";
   $langLearningPath="Parcours p�dagogique";
   $langLearningPathList="Liste des parcours p�dagogiques";
   $langLearningPathAdmin="Administration du parcours";
   $langModule = "Module";
   $langStatistics="Statistiques";
   $langTracking = "Suivi";
   $langOrder="Ordre";
   $langVisible="Visibilit�";
   $langBlock = "Bloquer";
   $langProgress = "Progression";
   $langIntroLearningPath="Utilisez cet outil pour fournir � vos apprenants un parcours s�quentiel d�fini par vos soins entre des documents, exercices, pages HTML, liens... ou importez des contenus SCORM existants<br /><br />Si vous d�sirez ajouter un texte d'introduction, cliquez sur ce bouton.<br />"; // JCC 

   $langStartModule = "Commencer le module";
   $langModuleAdmin = "Administration du module";
   $langModuleHelpDocument = "Vous pouvez choisir un document qui remplacera l'actuel document de ce module.";
   $langAsset = "Ressource";
   $langStartAsset = "Ressource de d�marrage";

   $langRename = "Renommer";
   $langRemoveFromLPShort = "Retirer";
   $langRemoveFromLPLong = "Retirer de ce parcours p�dagogique";
   $langComment = "Commentaire";
   $langModuleType = "Type";
   $langAddModulesButton = "Ajouter le(s) module(s)";
   $langAddOneModuleButton = "Ajouter le module";
   $langInsertNewModuleName="Ins�rer le nouveau nom";
   $langModifyCommentModuleName="Ins�rer un nouveau commentaire pour";

   $langGlobalProgress = "Progression du parcours p�dagogique : ";

   //tools titles
   $langInsertMyModulesTitle = "Ins�rer un module du cours";
   $langAddModule = "Ajouter";
   $langPathContentTitle = "Contenu du parcours p�dagogique";

   // alt comments for images
   $langAltMove = "D�placer";
   $langAltMoveUp = "Monter";
   $langAltMoveDown = "Descendre";   
   $langAltMakeVisible = "Rendre visible";
   $langAltMakeInvisible = "Rendre invisible";
   $langAltMakeBlocking = "Rendre bloquant";
   $langAltMakeNotBlocking = "Rendre non bloquant";
   
   // forms
   $langLearningPathName= "Nom du nouveau parcours : ";
   $langNewModuleName = "Nom du nouveau module et type de contenu : "; // JCC 
   $langButtonImport= "Importer";
   $langAddComment = "Ajouter un commentaire";
   $langAddAddedComment = "Ajouter un commentaire sp�cifique au parcours";
   $langChangeOrder = "Changer l'ordre";

   // lang for learningPathAdmin
   $langNewModule = "Cr�er un module vide";
   $langExerciseAsModule    = "Utiliser un exercice";
   $langDocumentAsModule     =  "Utiliser un document";
   $langModuleOfMyCourse  = "Utiliser un module de ce cours";
   $langAlertBlockingMakedInvisible = "Ce module est bloquant, \\nle rendre invisible permettra aux apprenants d\'acc�der \\n aux modules suivants du parcours sans devoir r�ussir celui-ci. \\n\\nConfirmer ?";
   $langAlertBlockingPathMadeInvisible = "Ce parcours est bloquant. \\nle rendre invisible permettra aux apprenants d\'acc�der \\n aux parcours suivants sans devoir r�ussir celui-ci. \\n\\nConfirmer ?"; // JCC 
   $langCreateLabel = "Cr�er un titre";
   $langNewLabel = "Cr�er un titre dans ce parcours p�dagogique";
   $langRoot = "Niveau sup�rieur";
   $langWrongOperation = "Op�ration impossible";
   $langMove = "D�placer";
   $langTo = "vers";
   $langModuleMoved = "Module d�plac�";
   $langBackToLPAdmin = "Retour au parcours p�dagogique";
   
   //lang for learningpathList

   $langPathsInCourseProg = "Progression dans le cours";

   // confirm
   $langAreYouSureToDelete = "Etes-vous s�r de vouloir effacer "; // JCC 
   $langModuleStillInPool = "Les modules de ce parcours seront toujours accessibles dans la banque de modules";
   $langAreYouSureToRemove = "Etes-vous s�r de vouloir retirer ce module du parcours p�dagogique : "; // JCC 
   $langAreYouSureToRemoveSCORM = "Les modules conformes � SCORM sont d�finitivement effac�s du serveur lorsqu'ils sont effac�s dans un parcours p�dagogique."; // JCC
   $langAreYouSureToRemoveStd = "Le module sera toujours accessible dans la banque de modules.";
   $langAreYouSureToRemoveLabel = "Effacer un titre efface �galement tous les titres et modules qu\'il contient.";   
   $langAreYouSureToDeleteScorm = "Ce parcours est issu de l'importation d'un package SCORM. Si vous effacez ce parcours, tous les contenus SCORM de ses modules seront supprim�s du serveur.  Etes-vous s�r de vouloir effacer le parcours p�dagogique "; // JCC 
   $langAreYouSureToDeleteScormModule = "Etes-vous s�r de vouloir effacer ce module SCORM ? Le module ne sera plus accessible sur le serveur."; // JCC 

   // this var is used in javascript popup so \n are escaped to be read by javascript only
   $langAreYouSureDeleteModule = "Etes-vous s�r de vouloir totalement effacer ce module ?\\n\\nIl sera d�finitivement effac� du serveur et du parcours p�dagogique.\\nVous ne pourrez plus l'utiliser dans aucun parcours p�dagogique.\\n\\nConfirmer la suppression de : "; // JCC 
   $langUsedInLearningPaths = "\\nNombre de parcours utilisant ce module : ";

   // success messages
   $langOKNewPath  = "Creation r�ussie";

   // errors messages
   $langErrorNameAlreadyExists = "Erreur : Le nom existe d�j�";

   // insertMyModule
   $langNoMoreModuleToAdd="Tous les modules de ce cours sont d�j� utilis�s dans ce parcours.";
   $langInsertMyModuleToolName="Ins�rer mon module";
   $langErrorEmptyName="Le nom doit �tre compl�t�";
   $langModuleType = "Type";
   $langAddedComment = "Commentaire sp�cifique";

   // insertMyDoc
   $langInsertMyDocToolName = "Ins�rer un document comme module";
   $langDocInsertedAsModule = "a �t� ajout� comme module";
   $langFileAlreadyExistsInDestinationDir = "Un fichier portant le m�me nom est d�j� pr�sent dans votre liste de module";
   $langDocumentAlreadyUsed = "Ce document est d�j� utilis� comme module dans ce parcours p�dagogique";

   $langDocModuleFileModified = "Le fichier a �t� modifi�";
   $langDocumentInModule = "Document dans le module";
   $langFileName = "Nom du fichier";

   // insertPublicModule
   $langClose ="Fermer";
   $langAvailable = "module(s) disponible(s)";

   // insertMyExercise
   $langInsertMyExerciseToolName = "Ajouter mon exercice";
   $langExercise = "Exercice";
   $langExInsertedAsModule = "a �t� ajout� comme module de ce cours et comme module de ce parcours p�dagogique";
   $langExAlreadyUsed = "Cet exercice est d�j� utilis� comme module dans ce parcours p�dagogique";
   $langExAlreadyUsedInModule = "Cet exercice est d�j� utilis� dans ce module";

   // modules pool
   $langModulesPoolToolName = "Banque de modules";
   $langNoModule = "Pas de module";
   $langUseOfPool = "Cette page vous permet de voir tous les modules disponibles dans votre cours. <br />
                     Tous les exercices ou document qui ont �t� ajout�s dans un parcours appara�tront aussi dans cette liste.";

   //assets
   $langNoStartAsset = "Il n'y a pas de ressource de d�marrage d�finie pour ce module.";

   // module admin / exercise
   $langChangeRaw = "Changer le score minimum pour r�ussir ce module (en pour cent) : "; // JCC 
   $langModuleHelpExercise = "Vous pouvez changer le score minimum n�cessaire que doit obtenir l'apprenant pour r�ussir ce module.";
   $langRawHasBeenChanged = "Le score minimum pour r�ussir le module a �t� chang�";
   $langExerciseInModule = "Exercice du module";
   $langModifyAll = "dans tous les parcours p�dagogiques ";
   $langModifyThis = "seulement dans ce parcours";
   $langUsedInSeveralModules = "Attention ! Cet exercice est utilis� dans un ou plusieurs modules de parcours p�dagogique. Voulez-vous le changer ?"; // JCC

   $langModuleModified = "Le module a �t� modifi�";
   $langQuitViewer = "Retour � la liste";
   $langNext = "Suivant";
   $langPrevious = "Pr�c�dent";
   $langBrowserCannotSeeFrames = "Votre navigateur ne supporte pas les frames";

   // default comment
   $langDefaultLearningPathComment = "Ceci est le texte d'introduction du parcours p�dagogique. Pour le remplacer par votre propre texte, cliquez en-dessous sur <b>modifier</b>.";
   $langDefaultModuleComment = "Ceci est un texte d'introduction du module, il appara�tra dans chaque parcours contenant ce module. Pour le remplacer par votre propre texte, cliquez en dessous sur <b>modifier</b>.";
   $langDefaultModuleAddedComment = "Ceci est un texte additionnel d'introduction du module. Il est sp�cifique � la pr�sence de ce module dans ce parcours p�dagogique. Pour le remplacer par votre propre texte, cliquez en dessous sur <b>modifier</b>."; // JCC

   $langAlt['document'] = "Document";
   $langAlt['handmade'] = "Handmade";
   $langAlt['exercise'] = "Exercise";
   $langAlt['clarodoc'] = "Clarodoc";
   $langAlt['scorm']    = "Scorm";

   // import learning path
   $langImport = "Importer";

   // import learning path / error messages
   $langErrorReadingManifest = "Erreur � la lecture du fichier <i>imsmanifest.xml</i>";
   $langErrortExtractingManifest = "Impossible d'extraire le manifeste du fichier zip (fichier corrompu ?)";
   $langErrorOpeningManifest = "Le manifeste n'a pas �t� trouv� dans le package.<br /> Fichier manquant : imsmanifest.xml";
   $langErrorReadingXMLFile = "Erreur � la lecture d'un fichier secondaire d'initialisation : ";
   $langErrorOpeningXMLFile = "Un fichier XML secondaire d'initialisation n'a pas pu �tre trouv�.<br /> Fichier manquant : ";
   $langErrorFileMustBeZip = "Le fichier upload� doit �tre au format zip (.zip)";
   $langErrorNoZlibExtension = "L'extension php 'zlib' est requise pour l'utilisation de cet outil. Contactez l'administrateur de la plate-forme."; // JCC
   $langErrorReadingZipFile = "Erreur lors de la lecture du fichier zip.";
   $langErrorNoModuleInPackage = "Pas de module dans le package";
   $langErrorAssetNotFound = "Ressource non trouv�e : ";
   $langErrorSql = "Erreur dans les requ�tes SQL";

   // import learning path / ok messages
   $langScormIntroTextForDummies = "Les packages import�s doivent �tre des fichiers zip et r�pondre � la norme SCORM 1.2";
   $langOkFileReceived = "Fichier re�u : ";
   $langOkManifestRead = "Manifest lu.";
   $langOkManifestFound = "Manifest trouv�.";
   $langOkModuleAdded = "Module ajout� : ";
   $langOkChapterHeadAdded = "Titre ajout� : ";
   $langOkDefaultTitleUsed ="attention : l'installation n'a pas trouv� le nom du parcours p�dagogique et a attribu� un nom par d�faut. Vous pourrez le changer par la suite.";
   $langOkDefaultCommentUsed = "attention : l'installation n'a pas trouv� la description du parcours p�dagogique et a attribu� un commentaire par d�faut. Vous pourrez le changer par la suite"; // JCC 

   $langUnamedPath = "Parcours sans nom" ;
   $langUnamedModule = "Module sans nom";

   $langNotInstalled = "Une erreur est survenue.  L'importation du parcours p�dagogique a �chou�.";
   $langInstalled = "L'importation du parcours p�dagogique a r�ussi.";


   //just before module start

   $langProgInModuleTitle = "Votre progression dans ce module";
   $langInfoProgNameTitle = "Information";
   $langPersoValue = "Valeurs";
   $langTotalTimeSpent = "Temps total";
   $langLastSessionTimeSpent = "Temps de la derni�re session";
   $langLessonStatus = "Statut du module";
   $langYourBestScore = "Votre meilleur score";
   $langNumbAttempt = "Tentative(s)";
   $langBrowsed = "visit�";
   $langTimes = "fois";
   $langTypeOfModule = "Type de module";
   $langSCORMTypeDesc = "Contenu conforme � SCORM 1.2"; // JCC 
   $langEXERCISETypeDesc = "Exercice Claroline"; // JCC 
   $langDOCUMENTTypeDesc = "Document";
   $langHANDMADETypeDesc = "Pages HTML";
   $langAlreadyBrowsed = "D�j� visit�";
   $langNeverBrowsed = "Jamais visit�";
   $langBackModule = "Retour � la liste";

  // in viewer
  $langExerciseCancelled = "Exercice annul�, choisissez un module dans la liste pour continuer.";
  $langExerciseDone = "Exercice termin�, choisissez un module dans la liste pour continuer."; 
  $langView = "Vue";
  $langFullScreen = "Plein �cran";
  $langInFrames = "En cadres";
?>
