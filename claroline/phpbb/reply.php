<?php // $ Id: $
/**
 * CLAROLINE
 *
 * Script view topic for forum tool
 *
 * @version 1.6 $Revision$
 *
 * @copyright 2001-2005 Universite catholique de Louvain (UCL) 
 * @copyright (C) 2001 The phpBB Group
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @author Claro Team <cvs@claroline.net>
 *
 * @package CLFRM
 *
 */

/*=================================================================
  Init Section
 =================================================================*/

$tlabelReq = 'CLFRM___';

include '../inc/claro_init_global.inc.php';

claro_unquote_gpc();

$nameTools = $langForums;

if ( !isset($_cid) ) claro_disp_select_course();
if ( !isset($is_courseAllowed) || ! $is_courseAllowed ) claro_disp_auth_form();

claro_set_display_mode_available(true);

/*-----------------------------------------------------------------
  Stats
 -----------------------------------------------------------------*/

include $includePath . '/lib/events.lib.inc.php';
event_access_tool($_tid, $_courseTool['label']);

/*-----------------------------------------------------------------
  Library
 -----------------------------------------------------------------*/

include $includePath . '/lib/forum.lib.php';
require $includePath . '/lib/pager.lib.php';

// for notification
include $includePath . '/lib/claro_mail.lib.inc.php';

/*-----------------------------------------------------------------
  DB table names
 -----------------------------------------------------------------*/

$tbl_mdb_names = claro_sql_get_main_tbl();
$tbl_cdb_names = claro_sql_get_course_tbl();

$tbl_users            = $tbl_mdb_names['user'];
$tbl_course_user      = $tbl_mdb_names['rel_course_user'];

$tbl_forums           = $tbl_cdb_names['bb_forums'];
$tbl_categories       = $tbl_cdb_names['bb_categories'];
$tbl_posts            = $tbl_cdb_names['bb_posts'];
$tbl_posts_text       = $tbl_cdb_names['bb_posts_text'];
$tbl_topics           = $tbl_cdb_names['bb_topics'];
$tbl_user_notify      = $tbl_cdb_names['bb_rel_topic_userstonotify'];
$tbl_group_properties = $tbl_cdb_names['group_property'];
$tbl_student_group	  = $tbl_cdb_names['group_team'];
$tbl_user_group       = $tbl_cdb_names['group_rel_team_user'];
$tbl_topics           = $tbl_cdb_names['bb_topics'];

$error = FALSE;
$error_message = '';
$allowed = TRUE;
$pagetitle = 'Post Reply';
$pagetype  = 'reply';

/*=================================================================
  Main Section
 =================================================================*/

if ( isset($_REQUEST['forum']) ) $forum_id = (int) $_REQUEST['forum'];
else                             $forum_id = 0;

if ( isset($_REQUEST['topic']) ) $topic_id = (int) $_REQUEST['topic'];
else                             $topic_id = 0;
        
if ( isset($_REQUEST['message']) ) $message = $_REQUEST['message'];
else                               $message = '';

if ( isset($_REQUEST['cancel']) )
{
	header('Location: viewtopic.php?topic=' . $topic_id . '&forum='.$forum_id);
}

$topic_exists = does_exists($topic_id, 'topic');

if ( ! isset($_uid) )
{
    // exclude anonymous user
    $allowed = false;
    $error_message = $langLoginBeforePost1 . '<br />' . "\n"
    . $langLoginBeforePost2 .'<a href=../../index.php>' . $langLoginBeforePost3 . '.</a>';
}
elseif ( $topic_exists )
{

    // Get forum and topics settings
    $topicSettingList = get_topic_settings($topic_id); 
    $forum_id         = $topicSettingList['forum_id'];
    $topic_title      = $topicSettingList['topic_title'];

    $forumSettingList = get_forum_settings($forum_id, $topic_id);
    
    $forum_name    = $forumSettingList['forum_name'  ];
    $forum_access  = $forumSettingList['forum_access'];
    $forum_type    = $forumSettingList['forum_type'  ];
	$forum_groupId = $forumSettingList['idGroup'     ];
    $forum_cat_id  = $forumSettingList['cat_id'      ];

    /**
     * Check if the topic isn't attached to a group,  or -- if it is attached --, 
     * check the user is allowed to see the current group forum.
     */

    if (   ! is_null($forumSettingList['idGroup']) 
        && ( $forumSettingList['idGroup'] != $_gid || ! $is_groupAllowed) )
    {
        // NOTE : $forumSettingList['idGroup'] != $_gid is necessary to prevent any hacking 
        // attempt like rewriting the request without $cidReq. If we are in group 
        // forum and the group of the concerned forum isn't the same as the session 
        // one, something weird is happening, indeed ...
        $allowed = FALSE;
        $error_message = $langNotAllowed ;
    }

    if ( isset($_REQUEST['submit']) )
    {
        if ( trim(strip_tags($message)) != '' )
        {

            if ( $allow_html == 0 || isset($html) ) $message = htmlspecialchars($message);

            $lastName   = $_user['lastName'];
            $firstName  = $_user['firstName'];
            $poster_ip  = $_SERVER['REMOTE_ADDR'];
            $time       = date('Y-m-d H:i');

            create_new_post($topic_id, $forum_id, $_uid, $time, $poster_ip, $lastName, $firstName, $message);
            trig_topic_notification($topic_id); 
        }
        else
        {
            $error = TRUE;
            $error_message = $l_emptymsg;
        }
    }
}
else
{
    // topic doesn't exist
    $error = 1;
    $error_message = $langNotAllowed;
}

/*=================================================================
  Display Section
 =================================================================*/

include $includePath . '/claro_init_header.inc.php';

$pagetitle = $l_topictitle;
$pagetype  = 'viewtopic';

$is_allowedToEdit = claro_is_allowed_to_edit(); 

claro_disp_tool_title($langForums, 
                      $is_allowedToEdit ? 'help_forum.php' : false);

if ( !$allowed )
{
    // not allowed
    claro_disp_message_box($error_message);
}
else
{

	if ( isset($_REQUEST['submit']) && !$error )
	{
	    // DISPLAY SUCCES MESSAGE
	    disp_confirmation_message ($l_stored, $forum_id, $topic_id);
	}
	else
	{
	    if ( $error )
	    {
	        claro_disp_message_box($error_message);
	    }

        // Show Group Documents and Group Space
        // only if in Category 2 = Group Forums Category

        if ( $forum_cat_id == 1 && $forum_id == $myGroupForum )
        {
	        // group space links
            disp_forum_group_toolbar();
        }

        disp_forum_toolbar($pagetype, $forum_id, 0, $topic_id);
        disp_forum_breadcrumb($pagetype, $forum_id, $forum_name, $topic_title);

        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST">' . "\n"
            . '<input type="hidden" name="forum" value="' . $forum_id . '">' . "\n"
            . '<input type="hidden" name="topic" value="' . $topic_id . '">' . "\n";
        
        echo '<table border="0">' . "\n"
            . '<tr valign="top">' . "\n"
            . '<td align="right"><br />' . $l_body . '&nbsp;:</td>'
            . '<td>';

        claro_disp_html_area('message', htmlspecialchars($message));

        echo '</td>'
            . '</tr>'
            . '<tr valign="top"><td>&nbsp;</td>'
            . '<td>'
            . '<input type="submit" name="submit" value="' . $langOk . '">&nbsp;'
            . '<input type="submit" name="cancel" value="' . $langCancel . '">'
            . '</tr>'
            . '</table>'
            . '</form>' ;

        echo '<p align="center"><a href="viewtopic.php?topic=' . $topic_id . '&forum=' . $forum_id . '" target="_blank">' . $l_topicreview . '</a>';

	} // end else if submit
}

// Display Forum Footer

echo  '<br />
<center>
<small>Copyright &copy; 2000 - 2001 <a href="http://www.phpbb.com/" target="_blank">The phpBB Group</a></small>
</center>';

include($includePath.'/claro_init_footer.inc.php');

?>
