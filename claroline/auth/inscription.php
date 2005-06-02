<?php // $Id$
//----------------------------------------------------------------------
// CLAROLINE
//----------------------------------------------------------------------
// Copyright (c) 2001-2004 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

/*=====================================================================
 Init Section
 =====================================================================*/ 

require '../inc/claro_init_global.inc.php';

claro_unquote_gpc();

// Redirect before first output
if ( ! isset($allowSelfReg) || $allowSelfReg == FALSE)
{
    header("Location: ".$rootWeb);
    exit;
}

// include profile library
include($includePath.'/conf/user_profile.conf.php');
include($includePath.'/lib/user.lib.php');
include($includePath.'/lib/auth.lib.inc.php');
include($includePath.'/lib/claro_mail.lib.inc.php');
include($includePath.'/lib/events.lib.inc.php');

// Initialise variables

$error = false;
$message = '';

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

    // check if there are no empty fields

    $regexp = "^[0-9a-z_\.-]+@(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-z][0-9a-z-]*[0-9a-z]\.)+[a-z]{2,4})$";

    if (  empty($user_data['lastname'] )       || empty($user_data['firstname'] ) 
        || empty($user_data['password_conf'] ) || empty($user_data['password'] )
        || empty($user_data['username'] )      || (empty($user_data['email'] ) && !$userMailCanBeEmpty) )
    {
        $error = true;
        $message .= '<p>' . $langEmptyFields . '</p>' . "\n";
    }
    
    // check if the two password are identical
    if ( $user_data['password_conf']  != $user_data['password']  )
    {
        $error = true;
        $message .= '<p>' . $langPassTwice . '</p>' . "\n";
    }

    // check if password isn't too easy
    if ( $user_data['password'] 
             && SECURE_PASSWORD_REQUIRED
             && ! is_password_secure_enough($user_data['password'],
                  array( $user_data['username'] , 
                         $user_data['officialCode'] , 
                         $user_data['lastname'] , 
                         $user_data['firstname'] , 
                         $user_data['email'] )) )
    {
        $error = true;
        $message .= '<p>' . $langPassTooEasy . ' <code>' . substr(md5(date('Bis').$_SERVER['HTTP_REFERER']),0,8) . '</code></p>' . "\n";
    }

    // check email address validity
    if ( !empty($user_data['email'] ) && ! eregi($regexp,$user_data['email'] ) )
    {
        $error = true;
        $message .= '<p>' . $langEmailWrong . '</p>' . "\n" ;
    }

    // check if the username is already owned by another user
    if (isset($_REQUEST['email']))
    {
        $sql = 'SELECT COUNT(*) `loginCount`
                FROM `'.$tbl_user.'` 
                WHERE username="' . addslashes($user_data['username'] ) . '"';

        list($result) = claro_sql_query_fetch_all($sql);

        if ( $result['loginCount'] > 0 )
        {
            $error = true;
            $message .= '<p>' . $langUserTaken . '</p>' . "\n";
        }

    }
    
    // check if the officialcode is already owned by another user
    if (isset($_REQUEST['officialCode']))
    {
        $sql = 'SELECT COUNT(*) `officialCodeCount`
                FROM `'.$tbl_user.'` 
                WHERE officialCode="' . addslashes($user_data['officialCode'] ) . '"';
                
        list($result) = claro_sql_query_fetch_all($sql);

        if ( $result['officialCodeCount'] > 0 )
        {
            $error = true;
            $message .= '<p>Official Code taken</p>' . "\n";
        }

    }
    
    if ( $error == false )
    {
        // register the new user in the claroline platform

        $_uid = user_insert($user_data);

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
            user_send_registration_mail($_uid,$user_data);
        
        } // if _uid

    } // end register user    

}

/*=====================================================================
  Display Section
 =====================================================================*/ 

$interbredcrump[]= array ("url"=>"inscription.php", "name"=> $langRegistration);

// Display Header
include($includePath."/claro_init_header.inc.php");

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
            . '<input type="submit" name="next" value="' . $langNext . '" validationmsg=" ' . $langNext . ' ">' . "\n"
            . '</form>'."\n" ;
}
else
{
    //  if registration failed display error message
    if ( $error ) 
    {
        claro_disp_message_box($message);
    }

    user_display_form_registration($user_data);

}

// display footer
include ("../inc/claro_init_footer.inc.php");

?>
