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
$cidReset = TRUE;$gidReset = TRUE;$tidReset = TRUE;
require '../inc/claro_init_global.inc.php';
claro_unquote_gpc();

//SECURITY CHECK
if (!$is_platformAdmin) claro_disp_auth_form();

if(file_exists($includePath.'/currentVersion.inc.php')) include ($includePath.'/currentVersion.inc.php');
include($includePath."/lib/admin.lib.inc.php");

//------------------------------------------------------------------------------------------------------------------------
//  USED SESSION VARIABLES
//------------------------------------------------------------------------------------------------------------------------
// deal with session variables clean session variables from previous search


unset($_REQUEST['admin_user_letter']);
unset($_REQUEST['admin_user_search']);
unset($_REQUEST['admin_user_firstName']);
unset($_REQUEST['admin_user_lastName']);
unset($_REQUEST['admin_user_userName']);
unset($_REQUEST['admin_user_mail']);
unset($_REQUEST['admin_user_action']);
unset($_REQUEST['admin_order_crit']);


//declare needed tables
$tbl_mdb_names = claro_sql_get_main_tbl();
//$tbl_course           = $tbl_mdb_names['course'           ];
//$tbl_rel_course_user  = $tbl_mdb_names['rel_course_user'  ];
$tbl_course_nodes     = $tbl_mdb_names['category'         ];
//$tbl_user             = $tbl_mdb_names['user'             ];
//$tbl_class            = $tbl_mdb_names['class'            ];
//$tbl_rel_class_user   = $tbl_mdb_names['rel_class_user'   ];

$tbl_course_nodes      = $tbl_course_nodes;

// Deal with interbredcrumps  and title variable

$interbredcrump[]= array ("url"=>$rootAdminWeb, "name"=> $langAdministration);
$nameTools = $langSearchUserAdvanced;

// Search needed info in db to creat the right formulaire

$sql_searchfaculty = 'SELECT * 
                      FROM `'.$tbl_course_nodes.'`
                      ORDER BY `treePos`';
$arrayFaculty=claro_sql_query_fetch_all($sql_searchfaculty);

//retrieve needed parameters from URL to prefill search form

if (isset($_REQUEST['action']))    $action    = $_REQUEST['action'];    else $action = "";
if (isset($_REQUEST['lastName']))  $lastName  = $_REQUEST['lastName'];  else $lastName = "";
if (isset($_REQUEST['firstName'])) $firstName = $_REQUEST['firstName']; else $firstName = "";
if (isset($_REQUEST['userName']))  $userName  = $_REQUEST['userName'];  else $userName = "";
if (isset($_REQUEST['mail']))      $mail      = $_REQUEST['mail'];      else $mail = "";

//header and bredcrump display

include($includePath."/claro_init_header.inc.php");

//tool title

echo claro_disp_tool_title($nameTools." : ");

?>

<form action="adminusers.php" method="GET" >
<table border="0">
	<tr>
		<td align="right">
			<label for="lastName"><?php echo $langLastName?></label>
			: <br>
		</td>
		<td>
			<input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($lastName); ?>"/>
		</td>
	</tr>

	<tr>
		<td align="right">
			<label for="firstName"><?php echo $langFirstName?></label>
			: <br>
		</td>
		<td>
			<input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($firstName) ?>"/>
		</td>
	</tr>
	
	<tr>
		<td align="right">
			<label for="userName"><?php echo $langUserName ?></label> 
			:  <br>
		</td>
		<td>
			<input type="text" name="userName" id="userName" value="<?php echo htmlspecialchars($userName); ?>"/>
		</td>
	</tr>

	<tr>
		<td align="right">
			<label for="mail"><?php echo $langEmail ?></label> 
			: <br>
		</td>
		<td>
			<input type="text" name="mail" id="mail" value="<?php echo htmlspecialchars($mail); ?>"/>
		</td>
	</tr>

<tr>
  <td align="right">
   <label for="action"><?php echo $langAction?></label> : <br>
  </td>
  <td>
    <select name="action" id="action">
        <option value="followcourse" <?php if ($action=="followcourse") echo "selected";?>><?php echo $langRegStudent?></option>
        <option value="createcourse" <?php if ($action=="createcourse") echo "selected";?>><?php echo $langCreateCourse?></option>
        <option value="plateformadmin" <?php if ($action=="plateformadmin") echo "selected";?>><?php echo $langPlatformAdministrator?></option>
    </select>
  </td>
</tr>

<tr>
    <td>

    </td>
    <td>
        <input type="submit" class="claroButton" value="<?php echo $langSearchUser?>" >
    </td>
</tr>
</table>
</form>
<?php
include($includePath."/claro_init_footer.inc.php");

?>
