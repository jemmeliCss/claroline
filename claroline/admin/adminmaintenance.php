<?php # $Id$
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
$langFile = "admin";
$cidReset = TRUE;$gidReset = TRUE;$tidReset = TRUE;
require '../inc/claro_init_global.inc.php';

$is_allowedToAdmin 	= $is_platformAdmin || $PHP_AUTH_USER;
if (!$is_allowedToAdmin) claro_disp_auth_form();


include($includePath."/lib/debug.lib.inc.php");
include($includePath."/lib/admin.lib.inc.php");

//SECURITY CHECK

@include ($includePath."/installedVersion.inc.php");

// Deal with interbredcrumps

$interbredcrump[]= array ("url"=>$rootAdminWeb, "name"=> $langAdministration);
$nameTools = $langMaintenance;

$dateNow 			= claro_disp_localised_date($dateTimeFormatLong);
/*
// make here some  test
// $checkMsgs[] = array("level" => 5, "target" => "test 1 ", "content" => "this is  just  a  warning test 1 ");
// ----- is install visible ----- begin
 if (file_exists("../install/index.php") && !file_exists("../install/.htaccess"))
 {
	 $controlMsg["warning"][]="install is not protected";
 }
// ----- is install visible ----- end
*/
include($includePath."/claro_init_header.inc.php");
claro_disp_tool_title($nameTools);
claro_disp_msg_arr($controlMsg);
?>
<table align="center" border="0" width="80%">
  <tr valign="top" height="50">
    <td width="50%">
      <a href="<?php echo $clarolineRepositoryWeb ?>calendar/admincourse.php">
      <img src="<?php echo $clarolineRepositoryWeb ?>img/agenda.gif" alt="agenda" border="0"></a>
      <a href="technical/config.php"><?php echo $langConfiguration?> </a>
    </td>
    <td width="50%">
      <a href="maintenance/index.php"><img src="<?php echo $clarolineRepositoryWeb ?>img/statistiques.gif" alt="" border="0"></a>
      <a href="maintenance/index.php"><?php echo $langUpgrade?></a><br>
    </td>
  </tr>
 <tr valign="top" height="50">
    <td width="50%">
      <a href="adminusers.php">
      <img src="<?php echo $clarolineRepositoryWeb ?>img/group.gif" alt="group" border="0"></a>
      <a href="adminusers.php"><?php echo $langTraduction?> </a>
    </td>
  </tr>
</table>
<?php
include($includePath."/claro_init_footer.inc.php");
?>