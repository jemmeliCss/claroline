<?php // $Id$
/**
 * CLAROLINE 
 *
 * @version 1.7 $Revision$
 *
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @package AUTH
 *
 * @author Claro Team <cvs@claroline.net>
 */

require '../inc/claro_init_global.inc.php';

claro_unquote_gpc();

// Redirect before first output
if ( ! isset($allowSelfReg) || $allowSelfReg == FALSE)
{
    header("Location: ".$rootWeb);
    exit;
}

// include profile library
include($includePath . '/conf/user_profile.conf.php');
include($includePath . '/lib/user.lib.php');
include($includePath . '/lib/claro_mail.lib.inc.php');
include($includePath . '/lib/events.lib.inc.php');

// Initialise variables

$error = false;
$messageList = array();

// Initialise field variable from subscription form 

$user_data = user_initialise();

if ( isset($_REQUEST['cmd']) ) $cmd = $_REQUEST['cmd'];
else                           $cmd = '';

/*=====================================================================
  Main Section
 =====================================================================*/ 

if ( $cmd == 'registration' )
{

    // get params from the form

    if ( isset($_REQUEST['lastname']) )      $user_data['lastname'] = strip_tags(trim($_REQUEST['lastname'])) ;
    if ( isset($_REQUEST['firstname']) )     $user_data['firstname']  = strip_tags(trim($_REQUEST['firstname'])) ;
    if ( isset($_REQUEST['officialCode']) )  $user_data['officialCode']  = strip_tags(trim($_REQUEST['officialCode'])) ;
    if ( isset($_REQUEST['username']) )      $user_data['username']  = strip_tags(trim($_REQUEST['username']));
    if ( isset($_REQUEST['password']) )      $user_data['password']  = trim($_REQUEST['password']);
    if ( isset($_REQUEST['password_conf']) ) $user_data['password_conf']  = trim($_REQUEST['password_conf']);
    if ( isset($_REQUEST['email']) )         $user_data['email']  = strip_tags(trim($_REQUEST['email'])) ;
    if ( isset($_REQUEST['phone']) )         $user_data['phone']  = trim($_REQUEST['phone']);
    if ( isset($_REQUEST['status']) )        $user_data['status']  = (int) $_REQUEST['status'];

    // validate forum params

    $messageList = user_validate_form_registration($user_data);
    
    if ( count($messageList) == 0 )
    {
        // register the new user in the claroline platform

        $_uid = user_add($user_data);

        if ( $_uid )
        {
            // add value in session
            $_user['firstName']     = $user_data['firstname'];
            $_user['lastName' ]     = $user_data['lastname'];
            $_user['mail'     ]     = $user_data['email'];
            $_user['lastLogin']     = time() - (24 * 60 * 60); // DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            $is_allowedCreateCourse = ($user_data['status'] == 1) ? TRUE : FALSE ;

            $_SESSION['_uid'] = $_uid;
            $_SESSION['_user'] = $_user;
            $_SESSION['is_allowedCreateCourse'] = $is_allowedCreateCourse;
            
            // track user login
            event_login();
    
            // last user login date is now
            $user_last_login_datetime = 0; // used as a unix timestamp it will correspond to : 1 1 1970
            $_SESSION['user_last_login_datetime'] = $user_last_login_datetime;
    
            // send info to user by email 
            user_send_registration_mail($_uid, $user_data);
        
        } // if _uid

    } // end register user    
    else
    {
        // user validate form return error messages
        $error = true;
    }

}

/*=====================================================================
  Display Section
 =====================================================================*/ 

$interbredcrump[]= array ("url"=>"inscription.php", "name"=> $langRegistration);

// Display Header

include($includePath . '/claro_init_header.inc.php');

// Display Title

claro_disp_tool_title($langRegistration);

if ( $cmd == 'registration' && $error == false )
{
        // registration succeeded

        printf($langMessageSubscribeDone_p_firstname_lastname, $user_data['firstname'], $user_data['lastname']);

        if ( $is_allowedCreateCourse )
        {
            echo '<p>' . $langNowGoCreateYourCourse . '</p>' . "\n";
        }
        else
        {
            echo '<p>' . $langNowGoChooseYourCourses . '</p>' . "\n";
        }

        echo '<form action="../../index.php?cidReset=1" >'
        .    '<input type="submit" name="next" value="' . $langNext . '" validationmsg=" ' . $langNext . ' ">' . "\n"
        .    '</form>'."\n" ;
}
else
{
    //  if registration failed display error message

    if ( count($messageList) > 0 ) 
    {
        claro_disp_message_box( implode('<br />', $messageList) );
    }

    user_display_form_registration($user_data);

}

// Display Footer

include ($includePath . '/claro_init_footer.inc.php' );

?>
