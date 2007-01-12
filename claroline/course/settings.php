<?php // $Id$
/**
 * CLAROLINE
 *
 * This tool manage properties of an exiting course
 *
 * @version 1.8 $Revision$
 * @copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @author claroline Team <cvs@claroline.net>
 *
 * old version : http://cvs.claroline.net/cgi-bin/viewcvs.cgi/claroline/claroline/course_info/infocours.php
 *
 * @package CLCRS
 *
 */

$gidReset = true;
require '../inc/claro_init_global.inc.php';

$nameTools = get_lang('Course settings');
$noPHP_SELF = true;

if ( ! claro_is_in_a_course() || ! claro_is_user_authenticated()) claro_disp_auth_form(true);

$is_allowedToEdit = claro_is_course_manager();

if ( ! $is_allowedToEdit )
{
    claro_die(get_lang('Not allowed'));
}

//=================================
// Main section
//=================================

include claro_get_conf_repository() . 'course_main.conf.php';
require_once get_path('incRepositorySys') . '/lib/course.lib.inc.php';
require_once get_path('incRepositorySys') . '/lib/user.lib.php';
require_once get_path('incRepositorySys') . '/lib/fileManage.lib.php';
require_once get_path('incRepositorySys') . '/lib/form.lib.php';
require_once get_path('incRepositorySys') . '/lib/claroCourse.class.php';

// initialisation
define('DISP_COURSE_EDIT_FORM',__LINE__);
define('DISP_COURSE_RQ_DELETE',__LINE__);

$dialogBox = '';

$cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : null;
$adminContext = isset($_REQUEST['adminContext']) ? (bool) $_REQUEST['adminContext'] : null;
$current_cid = null;
$display = DISP_COURSE_EDIT_FORM;

// New course object

$course = new ClaroCourse();

// Initialise current course id


// TODO cidToEdit would  die. cidReq be the  the  only  container to enter in a course context
if ( $adminContext && claro_is_platform_admin() )
{
    // from admin
	if ( isset($_REQUEST['cidToEdit']) )
	{
		$current_cid = trim($_REQUEST['cidToEdit']);
	}
	elseif ( isset($_REQUEST['cidReq']) )
	{
		$current_cid = trim($_REQUEST['cidReq']);
	}

    // add param to form
    $course->addHtmlParam('adminContext','1');
    $course->addHtmlParam('cidToEdit',$current_cid);

    // Back url
    $backUrl = get_path('rootAdminWeb') . 'admincourses.php' ;
}
elseif ( claro_is_in_a_course() )
{
    // from my course
    $current_cid = claro_get_current_course_id();
    $backUrl = get_path('clarolineRepositoryWeb') . 'course/index.php?cid=' . htmlspecialchars($current_cid);
}
else
{
	$current_cid = null ;
}

if ( $course->load($current_cid) )
{
	if ( $cmd == 'exEdit' )
	{
	    $course->handleForm();

	    if ( $course->validate() )
	    {
	    	if ( $course->save() )
	    	{
	    		$dialogBox = get_lang('The information have been modified') . '<br />' . "\n"
	    			. '<a href="' . $backUrl . '">' . get_lang('Continue') . '</a>' ;

	    		if ( ! $adminContext )
	    		{
		    		// force reload of the "course session" of the user
		    		$cidReset = true;
					$cidReq = $current_cid;
					include(get_path('incRepositorySys') . '/claro_init_local.inc.php');
				}
	    	}
	    	else
	    	{
	    		$dialogBox = get_lang('Unable to save');
	    	}
	    }
	    else
	    {
	    	$dialogBox = $course->backlog->output();
	    }
	}

	if ( $cmd == 'exDelete' )
	{
		if ( $course->delete() )
		{
			event_default( 'DELETION COURSE' , array ('courseName' => addslashes($course->title), 'uid' => claro_get_current_user_id()));
			if( $adminContext )
			{
				claro_redirect( get_path('rootAdminWeb') . '/admincourses.php');
			}
			else
			{
				claro_redirect(get_path('url') . '/index.php');
			}
		}
		else
		{
			$dialogBox = get_lang('Unable to save');
		}
	}

	if ( $cmd == 'rqDelete' )
	{
		$display = DISP_COURSE_RQ_DELETE;
	}

}
else
{
	// course data load failed
	claro_die(get_lang('Wrong parameters'));
}

//----------------------------
// initialise links array
//----------------------------

$links = array();

// add course tool list edit

$links[] = '<a class="claroCmd" href="' .  get_path('clarolineRepositoryWeb') . 'course/tools.php' . claro_url_relay_context('?') . '">'
.          '<img src="' . get_path('imgRepositoryWeb') . 'edit.gif" alt="" />'
.          get_lang('Edit Tool list')
.          '</a>' ;

// Main group settings
$links[] = '<a class="claroCmd" href="../group/group_properties.php' . claro_url_relay_context('?') . '">'
.          '<img src="' . get_path('imgRepositoryWeb') . 'settings.gif" alt="" />'
.          get_lang("Main Group Settings")
.          '</a>' ;

// add tracking link

if ( get_conf('is_trackingEnabled') )
{
	$links[] = '<a class="claroCmd" href="' . get_path('clarolineRepositoryWeb') . 'tracking/courseLog.php' . claro_url_relay_context('?') . '">'
    .          '<img src="' . get_path('imgRepositoryWeb') . 'statistics.gif" alt="" />'
    .          get_lang('Statistics')
    .          '</a>' ;
}

// add delete course link

if ( get_conf('showLinkToDeleteThisCourse') )
{
	$paramString = $course->getHtmlParamList('GET');

    $links[] = '<a class="claroCmd" href="' . get_path('clarolineRepositoryWeb') . 'course/settings.php?cmd=rqDelete' . ( !empty($paramString) ? '&amp;'.$paramString : '') . '">'
    .          '<img src="' . get_path('imgRepositoryWeb') . 'delete.gif" alt="" />'
    .          get_lang('Delete the whole course website')
    .          '</a>' ;
}

if ( $adminContext && claro_is_platform_admin() )
{
    // switch to admin breadcrumb

	$interbredcrump[]= array ('url' => get_path('rootAdminWeb') , 'name' => get_lang('Administration'));
    unset($_cid);

    $links[] = '<a class="claroCmd" href="' . $backUrl . '">'
    .          get_lang('Back to course list')
    .          '</a>' ;
}

//=================================
// Display section
//=================================

include get_path('incRepositorySys') . '/claro_init_header.inc.php';

echo claro_html_tool_title($nameTools);

if ( ! empty ($dialogBox) ) echo claro_html_message_box($dialogBox);

echo '<p>' . claro_html_menu_horizontal($links) . '</p>' . "\n\n" ;

if( $display == DISP_COURSE_EDIT_FORM )
{
	// Display form
	echo $course->displayForm($backUrl);
}
elseif( $display == DISP_COURSE_RQ_DELETE )
{
	// display delete confirmation request
	echo $course->displayDeleteConfirmation();
}


include get_path('incRepositorySys') . '/claro_init_footer.inc.php' ;

?>
