<?php // $Id$

/**
 * CLAROLINE
 *
 * Library profile
 *
 * @version 1.8 $Revision$
 *
 * @copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @package RIGHT
 *
 * @author Claro Team <cvs@claroline.net>
 */

require_once 'constants.inc.php';
require_once 'courseProfileToolAction.class.php';

/**
 * Get all names of profile in an array where key are profileId
 * return array assoc profileId => profileName
 */

function claro_get_all_profile_name_list ()
{
    $profileList = null;

    static $cachedProfileList = null ;    

    if ( $cachedProfileList )
    {
        $profileList = $cachedProfileList;
    }
    else
    {
        $tbl_mdb_names = claro_sql_get_main_tbl();
        $tbl_profile = $tbl_mdb_names['right_profile'];

        $sql = "SELECT profile_id, name, label
                FROM `" . $tbl_profile . "`
                ORDER BY profile_id ";

        $result = claro_sql_query_fetch_all($sql);

        foreach ( $result as $profile)
        {
            $profile_id = $profile['profile_id'];
            $profile_name = $profile['name'];
            $profile_label = $profile['label'];
            $profileList[$profile_id]['name'] = $profile_name; 
            $profileList[$profile_id]['label'] = $profile_label; 
        }

        $cachedProfileList = $profileList ; // cache for the next time ...
    }
    
    return $profileList ;
}

/**
 * Get profileId
 */

function claro_get_profile_id ($profileLabel)
{
    $profileList = claro_get_all_profile_name_list();

    foreach ( $profileList as $profileId => $profileInfo)
    {
        if ( $profileInfo['label'] ==  $profileLabel )
        {
            return $profileId;
        }
    }
    return false;
}

/**
 * Get profileName
 * @param integer $profileId profile identifier
 * @return array ['tool_id']['action_name'] value 
 */

function claro_get_profile_name ($profileId)
{
    $profileList = claro_get_all_profile_name_list();

    if ( isset($profileList[$profileId]['name']) )
    {
        return $profileList[$profileId]['name'];
    }
    else
    {
        return false;
    }
}

/**
 * Get profileName
 * @param integer $profileId profile identifier
 * @return array ['tool_id']['action_name'] value 
 */

function claro_get_profile_label ($profileId)
{
    $profileList = claro_get_all_profile_name_list();

    if ( isset($profileList[$profileId]['label']) )
    {
        return $profileList[$profileId]['label'];
    }
    else
    {
        return false;
    }
}

/**
 * Get course/profile right
 *
 * @param integer $profileId profile identifier
 * @param integer $courseId course identifier
 * @return array ['tool_id']['action_name'] value
 */

function claro_get_course_profile_right ($profileId = null, $courseId = null)
{
    $courseProfileRightList = null;

    static $cachedProfileId = null ;
    static $cachedCourseId = null ;
    static $cachedCourseProfileRightList = null ;

    if ( !empty($cachedCourseProfileRightList) &&
         ( $cachedProfileId == $profileId ) && 
         ( $cachedCourseId == $courseId )
       )
    {
        $courseProfileRightList = $cachedCourseProfileRightList;
    }
    
    if ( empty($courseProfileRightList) )
    {
        $profile = new RightProfile();
    
        if ( $profile->load($profileId) )
        {
            $courseProfileToolRight = new RightCourseProfileToolRight();
            $courseProfileToolRight->setCourseId($courseId);
            $courseProfileToolRight->load($profile);

            $courseProfileRightList = $courseProfileToolRight->getToolActionList();
            
            // cache for the next time ...
            $cachedProfileId = $profileId;
            $cachedCourseId = $courseId;
            $cachedCourseProfileRightList = $courseProfileRightList;
        }
        else
        {
            return false;
        }
    }

    return $courseProfileRightList ;
}

/**
 * Is tool action allowed
 *
 * @param string $actionName name of the action
 * @param integer $tid tool identifier 
 * @param integer $profileId profile identifier
 * @param integer $courseId course identifier
 * @return boolean 'true' if it's allowed
 */

function claro_is_allowed_tool_action ($actionName, $tid = null, $profileId = null, $courseId = null)
{
    global $_tid;
    global $_cid;
    global $_profileId;

    // load tool id
    if ( is_null($tid) )
    {
        if ( !empty($_tid) ) $tid = $_tid ;
        else                 return false ;
    }

    // load profile id
    if ( is_null($profileId) )
    {
        if ( !empty($_prtofileId) ) $profileId = $_profileId ;
        else                        return false ;
    }
   
    // load course id
    if ( is_null($courseId) )
    {
        if ( !empty($_cid) ) $courseId = $_cid ;
        else                 return false ;
    }

    // get course profile right    
    $courseProfileRight = claro_get_course_profile_right($profileId,$courseId);

    // return value for tool/action
    if ( isset($courseProfileRight[$tid][$actionName]) )
    {
        return $courseProfileRight[$tid][$actionName];
    }
    else
    {
        return false;
    }
}

/**
 * Is tool read action allowed
 * 
 * @param string $actionName name of the action
 * @param integer $tid tool identifier 
 * @param integer $profileId profile identifier
 * @param integer $courseId course identifier
 * @return boolean 'true' if it's allowed
 */

function claro_is_allowed_tool_read ($tid = null, $profileId = null, $courseId = null)
{
    return claro_is_allowed_tool_action('read',$tid,$profileId,$courseId);
}

/**
 * Is tool edit action allowed
 *
 * @param string $actionName name of the action
 * @param integer $tid tool identifier 
 * @param integer $profileId profile identifier
 * @param integer $courseId course identifier
 * @return boolean 'true' if it's allowed
 */

function claro_is_allowed_tool_edit ($tid = null, $profileId = null, $courseId = null)
{
    return claro_is_allowed_tool_action('edit',$tid,$profileId,$courseId);
}

?>
