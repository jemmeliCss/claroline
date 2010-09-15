<?php // $Id: agenda.php 12380 2010-05-18 11:19:27Z abourguignon $
if ( count( get_included_files() ) == 1 ) die( '---' );

/**
 * CLAROLINE
 *
 * Function to update course tool from 1.9 to 1.10
 *
 * - READ THE SAMPLE AND COPY PASTE IT
 * - ADD TWICE MORE COMMENT THAT YOU THINK NEEDED
 *
 * This code would be splited by task for the 1.8 Stable but code inside
 * function won't change, so let's go to write it.
 *
 * @version     1.10 $Revision: 12380 $
 * @copyright (c) 2001-2010, Universite catholique de Louvain (UCL)
 * @license     http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @package     UPGRADE
 * @author      Claro Team <cvs@claroline.net>
 * @author      Antonin Bourguignon <antonin.bourguignon@claroline.net>
 *
 */

/*===========================================================================
 Upgrade to claroline 1.10
 ===========================================================================*/


function announcements_upgrade_to_110 ($course_code)
{
    global $currentCourseVersion;

    $versionRequiredToProceed = '/^1.9/';
    
    $tool = 'ANNOUNCEMENTS';
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        // On init , $step = 1
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :
                
                // Add the attribute sourceCourseId to the course table
                $sqlForUpdate[] = "ALTER TABLE `" . $currentCourseDbNameGlu . "announcement` ADD `publishAt` DATE NULL DEFAULT NULL AFTER `contenu`";
                
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1);
                else return $step;
                
                unset($sqlForUpdate);
            
            case 2 :
                
                // Add the attribute sourceCourseId to the course table
                $sqlForUpdate[] = "ALTER TABLE `" . $currentCourseDbNameGlu . "announcement` ADD `expiresAt` DATE NULL DEFAULT NULL AFTER `publishAt`";
                
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1);
                else return $step;
                
                unset($sqlForUpdate);
            
            default :
                
                $step = set_upgrade_status($tool, 0);
                return $step;
        }
    }
    
    return false;
}

function calendar_upgrade_to_110 ($course_code)
{
    global $currentCourseVersion;

    $versionRequiredToProceed = '/^1.9/';
    
    $tool = 'CALENDAR';
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        // On init , $step = 1
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :
                
                // Add the attribute sourceCourseId to the course table
                $sqlForUpdate[] = "ALTER TABLE `" . $currentCourseDbNameGlu . "calendar_event` ADD `speakers` VARCHAR(150) NULL DEFAULT NULL AFTER `lasting`";
                
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1);
                else return $step;
                
                unset($sqlForUpdate);
                
            case 2 :
                
                // Add the attribute sourceCourseId to the course table
                $sqlForUpdate[] = "ALTER TABLE `" . $currentCourseDbNameGlu . "calendar_event` CHANGE `location` `location` VARCHAR(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL";
                
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1);
                else return $step;
                
                unset($sqlForUpdate);
                
            default :
                
                $step = set_upgrade_status($tool, 0);
                return $step;
            
        }
    }
    
    return false;
}

function exercise_upgrade_to_110 ($course_code)
{
	global $currentCourseVersion;

    $versionRequiredToProceed = '/^1.9/';
    
    $tool = 'CLQWZ';
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);
    
    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        // On init , $step = 1
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
            case 1 :
                
                // Add the attribute sourceCourseId to the course table
                $sqlForUpdate[] = "ALTER TABLE `" . $currentCourseDbNameGlu . "qwz_question` ADD `id_category` INT(11) NULL DEFAULT '0' AFTER `grade`";
                
                if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1);
                else return $step;
                
                unset($sqlForUpdate);               
                
            default :
                
                $step = set_upgrade_status($tool, 0);
                return $step;
            
        }
    }
    
    return false;
}