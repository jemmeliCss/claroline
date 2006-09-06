<?php // $Id$
/**
 * CLAROLINE
 *
 * @version 1.8 $Revision$
 *
 * @copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @author Claro Team <cvs@claroline.net>
 *
 */

$tlabelReq = 'CLQWZ';
 
require '../../inc/claro_init_global.inc.php';

if ( !$_cid || !$is_courseAllowed ) claro_disp_auth_form(true);

$is_allowedToEdit = claro_is_allowed_to_edit();

// courseadmin reserved page
if( !$is_allowedToEdit )
{
	header("Location: ../exercise.php");
	exit();	
}

// tool libraries
include_once '../lib/exercise.class.php'; 

include_once '../lib/exercise.lib.php';

// claroline libraries
include_once $includePath . '/lib/form.lib.php';

/*
 * Execute commands
 */
if ( isset($_REQUEST['cmd']) )	$cmd = $_REQUEST['cmd'];
else							$cmd = '';

if( isset($_REQUEST['exId']) && is_numeric($_REQUEST['exId']) ) $exId = (int) $_REQUEST['exId'];
else															$exId = null;

if( isset($_REQUEST['quId']) && is_numeric($_REQUEST['quId']) ) $quId = (int) $_REQUEST['quId'];
else															$quId = null;


$exercise = new Exercise();

if( !is_null($exId) && !$exercise->load($exId) ) 	
{
	$cmd = 'rqEdit';  
}
 	

$displayForm = false; 
$displaySettings = true;

if( $cmd == 'rmQu' && !is_null($quId) )
{
	$exercise->removeQuestion($quId);
}

if( $cmd == 'mvUp' && !is_null($quId) )
{
	$exercise->moveQuestionUp($quId);
}

if( $cmd == 'mvDown' && !is_null($quId) )
{
	$exercise->moveQuestionDown($quId);
}

if( $cmd == 'exEdit' )
{
	$exercise->setTitle($_REQUEST['title']);
	$exercise->setDescription($_REQUEST['description']);
	$exercise->setDisplayType($_REQUEST['displayType']);

	if( isset($_REQUEST['randomize']) && $_REQUEST['randomize'] )
	{
		$exercise->setShuffle($_REQUEST['questionDrawn']);
	}
	else
	{
		$exercise->setShuffle(0);
	}
	
	$exercise->setShowAnswers($_REQUEST['showAnswers']);
	
	$exercise->setStartDate( mktime($_REQUEST['startHour'],$_REQUEST['startMinute'],0,$_REQUEST['startMonth'],$_REQUEST['startDay'],$_REQUEST['startYear']) );
	
	if( isset($_REQUEST['useEndDate']) && $_REQUEST['useEndDate'] )
	{
		$exercise->setEndDate( mktime($_REQUEST['endHour'],$_REQUEST['endMinute'],0,$_REQUEST['endMonth'],$_REQUEST['endDay'],$_REQUEST['endYear']) );
	}
	else
	{
		$exercise->setEndDate(null);
	}
	
	if( isset($_REQUEST['useTimeLimit']) && $_REQUEST['useTimeLimit'] )
	{
		$exercise->setTimeLimit( $_REQUEST['timeLimitMin']*60 + $_REQUEST['timeLimitSec'] );
	}
	else
	{
		$exercise->setTimeLimit(0);				
	}
	
	$exercise->setAttempts($_REQUEST['attempts']);
	$exercise->setAnonymousAttempts($_REQUEST['anonymousAttempts']);
	
	if( $exercise->validate() )
	{
		if( $insertedId = $exercise->save() )
		{
			if( is_null($exId) )
			{
				$dialogBox = get_lang('%name added', array('%name' => get_lang('Exercise')));
				$exId = $insertedId;
			}
			else
			{
				$dialogBox = get_lang('%name modified', array('%name' => get_lang('Exercise')));
			} 
			$displaySettings = true;				
		}
		else
		{
			// sql error in save() ?
			$cmd = 'rqEdit';	
		}
		
	}
	else
	{
		if( claro_failure::get_last_failure() == 'exercise_no_title' )
		{
			$dialogBox = get_lang('Field \'%name\' is required', array('%name' => get_lang('Title')));
		}
		elseif( claro_failure::get_last_failure() == 'exercise_incorrect_dates')
		{
			$dialogBox = get_lang('Start date must be before end date ...');
		}
		$cmd = 'rqEdit';		
	}
}	
	
if( $cmd == 'rqEdit' )
{
	$form['title'] 				= $exercise->getTitle();
	$form['description'] 		= $exercise->getDescription();
	$form['displayType'] 		= $exercise->getDisplayType();
	$form['randomize'] 			= (boolean) $exercise->getShuffle() > 0;
	$form['questionDrawn']		= $exercise->getShuffle();
	$form['showAnswers'] 		= $exercise->getShowAnswers();
	
	$form['startDate'] 			= $exercise->getStartDate(); // unix

	if( is_null($exercise->getEndDate()) )
	{
		$form['useEndDate']		= false;
		$form['endDate'] 		= 0;
	}
	else
	{
		$form['useEndDate']		= true;
		$form['endDate'] 		= $exercise->getEndDate();
	}
	
	$form['useTimeLimit'] 		= (boolean) $exercise->getTimeLimit();
	$form['timeLimitSec']       = $exercise->getTimeLimit() % 60 ;
    $form['timeLimitMin'] 		= ($exercise->getTimeLimit() - $form['timeLimitSec']) / 60;
	
	$form['attempts'] 			= $exercise->getAttempts();
	$form['anonymousAttempts'] 	= $exercise->getAnonymousAttempts();
	
	$displayForm = true;
}	



/*
 * Output
 */

$interbredcrump[]= array ('url' => '../exercise.php', 'name' => get_lang('Exercises'));

if( !is_null($exId) ) 	$_SERVER['QUERY_STRING'] = 'exId='.$exId;
else					$_SERVER['QUERY_STRING'] = '';  

if( is_null($exId) )		
{
	$nameTools = get_lang('New exercise');
	$toolTitle = $nameTools;
}
elseif( $cmd == 'rqEdit' )
{
	$nameTools = get_lang('Edit exercise');
	$toolTitle['mainTitle'] = $nameTools;
	$toolTitle['subTitle'] = $exercise->getTitle();
}
else
{
	$nameTools = get_lang('Exercise');
	$toolTitle['mainTitle'] = $nameTools;
	$toolTitle['subTitle'] = $exercise->getTitle();
}

 
include($includePath.'/claro_init_header.inc.php');
 
echo claro_html_tool_title($toolTitle);

// dialog box if required 
if( !empty($dialogBox) ) echo claro_html_message_box($dialogBox);


if( $displayForm )
{
	echo '<form method="post" action="./edit_exercise.php?exId='.$exId.'" >' . "\n\n"
	.	 '<input type="hidden" name="cmd" value="exEdit" />' . "\n"
	.	 '<input type="hidden" name="claroFormId" value="'.uniqid('').'">' . "\n";
	
	echo '<table border="0" cellpadding="5">' . "\n";
	
	//-- 
	// title
	echo '<tr>' . "\n"
	.	 '<td valign="top"><label for="title">'.get_lang('Title').'&nbsp;<span class="required">*</span>&nbsp;:</label></td>' . "\n"
	.	 '<td><input type="text" name="title" id="title" size="60" maxlength="200" value="'.$form['title'].'" /></td>' . "\n"
	.	 '</tr>' . "\n\n";
	
	// description
	echo '<tr>' . "\n"
	.	 '<td valign="top"><label for="description">'.get_lang('Description').'&nbsp;:</label></td>' . "\n"
	.	 '<td>'.claro_html_textarea_editor('description', htmlspecialchars($form['description'])).'</td>' . "\n"
	.	 '</tr>' . "\n\n";
	
	// exercise type
	echo '<tr>' . "\n"
	.	 '<td valign="top">'.get_lang('Exercise type').'&nbsp;:</td>' . "\n"
	.	 '<td>'
	.	 '<input type="radio" name="displayType" id="displayTypeOne" value="ONEPAGE"'
	.	 ( $form['displayType'] == 'ONEPAGE'?' checked="checked"':' ') . '>'
	.	 ' <label for="displayTypeOne">'.get_lang('On an unique page').'</label>'
	.	 '<br />'
	.	 '<input type="radio" name="displayType" id="displayTypeSeq" value="SEQUENTIAL"'
	.	 ( $form['displayType'] == 'SEQUENTIAL'?' checked="checked"':' ') . '>'
	.	 ' <label for="displayTypeSeq">'.get_lang('One question per page (sequential)').'</label>'
	.	 '</td>' . "\n"
	.	 '</tr>' . "\n\n";
	
	$questionCount = count($exercise->getQuestionList());
	
	if( !is_null($exId) && $questionCount > 0 )
	{
		// prepare select option list
		for( $i = 1; $i <= $questionCount ; $i++)
		{
			$questionDrawnOptions[$i] = $i; 	
		}
		
		echo '<tr>' . "\n"
		.	 '<td valign="top">'.get_lang('Random questions').'&nbsp;:</td>' . "\n"
		.	 '<td>' . "\n"
		.	 '<input type="checkbox" name="randomize" id="randomize" '
		.	 ( $form['randomize']?' checked="checked"':' ') . '/> '
        . get_lang('<label1>Yes</label1>, <label2>take</label2> %nb questions among %total',
                    array ( '<label1>' => '<label for="randomize">',
                            '</label1>' => '</label>',
                            '<label2>' => '<label for="questionDrawn">',
                            '</label2>' => '</label>',
                            '%nb' => claro_html_form_select('questionDrawn', 
                                                            $questionDrawnOptions,
                                                            $form['questionDrawn'],
                                                            array('id' => 'questionDrawn') ) , 
                            '%total' =>  $questionCount ) )
		.	 '</td>' . "\n"
		.	 '</tr>' . "\n\n";	

	}
	
	//-- advanced part
	echo '<tr>' . "\n"
	.	 '<td colspan="2">'
	.	 '<hr />'
	.	 '<strong>'.get_lang('Advanced').'</strong> <small>('.get_lang('Optional').')</small>'
	.	 '</td>' . "\n"
	.	 '</tr>' . "\n\n";
	
	// start date
	echo '<tr>' . "\n"
	.	 '<td valign="top">'.get_lang('Start date').'&nbsp;:</td>' . "\n"
	.	 '<td>'
	.	 claro_disp_date_form('startDay', 'startMonth', 'startYear', $form['startDate'], 'long')." - ".claro_disp_time_form("startHour", "startMinute", $form['startDate'])
	.	 '<small>' . get_lang('(d/m/y hh:mm)') . '</small>'
	.	 '</td>' . "\n"
	.	 '</tr>' . "\n\n";	

	// end date
	echo '<tr>' . "\n"
	.	 '<td valign="top">'.get_lang('End date').'&nbsp;:</td>' . "\n"
	.	 '<td>'
	.	 '<input type="checkbox" name="useEndDate" id="useEndDate" '
	.	 ( $form['useEndDate']?' checked="checked"':' ') . '/>'
	.	 ' <label for="useEndDate">'.get_lang('Yes').'</label>,' . "\n"
	.	 claro_disp_date_form('endDay', 'endMonth', 'endYear', $form['endDate'], 'long')." - ".claro_disp_time_form("endHour", "endMinute", $form['endDate'])
	.	 '<small>' . get_lang('(d/m/y hh:mm)') . '</small>'
	.	 '</td>' . "\n"
	.	 '</tr>' . "\n\n";
		
	// time limit
	echo '<tr>' . "\n"
	.	 '<td valign="top">'.get_lang('Time limit').'&nbsp;:</td>' . "\n"
	.	 '<td>'
	.	 '<input type="checkbox" name="useTimeLimit" id="useTimeLimit" '
	.	 ( $form['useTimeLimit']?' checked="checked"':' ') . '/>'
	.	 ' <label for="useTimeLimit">'.get_lang('Yes').'</label>,' . "\n"
	.	 ' <input type="text" name="timeLimitMin" id="timeLimitMin" size="3" maxlength="3"  value="'.$form['timeLimitMin'].'" /> '.get_lang('min.')
	.	 ' <input type="text" name="timeLimitSec" id="timeLimitSec" size="2" maxlength="2"  value="'.$form['timeLimitSec'].'" /> '.get_lang('sec.')
	.	 '</td>' . "\n"
	.	 '</tr>' . "\n\n";
	
	// attempts allowed 
	echo '<tr>' . "\n"
	.	 '<td valign="top"><label for="attempts">'.get_lang('Attempts allowed').'&nbsp;:</label></td>' . "\n"
	.	 '<td>'
	.	 '<select name="attempts" id="attempts">' . "\n"
	.	 '<option value="0"' . ( $form['attempts'] < 1?' selected="selected"':' ') . '>' . get_lang('unlimited') . '</option>' . "\n"
	.	 '<option value="1"' . ( $form['attempts'] == 1?' selected="selected"':' ') . '>1</option>' ."\n"
	.	 '<option value="2"' . ( $form['attempts'] == 2?' selected="selected"':' ') . '>2</option>' ."\n"
	.	 '<option value="3"' . ( $form['attempts'] == 3?' selected="selected"':' ') . '>3</option>' ."\n"
	.	 '<option value="4"' . ( $form['attempts'] == 4?' selected="selected"':' ') . '>4</option>' ."\n"
	.	 '<option value="5"' . ( $form['attempts'] >= 5?' selected="selected"':' ') . '>5</option>' ."\n"
	.	 '</select>' . "\n"      
	.	 '</td>' . "\n"
	.	 '</tr>' . "\n\n";
		
	// anonymous attempts 
	echo '<tr>' . "\n"
	.	 '<td valign="top">'.get_lang('Anonymous attempts').'&nbsp;:</td>' . "\n"
	.	 '<td>'
	.	 '<input type="radio" name="anonymousAttempts" id="anonymousAttemptsAllowed" value="ALLOWED"'
	.	 ( $form['anonymousAttempts'] == 'ALLOWED'?' checked="checked"':' ') . '>'
	.	 ' <label for="anonymousAttemptsAllowed">'.get_lang('Allowed : do not record usernames in tracking, anonymous users can do the exercise.').'</label>'
	.	 '<br />'
	.	 '<input type="radio" name="anonymousAttempts" id="anonymousAttemptsNotAllowed" value="NOTALLOWED"'
	.	 ( $form['anonymousAttempts'] == 'NOTALLOWED'?' checked="checked"':' ') . '>'
	.	 ' <label for="anonymousAttemptsNotAllowed">'.get_lang('Not allowed : record usernames in tracking, anonymous users cannot do the exercise.').'</label>'
	.	 '</td>' . "\n"
	.	 '</tr>' . "\n\n";	
	
	// show answers
	echo '<tr>' . "\n"
	.	 '<td valign="top">'.get_lang('Show answers').'&nbsp;:</td>' . "\n"
	.	 '<td>'
	.	 '<input type="radio" name="showAnswers" id="showAnswerAlways" value="ALWAYS"'
	.	 ( $form['showAnswers'] == 'ALWAYS'?' checked="checked"':' ') . '>'
	.	 ' <label for="showAnswerAlways">'.get_lang('Yes').'</label>'
	.	 '<br />'
	.	 '<input type="radio" name="showAnswers" id="showAnswerLastTry" value="LASTTRY"'
	.	 ( $form['showAnswers'] == 'LASTTRY'?' checked="checked"':' ') . '>'
	.	 ' <label for="showAnswerLastTry">'.get_lang('After last allowed attempt').'</label>'
	.	 '<br />'
	.	 '<input type="radio" name="showAnswers" id="showAnswerNever" value="NEVER"'
	.	 ( $form['showAnswers'] == 'NEVER'?' checked="checked"':' ') . '>'
	.	 ' <label for="showAnswerNever">'.get_lang('No').'</label>'	
	.	 '</td>' . "\n"
	.	 '</tr>' . "\n\n";	

	//-- 
	echo '<tr>' . "\n"
	.	 '<td>&nbsp;</td>' . "\n"
	.	 '<td><small>' . get_lang('<span class="required">*</span> denotes required field') . '</small></td>' . "\n"
	.	 '</tr>' . "\n\n";
	
	//-- buttons
	echo '<tr>' . "\n"
	.	 '<td>&nbsp;</td>' . "\n"
	.	 '<td>'
	.	 '<input type="submit" name="" id="" value="'.get_lang('Ok').'" />&nbsp;&nbsp;'
	.	 claro_html_button('../exercise.php', get_lang("Cancel") )
	.	 '</td>' . "\n"
	.	 '</tr>' . "\n\n";
	
	echo '</table>' . "\n\n"
	.	 '</form>' . "\n\n";
}
else
{
	//-- exercise settings

	echo '<blockquote>'.claro_parse_user_text($exercise->getDescription()).'</blockquote>' . "\n"
	.	 '<ul style="font-size:small;">' . "\n";
	
  	echo '<li>'
  	.	 get_lang('Exercise type').'&nbsp;: '
  	.	 ( $exercise->getDisplayType() == 'SEQUENTIAL'?get_lang('One question per page (sequential)'):get_lang('On an unique page') )
  	.	 '</li>' . "\n";
  	
  	echo '<li>'
  	.	 get_lang('Random questions').'&nbsp;: '
  	.	 ( $exercise->getShuffle() > 0?get_lang('Yes'):get_lang('No') )
  	.	 '</li>' . "\n";  	
  	
	echo '<li>'
  	.	 get_lang('Start date').'&nbsp;: '
  	.	 claro_disp_localised_date($dateTimeFormatLong, $exercise->getStartDate())
  	.	 '</li>' . "\n";
  	
	echo '<li>'
	.	 get_lang('End date').'&nbsp;: ';
		
  	if( !is_null($exercise->getEndDate()) )
  	{
		echo claro_disp_localised_date($dateTimeFormatLong, $exercise->getEndDate());
  	}
  	else
  	{
  		echo get_lang('No closing date');
  	}
  	
  	echo '</li>' . "\n";

  	echo '<li>'
  	.	 get_lang('Time limit').'&nbsp;: '
  	.	 ( $exercise->getTimeLimit() > 0 ? claro_disp_duration($exercise->getTimeLimit()) : get_lang('No time limitation') )
  	.	 '</li>' . "\n";
  	
  	echo '<li>'
  	.	 get_lang('Attempts allowed') . '&nbsp;: '
  	.	 ( $exercise->getAttempts() > 0 ? $exercise->getAttempts() : get_lang('unlimited') )
  	.	 '</li>' . "\n";
  	
	echo '<li>'
  	.	 get_lang('Anonymous attempts') . '&nbsp;: ';
  	if ( $exercise->getAnonymousAttempts() == 'ALLOWED') 	echo get_lang('Allowed : do not record usernames in tracking, anonymous users can do the exercise.'); 
	else													echo get_lang('Not allowed : record usernames in tracking, anonymous users cannot do the exercise.');
  	echo '</li>' . "\n";
  	
    echo '<li>'
    .	 get_lang('Show answers')." : ";
    switch($exercise->getShowAnswers())
    {
      case 'ALWAYS' : echo get_lang('Yes'); break;
      case 'LASTTRY' : echo get_lang('After last allowed attempt'); break;
      case 'NEVER'  : echo get_lang('No'); break;
    }
	echo '</li>' . "\n";
	
	echo '</ul>' . "\n\n"; 
	
	
	//-- claroCmd
	$cmd_menu = array();
	$cmd_menu[] = '<a class="claroCmd" href="./edit_exercise.php?exId='.$exId.'&amp;cmd=rqEdit">'
				. '<img src="'.$clarolineRepositoryWeb.'img/edit.gif" border="0" alt="" />'
				. get_lang('Edit exercise settings')
				. '</a>';
	$cmd_menu[] = '<a class="claroCmd" href="./edit_question.php?exId='.$exId.'&amp;cmd=rqEdit">'.get_lang('New question').'</a>';
	$cmd_menu[] = '<a class="claroCmd" href="./question_pool.php?exId='.$exId.'">'.get_lang('Get a question from another exercise').'</a>';

	
	echo claro_html_menu_horizontal($cmd_menu);
	
	//-- question list	
	$questionList = $exercise->getQuestionList();
	
	echo '<table class="claroTable emphaseLine" border="0" align="center" cellpadding="2" cellspacing="2" width="100%">' . "\n\n"
	.	 '<thead>' . "\n"
	.	 '<tr class="headerX">' . "\n"
	.	 '<th>' . get_lang('Question') . '</th>' . "\n"
	.	 '<th>' . get_lang('Answer type') . '</th>' . "\n"
	.	 '<th>' . get_lang('Modify') . '</th>' . "\n"
	.	 '<th>' . get_lang('Remove') . '</th>' . "\n"
	.	 '<th colspan="2">' . get_lang('Order') . '</th>' . "\n"
	.	 '</tr>' . "\n"
	.	 '</thead>' . "\n\n"		
	.	 '<tbody>' . "\n";
	
	if( !empty($questionList) )
	{		
		$localizedQuestionType = get_localized_question_type();
			
		$questionIterator = 0;
		
		foreach( $questionList as $question )
		{
			$questionIterator++;
			
			echo '<tr>' . "\n"
			.	 '<td>'.$question['title'].'</td>' . "\n";

			// answer type			
			echo '<td><small>'.$localizedQuestionType[$question['type']].'</small></td>' . "\n";
			
			// edit
			echo '<td align="center">'
			.	 '<a href="edit_question.php?exId='.$exId.'&amp;quId='.$question['id'].'">'
			.	 '<img src="'.$clarolineRepositoryWeb.'img/edit.gif" border="0" alt="'.get_lang('Modify').'" />'
			.	 '</a>'
			.	 '</td>' . "\n";
			
			// remove question from exercise
			$confirmString = get_lang('Are you sure you want to remove the question from the exercise ?');		
			
			echo '<td align="center">'
			.	 '<a href="edit_exercise.php?exId='.$exId.'&amp;cmd=rmQu&amp;quId='.$question['id'].'" onclick="javascript:if(!confirm(\''.clean_str_for_javascript($confirmString).'\')) return false;">'
			.	 '<img src="'.$clarolineRepositoryWeb.'img/delete.gif" border="0" alt="'.get_lang('Remove').'" />'
			.	 '</a>'
			.	 '</td>' . "\n";
			
			// order
			// up
			echo '<td align="center">';
			if( $questionIterator > 1 )
			{
				echo '<a href="edit_exercise.php?exId='.$exId.'&amp;quId='.$question['id'].'&amp;cmd=mvUp">'
				.	 '<img src="'.$clarolineRepositoryWeb.'img/up.gif" border="0" alt="'.get_lang('Move up').'" />'
				.	 '</a>';
			}
			else
			{
				echo '&nbsp;';	
			}
			echo '</td>' . "\n";
			// down
			echo '<td align="center">';
			if( $questionIterator < count($questionList) )
			{
				echo '<a href="edit_exercise.php?exId='.$exId.'&amp;quId='.$question['id'].'&amp;cmd=mvDown">'
				.	 '<img src="'.$clarolineRepositoryWeb.'img/down.gif" border="0" alt="'.get_lang('Move down').'" />'
				.	 '</a>';
			}
			else
			{
				echo '&nbsp;';	
			}
			echo '</td>' . "\n";
				
			echo '</tr>' . "\n\n";;
			
		}
		
	}
	else 
	{
		echo '<tr>' . "\n"
		.	 '<td colspan="6">' . get_lang('Empty') . '</td>' . "\n"
		.	 '</tr>' . "\n\n";	
	}
	echo '</tbody>' . "\n\n"
	.	 '</table>' . "\n\n";
}

include($includePath.'/claro_init_footer.inc.php');
 
?>
