<?php // $Id$
/**
 * CLAROLINE
 *
 * Script displays topics list of a forum
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

if ( ! $_cid || ! $is_courseAllowed ) claro_disp_auth_form(true);
$currentContext = ( isset($_gid)) ? CLARO_CONTEXT_GROUP : CLARO_CONTEXT_COURSE;

claro_set_display_mode_available(true);

/*-----------------------------------------------------------------
  Stats
 -----------------------------------------------------------------*/

event_access_tool($_tid, $_courseTool['label']);

/*-----------------------------------------------------------------
  Library
 -----------------------------------------------------------------*/

include_once $includePath . '/lib/pager.lib.php';
include_once $includePath . '/lib/forum.lib.php';

/*-----------------------------------------------------------------
  Initialise variables
 -----------------------------------------------------------------*/

$last_visit    = $_user['lastLogin'];
$error         = false;
$forumAllowed  = true;
$error_message = '';

/*=================================================================
  Main Section
 =================================================================*/

// Get params

if ( isset($_REQUEST['forum']) ) $forum_id = (int) $_REQUEST['forum'];
else                             $forum_id = 0;

if ( !empty($_REQUEST['start']) ) $start = (int) $_REQUEST['start'];
else                              $start = 0;

// Get forum settings
$forumSettingList = get_forum_settings($forum_id);

if ( $forumSettingList )
{
    $forum_name         = $forumSettingList['forum_name'];
    $forum_cat_id       = $forumSettingList['cat_id'    ];
    $forum_post_allowed = ( $forumSettingList['forum_access'] != 0 ) ? true : false;

    /*
     * Check if the forum isn't attached to a group,  or -- if it is attached --,
     * check the user is allowed to see the current group forum.
     */

    if (   ! is_null($forumSettingList['idGroup'])
        && ( $forumSettingList['idGroup'] != $_gid || ! $is_groupAllowed) )
    {
        // user are not allowed to see topics of this group
        $forumAllowed       = false;
        $error_message = get_lang('Not allowed');
    }

    if ( $forumAllowed )
    {
        // Get topics list

        $topicLister = new topicLister($forum_id, $start, get_conf('topics_per_page') );
        $topicList   = $topicLister->get_topic_list();
        $pagerUrl = 'viewforum.php?forum=' . $forum_id . '&gidReq='.$_gid;
    }
}
else
{
    // No forum
    $forumAllowed       = false;
    $forum_post_allowed = false;
    $$forum_cat_id      = null;
    $error_message      = get_lang('Not allowed');
}

/*=================================================================
  Display Section
 =================================================================*/

$interbredcrump[] = array ('url' => 'index.php', 'name' => get_lang('Forums'));
$noPHP_SELF       = true;


    // Show Group tools
    // only if in group forum.

    if ( $currentContext == CLARO_CONTEXT_GROUP )
    {
        $groupToolList = forum_group_tool_list($_gid);
    }

include $includePath . '/claro_init_header.inc.php';

if ( ! $forumAllowed )
{
    echo claro_html_message_box($error_message);
}
else
{
    /*-----------------------------------------------------------------
      Display Forum Header
    -----------------------------------------------------------------*/

    $pagetype = 'viewforum';

    $is_allowedToEdit = claro_is_allowed_to_edit()
                        || ( $is_groupTutor && !$is_courseAdmin);
                        // ( $is_groupTutor
                        //  is added to give admin status to tutor
                        // && !$is_courseAdmin)
                        // is added  to let course admin, tutor of current group, use student mode

    echo claro_html_tool_title(get_lang('Forums'),
                          $is_allowedToEdit ? 'help_forum.php' : false);

    if ( isset($groupToolList) )
    {
        echo claro_html_menu_horizontal($groupToolList);
    }
    if ($forum_post_allowed) disp_forum_toolbar($pagetype, $forum_id, $forum_cat_id, 0);

    disp_forum_breadcrumb($pagetype, $forum_id, $forum_name);

    $topicLister->disp_pager_tool_bar($pagerUrl);

    echo '<table class="claroTable emphaseLine" width="100%">' . "\n"

        .' <tr class="superHeader">'                  . "\n"
        .'  <th colspan="6">' . $forum_name . '</th>' . "\n"
        .' </tr>'                                     . "\n"

        .' <tr class="headerX" align="left">'                            . "\n"
        .'  <th>&nbsp;' . get_lang('Topic') . '</th>'                             . "\n"
        .'  <th width="9%"  align="center">' . get_lang('Posts') . '</th>'        . "\n"
        .'  <th width="20%" align="center">&nbsp;' . get_lang('Author') . '</th>' . "\n"
        .'  <th width="8%"  align="center">' . get_lang('Seen') . '</th>'       . "\n"
        .'  <th width="15%" align="center">' . get_lang('Last message') . '</th>'    . "\n"
        .' </tr>' . "\n";

    $topics_start = $start;

    if ( count($topicList) == 0 )
    {
        echo ' <tr>' . "\n"
            .'  <td colspan="5" align="center">' . get_lang('There are no topics for this forum. You can post one') . '</td>'. "\n"
            .' </tr>' . "\n";
    }
    else
    {
        if (isset($_uid)) $date = $claro_notifier->get_notification_date($_uid);

        foreach ( $topicList as $thisTopic )
        {
            echo ' <tr>' . "\n";

            $replys         = $thisTopic['topic_replies'];
            $topic_time     = $thisTopic['topic_time'   ];
            $last_post_time = datetime_to_timestamp( $thisTopic['post_time']);
            $last_post      = datetime_to_timestamp( $thisTopic['post_time'] );

            if ( empty($last_post_time) )
            {
                $last_post_time = datetime_to_timestamp($topic_time);
            }

            if (isset($_uid) && $claro_notifier->is_a_notified_ressource($_cid, $date, $_uid, $_gid, $_tid, $forum_id."-".$thisTopic['topic_id'],FALSE))
            {
                $image = $imgRepositoryWeb.'topic_hot.gif';
                $alt='';
            }
            else
            {
                $image = $imgRepositoryWeb.'topic.gif';
                $alt   = 'new post';
            }

            if($thisTopic['topic_status'] == 1) $image = $locked_image;

            echo '<td>'
                .'<img src="' . $image . '" alt="' . $alt . '" />';

            $topic_title = $thisTopic['topic_title'];
            $topic_link  = 'viewtopic.php?topic='.$thisTopic['topic_id']
                        .  (is_null($forumSettingList['idGroup']) ?
                           '' : '&amp;gidReq ='.$forumSettingList['idGroup']);

            echo '&nbsp;'
                .'<a href="' . $topic_link . '">' . $topic_title . '</a>&nbsp;&nbsp;';

            disp_mini_pager($topic_link, 'start', $replys+1, get_conf('posts_per_page') );

            echo '</td>' . "\n"
                .'<td align="center"><small>' . $replys . '</small></td>' . "\n"
                .'<td align="center"><small>' . $thisTopic['prenom'] . ' ' . $thisTopic['nom'] . '<small></td>' . "\n"
                .'<td align="center"><small>' . $thisTopic['topic_views'] . '<small></td>' . "\n";

            if ( !empty($last_post) )
            {
                echo  '<td align="center">'
                    . '<small>'
                    . claro_disp_localised_date($dateTimeFormatShort, $last_post)
                    . '<small>'
                    . '</td>' . "\n";
            }
            else
            {
                echo '  <td align="center"><small>' . get_lang('No post') . '<small></td>' . "\n";
            }

            echo ' </tr>' . "\n";
        }
    }

    echo '</table>' . "\n";

    $topicLister->disp_pager_tool_bar($pagerUrl);
}

/*-----------------------------------------------------------------
  Display Forum Footer
 -----------------------------------------------------------------*/

include($includePath.'/claro_init_footer.inc.php');

?>
