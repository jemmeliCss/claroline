<?php # $Id$

/**
  * initialize conf settings
  * try to read  old values in old conf files
  * build new conf file content with these settings
  * write it.
*/

DEFINE ("DISPLAY_WELCOME_PANEL",1);
DEFINE ("DISPLAY_RESULT_ERROR_PANEL",2);
DEFINE ("DISPLAY_RESULT_SUCCESS_PANEL",3);

$display = DISPLAY_WELCOME_PANEL;

/* 
 * include file
*/

$newIncludePath = "../../inc/";
$oldIncludePath = "../../include/";
	
include ($newIncludePath."installedVersion.inc.php");
include ($newIncludePath."/lib/config.lib.inc.php");
	
$thisClarolineVersion = $version_file_cvs;

/* lang var */

include ("../../lang/english/complete.lang.php");
//include ("../../lang/english/claroline_admin_maintenance.lang.php");

$error = 0;

if ($_REQUEST['cmd'] == 'run')
{
	/**
	 Find config file.
	*/
	
	if ($fileSource=="") 
	{
		$fileSource 		= $newIncludePath."conf/claro_main.conf.php";
	}
	if (!file_exists($fileSource))
	{
		$fileSource 		= $oldIncludePath.""."config.inc.php";
	}
	if (!file_exists($fileSource))
	{
		$fileSource 		= "../../include/config.php";
	}
	if (!file_exists($fileSource))
	{
		$fileSource 		= $oldIncludePath.""."config.php";
	}
	if (!file_exists($fileSource))
	{
		$fileSource 		= $oldIncludePath.""."config.inc.php.dist";
	}

	/**
	 Target, temp and backup file
        */

	if ($fileTarget=="")
	{
		$fileTarget 		= $newIncludePath ."conf/claro_main.conf.php";
	}
	
	$fileTemp 	= tempnam ( ".", "config_work");
	$fileBackup 	= $newIncludePath ."conf/claro_main.conf.".date("Y-z-B").".bak.php";
	
	/**
	 Initialise conf var
	*/
	
	// MYSQL
	$dbHost 			= "localhost";
	$dbLogin 			= "root";
	$dbPass				= "'.$dbPass.'";
	
	$dbNamePrefixSkel = "claro150b";
	
	$mainDbName	= $dbNamePrefixSkel."Main";
	$statsDbName    = $dbNamePrefixSkel."Tracking";
	$pmaDbName	= $dbNamePrefixSkel."PMA";
	$dbNamePrefix	= $dbNamePrefixSkel."_";
	
	unset($dbNamePrefixSkel);
	
	$is_trackingEnabled	= '.trueFalse($is_trackingEnabled).';
	$singleDbEnabled	= '.trueFalse($singleDbEnabled).'; // DO NOT MODIFY THIS
	$courseTablePrefix	= "'.($singleDbEnabled?'crs_':'').'"; // IF NOT EMPTY, CAN BE REPLACED BY ANOTHER PREFIX, ELSE LEAVE EMPTY
	$dbGlu				= "'.($singleDbEnabled?'_':'`.`').'"; // DO NOT MODIFY THIS
	$mysqlRepositorySys = null ;
		
	// extract the path to append to the url if Claroline is not installed on the web root directory
	
	$rootWeb 		= 	"http://".$SERVER_NAME.$urlAppendPath."/";
	$urlAppend		=	ereg_replace ("/claroline/admin/maintenance/".basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['PHP_SELF']);
	$rootSys		=	realpath("../..")."/";
	
	$siteName		=	"My campus";
	
	$administrator["name"]	=	"John Doe";
	$administrator["phone"]	=	"(000) 001 02 03";
	$administrator["email"]	=	$_SERVER['ADMIN'];
	
	
	$institution["name"]	= "My Univ";
	$institution["url"]	= "http://www.google.com/";
	
	// param for new and future features
	$checkEmailByHashSent 		= false;
	$ShowEmailnotcheckedToStudent 	= true;
	$userMailCanBeEmpty 		= true;
	$userPasswordCrypted 		= false;
	$allowSelfReg			= true;
	$allowSelfRegProf		= true;
	
	$platformLanguage 	= 	"english";
	
	/**
	 include old conf file
	*/
		
	@include ($fileSource); // read Values in sources
	
	$rootWeb	= $urlServer;
	$rootSys 	= $webDir;
	$language 	= $platformLanguage ;
	$dbHost		= $mysqlServer;
	$dbLogin 	= $mysqlUser;
	$dbPass		= $mysqlPassword;
	$dbNamePrefix	= $mysqlPrefix;
	$mainDbName	= $mysqlMainDb;
	
	if ($statsDbName=="") $statsDbName = $mainDbName_stats;
	
	$administrator["name"]		= $administratorSurname." ".$administratorName;
	$administrator["phone"]		= $telephone;
	$educationManager["name"]	= $educationManager;
	$institution["name"]		= $Institution;
	$institution["url"]		= $InstitutionUrl;
	
	$pmaDbName			= $mainDbName;
	$mysqlRepositorySys 		= str_replace("\\","/",realpath($mysqlRepositorySys)."/");
	
	@include ($fileSource); // read Values in sources
	
	// force to mark that upgrade is runned
	$clarolineVersion = $version_file_cvs;
	
	/**
	 Create new config file
	*/
	
	$stringConfig=str_replace("\r","",'<?php
	
	# CLAROLINE version '.$clarolineVersion.'
	# File generated by /admin/maintenance/upgrade/index.php script - '.date("r").'
	
	//----------------------------------------------------------------------
	// CLAROLINE
	//----------------------------------------------------------------------
	// Copyright (c) 2001-2004 Universite catholique de Louvain (UCL)
	//----------------------------------------------------------------------
	// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
	// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
	// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
	//----------------------------------------------------------------------
	// Authors: see \'credits\' file
	//----------------------------------------------------------------------
	
	/***************************************************************
	*           CONFIG OF VIRTUAL CAMPUS
	****************************************************************
	GOAL
	****
	List of variables to be modified by the campus site administrator.
	File has been CHMODDED 0444 by install.php.
	CHMOD 0666 (Win: remove read-only file property) to edit manually
	*****************************************************************/
	
	/*
	
	******************
	** WARNING !!!  **
	******************
	
	This  file  would  parsed.
	A variable must be in one line.
	and they doesn\'t actually have an ; in value of a variable
	
	**********************************************************************/
	
	// This file was generate by script /install/index.php
	// on '.date("r").'
	// REMOTE_ADDR : 		'.$REMOTE_ADDR.' = '.gethostbyaddr($REMOTE_ADDR).'
	// REMOTE_HOST :		'.$REMOTE_HOST.'
	// REMOTE_PORT : 		'.$REMOTE_PORT.'
	// REMOTE_USER : 		'.$REMOTE_USER.'
	// REMOTE_IDENT :	 	'.$REMOTE_IDENT.'
	// HTTP_USER_AGENT : 		'.$HTTP_USER_AGENT.'
	// SERVER_NAME :		'.$SERVER_NAME.'
	// HTTP_COOKIE :		'.$HTTP_COOKIE.'
	
	$rootWeb 	= "'.$rootWeb.'";
	$urlAppend	= "'.$urlAppend.'";
	$rootSys	= "'.$rootSys.'" ;
	
	// MYSQL
	$dbHost 	= "'.$dbHost.'";
	$dbLogin 	= "'.$dbLogin.'";
	$dbPass		= "'.$dbPass.'";
	
	$mainDbName	= "'.$mainDbName.'";
	$statsDbName	= "'.$statsDbName.'";
	$pmaDbName	= "'.$pmaDbName.'";
	$dbNamePrefix	= "'.$dbNamePrefix.'"; // prefix all created base (for courses) with this string
	
	$is_trackingEnabled	= '.trueFalse($is_trackingEnabled).';
	$singleDbEnabled	= '.trueFalse($singleDbEnabled).'; // DO NOT MODIFY THIS
	$courseTablePrefix	= "'.($singleDbEnabled?'crs_':'').'"; // IF NOT EMPTY, CAN BE REPLACED BY ANOTHER PREFIX, ELSE LEAVE EMPTY
	$dbGlu				= "'.($singleDbEnabled?'_':'`.`').'"; // DO NOT MODIFY THIS
	$mysqlRepositorySys = "'.str_replace("\\","/",realpath($mysqlRepositorySys)."/").'";
	
	$clarolineRepositoryAppend  = "claroline/";
	$coursesRepositoryAppend	= "";
	$rootAdminAppend		= "admin/";
	$phpMyAdminAppend		= "mysql/";
	$phpSysInfoAppend		= "sysinfo/";
	$clarolineRepositorySys		= $rootSys.$clarolineRepositoryAppend;
	$clarolineRepositoryWeb 	= $rootWeb.$clarolineRepositoryAppend;
	$coursesRepositorySys		= $rootSys.$coursesRepositoryAppend;
	$coursesRepositoryWeb		= $rootWeb.$coursesRepositoryAppend;
	$rootAdminSys			= $clarolineRepositorySys.$rootAdminAppend;
	$rootAdminWeb			= $clarolineRepositoryWeb.$rootAdminAppend;
	$phpMyAdminWeb			= $rootAdminWeb.$phpMyAdminAppend;
	$phpMyAdminSys			= $rootAdminSys.$phpMyAdminAppend;
	$phpSysInfoWeb			= $rootAdminWeb.$phpSysInfoAppend;
	$phpSysInfoSys			= $rootAdminSys.$phpSysInfoAppend;
	$garbageRepositorySys		= "'.$garbageRepositorySys.'";
	
	//for new login module
	//uncomment these to activate ldap
	//$extAuthSource[\'ldap\'][\'login\'] = "./claroline/auth/ldap/login.php";
	//$extAuthSource[\'ldap\'][\'newUser\'] = "./claroline/auth/ldap/newUser.php";
	
	// Strings
	$siteName		= "'.$siteName.'";
	
	$administrator["name"]	= "'.$administrator["name"].'";
	$administrator["phone"]	= "'.$administrator["phone"].'";
	$administrator["email"]	= "'.$administrator["email"].'";
	
	$educationManager["name"]  = "'.$educationManager["name"].'";
	$educationManager["phone"] = "'.$educationManager["phone"].'";
	$educationManager["email"] = "'.$educationManager["email"].'";
	$institution["name"]	   = "'.$institution["name"].'";
	$institution["url"]	   = "'.$institution["url"].'";
	
	// param for new and future features
	$checkEmailByHashSent 		= '.trueFalse($checkEmailByHashSent).';
	$ShowEmailnotcheckedToStudent 	= '.trueFalse($ShowEmailnotcheckedToStudent).';
	$userMailCanBeEmpty 		= '.trueFalse($userMailCanBeEmpty).';
	$userPasswordCrypted		= '.trueFalse($userPasswordCrypted).';
	$allowSelfReg			= '.trueFalse($allowSelfReg).';
	$allowSelfRegProf		= '.trueFalse($allowSelfRegProf).';
	
	$platformLanguage = "'.$platformLanguage.'";
	
	$clarolineVersion = "'.$clarolineVersion.'";
	$versionDb 	  = "'.$versionDb.'";
	?>');
	
	// Main conf file
	
	$output = "<h3>Main configuration file</h3>";
	$output .= "<ul>\n";
	
	// Backup file if target file is the source file
	if ( $fileTarget == $fileSource ) {

		$output .= "<li>" . sprintf ("Back-up old configuration file in: <code>%s</code>",$fileBackup) ;
		if (!@copy($fileTarget, $fileBackup) )
		{
			$output .= "<br />\n";
			$output .= sprintf ("<span class=\"warning\"><code>%s</code> copy failed !</span>",$fileTarget);
		}
		$output .= "</li>\n";
		// change permission
		@chmod( $fileBackup, 600 );
		@chmod( $fileBackup, 0600 );
	}
	
	// Temporary file
	$output .=  sprintf ("<li>Temporary file: <code>%s</code>",$fileTemp) ;
	
	if (!($fd=fopen($fileTemp, "w")))
	{
		$output .= "<br />\n";
		$output .= sprintf ("<span class=\"warning\"><code>%s</code> write failed !</span>",$fileTemp);
		$error = 1;
	}
	else
	{
		fwrite($fd, $stringConfig);
		fclose($fd);
		@unlink($fileTarget);
	}
	$output .= "</li>\n";
	
	if (!$error)
	{
		// Save new configuration file
		$output .=  "<li>Saved as: <code>".$fileTarget ."</code>";
		
		if ( !@rename($fileTemp, $fileTarget) )
		{
			$error = 1;
			$output .= "<br />\n";
			$output .= "<span class=\"warning\">Rename <code>" . $fileTemp ."</code>" ;
			$output .= " to <code>" . $fileTarget ."</code> failed !</span>" ;
		}
		else
		{
			@chmod( $fileTarget, 766 );
			@chmod( $fileTarget, 0766 );
		}
		$output .= "</li>\n";
	}
	
	$output .= "</ul>\n";
	
	/**
	 * Config file to undist
	 */
	
	$arr_file_to_undist = 
	array (
		$newIncludePath."conf/add_course.conf.php",
		$newIncludePath."conf/admin.usermanagement.conf.php",
		$newIncludePath."conf/agenda.conf.inc.php",
		$newIncludePath."conf/announcement.conf.inc.php",
		$newIncludePath."conf/course_info.conf.php",
		$newIncludePath."conf/export.conf.php",
		$newIncludePath."conf/group.conf.php",
		$newIncludePath."conf/group.document.conf.php",
		$newIncludePath."conf/index.conf.inc.php",
		$newIncludePath."conf/profile.conf.inc.php",
		$newIncludePath."conf/user.conf.php",
		$newIncludePath."conf/work.conf.inc.php",
		$newIncludePath."../../textzone_top.inc.html",
		$newIncludePath."../../textzone_right.inc.html"

	);
	
	$output .="<h3>Others conf files</h3>\n";
	$output .="<ul>\n";
	foreach ($arr_file_to_undist As $undist_this)
	{
		$output .="<li>Conf file: <code>".basename ($undist_this)."</code>";
		if (claro_undist_file($undist_this))
		{
			$output .=" added";
		}
		else
		{
			$output .=" not changed.";
		};
		$output .="</li>\n";
	}
	$output .= "</ul>\n";
	
	if (!$error)
	{
		$display = DISPLAY_RESULT_SUCCESS_PANEL;
	}
	else
	{
		$display = DISPLAY_RESULT_ERROR_PANEL;
	}
	
} // end if run 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/HTML; charset=iso-8859-1"  />
  <title>-- Claroline upgrade -- version <?php echo $clarolineVersion ?></title>  
  <link rel="stylesheet" type="text/css" href="upgrade.css" media="screen" />
  <style media="print" >
    .notethis {	border: thin double Black;	margin-left: 15px;	margin-right: 15px;}
  </style>
</head>

<body bgcolor="white" dir="<?php echo $text_dir ?>">

<center>

<table cellpadding="10" cellspacing="0" border="0" width="650" bgcolor="#E6E6E6">
<tbody>
<tr bgcolor="navy">
<td valign="top" align="left">
<div id="header">
<?php
 echo sprintf ("<h1>Claroline (%s) - upgrade</h1>",$thisClarolineVersion);
?>
</div>
</td>
</tr>
<!--
<tr bgcolor="#E6E6E6">
<td valign="top"align="left">
<div id="menu">
<?php
 echo sprintf("<p><a href=\"upgrade.php\">%s</a> - %s</p>", "upgrade", $langUpgradeStep1);
?>
</div>
</td>
</tr>
-->
<tr valign="top" align="left">
<td>

<div id="content">	

<?php

switch ($display)
{
	case DISPLAY_WELCOME_PANEL: 
                echo sprintf ("<h2>%s</h2>",$langUpgradeStep1);
                echo $langIntroStep1;
		echo "<center>" . sprintf ($langLaunchStep1, $_SERVER['PHP_SELF']."?cmd=run") . "</center>";
		break;
        case DISPLAY_RESULT_ERROR_PANEL:
                echo sprintf ("<h2>%s</h2>",$langUpgradeStep1 . " - " . $langFailed);
                echo $output;
                break;
                
	case DISPLAY_RESULT_SUCCESS_PANEL:

                echo sprintf ("<h2>%s</h2>",$langUpgradeStep1 . " - " . $langSucceed);

                echo "<p>Here are the main settings that has been recorded in claroline/inc/conf/claro_main.conf.php</p>";
                
                // display the main setting of the new configuration file.
                
                echo "<fieldset>
		<legend>Database authentification</legend>
                <p>Host: $dbHost<br />
		Username: $dbLogin<br />
		Password: ".(empty($dbPass)?"--empty--":$dbPass)."</p>
		</fieldset>
                <br />
                <fieldset>
		<legend>Claroline databases</legend>
                <p>Course database Prefix: ".($dbNamePrefix?$dbNamePrefix:$langNo)."<br />
                Main database Name: $mainDbName <br />
		Statistics and Tracking database Name: $statsDbName <br />
		PhpMyAdmin Extention database Name: $pmaDbName <br />
		Enable Single database: ".($singleDbEnabled?$langYes:$langNo)."</p>
		</fieldset>
                <br />
                <fieldset>
                    <legend>Administrator</legend>
                    Name: ".$administrator["name"]."<br />
                    Mail: ".$administrator["email"]."<br />
		</fieldset>
                <br />
		<fieldset>
                 <legend>Campus</legend>
                 <p>
                    Language: $platformLanguage<br />
                    Your organisation: ".$institution["name"]."<br />
                    URL of this organisation: ".$institution["url"]."
                </p>
		</fieldset>
                <br />
		<fieldset>
                    <legend>Config</legend>
                    <p>
                    Enable Tracking: ".($is_trackingEnabled?$langYes:$langNo)."<br />
                    Self registration allowed: ".($allowSelfReg?$langYes:$langNo)."<br />
                    Self course creator allowed : ".($allowSelfRegProf?$langYes:$langNo)."<br />
                    Encrypt user passwords in database: " .($userPasswordCrypted?$langYes:$langNo)."
                    </p>
                </fieldset>";
                
                echo "<div align=\"right\">" . sprintf($langNextStep,"upgrade_main_db.php") . "</div>";
                
		break;
}
 
?>

</div>
</td>
</tr>
</tbody>
</table>

</body>
</html>
