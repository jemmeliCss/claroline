<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.*                             |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   $Id$     |
      |   English Translation                                                |
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or      |
      |   modify it under the terms of the GNU General Public License        |
      |   as published by the Free Software Foundation; either version 2     |
      |   of the License, or (at your option) any later version.             |
      +----------------------------------------------------------------------+
      | Translator :                                                         |
      |          Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Andrew Lynn       <Andrew.Lynn@strath.ac.uk>                |
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

$langExercice="Exercise";
$langExercices="Exercises";
$langQuestion="Question";
$langQuestions="Questions";
$langAnswer="Answer";
$langAnswers="Answers";
$langEnable="Enable";
$langDisable="Disable";
$langComment="Comment";
$langAttachedFile = "Download attached file";
$langMinuteShort = "min.";
$langSecondShort = "sec.";

// exercice.php

$langNoEx="There is no exercise for the moment";
$langNoResult="There is no result yet";
$langNewEx="New exercise";
$langUsedInSeveralPath = "This exercise is used in one or more learning path. If you delete it it will be no more available in the learning path.";  
$langConfirmDeleteExercise = "Are you sure to delete this exercise ?";


// exercise_admin.inc.php

$langExerciseType="Exercise type";
$langExerciseName="Exercise name";
$langExerciseDescription="Exercise description";
$langSimpleExercise="On an unique page";
$langSequentialExercise="One question per page (sequential)";
$langRandomQuestions="Random questions";
$langGiveExerciseName="Please give the exercise name";
$langAllowedTime="Time limit";
$langAllowedAttempts="Attempts allowed";
$langAnonymousVisibility="Anonymous visibity";
$langShowAnswers = "Show answers";
$langAlways = "Always";
$langNever = "Never";
$langShow = "Show";
$langHide = "Hide";
$langEditExercise = "Edit exercise settings";
$langUnlimitedAttempts = "Unlimited attempts";
$langAttemptAllowed = "attempt allowed";
$langAttemptsAllowed = "attempts allowed";
$langAllowAnonymousAttempts = "Anonymous attempts";
$langAnonymousAttemptsAllowed = "Allowed : don't record usernames in tracking, anonymous users can make the exercise.";
$langAnonymousAttemptsNotAllowed = "Not allowed : record usernames in tracking, anonymous users cannot make the exercise.";
$langExerciseOpening = "Exercise opening";
$langExerciseClosing = "Exercise closing";
$langRequired = "Required";
$langNoEndDate = "No closing date";


// question_admin.inc.php

$langNoAnswer="There is no answer for the moment";
$langGoBackToQuestionPool="Go back to the question pool";
$langGoBackToQuestionList="Go back to the question list";
$langQuestionAnswers="Answers to the question";
$langUsedInSeveralExercises="Warning ! This question and its answers are used in several exercises. Would you like to modify them";
$langModifyInAllExercises="in all exercises";
$langModifyInThisExercise="only in the current exercise";
$langEditQuestion = "Edit question";
$langEditAnswers = "Edit answers";


// statement_admin.inc.php

$langAnswerType="Answer type";
$langUniqueSelect="Multiple choice (Unique answer)";
$langMultipleSelect="Multiple choice (Multiple answers)";
$langFillBlanks="Fill in blanks";
$langMatching="Matching";
$langAddPicture="Add a picture";
$langReplacePicture="Replace the picture";
$langDeletePicture="Delete the picture";
$langQuestionDescription="Optional comment";
$langGiveQuestion="Please give the question";
$langAttachFile = "Attach a file";
$langReplaceAttachedFile = "Replace attached file";
$langDeleteAttachedFile = "Delete attached file";
$langMaxFileSize = "Max file size is ";


// answer_admin.inc.php

$langWeightingForEachBlank="Please give a weighting to each blank";
$langUseTagForBlank="use brackets [...] to define one or more blanks";
$langQuestionWeighting="Weighting";
$langTrue="True";
$langMoreAnswers="+answ";
$langLessAnswers="-answ";
$langMoreElements="+elem";
$langLessElements="-elem";
$langTypeTextBelow="Please type your text below";
$langDefaultTextInBlanks="[British people] live in [United Kingdom].";
$langDefaultMatchingOptA="rich";
$langDefaultMatchingOptB="good looking";
$langDefaultMakeCorrespond1="Your dady is";
$langDefaultMakeCorrespond2="Your mother is";
$langDefineOptions="Please define the options";
$langMakeCorrespond="Make correspond";
$langFillLists="Please fill the two lists below";
$langGiveText="Please type the text";
$langDefineBlanks="Please define at least one blank with brackets [...]";
$langGiveAnswers="Please give the question's answers";
$langChooseGoodAnswer="Please choose a good answer";
$langChooseGoodAnswers="Please choose one or more good answers";


// question_list_admin.inc.php

$langNewQu="New question";
$langQuestionList="Question list of the exercise";
$langMoveUp="Move up";
$langMoveDown="Move down";
$langGetExistingQuestion="Get a question from another exercise";


// question_pool.php

$langQuestionPool="Question pool";
$langOrphanQuestions="Orphan questions";
$langNoQuestion="There is no question for the moment";
$langAllExercises="All exercises";
$langFilter="Filter";
$langGoBackToEx="Go back to the exercise";
$langReuse="Reuse";
$langConfirmDeleteQuestion="Are you sure to totally delete this question ?";


// admin.php

$langExerciseManagement="Exercise management";
$langQuestionManagement="Question / Answer management";
$langQuestionNotFound="Question not found";


// exercice_submit.php

$langExerciseNotFound="Exercice not found";
$langAlreadyAnswered="You already answered the question";
$langCurrentTime = "Current time";
$langMaxAllowedTime = "Maximum allowed time";
$langNoTimeLimit = "No time limitation";
$langAttempt = "Attempt";
$langOn = "on";
$langAvailableFrom = "Available from";
$langExerciseNotAvailable = "Exercise not available";
$langExerciseNoMoreAvailable = "Exercise no more available";
$langTo = "to";
$langNoMoreAttemptsAvailable = "You have reached the maximum number of allowed attempts.";

// exercise_result.php

$langElementList="Element list";
$langResult= "Result";
$langExeTime = "Time";
$langScore="Score";
$langCorrespondsTo="Corresponds to";
$langExpectedChoice="Expected choice";
$langYourTotalScore="Your total score is";
$langYourTime = "Your time is";
$langTimeOver = "Time is over, results are not submitted.";
$langTracking = "Tracking";
?>
