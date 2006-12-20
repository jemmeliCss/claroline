<?php // $Id$
/**
 * CLAROLINE
 *
 * Script view topic for forum tool
 *
 * @version 1.8 $Revision$
 *
 * @copyright 2001-2006 Universite catholique de Louvain (UCL)
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

$tlabelReq = 'CLFRM';

require '../inc/claro_init_global.inc.php';

if ( ! claro_is_in_a_course() || ! claro_is_course_allowed() ) claro_disp_auth_form(true);

claro_set_display_mode_available(true);

/*-----------------------------------------------------------------
Stats
-----------------------------------------------------------------*/

event_access_tool(claro_get_current_tool_id(), claro_get_current_course_tool_data('label'));

/*-----------------------------------------------------------------
Library
-----------------------------------------------------------------*/

include_once get_path('incRepositorySys') . '/lib/forum.lib.php';
/*-----------------------------------------------------------------
Initialise variables
-----------------------------------------------------------------*/

$last_visit    = claro_get_current_user_data('lastLogin');
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
    $lastPostId         = $topicSettingList['topic_last_post_id'];

    /*
    * Check if the topic isn't attached to a group,  or -- if it is attached --,
    * check the user is allowed to see the current group forum.
    */

    if (   ! is_null($forumSettingList['idGroup'])
    && ! ( ($forumSettingList['idGroup'] == claro_get_current_group_id()) || claro_is_group_allowed()) )
    {
        $allowed = FALSE;
        $error_message = get_lang('Not allowed');
    }
    else
    {
        // get post and use pager
        $postLister = new postLister($topic_id, $start, get_conf('posts_per_page'));
        $postList   = $postLister->get_post_list();
        $totalPosts = $postLister->sqlPager->get_total_item_count();
        $pagerUrl   = $_SERVER['PHP_SELF'] . '?topic=' . $topic_id;

        // EMAIL NOTIFICATION COMMANDS
        // Execute notification preference change if the command was called

        if ( $cmd && claro_is_user_authenticated() )
        {
            switch ($cmd)
            {
                case 'exNotify' :
                    request_topic_notification($topic_id, claro_get_current_user_id());
                    break;

                case 'exdoNotNotify' :
                    cancel_topic_notification($topic_id, claro_get_current_user_id());
                    break;
            }

            $increaseTopicView = false; // the notification change command doesn't
            // have to be considered as a new topic
            // consult
        }

        // Allow user to be have notification for this topic or disable it

        if ( claro_is_user_authenticated() )  //anonymous user do not have this function
        {
            $notification_bloc = '<div style="float: right;">' . "\n"
            . '<small>';

            if ( is_topic_notification_requested($topic_id, claro_get_current_user_id()) )   // display link NOT to be notified
            {
                $notification_bloc .= '<img src="' . get_path('imgRepositoryWeb') . 'email.gif" alt="" />';
                $notification_bloc .= get_lang('Notify by email when replies are posted');
                $notification_bloc .= ' [<a href="' . $_SERVER['PHP_SELF'] ;
                $notification_bloc .= '?forum=' . $forum_id ;
                $notification_bloc .= '&amp;topic=' . $topic_id ;
                $notification_bloc .= '&amp;cmd=exdoNotNotify">';
                $notification_bloc .= get_lang('Disable');
                $notification_bloc .= '</a>]';
            }
            else   //display link to be notified for this topic
            {
                $notification_bloc .= '<a href="' . $_SERVER['PHP_SELF'];
                $notification_bloc .= '?forum=' . $forum_id ;
                $notification_bloc .= '&amp;topic=' . $topic_id ;
                $notification_bloc .= '&amp;cmd=exNotify">';
                $notification_bloc .= '<img src="' . get_path('imgRepositoryWeb') . 'email.gif" alt="" /> ';
                $notification_bloc .= get_lang('Notify by email when replies are posted');
                $notification_bloc .= '</a>';
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
    $error_message = get_lang('Not allowed');
}

if ( $increaseTopicView ) increase_topic_view_count($topic_id); // else noop

/*=================================================================
Display Section
=================================================================*/
// Confirm javascript code

$htmlHeadXtra[] =
"<script type=\"text/javascript\">
           function confirm_delete()
           {
               if (confirm('". clean_str_for_javascript(get_lang('Are you sure to delete')) . " ?'))
               {return true;}
               else
               {return false;}
           }
           </script>";

$interbredcrump[] = array ('url' => 'index.php', 'name' => get_lang('Forums'));
$noPHP_SELF       = true;

include get_path('incRepositorySys') . '/claro_init_header.inc.php';

if ( ! $allowed )
{
    echo claro_html_message_box($error_message);
}
else
{
    /*-----------------------------------------------------------------
    Display Forum Header
    -----------------------------------------------------------------*/

    $pagetype  = 'viewtopic';

    $is_allowedToEdit = claro_is_allowed_to_edit()
    || ( claro_is_group_tutor() && !claro_is_course_manager());

    echo claro_html_tool_title(get_lang('Forums'),
    $is_allowedToEdit ? 'help_forum.php' : false);

    echo disp_forum_breadcrumb($pagetype, $forum_id, $forum_name, 0, $topic_subject);

    if ($forum_post_allowed)
    {
        $toolList = disp_forum_toolbar($pagetype, $forum_id, $forum_cat_id, $topic_id);
        if ( count($postList) > 2 ) // if less than 2 las message is visible
        {
            $lastMsgUrl = 'viewtopic.php?forum=' . $forum_id
            .             '&amp;topic=' . $topic_id
            .             '&amp;start=' . ($totalPosts - get_conf('posts_per_page'))
            .             claro_url_relay_context('&amp;')
            .             '#post' . $lastPostId;
            $toolList[] = claro_html_cmd_link($lastMsgUrl,get_lang('Last message'));
        }
        echo claro_html_menu_horizontal($toolList);
    }

    $postLister->disp_pager_tool_bar($pagerUrl);

    echo '<table class="claroTable" width="100%">' . "\n"
    .    '<tr align="left">' . "\n"
    .    '<th class="superHeader">'
    ;

    // display notification link

    if ( !empty($notification_bloc) )
    {
        echo $notification_bloc;
    }

    echo $topic_subject
    .    '</th>' . "\n"
    .    '</tr>' . "\n"
    ;

    if (claro_is_user_authenticated()) $date = $claro_notifier->get_notification_date(claro_get_current_user_id());

    foreach ( $postList as $thisPost )
    {
        // Check if the forum post is after the last login
        // and choose the image according this state

        $post_time = datetime_to_timestamp($thisPost['post_time']);

        if (claro_is_user_authenticated() && $claro_notifier->is_a_notified_ressource(claro_get_current_course_id(), $date, claro_get_current_user_id(), claro_get_current_group_id(), claro_get_current_tool_id(), $forum_id."-".$topic_id))
        $postImg = 'post_hot.gif';
        else
        $postImg = 'post.gif';

        echo '<tr>' . "\n"
        .    '<th class="headerX">' . "\n"
        .    '<a name="post'. $thisPost['post_id'] .'" >' . "\n"
        .    '<img src="' . get_path('imgRepositoryWeb') . $postImg . '" alt="" />'
        .    get_lang('Author')
        .    ' : <b>' . $thisPost['firstname'] . ' ' . $thisPost['lastname'] . '</b> '
        .    '<small>' . get_lang('Posted') . ' : ' . claro_disp_localised_date(get_locale('dateTimeFormatLong'), $post_time) . '</small>' . "\n"
        .    '  </th>' . "\n"
        .' </tr>'. "\n"

        .' <tr>' . "\n"

        .'  <td>' . "\n"
        .claro_parse_user_text($thisPost['post_text']) . "\n";

        if ( $is_allowedToEdit )
        {
            echo '<p>' . "\n"

            . '<a href="editpost.php?post_id=' . $thisPost['post_id'] . '">'
            . '<img src="' . get_path('imgRepositoryWeb') . 'edit.gif" border="0" alt="' . get_lang('Edit') . '" />'
            . '</a>' . "\n"

            . '<a href="editpost.php?post_id=' . $thisPost['post_id'] . '&amp;delete=delete&amp;submit=submit" '
            . 'onClick="return confirm_delete();" >'
            . '<img src="' . get_path('imgRepositoryWeb') . 'delete.gif" border="0" alt="' . get_lang('Delete') . '" />'
            . '</a>' . "\n"

            . '</p>' . "\n";
        }

        echo '</td>' . "\n"
        .    '</tr>' . "\n"
        ;

    } // end for each

    echo '</table>' . "\n";

    if ($forum_post_allowed)
    {
        $toolBar[] = claro_html_cmd_link( 'reply.php'
                                        . '?topic=' . $topic_id
                                        . '&amp;forum=' . $forum_id
                                        . claro_url_relay_context('&amp;')
                                        , '<img src="' . get_path('imgRepositoryWeb') . 'reply.gif" />'
                                        . ' '
                                        . get_lang('Reply')
                                        );
        echo claro_html_menu_horizontal($toolBar);
    }


    $postLister->disp_pager_tool_bar($pagerUrl);

}

/*-----------------------------------------------------------------
Display Forum Footer
-----------------------------------------------------------------*/

include(get_path('incRepositorySys').'/claro_init_footer.inc.php');

?>