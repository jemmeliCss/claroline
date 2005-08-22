<?php // $Id$
/**
 * CLAROLINE
 *
 * Script for forum tool
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

if ( !isset($_cid) ) claro_disp_select_course();
if ( !isset($is_courseAllowed) || !$is_courseAllowed ) claro_disp_auth_form();

claro_set_display_mode_available(true); // view mode

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

$last_visit = $_user['lastLogin'];
$is_allowedToEdit = $is_courseAdmin || $is_platformAdmin;
$dialogBox = '';

/*=================================================================
  Main Section
 =================================================================*/

/*-----------------------------------------------------------------
  Administration command
 -----------------------------------------------------------------*/

if ( $is_allowedToEdit ) include_once('./admin.php');

/*-----------------------------------------------------------------
  Get forums categories
 -----------------------------------------------------------------*/

$categories       = get_category_list();
$total_categories = count($categories);

$forum_list = get_forum_list();

if ( ! empty($_uid) )
{
    $userGroupList  = get_user_group_list($_uid);
    $tutorGroupList = get_tutor_group_list($_uid);
}
else
{
    $userGroupList = array();
    $tutorGroupList = array();
}


/*=================================================================
  Display Section
 =================================================================*/

// Claroline Header

include $includePath . '/claro_init_header.inc.php';

$pagetitle = $l_indextitle;
$pagetype  = 'index';

$is_allowedToEdit = claro_is_allowed_to_edit() 
                    || ( $is_groupTutor && !$is_courseAdmin);
                    // ( $is_groupTutor 
                    //  is added to give admin status to tutor 
                    // && !$is_courseAdmin)
                    // is added  to let course admin, tutor of current group, use student mode
                     
$is_forumAdmin    = claro_is_allowed_to_edit();

$is_groupPrivate   = $_groupProperties ['private'];

echo claro_disp_tool_title($langForums, 
                      $is_allowedToEdit ? 'help_forum.php' : false);
                      
if ( !empty($dialogBox) ) echo claro_disp_message_box($dialogBox);                    

// Forum toolbar

disp_forum_toolbar($pagetype, 0, 0, 0);

/*-----------------------------------------------------------------
  Display Forum Index Page
------------------------------------------------------------------*/

echo '<table width="100%" class="claroTable emphaseLine">' . "\n";

$colspan = $is_allowedToEdit ? 9 : 4;

$categoryIterator = 0;

foreach ( $categories as $this_category )
{
    ++$categoryIterator;

    // Pass category for sumple user if no forum inside
    if ($this_category['forum_count'] == 0 && ! $is_allowedToEdit) continue;

    echo '<tr class="superHeader" align="left" valign="top">' . "\n"
    
    .    '<th colspan="'.$colspan.'" >';

    if($is_allowedToEdit)
    {
        echo '<div style="float:right">'
        .    '<a href="'.$_SERVER['PHP_SELF'].'?cmd=rqEdCat&amp;catId='.$this_category['cat_id'].'">'
        .    '<img src="'.$imgRepositoryWeb.'edit.gif" alt="'.$langEdit.'" />'
        .    '</a>'
        .    '&nbsp;'
        .    '<a href="'.$_SERVER['PHP_SELF'].'?cmd=exDelCat&amp;catId='.$this_category['cat_id'].'" '
        .    'onClick="return confirm_delete(\''. clean_str_for_javascript($this_category['cat_title']).'\');" >'
        .    '<img src="'.$imgRepositoryWeb.'delete.gif" alt="'.$langDelete.'" />'
        .    '</a>'
        .    '&nbsp;'
        ;

        if ( $categoryIterator > 1)
        echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=exMvUpCat&amp;catId='.$this_category['cat_id'].'">'
        .    '<img src="'.$imgRepositoryWeb.'up.gif" alt="'.$langMoveUp.'" />'
        .    '</a>';
        
        if ( $categoryIterator < $total_categories)
        echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=exMvDownCat&amp;catId='.$this_category['cat_id'].'">'
        .    '<img src="'.$imgRepositoryWeb.'down.gif" alt="'.$langMoveDown.'" />'
        .    '</a>';
        
        echo '</div>'
        ;   
    }
    
    echo htmlspecialchars($this_category['cat_title']);    
    
    echo '</th>' . "\n"
    .    '</tr>' . "\n";
    

    
    if ($this_category['forum_count'] == 0)
    {
        echo '<tr>' . "\n"
        .	 '<td  colspan="' . $colspan . '" align="center">' . $langNoForum . '</td>' . "\n"
        .	 '</tr>' . "\n";
    }
    else
    {
        echo ' <tr class="headerX" align="center">' . "\n"
        .    ' <th align="left">' . $langForum . '</th>' . "\n"
        .    ' <th>' . $l_topics . '</th>' . "\n"
        .    ' <th>' . $l_posts  . '</th>' . "\n"
        .    ' <th>' . $l_lastpost . '</th>' . "\n"
        ;       

        if ($is_allowedToEdit)
        {
            echo '<th>'.$langEdit.'</th>'
            .    '<th>'.$langEmpty.'</th>'
            .    '<th>'.$langDelete.'</th>'
            .    '<th colspan="2">'.$langMove.'</th>'
            ;
        }
        echo '</tr>' . "\n";
    }
    
    $forumIterator = 0;

    foreach ( $forum_list as $this_forum )
    {
        if ( $this_forum['cat_id'] == $this_category['cat_id'] )
        {
            ++ $forumIterator;
            
            $forum_name   = htmlspecialchars($this_forum['forum_name']);
            $forum_desc   = htmlspecialchars($this_forum['forum_desc']);
            $forum_id     = (int) $this_forum['forum_id'    ];
            $group_id     = (int) $this_forum['group_id'    ];
            $total_topics = (int) $this_forum['forum_topics'];
            $total_posts  = (int) $this_forum['forum_posts' ];
            $last_post    = $this_forum['post_time'   ];

            $forum_post_allowed = ($this_forum['forum_access'] != 0) ? true : false;

            echo '<tr align="left" valign="top">' . "\n";

            if ( ! is_null($last_post) && datetime_to_timestamp($last_post) > $last_visit )
            {
                $forum_img = 'forum_hot.gif';
            }
            else
            {
                $forum_img = 'forum.gif';
            }

            if ( $forum_post_allowed)
            {
                $locked_string = '';
            }
            else
            {
                $locked_string = '<img src="'.$imgRepositoryWeb.'locked.gif" alt="'.$langLocked.'" title="'.$langLocked.'" /> <small>('.$langNoPostAllowed.')</small>';
            }

            echo '<td>'                                               . "\n"
            .    '<img src="' . $imgRepositoryWeb . $forum_img . '" alt="" />' . "\n"
            .    '&nbsp;'                                             . "\n"
            ;

            // Visit only my group forum if not admin or tutor.
            // If tutor, see all groups but indicate my groups.
            // Group Category == 1

            if ( $this_category['cat_id'] == 1 )
            {
                if (   in_array($group_id, $userGroupList )
                    || in_array($group_id, $tutorGroupList) 
                    || ! $is_groupPrivate || $is_forumAdmin 
                   )
                {
                    echo '<a href="viewforum.php?gidReq=' . $group_id
                    .    '&amp;forum=' . $forum_id . '">'
                    .    $forum_name
                    .    '</a>' 
                    ;

                    if ( is_array($tutorGroupList) && in_array($group_id, $tutorGroupList) )
                    {
                        echo '&nbsp;<small>(' . $langOneMyGroups . ')</small>';
                    }

                    if ( is_array($userGroupList) && in_array($group_id, $userGroupList) )
                    {
                        echo '&nbsp;<small>(' . $langMyGroup . ')</small>';
                    }


                }
                else
                {
                    echo $forum_name;
                }
            }
            else
            {
                echo '<a href="viewforum.php?forum=' . $forum_id . '">'
                .    $forum_name
                .    '</a> ';
            }

            echo $locked_string;

            echo '<br /><small>' . $forum_desc . '</small>' . "\n"
            .    '</td>' . "\n"

            .    '<td align="center" valign="middle">' . "\n"
            .    '<small>' . $total_topics . '</small>' . "\n"
            .    '</td>' . "\n"

            .    '<td align="center" valign="middle">' . "\n"
            .    '<small>' . $total_posts . '</small>' . "\n"
            .    '</td>' . "\n"
            .    '<td align="center" valign="middle">' . "\n"
            .    '<small>' . (($last_post > 0) ? $last_post : $langNoPost) . '</small>'
            .    '</td>' . "\n"
            ;

            
            if( $is_allowedToEdit)
            {
                echo '<td align="center">'
                .    '<a href="'.$_SERVER['PHP_SELF'].'?cmd=rqEdForum&amp;forumId='.$forum_id.'">'
                .    '<img src="' . $imgRepositoryWeb . 'edit.gif" alt="'.$langEdit.'" />'
                .    '</a>'
                .    '</td>'
                .    '<td align="center">'
                .    '<a href="'.$_SERVER['PHP_SELF'].'?cmd=exEmptyForum&amp;forumId='.$forum_id.'" '
                .    'onClick="return confirm_empty(\''. clean_str_for_javascript($forum_name).'\');" >'
                .    '<img src="' . $imgRepositoryWeb . 'sweep.gif" alt="'.$langEmpty.'" />'
                .    '</a>'
                .    '</td>'
                .    '<td align="center">'
                .    '<a href="'.$_SERVER['PHP_SELF'].'?cmd=exDelForum&amp;forumId='.$forum_id.'" '
                .    'onClick="return confirm_delete(\''. clean_str_for_javascript($forum_name).'\');" >'
                .    '<img src="' . $imgRepositoryWeb . 'delete.gif" alt="'.$langDelete.'" />'
                .    '</a>'
                .    '</td>';
                
                echo '<td align="center">';
                
                if ($forumIterator > 1) 
                {
                    echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=exMvUpForum&amp;forumId='.$forum_id.'">'
                    .    '<img src="' . $imgRepositoryWeb . 'up.gif" alt="'.$langMoveUp.'" />'
                    .    '</a>';
                }
                             
                echo '</td>';
                
                echo '<td align="center">';   
                           
                if ( $forumIterator < $this_category['forum_count'] )
                {
                    echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=exMvDownForum&amp;forumId='.$forum_id.'">'
                    .    '<img src="' . $imgRepositoryWeb . 'down.gif" alt="'.$langMoveDown.'" />'
                    .    '</a>';
                }
                
                echo   '</td>';
            }

            echo '</tr>' . "\n";
        }
    }
}

echo '</table>' . "\n"

// Display Forum Footer

.     '<br />'
.     '<center>'
.     '<small>'
.     'Copyright &copy; 2000 - 2001 <a href="http://www.phpbb.com/" target="_blank">The phpBB Group</a>'
.     '</small>'
.     '</center>'
;

include($includePath . '/claro_init_footer.inc.php');

?>
