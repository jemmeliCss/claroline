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
$cidReset=true;
$gidReset=true;
require '../inc/claro_init_global.inc.php';

@include ($includePath."/installedVersion.inc.php");
include($includePath."/lib/admin.lib.inc.php");

//SECURITY CHECK

if (!$is_platformAdmin) claro_disp_auth_form();

//------------------------------------------------------------------------------------------------------------------------
//  USED SESSION VARIABLES
//------------------------------------------------------------------------------------------------------------------------
// clean session of possible previous search information. : COURSE

session_unregister('admin_course_code');
session_unregister('admin_course_letter');
session_unregister('admin_course_search');
session_unregister('admin_course_intitule');
session_unregister('admin_course_category');
session_unregister('admin_course_language');
session_unregister('admin_course_access');
session_unregister('admin_course_subscription');
session_unregister('admin_course_order_crit');

// deal with session variables clean session variables from previous search : USER


session_unregister('admin_user_letter');
session_unregister('admin_user_search');
session_unregister('admin_user_firstName');
session_unregister('admin_user_lastName');
session_unregister('admin_user_userName');
session_unregister('admin_user_mail');
session_unregister('admin_user_action');
session_unregister('admin_order_crit');


// clean session if we come from a course

session_unregister('_cid');
unset($_cid);

//----------------------------------
// DISPLAY
//----------------------------------

// Deal with interbredcrumps  and title variable


$nameTools = $langAdministration;


include($includePath."/lib/debug.lib.inc.php");
$dateNow             = claro_disp_localised_date($dateTimeFormatLong);
$is_allowedToAdmin     = $is_platformAdmin;


// ----- is install visible ----- begin
if ( file_exists("../install/index.php") && ! file_exists("../install/.htaccess"))
{
     $controlMsg = '<p class="highlight"><b>Notice :</b> The directory containing your Claroline installation process (<code>claroline/install/</code>) is still browsable by the web. It means anyone can reinstall Claroline and crush your previous installation. We highly recommend to protect this directory or to remove it from your server</p>';
}
// ----- is install visible ----- end


include($includePath."/claro_init_header.inc.php");
claro_disp_tool_title($nameTools);

if ($controlMsg) echo '<blockquote>'.$controlMsg.'</blockquote>';


?>
<h4><?php echo $langUsers?></h4>
<ul>
<li>
<form name="searchUser" action="adminusers.php" method="GET" >
<label for="search_user"><?php echo $langSearchUser?></label> 
: 
<input name="search" id="search_user"> 
<input type="submit" value=" Ok ">
&nbsp;&nbsp;[<a class="claroCmd" href="advancedUserSearch.php"><?php echo $langAdvanced?></a>]
</form>
</li>
<li>
<a href="adminaddnewuser.php"><?php echo $langCreateUser?></a>
</li>
<li>
<a href="admin_class.php"><?php echo $langManageClasses?></a>
</li>
<li>
<a href="../user/AddCSVusers.php?AddType=adminTool"><?php echo $langAddCSVUsers?></a>
</li>
</ul>

<h4><?php echo $langCourses?></h4>
<ul>
<li>
<form name="searchCourse" action="admincourses.php" method="GET" >
<label for="search_course"><?php echo $langSearchCourse?></label> : <input name="search" id="search_course"> <input type="submit" value=" Ok ">
&nbsp; &nbsp;[<a class="claroCmd" href="advancedCourseSearch.php"><?php echo $langAdvanced?></a>]
</form>
</li>
<li>
<a href="../create_course/add_course.php?fromAdmin=yes"><?php echo $langCreateCourse?></a><br>
</li>
<li>
<a href="admincats.php"><?php echo $langManageCourseCategories?></a>
</li>
</ul>

<h4><?php echo $langPlatform?></h4>
<ul>
<li>
<a href="tool/config_list.php"><?php echo $langConfiguration?></a>
</li>
<li>
<a href="managing/editFile.php"><?php echo $langHomePageTextZone ?></a>
</li>
<li>
<a href="campusLog.php"><?php echo $langViewPlatFormStatistics?></a>
</li>
<li>
<a href="campusProblem.php"><?php echo $langViewPlatFormError ?></a>
</li>
<li>
<a href="upgrade/index.php"><?php echo $langUpgrade?></a>
</li>
</ul>

<?php

if ( defined('CLAROLANG') && CLAROLANG == 'TRANSLATION')
{
?>
    <h4><?php echo $langSDK?></h4>

    <p><img src="<?php echo 'xtra/sdk/lang/language.png'?>" style="vertical-align: middle;" alt="" /> <a href="xtra/sdk/translation_index.php"><?php echo $langTranslationTools?></a></p>

<?php
}
?>

<?php
include($includePath."/claro_init_footer.inc.php");
?>
