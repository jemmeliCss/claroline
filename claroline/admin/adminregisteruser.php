<?php
# $Id$
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

// Lang files needed :

$langFile = "admin";

// initialisation of global variables and used libraries

include('../inc/claro_init_global.inc.php');
include($includePath."/lib/admin.lib.inc.php");
include($includePath."/lib/pager.lib.php");

if ($cidToEdit=="") {unset($cidToEdit);}

$userPerPage = 20; // numbers of user to display on the same page

if (! $_uid) exit("<center>You're not logged in !!</center></body>");
if ($cidToEdit=="") {$dialogBox ="ERROR : NO USER SET!!!";}


@include ($includePath."/installedVersion.inc.php");

// Deal with interbredcrumps

$interbredcrump[]= array ("url"=>$rootAdminWeb, "name"=> $langAdministrationTools);
$nameTools = $langEnrollUser;

//Header

include($includePath."/claro_init_header.inc.php");



//TABLES

$tbl_user             = $mainDbName."`.`user";
$tbl_courses        = $mainDbName."`.`cours";
$tbl_course_user    = $mainDbName."`.`cours_user";
$tbl_admin            = $mainDbName."`.`admin";
$tbl_todo            = $mainDbName."`.`todo";
$tbl_track_default    = $statsDbName."`.`track_e_default";// default_user_id
$tbl_track_login    = $statsDbName."`.`track_e_login";    // login_user_id



//------------------------------------
// Execute COMMAND section
//------------------------------------

switch ($cmd)
{
  case "delete" :
        delete_user($user_id);
        $dialogBox = "Delete of the user was done sucessfully";
        break;

  case "unsubscribe" :
        $done = remove_user_from_course($user_id, $cidToEdit);
        if ($done)
        {
           $dialogBox =$langUserUnsubscribed;
        }
        else
        {
           $dialogBox =$langUserNotUnsubscribed;
        }
        break;
}

//build and call DB to get info about current course (for title) if needed :

$sql = "SELECT *
        FROM  `".$tbl_courses."`
        WHERE `code`='".$cidToEdit."'
        ";
$queryCourse =  claro_sql_query($sql);
$resultCourse = mysql_fetch_array($queryCourse);



//----------------------------------
// Build query and find info in db
//----------------------------------


$sql = "SELECT *, CU.statut AS stat
        FROM  `".$tbl_user."` AS U
        ";

$toAdd = ", `".$tbl_course_user."` AS CU WHERE
          CU.`code_cours` = '".$cidToEdit."'
        ";

$sql.=$toAdd;

//deal with LETTER classification call

if (isset($_GET['letter']))
{
    $toAdd = "
             AND U.`nom` LIKE '".$_GET['letter']."%'
             ";
    $sql.=$toAdd;
}

//deal with KEY WORDS classification call

if (isset($_GET['search']))
{
    $toAdd = " AND ((U.`nom` LIKE '".$_GET['search']."%'
              OR U.`username` LIKE '".$_GET['search']."%'
              OR U.`prenom` LIKE '".$_GET['search']."%')) ";

    $sql.=$toAdd;
}

// deal with REORDER

if (isset($_GET['order_crit']))
{
    if ($_GET['order_crit']=="user_id")
    {
        $toAdd = " ORDER BY CU.`user_id` ".$_GET['dir'];
    }
    else
    {
        $toAdd = " ORDER BY `".$_GET['order_crit']."` ".$_GET['dir'];
    }
    $sql.=$toAdd;
}

echo $sql."<br>";

$myPager = new claro_sql_pager($sql, $offset, $userPerPage);
$resultList = $myPager->get_result_list();


//------------------------------------
// DISPLAY
//------------------------------------

// Display tool title

$nameTools .= " : ".$resultCourse['intitule'];

claro_disp_tool_title($nameTools);

// Display Forms or dialog box(if needed)

if($dialogBox)
  {
    claro_disp_message_box($dialogBox);
  }

//Display selectbox, alphabetic choice, and advanced search link search

  // ALPHABETIC SEARCH
/*
echo "<form name=\"indexform\" action=\"",$PHP_SELF,"\" method=\"GET\">
             ";

            if (isset($cidToEdit)) {$toAdd = "cidToEdit=".$cidToEdit;} else {$toAdd = "";}

            echo "<a href=\"",$PHP_SELF,"?".$toAdd."\"><b> ".$langAll."</b></a> | ";

            echo "<a href=\"",$PHP_SELF,"?letter=A&".$toAdd."\">A</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=B&".$toAdd."\">B</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=C&".$toAdd."\">C</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=D&".$toAdd."\">D</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=E&".$toAdd."\">E</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=F&".$toAdd."\">F</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=G&".$toAdd."\">G</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=H&".$toAdd."\">H</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=I&".$toAdd."\">I</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=J&".$toAdd."\">J</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=K&".$toAdd."\">K</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=L&".$toAdd."\">L</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=M&".$toAdd."\">M</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=N&".$toAdd."\">N</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=O&".$toAdd."\">O</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=P&".$toAdd."\">P</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=Q&".$toAdd."\">Q</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=R&".$toAdd."\">R</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=S&".$toAdd."\">S</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=T&".$toAdd."\">T</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=U&".$toAdd."\">U</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=V&".$toAdd."\">V</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=W&".$toAdd."\">W</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=X&".$toAdd."\">X</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=Y&".$toAdd."\">Y</a> | ";
            echo "<a href=\"",$PHP_SELF,"?letter=Z&".$toAdd."\">Z</a>";
            echo "
            <input type=\"text\" name=\"search\">
            <input type=\"hidden\" name=\"cidToEdit\" value=\"".$cidToEdit."\">
            <input type=\"submit\" value=\"".$langSearch."\">

      </form>
     ";
*/
     //TOOL LINKS

//Pager

if (isset($_GET['order_crit']))
{
  $addToURL = "&order_crit=".$_GET['order_crit']."&dir=".$_GET['dir'];
}

$myPager->disp_pager_tool_bar($PHP_SELF."?cidToEdit=".$cidToEdit.$addToURL);

// Display list of users

   // start table...

if ($_GET['dir']=="ASC")
{
    $dir = 'DESC';
}
else
{
    $dir = 'ASC';
}

   //columsn titles...

echo "<table class=\"claroTable\" width=\"100%\" border=\"0\" cellspacing=\"2\">

    <tr class=\"headerX\" align=\"center\" valign=\"top\">
       <th><a href=\"",$PHP_SELF,"?order_crit=user_id&dir=".$dir."&cidToEdit=".$cidToEdit."\">".$langUserid."</a></th>
       <th><a href=\"",$PHP_SELF,"?order_crit=nom&dir=".$dir."&cidToEdit=".$cidToEdit."\">".$langName."</a></th>
       <th><a href=\"",$PHP_SELF,"?order_crit=prenom&dir=".$dir."&cidToEdit=".$cidToEdit."\">".$langFirstName."</a></th>";

echo "<th>".$langEnrollAsManager."</th>
      <th>".$langEnrollAsStudent."</th>";

echo "</tr><tbody> ";

   // Start the list of users...

foreach($resultList as $list)
{
     echo "<tr>";

     //  Id

     echo "<td align=\"center\">".$list['user_id']."
           </td>";

     // lastname

     echo "<td align=\"left\">".$list['nom']."</td>";

     //  Firstname

     echo "<td align=\"left\">".$list['prenom']."</td>";

     // Register as user

     echo  "<td align=\"center\">\n",
                "<a href=\"",$PHP_SELF,"?cidToEdit=".$cidToEdit."&cmd=subscribe&user_id=".$list['user_id']."\" ",
                ">\n",
                "<img src=\"../img/enroll.gif\" border=\"0\" alt=\"$langUnsubscribe\" />\n",
                "</a>\n",
            "</td>\n";

     // Register as course manager


     echo  "<td align=\"center\">\n",
                "<a href=\"",$PHP_SELF,"?cidToEdit=".$cidToEdit."&cmd=subscribe&user_id=".$list['user_id']."\" ",
                ">\n",
                "<img src=\"../img/enroll.gif\" border=\"0\" alt=\"$langUnsubscribe\" />\n",
                "</a>\n",
            "</td>\n";

     echo "</tr>";
}

   // end display users table

echo "</tbody></table>";

//Pager

$myPager->disp_pager_tool_bar($PHP_SELF."?cidToEdit=".$cidToEdit.$addToURL);

?>

<?
include($includePath."/claro_init_footer.inc.php");
?>