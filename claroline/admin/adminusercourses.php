<?php // $Id$
/**
 * Claroline
 *
 * This  tools admin courses subscription of one user
 *
 * @version 1.8 $Revision$
 *
 * @copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @package CLADMIN
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Guillaume Lederer <guim@claroline.net>
 * @author Christophe Gesch� <moosh@claroline.net>
 *
 */

$dialogBox = '';
$cidReset = TRUE;$gidReset = TRUE;$tidReset = TRUE;

require '../inc/claro_init_global.inc.php';
include_once $includePath . '/lib/user.lib.php';
include_once $includePath . '/lib/course_user.lib.php';
include_once $includePath . '/lib/pager.lib.php';
include claro_get_conf_repository() . 'user_profile.conf.php';

// Security check
if ( ! get_init('_uid') ) claro_disp_auth_form();
if ( ! get_init('is_platformAdmin') ) claro_die(get_lang('Not allowed'));

// FILER INPUT
$validCmdList = array('unsubscribe',);
$cmd = (isset($_REQUEST['cmd']) && in_array($_REQUEST['cmd'],$validCmdList)? $_REQUEST['cmd'] : null);

$validRefererList = array('ulist',);
$cfrom = (isset($_REQUEST['cfrom']) && in_array($_REQUEST['cfrom'],$validRefererList) ? $_REQUEST['cfrom'] : null);

$uidToEdit = (int) (isset($_REQUEST['uidToEdit']) ?  $_REQUEST['uidToEdit'] : null);
$courseId = (isset($_REQUEST['courseId'])?$_REQUEST['courseId']:null);
$do = null;

//// FILTER INPUT FOR PAGING/SORTING : $offset, $sort, $dir

$offset = (int) (!isset($_REQUEST['offset'])) ? 0 :  $_REQUEST['offset'];
$pagerSortKey = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'name';
$pagerSortDir = isset($_REQUEST['dir' ]) ? $_REQUEST['dir' ] : SORT_ASC;


//----------------------------------
// ANALYSE COMMAND
//----------------------------------

/**
 * this maner to manage problem would be more discuss.  uidToEdit can neve empty....
 */
$userData = user_get_properties($uidToEdit);
if ((false === $userData) || $uidToEdit != $userData['user_id']) $dialogBox .= get_lang('user code to view is not valid');
if ('unsubscribe' == $cmd)
{
    if (is_null($courseId)) $dialogBox .= get_lang('course code to manage is not valid');
    else                    $do = 'rem_user';
}

//----------------------------------
// EXECUTE COMMAND
//----------------------------------

if ('rem_user' == $do )
{
    if ( user_remove_from_course($uidToEdit,$courseId,true,false) )
    {
        $dialogBox .= get_lang('The user has been successfully unregistered');
    }
    else
    {
        switch ( claro_failure::get_last_failure() )
        {
            case 'cannot_unsubscribe_the_last_course_manager' :

                $dialogBox .= get_lang('You cannot unsubscribe the last course manager of the course');
                break;
            case 'course_manager_cannot_unsubscribe_himself' :

                $dialogBox .= get_lang('Course manager cannot unsubscribe himself');
                break;
            default :
                $dialogBox .= get_lang('Unknow error during unsubscribing');
        }
    }
}

// needed to display the name of the user we are watching


if ('ulist' == $cfrom) $addToUrl = '&amp;cfrom=ulist';
else                   $addToUrl = '';

$sqlUserCourseList = prepare_sql_get_courses_of_a_user($uidToEdit);

$myPager = new claro_sql_pager($sqlUserCourseList, $offset, get_conf('coursePerPage', 20));
$myPager->set_sort_key($pagerSortKey, $pagerSortDir);

$userCourseList = $myPager->get_result_list();
$userCourseGrid = array();

foreach ($userCourseList as $courseKey => $course)
{
    $userCourseGrid[$courseKey]['officialCode']   = $course['officialCode'];
    $userCourseGrid[$courseKey]['name']      = '<a href="'. $clarolineRepositoryWeb . 'course/index.php?cid=' . htmlspecialchars($course['sysCode']) . '">'.$course['name']. '</a><br />' . $course['titular'];


    $userCourseGrid[$courseKey]['profileId'] = claro_get_profile_name($course['profileId']);

    if ( $course['isCourseManager'] )
    {
        $userCourseGrid[$courseKey]['isCourseManager'] = '<img src="' . $imgRepositoryWeb . 'manager.gif" alt="' . get_lang('Course manager') . '" border="0" />';
    }
    else
    {
        $userCourseGrid[$courseKey]['isCourseManager'] = '<img src="' . $imgRepositoryWeb . 'user.gif" alt="' . get_lang('Student') . '" border="0" />';
    }

    $userCourseGrid[$courseKey]['edit_course_user'] = '<a href="adminUserCourseSettings.php?cidToEdit='.$course['sysCode'].'&amp;uidToEdit='.$uidToEdit.'&amp;ccfrom=uclist"><img src="' . $imgRepositoryWeb . 'edit.gif" alt="' . get_lang('Course manager') . '" border="0" title="' . get_lang('User\'s course settings') . '"></a>';

    $userCourseGrid[$courseKey]['delete'] = '<a href="' . $_SERVER['PHP_SELF']
    .                                       '?uidToEdit=' . $uidToEdit
    .                                       '&amp;cmd=unsubscribe'
    .    $addToUrl
    .    '&amp;code=' . $course['sysCode']
    .    '&amp;offset=' . $offset . '"'
    .    ' onClick="return confirmationUnReg(\''.clean_str_for_javascript($userData['firstname'] . ' ' . $userData['lastname']).'\');">' . "\n"
    .    '<img src="' . $imgRepositoryWeb . 'unenroll.gif" border="0" alt="' . get_lang('Delete') . '" />' . "\n"
    .    '</a>' . "\n"
    ;

}

$sortUrlList = $myPager->get_sort_url_list($_SERVER['PHP_SELF'].'?uidToEdit='. $uidToEdit);

$userCourseDataGrid = new claro_datagrid();
$userCourseDataGrid->set_grid($userCourseGrid);

// extended setting for this datagrid
$userCourseDataGrid->set_colTitleList(array (
'officialCode'     => '<a href="' . $sortUrlList['officialCode'] . '">' . get_lang('Course code') . '</a>'
,'name'     => '<a href="' . $sortUrlList['name'] . '">' . get_lang('Course title') . '</a>'
,'profileId'  => '<a href="' . $sortUrlList['profileId'] . '">' . get_lang('User profile') . '</a>'
,'isCourseManager' => '<a href="' . $sortUrlList['isCourseManager'] . '">' . get_lang('Role') . '</a>'
,'edit_course_user' => get_lang('Edit settings') . '</a>'
,'delete'   => get_lang('Unregister user')
));

$userCourseDataGrid->set_caption('<img src="' . $imgRepositoryWeb . 'user.gif" alt="' . get_lang('Student') . '" border="0" >' . get_lang('Student') . ' - <img src="' . $imgRepositoryWeb . 'manager.gif" alt="' . get_lang('Course Manager') . '" border="0">&nbsp;' . get_lang('Course manager'));

if ( 0 == count($userCourseGrid)  )
{
    $userCourseDataGrid->set_noRowMessage( get_lang('No course to display') );
}
else
{
    $userCourseDataGrid->set_colAttributeList(array ( 'officialCode' => array ('align' => 'left')
    , 'name'            => array ('align' => 'left')
    , 'isCourseManager' => array ('align' => 'center')
    , 'edit_course_user' => array ('align' => 'center')
    , 'delete'          => array ('align' => 'center')
    ));
}

//display title
// initialisation of global variables and used libraries
$nameTools = get_lang('User Course list') . ' : ' . $userData['firstname'] . ' ' . $userData['lastname'];
$interbredcrump[]= array ( 'url' => get_conf('rootAdminWeb'), 'name' => get_lang('Administration'));

// javascript confirm pop up declaration
$htmlHeadXtra[] =
"<script>
            function confirmationUnReg (name)
            {
                if (confirm(\"".clean_str_for_javascript(get_lang('Are you sure you want to unregister '))." \"+ name + \"? \"))
                    {return true;}
                else
                    {return false;}
            }
            </script>";

$cmdList[] =  '<a class="claroCmd" href="adminprofile.php?uidToEdit=' . $uidToEdit . '\">' . get_lang('User settings') . '</a>';
$cmdList[] =  '<a class="claroCmd"  href="../auth/courses.php?cmd=rqReg&amp;uidToEdit=' . $uidToEdit . '&amp;category=&amp;fromAdmin=usercourse">' . get_lang('Enrol to a new course') . '</a>';

if ( 'ulist' == $cfrom )  //if we come from user list, we must display go back to list
{
    $cmdList[] = '<a class="claroCmd" href="adminusers.php">' . get_lang('Back to user list') . '</a>';
}

//----------------------------------
// DISPLAY VIEWS
//----------------------------------

include $includePath . '/claro_init_header.inc.php';
echo claro_html_tool_title($nameTools);

// display forms and dialogBox, alphabetic choice,...

if( isset($dialogBox) && !empty($dialogBox) ) echo claro_html_message_box($dialogBox);

echo '<p>'
.    claro_html_menu_horizontal($cmdList)
.    '</p>'
.    $myPager->disp_pager_tool_bar($_SERVER['PHP_SELF'] . '?uidToEdit=' . $uidToEdit)
.    $userCourseDataGrid->render()
.    $myPager->disp_pager_tool_bar($_SERVER['PHP_SELF'] . '?uidToEdit=' . $uidToEdit) ;

include $includePath . '/claro_init_footer.inc.php';

/**
 * prepare sql to get a list of course of a given user
 *
 * @param integer $userId id of you to fetch courses
 * @return string : mysql statement
 */

function prepare_sql_get_courses_of_a_user($userId=null)
{
    if (is_null($userId)) $userId = get_init('_uid');
    $tbl_mdb_names       = claro_sql_get_main_tbl();
    $tbl_course          = $tbl_mdb_names['course'];
    $tbl_rel_course_user = $tbl_mdb_names['rel_course_user' ];


    $sql = "SELECT `C`.`code`              `sysCode`,
                   `C`.`intitule`          `name`,
                   `C`.`fake_code`         `officialCode`,
                   `C`.`directory`         `path`,
                   `C`.`dbName`            `dbName`,
                   `C`.`titulaires`        `titular`,
                   `C`.`email`             `email`,
                   `C`.`enrollment_key`    `enrollmentKey` ,
                   `C`.`languageCourse`    `language`,
                   `C`.`departmentUrl`     `extLinkUrl`,
                   `C`.`departmentUrlName` `extLinkName`,
                   `C`.`visible`            `visible`,
                   `CU`.`profile_id`        `profileId`,
                   `CU`.`isCourseManager`,
                   `CU`.`tutor`
            FROM `" . $tbl_course . "` AS C,
                 `" . $tbl_rel_course_user . "` AS CU
            WHERE CU.`code_cours` = C.`code`
              AND CU.`user_id` = " . (int) $userId;

    return $sql;
}

?>