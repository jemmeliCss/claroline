<?php // $Id$
/**
 * @version CLAROLINE version 1.6
 * ----------------------------------------------------------------------
 * @copyright 2001, 2005 Universite catholique de Louvain (UCL)      |
 * @license GPL
 * @author claro team <info@claroline.net>
 * 
 * This page display global information about 
 */
require '../inc/claro_init_global.inc.php';

// exo_id is required
if( empty($_REQUEST['exo_id']) ) header("Location: ../exercice/exercice.php");

/**
 * DB tables definition
 */
$tbl_cdb_names = claro_sql_get_course_tbl();
$tbl_mdb_names = claro_sql_get_main_tbl();
$tbl_rel_course_user = $tbl_mdb_names['rel_course_user'  ];
$tbl_user            = $tbl_mdb_names['user'             ];
$tbl_quiz_test      = $tbl_cdb_names['quiz_test'              ];
$tbl_track_e_exercices = $tbl_cdb_names['track_e_exercices'];

include($includePath."/lib/statsUtils.lib.inc.php");

$is_allowedToTrack = $is_courseAdmin;

// get infos about the exercise
$sql = "SELECT `id`, `titre` `title`
        FROM `".$tbl_quiz_test."`
       WHERE `id` = ". (int) $_REQUEST['exo_id'];
$result = claro_sql_query($sql);
$exo_details = @mysql_fetch_array($result);



$interbredcrump[]= array ("url"=>"courseLog.php", "name"=> $langStatistics);
$nameTools = $langStatsOfExercise;

include($includePath."/claro_init_header.inc.php");
// display title
$titleTab['mainTitle'] = $nameTools;
$titleTab['subTitle'] = $langStatsOfExercise." : ".$exo_details['title'];
echo claro_disp_tool_title($titleTab);

if($is_allowedToTrack && $is_trackingEnabled) 
{

  // get global infos about scores in the exercise
  $sql = "SELECT  MIN(TEX.`exe_result`) AS `minimum`, 
                MAX(TEX.`exe_result`) AS `maximum`, 
                AVG(TEX.`exe_result`) AS `average`,
                MAX(TEX.`exe_weighting`) AS `weighting` ,
                COUNT(DISTINCT TEX.`exe_user_id`) AS `users`,
                COUNT(TEX.`exe_user_id`) AS `tusers`,
				AVG(`TEX`.`exe_time`) AS `avgTime`
        FROM `".$tbl_track_e_exercices."` AS TEX
        WHERE TEX.`exe_exo_id` = ".$exo_details['id']."
                AND TEX.`exe_user_id` IS NOT NULL";
  
  $result = claro_sql_query($sql);
  $exo_scores_details = mysql_fetch_array($result);
?>

<ul>
  <?php 
        if (isset($exo_score_details['weighting']) || $exo_scores_details['weighting'] != '')
            echo "<li>".$langWeighting." : ".$exo_scores_details['weighting']."</li>";

        if ( ! isset($exo_scores_details['minimum']) )
        {
          $exo_scores_details['minimum'] = 0;
          $exo_scores_details['maximum'] = 0;
          $exo_scores_details['average'] = 0;
        }
        else
        {
            // round average number for a beautifuler display :p
            $exo_scores_details['average'] = (round($exo_scores_details['average']*100)/100);
        }
  ?>
  <li><?php echo $langScoreMin; ?> : <?php echo $exo_scores_details['minimum']; ?></li>
  <li><?php echo $langScoreMax; ?> : <?php echo $exo_scores_details['maximum']; ?></li>
  <li><?php echo $langScoreAvg; ?> : <?php echo $exo_scores_details['average']; ?></li>
  <li><?php echo $langExeAvgTime; ?> : <?php echo claro_disp_duration(floor($exo_scores_details['avgTime'])); ?></li>
</ul>
<ul>
  <li><?php echo $langExerciseUsersAttempts; ?> : <?php echo $exo_scores_details['users']; ?></li>
  <li><?php echo $langExerciseTotalAttempts; ?> : <?php echo $exo_scores_details['tusers']; ?></li>
</ul>  


<?php
  // display details
   $sql = "SELECT `U`.`nom`, `U`.`prenom`, `U`.`user_id`,
            MIN(TEX.`exe_result`) AS `minimum`,
            MAX(TEX.`exe_result`) AS `maximum`,
            AVG(TEX.`exe_result`) AS `average`,
            COUNT(TEX.`exe_result`) AS `attempts`,
			AVG(TEX.`exe_time`) AS `avgTime`
    FROM `".$tbl_user."` AS `U`, `".$tbl_rel_course_user."` AS `CU`, `".$tbl_quiz_test."` AS `QT`
    LEFT JOIN `".$tbl_track_e_exercices."` AS `TEX`
          ON `CU`.`user_id` = `TEX`.`exe_user_id` 
          AND `QT`.`id` = `TEX`.`exe_exo_id`
    WHERE `CU`.`user_id` = `U`.`user_id`
      AND `CU`.`code_cours` = '".$_cid."'
      AND (
            `TEX`.`exe_exo_id` = ".$exo_details['id']." 
            OR 
            `TEX`.`exe_exo_id` IS NULL 
          )
    GROUP BY `U`.`user_id`
    ORDER BY `U`.`nom` ASC, `U`.`prenom` ASC";
    
    
  $result = claro_sql_query($sql);
  // display tab header
  echo "<table class=\"claroTable\" width=\"100%\" border=\"0\" cellspacing=\"2\">\n
      <tr class=\"headerX\" align=\"center\" valign=\"top\">\n
        <th>".$langStudent."</th>\n
        <th>".$langScoreMin."</th>\n
        <th>".$langScoreMax."</th>\n
        <th>".$langScoreAvg."</th>\n
        <th>".$langAttempts."</th>\n
        <th>".$langExeAvgTime."</th>\n
      </tr>\n
      <tbody>";
  // display tab content
  while ( $exo_users_details = mysql_fetch_array($result) )
  {
    if ( $exo_users_details['minimum'] == '' )
    {
      $exo_users_details['minimum'] = 0;
      $exo_users_details['maximum'] = 0;
    }
    echo 	 "<tr>\n"
      		."<td><a href=\"userLog.php?uInfo=".$exo_users_details['user_id']."&view=0100000&exoDet=".$exo_details['id']."\">"
			.$exo_users_details['nom']." ".$exo_users_details['prenom']."</a></td>\n"
      		."<td>".$exo_users_details['minimum']."</td>\n"
      		."<td>".$exo_users_details['maximum']."</td>\n"
      		."<td>".(round($exo_users_details['average']*100)/100)."</td>\n"
      		."<td>".$exo_users_details['attempts']."</td>\n"
      		."<td>".claro_disp_duration(floor($exo_users_details['avgTime']))."</td>\n"
    		."</tr>";
  }
  // foot of table
  echo "</tbody>\n</table>";

}
// not allowed
else
{
    if(!$is_trackingEnabled)
    {
        echo $langTrackingDisabled;
    }
    else
    {
        echo $langNotAllowed;
    }
}


?>
</table>

<?php
include($includePath."/claro_init_footer.inc.php");
?>
