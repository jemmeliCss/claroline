<?php // $Id$
/**
 * CLAROLINE
 *
 * Script view topic for forum tool
 *
 * @version 1.7 $Revision$
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

require '../inc/claro_init_global.inc.php';

$nameTools = $langForums;

if ( ! $_cid || ( ! $is_courseAllowed && ! $_uid) ) claro_disp_auth_form(true);

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
/*-----------------------------------------------------------------
  Initialise variables
 -----------------------------------------------------------------*/

$last_visit    = $_user['lastLogin'];
$error         = FALSE;
$allowed       = TRUE;
$error_message = '';

/*=================================================================
  Main Section
 =================================================================*/

// Get params

if ( isset($_REQUEST['topic']) ) $topic_id = (int) $_REQUEST['topic'];
else                             $topic_id = '';

if ( isset($_REQUEST['cmd']) )   $cmd = $_REQUEST['cmd'];
else                             $cmd = '';

if ( isset($_REQUEST['start'] ) ) $start = (int) $_REQUEST['start'];
else                              $start = 0;

$topicSettingList = get_topic_settings($topic_id);

$increaseTopicView = true;
if ($topicSettingList)
{
    $topic_subject    = $topicSettingList['topic_title' ];
    $lock_state       = $topicSettingList['topic_status'];
    $forum_id         = $topicSettingList['forum_id'    ];

    $forumSettingList   = get_forum_settings($forum_id);
    $forum_name         = $forumSettingList['forum_name'];
    $forum_cat_id       = $forumSettingList['cat_id'    ];
    $forum_post_allowed = ( $forumSettingList['forum_access'] != 0 ) ? true : false;
    
    /* 
     * Check if the topic isn't attached to a group,  or -- if it is attached --, 
     * check the user is allowed to see the current group forum.
     */
    
    if (   ! is_null($forumSettingList['idGroup']) 
        && ! ( $forumSettingList['idGroup'] == $_gid || $is_groupAllowed) )
    {   
        $allowed = FALSE;
        $error_message = $langNotAllowed;
	}
    else
    {
        // get post and use pager	
        $postLister = new postLister($topic_id, $start, $posts_per_page);
        $postList   = $postLister->get_post_list();     
        $pagerUrl   = $_SERVER['PHP_SELF']."?topic=".$topic_id;
        
        // EMAIL NOTIFICATION COMMANDS
        // Execute notification preference change if the command was called
        
        if ( $cmd && isset($_uid) )
        {
            switch ($cmd)
            {
                case 'exNotify' :
                    request_topic_notification($topic_id, $_uid);
                    break;
        
                case 'exdoNotNotify' :
                    cancel_topic_notification($topic_id, $_uid);
                    break;
            }
        
            $increaseTopicView = false; // the notification change command doesn't 
                                        // have to be considered as a new topic 
                                        // consult
        }
        
        // Allow user to be have notification for this topic or disable it
         
        if ( isset($_uid) )  //anonymous user do not have this function
        {
            $notification_bloc = '<div style="float: right;">' . "\n"
                                . '<small>';
        
            if ( is_topic_notification_requested($topic_id, $_uid) )   // display link NOT to be notified
            {
                $notification_bloc .= '<img src="' . $imgRepositoryWeb . 'email.gif" alt="" />'
                                    . $l_notify
                                    . ' [<a href="' . $_SERVER['PHP_SELF'] . '?forum=' . $forum_id . '&amp;topic=' . $topic_id . '&amp;cmd=exdoNotNotify">'
                                    .$langDisable
                                    . '</a>]';
            }
            else   //display link to be notified for this topic
            {
                $notification_bloc .= '<a href="' . $_SERVER['PHP_SELF'] 
                                    . '?forum=' . $forum_id . '&amp;topic=' . $topic_id . '&amp;cmd=exNotify">'
                                    . '<img src="' . $imgRepositoryWeb . 'email.gif" alt="" /> '
                                    . $l_notify 
                                    . '</a>';
            }
        
            $notification_bloc .= '</small>' . "\n"
                                . '</div>' . "\n";
        } //end not anonymous user
    }
}
else
{
    // forum or topic doesn't exist
    $allowed = false;
    $error_message = $langNotAllowed;
}

if ( $increaseTopicView ) increase_topic_view_count($topic_id); // else noop

/*=================================================================
  Display Section
 =================================================================*/
 
if ( $forum_cat_id == 1 && ($is_groupMember || $is_groupTutor || $is_courseAdmin ) )
{
    $interbredcrump[]  = array ('url'=>'../group/group.php', 'name'=> $langGroups);
    $interbredcrump[]= array ("url"=>"../group/group_space.php", 'name'=> $_group['name']);
}

include $includePath . '/claro_init_header.inc.php';

if ( ! $allowed )
{
    echo claro_disp_message_box($error_message);
}
else
{
	/*-----------------------------------------------------------------
	  Display Forum Header
	 -----------------------------------------------------------------*/
	
	$pagetitle = $l_topictitle;
	$pagetype  = 'viewtopic';
	
	$is_allowedToEdit = claro_is_allowed_to_edit() 
	                    || ( $is_groupTutor && !$is_courseAdmin);
	
	echo claro_disp_tool_title($langForums, 
	                      $is_allowedToEdit ? 'help_forum.php' : false);
		
    if ($forum_post_allowed)
    {
        disp_forum_toolbar($pagetype, $forum_id, $forum_cat_id, $topic_id);
    }
	
	disp_forum_breadcrumb($pagetype, $forum_id, $forum_name, $topic_subject);
	
	$postLister->disp_pager_tool_bar($pagerUrl);
	
	echo '<table class="claroTable" width="100%">' . "\n"
	.    ' <tr align="left">' . "\n"
	.    '  <th class="superHeader">';
	
	// display notification link
	
	if ( !empty($notification_bloc) )
	{
	    echo $notification_bloc;
	}
	
	echo $topic_subject
	    . '  </th>' . "\n"
	    . ' </tr>' . "\n";
	
	foreach ( $postList as $thisPost )
	{
	    // Check if the forum post is after the last login
	    // and choose the image according this state
	
	    $post_time = datetime_to_timestamp($thisPost['post_time']);
	
	    if($post_time < $last_visit) $postImg = 'post.gif';
	    else                         $postImg = 'post_hot.gif';
	
	    echo ' <tr>' . "\n"
	
	        .'  <th class="headerX">' . "\n"
	        .'<img src="' . $imgRepositoryWeb . $postImg . '" alt="" />'
	        . $l_author . ' : <b>' . $thisPost['firstname'] . ' ' . $thisPost['lastname'] . '</b> '
	        .'<small>' . $l_posted . ' : ' . claro_disp_localised_date($dateTimeFormatLong, $post_time) . '</small>' . "\n"
	        .'  </th>' . "\n"
	
	        .' </tr>'. "\n"
	
	        .' <tr>' . "\n"
	
	        .'  <td>' . "\n"
	        .claro_parse_user_text($thisPost['post_text']) . "\n";
	
	    if ( $is_allowedToEdit )
	    {
	        echo '<p>' . "\n"
	
	            . '<a href="editpost.php?post_id=' . $thisPost['post_id'] . '">'
	            . '<img src="' . $imgRepositoryWeb . 'edit.gif" border="0" alt="' . $langEdit . '" />'
	            . '</a>' . "\n"
	
	            . '<a href="editpost.php?post_id=' . $thisPost['post_id'] . '&amp;delete=delete&amp;submit=submit">'
	            . '<img src="' . $imgRepositoryWeb . 'delete.gif" border="0" alt="' . $langDelete . '" />'
	            . '</a>' . "\n"
	
	            . '</p>' . "\n";
	    }
	
	    echo    '  </td>' . "\n",
	            ' </tr>' . "\n";
	
	} // end for each
	
	echo '</table>' . "\n";
	
	$postLister->disp_pager_tool_bar($pagerUrl);

}

/*-----------------------------------------------------------------
  Display Forum Footer
 -----------------------------------------------------------------*/

include($includePath.'/claro_init_footer.inc.php');

?>
