<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.*                             |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   $Id$     |
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or      |
      |   modify it under the terms of the GNU General Public License        |
      |   as published by the Free Software Foundation; either version 2     |
      |   of the License, or (at your option) any later version.             |



      |          Olivier Brouckaert <oli.brouckaert@skynet.be>               |
      +----------------------------------------------------------------------+
*/

/***************************************************************
*                   Language translation
****************************************************************
GOAL
****
Translate the interface in chosen language

*****************************************************************/

// general

$langExercice="Exercice";
$langExercices="Exercices";
$langQuestion="Question";
$langQuestions="Questions";
$langAnswer="R�ponse";
$langAnswers="R�ponses";
$langActivate="Activer";
$langDeactivate="D�sactiver";
$langComment="Commentaire";


// exercice.php

$langNoEx="Il n'y a aucun exercice actuellement";
$langNoResult="Il n'y a pas encore de r�sultats";
$langNewEx="Nouvel exercice";
$langUsedInSeveralPath = "Cet exercice est utilis� dans un ou plusieurs parcours p�dagogiques.  Si vous le supprimez il ne sera plus disponible au sein de ce ou ces parcours.";


// exercise_admin.inc.php

$langExerciseType="Type d'exercice";
$langExerciseName="Intitul� de l'exercice";
$langExerciseDescription="Description de l'exercice";
$langSimpleExercise="Questions sur une seule page";
$langSequentialExercise="Une question par page (s�quentiel)";
$langRandomQuestions="Questions al�atoires";
$langGiveExerciseName="Veuillez introduire l'intitul� de l'exercice";


// question_admin.inc.php

$langNoAnswer="Il n'y a aucune r�ponse actuellement";
$langGoBackToQuestionPool="Retour � la banque de questions";
$langGoBackToQuestionList="Retour � la liste des questions";
$langQuestionAnswers="R�ponses � la question";
$langUsedInSeveralExercises="Attention ! Cette question et ses r�ponses sont utilis�es dans plusieurs exercices. Souhaitez-vous les modifier";
$langModifyInAllExercises="pour l'ensemble des exercices";
$langModifyInThisExercise="uniquement pour l'exercice courant";


// statement_admin.inc.php

$langAnswerType="Type de r�ponse";
$langUniqueSelect="Choix multiple (R�ponse unique)";
$langMultipleSelect="Choix multiple (R�ponses multiples)";
$langFillBlanks="Remplissage de blancs";
$langMatching="Correspondance";
$langAddPicture="Ajouter une image";
$langReplacePicture="Remplacer l'image";
$langDeletePicture="Supprimer l'image";
$langQuestionDescription="Commentaire facultatif";
$langGiveQuestion="Veuillez introduire la question";


// answer_admin.inc.php

$langWeightingForEachBlank="Veuillez donner une pond�ration � chacun des blancs";
$langUseTagForBlank="utilisez des crochets [...] pour cr�er un ou des blancs";
$langQuestionWeighting="Pond�ration";
$langTrue="Vrai";
$langMoreAnswers="+r�p";
$langLessAnswers="-r�p";
$langMoreElements="+�lem";
$langLessElements="-�lem";
$langTypeTextBelow="Veuillez introduire votre texte ci-dessous";
$langDefaultTextInBlanks="Les [anglais] vivent en [Angleterre].";
$langDefaultMatchingOptA="Royaume Uni";
$langDefaultMatchingOptB="Japon";
$langDefaultMakeCorrespond1="Les anglais vivent au";
$langDefaultMakeCorrespond2="Les japonais vivent au";
$langDefineOptions="D�finissez la liste des options";
$langMakeCorrespond="Faites correspondre";
$langFillLists="Veuillez remplir les deux listes ci-dessous";
$langGiveText="Veuillez introduire le texte";
$langDefineBlanks="Veuillez d�finir au moins un blanc en utilisant les crochets [...]";
$langGiveAnswers="Veuillez fournir les r�ponses de cette question";
$langChooseGoodAnswer="Veuillez choisir une bonne r�ponse";
$langChooseGoodAnswers="Veuillez choisir une ou plusieurs bonnes r�ponses";


// question_list_admin.inc.php

$langNewQu="Nouvelle question";
$langQuestionList="Liste des questions de l'exercice";
$langMoveUp="D�placer vers le haut";
$langMoveDown="D�placer vers le bas";
$langGetExistingQuestion="R�cup�rer une question d'un autre exercice";


// question_pool.php

$langQuestionPool="Banque de questions";
$langOrphanQuestions="Questions orphelines";
$langNoQuestion="Il n'y a aucune question actuellement";
$langAllExercises="Tous les exercices";
$langFilter="Filtre";
$langGoBackToEx="Retour � l'exercice";
$langReuse="R�cup�rer";


// admin.php

$langExerciseManagement="Administration d'un exercice";
$langQuestionManagement="Administration des questions / r�ponses";
$langQuestionNotFound="Question introuvable";


// exercice_submit.php

$langExerciseNotFound="Exercice introuvable";
$langAlreadyAnswered="Vous avez d�j� r�pondu � la question";


// exercise_result.php

$langElementList="Liste des �l�ments";
$langResult="R�sultat";
$langScore="Points";
$langCorrespondsTo="Correspond �";
$langExpectedChoice="Choix attendu";
$langYourTotalScore="Vous avez obtenu un total de";

$langTracking = "Statistiques";
?>
