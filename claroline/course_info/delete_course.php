<?php # $Id$
/**
 * CLAROLINE 
 *
 * @version 1.7 $Revision$
 *
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @package CLCRS
 *
 * @author Claro Team <cvs@claroline.net>
 */
$_tid='deletecourse';


define('DISP_CONFIRM_DELETE', __LINE__);
define('DISP_DELETE_RESULT', __LINE__);
define('DISP_NOT_ALLOWED', __LINE__);

require '../inc/claro_init_global.inc.php';
if ( ! $_cid) claro_disp_select_course();

include($includePath . '/lib/fileManage.lib.php');
include($includePath . '/lib/events.lib.inc.php');
include($includePath . '/lib/admin.lib.inc.php');

// in case of admin access (from admin tool) to the script, we must determine which course we are working with
$addToURL = '';
if ( isset($cidToEdit) && ($is_platformAdmin) )
{
    $current_cid       = $cidToEdit;
    $currentCourseId   = $cidToEdit;
    $cidReq            = $cidToEdit;
    $isAllowedToDelete = true;
    $addToURL          = '&amp;cidToEdit=' . $cidToEdit;
    $addToURL         .='&amp;cfrom=' . $cfrom;
}
else
{
    $current_cid = $_course['sysCode'];
}

//check user right
$isAllowedToDelete = ($is_courseAdmin || $is_platformAdmin);

//find needed info in db

$course_to_delete = claro_get_course_data($current_cid);
$currentCourseCode = $course_to_delete['officialCode'];
$currentCourseName = $course_to_delete['name'];

$nameTools = $langDelCourse;
$interbredcrump[] = array('url' => 'infocours.php?' . $addToURL, 'name' => $langCourseSettings);

if( $isAllowedToDelete )
{
    if ( isset($_REQUEST['delete']) && $_REQUEST['delete'] )
    {
        // DO DELETE
        delete_course($current_cid);
        event_default( 'DELETION COURSE' , array ('courseName' => addslashes($currentCourseName), 'uid' => $_uid));

        $display = DISP_DELETE_RESULT;
    } // end if $delete
    else
    {
        $display = DISP_CONFIRM_DELETE;
    }        // end else if $delete
}            // end if $isAllowedToDelete
else
{
    $display = DISP_NOT_ALLOWED;
}

include($includePath . '/claro_init_header.inc.php');
// display tool title
claro_disp_tool_title($nameTools);

switch ($display)
{
    case DISP_DELETE_RESULT :
        echo '<p>'
        .    $langCourse.' &quot;'.$currentCourseName.'&quot; '
        .    '('.$currentCourseCode.') '
        .    $langHasDel
        .    '</p>'
        .    '<p>'
        .    '<a href="../../index.php">'
        .    $langBackHomeOf.' '. $siteName
        .    '</a>'
        ;
    
        if ( isset($cidToEdit) ) //we can suppose that script is accessed from admin tool in this case
        {
            echo ' | '
            .    '<a href="../admin/index.php">'
            .    $langBackToAdmin . '</a>'
            ;
        }
        echo '</p>';
        break;
    // ASK DELETE CONFIRMATION TO THE USER
    case DISP_CONFIRM_DELETE :
        echo '<p>'
        .    '<font color="#CC0000">'
        .    $langByDel.' &quot;' . $currentCourseName . '&quot; '
        .    '(' . $currentCourseCode.') ?'
        .    '</font>'
        .    '</p>'
        .    '<p>'
        .    '<font color="#CC0000">'
        .    '<a href="' . $_SERVER['PHP_SELF'] . '?delete=yes' . $addToURL . '">'
        .    $langYes
        .    '</a>'
        .    '&nbsp;|&nbsp;'
        .    '<a href="infocours.php?'.$addToURL.'">'
        .    $langNo
        .    '</a>'
        .    '</font>'
        .    '</p>'
        ;
        break;
    case DISP_NOT_ALLOWED :
      echo $langNotAllowed;
       break;
}

include($includePath . '/claro_init_footer.inc.php');
?>