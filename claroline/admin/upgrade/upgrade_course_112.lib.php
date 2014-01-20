<?php // $Id$
if ( count( get_included_files() ) == 1 ) die( '---' );

/**
 * CLAROLINE
 *
 * Function to update course tool from 1.10 to 1.12
 *
 * - READ THE SAMPLE AND COPY PASTE IT
 * - ADD TWICE MORE COMMENT THAT YOU THINK NEEDED
 *
 * This code would be splited by task for the 1.8 Stable but code inside
 * function won't change, so let's go to write it.
 *
 * @version     $Revision$
 * @copyright   (c) 2001-2014, Universite catholique de Louvain (UCL)
 * @license     http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @package     UPGRADE
 * @author      Claro Team <cvs@claroline.net>
 */

/*===========================================================================
 Upgrade to claroline 1.12
 ===========================================================================*/


function lp_upgrade_to_112 ($course_code)
{
    global $currentCourseVersion;

    $versionRequiredToProceed = '/^1.10/';
    
    $tool = 'LP';
    $currentCourseDbNameGlu = claro_get_course_db_name_glued($course_code);

    if ( preg_match($versionRequiredToProceed,$currentCourseVersion) )
    {
        // On init , $step = 1
        switch( $step = get_upgrade_status($tool,$course_code) )
        {
        case 1 :
            // Add the field start date into lp_learnpath table
            $sqlForUpdate[] = "ALTER TABLE   `" . $currentCourseDbNameGlu . "lp_learnpath`  ADD  `startDate` DATETIME NOT NULL AFTER  `comment` ;";

            if ( upgrade_apply_sql($sqlForUpdate) ) $step = set_upgrade_status($tool, $step+1);
            else return $step;

            unset($sqlForUpdate);
            
        case 2 :
            // Add the field start date into lp_learnpath table
            $sqlForUpdate[] = "ALTER TABLE  `" . $currentCourseDbNameGlu . "lp_learnpath`  ADD  `endDate` DATETIME NOT NULL AFTER  `comment` ;";

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