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

define ("NB_LINE_OF_EVENTS", 15);
define ("CHECK_PASS_EASY_TO_FIND", true);
define ("USER_SELECT_FORM", 1);
define ("USER_DATA_FORM", 2);

$langFile='admin';
$cidReset = true;
include('../inc/claro_init_global.inc.php');
include($includePath.'/lib/text.lib.php');
include($includePath.'/lib/admin.lib.inc.php');

$nameTools=$langModifUserCourseSettings;

$interbredcrump[]= array ("url"=>$rootAdminWeb, "name"=> $langAdministrationTools);

  // javascript confirm pop up declaration

  $htmlHeadXtra[] =
         "<style type=text/css>
         <!--
         .comment { margin-left: 30px}
         .invisible {color: #999999}
         .invisible a {color: #999999}
         -->
         </style>";

   $htmlHeadXtra[] =
            "<script>
            function confirmationUnReg (name)
            {
                if (confirm(\"".$langAreYouSureToUnsubscribe."\"+ name + \"? \"))
                    {return true;}
                else
                    {return false;}
            }
            </script>";

// used tables

$tbl_log     = $mainDbName."`.`loginout";
$tbl_user     = $mainDbName."`.`user";
$tbl_admin  = $mainDbName."`.`admin";
$tbl_course = $mainDbName."`.`cours";
$tbl_course_user = $mainDbName."`.`cours_user";

include($includePath.'/claro_init_header.inc.php');

// deal with sesison variables (must unset variables if come back from enroll script)

session_unregister("userEdit");


// see which user we are working with ...

$user_id = $_GET['uidToEdit'];

//echo $user_id."<br>";

//------------------------------------
//FIND GLOBAL INFO SECTION
//------------------------------------

if(isset($user_id))

{


    $sqlGetInfoUser ="
    SELECT *
        FROM  `".$tbl_user."`
        WHERE user_id='$user_id'";
    $result=mysql_query($sqlGetInfoUser) or die("Erreur SELECT FROM user");
    //echo $sqlGetInfoUser;

    $myrow = mysql_fetch_array($result);
    $user_id =          $myrow[user_id];
    $nom_form =         $myrow[nom];
    $prenom_form =         $myrow[prenom];
    $username_form =     $myrow[username];
    $email_form    =        $myrow[email];
    $userphone_form =   $myrow[phoneNumber];
    $display = USER_DATA_FORM;

    // find global course info

    $sql = "SELECT * FROM `".$tbl_course."`
            WHERE code='".$cidToEdit."'
            ";
    $resultCourse = claro_sql_query($sql);
    $courseList = mysql_fetch_array($resultCourse);

    // find user settings, must see if the user is teacher for the course

    $sql = "SELECT * FROM `".$tbl_course_user."`
            WHERE user_id='$user_id'
            ";

    $resultCourseUser = claro_sql_query($sql);
    $list = mysql_fetch_array($resultCourseUser);

    if ($list['statut'] == 1)
    {
       $isCourseManager = true;
    }
    else
    {
       $isStudent = true;
    }
}



//------------------------------------
// Execute COMMAND section
//------------------------------------

switch (isset($cmd))
{
   case "changeStatus" :

        if ($_GET['status'] == "teacher")
        {
            $properties['status'] = 1;
            $properties['role']   = "Professor";
            $properties['tutor']  = 1;
            $done = update_user_course_properties($user_id, $cidToEdit, $properties);
            if ($done)
            {
               $isCourseManager = true;
               $dialogBox = $langUserIsNowCourseManager;
            }
            else
            {
               $dialogBox = $langStatusChangeFailed;
            }

        }
        else
        {
            $properties['status'] = 5;
            $properties['role']   = null;
            $properties['tutor']  = 0;
            $done = update_user_course_properties($user_id, $cidToEdit, $properties);
            if ($done)
            {
               $isCourseManager = false;
               $isStudent =true;
               $dialogBox = $langUserIsNowStudent;
            }
            else
            {
               $dialogBox = $langStatusChangeFailed;
            }
        }


        break;
}
//------------------------------------
// DISPLAY
//------------------------------------


// Display tool title

claro_disp_tool_title($nameTools." : ".$courseList['intitule']);

  //subttitle
?>
<h4><?php echo $langUser." : ".$prenom_form." ".$nom_form; ?></h4>
<?

//Display Forms or dialog box(if needed)

if($dialogBox)
  {
    claro_disp_message_box($dialogBox);
  }

//Display "form and info" about the user

?>

<form method="GET" action="<?php echo $PHP_SELF ?>">
<table width="100%" >

            <tr>
               <td><?=$langUserStatus?> : </td>
               <td>
                 <input type="radio" name="status" value="student" <? if ($isStudent) { echo "checked"; }?> ><?=$langStudent?></input>
                 <input type="radio" name="status" value="teacher" <? if ($isCourseManager) { echo "checked"; }?> ><?=$langCourseManager?></input>
                 <input type="hidden" name="uidToEdit" value="<?=$user_id?>">
                 <input type="hidden" name="cidToEdit" value="<?=$cidToEdit?>">
                 <input type="hidden" name="cfrom" value="<?=$cfrom?>">
                 <input type="submit" name="applyChange" value="<?=$langSaveChanges?>">
                 <input type="hidden" name="cmd" value="changeStatus">
                 <input type="hidden" name="cfrom" value="<?=$cfrom?>">
                 <input type="hidden" name="ccfrom" value="<?=$ccfrom?>">
               </td>
            </tr>
     </table>
</form>

<?php

// display TOOL links :

echo "<a class=\"claroButton\" href=\"adminuserunregistered.php?cidToEdit=".$cidToEdit."&cmd=UnReg&uidToEdit=".$user_id."\"
       onClick=\"return confirmationUnReg('",addslashes($prenom_form." ".$nom_form),"');\" >  ".$langUnsubscribe." </a>\n";
echo "<a class=\"claroButton\" href=\"adminProfile.php?uidToEdit=".$uidToEdit."\"> ".$langGoToMainUserSettings." </a>\n";
       //link to go back to list : depend where we come from...

if ($ccfrom=="culist")//coming from courseuser list
{
    echo "<a class=\"claroButton\" href=\"admincourseusers.php?cidToEdit=".$cidToEdit."&uidToEdit=".$uidToEdit."\"> ".$langBackToList." </a>\n";
}
elseif ($ccfrom=="uclist")//coming form usercourse list
{
    echo "<a class=\"claroButton\" href=\"adminusercourses.php?cidToEdit=".$cidToEdit."&uidToEdit=".$uidToEdit."\"> ".$langBackToList." </a>\n";
}

// display footer

include($includePath."/claro_init_footer.inc.php");
?>