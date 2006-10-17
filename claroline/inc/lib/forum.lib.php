<?php // $Id$
if ( count( get_included_files() ) == 1 ) die( '---' );
/**
 * CLAROLINE
 *
 * Library for forum tool
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
 * @since 1.6
 */

/**
 * Gets user data from uid
 * @param  int   user id
 * @return array user data if it succeeds, boolean false otherwise
 */

define ('GROUP_FORUMS_CATEGORY', 1);

/**
 * Returns the total number of posts in the whole system, a forum, or a topic
 * Also can return the number of users on the system.
 *
 * @param $id integer id of the item in the type
 * @param $type string 'users','forum', 'topic', 'all'
 *
 * @return integer qty
 */

function get_total_posts($id, $type = 'all', $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
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
        default     : $condition = false; // normally, we should never get this.
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
 * @param $post_id integer
 *
 * @return true if $post_id is first in the $topic_id
 */

function is_first_post($topic_id, $post_id)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_posts     = $tbl_cdb_names['bb_posts'];

    $sql = "SELECT post_id FROM `" . $tbl_posts . "`
            WHERE topic_id = " . (int) $topic_id . "
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
    echo '<table border="0" align="center" width="100%">'."\n"
        .'<tr>'."\n"
        .'<td>'."\n"
        .'<blockquote>'."\n"
        .$msg."\n"
        .'</blockquote>'."\n"
        .'</td>'."\n"
        .'</tr>'."\n"
        .'</table>'."\n";
}

/**
 * Update summary info in forum and topic table
 * @param int forumId
 * @param int topicId (optionnal)
 * @return boolean true if succeeds, false otherwise
 */

function sync($forumId, $topicId = null)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forums    = $tbl_cdb_names['bb_forums'];
    $tbl_topics    = $tbl_cdb_names['bb_topics'];
    $tbl_posts     = $tbl_cdb_names['bb_posts'];

    // TOPIC SYNC PART

    if ($topicId)
    {
        $sql = "SELECT COUNT(post_id) AS total
                FROM `" . $tbl_posts . "`
                WHERE topic_id = " . (int) $topicId;

        $total_posts = claro_sql_query_get_single_value($sql);

        if ($total_posts < 1)
        {
            // no post anymore in the topic --> delete topic

            $sql = "DELETE FROM `" . $tbl_topics . "`
                    WHERE topic_id = " . (int) $topicId;

            if (claro_sql_query($sql) == false) return false;

            if (! cancel_topic_notification($topicId) ) return false;
        }
        else
        {
            $sql = "SELECT MAX(post_id) AS last_post
                    FROM `" . $tbl_posts . "`
                    WHERE topic_id = " . (int) $topicId;

            $last_post = claro_sql_query_get_single_value($sql);

            $sql = "UPDATE `" . $tbl_topics . "`
                    SET topic_replies = " . (int) $total_posts . ",
                    # note. topic_replies should be renamed topic_posts'
                        topic_last_post_id = " . (int) $last_post . "
                    WHERE topic_id = " . (int) $topicId;

            if ( claro_sql_query($sql) == false ) return false;
        }
    }
    // else noop

    // FORUM SYNC PART

    $sql = "SELECT COUNT(post_id) AS total
            FROM `" . $tbl_posts . "`
            WHERE forum_id = " . (int) $forumId;

    $total_posts = claro_sql_query_get_single_value($sql);

    $sql = "SELECT MAX(post_id) AS last_post
            FROM `" . $tbl_posts . "`
            WHERE forum_id = '" .  (int) $forumId . "'";

    $last_post = claro_sql_query_get_single_value($sql);

    $sql = "SELECT COUNT(topic_id) AS total
            FROM `" . $tbl_topics."`
            WHERE forum_id = '" . (int) $forumId . "'";

    $total_topics = claro_sql_query_get_single_value($sql);

    $sql = "UPDATE `" . $tbl_forums."`
            SET forum_last_post_id = '" . (int) $last_post . "',
                forum_posts = '" . (int) $total_posts . "',
                forum_topics = '" . (int) $total_topics . "'
            WHERE forum_id = '" . (int) $forumId . "'";

    if ( claro_sql_query($sql) == false) return false;

    return true;
}

/**
 * Convert a SQL date or datetime to a unix time stamp
 *
 * @param string SQL DATETIME or DATE
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 *
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
 * @return array forum settings or false
 */

function get_forum_settings($forumId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forums        = $tbl_cdb_names['bb_forums'];

    $sql = "SELECT `f`.`forum_id`     `forum_id`,
                   `f`.`forum_name`   `forum_name`,
                   `f`.`forum_desc`   `forum_desc`,
                   `f`.`forum_access` `forum_access`,
                   `f`.`forum_type`   `forum_type`,
                   `f`.`cat_id`       `cat_id`,
                   `f`.`forum_order`  `forum_rank`,
                   `f`.`group_id`      `idGroup`
            FROM `" . $tbl_forums."` `f`
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
            WHERE topic_id = " . (int) $topicId;

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
 * @return integer id of the new topic
 */

function create_new_topic($subject, $time, $forumId
                         , $userId, $userFirstname, $userLastname, $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_forums  = $tbl_cdb_names['bb_forums'];
    $tbl_topics  = $tbl_cdb_names['bb_topics'];

    $sql = "INSERT INTO `" . $tbl_topics . "`
            SET topic_title  = '" . addslashes($subject) . "',
                topic_poster = " . (int) $userId . ",
                forum_id     = " . (int) $forumId . ",
                topic_time   = '" . addslashes($time) . "',
                topic_notify = 1,
                nom          = '" . addslashes($userLastname) . "',
                prenom       = '" . addslashes($userFirstname) . "'";
    $topicId = claro_sql_query_insert_id($sql);

    // UPDATE THE TOPIC STATUS FOR THE CURRENT FORUM

    $sql = "UPDATE `" . $tbl_forums."`
            SET   forum_topics = forum_topics + 1
            WHERE forum_id     = " . (int) $forumId;

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
            FROM `" . $tbl_posts . "`      p,
                 `" . $tbl_posts_text . "` pt
            WHERE p.post_id   = " . (int) $postId . "
              AND pt.post_id  = p.post_id";

    $result = claro_sql_query_fetch_all($sql);

    if ( count($result) == 1) return $result[0];
    else                      return false;
}


/**
 *
 * @author Hugues Peeters <peeters@ipm.cl.ac.be>
 * @param
 * @return  integer id of the new post
 *
 */


function create_new_post($topicId, $forumId, $userId, $time, $posterIp
                        , $userLastname, $userFirstname, $message, $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_forums           = $tbl_cdb_names['bb_forums'];
    $tbl_topics           = $tbl_cdb_names['bb_topics'];
    $tbl_posts            = $tbl_cdb_names['bb_posts'];
    $tbl_posts_text       = $tbl_cdb_names['bb_posts_text'];

    // CREATE THE POST SETTINGS

    $sql = "INSERT INTO `" . $tbl_posts . "`
            SET topic_id  = '" . (int) $topicId . "',
                forum_id  = '" . (int) $forumId . "',
                poster_id = '" . (int) $userId . "',
                post_time = '" . addslashes($time) . "',
                poster_ip = '" . addslashes($posterIp) . "',
                nom       = '" . addslashes($userLastname) . "',
                prenom    = '" . addslashes($userFirstname) . "'";

    $postId = claro_sql_query_insert_id($sql);

    if ($postId)
    {
        // RECORD THE POST CONTENT

        $sql = "INSERT INTO `" . $tbl_posts_text . "`
                SET post_id   = '" . (int) $postId . "',
                    post_text = '" . addslashes($message) . "'";

        claro_sql_query($sql);

        // UPDATE THE TOPIC STATUS

        $sql = "UPDATE `" . $tbl_topics . "`
                SET   topic_replies      =  topic_replies+1, # should be transformed into `topic_posts`
                      topic_last_post_id = '" .(int) $postId . "',
                      topic_time         = '" .addslashes($time) . "'
                WHERE topic_id = '" . (int) $topicId . "'";

        claro_sql_query($sql);

        // UPDATE THE POST STATUS FOR THE CURRENT FORUM

        $sql = "UPDATE `" . $tbl_forums . "`
                SET   forum_posts        = forum_posts+1,
                      forum_last_post_id = '" . (int) $postId . "'
                WHERE forum_id           = '" . (int) $forumId . "'";

        claro_sql_query($sql);

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
 */


function update_post($post_id, $topic_id, $message, $subject = '')
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_topics     = $tbl_cdb_names['bb_topics'];
    $tbl_posts_text = $tbl_cdb_names['bb_posts_text'];

    $sql = "UPDATE `" . $tbl_posts_text . "`
            SET post_text = '" . addslashes($message) . "'
            WHERE post_id = '" . (int) $post_id . "'";

    claro_sql_query($sql);

    if ( $subject != '' )
    {

        $sql = "UPDATE `" . $tbl_topics . "`
                SET topic_title  = '" . addslashes($subject) . "'
                WHERE topic_id = '" . (int) $topic_id . "'";

        claro_sql_query($sql);
    }
}

/**
 *
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param int $postId
 * @param int $topciId
 * @param int $forumId
 * @return boolean true if succeeds, false otherwise
 */

function delete_post($postId, $topicId, $forumId)
{
    $tbl_cdb_names  = claro_sql_get_course_tbl();
    $tbl_posts      = $tbl_cdb_names['bb_posts'     ];
    $tbl_posts_text = $tbl_cdb_names['bb_posts_text'];

    $sql = "DELETE FROM `" . $tbl_posts . "`
            WHERE post_id = '" . (int) $postId . "'";

    if (claro_sql_query($sql) == false) return false;

    $sql = "DELETE FROM `" . $tbl_posts_text . "`
            WHERE post_id = '" . (int) $postId . "'";

    if (claro_sql_query($sql) == false) return false;

    if ( sync($forumId, $topicId) ) return true;
    else                            return false;
}


/**
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param int $userId
 * @param int $topicId
 * @return void
 */

function request_topic_notification($topicId, $userId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_user_notify = $tbl_cdb_names['bb_rel_topic_userstonotify'];

    // check first if user is not regisitered for topic notification yet
    if (! is_topic_notification_requested($topicId, $userId) )
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
 * @param int $topicId (optionnal)
 * @return void
 */

function cancel_topic_notification($topicId = null, $userId = null)
{
    $tbl_cdb_names   = claro_sql_get_course_tbl();
    $tbl_user_notify = $tbl_cdb_names['bb_rel_topic_userstonotify'];

    $conditionList = array();
    if ($userId ) $conditionList[]  = " `user_id`  = " . (int) $userId;
    if ($topicId) $conditionList[]  = " `topic_id` = " . (int) $topicId;

    $sql = "DELETE FROM `" . $tbl_user_notify . "`"
    .      ( ( count($conditionList) > 0) ? " WHERE " . implode(" AND ", $conditionList) : "" );

    if (claro_sql_query($sql) == false) return false;
    else                                return true;
}


/**
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param int $userId
 * @param int $topicId
 * @return bool
 */

function is_topic_notification_requested($topicId, $userId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_user_notify = $tbl_cdb_names['bb_rel_topic_userstonotify'];

    $sql = "SELECT COUNT(*) notification_qty
            FROM `" . $tbl_user_notify . "`
            WHERE `user_id`  = " . (int) $userId . "
              AND `topic_id` = " . (int) $topicId ;

    if (claro_sql_query_get_single_value($sql) > 0) return true;
    else                                            return false;
}


function trig_topic_notification($topicId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_user_notify = $tbl_cdb_names['bb_rel_topic_userstonotify'];
    $tbl_mdb_names = claro_sql_get_main_tbl();
    $tbl_users       = $tbl_mdb_names['user'];

    global $_course;

    $sql = "SELECT u.user_id, u.prenom firstname, u.nom lastname
            FROM `" . $tbl_user_notify . "` AS notif,
                 `" . $tbl_users . "` AS u
            WHERE notif.topic_id = " . (int) $topicId . "
            AND   notif.user_id  = u.user_id";

    $notifyResult = claro_sql_query($sql);
    $subject      = get_lang('A reply to your topic has been posted');

    $url_topic = get_conf('rootWeb') . 'claroline/phpbb/viewtopic.php?topic=' .  $topicId . '&cidReq=' . $_course['sysCode'];
    $url_forum = get_conf('rootWeb') . 'claroline/phpbb/index.php?cidReq=' . $_course['sysCode'];

    // send mail to registered user for notification

    while ( ( $list = mysql_fetch_array($notifyResult) ) )
    {
    	$message = get_block('blockForumNotificationEmailMessage',array('%firstname' => $list['firstname'],
								  '%lastname' => $list['lastname'],
                                  '%url_topic' => $url_topic,
                                  '%url_forum' => $url_forum ) );

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

    echo '<table border="0" align="center" ">' . "\n"
       . '<tr>' . "\n"
       . '<td>' . "\n"
       . '<center>' . "\n"
       . '<p>' . $message . '</p>' . "\n" ;

    if ($forumId && $topicId)
    {
        $url = 'viewtopic.php?topic=' . $topicId . '&amp;forum=' . $forumId ;
        echo '<p>' . get_lang('Click <a href="%url">here</a> to view your message', array( '%url' => $url ) ). '</p>' . "\n" ;
    }

    if ($forumId)
    {
        $url = 'viewforum.php?forum=' . $forumId ;
        echo '<p>' . get_lang('Click <a href="%url">here</a> to return to the forum topic list', array( '%url' => $url ) ). '</p>' . "\n" ;
    }

    $url = 'index.php' ;
    echo '<p>' . get_lang('Click <a href="%url">here</a> to return to the forum index', array( '%url' => $url ) ). '</p>' . "\n" ;

    echo '</center>' . "\n"
        . '</td>' . "\n"
        . '</tr>' . "\n"
        . '</table>' . "\n";
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
 * @param  int    $pageMax (optionnal) - If the number of page exceeds this param
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
 * @package CLFRM
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
        echo $this->sqlPager->disp_pager_tool_bar($pagerUrl);
    }
}


/**
 * Class building a list of all the post of specific topic, with pager options
 * The class is actually based on the claro_sql_pager class
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @see    claro_sql_pager class
 * @package CLFRM
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
        echo $this->sqlPager->disp_pager_tool_bar($pagerUrl);
    }
}

/**
 * display a pager tool bar
 *
 * @author Mathieu Laurent <mla@claroline.net>
 * @return void
 */


function disp_forum_toolbar($pagetype, $forum_id, $cat_id = 0, $topic_id = 0)
{
    global $_gid, $forum_name, $topic_title, $imgRepositoryWeb;

    $toolBar = array();

    $html = '';

    switch ( $pagetype )
    {
        // 'index' is covered by default

        case 'newtopic':

            break;

        case 'reply':

            break;


        case 'viewforum':

            $toolBar[] = '<a class="claroCmd" href="newtopic.php?forum=' . $forum_id . '&amp;gidReq=' . $_gid . '">'
                        . '<img src="' . $imgRepositoryWeb . 'topic.gif"> ' . get_lang('New topic') . '</a>';

            break;

        case 'viewtopic':

            $toolBar[] = '<a class="claroCmd" href="newtopic.php?forum=' . $forum_id . '&amp;gidReq=' . $_gid . '">'
                         . '<img src="' . $imgRepositoryWeb . 'topic.gif"> ' . get_lang('New topic') . '</a>';

            $toolBar[] = '<a class="claroCmd" href="reply.php?topic=' . $topic_id . '&amp;forum=' . $forum_id . '&amp;gidReq='.$_gid.'">'
                         . '<img src="' . $imgRepositoryWeb . 'reply.gif" /> ' . get_lang('Reply') . '</a>' ."\n";

            break;

        // 'Register' is covered by default

        case 'index':

            if ( claro_is_allowed_to_edit() )
            {

                $toolBar[] = '<a class="claroCmd" href="'.$_SERVER['PHP_SELF'].'?cmd=rqMkCat">'
                          .  get_lang('Create category')
                          .  '</a>';

                $toolBar[] = '<a class="claroCmd" href="'.$_SERVER['PHP_SELF'].'?cmd=rqMkForum">'
                          .  '<img src="' . $imgRepositoryWeb . 'forum.gif" /> '
                          .  get_lang('Create forum')
                          .  '</a>';
            }
            break;
    }

    if ( ! in_array($pagetype, array('newtopic', 'reply','editpost') ) )
        $toolBar[] = '<a class="claroCmd" href="index.php?cmd=rqSearch">'
        .            '<img src="' . $imgRepositoryWeb . 'search.gif" /> '
        .            get_lang('Search')
        .            '</a>'
        ;

    if ( count($toolBar) )
    {
        $html = '<p>' . claro_html_menu_horizontal($toolBar) . '</p>';
    }

    return $html;
}

function disp_search_box()
{
    if (isset($_REQUEST['cmd']) && $_REQUEST['cmd'] == 'rqSearch' )
    {
        return claro_html_message_box(
        '<form action="viewsearch.php" method="post">'
        .            get_lang('Search') . ' : <br />'
        .            '<input type="text" name="searchPattern"><br />'
        .            '<input type="submit" value="' . get_lang('Ok') . '" />&nbsp; '
        .            claro_html_button($_SERVER['PHP_SELF'], get_lang('Cancel'))
        .            '</form>'
        );
    }
    else
    {
        return '';
    }
}

function disp_forum_breadcrumb($pagetype, $forum_id, $forum_name, $topic_id=0, $topic_name='')
{
    global $_gid;

    $breadCrumbNameList   = array ('Forum Index');
    $breadCrumbUrlList    = array ('index.php');

    if ( in_array($pagetype, array('viewforum', 'viewtopic', 'editpost', 'reply', 'newtopic') ) )
    {
        $breadCrumbNameList[] = $forum_name;
        $breadCrumbUrlList[]  = 'viewforum.php?forum=' . $forum_id . ($_gid ? '&amp;gidReq=' . $_gid : '');

        switch ( $pagetype )
        {
            case 'viewforum' :
                break;

            case 'viewtopic' :
                $breadCrumbNameList[] = $topic_name;
                $breadCrumbNameUrl[] = '';
                break;

            case 'newtopic' :
                $breadCrumbNameList[] = get_lang('New topic');
                $breadCrumbUrlList[]  = null;
                break ;

            case 'editpost' :
                $breadCrumbNameList[] = $topic_name;
                $breadCrumbUrlList[]  = 'viewtopic.php?topic=' . $topic_id . ($_gid ? '&amp;gidReq=' . $_gid : '');
                $breadCrumbNameList[] = get_lang('Edit post');
                $breadCrumbUrlList[]  = null;
                break ;

            case 'reply' :
                $breadCrumbNameList[] = $topic_name;
                $breadCrumbUrlList[]  = 'viewtopic.php?topic=' . $topic_id . ($_gid ? '&amp;gidReq=' . $_gid : '');
                $breadCrumbNameList[] = get_lang('Reply');
                $breadCrumbUrlList[]  = null;
                break ;
        }
    }
    elseif ($pagetype == 'viewsearch')
    {
            $breadCrumbNameList[] = get_lang('Search result');
            $breadCrumbUrlList[]  = null;
    }

    return claro_html_breadcrumbtrail($breadCrumbNameList, $breadCrumbUrlList, ' > ') . '<br />' ;
}

/**
 * @param
 * @param boolean $active if set to true, only actvated tool will be considered for display
 */

function forum_group_tool_list($gid, $active = true)
{
    global $imgRepositoryWeb, $_groupProperties, $is_courseAdmin, $is_groupTutor, $is_groupMember;
    $courseId = $GLOBALS['_cid'];
    include_once(dirname(__FILE__) . '/group.lib.inc.php');
    $groupToolList = get_group_tool_list($courseId,$active);

    $is_allowedToDocAccess      = (bool) (   $is_courseAdmin
                                      || $is_groupMember
                                      || $is_groupTutor);

    $is_allowedToChatAccess     = (bool) (     $is_courseAdmin
                                       || $is_groupMember
                                       || $is_groupTutor );


    // group space links

    $toolList[] = '<a class="claroCmd" href="../group/group_space.php?gidReq=' .(int) $gid . '">'
        . '<img src="' . $imgRepositoryWeb . 'group.gif" />&nbsp;'
        . get_lang('Group area')
        . '</a>'
        ;

    foreach ($groupToolList as $groupTool)
    {
        if ('CLFRM' !== $groupTool['label'])
        $toolList[] = '<a href="' . get_module_url($groupTool['label']) . '/' . $groupTool['url']. '?gidReq=' . (int) $gid  . '" '
        .             ' class="claroCmd '.($groupTool['visibility'] ? 'visible':'invisible').'">'
        .             '<img src="' . $imgRepositoryWeb . $groupTool['icon'] . '" />'
        .             '&nbsp;'
        .             claro_get_tool_name ($groupTool['label'])
        .             '</a>'
        ;
    }

    return $toolList;
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
            WHERE forum_id = " . (int) $forumId;

    $postIdList = claro_sql_query_fetch_all_cols($sql);
    $postIdList = $postIdList['post_id'];

    $sql = "SELECT topic_id FROM `".$tbl_topics."`
            WHERE forum_id = " .(int) $forumId;

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

    if ( claro_sql_query($sql) == false ) return false;

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
            WHERE cat_id    = ".(int) $catId;

        if (claro_sql_query($sql) != false) return true;
    }

    return false;
}

function update_forum_settings($forum_id, $forum_name, $forum_desc, $forum_post_allowed, $cat_id)
{
    $tbl_cdb_names        = claro_sql_get_course_tbl();
    $tbl_forum_forums     = $tbl_cdb_names['bb_forums'];
    $sql = "UPDATE `" . $tbl_forum_forums . "`
            SET `forum_name`     = '" . addslashes($forum_name) . "',
                `forum_desc`     = '" . addslashes($forum_desc) . "',
                `forum_access`   = " . ($forum_post_allowed ? 2 : 0) . ",
                `forum_moderator`= 1,
                `cat_id`         = " . (int) $cat_id . ",
                `forum_type`     = 0
            WHERE `forum_id` = " . (int) $forum_id;

    if (claro_sql_query($sql) != false) return true;
    else                                return false;
}

function create_category($cat_title, $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_forum_categories = $tbl_cdb_names['bb_categories'];

    // Find order in the category we must give to the newly created forum
    $sql = 'SELECT MAX(`cat_order`) FROM `' . $tbl_forum_categories . '`';
    $result = claro_sql_query($sql);

    list($orderMax) = mysql_fetch_row($result);
    $order = $orderMax + 1;

    $sql = 'INSERT INTO `' . $tbl_forum_categories . '`
            SET `cat_title` = "'. addslashes($cat_title) .'",
                `cat_order` = '. (int) $order;

    $catId = claro_sql_query_insert_id($sql);

    if ( $catId != false) return $catId;
    else                  return false;

}

/**
 * Delete the given category
 *
 * @param integer $cat_id
 * @return boolean wheter success
 */
function delete_category($cat_id)
{
    if ($cat_id == GROUP_FORUMS_CATEGORY)
        return claro_failure::set_failure('GROUP_FORUMS_CATEGORY_REMOVALE_FORBIDDEN');

    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forum_categories = $tbl_cdb_names['bb_categories'];
    $tbl_forum_forums     = $tbl_cdb_names['bb_forums'    ];
    $tbl_forum_topics     = $tbl_cdb_names['bb_topics'    ];

    $sql = 'SELECT `forum_id`, `group_id`
            FROM `' . $tbl_forum_forums . '`
            WHERE `cat_id` = "' . $cat_id . '"';

    $result = claro_sql_query_fetch_all_cols($sql);

    $forumIdList = $result['forum_id'];
    $groupIdList = $result['group_id'];

    if ( count(array_filter($groupIdList, 'is_null') ) < count($groupIdList) )
    {
        return claro_failure::set_failure('GROUP_FORUM_REMOVALE_FORBIDDEN');
    }
    else
    {
        foreach($forumIdList as $thisForumId)
        {
            $sql = 'DELETE FROM `'.$tbl_forum_topics.'`
                    WHERE `forum_id` = "'. (int)$thisForumId.'"';

            claro_sql_query($sql);
        }

        $sql = 'DELETE FROM `'.$tbl_forum_forums.'`
                WHERE `cat_id` = "'. (int) $cat_id.'"';

        claro_sql_query($sql);

        $sql = 'DELETE FROM `'.$tbl_forum_categories.'`
                WHERE `cat_id` = "'.(int) $cat_id.'"';

        claro_sql_query($sql);
        return true;
    }
}

function delete_forum($forum_id)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forum_forums     = $tbl_cdb_names['bb_forums'];

    delete_all_post_in_forum($forum_id);


    $sql = "DELETE FROM `" . $tbl_forum_forums . "`
            WHERE `forum_id` = " . (int) $forum_id ;

    if ( claro_sql_query($sql) == false) return false;
    else                                 return true;

    // note we should also clean the topic notification table ...
}


/**
 * Create a new forum (set of threads)
 *
 * @param string $forum_name
 * @param string $forum_desc
 * @param boolean $forum_post_allowed
 * @param integer $cat_id
 * @param integer $group_id default null(current)
 * @param string $course_id default null(current)
 *
 * @return integer id of new forum;
 *
 */

function create_forum($forum_name, $forum_desc, $forum_post_allowed, $cat_id, $group_id = null, $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_forum_forums = $tbl_cdb_names['bb_forums'];

    // find order in the category we have to give to the newly created forum

    $sql = "SELECT MAX(`forum_order`)
            FROM `" . $tbl_forum_forums . "`
            WHERE cat_id = " . (int) $cat_id;

    $result = claro_sql_query($sql);

    list($orderMax) = mysql_fetch_row($result);
    $order = $orderMax + 1;

    // add new forum in DB

    $sql = "INSERT INTO `" . $tbl_forum_forums . "`
            SET forum_name      = '" . addslashes($forum_name) . "',
                group_id        = " . (is_null($group_id) ? "NULL" : (int) $group_id) . ",
                forum_desc      = '" . addslashes($forum_desc) . "',
                forum_access    = " . ($forum_post_allowed ? 2 : 0) . ",
                forum_moderator = 1,
                cat_id          = " . (int) $cat_id . ",
                forum_type      = 0,
                forum_order     = " . (int) $order ;

    return claro_sql_query_insert_id($sql);
}

function move_forum_rank($currForumId, $direction)
{
    if ( strtoupper($direction) == 'UP')
    {
        $operator = ' < ';
        $orderDirection = ' DESC ';
    }
    elseif( strtoupper($direction) == 'DOWN')
    {
        $operator = ' > ';
        $orderDirection = ' ASC ';
    }
    else
    {
        return claro_failure::set_failure('WRONG DIRECTION');
    }

    $tbl_cdb_names    = claro_sql_get_course_tbl();
    $tbl_forum_forums = $tbl_cdb_names['bb_forums'];

    $forumSettingList = get_forum_settings($currForumId);
    $cat_id           = $forumSettingList['cat_id'];
    $currForumRank       = $forumSettingList['forum_rank'];

    $sql = 'SELECT forum_id AS id, forum_order AS rank
            FROM  `'.$tbl_forum_forums.'`
            WHERE cat_id      = ' . (int) $cat_id     . '
            AND   forum_order '  . $operator . ' ' . (int) $currForumRank . '
            ORDER BY forum_order ' . $orderDirection . ' LIMIT 1';

    $adjacentForum = claro_sql_query_get_single_row($sql);

    if ( is_array($adjacentForum) )
    {
        // SWAP BOTH FORUM RANKS

        $sql = 'UPDATE `'.$tbl_forum_forums.'`
                SET `forum_order` = '. (int) $currForumRank. '
                WHERE `forum_id` =  '. (int) $adjacentForum['id'] ;

        if ( claro_sql_query($sql) == false ) return false;

        $sql = 'UPDATE `'.$tbl_forum_forums.'`
                SET   `forum_order` = '. (int) $adjacentForum['rank'] . '
                WHERE `forum_id`    = '. (int) $currForumId ;

        if ( claro_sql_query($sql) == false ) return false;
    }
    else
    {
        return false;
    }

    return true;
}

function move_up_forum($forum_id)
{
    move_forum_rank($forum_id, 'UP');
}

function move_down_forum($forum_id)
{
    move_forum_rank($forum_id, 'DOWN');
}

function get_category_settings($cat_id)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forum_categories = $tbl_cdb_names['bb_categories'];

    $sql = 'SELECT `cat_id`, `cat_title`, `cat_order`
            FROM `'.$tbl_forum_categories.'` f
            WHERE f.`cat_id` = ' . (int) $cat_id;

    $resultList = claro_sql_query_fetch_all($sql);

    if (count($resultList) == 1) return $resultList[0];
    else                         return false;
}


/**
 * Change change rank of a category
 *
 * @param integer $currCatId id  of category
 * @param string $direction (UP|DOWN)
 * @return boolean true wheater success
 */
function move_category_rank($currCatId, $direction)
{
    if ( strtoupper($direction) == 'UP')
    {
        $operator = ' < ';
        $orderDirection = ' DESC ';
    }
    elseif( strtoupper($direction) == 'DOWN')
    {
        $operator = ' > ';
        $orderDirection = ' ASC ';
    }
    else
    {
        return claro_failure::set_failure('WRONG DIRECTION');
    }

    $tbl_cdb_names        = claro_sql_get_course_tbl();
    $tbl_forum_categories = $tbl_cdb_names['bb_categories'];

    $categorySettingList = get_category_settings($currCatId);
    $currCatRank         = $categorySettingList['cat_order'];

    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_forum_categories = $tbl_cdb_names['bb_categories'];

    $sql = 'SELECT cat_id AS id, cat_order AS rank
            FROM `'.$tbl_forum_categories.'`
            WHERE cat_order ' . $operator . ' '. (int) $currCatRank .'
            ORDER BY cat_order ' . $orderDirection . ' LIMIT 1';

    $adjacentCategory = claro_sql_query_get_single_row($sql);

    if (is_array($adjacentCategory) )
    {

        // SWAP BOTH RANK
        $sql = 'UPDATE `'.$tbl_forum_categories.'`
                SET cat_order = '.(int) $adjacentCategory['rank'].'
                WHERE cat_id = ' . (int) $currCatId;

        if ( claro_sql_query($sql) == false) return false;

        $sql = 'UPDATE `'.$tbl_forum_categories.'`
                SET cat_order = '.(int) $currCatRank.'
                WHERE cat_id = ' . (int) $adjacentCategory['id'];

        if ( claro_sql_query($sql) == false) return false;
    }
    else
    {
    	return false;
    }

    return true;
}

/**
 * Increase the rank of the given category
 *
 * @param integer $cat_id
 * @return boolean
 */
function move_up_category($cat_id)
{
    return move_category_rank($cat_id, 'UP');
}


/**
 * Decrease the rank of the given category
 *
 * @param integer $cat_id
 * @return boolean true whether success
 */
function move_down_category($cat_id)
{
    return move_category_rank($cat_id, 'DOWN');
}

/**
 * List of a group for a given user
 *
 * @param integer $uid
 * @return array of integer
 */
function get_user_group_list($uid)
{
    $tbl_cdb_names     = claro_sql_get_course_tbl();
    $tbl_student_group = $tbl_cdb_names['group_team'         ];
    $tbl_user_group    = $tbl_cdb_names['group_rel_team_user'];

    $sql = "SELECT `g`.`id` AS `group_id`
            FROM `" . $tbl_student_group . "` AS `g`,
                 `" . $tbl_user_group    . "` AS `gu`
            WHERE `g`.`id`    = `gu`.`team`
              AND `gu`.`user` = " . (int) $uid ;

    $groupList = claro_sql_query_fetch_all_cols($sql);
    $groupList = $groupList['group_id'];
    return $groupList;
}

/**
 * return list of groups id where a given user (userId) is tutor
 *
 * @param integer $uid uid to find groups where he's tutor
 * @return array of integer : group list
 */
function get_tutor_group_list($uid)
{
    $tbl_cdb_names     = claro_sql_get_course_tbl();
    $tbl_student_group = $tbl_cdb_names['group_team'];

    $sql = "SELECT `id` `group_id`
            FROM `" . $tbl_student_group . "`
            WHERE tutor = " . (int) $uid ;

    $groupList = claro_sql_query_fetch_all_cols($sql);
    $groupList = $groupList['group_id'];
    return $groupList;
}


/**
 * Return the full list of forum
 *
 * @return array(forum_id, forum_name, forum_desc, forum_access, forum_moderator,
                 forum_topics, forum_posts, forum_last_post_id, cat_id,
                 forum_type, forum_order, poster_id, post_time, group_id)
 */

function get_forum_list()
{
    $tbl_cdb_names = claro_sql_get_course_tbl();

    $tbl_forums           = $tbl_cdb_names['bb_forums' ];
    $tbl_posts            = $tbl_cdb_names['bb_posts'  ];

    $sql = "SELECT f.forum_id, f.forum_name, f.forum_desc,
                   f.forum_access, f.forum_moderator,
                   f.forum_topics, f.forum_posts, f.forum_last_post_id,
                   f.cat_id, f.forum_type, f.forum_order,
            p.poster_id, p.post_time, f.group_id
            FROM `" . $tbl_forums . "` AS f
            LEFT JOIN `" . $tbl_posts . "` AS p
                   ON p.post_id = f.forum_last_post_id
            ORDER BY f.forum_order, f.cat_id, f.forum_id ";

     return claro_sql_query_fetch_all($sql);
}

/**
 * Get the list of not empty categories.
 * The query return category only if there is forums inside
 *
 * @return array (cat_id, cat_title, cat_order);
 */
function get_category_list()
{
    $tbl_cdb_names  = claro_sql_get_course_tbl();
    $tbl_categories = $tbl_cdb_names['bb_categories'      ];
    $tbl_forums     = $tbl_cdb_names['bb_forums'          ];

    $sql = "SELECT `c`.`cat_id`,
                   `c`.`cat_title`,
                   `c`.`cat_order`,
                   COUNT(`f`.`forum_id`) AS forum_count
           FROM   `" . $tbl_categories . "` AS c
           LEFT JOIN `" . $tbl_forums . "`  AS f
           ON `f`.`cat_id` = `c`.`cat_id`
           GROUP BY `c`.`cat_id`, `c`.`cat_title`, `c`.`cat_order`
           ORDER BY `c`.`cat_order` ASC";

    // Note. The query return category only if there is forums inside

    return claro_sql_query_fetch_all($sql);
}

/**
 * Function to increase the counter of view a topic
 *
 * @param integer $topicId
 * @return Success true whether false
 */
function increase_topic_view_count($topicId)
{
    $tbl_cdb_names = claro_sql_get_course_tbl();
    $tbl_topics =  $tbl_cdb_names['bb_topics'];

    $sql = "UPDATE `" . $tbl_topics . "`
            SET   topic_views = topic_views + 1
            WHERE topic_id    = " . (int) $topicId;

    if ( claro_sql_query($sql) == false) return false;
    else                                 return true;
}

/**
 * Deletes forums for ALL or a given group
 *
 * @param integer $groupId or ALL
 *        If param is 'ALL', all groups forums are returned
 *        otherwise the param is use as group id to filter result.
 * @return true whether false if  a  forum deletion failed
 */
function delete_group_forums ($groupId)
{
    $forum_list = get_group_forum_list($groupId);

    foreach ( $forum_list as $forum )
    {
        if ( ! delete_forum($forum['forum_id']) ) return false;
    }

    return true;
}

/**
 * Get list of forums linked to ALL or a specific group.
 *
 * @param integer $groupId or ALL
 *        If param is 'ALL', all groups forums are returned
 *        otherwise the param is use as group id to filter result.
 * @return array of integer. Each integer is a forum id.
 */
function get_group_forum_list ($groupId)
{
    $tbl_cdb_names  = claro_sql_get_course_tbl();
    $tbl_forums     = $tbl_cdb_names['bb_forums'];

    if ( $groupId == 'ALL' )
    {
        $sql = " SELECT forum_id
                FROM `" . $tbl_forums . "`
                where group_id IS NOT NULL";
    }
    else
    {
        $sql = " SELECT forum_id
                FROM `" . $tbl_forums . "`
                where group_id = " . (int) $groupId ;
    }

    return claro_sql_query_fetch_all_rows($sql);

}

?>