<?php // $Id$
/**
 * @version CLAROLINE 1.6
 *
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license GENERAL PUBLIC LICENSE (GPL)
 *
 * @author Christophe Gesch� <moosh@claroline.net>
 */

unset($controlMsg);

$cidReset = true;

require '../../inc/claro_init_global.inc.php';
$nameTools = $langNomPageAddHtPass;

$interbredcrump[]= array ("url"=>$rootAdminWeb, "name"=> $langAdministration);
$interbredcrump[]= array ("url"=>$rootAdminWeb."managing/", "name"=> $langManage);

@include("./checkIfHtAccessIsPresent.php");
/*$htmlHeadXtra[] = "<style type=\"text/css\">
<!--

-->
</STYLE>";*/

$tbl_cdb_names = claro_sql_get_main_tbl();
$tbl_admin       = $tbl_cdb_names['admin'];
$tbl_user        = $tbl_cdb_names['user'];



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

	if (isset($_REQUEST["addLoginPass"]))
	{
		$interbredcrump[]= array ("url"=>$_SERVER['PHP_SELF'], "name"=> $langNomPageAddHtPass);
		$nameTools = $langAddLoginPass;
		$display = ADD_LOGIN_PASS;
	}
	elseif (isset($_REQUEST["giveAdminRight"]))
	{
		$display = USER_SELECT_FORM;
		if (isset($_REQUEST["listAllUsers"]))
		{
//	$sqlGetListUser = "SELECT user_id, nom, prenom, username, email FROM  `".$tbl_user."` `user` ORDER BY UPPER(nom), UPPER(prenom) ";
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
FROM  `".$tbl_user."`  `user` 
LEFT JOIN `".$tbl_admin."` `admin` ON `user`.`user_id` = `admin`.`idUser`
WHERE `admin`.`idUser` IS NULL AND statut = '".COURSE_CREATOR."' ORDER BY UPPER(nom), UPPER(prenom) ";
		}
		$resListOfUsers = claro_sql_query($sqlGetListUser);
		
		if (mysql_num_rows($resListOfUsers)==0)
		{
			if (isset($_REQUEST["listAllUsers"]))
			{
			    $controlMsg["warning"][]="There is no user wich can be set as admin";
				$display = WHAT_YOU_WANT_TO_DO;
			}
			else
			{
			    $controlMsg["warning"][]= "There is no user with course creator level wich can be set as admin";
				$interbredcrump[]= array ("url"=>$_SERVER['PHP_SELF'], "name"=> $langNomPageAddHtPass);
				$nameTools = $langGiveAdminRight;
			}
		}
		else
		{
			$interbredcrump[]= array ("url"=>$_SERVER['PHP_SELF'], "name"=> $langNomPageAddHtPass);
			$nameTools = $langGiveAdminRight;
		}
	}
	elseif (isset($_REQUEST["listAdmins"]))
	{
		$display = LIST_ADMINS;
		$sqlGetListUser = "SELECT user_id, nom, prenom, username, email FROM `".$tbl_user."` u, `".$tbl_admin."` a WHERE u.user_id = a.idUSer ";
		$resListOfUsers= claro_sql_query($sqlGetListUser) or die("Erreur SELECT FROM user admins ".$sqlGetListUser);
		$interbredcrump[]= array ("url"=>$_SERVER['PHP_SELF'], "name"=> $langNomPageAddHtPass);
		$nameTools = $langListAdmin;
	}
	elseif (isset($_REQUEST["listHtLogins"]))
	{
		$display = LIST_HT_LOGIN;
		$interbredcrump[]= array ("url"=>$_SERVER['PHP_SELF'], "name"=> $langNomPageAddHtPass);
		$nameTools = $langListHtUsers;
	}
	elseif (isset($HTTP_POST_VARS["uidToSetAdmin"]))
	{
		$sqlSetAdminUser = "Insert IGNORE INTO  `".$tbl_admin."` SET `idUser` = '".$HTTP_POST_VARS["uidToSetAdmin"]."'";
  		claro_sql_query($sqlSetAdminUser) or die("Erreur sqlSetAdminUser ".$sqlSetAdminUser);
		$sqlGetUser = "SELECT `nom`, `prenom`, `username`, `password`, `email` FROM  `".$tbl_user."`  `user` WHERE `user_id` = '".$HTTP_POST_VARS["uidToSetAdmin"]."';";
  		$resGetUser = claro_sql_query($sqlGetUser) or die("Erreur in sqlGetUser ".$sqlGetUser);
		$user = mysql_fetch_array($resGetUser,  MYSQL_ASSOC);
	    $controlMsg["success"][]= "ok : Now, add a login-pass for <strong>".$user["prenom"]." ".$user["nom"]."</strong> in .htaccess and  give it to the user by secure way";
		$display         = AFTER_ADD_ADMIN;
		$interbredcrump[]= array ("url"=>$_SERVER['PHP_SELF'], "name"=> $langNomPageAddHtPass);
		$nameTools = $langGiveAdminRight;
	}
	elseif (isset($_REQUEST["uidToSetNotAdmin"]))
	{
		if(!isset($_uid))
		{
			$controlMsg["warning"][]= "You must be logged on ".$siteName." to access to this section";
		}
		else
		{
			$sqlDelAdminUser = "Delete From `".$tbl_admin."`  `user`  Where NOT (`idUser` = '".$_uid."') AND `idUser` = '".$_REQUEST["uidToSetNotAdmin"]."'";
	  		claro_sql_query($sqlDelAdminUser) or die("Erreur sqlDelAdminUser ".$sqlDelAdminUser);
			$sqlGetUser = "SELECT `nom`, `prenom`, `username`, `password`, `email` FROM  `".$tbl_user."`  `user`  WHERE `user_id` = '".$_REQUEST["uidToSetNotAdmin"]."';";
	  		$resGetUser = claro_sql_query($sqlGetUser) or die("Erreur in sqlGetUser ".$sqlGetUser);
			$user = mysql_fetch_array($resGetUser,  MYSQL_ASSOC);
			$controlMsg["warning"][]= "ok : Now, <strong>".$user["prenom"]." ".$user["nom"]."</strong> is no more admin for ".$siteName." but you must remove your self login-pass in .htaccess ";
		}
	}
	elseif (isset($_REQUEST["addLoginPassFromClaroUser"]))
	{
		$display = FINAL_MESSAGE;
		$sqlGetUser = "SELECT `nom`, `prenom`, `username`, `password`, `email` FROM  `".$tbl_user."`  `user`  WHERE `user_id` = '".$_REQUEST["addLoginPassFromClaroUser"]."';";
  		$resGetUser = claro_sql_query($sqlGetUser) or die("Erreur in sqlGetUser ".$sqlGetUser);
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
				<a href="<?php echo $_SERVER['PHP_SELF'] ?>?giveAdminRight=1"><?php echo $langGiveAdminRight; ?></a>
			</b>
		</LI>
	</UL>
	<?php echo $langOtherWorks ; ?>
	<UL>
		<LI>
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?addLoginPass=1"><?php echo $langAddLoginPass; ?></a>
		</LI>
		<LI>
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?listAdmins=1"><?php echo $langListAdmin; ?></a>
		</LI>
		<LI>
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?listHtLogins=1"><?php echo $langListHtUsers; ?></a>
		</LI>
	</UL>
	<?php
}
elseif ($display == AFTER_ADD_ADMIN)
{
	?>
	<UL>
		<LI>
			<strong>
				<a href="<?php echo $_SERVER['PHP_SELF'] ?>?addLoginPassFromClaroUser=<?php echo $HTTP_POST_VARS["uidToSetAdmin"] ?>"><?php echo $langAddLoginPassForThisUser; ?></a> (<?php echo $user["prenom"]." ".$user["nom"]; ?>)
			</strong>
		</LI>
	</UL>
	<UL>
		<LI>
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?addLoginPass=1"><?php echo $langAddLoginPass; ?></a>
		</LI>
	</UL>
	<?php echo $langOtherWorks ; ?>
	<UL>
		<LI>
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?giveAdminRight=1"><?php echo $langGiveAdminRight; ?></a>
		</LI>
	</UL>
	<?php

}
elseif ($display == USER_SELECT_FORM)
{
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
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
	<a href="<?php echo $_SERVER['PHP_SELF'] ?>?giveAdminRight=1&listAllUsers=1"><?php echo $langListAllUsers; ?></a>
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
		[<a href=\"adminprofile.php?uidToEdit=",$user["user_id"],"\" >",$langEdit,"</a>]";
		if (isset($_uid))
		{
			if ($user["user_id"]!=$_uid)
			{
				echo "
			[<a href=\"".$_SERVER['PHP_SELF']."?uidToSetNotAdmin=",$user["user_id"],"\" >",$langRemoveAdminLevel,"</a>]";
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
<form  method="POST" name="crypte" action="<?php echo $_SERVER['PHP_SELF']?>">
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
				<a href="<?php echo $_SERVER['PHP_SELF'] ?>?listHtLogins=1"><?php echo $langListHtUsers; ?></a>
			</b>
		</LI>
	</UL>
	<?php echo $langOtherWorks ; ?>
	<UL>
		<LI>
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>"><?php echo $langNomPageAddHtPass; ?></a>
		</LI>
	</UL>
	<?php
}
else
{
	echo $lang_no_access_here;
}

include($includePath."/claro_init_footer.inc.php");
?>
