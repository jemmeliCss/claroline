<?php # $Id$
//----------------------------------------------------------------------
// CLAROLINE
//----------------------------------------------------------------------
// Copyright (c) 2001-2003 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

unset($controlMsg);


$langFile = "admin.access";
$cidReset = true;

include('../../inc/claro_init_global.inc.php');
$nameTools = $langNomPageAddHtPass;

$interbredcrump[]= array ("url"=>$rootAdminWeb, "name"=> $langAdministrationTools);
$interbredcrump[]= array ("url"=>$rootAdminWeb."managing/", "name"=> $langManage);

@include("./checkIfHtAccessIsPresent.php");
/*$htmlHeadXtra[] = "<style type=\"text/css\">
<!--

-->
</STYLE>";*/

$tbl_user 	= $mainDbName."`.`user";
$tbl_admin 	= $mainDbName."`.`admin";

$is_allowedToEdit 	= $is_platformAdmin || isset($PHP_AUTH_USER);

$pathHtPassword = $rootAdminSys."/".".htpasswd4admin";

define ("NO_WAY", 0);
define ("USER_SELECT_FORM", 1);
define ("WHAT_YOU_WANT_TO_DO", 2);
define ("ADD_LOGIN_PASS", 3);
define ("LIST_ADMINS", 5);
define ("LIST_HT_LOGIN", 6);
define ("AFTER_ADD_ADMIN", 7);
define ("FINAL_MESSAGE", 99);

define ("COURSE_CREATOR",1);


//phpinfo();
if ($is_allowedToEdit)
{
	$display = WHAT_YOU_WANT_TO_DO;

	if (isset($HTTP_GET_VARS["addLoginPass"]))
	{
		$interbredcrump[]= array ("url"=>$PHP_SELF, "name"=> $langNomPageAddHtPass);
		$nameTools = $langAddLoginPass;
		$display = ADD_LOGIN_PASS;
	}
	elseif (isset($HTTP_GET_VARS["giveAdminRight"]))
	{
		$display = USER_SELECT_FORM;
		if (isset($HTTP_GET_VARS["listAllUsers"]))
		{
//	$sqlGetListUser = "SELECT user_id, nom, prenom, username, email FROM  `".$tbl_user."` ORDER BY UPPER(nom), UPPER(prenom) ";
			$sqlGetListUser = "
SELECT `user`.`user_id`, `user`.`nom`, `user`.`prenom`, `user`.`username`, `user`.`email`
FROM `".$tbl_user."` `user`
LEFT JOIN `".$tbl_admin."` `admin` ON `user`.`user_id` = `admin`.`idUser`
WHERE `admin`.`idUser` IS NULL
ORDER BY UPPER( `user`.`nom` ) , UPPER( `user`.`prenom` )";
			$langListAllUsers = "";
		}
		else
		{
			$sqlGetListUser = "
SELECT user_id, nom, prenom, username, email
FROM  `".$tbl_user."`
LEFT JOIN `".$tbl_admin."` `admin` ON `user`.`user_id` = `admin`.`idUser`
WHERE `admin`.`idUser` IS NULL AND statut = '".COURSE_CREATOR."' ORDER BY UPPER(nom), UPPER(prenom) ";
		}
		$resListOfUsers = mysql_query($sqlGetListUser) or die("Erreur SELECT FROM user ".$sqlGetListUser);
		if (mysql_num_rows($resListOfUsers)==0)
		{
			if (isset($HTTP_GET_VARS["listAllUsers"]))
			{
			    $controlMsg["warning"][]="There is no user wich can be set as admin";
				$display = WHAT_YOU_WANT_TO_DO;
			}
			else
			{
			    $controlMsg["warning"][]= "There is no user with course creator level wich can be set as admin";
				$interbredcrump[]= array ("url"=>$PHP_SELF, "name"=> $langNomPageAddHtPass);
				$nameTools = $langGiveAdminRight;
			}
		}
		else
		{
			$interbredcrump[]= array ("url"=>$PHP_SELF, "name"=> $langNomPageAddHtPass);
			$nameTools = $langGiveAdminRight;
		}
	}
	elseif (isset($HTTP_GET_VARS["listAdmins"]))
	{
		$display = LIST_ADMINS;
		$sqlGetListUser = "SELECT user_id, nom, prenom, username, email FROM `".$tbl_user."` u, `".$tbl_admin."` a WHERE u.user_id = a.idUSer ";
		$resListOfUsers= mysql_query($sqlGetListUser) or die("Erreur SELECT FROM user admins ".$sqlGetListUser);
		$interbredcrump[]= array ("url"=>$PHP_SELF, "name"=> $langNomPageAddHtPass);
		$nameTools = $langListAdmin;
	}
	elseif (isset($HTTP_GET_VARS["listHtLogins"]))
	{
		$display = LIST_HT_LOGIN;
		$interbredcrump[]= array ("url"=>$PHP_SELF, "name"=> $langNomPageAddHtPass);
		$nameTools = $langListHtUsers;
	}
	elseif (isset($HTTP_POST_VARS["uidToSetAdmin"]))
	{
		$sqlSetAdminUser = "Insert IGNORE INTO  `".$tbl_admin."` SET `idUser` = '".$HTTP_POST_VARS["uidToSetAdmin"]."'";
  		mysql_query($sqlSetAdminUser) or die("Erreur sqlSetAdminUser ".$sqlSetAdminUser);
		$sqlGetUser = "SELECT `nom`, `prenom`, `username`, `password`, `email` FROM  `".$tbl_user."` WHERE `user_id` = '".$HTTP_POST_VARS["uidToSetAdmin"]."';";
  		$resGetUser = mysql_query($sqlGetUser) or die("Erreur in sqlGetUser ".$sqlGetUser);
		$user = mysql_fetch_array($resGetUser,  MYSQL_ASSOC);
	    $controlMsg["success"][]= "ok : Now, add a login-pass for <strong>".$user["prenom"]." ".$user["nom"]."</strong> in .htaccess and  give it to the user by secure way";
		$display         = AFTER_ADD_ADMIN;
		$interbredcrump[]= array ("url"=>$PHP_SELF, "name"=> $langNomPageAddHtPass);
		$nameTools = $langGiveAdminRight;
	}
	elseif (isset($HTTP_GET_VARS["uidToSetNotAdmin"]))
	{
		if(!isset($_uid))
		{
			$controlMsg["warning"][]= "You must be logged on ".$siteName." to access to this section";
		}
		else
		{
			$sqlDelAdminUser = "Delete From `".$tbl_admin."` Where NOT (`idUser` = '".$_uid."') AND `idUser` = '".$HTTP_GET_VARS["uidToSetNotAdmin"]."'";
	  		mysql_query($sqlDelAdminUser) or die("Erreur sqlDelAdminUser ".$sqlDelAdminUser);
			$sqlGetUser = "SELECT `nom`, `prenom`, `username`, `password`, `email` FROM  `".$tbl_user."` WHERE `user_id` = '".$HTTP_GET_VARS["uidToSetNotAdmin"]."';";
	  		$resGetUser = mysql_query($sqlGetUser) or die("Erreur in sqlGetUser ".$sqlGetUser);
			$user = mysql_fetch_array($resGetUser,  MYSQL_ASSOC);
			$controlMsg["warning"][]= "ok : Now, <strong>".$user["prenom"]." ".$user["nom"]."</strong> is no more admin for ".$siteName." but you must remove your self login-pass in .htaccess ";
		}
	}
	elseif (isset($HTTP_GET_VARS["addLoginPassFromClaroUser"]))
	{
		$display = FINAL_MESSAGE;
		$sqlGetUser = "SELECT `nom`, `prenom`, `username`, `password`, `email` FROM  `".$tbl_user."` WHERE `user_id` = '".$HTTP_GET_VARS["addLoginPassFromClaroUser"]."';";
  		$resGetUser = mysql_query($sqlGetUser) or die("Erreur in sqlGetUser ".$sqlGetUser);
		$user = mysql_fetch_array($resGetUser,  MYSQL_ASSOC);
		if ($user["username"]!="" || $user["password"]!="")
		{
			if (PHP_OS!="WIN32" && PHP_OS!="WINNT")
			{
				$user["password"]=crypt($user["password"]);
			}
			$stringPasswd = "\n".$user["username"].":".$user["password"];
			$filePasswd=fopen( $pathHtPassword , "a");
			fwrite($filePasswd, $stringPasswd);
			fclose($filePasswd);
			$controlMsg["success"][]= "ok : Now, ".$user["prenom"]." ".$user["nom"]." can use his campus login-pass to access in protected areas
			<br>".realpath($pathHtPassword)." ".$langUpdated;
		}
		else
		{
			$controlMsg["error"][]= $user["prenom"]." ".$user["nom"]." have username or password empty";
		}
	}
	elseif (isset($HTTP_POST_VARS["crypt"]))
	{
		if ($encodeLogin!="" || $encodePass!="")
		{
			if (PHP_OS!="WIN32" && PHP_OS!="WINNT")
			{
				$encodePass=crypt($encodePass);
			}
			$stringPasswd = "\n".$encodeLogin.":".$encodePass;
			$filePasswd=fopen( $pathHtPassword , "a");
			fwrite($filePasswd, $stringPasswd);
			fclose($filePasswd);
			$controlMsg["success"][]= realpath($pathHtPassword)." ".$langUpdated;
			$display = FINAL_MESSAGE;
		}
		else
		{
			echo "can't be empty";
		}
	}
}
else
{
	$display = NO_WAY;
}


////////////////////////////////////////////////
///////// OUTPUT////////////////////////////////
////////////////////////////////////////////////


include($includePath."/claro_init_header.inc.php");
claro_disp_tool_title($nameTools);
claro_disp_msg_arr($controlMsg);

if ($display == NO_WAY)
{
	echo $lang_no_access_here;
}
elseif ($display == WHAT_YOU_WANT_TO_DO)
{
	?>
	<UL>
		<LI>
			<b>
				<a href="<?php echo $PHP_SELF ?>?giveAdminRight=1"><?php echo $langGiveAdminRight; ?></a>
			</b>
		</LI>
	</UL>
	<?php echo $langOtherWorks ; ?>
	<UL>
		<LI>
			<a href="<?php echo $PHP_SELF ?>?addLoginPass=1"><?php echo $langAddLoginPass; ?></a>
		</LI>
		<LI>
			<a href="<?php echo $PHP_SELF ?>?listAdmins=1"><?php echo $langListAdmin; ?></a>
		</LI>
		<LI>
			<a href="<?php echo $PHP_SELF ?>?listHtLogins=1"><?php echo $langListHtUsers; ?></a>
		</LI>
	</UL>
	<?
}
elseif ($display == AFTER_ADD_ADMIN)
{
	?>
	<UL>
		<LI>
			<strong>
				<a href="<?php echo $PHP_SELF ?>?addLoginPassFromClaroUser=<?php echo $HTTP_POST_VARS["uidToSetAdmin"] ?>"><?php echo $langAddLoginPassForThisUser; ?></a> (<?php echo $user["prenom"]." ".$user["nom"]; ?>)
			</strong>
		</LI>
	</UL>
	<UL>
		<LI>
			<a href="<?php echo $PHP_SELF ?>?addLoginPass=1"><?php echo $langAddLoginPass; ?></a>
		</LI>
	</UL>
	<?php echo $langOtherWorks ; ?>
	<UL>
		<LI>
			<a href="<?php echo $PHP_SELF ?>?giveAdminRight=1"><?php echo $langGiveAdminRight; ?></a>
		</LI>
	</UL>
	<?

}
elseif ($display == USER_SELECT_FORM)
{
?>
<form action="<?php echo $PHP_SELF ?>" method="POST">
<LABEL for="userBeAdmin"><?php echo $langSelectAUser; ?></LABEL>
<?php 
	if (mysql_num_rows($resListOfUsers)>0)
	{
	?>
<select name="uidToSetAdmin" id="userBeAdmin" >
<?php
		while ($user = mysql_fetch_array($resListOfUsers))
		{
			echo "
	<OPTION  value=\"",$user["user_id"],"\" >
		",$user["nom"]," ",$user["prenom"],"
		(",$user["username"],")
		",$user["email"],"
	</OPTION>";
		}
?>
</select> 			
<input type="submit" value="<?php echo $langSetAdmin; ?>"><br>
<?php
	}
?>
<small>
	<a href="<?php echo $PHP_SELF ?>?giveAdminRight=1&listAllUsers=1"><?php echo $langListAllUsers; ?></a>
</small>
</form>
<?php
}
elseif ($display == LIST_ADMINS)
{
	echo "
<UL>";
	while ($user = mysql_fetch_array($resListOfUsers))
	{
		echo "
	<LI>
		",$user["user_id"],"<TT> 
		[<a href=\"adminProfile.php?uidToEdit=",$user["user_id"],"\" >",$langEdit,"</a>]";
		if (isset($_uid))
		{
			if ($user["user_id"]!=$_uid)
			{
				echo "
			[<a href=\"".$PHP_SELF."?uidToSetNotAdmin=",$user["user_id"],"\" >",$langRemoveAdminLevel,"</a>]";
			}
			else
			{
				echo "
			[",str_repeat ( "-", strlen($langRemoveAdminLevel)),"]";

			}
		}
		echo "</TT>
		",$user["nom"]," ",$user["prenom"],"
		(",$user["username"],")
		<a href=\"mailto:",$user["email"],"\">",$user["email"],"</a>
	</LI>";
	}
	echo "
</UL>
<br>
<br>";
}
elseif ($display == LIST_HT_LOGIN)
{
	echo "<PRE>";
	readfile($pathHtPassword);
	echo "</PRE>";
}
elseif ($display == ADD_LOGIN_PASS)
{	if (isset($msgstr))
	{
		echo "<DIV class=\"",$classMsg,"\">",$msgstr,"</DIV><br>";
	}
?>
<form  method="POST" name="crypte" action="<?= $PHP_SELF?>">
<TABLE>
	<TR>
		<TD>
			<LABEL for="login">
				<?php  echo  $langLogin ?>
			</LABEL> :
		</TD>
		<TD>
			<input type="text" id="login" name="encodeLogin" size="20" maxlength="30">
		</TD>
	</TR>
	<TR>
		<TD>
			<LABEL for="password">
				<?php  echo  $langPassword ?>
			</LABEL> :
		</TD>
		<TD>
			<input type="text" id="password" name="encodePass" size="20" maxlength="30">
		</TD>
	</TR>
	<TR>
		<TD colspan="2">
			<input type="submit" name="crypt" value="crypt">
		</TD>
	</TR>
</TABLE>
</form>
<?php
}
elseif ($display == FINAL_MESSAGE)
{
	?>
	<UL>
		<LI>
			<b>
				<a href="<?php echo $PHP_SELF ?>?listHtLogins=1"><?php echo $langListHtUsers; ?></a>
			</b>
		</LI>
	</UL>
	<?php echo $langOtherWorks ; ?>
	<UL>
		<LI>
			<a href="<?php echo $PHP_SELF ?>"><?php echo $langNomPageAddHtPass; ?></a>
		</LI>
	</UL>
	<?
}
else
{
	echo $lang_no_access_here;
}

include($includePath."/claro_init_footer.inc.php");
?>