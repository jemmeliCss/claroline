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

if (! $_uid) exit("<center>You're not logged in !!</center></body>");

if ($cidToEdit=="") {unset($cidToEdit);}

$userPerPage = 20; // numbers of user to display on the same page

//------------------------------------------------------------------------------------------------------------------------
//  USED SESSION VARIABLES
//------------------------------------------------------------------------------------------------------------------------

// clean session if needed

if ($_GET['newsearch']=="yes")
{
    session_unregister('admin_user_letter');
    session_unregister('admin_user_search');
    session_unregister('admin_user_firstName');
    session_unregister('admin_user_lastName');
    session_unregister('admin_user_userName');
    session_unregister('admin_user_mail');
    session_unregister('admin_user_action');
    session_unregister('admin_order_crit');
}

// deal with session variables for search criteria, it depends where we come from :
// 1 ) we must be able to get back to the list that concerned the criteria we previously used (with out re entering them)
// 2 ) we must be able to arrive with new critera for a new search.

if (isset($_GET['letter']))    {$_SESSION['admin_user_letter'] = $_GET['letter'];}
if (isset($_GET['search']))    {$_SESSION['admin_user_search'] = $_GET['search'];}
if (isset($_GET['firstName'])) {$_SESSION['admin_user_firstName'] = $_GET['firstName'];}
if (isset($_GET['lastName']))  {$_SESSION['admin_user_lastName'] = $_GET['lastName'];}
if (isset($_GET['userName']))  {$_SESSION['admin_user_userName'] = $_GET['userName'];}
if (isset($_GET['mail']))      {$_SESSION['admin_user_mail'] = $_GET['mail'];}
if (isset($_GET['action']))    {$_SESSION['admin_user_action'] = $_GET['action'];}
if (isset($_GET['order_crit'])){$_SESSION['admin_user_order_crit'] = $_GET['order_crit'];}




@include ($includePath."/installedVersion.inc.php");

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
            function confirmation (name)
            {
                if (confirm(\"".$langAreYouSureToDelete."\"+ name + \"? \"))
                    {return true;}
                else
                    {return false;}
            }
            </script>";

// Deal with interbredcrumps

$interbredcrump[]= array ("url"=>$rootAdminWeb, "name"=> $langAdministrationTools);
$nameTools = $langListUsers;

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
        $dialogBox = $langUserDelete;
        break;
}

//----------------------------------
// Build query and find info in db
//----------------------------------


$sql = "SELECT *
        FROM  `".$tbl_user."` AS U WHERE 1=1
        ";

//deal with LETTER classification call

if (isset($_SESSION['admin_user_letter']))
{
    $toAdd = "
             AND U.`nom` LIKE '".$_SESSION['admin_user_letter']."%'
             ";
    $sql.=$toAdd;

}

//deal with KEY WORDS classification call

if (isset($_SESSION['admin_user_search']))
{
    $toAdd = " AND (U.`nom` LIKE '".$_SESSION['admin_user_search']."%'
              OR U.`prenom` LIKE '".$_SESSION['admin_user_search']."%') ";
    " OR U.`username` LIKE '".$_SESSION['admin_user_search']."%'";

    $sql.=$toAdd;

}

//deal with ADVANCED SEARCH parameters call

if (isset($_SESSION['admin_user_firstName']))
{
    $toAdd = " AND (U.`prenom` LIKE '".$_SESSION['admin_user_firstName']."%') ";
    $sql.=$toAdd;

}

if (isset($_SESSION['admin_user_lastName']))
{
    $toAdd = " AND (U.`nom` LIKE '".$_SESSION['admin_user_lastName']."%') ";
    $sql.=$toAdd;

}

if (isset($_SESSION['admin_user_userName']))
{
    $toAdd = " AND (U.`username` LIKE '".$_SESSION['admin_user_userName']."%') ";
    $sql.=$toAdd;

}

if (isset($_SESSION['admin_user_mail']))
{
    $toAdd = " AND (U.`email` LIKE '".$_SESSION['admin_user_mail']."%') ";
    $sql.=$toAdd;

}
if (isset($_GET['admin_user_action']))
{
    if ($_SESSION['admin_user_action']=="createcourse")
    {
       $toAdd = " AND (U.`statut`=1) ";
    }
    if ($_SESSION['admin_user_action']=="plateformadmin")
    {
       $toAdd = " AND (U.`statut`=1) ";
    }
    $sql.=$toAdd;

}


//deal with direction settings

if ($_GET['dir']=="ASC")
{
    $dir = 'DESC';
    $_SESSION['dir'] = 'DESC';
    $addToURL .="&dir=DESC";
}
if ($_GET['dir']=="DESC")
{
    $dir = 'ASC';
    $_SESSION['dir'] = 'ASC';
    $addToURL .="&dir=ASC";
}

if (($_GET['dir']!="DESC")&&($_GET['dir']!="ASC") && ($_SESSION['dir']!="ASC") &&($_SESSION['dir']!="DESC"))
{
  $_GET['dir'] = "ASC";
  $_SESSION['dir']!="ASC";
}

// deal with REORDER

if (isset($_SESSION['admin_user_order_crit']))
{
    $toAdd = " ORDER BY `".$_SESSION['admin_user_order_crit']."` ".$_SESSION['dir'];
    $sql.=$toAdd;

}

//echo $sql."<br>";

$myPager = new claro_sql_pager($sql, $offset, $userPerPage);
$resultList = $myPager->get_result_list();

//------------------------------------
// DISPLAY
//------------------------------------

// Display tool title

claro_disp_tool_title($nameTools);

//Display Forms or dialog box(if needed)

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

   //Display search form


      //see passed search parameters :

if ($_GET['search']!="")    {$isSearched .= $_GET['search']."* ";}
if ($_GET['firstName']!="") {$isSearched .= $langFirstName."=".$_GET['firstName']."* ";}
if ($_GET['lastName']!="")  {$isSearched .= $langLastName."=".$_GET['lastName']."* ";}
if ($_GET['userName']!="")  {$isSearched .= $langUsername."=".$_GET['userName']."* ";}
if ($_GET['mail']!="")      {$isSearched .= $langEmail."=".$_GET['mail']."* ";}
if ($_GET['action']=="createcourse")    {$isSearched .=  "<b> <br>".$langCourseCreator."  </b> ";}
if ($_GET['action']=="plateformadmin")    {$isSearched .= "<b> <br>".$langPlatformAdmin."  </b> ";}

     //see what must be kept for advanced links

$addtoAdvanced = "?firstName=".$_GET['firstName'];
$addtoAdvanced .="&lastName=".$_GET['lastName'];
$addtoAdvanced .="&userName=".$_GET['userName'];
$addtoAdvanced .="&mail=".$_GET['mail'];
$addtoAdvanced .="&action=".$_GET['action'];

    //finaly, form itself

if (($isSearched=="") || !isset($isSearched)) {$title = "";} else {$title = $langSearchOn." : ";}

echo "<table width=\"100%\">
        <tr>
          <td align=\"left\">
             <b>".$title."</b>
             <small>
             ".$isSearched."
             </small>
          </td>
          <td align=\"right\">
            <form action=\"",$PHP_SELF,"\">
            ".$langMakeNewSearch."
            <input type=\"text\" value=\"".$_GET['search']."\" name=\"search\"\">
            <input type=\"submit\" value=\" ".$langOk." \">
            <input type=\"hidden\" name=\"newsearch\" value=\"yes\">
            <a href=\"advancedUserSearch.php".$addtoAdvanced."\"><small>[".$langAdvanced."]</small></a>
            </form>
          </td>
        </tr>
      </table>
       ";

   //Pager

$myPager->disp_pager_tool_bar($PHP_SELF);


// Display list of users

   // start table...

echo "<table class=\"claroTable\" width=\"100%\" border=\"0\" cellspacing=\"2\">

     <tr class=\"headerX\" align=\"center\" valign=\"top\">
          <th><a href=\"",$PHP_SELF,"?order_crit=user_id&dir=".$_SESSION['dir']."\">".$langUserid."</a></th>
          <th><a href=\"",$PHP_SELF,"?order_crit=nom&dir=".$_SESSION['dir']."\">".$langName."</a></th>
          <th><a href=\"",$PHP_SELF,"?order_crit=prenom&dir=".$_SESSION['dir']."\">".$langFirstName."</a></th>
          <th>".$langOfficialCode."</th>";
echo     "<th>".$langUserStatus."</th>";
echo     "<th>".$langAllUserOfThisCourse."</th>
          <th>".$langEditUserSettings."</th>
          <th>".$langDelete."</th>";
echo "</tr><tbody> ";

   // Start the list of users...
foreach($resultList as $list)
//while ($list = mysql_fetch_array($query))
{
     echo "<tr>";

     //  Id

     echo "<td align=\"center\">".$list['user_id']."
           </td>";

     // name

     echo "<td align=\"left\">".$list['nom']."</td>";

     //  Firstname

     echo "<td align=\"left\">".$list['prenom']."</td>";

     //  Official code

     if (isset($list['officialCode'])) { $toAdd = $list['officialCode']; } else $toAdd = " - ";
     echo "<td align=\"center\">".$toAdd."</td>";

     // Status

     if (isAdminUser($list['user_id']))
     {
        $userStatus = $langAdministrator;
     }
     else
     {
        if ($list['statut']==1)
        {
          $userStatus = $langCourseCreator;
        }
        else
        {
          $userStatus = $langStudent;
        }
     }

     echo     "<td align=\"center\">\n
                         ".$userStatus.
              "</td>\n";

     // All course of this user

     echo     "<td align=\"center\">\n",
                        "<a href=\"adminusercourses.php?uidToEdit=".$list['user_id']."&cfrom=ulist".$addToURL."\">\n
                         ".$langViewList."\n",
                        "</a>\n",
                        "</td>\n";

     // Modify link

     echo     "<td align=\"center\">\n",
                        "<a href=\"adminprofile.php?uidToEdit=".$list['user_id']."&cfrom=ulist".$addToURL."\">\n
                         <img src=\"../img/edit.gif\" border=\"0\" alt=\"$langModify\" />\n",
                        "</a>\n",
                        "</td>\n";

     //  Delete link

     echo   "<td align=\"center\">\n",
                "<a href=\"",$PHP_SELF,"?cmd=delete&user_id=".$list['user_id']."&offset=".$offset."".$addToURL."\" ",
                "onClick=\"return confirmation('",addslashes($list['username']),"');\">\n",
                "<img src=\"../img/deluser.gif\" border=\"0\" alt=\"$langDelete\" />\n",
                "</a>\n",
            "</td>\n";
     echo "</tr>";
     $atLeastOne= true;
}
   // end display users table
if (!$atLeastOne)
{
   echo "<tr>
          <td colspan=\"8\" align=\"center\">
            ".$langNoUserResult."<br>
            <a href=\"advancedUserSearch.php".$addtoAdvanced."\">".$langSearchAgain."</a>
          </td>
         </tr>";
}
echo "</tbody></table>";

//Pager

$myPager->disp_pager_tool_bar($PHP_SELF);

?>

<?
function isAdminUser($user_id)
{
    global $tbl_admin;

    $sql = "SELECT * FROM `".$tbl_admin."` WHERE `idUser`=".$user_id."";
    $result = mysql_query($sql);
    if (mysql_num_rows($result)>0)
    {
      return true;
    }
    else
    {
      return false;
    }
}

include($includePath."/claro_init_footer.inc.php");

?>