<?php // $Id$
/*    +----------------------------------------------------------------------+
      | CLAROLINE version 1.6
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
 */
 
$langCheckemail = "V�rification de l'email";
$langFile = "registration";
//$tlabelReq = ""; // actually tools out cours don't have label

include('../inc/claro_init_global.inc.php');
$nameTools = $langCheckemail;

$tbl_mdb_names = claro_sql_get_main_tbl();
$tbl_user      = $tbl_mdb_names['user'];

//stats
$interbredcrump[]= array ("url"=>"inscription.php", "name"=> $langRegistration);
if (!isset($userMailCanBeEmpty))
{
	$userMailCanBeEmpty = true;
}
/*
		\n".$rootWeb."/claroline/auth/checkEmail.php?hash=".$hash"&email=".$email;
		}
		else
		{
			$hash = "ok";
		}
		$sqlIncriptUserHash = "
INSERT 
	INTO $mainDbName.userHash
		(user_id, hash) 
	VALUES 
		('$last_id', '$hash')";
		@mysql_query($sqlIncriptUserHash);
*/


$sqlCheck = "
Select
	`user`.*, `hash`.* , `hash`.`user_id` `uid` 
From  
	`$mainDbName`.`userHash` `hash`, 
	`".$tbl_user."`  
WHERE
	`hash`.`user_id` = `user`.`user_id` and `email` = '".$emailHash."' and `hash` = '".$hash."';";

$resHashFound  = claro_sql_query($sqlCheck);
$hashFound = mysql_fetch_array($resHashFound);
if (	$hashFound["email"] == $emailHash 
	&& 	$hashFound["hash"] == $hash ) 
{
	if ($hashFound["state"] != "VALID" )
	{
		$sqlUpdateState = "
UPDATE
	userHash
SET  
	STATE =  'VALID'
WHERE
	user_id	= '".$hashFound["uid"]."' and hash = '".$hash."';";
		claro_sql_query($sqlUpdateState);
		$resultOutput = "<br>".$emailHash." is now valid.";
	}
	else 
	{
		$resultOutput = "<br>".$emailHash." is already valdided.";
	}
}

// OUTPUT
include($includePath."/claro_init_header.inc.php");
?>
Hash : <?php echo $hash ?>
<br>
Email : <?php echo $emailHash ?>
<BR>
<?php
echo $resultOutput;

include($includePath."/claro_init_footer.inc.php");
?>