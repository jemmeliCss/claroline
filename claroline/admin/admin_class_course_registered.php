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

require '../inc/claro_init_global.inc.php';
include $includePath."/lib/admin.lib.inc.php";
include $includePath."/lib/class.lib.php";
include $includePath."/lib/user.lib.php";
include $includePath.'/conf/user_profile.conf.php'; // find this file to modify values.

// Security check
if ( ! $_uid ) claro_disp_auth_form();
if ( ! $is_platformAdmin ) claro_die($langNotAllowed);

//bredcrump

$nameTools=$langClassRegistered;
$interbredcrump[]= array ("url"=>$rootAdminWeb, "name"=> $langClassRegistered);

/*
 * DB tables definition
 */
$tbl_mdb_names = claro_sql_get_main_tbl();
$tbl_user                  = $tbl_mdb_names['user'];
$tbl_course                = $tbl_mdb_names['course'];
$tbl_course_user           = $tbl_mdb_names['rel_course_user'];

$tbl_class                 = $tbl_mdb_names['user_category'];
$tbl_class_user            = $tbl_mdb_names['user_rel_profile_category'];

include($includePath.'/claro_init_header.inc.php');

//find info about the class

$sqlclass = "SELECT * 
             FROM `".$tbl_class."` 
             WHERE `id`='". (int)$_SESSION['admin_user_class_id']."'";
list($classinfo) = claro_sql_query_fetch_all($sqlclass);

//------------------------------------
// Execute COMMAND section
//------------------------------------

if ( isset($_REQUEST['cmd']) ) $cmd = $_REQUEST['cmd'];
else                           $cmd = null;

if (isset($cmd) && $is_platformAdmin)
{
    if ($cmd=="exReg")
    {
        $resultLog = register_class_to_course($_REQUEST['class'], $_REQUEST['course']);
        $outputResultLog = '';    

        if ( isset($resultLog['OK']) && is_array($resultLog['OK']) )
        {
            foreach($resultLog['OK'] as $userSubscribed)
            {
                $outputResultLog .= '[<font color="green">OK</font>] '.sprintf($lang_p_s_s_has_been_sucessfully_registered_to_the_course_p_name_firstname,$userSubscribed['prenom'],$userSubscribed['nom']).'<br>';
            }
        }

        if ( isset($resultLog['KO']) && is_array($resultLog['KO']) )
        {
            foreach($resultLog['KO'] as $userSubscribedKo)
            {
                $outputResultLog .= '[<font color="red">KO</font>] '.sprintf($lang_p_s_s_has_not_been_sucessfully_registered_to_the_course_p_name_firstname,$userSubscribedKo['prenom'],$userSubscribedKo['nom']).'<br>';
            }
        }
    }

}

//------------------------------------
// DISPLAY
//------------------------------------


// Display tool title

echo claro_disp_tool_title($langClassRegistered." : ".$classinfo['name']);

//Display Forms or dialog box(if needed)
    
// display log
if ( !empty($outputResultLog) )
{
    $dialogBox = $outputResultLog;
}

if ( !empty($dialogBox) )
{
    echo claro_disp_message_box($dialogBox);
}

// display TOOL links :

echo '<p><a class="claroCmd" href="index.php">' . $langBackToAdmin . '</a> | ';
echo '<a class="claroCmd" href="' . 'admin_class_user.php?class=' . $classinfo['id'] . '">' . $langBackToClassMembers . '</a> | ';
echo '<a class="claroCmd" href="' . $clarolineRepositoryWeb . 'auth/courses.php?cmd=rqReg&fromAdmin=class' . '">' . $langClassRegisterWholeClassAgain . '</a></p>';

// display footer

include($includePath."/claro_init_footer.inc.php");
?>
