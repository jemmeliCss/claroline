<?php // $Id$
/*
+----------------------------------------------------------------------+
| CLAROLINE 1.6
+----------------------------------------------------------------------+
| Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
+----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or      |
      |   modify it under the terms of the GNU General Public License        |
      |   as published by the Free Software Foundation; either version 2     |
      |   of the License, or (at your option) any later version.             |
      +----------------------------------------------------------------------+
      | Authors: Olivier Brouckaert <oli.brouckaert@skynet.be>               |
      +----------------------------------------------------------------------+
*/

		/*>>>>>>>>>>>>>>>>>>>> ANSWER ADMINISTRATION <<<<<<<<<<<<<<<<<<<<*/

/**
 * This script allows to manage answers
 *
 * It is included from the script admin.php
 */

// ALLOWED_TO_INCLUDE is defined in admin.php
if(!defined('ALLOWED_TO_INCLUDE'))
{
	exit();
}

if( isset($_REQUEST['nbrAnswers']) ) $nbrAnswers = $_REQUEST['nbrAnswers'];
if( isset($_REQUEST['nbrOptions']) ) $nbrOptions = $_REQUEST['nbrOptions'];
if( isset($_REQUEST['nbrMatches']) ) $nbrMatches = $_REQUEST['nbrMatches'];
// main request parameters
if( isset($_REQUEST['correct']) ) 	$correct = $_REQUEST['correct'];
else                                $correct = "";
if( isset($_REQUEST['reponse']) ) $reponse = $_REQUEST['reponse'];
if( isset($_REQUEST['comment']) ) $comment = $_REQUEST['comment'];
// for matching question
if( isset($_REQUEST['match']) ) $match = $_REQUEST['match'];
if( isset($_REQUEST['option']) ) $option = $_REQUEST['option'];
if( isset($_REQUEST['sel']) ) $sel = $_REQUEST['sel'];
// for "fill in the blanks"
if( isset($_REQUEST['blanks']) ) $blanks = $_REQUEST['blanks'];

if( isset($_REQUEST['weighting']) ) $weighting = $_REQUEST['weighting'];
if( isset($_REQUEST['setWeighting']) ) $setWeighting = $_REQUEST['setWeighting'];

$questionName = $_SESSION['objQuestion']->selectTitle();
$questionStatement = $_SESSION['objQuestion']->selectDescription();
$answerType = $_SESSION['objQuestion']->selectType();
$attachedFile = $_SESSION['objQuestion']->selectAttachedFile();

$okAttachedFile = empty($attachedFile)?false:true;

// if we come from the warning box "this question is used in serveral exercises"
if( isset($modifyIn) )
{
	// if the user has chosed to modify the question only in the current exercise
	if($modifyIn == 'thisExercise')
	{
		// duplicates the question
		$questionId = $_SESSION['objQuestion']->duplicate();

		// deletes the old question
		$_SESSION['objQuestion']->delete($exerciseId);

		// removes the old question ID from the question list of the Exercise object
		$_SESSION['objExercise']->removeFromList($modifyAnswers);

		// adds the new question ID into the question list of the Exercise object
		$_SESSION['objExercise']->addToList($questionId);

		// construction of the duplicated Question
		$_SESSION['objQuestion'] = new Question();

		$_SESSION['objQuestion']->read($questionId);

		// adds the exercise ID into the exercise list of the Question object
		$_SESSION['objQuestion']->addToList($exerciseId);

		// copies answers from $modifyAnswers to $questionId
		$_SESSION['objAnswer']->duplicate($questionId);

		// construction of the duplicated Answers
		$_SESSION['objAnswer'] = new Answer($questionId);
	}

	if($answerType == UNIQUE_ANSWER || $answerType == MULTIPLE_ANSWER || $answerType == TRUEFALSE )
	{
		$correct = unserialize($correct);
		$reponse = unserialize($reponse);
		$comment = unserialize($comment);
		$weighting = unserialize($weighting);
	}
	elseif($answerType == MATCHING)
	{
		$option = unserialize($option);
		$match = unserialize($match);
		$sel = unserialize($sel);
		$weighting = unserialize($weighting);
	}
	else
	{
		$reponse = unserialize($reponse);
		$comment = unserialize($comment);
		$blanks = unserialize($blanks);
		$weighting = unserialize($weighting);
	}

	unset($_REQUEST['buttonBack']);
}

// the answer form has been submitted
if( isset($_REQUEST['submitAnswers']) || isset($_REQUEST['buttonBack']) )
{
	if($answerType == UNIQUE_ANSWER || $answerType == MULTIPLE_ANSWER || $answerType == TRUEFALSE)
	{
		$questionWeighting = 0;
		$nbrGoodAnswers = 0;

		for($i = 1; $i <= $nbrAnswers; $i++)
		{
			$reponse[$i] = trim($reponse[$i]);
			$comment[$i] = trim($comment[$i]);
			$weighting[$i] = (float)$weighting[$i];
			

			if($answerType == UNIQUE_ANSWER || $answerType == TRUEFALSE)
			{
				$goodAnswer = ( $correct == $i )?1:0;
			}
			else
			{
				$goodAnswer = isset($correct[$i])?true:false;
			}

			if($goodAnswer)
			{
				$nbrGoodAnswers++;

				// a good answer can't have a negative weighting
				$weighting[$i] = abs($weighting[$i]);

				// calculates the sum of answer weighting only if it is different from 0 and the answer is good
				if($weighting[$i])
				{
					$questionWeighting += $weighting[$i];
				}
			}
			else
			{
				// a bad answer can't have a positive weighting
				$weighting[$i] = 0 - abs($weighting[$i]);
			}

			// checks if field is empty
			if( $reponse[$i] == "" )
			{
				$msgErr = $langGiveAnswers;

				// clears answers already recorded into the Answer object
				$_SESSION['objAnswer']->cancel();

				break;
			}
			else
			{
				// adds the answer into the object
				$_SESSION['objAnswer']->createAnswer($reponse[$i],$goodAnswer,$comment[$i],$weighting[$i],$i);
			}
		}  // end for()

		if(empty($msgErr))
		{
 			if(!$nbrGoodAnswers)
			{
				$msgErr = ($answerType == UNIQUE_ANSWER || $answerType == TRUEFALSE)?$langChooseGoodAnswer:$langChooseGoodAnswers;

				// clears answers already recorded into the Answer object
				$_SESSION['objAnswer']->cancel();
			}
			// checks if the question is used in several exercises
			elseif($exerciseId && !empty($modifyIn) && !$modifyIn && $_SESSION['objQuestion']->selectNbrExercises() > 1)
			{
				$usedInSeveralExercises = 1;
			}
			else
			{
				// saves the answers into the data base
				$_SESSION['objAnswer']->save();

				// sets the total weighting of the question
				$_SESSION['objQuestion']->updateWeighting($questionWeighting);
				$_SESSION['objQuestion']->save($exerciseId);

				$editQuestion = $questionId;

				unset($modifyAnswers);
			}
		}
	}
	elseif($answerType == FILL_IN_BLANKS)
	{
		$reponse = trim($reponse);

		if(!isset($_REQUEST['buttonBack']))
		{
			if(isset($setWeighting) && $setWeighting )
			{
				if( isset($blanks) ) $blanks = unserialize($blanks);

				// checks if the question is used in several exercises
				if($exerciseId && !empty($modifyIn) && $_SESSION['objQuestion']->selectNbrExercises() > 1)
				{
					$usedInSeveralExercises = 1;
				}
				else
				{
					// separates text and weightings by '::'
					$reponse .= '::';

					$questionWeighting = 0;

					foreach($weighting as $val)
					{
						// a blank can't have a negative weighting
						$val = abs($val);

						$questionWeighting += $val;

						// adds blank weighting at the end of the text
						$reponse .= $val.',';
					}

					$reponse = substr($reponse,0,-1);

					$_SESSION['objAnswer']->createAnswer($reponse,0,'',0,'');
					$_SESSION['objAnswer']->save();

					// sets the total weighting of the question
					$_SESSION['objQuestion']->updateWeighting($questionWeighting);
					$_SESSION['objQuestion']->save($exerciseId);

					$editQuestion = $questionId;

					unset($modifyAnswers);
				}
			}
			// if no text has been typed or the text contains no blank
			elseif(empty($reponse))
			{
				$msgErr = $langGiveText;
			}
			elseif(!ereg('\[.+\]',$reponse))
			{
				$msgErr = $langDefineBlanks;
			}
			else
			{
				// now we're going to give a weighting to each blank
				$setWeighting = 1;

				unset($_REQUEST['submitAnswers']);

				// we save the answer because it will be modified
				$temp = $reponse;

				// blanks will be put into an array
				$blanks = Array();

				$i = 1;

				// the loop will stop at the end of the text
				while(1)
				{
					// quits the loop if there are no more blanks
					if(($pos = strpos($temp,'[')) === false)
					{
						break;
					}

					// removes characters till '['
					$temp = substr($temp,$pos+1);

					// quits the loop if there are no more blanks
					if(($pos = strpos($temp,']')) === false)
					{
						break;
					}

					// stores the found blank into the array
					$blanks[$i++] = substr($temp,0,$pos);

					// removes the character ']'
					$temp = substr($temp,$pos+1);
				}
			}
		}
		else
		{
			unset($setWeighting);
		}
	}
	elseif($answerType == MATCHING)
	{
		for($i=1;$i <= $nbrOptions;$i++)
		{
			$option[$i] = trim($option[$i]);

			// checks if field is empty
			if(empty($option[$i]))
			{
				$msgErr = $langFillLists;

				// clears options already recorded into the Answer object
				$_SESSION['objAnswer']->cancel();

				break;
			}
			else
			{
				// adds the option into the object
				$_SESSION['objAnswer']->createAnswer($option[$i],0,'',0,$i);
			}
		}

		$questionWeighting = 0;

		if(empty($msgErr))
		{
			for($j=1;$j <= $nbrMatches;$i++,$j++)
			{
				$match[$i] = trim($match[$i]);
				$weighting[$i] = abs( (float)$weighting[$i] );

				$questionWeighting += $weighting[$i];

				// checks if field is empty
				if(empty($match[$i]))
				{
					$msgErr = $langFillLists;

					// clears matches already recorded into the Answer object
					$_SESSION['objAnswer']->cancel();

					break;
				}
				// check if correct number
				else
				{
					// adds the answer into the object
					$_SESSION['objAnswer']->createAnswer($match[$i],$sel[$i],'',$weighting[$i],$i);
				}
			}
		}

		if(empty($msgErr))
		{
			// checks if the question is used in several exercises
			if($exerciseId && !$modifyIn && $_SESSION['objQuestion']->selectNbrExercises() > 1)
			{
				$usedInSeveralExercises = 1;
			}
			else
			{
				// all answers have been recorded, so we save them into the data base
				$_SESSION['objAnswer']->save();

				// sets the total weighting of the question
				$_SESSION['objQuestion']->updateWeighting($questionWeighting);
				$_SESSION['objQuestion']->save($exerciseId);

				$editQuestion = $questionId;

				unset($modifyAnswers);
			}
		}
	}
}

if( isset($modifyAnswers) )
{
	// construction of the Answer object
	$_SESSION['objAnswer'] = new Answer($questionId);

	if($answerType == UNIQUE_ANSWER || $answerType == MULTIPLE_ANSWER || $answerType == TRUEFALSE)
	{
		if(!isset($nbrAnswers))
		{
			$nbrAnswers = $_SESSION['objAnswer']->selectNbrAnswers();

			$reponse = array();
			$comment = array();
			$weighting = array();

			// initializing
			if($answerType == MULTIPLE_ANSWER)
			{
				$correct = Array();
			}
			else
			{
				$correct = 0;
			}

			for($i=1; $i <= $nbrAnswers;$i++)
			{
				$reponse[$i] = $_SESSION['objAnswer']->selectAnswer($i);
				$comment[$i] = $_SESSION['objAnswer']->selectComment($i);
				$weighting[$i] = $_SESSION['objAnswer']->selectWeighting($i);

				if($answerType == MULTIPLE_ANSWER)
				{
					$correct[$i] = $_SESSION['objAnswer']->isCorrect($i);
				}
				elseif($_SESSION['objAnswer']->isCorrect($i))
				{
					$correct = $i;
				}
			}
		}

		if( isset($_REQUEST['lessAnswers']) )
		{
			$nbrAnswers--;
		}

		if( isset($_REQUEST['moreAnswers']) )
		{
			$nbrAnswers++;
		}

		// minimum 2 answers
		if($nbrAnswers < 2)
		{
			$nbrAnswers = 2;
		}
	}
	elseif($answerType == FILL_IN_BLANKS)
	{
		if( !isset($_REQUEST['submitAnswers']) && !isset($_REQUEST['buttonBack']) )
		{
			if( !isset($setWeighting) )
			{
				// $reponse is like :  [British people] live in [United Kingdom]::5,5
				// split it to have
				// $reponse = [British people] live in [United Kingdom]
				// $weighting[0] = 5; $weighting[1] = 5;
				$reponse = $_SESSION['objAnswer']->selectAnswer(1);

				$separatorPosition = strrpos($reponse, '::');

				if( $separatorPosition !== false )  $weighting = explode(',', substr($reponse,$separatorPosition+1));
				else                                $weighting = array();
				
				if( $separatorPosition !== false ) 	$reponse = substr($reponse,0,$separatorPosition-1);
				// else $reponse = $reponse;

				$temp = Array();

				// keys of the array go from 1 to N and not from 0 to N-1
				for($i=0; $i < sizeof($weighting);$i++)
				{
					$temp[$i+1] = $weighting[$i];
				}

				$weighting = $temp;
			}
			elseif( empty($modifyIn) )
			{
				$weighting = unserialize($weighting);
			}
		}
	}
	elseif($answerType == MATCHING)
	{
		if(!isset($nbrOptions) || !isset($nbrMatches))
		{
			$option = array();
			$match = array();
			$sel = array();

			$nbrOptions = $nbrMatches = 0;

			// fills arrays with data from de data base
			for($i=1;$i <= $_SESSION['objAnswer']->selectNbrAnswers();$i++)
			{
				// it is a match
				if($_SESSION['objAnswer']->isCorrect($i))
				{
					$match[$i] = $_SESSION['objAnswer']->selectAnswer($i);
					$sel[$i] = $_SESSION['objAnswer']->isCorrect($i);
					$weighting[$i] = $_SESSION['objAnswer']->selectWeighting($i);
					$nbrMatches++;
				}
				// it is an option
				else
				{
					$option[$i] = $_SESSION['objAnswer']->selectAnswer($i);
					$nbrOptions++;
				}
			}
		}

		if( isset($_REQUEST['lessOptions']) )
		{
			// keeps the correct sequence of array keys when removing an option from the list
			for($i=$nbrOptions+1,$j=1; $nbrOptions > 2 && $j <= $nbrMatches;$i++,$j++)
			{
				$match[$i-1] = $match[$i];
				$sel[$i-1] = $sel[$i];
				$weighting[$i-1] = $weighting[$i];
			}

			unset($match[$i-1]);
			unset($sel[$i-1]);

			$nbrOptions--;
		}

		if( isset($_REQUEST['moreOptions']) )
		{
			// keeps the correct sequence of array keys when adding an option into the list
			for($i=$nbrMatches+$nbrOptions;$i > $nbrOptions;$i--)
			{
				$match[$i+1] = $match[$i];
				$sel[$i+1] = $sel[$i];
				$weighting[$i+1] = $weighting[$i];
			}

			unset($match[$i+1]);
			unset($sel[$i+1]);

			$nbrOptions++;
		}

		if( isset($_REQUEST['lessMatches']) )
		{
			$nbrMatches--;
		}

		if( isset($_REQUEST['moreMatches']) )
		{
			$nbrMatches++;
		}

		// minimum 2 options
		if($nbrOptions < 2)
		{
			$nbrOptions = 2;
		}

		// minimum 2 matches
		if($nbrMatches < 2)
		{
			$nbrMatches = 2;
		}

	}

	if( !isset($usedInSeveralExercises) || !$usedInSeveralExercises )
	{
		if($answerType == UNIQUE_ANSWER || $answerType == MULTIPLE_ANSWER || $answerType == TRUEFALSE)
		{
?>

<h3>
  <?php echo $questionName; ?>
</h3>

<?php
			if(!empty($questionStatement))
			{
				echo "<p>".$questionStatement."</p>";
			}
			
			if($okAttachedFile)
			{
				echo "<p>".display_attached_file($attachedFile)."</p>";
			}			
?>			
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?modifyAnswers=<?php echo $modifyAnswers; ?>">
<input type="hidden" name="formSent" value="1">
<input type="hidden" name="nbrAnswers" value="<?php echo $nbrAnswers; ?>">


<?php
			// if there is an error message
			if(!empty($msgErr))
			{
				claro_disp_message_box($msgErr);
			}
?>

<table class="claroTable">
<thead>
<tr class="headerX">
  <th>N�</th>
  <th><?php echo $langTrue; ?></th>
  <th><?php echo $langAnswer; ?></th>
  <th><?php echo $langComment; ?></th>
  <th><?php echo $langQuestionWeighting; ?></th>
</tr>
</thead>
<tbody>
<?php
			for($i=1;$i <= $nbrAnswers;$i++)
			{
?>

<tr>
  <td valign="top"><?php echo $i; ?></td>

<?php
				if($answerType == UNIQUE_ANSWER || $answerType == TRUEFALSE)
				{
?>

  <td valign="top"><input type="radio" value="<?php echo $i; ?>" name="correct" <?php if($correct == $i) echo 'checked="checked"'; ?>></td>

<?php
				}
				else
				{
?>

  <td valign="top"><input type="checkbox" value="1" name="correct[<?php echo $i; ?>]" <?php if( isset($correct[$i]) && $correct[$i] ) echo 'checked="checked"'; ?>></td>

<?php
				}
				if(!empty($reponse[$i])) $displayedResponse = htmlentities($reponse[$i]);
				elseif( $answerType == TRUEFALSE && $i == 1 ) $displayedResponse = $langTrue;
				elseif( $answerType == TRUEFALSE && $i == 2 ) $displayedResponse = $langFalse;
				else $displayedResponse = "";
?>

  <td align="left"><textarea wrap="virtual" rows="7" cols="25" name="reponse[<?php echo $i; ?>]"><?php echo $displayedResponse; ?></textarea></td>
  <td align="left"><textarea wrap="virtual" rows="7" cols="25" name="comment[<?php echo $i; ?>]"><?php if(isset($comment[$i])) echo htmlentities($comment[$i]); ?></textarea></td>
  <td valign="top"><input type="text" name="weighting[<?php echo $i; ?>]" size="5" value="<?php echo isset($weighting[$i])?$weighting[$i]:0; ?>"></td>
</tr>

<?php
  			}
?>
</tbody>
<tfoot>
<tr>
  <td colspan="5" align="center">
	<input type="submit" name="submitAnswers" value="<?php echo $langOk; ?>">
<?php
	if( $answerType != TRUEFALSE )
	{
?>
	&nbsp;&nbsp;<input type="submit" name="lessAnswers" value="<?php echo $langLessAnswers; ?>">
	&nbsp;&nbsp;<input type="submit" name="moreAnswers" value="<?php echo $langMoreAnswers; ?>">
<?php
	}
?>
	&nbsp;&nbsp;<input type="submit" name="cancelAnswers" value="<?php echo $langCancel; ?>">
  </td>
</tr>
</tfoot>
</table>
</form>

<?php
		}
		elseif($answerType == FILL_IN_BLANKS)
		{
?>

<h3>
  <?php echo $questionName; ?>
</h3>

<form name="formulaire" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?modifyAnswers=<?php echo $modifyAnswers; ?>">
<input type="hidden" name="formSent" value="1">
<input type="hidden" name="setWeighting" value="<?php if(isset($setWeighting)) echo $setWeighting; ?>">

<?php
			if(!isset($setWeighting) )
			{
?>

<input type="hidden" name="weighting" value="<?php echo isset($_REQUEST['submitAnswers'])?htmlentities($weighting):htmlentities(serialize($weighting)); ?>">

<?php
				if($okAttachedFile)
				{
					echo "<p>\n".display_attached_file($attachedFile)."\n</p>\n\n";
				}

				// if there is an error message
				if(!empty($msgErr))
				{
					claro_disp_message_box($msgErr);
				}
?>
<table border="0" cellpadding="5" width="500">
<tr>
  <td><?php echo $langTypeTextBelow.', '.$langAnd.' '.$langUseTagForBlank; ?>&nbsp;:</td>
</tr>
<tr>
  <td><textarea wrap="virtual" name="reponse" cols="65" rows="6"><?php if(!isset($_REQUEST['submitAnswers']) && empty($reponse)) echo $langDefaultTextInBlanks; else echo htmlentities($reponse); ?></textarea></td>
</tr>
<tr>
  <td colspan="5" align="center">
	<input type="submit" name="submitAnswers" value="<?php echo $langNext; ?> &gt;">
	&nbsp;&nbsp;<input type="submit" name="cancelAnswers" value="<?php echo $langCancel; ?>">
  </td>
</tr>
</table>

<?php
			}
			else
			{
?>

<input type="hidden" name="blanks" value="<?php echo htmlentities(serialize($blanks)); ?>">
<input type="hidden" name="reponse" value="<?php echo htmlentities($reponse); ?>">

<?php
				// if there is an error message
				if(!empty($msgErr))
				{
					claro_disp_message_box($msgErr);
				}
?>
<table border="0" cellpadding="5" width="500">
<tr>
  <td colspan="2"><?php echo $langWeightingForEachBlank; ?> :</td>
</tr>
<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<?php
				if( isset($blanks) && is_array($blanks) )
				{
					foreach($blanks as $i=>$blank)
					{
?>

<tr>
  <td width="50%"><?php echo $blank; ?> :</td>
  <td width="50%"><input type="text" name="weighting[<?php echo $i; ?>]" size="5" value="<?php if(isset($weighting[$i])) echo (float)$weighting[$i]; else echo '0'; ?>"></td>
</tr>

<?php
					}
	    		}
?>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" align="center">
	<input type="submit" name="buttonBack" value="&lt; <?php echo $langBack; ?>">
	&nbsp;&nbsp;<input type="submit" name="submitAnswers" value="<?php echo $langOk; ?>">
	&nbsp;&nbsp;<input type="submit" name="cancelAnswers" value="<?php echo $langCancel; ?>">
  </td>
</tr>
</table>

<?php
			}
?>

</form>

<?php
		}
		elseif($answerType == MATCHING)
		{
?>

<h3>
  <?php echo $questionName; ?>
</h3>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?modifyAnswers=<?php echo $modifyAnswers; ?>">
<input type="hidden" name="formSent" value="1">
<input type="hidden" name="nbrOptions" value="<?php echo $nbrOptions; ?>">
<input type="hidden" name="nbrMatches" value="<?php echo $nbrMatches; ?>">


<?php
			if($okAttachedFile)
			{
				echo "<p>\n".display_attached_file($attachedFile)."</p>\n\n";
			}

			// if there is an error message
			if(!empty($msgErr))
			{
				claro_disp_message_box($msgErr);
			}

			$listeOptions=Array();

			// creates an array with the option letters
			for($i=1,$j='A';$i <= $nbrOptions;$i++,$j++)
			{
				$listeOptions[$i] = $j;
			}
?>
<table border="0" cellpadding="5">
<tr>
  <td colspan="3"><?php echo $langMakeCorrespond; ?> :</td>
  <td><?php echo $langQuestionWeighting; ?> :</td>
</tr>

<?php
			for($j=1;$j <= $nbrMatches;$i++,$j++)
			{
                $inputValue = '';
                if(!isset($_REQUEST['formSent']) && !isset($match[$i]))
                {
					if($j == 1) $inputValue = $langDefaultMatchingProp1;
					elseif($j == 2) $inputValue = $langDefaultMatchingProp2;
				}
				else
				{
					 if( isset($match[$i]) ) $inputValue = htmlentities($match[$i]);
				}
?>

<tr>
  <td><?php echo $j; ?></td>
  <td><input type="text" name="match[<?php echo $i; ?>]" size="58" value="<?php echo $inputValue; ?>"></td>
  <td align="center"><select name="sel[<?php echo $i; ?>]">

<?php
				foreach($listeOptions as $key=>$val)
				{
?>

	<option value="<?php echo $key; ?>" <?php if( (!isset($_REQUEST['submitAnswers']) && !isset($sel[$i]) && $j == 2 && $val == 'B') || isset($sel[$i]) && $sel[$i] == $key) echo 'selected="selected"'; ?>><?php echo $val; ?></option>

<?php
				} // end foreach()
?>

  </select></td>
  <td align="center"><input type="text" size="8" name="weighting[<?php echo $i; ?>]" value="<?php if(!isset($_REQUEST['submitAnswers']) && !isset($weighting[$i])) echo '5'; else echo $weighting[$i]; ?>"></td>
</tr>

<?php
		  	} // end for()
?>

<tr>
  <td colspan="4">
	<input type="submit" name="lessMatches" value="<?php echo $langLessElements; ?>">
	&nbsp;&nbsp;<input type="submit" name="moreMatches" value="<?php echo $langMoreElements; ?>">
  </td>
</tr>
<tr>
  <td colspan="4"><?php echo $langDefineOptions; ?> :</td>
</tr>

<?php
			foreach($listeOptions as $key=>$val)
			{
                $inputValue = '';
                if(!isset($_REQUEST['formSent']) && !isset($option[$key]))
                {
					if($val == 'A') $inputValue = $langDefaultMatchingOpt1;
					elseif($val == 'B') $inputValue = $langDefaultMatchingOpt2;
				}
				else
				{
					 if( isset($option[$key]) ) $inputValue = htmlentities($option[$key]);
				}
?>

<tr>
  <td><?php echo $val; ?></td>
  <td colspan="3"><input type="text" name="option[<?php echo $key; ?>]" size="80" value="<?php echo $inputValue; ?>"></td>
</tr>

<?php
			 } // end foreach()
?>

<tr>
  <td colspan="4">
	<input type="submit" name="lessOptions" value="<?php echo $langLessElements; ?>">
	&nbsp;&nbsp;<input type="submit" name="moreOptions" value="<?php echo $langMoreElements; ?>">
  </td>
</tr>
<tr>
  <td colspan="4" align="center">
	<input type="submit" name="submitAnswers" value="<?php echo $langOk; ?>">
	&nbsp;&nbsp;<input type="submit" name="cancelAnswers" value="<?php echo $langCancel; ?>">
  </td>
</tr>
</table>
</form>

<?php
		}
	}
}
?>
