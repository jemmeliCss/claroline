<?php // $Id$
//----------------------------------------------------------------------
// CLAROLINE 1.6
//----------------------------------------------------------------------
// Copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
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

$cidReset = TRUE;
$gidReset = TRUE;
$tidReset = TRUE;

require '../inc/claro_init_global.inc.php';

claro_unquote_gpc();

// Security check
if ( !$is_platformAdmin ) claro_disp_auth_form();

// Include configuration
include $includePath.'/conf/user_profile.conf.php';

// Include libraries
include $includePath.'/lib/user.lib.php';

// Initialise variables
$nameTools=$langUserSettings;
$error = false;
$messageList = array();

$tbl_mdb_names   = claro_sql_get_main_tbl();
$tbl_user        = $tbl_mdb_names['user'  ];
$tbl_course      = $tbl_mdb_names['course'];
$tbl_admin       = $tbl_mdb_names['admin' ];
$tbl_course_user = $tbl_mdb_names['rel_course_user'];

/*=====================================================================
  Main Section
 =====================================================================*/ 

// see which user we are working with ...

if ( !empty($_REQUEST['uidToEdit']) )
{
    $user_id = $_REQUEST['uidToEdit'];
}
else
{
    header("Location: adminusers.php");
}

$user_data = user_initialise();
$user_data['is_admin'] = false;

if ( isset($_REQUEST['applyChange']) )  //for formular modification
{

    // get params form the form
    if ( isset($_REQUEST['lastname']) )      $user_data['lastname'] = trim($_REQUEST['lastname']);
    if ( isset($_REQUEST['firstname']) )     $user_data['firstname'] = trim($_REQUEST['firstname']);
    if ( isset($_REQUEST['officialCode']) )  $user_data['officialCode'] = trim($_REQUEST['officialCode']);
    if ( isset($_REQUEST['username']) )      $user_data['username'] = trim($_REQUEST['username' ]);
    if ( isset($_REQUEST['password']) )      $user_data['password'] = trim($_REQUEST['password']);
    if ( isset($_REQUEST['password_conf']) ) $user_data['password_conf'] = trim($_REQUEST['password_conf']);
    if ( isset($_REQUEST['email']) )         $user_data['email'] = trim($_REQUEST['email']);
    if ( isset($_REQUEST['phone']) )         $user_data['phone'] = trim($_REQUEST['phone']);
    if ( isset($_REQUEST['status']) )        $user_data['status'] = (int) $_REQUEST['status'];

    if ( isset($_REQUEST['is_admin']) )      $user_data['is_admin'] = (bool) $_REQUEST['is_admin'];

    // validate forum params

    $messageList = user_validate_form_profile($user_data, $user_id);
    
    if ( count($messageList) == 0 )
    {

        // if no error update use setting 
        user_update ($user_id, $user_data); 

        // re-init the system to take new settings in account
        if ( $user_id == $_uid )
        {
            $uidReset = true;
            include($includePath.'/claro_init_local.inc.php');
        }

        $classMsg = 'success';
        $dialogBox = $langAppliedChange;

        // set user admin parameter
        if ( $user_data['is_admin'] )
        {
            user_add_admin($user_id);
        }
        else
        {
            user_delete_admin($user_id);
        }

        $messageList[] = $langAppliedChange;
    }
    else
    {
        // user validate form return error messages 
        $error = true;
    }

} // if apply changes

$user_data = user_get_data($user_id);
$user_data['is_admin'] = user_is_admin($user_id);

/*=====================================================================
 Display Section
 =====================================================================*/ 

$interbredcrump[]= array ("url" => $rootAdminWeb, "name" => $langAdministration);

if( isset($_REQUEST['cfrom']) && $_REQUEST['cfrom'] == "ulist")
{
    $interbredcrump[]= array ("url" => $rootAdminWeb."adminusers.php", "name" => $langListUsers);
}

$htmlHeadXtra[] =
            "<script>
            function confirmation (name)
            {
                if (confirm(\"".clean_str_for_javascript($langAreYouSureToDelete)." \"+ name + \"? \"))
                    {return true;}
                else
                    {return false;}
            }
            </script>";

// Disdplay header
include($includePath.'/claro_init_header.inc.php');

// Display tool title
echo claro_disp_tool_title($nameTools);

// Display Forms or dialog box(if needed)
if ( count($messageList) > 0 )
{
    claro_disp_message_box(implode('<br />', $messageList));
}

// Display "form and info" about the user

user_display_form_admin_user_profile($user_data);

// Display tools link :

echo '<a class="claroCmd" href="adminuserdeleted.php?uidToEdit=' . $user_id . '&cmd=delete" onClick="return confirmation(\''.clean_str_for_javascript($langAreYouSureToDelete . ' ' . $user_data['username']) . '\');" ><img src="' . $imgRepositoryWeb . 'deluser.gif" /> ' . $langDeleteUser . '</a>' 
    . ' | '
    . '<a class="claroCmd" href="../auth/courses.php?cmd=rqReg&amp;uidToEdit=' . $user_id . '&amp;fromAdmin=settings&amp;category=" >' . $langRegisterUser . '</a>'
    . '| '
    . '<a class="claroCmd" href="../auth/lostPassword.php?Femail=' . urlencode($user_data['email']) . '&amp;searchPassword=1" >' . $langSendToUserAccountInfoByMail . '</a>';

if ( isset($cfrom) && $cfrom == 'ulist' ) // if we come form user list, we must display go back to list
{
    echo ' | <a class="claroCmd" href="adminusers.php" >' . $langBackToUserList . '</a>' ;
}

// display footer

include($includePath."/claro_init_footer.inc.php");
?>
