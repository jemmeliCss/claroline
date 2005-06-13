<?php // $Id$
/**
 * CLAROLINE
 *
 * Library for forum tool
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
 * @since 1.6
 */

/**
 * Gets user data from uid
 * @param  int   user id
 * @return array user data if it succeeds, boolean false otherwise
 */

function get_userdata_from_id($userId)
{
    $tbl_mdb_names = claro_sql_get_main_tbl();
    $tbl_users       = $tbl_mdb_names['user'];

    $sql = "SELECT prenom first_name, 
                   nom    last_name, 
                   email, 
                   user_id
            FROM `" . $tbl_users . "`
            WHERE user_id ='" . (int) $userId . "'";

    $result = claro_sql_query_fetch_all($sql);

    if ( count($result) == 1 ) return $result[0];
    else                       return false;
}

/**
 * Returns the total number of posts in the whole system, a forum, or a topic
 * Also can return the number of users on the system.
 */

function get_total_posts($id, $type)
{
    $tbl_mdb_names = claro_sql_get_main_tbl();
    $tbl_users = $tbl_mdb_names['user'];
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_posts = $tbl_cdb_names['bb_posts'];

    switch ( $type )
    {
        case 'users': $condition = 'poster_id = ' . (int) $id;
            break;
        case 'forum': $condition = 'forum_id = '  . (int) $id ;
            break;
        case 'topic': $condition = 'topic_id = '  . (int) $id ;
            break;
        case 'all'  : $condition = '1'; // forces TRUE in all cases ...
            break;

        // Old, we should never get this.
        default     : $condition = false;
    }

    if ( $condition !== false )
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM `" . $tbl_posts."` 
                WHERE " . $condition;

        return claro_sql_query_get_single_value($sql);
    }
    else
    {
    	return false;
    }
}

/**
 * Check if this is the first post in a topic. Used in editpost.php
 * @param $topic_id integer
 */

function is_first_post($topic_id, $post_id)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_posts     = $tbl_cdb_names['bb_posts'];

    $sql = "SELECT post_id FROM `" . $tbl_posts . "`
            WHERE topic_id = '" . (int) $topic_id . "'
            ORDER BY post_id LIMIT 1";

    $id_found = claro_sql_query_get_single_value($sql);
    if ($id_found == $post_id) return TRUE;
    else                       return FALSE;
}


/**
 * Displays an error message and exits the script. Used in the posting files.
 */

function error_die($msg)
{
    echo "<table border=\"0\" align=\"center\" width=\"100%\">\n"
        ."<tr>\n"
        ."<td>\n"
        ."<blockquote>\n" . $msg."\n</blockquote>\n"
        ."</td>\n"
        ."</tr>\n"
        ."</table>\n";
}

/** 
 * Update summary info in forum table
 */
function sync($id, $type)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forums  = $tbl_cdb_names['bb_forums'];
    $tbl_topics  = $tbl_cdb_names['bb_topics'];
    $tbl_posts   = $tbl_cdb_names['bb_posts'];

    switch ( $type )
    {
        case 'forum':
            $sql = "SELECT MAX(post_id) AS last_post 
                    FROM `" . $tbl_posts . "` 
                    WHERE forum_id = '" .  (int) $id . "'";

            $last_post = claro_sql_query_get_single_value($sql);

            $sql = "SELECT COUNT(post_id) AS total 
                    FROM `" . $tbl_posts . "` 
                    WHERE forum_id = '" . (int) $id . "'";

            $total_posts = claro_sql_query_get_single_value($sql);

            $sql = "SELECT COUNT(topic_id) AS total 
                    FROM `" . $tbl_topics."` 
                    WHERE forum_id = '" . (int) $id . "'";

            $total_topics = claro_sql_query_get_single_value($sql);

            $sql = "UPDATE `" . $tbl_forums."`
                    SET forum_last_post_id = '" . (int) $last_post . "',
                        forum_posts = '" . (int) $total_posts . "',
                        forum_topics = '" . (int) $total_topics . "'
                    WHERE forum_id = '" . (int) $id . "'";

            $result = claro_sql_query($sql);

        break;

    case 'topic':

        $sql = "SELECT MAX(post_id) AS last_post 
                FROM `" . $tbl_posts . "` 
                WHERE topic_id = '" . (int) $id . "'";

        $last_post = claro_sql_query_get_single_value($sql);

        $sql = "SELECT COUNT(post_id) AS total 
                FROM `" . $tbl_posts . "` 
                WHERE topic_id = '" . (int) $id . "'";

        $total_posts = claro_sql_query_get_single_value($sql);

        $sql = "UPDATE `" . $tbl_topics . "`
                SET topic_replies = '" . (int) $total_posts . " #topic_replies should be renamed topic_posts', 
                topic_last_post_id = '" . (int) $last_post . "'
                WHERE topic_id = '" . (int) $id . "'";

        claro_sql_query($sql);

    break;

    case 'all forums':
        $sql = "SELECT forum_id FROM `" . $tbl_forums . "`";
        $forumList = claro_sql_query_fetch_all($sql);

        foreach($forumList as $thisForum)
        {
            $id = $thisForum['forum_id'];
            sync($id, 'forum');
        }
        
    break;

    case 'all topics':
        $sql = "SELECT topic_id FROM `" . $tbl_topics . "`";
        $topicList = claro_sql_query_fetch_all($sql);

        foreach($topicList as $thisTopic)
        {
            $id = $thisTopic['topic_id'];
            sync($id, "topic");
        }
        
    break;

    } // end switch

    return true;
}

/**
 * Convert a SQL date or datetime to a unix time stamp
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param string SQL DATETIME or DATE
 * @return int unix time stamp
 */

function datetime_to_timestamp($dateTime)
{
    $year = $month = $day = $hour = $min = $sec = 0;

    $dateTimeList = explode(' ', $dateTime);

    if ( count($dateTimeList) == 1 ) $dateTimeList[1] = '00:00:00'; // complete the missing time
    list($date, $time) = $dateTimeList;
    
    $dates = explode('-', $date);

    if ( isset($dates[0]) ) $year = $dates[0];
    if ( isset($dates[1]) ) $month = $dates[1];
    if ( isset($dates[2]) ) $day = $dates[2];
    
    $times = explode(':', $time);

    if ( isset($times[0]) ) $hour = $times[0];
    if ( isset($times[1]) ) $min = $times[1];
    if ( isset($times[2]) ) $sec = $times[2];

    return mktime($hour, $min, $sec, $month, $day, $year);
}

/**
 * Get the forum settings of a forum
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param  int $forumId
 * @param  int $topicId (optional)
 * @return array forum settings
 */

function get_forum_settings($forumId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_student_group = $tbl_cdb_names['group_team'];
    $tbl_forums        = $tbl_cdb_names['bb_forums'];
    $tbl_topics        = $tbl_cdb_names['bb_topics'];

    $sql = "SELECT `f`.`forum_id`     `forum_id`,
                   `f`.`forum_name`   `forum_name`,
                   `f`.`forum_access` `forum_access`,
                   `f`.`forum_type`   `forum_type`,
                   `f`.`cat_id`       `cat_id`,
                   `g`.`id`           `idGroup`

            FROM `" . $tbl_forums."` `f`

            # Check possible attached group ...
             LEFT JOIN `" . $tbl_student_group."` `g`
                    ON `f`.`forum_id` = `g`.`forumId`

             WHERE `f`.`forum_id` = '" . (int) $forumId."'" ;

    $result = claro_sql_query_fetch_all($sql);

    if ( count($result) == 1) return $result[0];
    else                      return false;
}

/**
 * Get topic settings of a topic
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param  int $topicId
 * @return array topic settings
 */

function get_topic_settings($topicId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_topics           = $tbl_cdb_names['bb_topics'];

    $sql = "SELECT topic_id, topic_title, topic_status, forum_id , 
                   topic_poster, topic_time, topic_views, 
                   topic_replies, topic_last_post_id, topic_notify, 
                   nom, prenom
            FROM `" . $tbl_topics . "` 
            WHERE topic_id = '" . (int) $topicId . "'";

    $settingList = claro_sql_query_fetch_all($sql);

    if ( count($settingList) == 1) $settingList = $settingList[0];
    else                           return false;

    return $settingList;
}

/**
 * create a new topic
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param string $subject
 * @param string $time
 * @param int $forumId
 * @param int $userId
 * @param string $userFirstname
 * @param string $userLastname
 * @return 
 */

function create_new_topic($subject, $time, $forumId
                         , $userId, $userFirstname, $userLastname)
{
    
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forums  = $tbl_cdb_names['bb_forums'];
    $tbl_topics  = $tbl_cdb_names['bb_topics'];

    $sql = "INSERT INTO `" . $tbl_topics . "` 
            SET topic_title  = '" . addslashes($subject) . "', 
                topic_poster = '" . (int) $userId . "', 
                forum_id     = '" . (int) $forumId . "', 
                topic_time   = '" . addslashes($time) . "', 
                topic_notify = 1,
                nom          = '" . addslashes($userLastname) . "', 
                prenom       = '" . addslashes($userFirstname) . "'";

    $topicId = claro_sql_query_insert_id($sql);

    // UPDATE THE TOPIC STATUS FOR THE CURRENT FORUM

    $sql = "UPDATE `" . $tbl_forums."` 
            SET   forum_topics = forum_topics+1
            WHERE forum_id     = '" . (int) $forumId . "'";

    claro_sql_query($sql);

    return $topicId;
}

/**
 * get the main settings of a post
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param int $postId 
 * @return array containing poster_id, forum_id, topic_id and post_time
 */

function get_post_settings($postId)
{
    $tbl_cdb_names  = claro_sql_get_course_tbl();
    $tbl_posts      = $tbl_cdb_names['bb_posts'     ];
    $tbl_posts_text = $tbl_cdb_names['bb_posts_text'];

    $sql = "SELECT p.forum_id, p.topic_id, p.poster_id,
                   p.nom poster_lastname, p.prenom poster_firstname,
                   p.poster_ip, p.post_time,
                   pt.post_text
            FROM `".$tbl_posts."`      p,
                 `".$tbl_posts_text."` pt
            WHERE p.post_id   = '". (int) $postId."'
              AND pt.post_id  = p.post_id";

    $result = claro_sql_query_fetch_all($sql);

    if ( count($result) == 1) return $result[0];
    else                      return false;
}


/**
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param
 * @return 
 */


function create_new_post($topicId, $forumId, $userId, $time, $posterIp
                        , $userLastname, $userFirstname, $message)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forums           = $tbl_cdb_names['bb_forums'];
    $tbl_topics           = $tbl_cdb_names['bb_topics'];
    $tbl_posts            = $tbl_cdb_names['bb_posts'];
    $tbl_posts_text       = $tbl_cdb_names['bb_posts_text'];
    $tbl_mdb_names = claro_sql_get_main_tbl();
    $tbl_users       = $tbl_mdb_names['user'];


    // CREATE THE POST SETTINGS

    $sql = "INSERT INTO `" . $tbl_posts . "`
            SET topic_id  = '" . (int) $topicId . "', 
                forum_id  = '" . (int) $forumId . "', 
                poster_id = '" . (int) $userId . "', 
                post_time = '" . addslashes($time) . "', 
                poster_ip = '" . $posterIp . "', 
                nom       = '" . addslashes($userLastname) . "', 
                prenom    = '" . addslashes($userFirstname) . "'";

    $postId = claro_sql_query_insert_id($sql);

    if ($postId)
    {
        // RECORD THE POST CONTENT

        $sql = "INSERT INTO `" . $tbl_posts_text . "` 
                SET post_id   = '" . (int) $postId . "', 
                    post_text = '" . addslashes($message) . "'";

        $result = claro_sql_query($sql);

        // UPDATE THE TOPIC STATUS

        $sql = "UPDATE `" . $tbl_topics . "` 
                SET   topic_replies      =  topic_replies+1, # should be transformed into `topic_posts`
                      topic_last_post_id = '" .(int) $postId . "',
                      topic_time         = '" .addslashes($time) . "' 
                WHERE topic_id = '" . (int) $topicId . "'";

        $result = claro_sql_query($sql);

        // UPDATE THE POST STATUS FOR THE CURRENT FORUM

        $sql = "UPDATE `" . $tbl_forums . "` 
                SET   forum_posts        = forum_posts+1, 
                      forum_last_post_id = '" . (int) $postId . "' 
                WHERE forum_id           = '" . (int) $forumId . "'";

        $result = claro_sql_query($sql);

        return $postId;
    }
    else
    {
    	return false;
    }
}


/**
 * 
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param
 * @return 
 */


function update_post($post_id, $topic_id, $message, $subject = '')
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_topics     = $tbl_cdb_names['bb_topics'];
    $tbl_posts_text = $tbl_cdb_names['bb_posts_text'];

    $sql = "UPDATE `" . $tbl_posts_text . "` 
            SET post_text = '" . addslashes($message) . "' 
            WHERE post_id = '" . (int) $post_id . "'";

    $result = claro_sql_query($sql);

    if ( $subject != '' )
    {

        $sql = "UPDATE `" . $tbl_topics . "` 
                SET topic_title  = '" . addslashes($subject) . "'
                WHERE topic_id = '" . (int) $topic_id . "'";

        $result = claro_sql_query($sql);
    }
}

/**
 * 
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param
 * @return 
 */

function delete_post($postId, $topicId, $forumId, $userId)
{
    $tbl_cdb_names  = claro_sql_get_course_tbl();
    $tbl_topics     = $tbl_cdb_names['bb_topics'    ];
    $tbl_posts      = $tbl_cdb_names['bb_posts'     ];
    $tbl_posts_text = $tbl_cdb_names['bb_posts_text'];

    $tbl_mdb_names = claro_sql_get_main_tbl();
    $tbl_users       = $tbl_mdb_names['user'];

    $sql = "DELETE FROM `" . $tbl_posts . "` 
            WHERE post_id = '" . (int) $postId . "'";

    $result = claro_sql_query($sql);

    $sql = "DELETE FROM `" . $tbl_posts_text . "` 
            WHERE post_id = '" . (int) $postId . "'";

    $result = claro_sql_query($sql);


    if( get_total_posts($topicId, 'topic') == 0 ) # warning $db poses 
                                                  # problems, we have to 
                                                  # remove it.
    {
        $sql = "DELETE FROM `" . $tbl_topics . "` 
                WHERE topic_id = '" . (int) $topicId . "'";

        $result = claro_sql_query($sql);
        $topic_removed = true;
    }
    else
    {
        $sql = "UPDATE `" . $tbl_topics . "` 
                SET topic_time = '" . get_last_post($topicId, 'topic') . "' 
                WHERE topic_id = '" . (int) $topicId . "'";

        $result = claro_sql_query($sql);
        $topic_removed = false;
    }

//    if($userId != -1)
//    {
//        $sql = "UPDATE `" . $tbl_users."` 
//                SET user_posts = user_posts - 1 
//                WHERE user_id = '" . (int) $userId . "'";
//
//        $result = claro_sql_query($sql);
//    }

    // don't understand these two lines below
    sync($forumId, 'forum');
    if (!$topic_removed) sync($topicId, 'topic');

}


/**
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param int $userId
 * @param int $topicId
 * @return void
 */

function request_topic_notification($userId, $topicId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_user_notify = $tbl_cdb_names['bb_rel_topic_userstonotify'];

    // check first if user is not regisitered for topic notification yet
    if (! is_topic_notification_requested($userId, $topicId) )
    {   
        $sql = "INSERT INTO `" . $tbl_user_notify . "`
                SET `user_id`  = '" . (int) $userId . "',
                    `topic_id` = '" . (int) $topicId . "'";

        claro_sql_query($sql);
    }
}

/**
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param int $userId
 * @param int $topicId
 * @return void
 */

function cancel_topic_notification($userId, $topicId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_user_notify = $tbl_cdb_names['bb_rel_topic_userstonotify'];


    $sql = "DELETE FROM `" . $tbl_user_notify . "`
            WHERE `user_id`  = '" . (int) $userId . "'
              AND `topic_id` = '" . (int) $topicId . "'";

    claro_sql_query($sql);
}

/**
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param int $userId
 * @param int $topicId
 * @return bool
 */

function is_topic_notification_requested($userId, $topicId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_user_notify = $tbl_cdb_names['bb_rel_topic_userstonotify'];

    $sql = "SELECT COUNT(*) 
            FROM `" . $tbl_user_notify . "`
            WHERE `user_id`  = '" . (int) $userId . "'
              AND `topic_id` = '" . (int) $topicId . "'";

    if (claro_sql_query_get_single_value($sql) > 0) return true;
    else                                            return false;
}


function trig_topic_notification($topicId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_user_notify = $tbl_cdb_names['bb_rel_topic_userstonotify'];
    $tbl_mdb_names = claro_sql_get_main_tbl();
    $tbl_users       = $tbl_mdb_names['user'];

    global $sys_lang;
    global $langDear, $l_notifybody, $l_notifysubj;
    global $rootWeb, $_course;

    $sql = "SELECT u.user_id, u.prenom firstname, u.nom lastname
            FROM `" . $tbl_user_notify . "` AS notif, 
                 `" . $tbl_users . "` AS u
            WHERE notif.topic_id = '" . (int) $topicId . "'
            AND   notif.user_id  = u.user_id";

    $notifyResult = claro_sql_query($sql);
    $subject      = $l_notifysubj;

    $url_topic = $rootWeb . "claroline/phpbb/viewtopic.php?topic=" .  $topicId . "&amp;cidReq=" . $_course['sysCode'];
    $url_forum = $rootWeb . "claroline/phpbb/index.php?cidReq=" . $_course['sysCode'];

    // send mail to registered user for notification

    while ($list = mysql_fetch_array($notifyResult))
    {
       $message = $langDear . " " . $list['firstname']." " . $list['lastname'].",\n\n";
       $message.= sprintf($l_notifybody,$url_topic,$url_forum);

       claro_mail_user($list['user_id'], $message, $subject);
    }
}


/**
 * Display formated message with several 'return to ...' possibility
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param string $message
 * @param int $forumId (optional)
 * @param int $topicId (optional)
 * @return void
 */

function disp_confirmation_message ($message, $forumId = false, $topicId = false)
{
    global $tablewidth;
    global $l_click, $l_here, $l_viewmsg, $l_returntopic, $l_returnindex;

    echo '<table border="0" align="center" width="' . $tablewidth . '">'
       . '<tr>' . "\n"
       . '<td>' . "\n"
       . '<center>' . "\n"
       . '<p>' . $message . '</p>' . "\n"
       ;

        if ($forumId && $topicId)
        {
            echo '<p>'
               . $l_click
               . ' <a href="viewtopic.php?topic=' . $topicId . '&amp;forum=' . $forumId . '">'
               . $l_here
               . '</a> '
               . $l_viewmsg
               . '</p>' . "\n"
               ;
        }
        
        if ($forumId)
        {
            echo "<p>"
                .$l_click
                ." <a href=\"viewforum.php?forum=" . $forumId."\">"
                .$l_here
                ."</a> " 
                .$l_returntopic
                ."</p>\n";
        }

        echo "<p>"
            .$l_click
            ." <a href=\"index.php\">"
            .$l_here
            ."</a> "
            .$l_returnindex
            ."</p>"
        
            ."</center>\n"
            ."</td>\n"
            ."</tr>\n"
        
            ."</table>\n";
}

/**
 * Display a mini pager. At the opposite of the claro_sql_pager, it doesn't 
 * depend of SQL, but you have to know before the total count of item.
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param string $url - url to be used
 * @param string $offsetParam - param to introduce to call the pager offset
 * @param int    $total - total number of items
 * @param int    $step  - step between each offset
 * @parm  int    $pageMax (optionnal) - If the number of page exceeds this param
 *               the remaining pages are replaced by a '...' except the last one.
 * @return void 
 */


function disp_mini_pager($url, $offsetParam, $total, $step, $pageMax = 3)
{
    $pageList  = array();
    $pageNum   = 1;
    $skip      = false;

    if ( $total < $step      ) return; // no need to go further
    if ( ! strpos($url, '?') ) $glue = '?';
    else                       $glue = '&amp;';

    for($offset = 0; $offset < $total; $offset += $step)
    {
        $isLastPage = (bool) ( ($offset + $step) >= $total);

        if ($pageNum < $pageMax || $isLastPage)
        {
        	$pageList[] = '<a href="' . $url . $glue . $offsetParam . '=' . $offset . '">'
                        . $pageNum
                        . '</a>'
                        ;
        }
        elseif (! $skip)
        {
        	$pageList[] = '...'; // actually first time one have to skip
            $skip       = true;
        }
           
        $pageNum++;
    }

    if (count($pageList) > 0)
    {
        echo '<small>(' . implode(', ', $pageList) . ')</small>';	
    }
}

/**
 * class building a list of all topic of a specific forum, with pager option
 * The class is actually based on the claro_sql_pager class
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @see    claro_sql_pager class
 */

class topicLister
{
    var $sqlPager;

    /**
     * class constructor
     *
     * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
     * @param int $forumId     id of the current forum
     * @param int $start       post where to start
     * @param int $postPerPage number of post to display per page
     */

    function topicLister($forumId, $start = 1, $topicPerPage)
    {
        $tbl_cdb_names = claro_sql_get_course_tbl();
        $tbl_topics    = $tbl_cdb_names['bb_topics'];
        $tbl_posts     = $tbl_cdb_names['bb_posts'];

        // Get topics list

        $sql = "SELECT    t.*, p.post_time
                FROM      `" . $tbl_topics . "` t
                LEFT JOIN `" . $tbl_posts . "` p 
                       ON t.topic_last_post_id = p.post_id
                WHERE     t.forum_id = '" . (int) $forumId . "'
                ORDER BY  topic_time DESC";

        require_once dirname(__FILE__) . '/pager.lib.php';

        $this->sqlPager = new claro_sql_pager($sql, $start, $topicPerPage);
        $this->sqlPager->set_pager_call_param_name('start');
    }

    /**
     * return all the topic list of the current forum
     *
     * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
     * @return array post list
     */

    function get_topic_list()
    {
        return $this->sqlPager->get_result_list();
    }

    /**
     * display a pager tool bar
     *
     * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
     * @param string $url page where to point
     * @return void
     */

    function disp_pager_tool_bar($pagerUrl)
    {
        $this->sqlPager->disp_pager_tool_bar($pagerUrl);
    }
}


/**
 * Class building a list of all the post of specific topic, with pager options
 * The class is actually based on the claro_sql_pager class
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @see    claro_sql_pager class
 */

class postLister
{
    var $sqlPager;

    /**
     * class constructor
     *
     * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
     * @param int $topicId     id of the current topic
     * @param int $start       post where to start
     * @param int $postPerPage number of post to display per page
     */

    function postLister($topicId, $start = 1, $postsPerPage)
    {
        $tbl_cdb_names = claro_sql_get_course_tbl();
        $tbl_posts            = $tbl_cdb_names['bb_posts'];
        $tbl_posts_text       = $tbl_cdb_names['bb_posts_text'];

        $sql = "SELECT  p.`post_id`,   p.`topic_id`,  p.`forum_id`,
                        p.`poster_id`, p.`post_time`, p.`poster_ip`,
                        p.`nom` lastname, p.`prenom` firstname,
                        pt.`post_text` 

               FROM     `" . $tbl_posts . "`      p, 
                        `" . $tbl_posts_text . "` pt 

               WHERE    topic_id  = '" . (int) $topicId . "' 
                 AND    p.post_id = pt.`post_id`

               ORDER BY post_id";

        require_once dirname(__FILE__) . '/pager.lib.php';

        $this->sqlPager = new claro_sql_pager($sql, $start, $postsPerPage);

        $this->sqlPager->set_pager_call_param_name('start');
    }

    /**
     * return all the post list of the current topic
     *
     * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
     * @return array post list
     */

    function get_post_list()
    {
        return $this->sqlPager->get_result_list();
    }

    /**
     * display a pager tool bar
     *
     * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
     * @param string $url page where to point
     * @return void
     */

    function disp_pager_tool_bar($pagerUrl)
    {
        $this->sqlPager->disp_pager_tool_bar($pagerUrl);
    }
}

function disp_forum_toolbar($pagetype, $forum_id, $cat_id = 0, $topic_id = 0)
{

    global $_gid,
           $forum_name, 
           $imgRepositoryWeb, 
           $langAdm, $langBackTo, $langNewTopic, $langReply;

    if ( claro_is_allowed_to_edit() )
    {
        if ( $cat_id > 0 ) $toAdd = '?forumgo=yes&amp;cat_id=' . $cat_id;
        else               $toAdd = '';

        $toolBar[] = '<a class="claroCmd" href="../forum_admin/forum_admin.php' . $toAdd . '">'
                  . '<img src="' . $imgRepositoryWeb . 'settings.gif"> '
                  . $langAdm . '</a>' . "\n"
                  ;
    }

    switch ( $pagetype )
    {
    	// 'index' is covered by default
    
    	case 'newtopic':
    
    		$toolBar [] = $langBackTo
    					. '<a class="claroCmd" href="viewforum.php?forum=' . $forum_id . '&amp;gidReq=' . $_gid . '">'
    					. $forum_name
    					. '</a>'."\n";
    		break;
    
    	case 'viewforum':
    
    		$toolBar [] =	'<a class="claroCmd" href="newtopic.php?forum=' . $forum_id . '&amp;gidReq=' . $_gid . '">'
                           .'<img src="' . $imgRepositoryWeb . 'topic.gif"> '
                           . $langNewTopic
                           .'</a>';
    
    		break;
    
    	case 'viewtopic':
    
    		$toolBar [] =	'<a class="claroCmd" href="newtopic.php?forum=' . $forum_id . '&amp;gidReq=' . $_gid . '">'
                           .'<img src="' . $imgRepositoryWeb . 'topic.gif"> '
                           . $langNewTopic
                           . '</a>';  
    
    		$toolBar [] =	'<a class="claroCmd" href="reply.php?topic=' . $topic_id . '&amp;forum=' . $forum_id . '&amp;gidReq='.$_gid.'">'
                            ."<img src=\"" . $imgRepositoryWeb."reply.gif\"> "
    					    . $langReply
    						. '</a>' ."\n";
    
    		break;
    
    	// 'Register' is covered by default
    
    	default:
    		break;
    }

    if ( isset($toolBar) && is_array($toolBar)) 
    {
        $toolBar = implode(' | ', $toolBar);
        echo '<p>' . $toolBar . '<p>' . "\n";
    }
    return TRUE;
}

function disp_forum_breadcrumb($pagetype, $forum_id, $forum_name, $topic_name='')
{

    global $sitename, $l_separator, $_gid, $topic_subject;

    switch ($pagetype) 
    {
    	case 'index' :
            // noop ...
    		break;

    	case 'viewforum' :
    	case 'viewtopic' :
        case 'editpost' :
        case 'reply' :

	    	echo '<small>' . "\n";
    
	    	echo '<a href="index.php">' . $sitename . ' Forum Index</a> '
    			. $l_separator
	    		. ' <a href="viewforum.php?forum=' . $forum_id . '&amp;gidReq=' . $_gid . '">'
		    	. htmlspecialchars($forum_name)
			    . '</a>' 
                ;

    		if ( $pagetype != "viewforum" ) echo ' ' . $l_separator . ' ';

		    echo htmlspecialchars($topic_name);

        	echo '</small>' . "\n";

		break;
    }

}

function disp_forum_group_toolbar($gid)
{
    global $langGroupSpaceLink, $langGroupDocumentsLink;

	// group space links

	echo  '<p>'
        . '<a href="../group/group_space.php?gidReq=' .(int) $gid . '">'
        . $langGroupSpaceLink
        . '</a>' 
        . '&nbsp;&nbsp' 
		. '<a href="../document/document.php?gidReq='
        . (int) $gid . '">'
        . $langGroupDocumentsLink
        . '</a>'
        . '</p>' . "\n";

}

/**
 * delete all post and topics from a sepcific forum
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param int - forum id
 * @return boolean - true if it succeed, flase otherwise
 */

function delete_all_post_in_forum($forumId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forums                  = $tbl_cdb_names['bb_forums'                 ];
    $tbl_topics                  = $tbl_cdb_names['bb_topics'                 ];
    $tbl_posts                   = $tbl_cdb_names['bb_posts'                  ];
    $tbl_posts_text              = $tbl_cdb_names['bb_posts_text'             ];
    $tbl_rel_topic_userstonotify = $tbl_cdb_names['bb_rel_topic_userstonotify'];

    $sql = "SELECT post_id FROM `".$tbl_posts."`
            WHERE forum_id = '" . (int) $forumId . "'";

    $postIdList = claro_sql_query_fetch_all_cols($sql);
    $postIdList = $postIdList['post_id'];

    $sql = "SELECT topic_id FROM `".$tbl_topics."` 
            WHERE forum_id = ".(int) $forumId;

    $topicIdList = claro_sql_query_fetch_all_cols($sql);
    $topicIdList = $topicIdList['topic_id'];

    if ( count($topicIdList) > 0)
    {
        $sql = "DELETE FROM `".$tbl_rel_topic_userstonotify."`
                WHERE  topic_id IN (".implode(', ', $topicIdList).")";
        if (claro_sql_query($sql) == false) return false;
    }

    $sql = "DELETE FROM `".$tbl_topics."` 
            WHERE forum_id = ".(int) $forumId;

    if (claro_sql_query($sql) == false) return false;

    $sql = "DELETE FROM `" . $tbl_posts . "` 
            WHERE forum_id = " . (int) $forumId . "";

    if (claro_sql_query($sql) == false) return false;

    if ( count($postIdList) > 0 )
    {
        $sql = "DELETE FROM `".$tbl_posts_text."`
                WHERE post_id IN (".implode(', ', $postIdList).")";

        if (claro_sql_query($sql) == false) return false;
    }

    $sql = "UPDATE `".$tbl_forums."` 
            SET  forum_topics = 0,
                 forum_posts  = 0
            WHERE forum_id = ".(int)$forumId;

    if (claro_sql_query($sql) == false) return false;

    return true;
}

function update_category_title( $catId, $catTitle )
{
    $tbl_cdb_names        = claro_sql_get_course_tbl();
    $tbl_forum_categories = $tbl_cdb_names['bb_categories'];

    if ( !empty($catTitle) )
    {
        $sql = "UPDATE `".$tbl_forum_categories."`
            SET   cat_title = '". addslashes($catTitle) ."'
            WHERE cat_id    = '".(int)$catId."'";
        
        if (claro_sql_query($sql) != false) return true;
    }

    return false;
}

function update_forum_settings($forum_id, $forum_name, $forum_desc, $forum_type, $cat_id)
{
    $tbl_cdb_names        = claro_sql_get_course_tbl();
    $tbl_forum_forums     = $tbl_cdb_names['bb_forums'];
    $sql = 'UPDATE `'.$tbl_forum_forums.'`
            SET `forum_name`     = "'. addslashes($forum_name) .'",
                `forum_desc`     = "'. addslashes($forum_desc) .'",
                `forum_access`   = 2,
                `forum_moderator`= 1,
                `cat_id`         = "' . (int)$cat_id     . '",
                `forum_type`     = "' . $forum_type .'"
            WHERE `forum_id` = ' . (int)$forum_id;

    if (claro_sql_query($sql) != false) return true;
    else                                return false;
}

function create_category($cat_title)
{
    $tbl_cdb_names        = claro_sql_get_course_tbl();
    $tbl_forum_categories = $tbl_cdb_names['bb_categories'];

    // Find order in the category we must give to the newly created forum
    $sql = 'SELECT MAX(`cat_order`) FROM `'.$tbl_forum_categories.'`';
    $result = claro_sql_query($sql);

    list($orderMax) = mysql_fetch_row($result);
    $order = $orderMax + 1;

    $sql = 'INSERT INTO `'.$tbl_forum_categories.'`
            SET `cat_title` = "'. addslashes($cat_title) .'",
                `cat_order` = "'. (int) $order.'"';

    if ( claro_sql_query($sql) != false) return true;
    else                                 return false;
}

function delete_category($cat_id)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forum_categories = $tbl_cdb_names['bb_categories'];
    $tbl_forum_forums     = $tbl_cdb_names['bb_forums'    ];
    $tbl_forum_topics     = $tbl_cdb_names['bb_topics'    ];

    $sql = 'SELECT `forum_id` 
            FROM `'.$tbl_forum_forums.'` 
            WHERE `cat_id` = "'.$cat_id.'"';
    $result = claro_sql_query($sql);

    while( list($forum_id) = mysql_fetch_row($result) )
    {
        $sql = 'DELETE FROM `'.$tbl_forum_topics.'` 
                WHERE `forum_id` = "'.$forum_id.'"';

        claro_sql_query($sql);
    }

    $sql = 'DELETE FROM `'.$tbl_forum_forums.'` 
            WHERE `cat_id` = "'. (int) $cat_id.'"';

    claro_sql_query($sql);

    $sql = 'DELETE FROM `'.$tbl_forum_categories.'` 
            WHERE `cat_id` = "'.(int) $cat_id.'"';
    claro_sql_query($sql);
}

function delete_forum($forum_id)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forum_categories = $tbl_cdb_names['bb_categories'];
    $tbl_forum_forums     = $tbl_cdb_names['bb_forums'    ];
    $tbl_forum_topics     = $tbl_cdb_names['bb_topics'    ];

//    $sql = 'DELETE FROM `'.$tbl_forum_topics.'` 
//            WHERE `forum_id` = "'.$forum_id.'"';
//    if ( claro_sql_query($sql) == false ) return false;

    delete_all_post_in_forum($forum_id);


    $sql = 'DELETE FROM `'.$tbl_forum_forums.'` 
            WHERE `forum_id` = "'. (int) $forum_id.'"';
        
    if ( claro_sql_query($sql) == false) return false;
    else                                 return true;

    // note we should also clean the topic notification table ...
}



function create_forum($forum_name, $forum_desc, $forum_type, $cat_id)
{
     $tbl_cdb_names    = claro_sql_get_course_tbl();
     $tbl_forum_forums = $tbl_cdb_names['bb_forums'             ];

   // find order in the category we have to give to the newly created forum

    $sql = 'SELECT MAX(`forum_order`)
            FROM `'.$tbl_forum_forums.'`
            WHERE cat_id = "'. (int) $cat_id.'"';

    $result = claro_sql_query($sql);

    list($orderMax) = mysql_fetch_row($result);
    $order = $orderMax + 1;

    // add new forum in DB

    $sql = 'INSERT INTO `'.$tbl_forum_forums.'`
            SET forum_name  = "'. addslashes($forum_name) .'", 
            forum_desc      = "'. addslashes($forum_desc) .'", 
            forum_access    = 2,
            forum_moderator = 1, 
            cat_id          = "'. (int) $cat_id .'", 
            forum_type      = "'. (int) $forum_type  . '",
            md5             ="'.md5(time()).'", 
            forum_order    ="'. (int) $order.'"';

    if ( claro_sql_query($sql) != false) return true;
    else                                 return false;
}

function move_up_forum($forum_id, $cat_id)
{
    $tbl_cdb_names    = claro_sql_get_course_tbl();
    $tbl_forum_forums = $tbl_cdb_names['bb_forums'];

    $forum_rank = get_forum_rank($forum_id);

    if ($forum_rank > 1 )
    {
        // previous forum +1
        $sql = 'UPDATE `'.$tbl_forum_forums.'`
                SET    `forum_order` = `forum_order`+1
                WHERE  `forum_order` =  '. ($forum_rank - 1) . '
                  AND  `cat_id` = '. (int) $cat_id ;

        if ( claro_sql_query($sql) == false ) return false;

        // forum -1
        $sql = 'UPDATE `'.$tbl_forum_forums.'`
                SET    `forum_order` = `forum_order`-1
                WHERE  `forum_id` =  "'.(int) $forum_id.'"
                  AND  `cat_id` = '. (int) $cat_id ;

        if ( claro_sql_query($sql) == false ) return false;

        return true;
    }
}

function move_down_forum($forum_id, $cat_id)
{
    $tbl_cdb_names    = claro_sql_get_course_tbl();
    $tbl_forum_forums = $tbl_cdb_names['bb_forums'];

    $forum_rank = get_forum_rank($forum_id);

    $sql = 'SELECT MAX(f.`forum_order`) AS `max_order`
            FROM  `'.$tbl_forum_forums.'` f
            WHERE `cat_id` = '. (int) $cat_id ;

    $max_order = claro_sql_query_get_single_value($sql);

    if ( $forum_rank < $max_order )
    {
        // next forum - 1
        $sql = 'UPDATE `'.$tbl_forum_forums.'`
                SET `forum_order` = `forum_order`-1
                WHERE `forum_order` =  '. ($forum_rank + 1) . ' 
                    AND `cat_id` = '. (int) $cat_id ;

        if ( claro_sql_query($sql) == false ) return false;

        // forum + 1
        $sql = 'UPDATE `'.$tbl_forum_forums.'`
                SET `forum_order` = `forum_order`+1
                WHERE `forum_id` =  ' . $forum_id . '
                AND `cat_id` = ' . (int) $cat_id ;

        if ( claro_sql_query($sql) == false ) return false;
    }
}


/**
 * return the rank (order) of a forum into a category
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param  int fourm id
 * @return int forum rank (order)
 */

function get_forum_rank($forum_id)
{
    $tbl_cdb_names    = claro_sql_get_course_tbl();
    $tbl_forum_forums = $tbl_cdb_names['bb_forums'];

    $sql = 'SELECT f.`forum_order` 
            FROM `'.$tbl_forum_forums.'` f
            WHERE `forum_id` = ' . (int) $forum_id ;

    $forum_rank = claro_sql_query_get_single_value($sql);

    return $forum_rank;
}

function get_category_rank($cat_id)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forum_categories = $tbl_cdb_names['bb_categories'];

    $sql = 'SELECT f.`cat_order` 
    FROM `'.$tbl_forum_categories.'` f
    WHERE f.`cat_id` = ' . (int) $cat_id;

    $category_rank = claro_sql_query_get_single_value($sql);
    return $category_rank;
}

function move_up_category($cat_id)
{
	$order = get_category_rank($cat_id);

    if ($order > 1 )
    {
        $tbl_cdb_names = claro_sql_get_course_tbl();
        $tbl_forum_categories = $tbl_cdb_names['bb_categories'];

        // previous cat +1
        $sql = 'UPDATE `'.$tbl_forum_categories.'`
                SET `cat_order` = `cat_order`+1
                WHERE `cat_order` = ' . (int) ($order-1);

        if ( claro_sql_query($sql) == false) return false;

        // cat -1
        $sql = 'UPDATE `'.$tbl_forum_categories.'`
                SET `cat_order` = `cat_order`-1
                WHERE `cat_id` = ' . (int) $cat_id ;

        if ( claro_sql_query($sql) == false) return false;
    }

    return true;
}

function move_down_category($cat_id)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forum_categories = $tbl_cdb_names['bb_categories'];

    $order = get_category_rank($cat_id);

    $sql = 'SELECT max(f.`cat_order`) as `cat_order`
         FROM `'.$tbl_forum_categories.'` f';

    $max_order = claro_sql_query_get_single_value($sql);
    
    if ( $order < $max_order )
    {
        // next cat - 1
        $sql = 'UPDATE `'.$tbl_forum_categories.'`
                SET `cat_order` = `cat_order`-1
                WHERE `cat_order` =  '. (int) ($order+1);

        if ( claro_sql_query($sql) == false) return false;

        // cat + 1
        $sql = 'UPDATE `'.$tbl_forum_categories.'`
                SET `cat_order` = `cat_order`+1
                WHERE `cat_id` = '. (int) $cat_id;

        if ( claro_sql_query($sql) == false) return false;
        
    }

    return true;
}


function get_user_group_list($uid)
{
    $tbl_cdb_names     = claro_sql_get_course_tbl();
    $tbl_student_group = $tbl_cdb_names['group_team'         ];
    $tbl_user_group    = $tbl_cdb_names['group_rel_team_user'];

    $sql = "SELECT `g`.`id` `group_id` 
            FROM `" . $tbl_student_group . "` `g`,
                 `" . $tbl_user_group    . "` `gu`
            WHERE `g`.`id`    = `gu`.`team`
              AND `gu`.`user` = '".(int)$uid."'";

    $groupList = claro_sql_query_fetch_all_cols($sql);
    $groupList = $groupList['group_id'];
    return $groupList;
}

function get_tutor_group_list($uid)
{
    $tbl_cdb_names     = claro_sql_get_course_tbl();
    $tbl_student_group = $tbl_cdb_names['group_team'];

    $sql = "SELECT `id` `group_id` 
            FROM `" . $tbl_student_group . "`
            WHERE tutor = '" . $uid . "'";

    $groupList = claro_sql_query_fetch_all_cols($sql);
    $groupList = $groupList['group_id'];
    return $groupList;
}

function get_forum_list()
{
    $tbl_cdb_names = claro_sql_get_course_tbl();

    $tbl_forums           = $tbl_cdb_names['bb_forums' ];
    $tbl_posts            = $tbl_cdb_names['bb_posts'  ];
    $tbl_student_group    = $tbl_cdb_names['group_team'];

    $sql = "SELECT f.*, p.poster_id, p.post_time, g.id group_id
            FROM `" . $tbl_forums . "` f
            LEFT JOIN `" . $tbl_posts . "` p 
                   ON p.post_id = f.forum_last_post_id
            LEFT JOIN `" . $tbl_student_group . "` g 
                   ON g.forumId = f.forum_id
            ORDER BY f.forum_order, f.cat_id, f.forum_id ";

     return claro_sql_query_fetch_all($sql);
}

function get_category_list()
{
    $tbl_cdb_names  = claro_sql_get_course_tbl();
    $tbl_categories = $tbl_cdb_names['bb_categories'      ];
    $tbl_forums     = $tbl_cdb_names['bb_forums'          ];

    $sql = "SELECT `c`.`cat_id`, `c`.`cat_title`, `c`.`cat_order`
           FROM   `" . $tbl_categories . "` c, `" . $tbl_forums . "` f
           WHERE `f`.`cat_id` = `c`.`cat_id`
           GROUP BY `c`.`cat_id`, `c`.`cat_title`, `c`.`cat_order`
           ORDER BY `c`.`cat_order` ASC";

    // Note. The query return category only if there is forums inside

    return claro_sql_query_fetch_all($sql);
}


?>
