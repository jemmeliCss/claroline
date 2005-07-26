<?php # $Id$

//----------------------------------------------------------------------
// CLAROLINE
//----------------------------------------------------------------------
// Copyright (c) 2001-2004 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

/*============================================================================
                                 EVENTS LIBRARY
  ============================================================================*/

/*
 * Functions of this library are used to record informations when some kind
 * of event occur. Each event has his own types of informations then each event
 * use its own function.
 */


// REGROUP TABLE NAMES FOR MAINTENANCE PURPOSE

$tbl_mdb_names 			   = claro_sql_get_main_tbl();
$tbl_track_e_default       = $tbl_mdb_names['track_e_default'];
$tbl_track_e_login         = $tbl_mdb_names['track_e_login'];
$tbl_track_e_open          = $tbl_mdb_names['track_e_open'];

// course db
$tbl_cdb_names 			  = claro_sql_get_course_tbl();
$tbl_track_e_access       = $tbl_cdb_names['track_e_access'];
$tbl_track_e_downloads    = $tbl_cdb_names['track_e_downloads'];
$tbl_track_e_uploads      = $tbl_cdb_names['track_e_uploads'];
$tbl_track_e_exercises    = $tbl_cdb_names['track_e_exercices'];
$tbl_track_e_exe_details  = $tbl_cdb_names['track_e_exe_details'];
$tbl_track_e_exe_answers  = $tbl_cdb_names['track_e_exe_answers'];


define("CONFVAL_LOG_DIRECT_IN_TABLE",true); //unstable with false
define("CONFVAL_INSERT_IS_DELAYED", true);

/**
 * Function found on php.net to replace the html_entity_decode (that only works in php 4.3.0 and upper)
 *
 */
function unhtmlentities ($string) 
{
   $trans_tbl = get_html_translation_table (HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   return strtr ($string, $trans_tbl);
}
  
  
/**
 *
 * @author Sebastien Piraux <pir@cerdecam.be>
 * @desc Record information for open event (when homepage is opened)
 */

function event_open()
{
    global $is_trackingEnabled ;
    // if tracking is disabled record nothing
    if( ! $is_trackingEnabled ) return 0;

    global $rootWeb ;
    global $tbl_track_e_open;

    if (isset($_SERVER['HTTP_REFERER'])) 
        $referer = $_SERVER['HTTP_REFERER'];
    else
        $referer = NULL;

    // record informations only if user comes from another site
    //if(!eregi($rootWeb,$referer))
    $pos = strpos($referer,$rootWeb);
    if( $pos === false )
    {
        $reallyNow = time();

        $sql = "INSERT INTO `".$tbl_track_e_open."`
                        (`open_date`)
                VALUES
                        (FROM_UNIXTIME($reallyNow))";

        $res = claro_sql_query($sql);
        //$mysql_query($sql);
    }
    return 1;
}


/**

 * @author Sebastien Piraux <pir@cerdecam.be>
 * @desc Record information for login event
     (when an user identifies himself with username & password)
 */

function event_login()
{
    global $is_trackingEnabled ;
    // if tracking is disabled record nothing
    if( ! $is_trackingEnabled ) return 0;

    global $_uid;
    global $tbl_track_e_login;

    $reallyNow = time();
    $sql = "INSERT INTO `".$tbl_track_e_login."`
            (`login_user_id`, 
             `login_ip`, 
             `login_date`)

             VALUES
                ('".$_uid."', 
                '".$_SERVER['REMOTE_ADDR']."',
                FROM_UNIXTIME(".$reallyNow."))";

    $res = claro_sql_query($sql);

	return 1;

}


/**
 * @param tool name of the tool (rubrique in mainDb.accueil table)
 * @author Sebastien Piraux <pir@cerdecam.be>
 * @desc Record information for access event for courses
 */
function event_access_course()
{
    global $is_trackingEnabled ;
    // if tracking is disabled record nothing
    if( ! $is_trackingEnabled ) return 0;

    global $_uid;
    global $tbl_track_e_access;

    $reallyNow = time();
    if($_uid)
    {
        $user_id = "'".$_uid."'";
    }
    else // anonymous
    {
        $user_id = "NULL";
    }
    $sql = "INSERT INTO `".$tbl_track_e_access."`
            (`access_user_id`,  
             `access_date`)
            VALUES
            (".$user_id.", 
            FROM_UNIXTIME(".$reallyNow."))";

        $res = claro_sql_query($sql);

    return 1;

}

/**
 * @param tid id of the tool user access (tid is a unique identifier of a tool occurence)
 * @param tlabel label of the tool the user access (tlabel is a unique identifier for a type of tool
 * @author Sebastien Piraux <pir@cerdecam.be>
 * @desc Record information for access event for tools
 */
function event_access_tool($tid, $tlabel)
{
    global $is_trackingEnabled ;
    // if tracking is disabled record nothing
    if( ! $is_trackingEnabled ) return 0;

    global $_uid;
    global $tbl_track_e_access;
    global $rootWeb;
    global $_course;

    $reallyNow = time();
    // record information only if user doesn't come from the tool itself
    if( !isset($_SESSION['tracking']['lastUsedTool']) || $_SESSION['tracking']['lastUsedTool'] != $tlabel )
    {
        if($_uid)
        {
            $user_id = "'".$_uid."'";
        }
        else // anonymous
        {
            $user_id = "NULL";
        }

        $sql = "INSERT INTO `".$tbl_track_e_access."`
                (`access_user_id`,
                 `access_tid`,
                 `access_tlabel`,
                 `access_date`)

             VALUES

             (".$user_id.",
              ".$tid.",
              '".$tlabel."',
              FROM_UNIXTIME(".$reallyNow."))";
              
        $res = claro_sql_query($sql);
        $_SESSION['tracking']['lastUsedTool'] = $tlabel;
    }
    return 1;
}

/**

 * @param doc_id id of document (id in mainDb.document table)
 * @author Sebastien Piraux <pir@cerdecam.be>
 * @desc Record information for download event
     (when an user click to d/l a document)
     it will be used in a redirection page
 */
function event_download($doc_url)
{
    global $is_trackingEnabled ;
    // if tracking is disabled record nothing
    if( ! $is_trackingEnabled ) return 0;

    global $_uid;

    global $tbl_track_e_downloads;

    $reallyNow = time();
    if($_uid)
    {
        $user_id = "'".$_uid."'";
    }
    else // anonymous
    {
        $user_id = "NULL";
    }

    $sql = "INSERT INTO `".$tbl_track_e_downloads."`
            (
             `down_user_id`,
             `down_doc_path`,
             `down_date`
            )

            VALUES
            (
             ".$user_id.",
             '".htmlspecialchars($doc_url,ENT_QUOTES)."', 
             FROM_UNIXTIME(".$reallyNow.")
            )";
                
    $res = claro_sql_query($sql);
    return 1;
}

/**

 * @param doc_id id of document (id in mainDb.document table)
 * @author Sebastien Piraux <pir@cerdecam.be>
 * @desc Record information for upload event
     used in the works tool to record informations when
     an user upload 1 work
 */
function event_upload($doc_id)
{
    global $is_trackingEnabled ;
    // if tracking is disabled record nothing
    if( ! $is_trackingEnabled ) return 0;

    global $_uid;

    global $tbl_track_e_uploads;

    $reallyNow = time();
    if($_uid)
    {
        $user_id = "'".$_uid."'";
    }
    else // anonymous
    {
        $user_id = "NULL";
    }
    
    $sql = "INSERT INTO `".$tbl_track_e_uploads."`
            (
             `upload_user_id`,
             `upload_work_id`, 
             `upload_date`
            )

            VALUES
            (
             ". (int)$user_id.", 
             '".(int)$doc_id."', 
             FROM_UNIXTIME(".$reallyNow.")
            )";
                
    $res = claro_sql_query($sql);
    return 1;
}

/**
 * @param exo_id ( id in courseDb exercices table )
 * @param result ( score @ exercice )
 * @param weighting ( higher score )
 * @return inserted id or false if the query cannot be done
 * @author Sebastien Piraux <pir@cerdecam.be>
 * @desc Record result of user when an exercice was done
*/
function event_exercice($exo_id,$score,$weighting,$time, $uid = "")
{
    global $is_trackingEnabled ;
    // if tracking is disabled record nothing
    if( ! $is_trackingEnabled ) return false;

    global $tbl_track_e_exercises;

    $reallyNow = time();
    if($uid && $uid != "")
    {
        $user_id = "'".$uid."'";
    }
    else // anonymous
    {
        $user_id = "NULL";
    }
    $sql = "INSERT INTO `".$tbl_track_e_exercises."`
          (
			`exe_user_id`,
			`exe_exo_id`,
			`exe_result`,
			`exe_weighting`,
			`exe_date`,
			`exe_time`
          )
          
          VALUES
          (
          ".$user_id.",
           '".$exo_id."',
           '".$score."',
           '".$weighting."',
           FROM_UNIXTIME(".$reallyNow."),
	   $time
          )";

    return claro_sql_query_insert_id($sql);
}

/**
 * @param exerciseTrackId id in track_e_exercices table
 * @param questionId
 * @param values array with user answers
 * @param questionResult result of this question
 * @author Sebastien Piraux <pir@cerdecam.be>
 * @desc Record result of user when an exercice was done
*/
function event_exercise_details($exerciseTrackId,$questionId,$values,$questionResult)
{
    global $is_trackingEnabled ;
    // if tracking is disabled record nothing
    if( ! $is_trackingEnabled ) return 0;

    global $tbl_track_e_exe_details, $tbl_track_e_exe_answers;

	// add the answer tracking informations
    $sql = "INSERT INTO `".$tbl_track_e_exe_details."`
          (
			`exercise_track_id`,
			`question_id`,
			`result`
          )
          VALUES
          (
          	".(int) $exerciseTrackId.",
           	'".(int) $questionId."',
           	'".(int) $questionResult."'
          )";
    $details_id = claro_sql_query_insert_id($sql);
    
    // check if previous query succeed to add answers
    if( $details_id )
    {
	    // add, if needed, the different answers of the user
	    // one line by answer
	    // each entry of $values should be correctly formatted depending on the question type
	    foreach( $values as $answer )
	    {
			$sql = "INSERT INTO `".$tbl_track_e_exe_answers."`
				(
					`details_id`,
					`answer`
				)
				VALUES
				(
				    ". (int)$details_id.",
				    '".addslashes($answer)."'
				)";
		    claro_sql_query($sql);
		}
	}
    return 1;
}

/**

 * @param type_event type of event to record
 * @param values indexed array of values (keys are the type of values, values are the event_values)
 * @author Sebastien Piraux <pir@cerdecam.be>
 * @desc Standard function for all users who wants to add an event recording in their pages
         e.g. : event_default("Exercice Result",array ("ex_id"=>"1", "result"=> "5", "weighting" => "20"));
*/
function event_default($type_event,$values)
{
    global $is_trackingEnabled ;
    // if tracking is disabled record nothing
    if( ! $is_trackingEnabled ) return 0;

    global $_uid;
    global $_cid;
    global $tbl_track_e_default;

    $reallyNow = time();

    if($_uid)
    {
        $user_id = "\"".(int)$_uid."\"";
    }
    else // anonymous
    {
        $user_id = "NULL";
    }

    if($_uid)
    {
        $cours_id = "\"".addslashes($_cid)."\"";
    }
    else // anonymous
    {
        $cours_id = "NULL";
    }

    $sqlValues = "";

    foreach($values as $type_value => $event_value)
    {
        if($sqlValues == "")
        {
            $sqlValues .= "('',".$user_id.",".$cours_id.",FROM_UNIXTIME(".$reallyNow."),\"".addslashes($type_event)."\",\"".addslashes($type_value)."\",\"".addslashes($event_value)."\")";
        }
        else
        {
            $sqlValues .= ",('',".$user_id.",".$cours_id.",FROM_UNIXTIME(".$reallyNow."),\"".addslashes($type_event)."\",\"".addslashes($type_value)."\",\"".addslashes($event_value)."\")";
        }
    }
    $sql = "INSERT INTO `".$tbl_track_e_default."`
            VALUES ".$sqlValues;


    $res = claro_sql_query($sql);
    return 1;
}
?>
