<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.6
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2005 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or      |
      |   modify it under the terms of the GNU General Public License        |
      |   as published by the Free Software Foundation; either version 2     |
      |   of the License, or (at your option) any later version.             |
      +----------------------------------------------------------------------+
      | Authors: Olivier Brouckaert <oli.brouckaert@skynet.be>               |
      +----------------------------------------------------------------------+
*/

		/*>>>>>>>>>>>>>>>>>>>> EXERCISE LIST <<<<<<<<<<<<<<<<<<<<*/

/**
 * This script shows the list of exercises for administrators and students.
 */

include('exercise.class.php');
include('question.class.php');
include('answer.class.php');

include('exercise.lib.php');

$tlabelReq = 'CLQWZ___';

require '../inc/claro_init_global.inc.php';

include($includePath."/lib/pager.lib.php");

/*******************************/
/* Clears the exercise session */
/*******************************/

unset($_SESSION['objExercise'	]);
unset($_SESSION['objQuestion'	]);
unset($_SESSION['objAnswer'		]);		
unset($_SESSION['questionList'	]);
unset($_SESSION['exerciseResult']);
unset($_SESSION['exeStartTime'	]);

// prevent inPathMode to be used when browsing an exercise in the exercise tool
$_SESSION['inPathMode'] = false;

claro_set_display_mode_available(true);

$is_allowedToEdit = claro_is_allowed_to_edit();
$is_allowedToTrack = claro_is_allowed_to_edit() && $is_trackingEnabled;

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
$tbl_track_e_exercises       = $tbl_cdb_names['track_e_exercices'      ];

// maximum number of exercises on a same page
$exercisesPerPage = 25;

$nameTools=$langExercices;

/* Asking for an export in IMS/QTI ?
 * We need to take care of it before any content has been sent.
 */
if (isset($export))
{
    include('exercise_export.php');
    
    // Get the corresponding XML
    $xml = export_exercise($export);
    
    // Send it if we got something. Otherwise, just continue as if nothing happened.
    if(!empty($xml))
    {
        header("Content-type: application/xml");
        header('Content-Disposition: attachment; filename="quiz_'. $export . '.xml"');
        echo $xml;
        exit;
    }
}


if ( ! $is_courseAllowed)
	claro_disp_auth_form();

include($includePath.'/claro_init_header.inc.php');

// used for stats
include($includePath.'/lib/events.lib.inc.php');

event_access_tool($_tid, $_courseTool['label']);

// need functions of statsutils lib to display previous exercices scores
include($includePath.'/lib/statsUtils.lib.inc.php');

claro_disp_tool_title($nameTools, $is_allowedToEdit ? 'help_exercise.php' : false);

// defines answer type for previous versions of Claroline, may be removed in Claroline 1.5
$sql = "UPDATE `".$tbl_quiz_question."`
	SET `q_position` = '1', `type` = '2'
	WHERE `q_position` IS NULL
		 OR `q_position` < '1'
		 OR `type` = '0'";
claro_sql_query($sql) or die("Error : UPDATE at line ".__LINE__);

// only for administrator
if($is_allowedToEdit)
{
	if(!empty($_REQUEST['choice']))
	{
		// construction of Exercise
		$objExerciseTmp = new Exercise();

		if($objExerciseTmp->read($_REQUEST['exerciseId']))
		{
			switch($_REQUEST['choice'])
			{
				case 'delete':	// deletes an exercise
								$objExerciseTmp->delete();

                                //if some learning path must be deleted too, just do it
                                if (isset($_REQUEST['lpmDel']) && $_REQUEST['lpmDel']=='true')
                                {
                                    //get module_id concerned (by the asset)...
                                    $sql = "SELECT `module_id`
											FROM `".$tbl_lp_asset."`
											WHERE `path` = '".$_REQUEST['exerciseId']."'";
                                    $aResult = claro_sql_query($sql);
                                    $aList = mysql_fetch_array($aResult);
                                    $idOfModule = $aList['module_id'];

                                    // delete the asset
                                    $sql = "DELETE
											FROM `".$tbl_lp_asset."`
											WHERE `path` = '".$_REQUEST['exerciseId']."'";
                                    claro_sql_query($sql);

                                    // delete the module
                                    $sql = "DELETE
											FROM `".$tbl_lp_module."`
											WHERE `module_id` = ".$idOfModule."";
                                    claro_sql_query($sql);

                                    // find the learning path module(s) concerned
                                    $sql = "SELECT *
											FROM `".$tbl_lp_rel_learnPath_module."`
											WHERE `module_id` = ".$idOfModule."";

                                    $lpmResult = claro_sql_query($sql);

                                    // delete any user progression info for this/those learning path module(s)
                                    $sql = "DELETE
											FROM `".$tbl_lp_user_module_progress."`
											WHERE
                                          ";
                                     while ($lpmList = mysql_fetch_array($lpmResult))
                                     {
                                        $sql.="`learnPath_module_id` = '".$lpmList['learnPath_module_id']."' OR ";
                                     }
                                     $sql.=" 0=1 ";
                                     claro_sql_query($sql);

                                     // delete the learning path module(s)
                                    $sql = "DELETE
											FROM `".$tbl_lp_rel_learnPath_module."`
											WHERE `module_id`=".$idOfModule."";
                                    claro_sql_query($sql);

                                } //end if at least in one learning path
								break;
				case 'enable':  // enables an exercise
								$objExerciseTmp->enable();
								$objExerciseTmp->save();

								break;
				case 'disable': // disables an exercise
								$objExerciseTmp->disable();
								$objExerciseTmp->save();

								break;
			}
		}

		// destruction of Exercise
		unset($objExerciseTmp);
	}

	$sql = 'SELECT `id`, `titre`, `type`, `active` 
	          FROM `'.$tbl_quiz_test.'` 
			  ORDER BY `id`';
}
// only for students
else
{
  if ($_uid)
  {
	$sql = 'SELECT `id`, `titre`, `type` 
	          FROM `'.$tbl_quiz_test.'` 
			  WHERE `active` = "1"
			  ORDER BY `id`';
  }
  else // anonymous user
  {
	$sql = 'SELECT `id`, `titre`, `type` 
	          FROM `'.$tbl_quiz_test.'` 
			  WHERE     `active`="1"
				 	AND `anonymous_attempts`="YES" 
			  ORDER BY `id`';
  }
}

// pager initialisation
if( !isset($_REQUEST['offset']) ) 	$offset = 0;
else 								$offset = $_REQUEST['offset'];


$myPager = new claro_sql_pager($sql, $offset, $exercisesPerPage);
$exercisesList = $myPager->get_result_list();


// commands
if($is_allowedToEdit)
{
	echo '<p>'."\n"
		.'<a class="claroCmd" href="admin.php">'.$langNewEx.'</a> | '."\n"
		.'<a class="claroCmd" href="question_pool.php">'.$langQuestionPool.'</a>'."\n"
		.'</p>'."\n\n";
}

//pager display
$myPager->disp_pager_tool_bar($_SERVER['PHP_SELF']);

?>


<table class="claroTable emphaseLine" border="0" align="center" cellpadding="2" cellspacing="2" width="100%">

<thead>
<tr class="headerX">
  <th>
	<?php echo $langExerciseName; ?>
  </th>
<?php
	if($is_allowedToEdit)
	{
?>
  <th>
	<?php echo $langModify; ?>
  </th>
  <th>
	<?php echo $langDelete; ?>
  </th>
  <th>
	<?php echo $langEnable.' / '.$langDisable; ?>
  </th>
  <th>
    <?php echo $langExport; ?>
  </th>
<?php
	}
	
  	if($is_allowedToTrack)
  	{
?>
  <th>
	<?php echo $langTracking; ?>
  </th>
<?php
  	}
?>
</tr>
</thead>
<?php

if( !is_array($exercisesList) || count($exercisesList) == 0 )
{
?>
<tbody>
<tr>
  <td <?php if($is_allowedToEdit) echo 'colspan="5"'; ?>><?php echo $langNoEx; ?></td>
</tr>
</tbody>
<?php
}



// see if exercises are used in learning path and must be protected by a confirm alert

$sql = "SELECT *,A.`path` AS thePath
          FROM `".$tbl_lp_rel_learnPath_module."` AS LPM, `".$tbl_lp_asset."` AS A, `".$tbl_lp_module."` AS M
          WHERE M.`contentType` = 'EXERCISE'
                AND A.`module_id` = M.`module_id`
                AND LPM.`module_id` = M.`module_id`";

$res = claro_sql_query($sql);

// build an array of action to add to link of deletion for each exercise included in a learning path.

$actionsForDelete[] = array();
while ($list = mysql_fetch_array($res))
{
    $exId = $list['thePath'];
    $toAdd = clean_str_for_javascript($langUsedInSeveralPath." ".$langConfirmDeleteExercise);
    $actionsForDelete[$exId] = "onclick=\"javascript:if(!confirm('".$toAdd."')) return false;\"";
}
$defaultConfirm = "onclick=\"javascript:if(!confirm('".clean_str_for_javascript($langConfirmDeleteExercise)."')) return false;\"";


$i = 1;
// while list exercises
foreach( $exercisesList as $exercise )
{
?>
<tbody>
<tr>

<?php
	// course admin only
	if($is_allowedToEdit)
	{
?>

  <td>
    <?php echo ( $i + $offset ).'.'; ?>
    &nbsp;
    <a href="exercice_submit.php?exerciseId=<?php echo $exercise['id']; ?>" <?php if(!$exercise['active']) echo 'class="invisible"'; ?>><?php echo $exercise['titre']; ?></a>
  </td>
  <td align="center"><a href="admin.php?exerciseId=<?php echo $exercise['id']; ?>"><img src="<?php echo $imgRepositoryWeb ?>edit.gif" border="0" alt="<?php echo htmlentities($langModify); ?>"></a></td>
  <td align="center">
  	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?choice=delete&amp;exerciseId=<?php echo $exercise['id']; if (isset($actionsForDelete[$exercise['id']])) { echo "&amp;lpmDel=true";}?>" <?php if (isset($actionsForDelete[$exercise['id']])) { echo $actionsForDelete[$exercise['id']];} else {echo $defaultConfirm;} ?>><img src="<?php echo $imgRepositoryWeb ?>delete.gif" border="0" alt="<?php echo htmlentities($langDelete); ?>"></a>
  </td>
<?php
		// if active
		if($exercise['active'])
		{
?>

  <td align="center"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?choice=disable&amp;page=<?php echo $page; ?>&amp;exerciseId=<?php echo $exercise['id']; ?>"><img src="<?php echo $imgRepositoryWeb ?>visible.gif" border="0" alt="<?php echo htmlentities($langDisable); ?>"></a></td>

<?php
		}
		// else if not active
		else
		{
?>

  <td align="center"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?choice=enable&amp;page=<?php echo $page; ?>&amp;exerciseId=<?php echo $exercise['id']; ?>"><img src="<?php echo $imgRepositoryWeb ?>invisible.gif" border="0" alt="<?php echo htmlentities($langEnable); ?>"></a></td>

<?php
		}
?>
  <td align="center"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?export=<?php echo $row['id']; ?>"><img src="<?php echo $clarolineRepositoryWeb; ?>img/export.gif"
  border="0" alt="<?php echo $langExport; ?>"></a></td>
  
<?php
    if($is_allowedToTrack)
    {
  ?>
          <td align="center"><a href="../tracking/exercises_details.php?exo_id=<?php echo $row['id']; ?>"><img src="<?php echo $clarolineRepositoryWeb ?>img/statistiques.gif" border="0" alt="<?php echo htmlentities($langTracking); ?>"></a></td>
     
   <?php
    }
    echo " </tr>";
	}
	// student only
	else
	{
?>
	  <td>
	  	<?php echo ( $i + $offset ).'.'; ?>&nbsp;
	  	<a href="exercice_submit.php?exerciseId=<?php echo $exercise['id']; ?>"><?php echo $exercise['titre']; ?></a>
	  </td>

<?php
	}
	$i++;
}	// end while()
?>
</tbody>
</table>

<?php
/*****************************************/
/* Exercise Results (uses tracking tool) */
/*****************************************/

// if tracking is enabled && user is not anomymous
if($is_trackingEnabled && $_uid):
?>

<br /><br />
<h3><?php echo $langMyResults; ?></h3>
<table class="claroTable emphaseLine" cellpadding="2" cellspacing="2" border="0" width="80%">
<thead>
<tr class="headerX">
  <th><?php echo $langExercice; ?></th>
  <th><?php echo $langDate; ?></th>
  <th><?php echo $langResult; ?></th>
  <th><?php echo $langExeTime; ?></th>	
</tr>
</thead>

<?php
$sql="SELECT `ce`.`titre`, `te`.`exe_result` ,
			 `te`.`exe_weighting`, UNIX_TIMESTAMP(`te`.`exe_date`) AS `exeDate`,
			 `te`.`exe_time`
      FROM `".$tbl_quiz_test."` AS ce , `".$tbl_track_e_exercises."` AS te
      WHERE `te`.`exe_user_id` = '".$_uid."'
      AND `te`.`exe_exo_id` = `ce`.`id`
      ORDER BY `ce`.`titre` ASC, `te`.`exe_date` ASC";

$results = claro_sql_query_fetch_all($sql);

echo "<tbody>";

foreach($results as $row)
{

?>
<tr>
  <td><?php echo $row['titre']; ?></td>
  <td><small><?php echo claro_disp_localised_date($dateTimeFormatLong,$row['exeDate']); ?></small></td>
  <td><?php echo $row['exe_result']; ?> / <?php echo $row['exe_weighting']; ?></td>
  <td><?php echo disp_minutes_seconds($row['exe_time']); ?></td>
</tr>

<?php

}


if( !is_array($results) || sizeof($results) == 0 )
{
?>

<tr>
  <td colspan="4"><?php echo $langNoResultYet; ?></td>
</tr>

<?php
}
?>
</tbody>
</table>

<?php
endif; // end if tracking is enabled

include($includePath.'/claro_init_footer.inc.php');
?>
