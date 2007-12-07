<?php // $Id$
if ( count( get_included_files() ) == 1 ) die( '---' );
/**
 * CLAROLINE
 *
 * @version 1.8 $Revision$
 *
 * @copyright 2001-2006 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @see http://www.claroline.net/wiki/index.php/CLGRP
 *
 * @package CLGRP
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Christophe Gesch� <moosh@claroline.net>
 * @author Hugues Peeters <hugues.peeters@claroline.net>
 *
 */

include_once dirname(__FILE__) . '/fileManage.lib.php';

/**
 * Remove all user of a group
 *
 * @param mixed $groupIdList indicates wich group(s) will be emptied            *
 *        integer:group_id | array: array of group_id | string 'ALL'            *
 *        default : ALL
 * @param string $course_id course context where the  group(s) can be founded   *
 *        default : null (get id from init)
 * @return true
 * @throws claro_failure errors
 *
 */

function empty_group($groupIdList = 'ALL', $course_id = null)
{
    $groupFilter = false;
    $tbl_c_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));

    if ( ctype_digit(($groupIdList) )) $groupIdList[] = (int) $groupIdList;
    if ( strtoupper($groupIdList) == 'ALL' ) $sql_condition = '';
    elseif ( is_array($groupIdList) )
    {
        foreach ($groupIdList as $thisGroupId )
        {
            if ( ! is_int($thisGroupId) ) return claro_failure::set_failure('GROUP_LIST_ACTION_UNKNOWN');
        }
        $groupFilter = true;
        $sql_condition = implode(" , ", $groupIdList) ;
    }
    else
    {
        return claro_failure::set_failure('GROUP_LIST_ACTION_UNKNOWN');
    }


    $sql = " DELETE "
    .      " FROM `" . $tbl_c_names['group_rel_team_user'] . "`"
    .      ($groupFilter ? " WHERE team IN (" . $sql_condition . ")":"")
    ;
    if (!claro_sql_query($sql)) return claro_failure::get_last_failure();

    $sql = " UPDATE `" . $tbl_c_names['group_team'] . "` SET tutor='0'"
    .      ($groupFilter ? "WHERE id IN (" . $sql_condition . ")":"")
    ;
    if (!claro_sql_query($sql)) return claro_failure::get_last_failure();

    return true;
}

/**
 * function delete_groups($groupIdList = 'ALL')
 * deletes groups and their datas.
 *
 * @param  mixed   $groupIdList - group(s) to delete. It can be a single id
 *                                (int) or a list of id (array). If no id is
 *                                given all the course group are deleted
 *
 * @return integer : number of groups deleted.
 * @throws claro_failure
 */

function delete_groups($groupIdList = 'ALL')
{
    global $_cid,$_tid,$eventNotifier;

    $tbl_c_names = claro_sql_get_course_tbl();

    $tbl_groups      = $tbl_c_names['group_team'         ];
    $tbl_groupsUsers = $tbl_c_names['group_rel_team_user'];

    require_once $GLOBALS['includePath'] . '/../wiki/lib/lib.createwiki.php';
    require_once $GLOBALS['includePath'] . '/lib/forum.lib.php';

    delete_group_wikis( $groupIdList );
    delete_group_forums( $groupIdList );

    /**
     * Check the data and notify eventmanager of the deletion
     */

    if ( strtoupper($groupIdList) == 'ALL' )
    {
        $sql_condition = '';
    }
    elseif ( is_array($groupIdList) )
    {
        foreach ($groupIdList as $thisGroupId )
        {
            if ( ! is_int($thisGroupId) ) return false;
        }

        $sql_condition = 'WHERE id IN ('. implode(' , ', $groupIdList) . ')';
    }
    else
    {
        if ( settype($groupIdList, 'integer') )
        {
            $sql_condition = '  WHERE id = ' . (int)$groupIdList ;

            $eventNotifier->notifyCourseEvent('group_deleted'
            , $_cid
            , $_tid
            , '0'
            , $groupIdList
            , '0');
        }
        else
        {
            // TODO : perhaps a trigger erro is better
            return claro_failure::set_failure('CANT_SET_ID_GROUP_AS_INTEGER ' . __LINE__);
        }
    }

    /*
    * Search the groups data necessary to delete them
    */

    $sql_searchGroup = "SELECT `id` AS `id`,
                               `secretDirectory` AS `directory`
                        FROM `" . $tbl_groups . "`".
    $sql_condition;

    $groupList = claro_sql_query_fetch_all_cols($sql_searchGroup);

    //notify event manager about the deletion for each group

    foreach ($groupList['id'] as $thisGroupId )
    {
        $eventNotifier->notifyCourseEvent('group_deleted'
        , $_cid
        , $_tid
        , '0'
        , $thisGroupId
        , '0');
    }

    if ( count($groupList['id']) > 0 )
    {
        /*
        * Remove users, group(s) and group forum(s) from the course tables
        */

        $sql_deleteGroup        = "DELETE FROM `" . $tbl_groups . "`
                                   WHERE id IN (" . implode(' , ', $groupList['id']) . ")
                                    # ".__FUNCTION__."
                                    # ".__FILE__."
                                    # ".__LINE__;

        $sql_cleanOutGroupUsers = "DELETE FROM `" . $tbl_groupsUsers . "`
                                   WHERE team IN (" . implode(' , ', $groupList['id']) . ")
                                    # ".__FUNCTION__."
                                    # ".__FILE__."
                                    # ".__LINE__;

        // Deleting group record in table
        $deletedGroupNumber = claro_sql_query_affected_rows($sql_deleteGroup);

        // Delete all members of deleted group(s)
        claro_sql_query($sql_cleanOutGroupUsers);

        /**
         * Archive and delete the group files
         */

        // define repository for deleted element

        $groupGarbage = $GLOBALS['garbageRepositorySys'] . '/' . $GLOBALS['currentCourseRepository'] . '/group/';
        if ( ! file_exists($groupGarbage) ) claro_mkdir($groupGarbage, CLARO_FILE_PERMISSIONS, true);

        foreach ( $groupList['directory'] as $thisDirectory )
        {
            if ( file_exists($GLOBALS['coursesRepositorySys'] . $GLOBALS['currentCourseRepository'] . '/group/' . $thisDirectory) )
            {
                rename($GLOBALS['coursesRepositorySys'] . $GLOBALS['currentCourseRepository'] . '/group/' . $thisDirectory,
                $groupGarbage . $thisDirectory);
            }
        }

        return $deletedGroupNumber;

    } // end if $groupList
    else
    {
        return FALSE;
    }
}

/**
 * Alias of delete_groups() called without parameters
 */

function deleteAllGroups()
{
    return delete_groups('ALL');
}

/**
 * Fill in the groups with still unenrolled students.
 * The algorithm takes care to fill first the freest groups
 * with the less enrolled users
 *
 * @param integer $nbGroupPerUser
 * @param string  $course_id course context where the  group(s) can be founded
 *
 * @author Chrisptophe Gesch� <moosh@claroline.net>,
 * @author Hugues Peeters     <hugues.peeters@claroline.net>
 *
 * @return void
 */

function fill_in_groups($nbGroupPerUser, $course_id )
{
    $tbl_m_names = claro_sql_get_main_tbl();
    $tbl_c_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));

    $tbl_CoursUsers       = $tbl_m_names['rel_course_user'    ];
    $tbl_groups           = $tbl_c_names['group_team'         ];
    $tbl_groupsUsers      = $tbl_c_names['group_rel_team_user'];

    // check if nbGroupPerUser is a positive integer else return false
    if( !settype($nbGroupPerUser, 'integer') || $nbGroupPerUser < 0 )
    return FALSE;
    /*
    * Retrieve all the groups where enrollment is still allowed
    * (reverse) ordered by the number of place available
    */

    $sql = "SELECT
               g.id                        AS gid,
               g.maxStudent-count(ug.user) AS seatCount,
               g.maxStudent                AS g_maxStudent
               # g.maxStudent AS g_maxStudent  is not use
               # in code but would be added  for exists in HAVING
            FROM `" . $tbl_groups . "`            AS  g
            LEFT JOIN  `" . $tbl_groupsUsers . "` AS ug
            ON    `g`.`id` = `ug`.`team`
            GROUP BY (`g`.`id`)
            HAVING seatCount > 0 OR g_maxStudent IS NULL
            ORDER BY seatCount DESC";

    $groupAvailSeatList = array();
    $groupList = claro_sql_query_fetch_all($sql);
    foreach ($groupList as $group) $groupAvailSeatList[$group['gid']] = $group['seatCount'];

    /*
    * Retrieve course users (reverse) ordered by the number
    * of group they are already enrolled
    */

    $sql = "SELECT
                cu.user_id                               AS uid,
                (" . $nbGroupPerUser . "-count(ug.team)) AS tokenCount
            FROM `" . $tbl_CoursUsers . "` AS cu
            LEFT JOIN  `" . $tbl_groupsUsers . "` AS ug
            ON    `ug`.`user`      = `cu`.`user_id`
            WHERE `cu`.`code_cours`='" . addslashes($course_id) . "'
            AND   `cu`.`isCourseManager`    = 0 #no teacher
            AND   `cu`.`tutor`     = 0 #no tutor
            GROUP BY (cu.user_id)
            HAVING tokenCount > 0
            ORDER BY tokenCount DESC";

    $userToken = array();
    $userList = claro_sql_query_fetch_all($sql);
    foreach ($userList as $user) $userToken[$user['uid']] = $user['tokenCount'];
    unset($userList,$user);
    /**
     * Retrieve the present state of the users repartion in groups
     */

    $sql = "SELECT user AS uid, team AS gid FROM `" . $tbl_groupsUsers . "`";
    $groupUser = array();
    $memberList = claro_sql_query_fetch_all($sql);
    foreach ($memberList as $member) $groupUser[$member['gid']] [] = $member['uid'];
    unset($memberList,$member);


    /**
     * Compute the most approriate group fill in
     */

    $prepareQuery = array();

    while    (   is_array($groupAvailSeatList) && !empty($groupAvailSeatList)
    && !empty($userToken) && is_array($userToken))
    {

        /*
        * Sort the users to always start with the less enrolled user
        * to reach first a balance between groups
        */

        arsort($userToken);
        reset($userToken);
        $userPutSucceed = false; // default initialisation

        while (   ( $userPutSucceed == false               )
        && ( list($thisUser, ) = each($userToken) ) )
        {
            /*
            * Sort the groups to always start with the freest group
            * to reach first a balance between groups
            */

            arsort($groupAvailSeatList);
            reset($groupAvailSeatList);
            while (   ( $userPutSucceed == false )
            && (list ($thisGroup, ) = each ($groupAvailSeatList) ) )
            {
                if ( ! isset($groupUser[$thisGroup])
                || ! is_array( $groupUser[$thisGroup] )
                || ! in_array( $thisUser, $groupUser[$thisGroup]) )
                {
                    $groupUser[$thisGroup][] = $thisUser;

                    $prepareQuery[] = "(" . $thisUser . ", ".$thisGroup.")";

                    if ( -- $groupAvailSeatList[$thisGroup] <= 0 )
                    unset( $groupAvailSeatList[$thisGroup] );

                    if ( -- $userToken[$thisUser] <= 0)
                    unset( $userToken[$thisUser] );

                    $userPutSucceed = TRUE;
                }
            }
            // if the user cannot be put in any group delete him from the userToken
            if ( $userPutSucceed == false ) unset( $userToken[$thisUser] );
        }
    }


    /*
    * STORE THE 'FILL IN' PROCESS IN THE DATABASE
    */

    if ( is_array($prepareQuery) && count($prepareQuery) > 0)
    {
        $sql = "INSERT INTO `" . $tbl_groupsUsers . "`
                    (`user`, `team`)
                    VALUES " . implode(" , ", $prepareQuery) ;
        claro_sql_query($sql);
    }
    // else : no student without groups

    return true;
}


/**
 * Count student in course.
 * @param string course_id
 * @return integer user qty in the given course
 * @throws claro_failure
 *
 * @author Christophe Gesch� <moosh@claroline.net>
 *
 */
function group_count_students_in_course($course_id)
{
    $tbl_mdb_names = claro_sql_get_main_tbl();

    $sql = "SELECT COUNT(user_id) AS qty
            FROM `" . $tbl_mdb_names['rel_course_user'] . "`
            WHERE  code_cours = '" . addslashes($course_id) . "'
            AND    isCourseManager = 0 AND tutor = 0";

    return claro_sql_query_get_single_value($sql);

}
/**
 * Count users in all groups.
 * @param interger (optional) course_id
 * @return interger user quantity
 * @author Christophe Gesch� <moosh@claroline.net>
 * @todo TODO : rename this function or change it. count include non student users.
 */
function group_count_students_in_groups($course_id=null)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));

    $sql = "SELECT COUNT(user)
            FROM `" . $tbl_cdb_names['group_rel_team_user'] . "`";
    return (int) claro_sql_query_get_single_value($sql);
}

/**
 * Count users in a given group.
 * @param interger (optional) group_id
 * @param interger (optional) course_id
 * @return interger user quantity
 * @author Christophe Gesch� <moosh@claroline.net>
 * @todo TODO : rename this function or change it. count include non student users.
 */
function group_count_students_in_group($group_id,$course_id=null)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));

    $sql = "SELECT COUNT(user)
            FROM `" . $tbl_cdb_names['group_rel_team_user'] . "`
            WHERE `team` = ". (int) $group_id;
    return (int) claro_sql_query_get_single_value($sql);
}

/**
 * Count groups where a user is ennrolled in a given course
 * @param integer $user_id
 * @param integer (optional) course_id
 * @return integer Count of groups where a given user is ennrolled in a given (o current) course
 * @author Christophe Gesch� <moosh@claroline.net>
 *
 */
function group_count_group_of_a_user($user_id, $course_id=null)
{
    $tbl_cdb_names   = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $sql = "SELECT COUNT(`team`)
            FROM `" . $tbl_cdb_names['group_rel_team_user'] . "`
            WHERE user = " . (int) $user_id;

    return claro_sql_query_get_single_value($sql);
}

/**
 * Create a new group
 *
 * @param  string $groupName - name of the group
 * @param  integer $maxMember  - max user allowed for this group
 * @return integer : id of the new group
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 */

function create_group($prefixGroupName, $maxMember)
{
    require_once dirname(__FILE__) . '/forum.lib.php';
    require_once dirname(__FILE__) . '/fileManage.lib.php';

    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_groups    = $tbl_cdb_names['group_team'];

    // Check name of group
    $sql ="SELECT name FROM  `" . $tbl_groups . "` WHERE name LIKE  '" . addslashes($prefixGroupName) . "%'";
    $existingGroupList = claro_sql_query_fetch_all_cols($sql);
    $existingGroupList = $existingGroupList['name'];
    $i=1;
    do
    {
       $groupName = $prefixGroupName . str_pad($i, 4,' ',STR_PAD_LEFT);
       $i++;
       if ($i-2 > count($existingGroupList))  die($groupName . 'infiniteloop');
    }
    while ( in_array($groupName,$existingGroupList));

    /**
     * Create a directory allowing group student to upload documents
     */

    //  Create a Unique ID path preventing other enter

    do
    {
        $groupRepository = substr(uniqid(substr($groupName,0,19) . '_'),0,30);
    }
    while ( check_name_exist(  $GLOBALS['coursesRepositorySys']
    . $GLOBALS['currentCourseRepository']
    . '/group/' . $groupRepository) );

    claro_mkdir($GLOBALS['coursesRepositorySys'] . $GLOBALS['currentCourseRepository'] . '/group/' . $groupRepository, CLARO_FILE_PERMISSIONS);

    /*
    * Insert a new group in the course group table and keep its ID
    */

    $sql = "INSERT INTO `" . $tbl_groups . "`
            SET name = '" . $groupName . "',
               `maxStudent`  = ". (is_null($maxMember) ? 'NULL' : "'" . (int) $maxMember ."'") .",
                secretDirectory = '" . addslashes($groupRepository) . "'";

    $createdGroupId = claro_sql_query_insert_id($sql);

    /*
    * Create a forum for the group in the forum table
    */

    create_forum( $groupName. ' - '. strtolower(get_lang('Forum'))
    , '' // forum description
    , 2  // means forum post allowed,
    , (int) GROUP_FORUMS_CATEGORY
    , $createdGroupId
    );

    require_once $GLOBALS['includePath'] . '/../wiki/lib/lib.createwiki.php';
    create_wiki( $createdGroupId, $groupName. ' - Wiki' );

    return $createdGroupId;
}

/**
 * Return the list of tutor in the current course.
 *
 * @param string $currentCourseId
 * @return array (userId, name, firstname)
 */

function get_course_tutor_list($currentCourseId)
{
    $tbl = claro_sql_get_main_tbl();

    $sql = "SELECT `user`.`user_id`  AS `userId` ,
                    `user`.`nom`     AS `name`,
                    `user`.`prenom`  AS `firstname`
                FROM `" . $tbl['user'] . "` AS `user`,
                     `" . $tbl['rel_course_user'] . "` AS `cours_user`
                WHERE `cours_user`.`user_id`    = `user`.`user_id`
                AND   `cours_user`.`tutor`      = 1
                AND   `cours_user`.`code_cours` = '" . $currentCourseId . "'";

    $resultTutor = claro_sql_query_fetch_all($sql);
    return $resultTutor;
}



/**
 * This dirty function is a blackbox to provide normalised output of tool list for a group
 * like  get_course_tool_list($course_id=NULL) in course_home.
 *
 * It's dirty because data structure is dirty.
 * Tool_list (with clarolabel and tid come from tool tables and  group properties and localinit)
 * @param $course_id
 * @param boolean $active, if set to true, only activated tools of the platform must be returned
 * @author Christophe Gesch� <moosh@claroline.net>
 * @return array
 */


function get_group_tool_list($course_id=NULL,$active = true)
{
    global $_groupProperties, $forumId, $is_courseAdmin, $is_platformAdmin;

    $isAllowedToEdit = $is_courseAdmin || $is_platformAdmin;

    $tbl = claro_sql_get_main_tbl(array('module','course_tool'));

    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl['course_tool'] = $tbl_cdb_names['tool'];

    // This stupid array is an hack to simulate the context
    // managing by module structure
    // It's represent tools aivailable to work in a group context.

    $aivailable_tool_in_group = array('CLFRM','CLCHT','CLDOC','CLWIKI');

    $sql = "
SELECT tl.id                               id,
       tl.script_name                      name,
       tl.visibility                       visibility,
       tl.rank                             rank,
       IFNULL(ct.script_url,tl.script_url) url,
       ct.claro_label                      label,
       ct.icon                             icon,
       m.activation                        activation
FROM      `" . $tbl['course_tool'] . "`       tl
LEFT JOIN `" . $tbl['tool'] . "` `ct`
ON        ct.id = tl.tool_id
LEFT JOIN `" . $tbl['module'] . "` `m`
ON        m.label = ct.claro_label
ORDER BY tl.rank

";

    $tool_list = claro_sql_query_fetch_all($sql);

    $group_tool_list = array();

    foreach($tool_list as $tool)
    {
        $tool['label'] = trim($tool['label'],'_');

        if (in_array($tool['label'],$aivailable_tool_in_group)
        && ( $active !== true || 'activated' == $tool['activation']))
        switch ($tool['label'])
        {
            case 'CLDOC' :
                if($_groupProperties['tools']['CLDOC'] || $isAllowedToEdit)
                {

                    $group_tool_list[] = $tool;
                }
                break;

            case 'CLFRM' :

                if($_groupProperties['tools']['CLFRM'] || $isAllowedToEdit)
                {
                    $tool['url'] = 'viewforum.php?forum=' . $forumId ;
                    $group_tool_list[] = $tool;
                }

                break;

            case 'CLWIKI' :

                if($_groupProperties['tools']['CLWIKI'] || $isAllowedToEdit)
                {
                    $group_tool_list[] = $tool;
                }
                break;

            case 'CLCHT' :

                if($_groupProperties['tools']['CLCHT'] || $isAllowedToEdit)
                {
                    $group_tool_list[] = $tool;
                }
                break;


        }
    }

    return $group_tool_list;
}


?>