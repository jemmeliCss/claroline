<?php // $Id$
/**
 * CLAROLINE
 *
 * this tool manage the new users
 *
 * @version 1.7 $Revision$
 *
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @author Claro Team <cvs@claroline.net>
 */

$cidReset = TRUE;
$gidReset = TRUE;
$tidReset = TRUE;

require '../inc/claro_init_global.inc.php';

// Security Check
if ( ! $_uid ) claro_disp_auth_form();
if ( ! $is_platformAdmin ) claro_die($langNotAllowed);

// Include library
require $includePath . '/conf/user_profile.conf.php';
require_once $includePath . '/lib/debug.lib.inc.php';
require_once $includePath . '/lib/user.lib.php';
require_once $includePath . '/lib/claro_mail.lib.inc.php';

// Initialise variables
$nameTools = $langAddUser;
$error = false;
$messageList = array();

// DB tables definition
$tbl_mdb_names = claro_sql_get_main_tbl();
$tbl_user = $tbl_mdb_names['user'];

/*=====================================================================
  Main Section
 =====================================================================*/

// Initialise field variable from subscription form
$user_data = user_initialise();

if ( isset($_REQUEST['cmd']) ) $cmd = $_REQUEST['cmd'];
else                           $cmd = '';

if ( $cmd == 'registration' )
{
    // get params from the form

    if ( isset($_REQUEST['lastname']) )      $user_data['lastname']      = strip_tags(trim($_REQUEST['lastname'])) ;
    if ( isset($_REQUEST['firstname']) )     $user_data['firstname']     = strip_tags(trim($_REQUEST['firstname'])) ;
    if ( isset($_REQUEST['officialCode']) )  $user_data['officialCode']  = strip_tags(trim($_REQUEST['officialCode'])) ;
    if ( isset($_REQUEST['username']) )      $user_data['username']      = strip_tags(trim($_REQUEST['username']));
    if ( isset($_REQUEST['password']) )      $user_data['password']      = trim($_REQUEST['password']);
    if ( isset($_REQUEST['password_conf']) ) $user_data['password_conf'] = trim($_REQUEST['password_conf']);
    if ( isset($_REQUEST['email']) )         $user_data['email']         = strip_tags(trim($_REQUEST['email'])) ;
    if ( isset($_REQUEST['phone']) )         $user_data['phone']         = trim($_REQUEST['phone']);
    if ( isset($_REQUEST['status']) )        $user_data['status']        = (int) $_REQUEST['status'];

    // validate forum params

    $messageList = user_validate_form_registration($user_data);

    if ( count($messageList) == 0 )
    {
        // register the new user in the claroline platform
        $inserted_uid = user_add($user_data);

        // send a mail to the user
        user_send_registration_mail($inserted_uid,$user_data);
    }
    else
    {
        // user validate form return error messages
        $error = true;
    }
}

/*=====================================================================
  Display Section
 =====================================================================*/

$interbredcrump[] = array ('url' => $rootAdminWeb, 'name' => $langAdministration);
$noQUERY_STRING   = TRUE;

// Display Header

include $includePath . '/claro_init_header.inc.php';

// Display title

echo claro_disp_tool_title( array('mainTitle'=>$nameTools ) );

if ( $cmd == 'registration' && $error == false )
{
    echo '<p>' . $langUserCreated . '</p>'
    .    '<ul>'
    .    '<li>'
    .    '<a class="claroCmd" href="../auth/courses.php?cmd=rqReg&amp;uidToEdit=' . $inserted_uid . '&amp;category=&amp;fromAdmin=settings">'
    .    $langRegisterTheNewUser
    .    '</a>'
    .    '</li>'
    .    '<li>'
    .    '<a class="claroCmd" href="adminprofile.php?uidToEdit=' . $inserted_uid . '&amp;category="> '
    .    $langGoToUserSettings
    .    '</a>'
    .    '</li>'
    .    '<li>'
    .    '<a class="claroCmd" href="adminaddnewuser.php"> ' . $langCreateAnotherUser . ' </a>'
    .    '</li>'
    .    '<li>'
    .    '<a class="claroCmd" href="index.php"> ' . $langBackToAdmin . ' </a>'
    .    '</li>'
    .    '</ul>'
    ;
}
else
{
    //  if registration failed display error message

    if ( count($messageList) > 0 )
    {
        echo claro_disp_message_box( implode('<br />', $messageList) );
    }

    echo $langAddUserOneByOne;

    user_display_form_admin_add_new_user($user_data);
}

// Display footer

include $includePath . '/claro_init_footer.inc.php';
?>