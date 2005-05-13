<?php   // Id: $
/**
 * CLAROLINE
 *
 * Script for forum tool
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
  Inistialise
 =================================================================*/

$tlabelReq = 'CLFRM___';

include '../inc/claro_init_global.inc.php';

claro_unquote_gpc();

$nameTools = $langForums;

if ( !isset($_cid) ) claro_disp_select_course();
if ( !isset($is_courseAllowed) || !$is_courseAllowed ) claro_disp_auth_form();

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
  DB table names
 -----------------------------------------------------------------*/

$tbl_mdb_names = claro_sql_get_main_tbl();
$tbl_cdb_names = claro_sql_get_course_tbl();

$tbl_forums           = $tbl_cdb_names['bb_forums'];
$tbl_topics           = $tbl_cdb_names['bb_topics'];
$tbl_student_group	  = $tbl_cdb_names['group_team'];
$tbl_posts_text       = $tbl_cdb_names['bb_posts_text'];
$tbl_posts            = $tbl_cdb_names['bb_posts'];

$tbl_users            = $tbl_mdb_names['user'];

// variables

$allowed = TRUE;
$error = FALSE;

$error_message = '';
$pagetitle = 'New Topic';
$pagetype =  'newtopic';

/*=================================================================
  Main Section
 =================================================================*/

if ( isset($_REQUEST['forum']) ) $forum_id = (int) $_REQUEST['forum'];
else                             $forum_id = 0;

if ( isset( $_REQUEST['cancel'] ) )
{
	header('Location: viewforum.php?forum='.$forum);
	exit();
}
 
if ( isset($_REQUEST['subject']) ) $subject = $_REQUEST['subject'];
else                               $subject = '';

if ( isset($_REQUEST['message']) ) $message = $_REQUEST['message'];
else                               $message = '';

$forum_exists = does_exists($forum_id, 'forum');

$is_allowedToEdit = claro_is_allowed_to_edit() 
                    || ( $is_groupTutor && !$is_courseAdmin);
                    // ( $is_groupTutor 
                    //  is added to give admin status to tutor 
                    // && !$is_courseAdmin)
                    // is added  to let course admin, tutor of current group, use student mode
	    
if ( ! isset($_uid) )    // exclude anonymous users
{
    $allowed = false;
    $error_message = $langLoginBeforePost1 . '<br />' . "\n"
       . $langLoginBeforePost2 .'<a href=../../index.php>' . $langLoginBeforePost3 . '.</a>';
} 
elseif ( $forum_exists )
{
	$forumSettingList = get_forum_settings($forum_id);
	
	$forum_name 		= stripslashes($forumSettingList['forum_name']);
	$forum_access 		= $forumSettingList['forum_access'];
	$forum_type 		= $forumSettingList['forum_type'  ];
	$forum_groupId 		= $forumSettingList['idGroup'     ];
    $forum_cat_id              = $forumSettingList['cat_id'      ];
	
	/* 
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
    else
    {
	
		if ( isset($_REQUEST['submit']) )
		{
		    // Either valid user/pass, or valid session. continue with post.. but first:
		    // Check that, if this is a private forum, the current user can post here.
		
		    /*------------------------------------------------------------------------
		                                PREPARE THE DATA
		      ------------------------------------------------------------------------*/
		
		    // SUBJECT
		    $subject = trim($subject);
		
		    // MESSAGE
		    if ( $allow_html == 0 || isset($html) ) $message = htmlspecialchars($message);
		    $message = trim($message);
		
		    // USER
		    $userLastname  = $_user['lastName'];
		    $userFirstname = $_user['firstName'];
		    $poster_ip     = $_SERVER['REMOTE_ADDR'];
		
		    $time = date('Y-m-d H:i');
		    
		    // prevent to go further if the fields are actually empty
		    if ( strip_tags($message) == '' || $subject == '' ) 
			{
				$error_message = $l_emptymsg;
		        $error = TRUE;
			}
		
            if ( !$error ) 
            {
    	        // record new topic
	    	    $topic_id = create_new_topic($subject, $time, $forum_id, $_uid, $userFirstname, $userLastname);
		        if ( $topic_id )
		        {
		            create_new_post($topic_id, $forum_id, $_uid, $time, $poster_ip, $userLastname, $userFirstname, $message);
    		    }
            }
		
		} // end if submit
    }
}
else
{
    // forum doesn't exists
    $allowed = false;
    $error_message = $langNotAllowed;
}

/*=================================================================
  Display Section
 =================================================================*/

include $includePath . '/claro_init_header.inc.php';

// display tool title
claro_disp_tool_title($langForums, $is_allowedToEdit ? 'help_forum.php' : false);

if ( !$allowed )
{
    // not allowed
    claro_disp_message_box($error_message);
}
else
{
    // Display new topic page

	if ( isset($_REQUEST['submit']) && !$error)
	{
	    // Display success message
	    disp_confirmation_message ($l_stored, $forum_id, $topic_id);
	
	} 
	else
	{
	    if ( $error )
	    {
            // display error message
	        claro_disp_message_box($error_message);
	    }

        // Show Group Documents and Group Space
        // only if in Category 2 = Group Forums Category

        if ( $forum_cat_id == 1 && $forum_id == $myGroupForum )
        {
	        // group space links
            disp_forum_group_toolbar($_gid);
        }

        disp_forum_toolbar($pagetype, $forum_id, 0, 0);

        //disp_forum_breadcrumb($pagetype, $forum_id, $forum_name);

        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">' . "\n"
         . '<input type="hidden" name="forum" value="' . $forum_id . '" />' . "\n"

         . '<table border="0">' . "\n"
         . '<tr valign="top">' . "\n"
         . '<td align="right"><label for="subject">' . $l_subject . '</label> : </td>' 
         . '<td><input type="text" name="subject" id="subject" size="50" maxlength="100" value="' . htmlspecialchars($subject) . '" /></td>'
	     . '<tr  valign="top">' . "\n" 
         . '<td align="right"><br />' . $l_body . ' :</td>'; 

		if ( !empty($message) ) $content = htmlspecialchars($message);
	    else                    $content = '';
        
        echo '<td>';
	    
		claro_disp_html_area('message',$content);

        echo '</td>'
            . '</tr>'
            . '<tr  valign="top"><td>&nbsp;</td>'
            . '<td><input type="submit" name="submit" value="' . $langOk . '" />' 
            . '&nbsp;<input type="submit" name="cancel" value="' . $langCancel . '" />' . "\n"
            . '</td></tr>'
            . '</table>'
            .'</form>' . "\n";
	}
} // end allowed

/*-----------------------------------------------------------------
  Display Forum Footer
 -----------------------------------------------------------------*/

echo  '<br />
<center>
<small>Copyright &copy; 2000 - 2001 <a href="http://www.phpbb.com/" target="_blank">The phpBB Group</a></small>
</center>';

include $includePath . '/claro_init_footer.inc.php';

?>
