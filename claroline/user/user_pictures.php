<?php //$Id$

/**
 * CLAROLINE
 *
 * Display all the pictures for a specific course list of users.
 *
 * @version     $Revision$
 * @copyright   (c) 2001-2014, Universite catholique de Louvain (UCL)
 * @license     http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @package     USER
 * @author      Antonin Bourguignon <antonin.bourguignon@claroline.net>
 * @since       1.10
 */

/*=====================================================================
   Initialisation
  =====================================================================*/
$tlabelReq = 'CLUSR';
$gidReset = true;

require '../inc/claro_init_global.inc.php';

if ( ! claro_is_in_a_course() || ! claro_is_course_allowed() ) claro_disp_auth_form(true);

/*----------------------------------------------------------------------
   Include Library
  ----------------------------------------------------------------------*/

require_once get_path('incRepositorySys')  . '/lib/admin.lib.inc.php';
require_once get_path('incRepositorySys')  . '/lib/user.lib.php';
require_once get_path('incRepositorySys')  . '/lib/course_user.lib.php';
require_once get_path('incRepositorySys')  . '/lib/pager.lib.php';
require_once __DIR__ . '/../messaging/lib/permission.lib.php';

/*----------------------------------------------------------------------
   Load config
  ----------------------------------------------------------------------*/
include claro_get_conf_repository() . 'user_profile.conf.php';

$nameTools = get_lang('Users\' pictures');

$tbl_mdb_names          = claro_sql_get_main_tbl();
$tbl_rel_course_user    = $tbl_mdb_names['rel_course_user'];
$tbl_users              = $tbl_mdb_names['user'];

//Get the users
$sql = "SELECT `user`.`user_id`      AS `user_id`,
               `user`.`nom`          AS `lastname`,
               `user`.`prenom`       AS `firstname`,
               `user`.`email`        AS `email`,
               `user`.pictureUri,
               `course_user`.`profile_id`,
               `course_user`.`isCourseManager`,
               `course_user`.`isPending`,
               `course_user`.`tutor`  AS `tutor`,
               `course_user`.`role`   AS `role`
       FROM `" . $tbl_users . "`           AS user,
            `" . $tbl_rel_course_user . "` AS course_user
       WHERE `user`.`user_id`=`course_user`.`user_id`
       AND   `course_user`.`code_cours`=" . Claroline::getDatabase()->quote(claro_get_current_course_id());

$result = Claroline::getDatabase()->query($sql);

// Command list
$cmdList = array();

$cmdList[] = array(
    'img' => 'back',
    'name' => get_lang('User list'),
    'url' => claro_htmlspecialchars(Url::Contextualize(get_path('clarolineRepositoryWeb') . 'user/user.php'))
);


// Display
$out = '';

$out .= claro_html_tool_title($nameTools, null, $cmdList)
    . '<ul class="userList">'
    ;

foreach ($result as $userKey => $user)
{
    $user['picture'] = $user['pictureUri'];
    
    $picture_url = user_get_picture_url($user);
    
    if(empty($picture_url))
    {
        $picture_url = get_icon_url('nopicture');
    }
    
    $out .= '<li>'
          . '<img width="100" height="100" src="'
          . $picture_url.'" alt="'.get_lang('%firstName %lastName', array('%firstName' => $user['firstname'], '%lastName' => $user['lastname'])).'" />'
          . '<br/>'
          . get_lang('%firstName %lastName', array('%firstName' => $user['firstname'], '%lastName' => $user['lastname']))
          . '</li>';
}

$out .= '</ul>';

$GLOBALS['claroline']->display->body->appendContent($out);

echo $GLOBALS['claroline']->display->render();
