<?php // $Id$
if ( count( get_included_files() ) == 1 ) die( '---' );
/**
 * CLAROLINE
 *
 * Function to update course tool 1.8 to 1.9
 *
 * - READ THE SAMPLE AND COPY PASTE IT
 *
 * - ADD TWICE MORE COMMENT THAT YOU THINK NEEDED
 *
 * This code would be splited by task for the 1.8 Stable but code inside
 * function won't change, so let's go to write it.
 *
 * @version 1.9 $Revision$
 *
 * @copyright (c) 2001-2008 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @see http://www.claroline.net/wiki/index.php/Upgrade_claroline_1.6
 *
 * @package UPGRADE
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Mathieu Laurent   <mla@claroline.net>
 * @author Christophe Gesché <moosh@claroline.net>
 *
 */

/*===========================================================================
 Upgrade to claroline 1.8
 ===========================================================================*/

/**
 * Upgrade course repository files and script to 1.8
 */

/*function course_repository_upgrade_to_19 ($course_code)
{
    global $currentCourseVersion, $currentcoursePathSys;

    $versionRequiredToProceed = '/^1.7/';
    $tool = 'CLINDEX';

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :
                
                if ( is_writable($currentcoursePathSys) )
                {
                    if ( !is_dir($currentcoursePathSys) ) 
                        claro_mkdir($currentcoursePathSys);
                    if ( !is_dir($currentcoursePathSys.'/chat') )
                        claro_mkdir($currentcoursePathSys.'/chat');
                    if ( !is_dir($currentcoursePathSys.'/modules') )
                        claro_mkdir($currentcoursePathSys.'/modules');
                    if ( !is_dir($currentcoursePathSys.'/scormPackages') )
                        claro_mkdir($currentcoursePathSys . '/scormPackages');
            
                    $step = set_upgrade_status($tool, 2, $course_code);
                }
                else
                {
                    log_message(sprintf('Repository %s not writable', $currentcoursePathSys));
                    return $step;
                }

            case 2 :

                // build index.php of course
                $fd = fopen($currentcoursePathSys . '/index.php', 'w');
        
                if (!$fd) return $step ;

                // build index.php
                $string = '<?php ' . "\n"
                    . 'header (\'Location: '. $GLOBALS['urlAppend'] . '/claroline/course/index.php?cid=' . rawurlencode($course_code) . '\') ;' . "\n"
                    . '?' . '>' . "\n" ;

                if ( ! fwrite($fd, $string) ) return $step;
                if ( ! fclose($fd) )          return $step;
                    
                $step = set_upgrade_status($tool, 0, $course_code);

            default :
                return $step;
        }
    }
    return false ;
}*/

/**
 * Upgrade foo tool to 1.8
 *
 * explanation of task
 *
 * @param $course_code string
 * @return boolean whether true if succeed
 */

/*function group_upgrade_to_19($course_code)
{
    global $currentCourseVersion;

    $versionRequiredToProceed = '/^1.8/';
    $tool = 'CLGRP';
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        // On init , $step = 1
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :

                $sql_step1 = " CREATE TABLE
                        `".$currentCourseDbNameGlu."course_properties`
                        (
                            `id` int(11) NOT NULL auto_increment,
                            `name` varchar(255) NOT NULL default '',
                            `value` varchar(255) default NULL,
                            `category` varchar(255) default NULL,
                            PRIMARY KEY  (`id`)
                        ) TYPE=MyISAM ";

                if ( upgrade_sql_query($sql_step1) )
                {
                    $step = set_upgrade_status($tool, 2, $course_code);
                }
                else
                {
                    return $step;
                }

            case 2 :

                $sql = "SELECT self_registration,
                               private,
                               nbGroupPerUser,
                               forum,
                               document,
                               wiki,
                               chat
                    FROM `".$currentCourseDbNameGlu."group_property`";

                $groupSettings = claro_sql_query_get_single_row($sql);

                if ( is_array($groupSettings) )
                {
                    $sql = "INSERT
                            INTO `".$currentCourseDbNameGlu."course_properties`
                                   (`name`, `value`, `category`)
                            VALUES
                            ('self_registration', '".$groupSettings['self_registration']."', 'GROUP'),
                            ('nbGroupPerUser',    '".$groupSettings['nbGroupPerUser'   ]."', 'GROUP'),
                            ('private',           '".$groupSettings['private'          ]."', 'GROUP'),
                            ('CLFRM',             '".$groupSettings['forum'            ]."', 'GROUP'),
                            ('CLDOC',             '".$groupSettings['document'         ]."', 'GROUP'),
                            ('CLWIKI',            '".$groupSettings['wiki'             ]."', 'GROUP'),
                            ('CLCHT',             '".$groupSettings['chat'             ]."', 'GROUP')";
                }

                if ( upgrade_sql_query($sql) )
                {
                    $step = set_upgrade_status($tool, 3, $course_code);
                }
                else
                {
                    return $step;
                }

            case 3 :

                $sql = "DROP TABLE IF EXISTS`".$currentCourseDbNameGlu."group_property`";

                if ( upgrade_sql_query($sql) )
                {
                    $step = set_upgrade_status($tool, 4, $course_code);
                }
                else
                {
                    return $step;
                }

            case 4 :

                $sql = "UPDATE `".$currentCourseDbNameGlu."group_team`
                        SET `maxStudent` = NULL
                        WHERE `maxStudent` = 0 ";

                if ( upgrade_sql_query($sql) )
                {
                    $step = set_upgrade_status($tool, 0, $course_code);
                }
                else
                {
                    return $step;
                }


            default :
                return $step;
        }
    }

    return false;
}*/

/**
 * Upgrade foo tool to 1.8
 *
 * explanation of task
 *
 * @param $course_code string
 * @return boolean whether true if succeed
 */

function tool_list_upgrade_to_19 ($course_code)
{
    global $currentCourseVersion;

    $versionRequiredToProceed = '/^1.8/';
    $tool = 'TOOLLIST';
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        // On init , $step = 1
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :
                $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $currentCourseDbNameGlu . "tool_list` 
                              ADD `activated` ENUM('true','false') NOT NULL DEFAULT 'true'";
                
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1, $course_code);
                else return $step ;
                
            case 2 :
                $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $currentCourseDbNameGlu . "tool_list` 
                              ADD `installed` ENUM('true','false') NOT NULL DEFAULT 'true'";
                
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, 0, $course_code);
                else return $step ;
                
            default :
                $step = set_upgrade_status($tool, 0, $course_code);
                return $step;
        }
    }
    return false;
}

function chat_upgrade_to_19 ($course_code)
{
    /*
    global $currentCourseVersion;

    $versionRequiredToProceed = '/^1.8/';
    $tool = 'TOOLLIST';
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        $groupExportFile     = $coursePath.'/group/'.claro_get_current_group_data('directory').'/';
        $courseExportFile     = $coursePath.'/document/';
        // On init , $step = 1
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :
                // get file of course chat 
                // get list of groups in this course
                // get files of each group chats
                // copy content of each file to document tool
                $chatDate = 'chat.'.date('Y-m-j').'_';
                
                    // TRY DO DETERMINE A FILE NAME THAT DOESN'T ALREADY EXISTS
                    // IN THE DIRECTORY WHERE THE CHAT EXPORT WILL BE STORED
                
                    $i = 1;
                    while ( file_exists($exportFile.$chatDate.$i.'.html') ) $i++;
                
                    $saveIn = $chatDate.$i.'.html';
                
                    // COMPLETE THE ON FLY BUFFER FILE WITH THE LAST LINES DISPLAYED
                    // BEFORE PROCEED TO COMPLETE FILE STORAGE
                
                    buffer( implode('', file($activeChatFile) ).'</body>'."\n\n".'</html>'."\n",
                    $onflySaveFile);
                
                    if (copy($onflySaveFile, $exportFile.$saveIn) )
                    {
                        $chat_filename = '<a href="../document/document.php" target="blank">' . $saveIn . '</a>' ;
                
                        $cmdMsg = "\n"
                                . '<blockquote>'
                                . get_lang('%chat_filename is now in the document tool. (<em>This file is visible</em>)',array('%chat_filename'=>$chat_filename))
                                . '</blockquote>'."\n";
                
                        @unlink($onflySaveFile);
                    }
                
                if ( 1  ) $step = set_upgrade_status($tool, $step+1, $course_code);
                else return $step ;
                
                
            default :
                $step = set_upgrade_status($tool, 0, $course_code);
                return $step;
        }
    }
    return false;
    */
}

function course_description_upgrade_to_19 ($course_code)
{
    global $currentCourseVersion;

    $versionRequiredToProceed = '/^1.8/';
    $tool = 'CLDSC';
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        // On init , $step = 1
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :
                // id becomes int(11)
                $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $currentCourseDbNameGlu . "course_description` 
                              CHANGE `id` `id` int(11) NOT NULL auto_increment";
                
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1, $course_code);
                else return $step ;
                
            case 2 :
                // add category field
                $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $currentCourseDbNameGlu . "course_description` 
                              ADD `category` int(11) NOT NULL DEFAULT '-1'";
                
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1, $course_code);
                else return $step ;
            
            case 3 :
                // rename update to lastEditDate
                $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $currentCourseDbNameGlu . "course_description` 
                              CHANGE `upDate` `lastEditDate` datetime NOT NULL";
                
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1, $course_code);
                else return $step ;

            case 4 :
                // change possible values of visibility fields (show/hide -> visible/invisible)
                // so to do that we: #1 change the possible enum values to have them all
                //                   #2 change fields with show to visible / fields with hide to invisible
                //                   #3 change the possible enum values to keep only the good ones

                // #1
                $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $currentCourseDbNameGlu . "course_description`
                              CHANGE `visibility` `visibility` enum('SHOW','HIDE','VISIBLE','INVISIBLE') NOT NULL default 'VISIBLE'";
                //#2
                $sqlForUpdate[] = "UPDATE `" . $currentCourseDbNameGlu . "course_description`
                                SET `visibility` = IF(`visibility` = 'SHOW' ,'VISIBLE','INVISIBLE')";
                
                //#3
                $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $currentCourseDbNameGlu . "course_description` 
                              CHANGE `visibility` `visibility` enum('VISIBLE','INVISIBLE') NOT NULL default 'VISIBLE'";
                
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, 0, $course_code);
                else return $step ;
                
            default :
                $step = set_upgrade_status($tool, 0, $course_code);
                return $step;
        }
    }
    return false;
}

/**
 * Upgrade foo tool to 1.9
 *
 * explanation of task
 *
 * @param $course_code string
 * @return boolean whether true if succeed
 */

function quiz_upgrade_to_19 ($course_code)
{
    // PRIMARY KEY (`exerciseId`,`questionId`)
    global $currentCourseVersion, $currentcoursePathSys;

    $versionRequiredToProceed = '/^1.8/';
    $tool = 'CLQWZ';
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        // On init , $step = 1
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :
                // qwz_rel_exercise_question - fix key and index
                $sqlForUpdate[] = "ALTER TABLE `". $currentCourseDbNameGlu ."qwz_rel_exercise_question`
                  DROP PRIMARY KEY,
                  ADD PRIMARY KEY(`exerciseId`, `questionId`)";
                   
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1, $course_code);
                else return $step ;
                
                unset($sqlForUpdate);
                
            case 2 :
                // qwz_tracking - rename table
                $sqlForUpdate[] = "ALTER IGNORE TABLE `". $currentCourseDbNameGlu . "track_e_exercices` 
                                RENAME TO `". $currentCourseDbNameGlu ."qwz_tracking`";
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1, $course_code);
                else return $step ;
                
                unset($sqlForUpdate);
                
            case 3 : 
                // qwz_tracking - rename fields
                $sqlForUpdate[] = "ALTER IGNORE TABLE `". $currentCourseDbNameGlu . "qwz_tracking`
                                CHANGE `exe_id`         `id`        int(11) NOT NULL auto_increment,
                                CHANGE `exe_user_id`    `user_id`   int(11) default NULL,
                                CHANGE `exe_date`       `date`      datetime NOT NULL default '0000-00-00 00:00:00',
                                CHANGE `exe_exo_id`     `exo_id`    int(11) NOT NULL default '0',
                                CHANGE `exe_result`     `result`    float NOT NULL default '0',
                                CHANGE `exe_time`       `time`      mediumint(8) NOT NULL default '0',
                                CHANGE `exe_weighting`  `weighting` float NOT NULL default '0'";

                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1, $course_code);
                else return $step ;
                
                unset($sqlForUpdate);

            case 4 : 
                 // qwz_tracking_questions - rename table
                $sqlForUpdate[] = "ALTER TABLE `". $currentCourseDbNameGlu . "track_e_exe_details` 
                                RENAME TO `". $currentCourseDbNameGlu . "qwz_tracking_questions`";

                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1, $course_code);
                else return $step ;
                
                unset($sqlForUpdate);
                
            case 5 : 
                // qwz_tracking_answers - rename table
                $sqlForUpdate[] = "ALTER TABLE `". $currentCourseDbNameGlu . "track_e_exe_answers` 
                                RENAME TO `". $currentCourseDbNameGlu . "qwz_tracking_answers`";

                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, 0, $course_code);
                else return $step ;
                
                unset($sqlForUpdate);
                
            default :
                return $step;
        }
    }

    return false;
}

/**
 * Upgrade tracking tool to 1.8
 */

function tracking_upgrade_to_19($course_code)
{
    /*
     * DO NOT get the old tracking data to put it in this table here
     * as it is a very heavy process it will be done in another script dedicated to that.
     */
    $versionRequiredToProceed = '/^1.8/';
    $tool = 'CLSTATS';
    
    global $currentCourseVersion;
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :
                $sql = "CREATE TABLE IF NOT EXISTS `".$currentCourseDbNameGlu."tracking_event` (
                      `id` int(11) NOT NULL auto_increment,
                      `tool_id` int(11) DEFAULT NULL,
                      `user_id` int(11) DEFAULT NULL,
                      `group_id` int(11) DEFAULT NULL,
                      `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                      `type` varchar(60) NOT NULL DEFAULT '',
                      `data` text NOT NULL DEFAULT '',
                      PRIMARY KEY  (`id`)
                    ) TYPE=MyISAM;";
                
                if ( upgrade_sql_query($sql) ) $step = set_upgrade_status($tool, 0, $course_code);
                else return $step;

            default :
                return $step;
        }
    }

    return false;
}

function calendar_upgrade_to_19($course_code)
{
    $versionRequiredToProceed = '/^1.8/';
    $tool = 'CLCAL';
    
    global $currentCourseVersion;
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :
                $sql = "ALTER IGNORE TABLE `".$currentCourseDbNameGlu."calendar_event` 
                        ADD `location` varchar(50)";
                
                if ( upgrade_sql_query($sql) ) $step = set_upgrade_status($tool, 0, $course_code);
                else return $step;

            default :
                return $step;
        }
    }

    return false;
}

function convert_crl_from_18_to_19( $crl )
{
    if (preg_match(
        '!(crl://'.get_conf('platform_id').'/[^/]+/groups/\d+/)([^/])(.*)!',
        $crl, $matches ) )
    {
        return $matches[1] . rtrim( $matches[2], '_' ) . $matches[3];
    }
    elseif (preg_match(
        '!(crl://'.get_conf('platform_id').'/[^/]+/)([^/])(.*)!',
        $crl, $matches ) )
    {
        return $matches[1] . rtrim( $matches[2], '_' ) . $matches[3];
    }
    else
    {
        return $crl;
    }
}


function linker_upgrade_to_19($course_code)
{
    $versionRequiredToProceed = '/^1.8/';
    $tool = 'CLCAL';
    
    global $currentCourseVersion;
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :
                $sql = "SELECT `crl` FROM `".$currentCourseDbNameGlu."lnk_resource`";
                
                $res = claro_sql_query_fetch_all_rows( $sql );
                $success = ($res !== false);

                foreach( $res as $resource )
                {
                    $sql = "UPDATE `".$currentCourseDbNameGlu."lnk_resource`
                    SET `crl` = '" . claro_sql_escape( convert_crl_from_18_to_19($resource['crl']) ) ."'
                    WHERE `crl` = '" .claro_sql_escape( $resource['crl'] ) ."'";
                    
                    $success = upgrade_sql_query( $sql );
                    
                    if ( ! $success )
                    {
                        break;
                    }
                }
                
                if ( $success ) $step = set_upgrade_status($tool, 0, $course_code);
                else return $step;

            default :
                return $step;
        }
    }

    return false;
}