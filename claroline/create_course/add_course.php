<?php # $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
 */
/**
 * COURSE SITE CREATION TOOL
 * GOALS
 * *******
 * Allow professors and administrative staff to create course sites.
 * This big script makes, basically, 6 things:
 *     1. Create a database whose name=course code (sort of course id)
 *     2. Create tables in this base and fill some of them
 *     3. Create a www directory with the same name as the db name
 *     4. Add the course to the main icampus/course table
 *     5. Check whether the course code is not already taken.
 *     6. Associate the current user id with the course in order to let 
 *        him administer it.
 * 
 * One of the functions of this script is to merge the different 
 * Open Source Tools used in the courses (statistics by EzBoo,
 * forum by phpBB...) under one unique user session and one unique
 * course id.
 * ******************************************************************
 */
/*

List of Events
	- can't create course
		show displayNotForU and exit
	-

List  of  views
	- displayNotForU
		the  user  is not allowed to  use this script
	- displayWhatAdd
		here  user select  what take in the archive
	- displayCourseRestore
		User  can select source file to add course (that's must be a file  build with export)
	- displayCoursePropertiesForm
		User  can enter/edit  parameter  for the  new  course. If  they use an archive,
		value are proposed but can be edited
	- displayCourseAddResult
		New course is added.  Show  success message.
*/


$langFile = "create_course";
include('../inc/claro_init_global.inc.php');

//// Config tool
include($includePath."/conf/add_course.conf.php");
//// LIBS
include($includePath."/lib/text.lib.php");
include($includePath."/lib/add_course.lib.inc.php");
include($includePath."/lib/debug.lib.inc.php");
include($includePath."/lib/fileManage.lib.php");
include($includePath."/conf/course_info.conf.php");
$nameTools = $langCreateSite;

$TABLECOURSE 		= "$mainDbName`.`cours";
$TABLECOURSDOMAIN	= "$mainDbName`.`faculte";
$TABLEUSER			= "$mainDbName`.`user";
$TABLECOURSUSER 	= "$mainDbName`.`cours_user";
$TABLEANNOUNCEMENTS	= "announcement";
$can_create_courses = (bool) ($is_allowedCreateCourse);
$coursesRepositories = $rootSys;

if (empty($valueEmail)) $valueEmail = $_user['mail'];

//// Starting script
$displayNotForU = FALSE;
if (!$can_create_courses)
// if (!$is_platformAdmin)
{
	$displayNotForU = TRUE;
}
else
{
	if (	$sendByUploadAivailable
			|| $sendByLocaleAivailable
			|| $sendByHTTPAivailable
			|| $sendByFTPAivailable
		)
	{
		$displayWhatAdd = TRUE;
	}
	else
	{
		$displayCoursePropertiesForm 	= TRUE;
		$valueTitular					= $_user['firstName']." ".$_user['lastName'];
		$valueLanguage 					= $platformLanguage;
	}

	if (isset($HTTP_POST_VARS["fromWhatAdd"]))
	{
		$displayWhatAdd = FALSE;

		if ($HTTP_POST_VARS["whatAdd"] == "newCourse")
		{
			$displayCoursePropertiesForm 	= TRUE;
			$valueTitular					= $_user['firstName']." ".$_user['lastName'];
			$valueLanguage 					= $platformLanguage;
		}
		elseif ($HTTP_POST_VARS["whatAdd"] == "archive")
		{
			$displayCourseRestore 			= TRUE;
		}
		else
		{
			$displayWhatAdd 				= TRUE;
		}
	}
	elseif (isset($HTTP_POST_VARS["selectArchive"]))
	{
		$displayWhatAdd = FALSE;

// 1°   Keep the  zipFile and move it in $pathToStorgeArchiveBeforeUnzip
//		printVar($postFile, "PostFile");
//		printVar($HTTP_POST_FILES, "HTTP_POST_FILES");

		$pathToStorgeArchiveBeforeUnzip = $rootSys."claroline/tmp/".md5(uniqid(mt_rand().$_uid, true));
		mkpath($pathToStorgeArchiveBeforeUnzip);
		//debugIO($pathToStorgeArchiveBeforeUnzip);
		switch($HTTP_POST_VARS["typeStorage"])
		{
			case "upload" :
				$displayCoursePropertiesForm = TRUE;
				if (	$sendByUploadAivailable
						&& is_uploaded_file($postFile)
//						&& copy($HTTP_POST_FILES["postFile"]["tmp_name"], $pathToStorgeArchiveBeforeUnzip)
					)
				{
					$pathToStorgeArchiveBeforeUnzip = dirname($HTTP_POST_FILES["postFile"]["tmp_name"]);
					$nameOfZipFile = basename($HTTP_POST_FILES["postFile"]["tmp_name"]);
					$okToUnzip = TRUE;
				}
				else
				{
					// error during send, back to 1st Panel
					$displayWhatAdd = TRUE;
					$displayCoursePropertiesForm = FALSE;
					$okToUnzip = FALSE;
					break;
				}
				$displayCoursePropertiesForm = TRUE;
				break;
			case "local":
				// copy local file to $pathToStorgeArchiveBeforeUnzip
				$displayCoursePropertiesForm = TRUE;
				$okToUnzip = TRUE;
				if (	!$sendByLocaleAivailable
						&& file_exists($localArchivesRepository.trim($HTTP_POST_VARS["localFile"]))
						&& !copy($localArchivesRepository.trim($HTTP_POST_VARS["localFile"]), $pathToStorgeArchiveBeforeUnzip)
					)
				{
					$nameOfZipFile = basename(trim($HTTP_POST_VARS["localFile"]));
					// error during send, back to 1st Panel
					$displayWhatAdd = TRUE;
					$displayCoursePropertiesForm = FALSE;
					$okToUnzip = FALSE;
					break;
				}
				break;
			case "http":
				// copy downloaded file to $pathToStorgeArchiveBeforeUnzip
				$displayCoursePropertiesForm = TRUE;
				$okToUnzip = TRUE;
				if (!$sendByHTTPAivailable)
				{
					$displayWhatAdd = TRUE;
					$displayCoursePropertiesForm = FALSE;
					$okToUnzip = FALSE;
					break;
				}
				break;
			case "ftp":
				// copy downloaded file to $pathToStorgeArchiveBeforeUnzip
				$displayCoursePropertiesForm = TRUE;
				$okToUnzip = TRUE;
				if (!$sendByFTPAivailable)
				{
					$displayWhatAdd = TRUE;
					$displayCoursePropertiesForm = FALSE;
					$okToUnzip = FALSE;
					break;
				}

				break;
			default :
				$displayWhatAdd = TRUE;
				$okToUnzip = FALSE;
				// gloups
		}

//2° unzip archive in $pathToStorgeArchiveBeforeUnzip

		if ($okToUnzip)
		{
			checkArchive($pathToStorgeArchiveBeforeUnzip."/".$nameOfZipFile);

			$displayWhatAdd = FALSE;
			$displayCoursePropertiesForm = TRUE;
			$courseProperties = readPropertiesInArchive($pathToStorgeArchiveBeforeUnzip."/".$nameOfZipFile);
//			printVar($courseProperties," propriétés du cours");
			$showPropertiesFromArchive = TRUE;

			$valueSysId 		= $courseProperties["sysId"];

			$valueCode			= $courseProperties["officialCode"];
			$valueTitular		= $courseProperties["titular"];
			$valueIntitule		= $courseProperties["name"];
			$valueFacultyName	= $courseProperties["categoryName"];
			$valueFacultyCode	= $courseProperties["categoryCode"];
			$valueLanguage 		= $courseProperties["language"];

			$valueDescription 	= $courseProperties["description"];
			$valueDepartment	= $courseProperties["extLinkName"];
			$valueDepartmentUrl	= $courseProperties["extLinkUrl"];

			$valueScoreShow		= $courseProperties["scoreShow"];
			$valueVisibility	= $courseProperties["visibility"];

			$valueAdminCode		= $courseProperties["adminCode"];
			$valueDbName		= $courseProperties["dbName"];
			$valuePath			= $courseProperties["path"];
			$valueRegAllowed 	= $courseProperties["registrationAllowed"];

			$valueVersionDb		= $courseProperties["versionDb"];
			$valueVersionClaro	= $courseProperties["versionClaro"];
			$valueLastVisit		= $courseProperties["lastVisit"];
			$valueLastEdit 		= $courseProperties["lastEdit"];
			$valueExpire 		= $courseProperties["expirationDate"];
		}
	}
	elseif ($submitFromCoursProperties)
	{
		$wantedCode = $HTTP_POST_VARS["wantedCode"];
	//  function define_course_keys ($wantedCode, $prefix4all="", $prefix4baseName="", 	$prefix4path="", $addUniquePrefix =false,	$useCodeInDepedentKeys = TRUE	)
		$keys = define_course_keys ($wantedCode,"",$dbNamePrefix);
		$currentCourseCode		 = $keys["currentCourseCode"];
		$currentCourseId		 = $keys["currentCourseId"];
		$currentCourseDbName	 = $keys["currentCourseDbName"];
		$currentCourseRepository = $keys["currentCourseRepository"];
		$expirationDate 		= 	time() + $firstExpirationDelay;
	 if ($DEBUG) echo "[Code:",	$currentCourseCode,"][Id:",$currentCourseId,"][Db:",$currentCourseDbName	 ,"][Path:",$currentCourseRepository ,"]";

	//function prepare_course_repository($courseRepository, $courseId)

		prepare_course_repository($currentCourseRepository,$currentCourseId);
		update_Db_course($currentCourseDbName);
		fill_course_repository($currentCourseRepository);

		// function 	fill_Db_course($courseDbName,$courseRepository)
		fill_Db_course($currentCourseDbName, $currentCourseRepository, $HTTP_POST_VARS["languageCourse"]);
		register_course($currentCourseId, $currentCourseCode, $currentCourseRepository, $currentCourseDbName, $HTTP_POST_VARS["titulaires"],$HTTP_POST_VARS["email"],$HTTP_POST_VARS["faculte"],$HTTP_POST_VARS["intitule"], $HTTP_POST_VARS["languageCourse"], $_uid, $expirationDate);
		$displayCourseAddResult = TRUE;
		$displayCoursePropertiesForm = FALSE;
		$displayWhatAdd = FALSE;
	}
}

include($includePath."/claro_init_header.inc.php");
claro_disp_tool_title($nameTools);
claro_disp_msg_arr($controlMsg);

// db connect
// path for breadcrumb contextual menu in this page
$chemin="<a href=../../index.php>$siteName</a>&nbsp;&gt;&nbsp;<b>$langCreateSite</b>";
###################### FORM  #########################################

if($displayNotForU)
{
	echo $langNotAllowed;
}
elseif($displayWhatAdd)
{
?>
<form class="forms" action="<?php echo $PHP_SELF; ?>" method="post">
<table  width="100%">
	<tr valign="top">
		<td colspan="2" valign="top">
			<H5>
				<?php echo $langAddNewCourse ?>
			</H5>
			<br>
		</td>
	</tr>
	<tr valign="top">
		<td width="40"></td>
		<td >
			<input type="radio" name="whatAdd" value="newCourse" checked>
			<?php echo $langNewCourse ?>
		</td>
	</tr>
	<tr valign="top">
		<td width="40"></td>
		<td >
			<input type="radio" name="whatAdd" value="archive" >
			<?php echo $langRestoreACourse ?>
		</td>
	</tr>
	<tr valign="top">
		<td width="40"></td>
		<td valign="top">
			<br><br>
			<input type="submit" name="fromWhatAdd" value="Next">
		</td>
	</tr>
</table>
</form>
<?php
}
elseif($displayCourseRestore)
{
?>
<br>
<form  class="forms" action="<?php echo $PHP_SELF; ?>" method="post" enctype="multipart/form-data">
<table width="100%">
	<tr valign="top">
		<td colspan="2" valign="top">
			<H5>
				<?php echo $langChoseFile ?>
			</H5>
			<br>
		</td>
	</tr>
<?php
	if ($sendByUploadAivailable)
	{
?>
	<tr valign="top">
		<TD >
			<input type="radio" name="typeStorage" value="upload" checked >&nbsp;Upload
		</TD>
		<td >
			<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="7000000">
			<input type="file" name="postFile" accept="application/x-zip-compressed">
			<DIV class="formsTips">
				<?php echo $langPostFileTips; ?>
			</DIV>
		</td>
	</tr>
<?php
	}
	if ($sendByHTTPAivailable)
	{
?>
	<tr valign="top">
		<TD >
			<input type="radio" name="typeStorage" value="http" >&nbsp;Http
		</TD>
		<td >
			<input type="text" name="httpFile" >
			<DIV class="formsTips">
				<?php echo $langHttpFileTips; ?>
			</DIV>
		</td>
	</tr>
<?php
	}
	if ($sendByFTPAivailable )
	{
?>
	<tr valign="top">
		<TD >
			<input type="radio" name="typeStorage" value="ftp" >&nbsp;Ftp
		</TD>
		<td >
			<input type="text" name="ftpFile" >
			<DIV class="formsTips">
				<?php echo $langFtpFileTips; ?>
			</DIV>
		</td>
	</tr>
<?php
	}
	if ($sendByLocaleAivailable)
	{
?>
	<tr valign="top">
		<TD>
			<input type="radio" name="typeStorage" value="local" >&nbsp;On server
		</TD>
		<td >
			<input type="text" name="localFile" >
			<DIV class="formsTips">
				<?php echo $langLocalFileTips; ?>
			</DIV>
		</td>
	</tr>
<?php
	}
?>
	<tr valign="top">
		<TD >
		</TD>
		<td valign="top">
			<br><br>
			<input type="submit" name="selectArchive" value="Next">
		</td>
	</tr>
</table>
</form>
<?php
}
elseif($displayCoursePropertiesForm)
{
?>
<b><?php echo $langFieldsRequ ?></b>
<form method="post" action="<?php echo $PHP_SELF ?>">
<table>
<tr valign="top">
<td colspan="2">

</td>
</tr>

<tr valign="top">
<td align="right">
<?php echo $langTitle ?> :
</td>
<td valign="top">
<input type="Text" name="intitule" size="60" value="<?php echo $valueIntitule ?>">
<br><small><?php echo $langEx ?></small>
<input type="hidden" name="fromAdmin" size="60" value="<?php echo $fromAdmin ?>">
</td>
</tr>

<tr valign="top">
<td align="right"><?php echo $langFac ?> : </td>
<td>
<select name="faculte">
<?
$resultFac = mysql_query_dbg("SELECT `code`, `name`
                              FROM `".$TABLECOURSDOMAIN."`
                              WHERE `canHaveCoursesChild` ='TRUE'
                              ORDER BY `name`");

	while ($myfac = mysql_fetch_array($resultFac))
	{
		echo "<option value=\"", $myfac["code"], "\"";
		echo ">(", $myfac["code"], ") ", ($myfac["code"]==$myfac["name"]?"":$myfac["name"]);
		echo "</option>\n";
	}
?>
</select>
<br><small><?php echo $langTargetFac ?></small>
</td>
</tr>

<tr valign="top">
<td align="right"><?php echo $langCode ?> : </td>
<td ><input type="Text" name="wantedCode" maxlength="12" value="<?php echo $valuePublicCode ?>">
<br><small><?php echo $langMax ?></small>
</td>
</tr>

<tr valign="top">
<td align="right">
<?php echo $langProfessors ?> :
</td>
<td>
<input type="Text" name="titulaires" size="60" value="<?php echo $valueTitular ?>">
</td>
</tr>

<tr>
<td align="right"><?echo $langEmail ?>&nbsp;:</td>
<td><input type="text" name="email" value="<?php echo $valueEmail; ?>" size="30" maxlength="255"></td>
</tr>

<tr valign="top">
<td align="right">
<?php echo $langLn ?> :
</td>
<td>
<select name="languageCourse">";
<?php
	$dirname = "../lang/";
	if($dirname[strlen($dirname)-1]!='/')
		$dirname.='/';
	$handle=opendir($dirname);
	while ($entries = readdir($handle))
	{
		if ($entries=='.'||$entries=='..'||$entries=='CVS')
			continue;
		if (is_dir($dirname.$entries))
		{
			echo "<option value=\"".$entries."\"";
			if ($entries == $valueLanguage) echo " selected ";
			echo ">"; 
					if (!empty($langNameOfLang[$entries]) && $langNameOfLang[$entries]!="" && $langNameOfLang[$entries]!=$entries)
					echo $langNameOfLang[$entries]." - ";
			echo $entries,"</option>\n";
		}
	}	
	closedir($handle);
?>
</select>
</td>
</tr>
<tr valign="top">
<td>
</td>
<td>
<input type="Submit" name="submitFromCoursProperties" value="<?php echo $langOk?>">
</td>
</tr>
</table>
</form>
<p><?php echo $langExplanation ?>.</p>

<?php
		if($showLinkToRestoreCourse)
		{
			if($is_platformAdmin)
			{
?>

<hr noshade size="1">
<a href="../course_info/restore_course.php"><?php echo $langRestoreCourse; ?></a>

<?php

			}
		}
/*
	$valueCode			= $courseProperties["officialCode"];
	$valueIntitule		= $courseProperties["name"];
	$valueFacultyName	= $courseProperties["categoryName"];
	$valueFacultyCode	= $courseProperties["categoryCode"];
	$valueLanguage 		= $courseProperties["language"];
	$valueAdminCode		= $courseProperties["adminCode"];
	$valueDbName		= $courseProperties["dbName"];
	$valuePath			= $courseProperties["path"];
	$valueRegAllowed 	= $courseProperties["registrationAllowed"];
*/
	if ($showPropertiesFromArchive)
	{
?>
<table class="forms" width="100%">
	<tr valign="top">
		<td colspan="2" valign="top">
				<b>
					<?php echo $langOtherProperties ?>
				</b>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langSysId ?>
		</td
		<td>
			<?php echo $valueSysId ?><br>
			<?php echo $valueAdminCode?><br>
			<?php echo $valueDbName?><br>
			<?php echo $valuePath?>
		</td>
	</tr>

	<tr>
		<td>
			<?php echo $langFaculty ?>
		</td
		<td>
			[<?php echo $valueFacultyCode ?>]<?php echo $valueFacultyName ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langDescription ?>
		</td
		<td>
			<?php echo $valueDescription ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langDepartment	 ?>
		</td
		<td>
			<?php echo $valueDepartment ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langDepartmentUrl	 ?>
		</td
		<td>
			<?php echo $valueDepartmentUrl ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langScoreShow ?>
		</td
		<td>
			<?php echo $valueScoreShow ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langVisibility ?>
		</td
		<td>
			<?php echo $valueVisibility ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langregistration ?>
		</td
		<td>
			<?php echo $valueRegAllowed ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langVersionDb ?>
		</td
		<td>
			<?php echo $valueVersionDb ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langVersionClaro ?>
		</td
		<td>
			<?php echo $valueVersionClaro ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langLastVisit ?>
		</td
		<td>
			<?php echo ucfirst(claro_format_locale_date($dateTimeFormatLong,strtotime($valueLastVisit))) ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langLastEdit ?>
		</td
		<td>
			<?php echo ucfirst(claro_format_locale_date($dateTimeFormatLong,strtotime($valueLastEdit))) ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $langExpire ?>
		</td
		<td>
			<?php echo $valueExpire ?>
		</td>
	</tr>
<?
	}
?>
</table>
<?
}   // IF ! SUBMIT

#################SORT THE FORM ####################
# 1. CHECK IF DIRECTORY/COURSE_CODE ALREADY TAKEN #
#### CREATE THE COURSE AND THE DATABASE OF IT #####
elseif($displayCourseAddResult)
{
// Replace HTML special chars by equivalent - cannot use html_specialchars
// Special for french
?>
	<tr bgcolor="<?php echo $color2	?>">
		<td colspan="3">
				<?php

                 echo $langJustCreated." <strong>".$currentCourseCode."</strong><br>"; ?>
                 <?
                 if ($_POST['fromAdmin']!="yes")
                 {
                    claro_disp_button("../../index.php",$langEnter);
                 }
                 else
                 {
                    claro_disp_button("add_course.php?fromAdmin=yes",$langAnotherCreateSite);
                    claro_disp_button("../admin/index.php",$langBackToAdmin);
                 }?>
		</td>
	</tr>
<?php
} // if all fields fulfilled
include($includePath."/claro_init_footer.inc.php");
?>