<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.6
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or      |
      |   modify it under the terms of the GNU General Public License        |
      |   as published by the Free Software Foundation; either version 2     |
      |   of the License, or (at your option) any later version.             |
      +----------------------------------------------------------------------+
      | Authors: Olivier Brouckaert <oli.brouckaert@skynet.be>               |
      | Changed by Guillaume Lederer <lederer@cerdecam.be>                   |
      |              Sébastien Piraux <piraux@cerdecam.be>                   |
      +----------------------------------------------------------------------+
*/

		/*>>>>>>>>>>>>>>>>>>>> EXERCISE RESULT <<<<<<<<<<<<<<<<<<<<*/

/**
 * This script gets informations from the script "exercise_submit.php",
 * through the session, and calculates the score of the student for
 * that exercise.
 *
 * Then it shows results at screen.
 */
include('exercise.class.php');
include('question.class.php');
include('answer.class.php');

include('exercise.lib.php');

// answer types
define('UNIQUE_ANSWER',	 1);
define('MULTIPLE_ANSWER',2);
define('FILL_IN_BLANKS', 3);
define('MATCHING',		 4);

$langFile='exercice';

require '../inc/claro_init_global.inc.php';

include($includePath.'/lib/text.lib.php');
/*
 * DB tables definition
 */
$tbl_cdb_names = claro_sql_get_course_tbl();
$tbl_lp_learnPath            = $tbl_cdb_names['lp_learnPath'           ];
$tbl_lp_rel_learnPath_module = $tbl_cdb_names['lp_rel_learnPath_module'];
$tbl_lp_user_module_progress = $tbl_cdb_names['lp_user_module_progress'];
$tbl_lp_module               = $tbl_cdb_names['lp_module'              ];
$tbl_lp_asset                = $tbl_cdb_names['lp_asset'               ];
$tbl_quiz_answer             = $tbl_cdb_names['quiz_answer'            ];
$tbl_quiz_question           = $tbl_cdb_names['quiz_question'          ];
$tbl_quiz_rel_test_question  = $tbl_cdb_names['quiz_rel_test_question' ];
$tbl_quiz_test               = $tbl_cdb_names['quiz_test'              ];

$TBL_EXERCICE_QUESTION = $tbl_quiz_rel_test_question; // No use in the script
$TBL_EXERCICES         = $tbl_quiz_test;              // No use in the script
$TBL_QUESTIONS         = $tbl_quiz_question;          // No use in the script
$TBL_REPONSES          = $tbl_quiz_answer;			  // No use in the script

$TBL_TRACK_EXERCISES	= $_course['dbNameGlu'].'track_e_exercices';
$TABLELEARNPATH         = $tbl_lp_learnPath;
$TABLEMODULE            = $tbl_lp_module; // No use in the script
$TABLELEARNPATHMODULE   = $tbl_lp_rel_learnPath_module;
$TABLEASSET             = $tbl_lp_asset;
$TABLEUSERMODULEPROGRESS= $tbl_lp_user_module_progress;

// if the above variables are empty or incorrect, stops the script
if(!is_array($exerciseResult) || !is_array($questionList) || !is_object($objExercise))
{
	die($langExerciseNotFound);
}

$exerciseTitle		= $objExercise->selectTitle();
$showAnswers 		= $objExercise->get_show_answer();
$exerciseMaxTime 	= $objExercise->get_max_time();

$nameTools=$langExercice;

// calculate time needed to complete the exercise
if (isset($_SESSION['exeStartTime']))
{
	$timeToCompleteExe =  time() - $_SESSION['exeStartTime'];
}

// deal with the learning path mode

if ($_SESSION['inPathMode']== true)          // learning path mode
{
	include($includePath."/lib/learnPath.lib.inc.php");
	$is_allowedToEdit = false; // do not allow to be in admin mode during a path progression
    // need to include the learningPath langfile for the added interbredcrump
   	// echo minimal html page header so that the page is valid
	// FIXME : find a better solution than duplicate the css link
	echo '<html>
		<head>
			<title>'.$exerciseTitle.'</title>
			<link rel="stylesheet" type="text/css" href="'.$clarolineRepositoryWeb.'css/default.css" />
		</head>
		<body>';
}
else                                        // normal exercise mode
{
	$is_allowedToEdit = true; // allow to be in admin mode
	$interbredcrump[] = array("url" => "exercice.php","name" => $langExercices);
	include($includePath.'/claro_init_header.inc.php');
}

?>

<h3>
  <?php echo stripslashes($exerciseTitle).' : '.$langResult; ?>
</h3>
<?php

    if($_SESSION['inPathMode']!= true) // exercise mode
    {
            echo "<form method=\"get\" action=\"exercice.php\">";
    }
    else    // Learning path mode
    {
            echo "<form method=\"get\" action=\"../learnPath/navigation/backFromExercise.php\">\n
            <input type=\"hidden\" name=\"op\" value=\"finish\">";
    }

	$i=$totalScore=$totalWeighting=0;
	
	// check if max allowed time has been respected 
	if ( $exerciseMaxTime > 0 && $exerciseMaxTime < $timeToCompleteExe )  
	{
		$displayAnswers 	= false;
		$displayScore 		= false;
	}
	else
	{
		$displayScore = true;
		// always display score except if the 
		// check if answers have to be shown
		if ( $showAnswers == 'ALWAYS' )
		{
			$displayAnswers = true;
		}
		else
		{
			// $showAnswers == 'NEVER'
			$displayAnswers = false;
		}
	} // end else of if ($timeToCompleteExe ...

	// for each question
	foreach($questionList as $questionId)
	{
		// gets the student choice for this question
		$choice=$exerciseResult[$questionId];

		// creates a temporary Question object
		$objQuestionTmp=new Question();

		$objQuestionTmp->read($questionId);

		$questionName=$objQuestionTmp->selectTitle();
		$questionWeighting=$objQuestionTmp->selectWeighting();
		$answerType=$objQuestionTmp->selectType();

		// destruction of the Question object
		unset($objQuestionTmp);

		if($answerType == UNIQUE_ANSWER || $answerType == MULTIPLE_ANSWER)
		{
			$colspan=4;
		}
		elseif($answerType == MATCHING)
		{
			$colspan=2;
		}
		else
		{
			$colspan=1;
		}
		
		if($displayAnswers)
		{
?>

<table width="100%" border="0" cellpadding="3" cellspacing="2">
<tr bgcolor="#DDDEBC">
  <td colspan="<?php echo $colspan; ?>">
	<?php echo $langQuestion.' '.($i+1); ?>
  </td>
</tr>
<tr>
  <td colspan="<?php echo $colspan; ?>">
	<?php echo $questionName; ?>
  </td>
</tr>

<?php
			if($answerType == UNIQUE_ANSWER || $answerType == MULTIPLE_ANSWER)
			{
?>

<tr>
  <td width="5%" valign="top" align="center" nowrap="nowrap">
	<small><i><?php echo $langChoice; ?></i></small>
  </td>
  <td width="5%" valign="top" nowrap="nowrap">
	<small><i><?php echo $langExpectedChoice; ?></i></small>
  </td>
  <td width="45%" valign="top">
	<small><i><?php echo $langAnswer; ?></i></small>
  </td>
  <td width="45%" valign="top">
	<small><i><?php echo $langComment; ?></i></small>
  </td>
</tr>

<?php
			}
			elseif($answerType == FILL_IN_BLANKS)
			{
?>

<tr>
  <td>
	<small><i><?php echo $langAnswer; ?></i></small>
  </td>
</tr>

<?php
			}
			else
			{
?>

<tr>
  <td width="50%">
	<small><i><?php echo $langElementList; ?></i></small>
  </td>
  <td width="50%">
	<small><i><?php echo $langCorrespondsTo; ?></i></small>
  </td>
</tr>

<?php
			}
		} // end if ($displayAnswers)
		// construction of the Answer object
		$objAnswerTmp=new Answer($questionId);

		$nbrAnswers=$objAnswerTmp->selectNbrAnswers();

		$questionScore=0;

		for($answerId=1;$answerId <= $nbrAnswers;$answerId++)
		{
			$answer=$objAnswerTmp->selectAnswer($answerId);
			$answerComment=$objAnswerTmp->selectComment($answerId);
			$answerCorrect=$objAnswerTmp->isCorrect($answerId);
			$answerWeighting=$objAnswerTmp->selectWeighting($answerId);

			switch($answerType)
			{
				// for unique answer
				case UNIQUE_ANSWER :	$studentChoice=($choice == $answerId)?1:0;

										if($studentChoice)
										{
										  	$questionScore+=$answerWeighting;
											$totalScore+=$answerWeighting;
										}

										break;
				// for multiple answers
				case MULTIPLE_ANSWER :	$studentChoice=$choice[$answerId];

										if($studentChoice)
										{
											$questionScore+=$answerWeighting;
											$totalScore+=$answerWeighting;
										}

										break;
				// for fill in the blanks
				case FILL_IN_BLANKS :	// splits text and weightings that are joined with the character '::'
										list($answer,$answerWeighting)=explode('::',$answer);

										// splits weightings that are joined with a comma
										$answerWeighting=explode(',',$answerWeighting);

										// we save the answer because it will be modified
										$temp=$answer;

										$answer='';

										$j=0;

										// the loop will stop at the end of the text
										while(1)
										{
											// quits the loop if there are no more blanks
											if(($pos = strpos($temp,'[')) === false)
											{
												// adds the end of the text
												$answer.=$temp;
												break;
											}

											// adds the piece of text that is before the blank and ended by [
											$answer.=substr($temp,0,$pos+1);

											$temp=substr($temp,$pos+1);

											// quits the loop if there are no more blanks
											if(($pos = strpos($temp,']')) === false)
											{
												// adds the end of the text
												$answer.=$temp;
												break;
											}

											$choice[$j]=trim(stripslashes($choice[$j]));

											// if the word entered by the student IS the same as the one defined by the professor
											if(strtolower(substr($temp,0,$pos)) == strtolower($choice[$j]))
											{
												// gives the related weighting to the student
												$questionScore+=$answerWeighting[$j];

												// increments total score
												$totalScore+=$answerWeighting[$j];

												// adds the word in green at the end of the string
												$answer.=$choice[$j];
											}
											// else if the word entered by the student IS NOT the same as the one defined by the professor
											elseif(!empty($choice[$j]))
											{
												// adds the word in red at the end of the string, and strikes it
												$answer.='<font color="red"><s>'.$choice[$j].'</s></font>';
											}
											else
											{
												// adds a tabulation if no word has been typed by the student
												$answer.='&nbsp;&nbsp;&nbsp;';
											}

											// adds the correct word, followed by ] to close the blank
											$answer.=' / <font color="green"><b>'.substr($temp,0,$pos).'</b></font>]';

											$j++;

											$temp=substr($temp,$pos+1);
										}

										break;
				// for matching
				case MATCHING :			if($answerCorrect)
										{
											if($answerCorrect == $choice[$answerId])
											{
												$questionScore+=$answerWeighting;
												$totalScore+=$answerWeighting;
												$choice[$answerId]=$matching[$choice[$answerId]];
											}
											elseif(!$choice[$answerId])
											{
												$choice[$answerId]='&nbsp;&nbsp;&nbsp;';
											}
											else
											{
												$choice[$answerId]='<font color="red"><s>'.$matching[$choice[$answerId]].'</s></font>';
											}
										}
										else
										{
											$matching[$answerId]=$answer;
										}
										break;
			}	// end switch()

			if( ($answerType != MATCHING || $answerCorrect) && $displayAnswers)
			{
				if($answerType == UNIQUE_ANSWER || $answerType == MULTIPLE_ANSWER)
				{
?>

<tr>
  <td width="5%" align="center">
	<img src="<?php echo $clarolineRepositoryWeb ?>img/<?php echo ($answerType == UNIQUE_ANSWER)?'radio':'checkbox'; echo $studentChoice?'_on':'_off'; ?>.gif" border="0">
  </td>
  <td width="5%" align="center">
	<img src="<?php echo $clarolineRepositoryWeb ?>img/<?php echo ($answerType == UNIQUE_ANSWER)?'radio':'checkbox'; echo $answerCorrect?'_on':'_off'; ?>.gif" border="0">
  </td>
  <td width="45%">
	<?php echo $answer; ?>
  </td>
  <td width="45%">
	<?php if($studentChoice) echo claro_parse_user_text(make_clickable($answerComment)); else echo '&nbsp;'; ?>
  </td>
</tr>

<?php
				}
				elseif($answerType == FILL_IN_BLANKS)
				{
?>

<tr>
  <td>
	<?php echo claro_parse_user_text($answer); ?>
  </td>
</tr>

<?php
				}
				else
				{
?>

<tr>
  <td width="50%">
	<?php echo $answer; ?>
  </td>
  <td width="50%">
	<?php echo $choice[$answerId]; ?> / <font color="green"><b><?php echo $matching[$answerCorrect]; ?></b></font>
  </td>
</tr>

<?php
				}
			} // end of if( ($answerType != MATCHING || $answerCorrect) && $displayAnswers)
		}	// end for()

		if($displayAnswers)
		{
?>
<tr>
  <td colspan="<?php echo $colspan; ?>" align="right">
	<b><?php echo "$langScore : $questionScore/$questionWeighting"; ?></b>
  </td>
</tr>
</table>

<?php
		}
		// destruction of Answer
		unset($objAnswerTmp);

		$i++;

		$totalWeighting+=$questionWeighting;
	}	// end foreach()
?>

<table width="100%" border="0" cellpadding="3" cellspacing="2">
<tr>
  <td align="center">
	<?php 
		echo $langYourTime." ".disp_minutes_seconds($timeToCompleteExe); 
		if( $exerciseMaxTime > 0 )
		{
			echo "<br />".$langMaxAllowedTime." ".disp_minutes_seconds($exerciseMaxTime);
		}
	?>
  </td>
</tr>
<tr>
  <td align="center">
	<b>
<?php 
	if ( $displayScore )
	{
		echo $langYourTotalScore." ". $totalScore."/".$totalWeighting;
	}
	else
	{
		echo $langTimeOver;
	}
?>
</b>
  </td>

</tr>
<tr>
<tr>
  <td align="center">
    <br>
	<input type="submit" value="<?php echo $langFinish; ?>">
  </td>
</tr>
</table>

</form>

<br>

<?php

/*******************************/
/* Tracking of results         */
/*******************************/

// if tracking is enabled
if($is_trackingEnabled && $displayScore)
{
    @include($includePath.'/lib/events.lib.inc.php');
    // if anonymousAttemps is true : record anonymous user stats, record authentified user stats without uid
    if ( $objExercise->anonymous_attempts()  )
    {
        event_exercice($objExercise->selectId(),$totalScore,$totalWeighting,$timeToCompleteExe );
    }
    elseif( $_uid ) // anonymous attempts not allowed, record stats with uid only if uid is set
    {
      event_exercice($objExercise->selectId(),$totalScore,$totalWeighting,$timeToCompleteExe, $_uid );  
    }

}

// record progression 
if($_SESSION['inPathMode'] == true && $displayScore ) // learning path mode
{
    // update raw in DB to keep the best one, so update only if new raw is better  AND if user NOT anonymous

    if($_uid)
    {
        if ( $totalWeighting != 0 )
        {
                $newRaw = @round($totalScore/$totalWeighting*100);
        }
        else
        {
                $newRaw = 0;
        }
        $scoreMin = 0;
        $scoreMax = $totalWeighting;
        // need learningPath_module_id and raw_to_pass value
        $sql = "SELECT LPM.`raw_to_pass`, LPM.`learnPath_module_id`, UMP.`total_time`, UMP.`raw`
                  FROM `".$tbl_lp_rel_learnPath_module."` AS LPM, `".$TABLEUSERMODULEPROGRESS."` AS UMP
                 WHERE LPM.`learnPath_id` = '".$_SESSION['path_id']."'
                   AND LPM.`module_id` = '".$_SESSION['module_id']."'
				   AND LPM.`learnPath_module_id` = UMP.`learnPath_module_id`
				   AND UMP.`user_id`";

        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);

		$scormSessionTime = seconds_to_scorm_time($timeToCompleteExe);
        
		// build sql query
		$sql = "UPDATE `".$TABLEUSERMODULEPROGRESS."` SET ";
		// if recorded score is less then the new score => update raw, credit and status
		if ($row['raw'] < $totalScore) 
		{ 
			// update raw
			$sql .= "`raw` = $totalScore,";
			// update credit and statut if needed ( score is better than raw_to_pass )
			if ($row['raw_to_pass'] <= $newRaw)
			{
				$sql .= "	`credit` = 'CREDIT',
					 		`lesson_status` = 'PASSED',";
			}
			else // minimum raw to pass needed to get credit 
			{
				$sql .= "	`credit` = 'NO-CREDIT',
							`lesson_status` = 'FAILED',";
			}
		}// else don't change raw, credit and lesson_status

		// default query statements
		$sql .= "	`scoreMin` 		= $scoreMin,
					`scoreMax` 		= $scoreMax,
					`total_time`	= '".addScormTime($row['total_time'], $scormSessionTime)."',
					`session_time`	= '".$scormSessionTime."'
                 WHERE `learnPath_module_id` = ".$row['learnPath_module_id']."
                   AND `user_id` = $_uid";
	    mysql_query($sql);
    }

}

if ($_SESSION['inPathMode'] != true) 
{
  include($includePath.'/claro_init_footer.inc.php');
}
else
{
  // display minimal html footer
  echo '	</body>
	</html>';
  // clean exercise session vars only if in learning path mode
  // because I don't know why the original author of exercise tool did not unset these here
  session_unregister('exerciseResult');
  session_unregister('questionList');
}

?>
