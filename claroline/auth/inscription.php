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

$langFile = "registration";
include("../inc/claro_init_global.inc.php");
$interbredcrump[]= array ("url"=>"inscription.php", "name"=> $langRegistration);
include($includePath."/claro_init_header.inc.php");
include($includePath."/conf/profile.conf.inc.php");
$nameTools = "1";

define ("STUDENT",5);
define ("COURSEMANAGER",1);


// Forbidden to self-register Claroline 1.4.0
if(!$allowSelfReg and isset($allowSelfReg))
{
	echo "<BR><BR>You are not allowed here<BR><BR><BR><BR>";
}
else
{

?>

<h3>
	<?php echo $langRegistration ?>
</h3>

<form action="inscription_second.php" method="post">
<table width="100%" cellpadding="3" cellspacing="0" border="0">

    <tr>
        <td >
            <LABEL for="surname">
                <?php echo $langLastname;?>
            </LABEL>
            &nbsp; :
        </td>
        <td>
            <input type="text" size="40" name="prenom" id="surname" value="<?=$prenom?>">
        </td>
    </tr>

    <tr>
		<td >
			<LABEL for="name">
				<?php echo $langName ?>
			</LABEL>
			&nbsp; :
		</td>
		<td>
			<input type="text" size="40" id="name" name="nom" value="<?=$nom?>">
		</td>
	</tr>
<?
if (CONFVAL_ASK_FOR_OFFICIAL_CODE)
{
?>
    <tr>
        <td >
            <LABEL for="name">
                <?php echo $langOfficialCode ?>
            </LABEL>
            &nbsp; :
        </td>
        <td>
            <input type="text" size="40" id="name" name="officialCode" value="<?=$officialCode?>">
        </td>
    </tr>
<?
}
?>
    <tr>
        <td >
        </td>
        <td>
          <br>
        </td>
    </tr>

	<tr>
		<td>
			<LABEL for="username">
				<?php echo $langUsername ?>
			</LABEL>
			&nbsp;:
		</td>
		<td>
			<input type="text" size="40" name="uname" id="username" value="<?=$uname?>">
		</td>
	</tr>

	<tr>
		<td>
			<LABEL for="pass1">
				<?php echo $langPass ?>
			</LABEL>
			&nbsp;:
		</td>
		<td>
			<input type="password" size="40" name="password1" id="pass1" >
		</td>
	</tr>

	<tr>
		<td>
			<LABEL for="pass2">
				<?php echo $langPass ?>
			</LABEL> :
			<br>
			<small>
				(<?php echo $langConfirmation ?>)
			</small>
		</td>
		<td>
			<input type="password" size="40" name="password" id="pass2">
		</td>
	</tr>

    <tr>
        <td >
        </td>
        <td>
          <br>
        </td>
    </tr>

	<tr>
		<td>
			<LABEL for="email">
				<?php echo $langEmail;?>
			</LABEL> :
		</td>
		<td>
			<input type="text" size="40" name="email" id="email" value="<?=$email?>">
		</td>
	</tr>

    <tr>
        <td>
            <LABEL for="email">
                <?php echo $langPhone;?>
            </LABEL> :
        </td>
        <td>
            <input type="text" size="40" name="phone" id="phone" value="<?=$phone?>">
        </td>
    </tr>

	<tr>
		<td>
			<LABEL for="language">
				<?php echo $langStatus ?>
			</LABEL>
			:
		</td>
		<td>
		
<?php
// Deactivate Teacher Self-registration if $allowSelfRegProf=FALSE

if ($is_platformAdmin OR $allowSelfRegProf)
	{
	echo "
		<select name=\"statut\" id=\"language\">
		<option value=\"".STUDENT."\">$langRegStudent</option>
		<option value=\"".COURSEMANAGER."\">$langRegAdmin</option>
		</select>";
	}
else
	{
	echo "
		<input type=\"hidden\" name=\"statut\" id=\"language\" value=\"STUDENT\">
		$langRegStudent";
}
?>

</td></tr><tr>
<td>
	<input type="hidden" name="submitRegistration" value="true">
</td>
<td><input type="submit" value="<?php echo $langRegister;?>" ></td>
</tr>

</table>

</form>

<?php
}	// END else == $allowSelfReg
?>
<?php include ("../inc/claro_init_footer.inc.php");