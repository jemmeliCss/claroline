<?php
// $Id$
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

/*==========================
             INIT
  ==========================*/

$langFile = "registration";
include("../inc/claro_init_global.inc.php");
$interbredcrump[]= array ("url"=>"inscription.php", "name"=> $langRegistration);
include($includePath."/claro_init_header.inc.php");
$nameTools = "2";

// $TABLELOGINOUT  = $mainDbName."`.`loginout";
$TABLEUSER      = $mainDbName."`.`user";

define ("CHECK_PASS_EASY_TO_FIND", true);
define ("STUDENT",5);
define ("COURSEMANAGER",1);

if (!isset($userMailCanBeEmpty))   $userMailCanBeEmpty   = true;
if (!isset($checkEmailByHashSent)) $checkEmailByHashSent = false;
if (!isset($userPasswordCrypted))  $userPasswordCrypted	 = false;

$regDataOk = false; // default value...
?>
<h3><?= $langRegistration ?></h3>

<?

if($submitRegistration)
{
	$regexp = "^[0-9a-z_\.-]+@(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-z][0-9a-z-]*[0-9a-z]\.)+[a-z]{2,3})$";
	$uname      = trim ($HTTP_POST_VARS["uname"     ]);
	$email      = trim ($HTTP_POST_VARS["email"     ]);
	$nom        = trim ($HTTP_POST_VARS["nom"       ]);
	$prenom     = trim ($HTTP_POST_VARS["prenom"    ]);
	$password   = trim ($HTTP_POST_VARS["password"  ]);
	$password1  = trim ($HTTP_POST_VARS["password1" ]);
	$statut     = ($HTTP_POST_VARS["statut"    ]==COURSEMANAGER)?COURSEMANAGER:STUDENT;
	/*==========================
	   DATA SUBIMITED CHECKIN
	  ==========================*/

	// CHECK IF THERE IS NO EMPTY FIELD

	if (
		   empty($nom)
		OR empty($prenom)
		OR empty($password1)
		OR empty($password)
		OR empty($uname)
		OR (empty($email) && !$userMailCanBeEmpty)
			)
	{
		$regDataOk = false;

		unset($password1, $password);

		echo	"<p>",$langEmptyFields,"</p>\n";
	}

	// CHECK IF THE TWO PASSWORD TOKEN ARE IDENTICAL

	elseif($password1 != $password)
	{
		$regDataOk = false;
		unset($password1, $password);

		echo	"<p>",$langPassTwice,"</p>\n";
	}

	// CHECK EMAIL ADDRESS VALIDITY

    elseif( !empty($email) && ! eregi( $regexp, $email ))
	{
		$regDataOk = false;
		unset($password1, $password, $email);

		echo	"<p>",$langEmailWrong,".</p>\n";
	}

	// CHECK IF THE LOGIN NAME IS ALREADY OWNED BY ANOTHER USER

	else
	{
		$result = mysql_query("SELECT user_id FROM `$TABLEUSER` 
							   WHERE username=\"$uname\"");

		if (mysql_num_rows($result) > 0)
		{
			$regDataOk = false;
			unset($password1, $password, $uname);

			echo	"<p>",$langUserFree,"</p>";
		}
		else
		{
			$regDataOk = true;
		}
	}
}

if ( ! $regDataOk)
{
	echo	"<p>",
			"<a href=\"inscription.php?nom=",$nom,"&prenom=",$prenom,"&uname=",$uname,"&email=",$email,"\">",
			$langAgain,
			"</a>",
			"</p>\n";
}


/*> > > > > > > > > > > > REGISTRATION ACCEPTED < < < < < < < < < < < <*/

if ($regDataOk)
{
	/*-----------------------------------------------------
	  STORE THE NEW USER DATA INSIDE THE CLAROLINE DATABASE
	  -----------------------------------------------------*/

	mysql_query("INSERT INTO `".$TABLEUSER."`
	             SET `nom`      	= \"".$nom."\",
	                 `prenom`   	= \"".$prenom."\",
	                 `username` 	= \"".$uname."\",
	                 `password` 	= \"".($userPasswordCrypted?md5($password):$password)."\",
	                 `email`    	= \"".$email."\",
	                 `statut`   	= \"".$statut."\",
	                 `officialCode`	= \"".$officialCode."\"
					 ");

	$_uid = mysql_insert_id();

				/*
						@mysql_query("INSERT INTO `$mainDbName`.`user_hash`
									  (user_id, hash, state) 
									  VALUES ('$last_id', '$hash', 'WAITCHECK')");
				*/

if ($_uid)
{
	/*--------------------------------------
	          SESSION REGISTERING
	  --------------------------------------*/

	$_user['firstName']     = $prenom;
	$_user['lastName' ]     = $nom;
	$_user['mail'     ]     = $email;
	$is_allowedCreateCourse = ($statut == 1) ? true : false ;
        
    	session_register("_uid");
	session_register("_user");
	session_register("is_allowedCreateCourse");

        //stats
        @include("../inc/lib/events.lib.inc.php");
        event_login();
        // last user login date is now
        $user_last_login_datetime = 0; // used as a unix timestamp it will correspond to : 1 1 1970
        session_register('user_last_login_datetime');

	/*--------------------------------------
	             EMAIL NOTIFICATION
	  --------------------------------------*/
	

	// Lets predefine some variables. Be sure to change the from address!

	$emailto       = "\"$prenom $nom\" <$email>";
	$emailfromaddr =  $administrator["email"];
	$emailfromname = "$siteName";
	$emailsubject  = "[".$siteName."] $langYourReg";

	// The body can be as long as you wish, and any combination of text and variables

	$emailbody    = "$langDear $prenom $nom,\n
$langYouAreReg $siteName $langSettings $uname\n$langPass : $password\n$langAddress $siteName $langIs : $rootWeb\n$langProblem\n$langFormula,\n" .
$administrator["name"] . "\n $langManager $siteName\nT. " . $administrator["phone"] . "\n$langEmail : " . $administrator["email"] . "\n";

		/*
			if ($checkEmailByHAshSent)
			{
				$hash = md5($email).md5($REMOTE_ADDR);
				$emailbody .= $rootWeb."claroline/auth/checkEmail.php?hash=".$hash."&emailHash=".$email;
			}
			else
			{
				$hash = "ok";
			}
		*/

	// Here we are forming one large header line
	// Every header must be followed by a \n except the last
	$emailheaders = "From: " . $administrator["name"] . " <".$administrator["email"].">\n";
	$emailheaders .= "Reply-To: " . $administrator["email"] . ""; 

	// Because I predefined all of my variables, this mail() function looks nice and clean hmm?
	@mail( $emailto, $emailsubject, $emailbody, $emailheaders);
}

	echo "<p>$langDear $prenom $nom, $langPersonalSettings</p>\n";

	if($is_allowedCreateCourse)
	{
		echo "<p>",$langNowGoCreateYourCourse,"</p>\n";
		$actionUrl = "../create_course/add_course.php";
	}
	else
	{
		echo "<p>",$langNowGoChooseYourCourses,"</p>\n";
		$actionUrl = "../../index.php?cidReset=1";
	}

	echo	"<form action=\"",$actionUrl,"\"\n>",
			"<input type=\"submit\" name=\"next\" value=\"",$langNext,"\" validationmsg=\" ",$langNext," \">\n",
			"</form>\n";

}	// else Registration accepted

$already_second=1;

include($includePath."/claro_init_footer.inc.php");



/* Don't understant this part of the code -- so I comment it.

				$errorAddHash = mysql_errno();

				if ($errorAddHash && $checkEmailByHashSent)
				{
					// $checkEmailByHashSent is  true and userHash is missing

					$emailbody = "Error detected in ".__FILE__." <br>
					";
					switch($errorAddHash)
					{
						case "1146"  :
							$emailbody .= date(" d - m - Y -- H:I")."<br>
							<br>
							<font color=\"red\">
							Table : ".$mainDbName.".user_hash don't exist.  
							</font>
							<br><br>
							They must be created when \$checkEmailByHAshSent is on. (config.php)
							<br>
							<br>
							
							Error : ".$errorAddHash." : ".mysql_error()."<br><br>
							".$sqlIncriptUserHash."<hr>";
							break;
						default : 
							$emailbody .= " error : ".$errorAddHash." : ".mysql_error()."<br>
							".$sqlIncriptUserHash."<br>
							".date("B d, Y at I:M p");
					}

					$emailto = "\"$administratorSurname $administratorName\" <$emailAdministrator>";
					$emailsubject ="[".$siteName."] error with email hash";
					$emailheaders = "From: $administratorSurname $administratorName <$emailAdministrator>\n";
					$emailheaders .= "Reply-To: $emailAdministrator"; 
					// Because I predefined all of my variables, this mail() function looks nice and clean hmm?
					@mail( $emailto, $emailsubject, $emailbody, $emailheaders);
					echo $emailbody;
				};

				$result=mysql_query("SELECT user_id, nom, prenom 
									 FROM `".$TABLEUSER."`
									 WHERE user_id='$last_id'");

				while ($myrow = mysql_fetch_array($result)) 
				{
					$uid=$myrow[0];
					$nom=$myrow[1];
					$prenom=$myrow[2];
				}

				mysql_query("INSERT INTO `".$TABLELOGINOUT."` 
							 (loginout.idLog, loginout.id_user, loginout.ip, loginout.when, loginout.action) 
							 VALUES 
							 ('', '".$uid."', '".$REMOTE_ADDR."', NOW(), 'LOGIN')");
		*/
 ?>