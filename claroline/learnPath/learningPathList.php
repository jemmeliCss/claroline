<?php  // $Id$
/*
  +----------------------------------------------------------------------+
  | CLAROLINE version 1.5.*
  +----------------------------------------------------------------------+
  | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
  +----------------------------------------------------------------------+
  | This source file is subject to the GENERAL PUBLIC LICENSE,           |
  | available through the world-wide-web at                              |
  | http://www.gnu.org/copyleft/gpl.html                                 |
  +----------------------------------------------------------------------+
  | Authors: Piraux Sebastien <pir@cerdecam.be>                          |
  |          Lederer Guillaume <led@cerdecam.be>                         |
  +----------------------------------------------------------------------+

  DESCRIPTION:
  ***********
  This file display the list of all learning paths availables for the
  course.

  Display :
  - Name of tool
  - Introduction text for learning paths
  - (admin of course) link to create new empty learning path
  - (admin of course) link to import (upload) a learning path
  - list of available learning paths
    - (student) only visible learning paths
    - (student) the % of progression into each learning path
    - (admin of course) all learning paths with
       - modify, delete, statistics, visibility and order, options


*/

/*======================================
       CLAROLINE MAIN
  ======================================*/

  $langFile = "learnPath";
  $tlabelReq = 'CLLNP___';
  include ('../inc/claro_init_global.inc.php');

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
                if (confirm(\"".$langAreYouSureToDelete."\"+ name + \"? ".$langModuleStillInPool."\"))
                    {return true;}
                else
                    {return false;}
            }
            </script>";
  $htmlHeadXtra[] =
           "<script>
            function scormConfirmation (name)
            {
                if (confirm(\" $langAreYouSureToDeleteScorm \"+ name + \" ?\"))
                    {return true;}
                else
                    {return false;}
            }
            </script>";

  $nameTools = $langLearningPathList;
  //header
  include($includePath."/claro_init_header.inc.php");
  
  
  if ( ! $is_courseAllowed) claro_disp_auth_form();

  // tables names
  $TABLELEARNPATH         = $_course['dbNameGlu']."lp_learnPath";
  $TABLEMODULE            = $_course['dbNameGlu']."lp_module";
  $TABLELEARNPATHMODULE   = $_course['dbNameGlu']."lp_rel_learnPath_module";
  $TABLEASSET             = $_course['dbNameGlu']."lp_asset";
  $TABLEUSERMODULEPROGRESS= $_course['dbNameGlu']."lp_user_module_progress";

  //lib of this tool
  include($includePath."/lib/learnPath.lib.inc.php");

  //lib needed to delete packages
  include("../inc/lib/fileManage.lib.php");

  // statistics
  include($includePath."/lib/events.lib.inc.php");
  event_access_tool($langLearningPath);

  // title
  claro_disp_tool_title($nameTools);

  // main page
  $is_AllowedToEdit = $is_courseAdmin;
  $lpUid = $_uid;

  // display introduction
  $moduleId = 6; // Id of the Learning Path introduction Area
  $langHelpAddIntroText=$langIntroLearningPath;
  include($includePath."/introductionSection.inc.php");


   // execution of commands
   switch ($cmd)
   {
        // DELETE COMMAND
        case "delete" :

              // delete learning path
              // have to delete also learningPath_module using this learningPath
              // The first multiple-table delete format is supported starting from MySQL 4.0.0. The second multiple-table delete format is supported starting from MySQL 4.0.2.
              /*  this query should work with mysql > 4
              $sql = "DELETE
                        FROM `".$TABLELEARNPATHMODULE."`,
                             `".$TABLEUSERMODULEPROGRESS."`,
                             `".$TABLELEARNPATH."`
                        WHERE `".$TABLELEARNPATHMODULE."`.`learnPath_module_id` = `".$TABLEUSERMODULEPROGRESS."`.`learnPath_module_id`
                          AND `".$TABLELEARNPATHMODULE."`.`learnPath_id` = `".$TABLELEARNPATH."`.`learnPath_id`
                          AND `".$TABLELEARNPATH."`.`learnPath_id` = ".$_GET['path_id'] ;
              */
              // so we use a multiple query method


              // in case of a learning path made by SCORM, we completely remove files and use in others path of the imported package
              // First we save the module_id of the SCORM modules in a table in case of a SCORM imported package

              if (is_dir("../../".$_course['path']."/scormPackages/path_".$_GET['del_path_id']))
              {
                   $findsql = "SELECT M.`module_id`
                                   FROM  `".$TABLELEARNPATHMODULE."` AS LPM,
                                        `".$TABLEMODULE."` AS M
                                   WHERE LPM.`learnPath_id` = ".$_GET['del_path_id']."
                                   AND 
                                      ( M.`contentType` = '".CTSCORM_."'
                                        OR
                                        M.`contentType` = '".CTLABEL_."'
                                      )
                                   AND LPM.`module_id` = M.`module_id`
                                   ";
                   //echo $findsql;
                   $findResult =claro_sql_query($findsql);

                   // Delete the startAssets

                   $delAssetSql = "DELETE
                                      FROM `".$TABLEASSET."`
                                      WHERE 1=0
                                      ";

                    while ($delList = mysql_fetch_array($findResult))
                    {
                      $delAssetSql .= " OR `module_id`=".$delList['module_id'];
                    }

                    claro_sql_query($delAssetSql);

                    //echo $delAssetSql."<br>";

                    // DELETE the SCORM modules

                    $delModuleSql = "DELETE
                                      FROM `".$TABLEMODULE."`
                                      WHERE (`contentType` = '".CTSCORM_."' OR `contentType` = '".CTLABEL_."')
                                      AND (1=0
                                      ";

                    if (mysql_num_rows($findResult)>0)
                    {
                       mysql_data_seek($findResult,0);
                    }

                    while ($delList = mysql_fetch_array($findResult))
                    {

                       $delModuleSql .= " OR `module_id`=".$delList['module_id'];

                    }
                    $delModuleSql .= ")";

                    //echo $delModuleSql."<br>";

                    claro_sql_query($delModuleSql);

                    // DELETE the directory containing the package and all its content
                    $real = realpath("../../".$_course['path']."/scormPackages/path_".$_GET['del_path_id']);
                    removeDir($real);

              }   // end of dealing with the case of a scorm learning path.
              else
              {
                  $findsql = "SELECT M.`module_id`
                                   FROM  `".$TABLELEARNPATHMODULE."` AS LPM,
                                        `".$TABLEMODULE."` AS M
                                   WHERE LPM.`learnPath_id` = ".$_GET['del_path_id']."
                                   AND M.`contentType` = '".CTLABEL_."'
                                   AND LPM.`module_id` = M.`module_id`
                                   ";
                 //echo $findsql;
                 $findResult =claro_sql_query($findsql);
                  // delete labels of non scorm learning path
                  $delLabelModuleSql = "DELETE
                                    FROM `".$TABLEMODULE."`
                                    WHERE 1=0
                                    ";
                    
                  while ($delList = mysql_fetch_array($findResult))
                  {
                    $delLabelModuleSql .= " OR `module_id`=".$delList['module_id'];
                  }
                  //echo $delLabelModuleSql;
                  $query = claro_sql_query($delLabelModuleSql);
              }

              // delete everything for this path (common to normal and scorm paths) concerning modules, progression and path
              // delete all user progression
              $sql1 = "DELETE
                         FROM `".$TABLEUSERMODULEPROGRESS."`
                         WHERE `learnPath_id` = ".$_GET['del_path_id'];
              $query = claro_sql_query($sql1);
              // delete all relation between modules and the deleted learning path
              $sql2 = "DELETE
                         FROM `".$TABLELEARNPATHMODULE."`
                         WHERE `learnPath_id` = ".$_GET['del_path_id'];
              $query = claro_sql_query($sql2);
              // delete the learning path
              $sql3 = "DELETE
                            FROM `".$TABLELEARNPATH."`
                            WHERE `learnPath_id` = ".$_GET['del_path_id'] ;
              $query = claro_sql_query($sql3);
              
              break;


        // ACCESSIBILITY COMMAND
        case "mkBlock" :
        case "mkUnblock" :
              $cmd == "mkBlock" ? $blocking = 'CLOSE' : $blocking = 'OPEN';
              $sql = "UPDATE `".$TABLELEARNPATH."`
                              SET `lock` = '$blocking'
                              WHERE `learnPath_id` = ".$_GET['cmdid']."
                                AND `lock` != '$blocking'";
              $query = claro_sql_query ($sql);
              break;

        // VISIBILITY COMMAND
        case "mkVisibl" :
        case "mkInvisibl" :
              $cmd == mkVisibl ? $visibility = 'SHOW' : $visibility = 'HIDE';
              $sql = "UPDATE `".$TABLELEARNPATH."`
                         SET `visibility` = '$visibility'
                       WHERE `learnPath_id` = ".$_GET['visibility_path_id']."
                         AND `visibility` != '$visibility'";
              $query = claro_sql_query ($sql);
              break;

        // ORDER COMMAND
        case "moveUp" :
              $thisLearningPathId = $_GET['move_path_id'];
              $sortDirection = "DESC";
              break;
        case "moveDown" :
              $thisLearningPathId = $_GET['move_path_id'];
              $sortDirection = "ASC";
              break;
        case "changeOrder" :
              // $sortedTab = new Order => id learning path
              $sortedTab = setOrderTab( $_POST['id2sort'] );
              if ($sortedTab)
              {
                 foreach ( $sortedTab as $order => $LP_id )
                 {
                      // `order` is set to $order+1 only for display later
                      $sql = "UPDATE `".$TABLELEARNPATH."`
                                 SET `rank` = ".($order+1)."
                               WHERE `learnPath_id` = ".$LP_id;
                      claro_sql_query($sql);
                 }
              }
              break;

        // CREATE COMMAND
        case "create" :
              // create form sent
              if( isset($_POST["newPathName"]) && $_POST["newPathName"] != "")
              {

                 // check if name already exists
                 $sql = "SELECT `name`
                           FROM `".$TABLELEARNPATH."`
                          WHERE `name` = '".claro_addslashes($_POST['newPathName'])."'";
                 $query = claro_sql_query($sql);
                 $num = mysql_numrows($query);
                 if($num == 0 ) // "name" doesn't already exist
                 {
                        // determine the default order of this Learning path
                        $result = claro_sql_query("SELECT MAX(`rank`)
                                                 FROM `".$TABLELEARNPATH."`");

                        list($orderMax) = mysql_fetch_row($result);
                        $order = $orderMax + 1;
                        // create new learning path
                        $sql = "INSERT
                                  INTO `".$TABLELEARNPATH."`
                                       (`name`, `comment`, `rank`)
                                VALUES ('".claro_addslashes($_POST['newPathName'])."','".addslashes($langDefaultLearningPathComment)."',".$order.")";
                        //echo $sql;
                        $query = claro_sql_query($sql);
                        //echo $langOKNewPath;


                 }
                 else
                 {
                      // display error message
                      $dialogBox = $langErrorNameAlreadyExists;
                 }
              }
              else  // create form requested
              {
                 echo "<form method=\"POST\">
                        <h4><label for=\"newPathName\">".$langCreateNewLearningPath."</label></h4>
                        ".$langLearningPathName."<input type=\"text\" name=\"newPathName\" id=\"newPathName\" maxlength=\"255\"></input>
                        <input type=\"hidden\" name=\"cmd\" value=\"create\">
                        <input type=\"submit\" value=\"".$langOk."\"></input>
                      </form>";
              }
              break;
   }
   // IF ORDER COMMAND RECEIVED
   // CHANGE ORDER
   if ($sortDirection)
   {
        $result = claro_sql_query("SELECT `learnPath_id`, `rank`
                                 FROM `".$TABLELEARNPATH."`
                             ORDER BY `rank` $sortDirection");
        // LP = learningPath
        while (list ($LPId, $LPOrder) = mysql_fetch_row($result))
        {
            // STEP 2 : FOUND THE NEXT ANNOUNCEMENT ID AND ORDER.
            //          COMMIT ORDER SWAP ON THE DB

            if (isset($thisLPOrderFound)&&$thisLPOrderFound == true)
            {
                $nextLPId = $LPId;
                $nextLPOrder = $LPOrder;

                // move 1 to a temporary rank
                claro_sql_query("UPDATE `".$TABLELEARNPATH."`
                                SET `rank` = \"-1337\"
                              WHERE `learnPath_id` =  \"$thisLearningPathId\"");
                // move 2 to the previous rank of 1
                claro_sql_query("UPDATE `".$TABLELEARNPATH."`
                                SET `rank` = \"$thisLPOrder\"
                              WHERE `learnPath_id` =  \"$nextLPId\"");
                // move 1 to previous rank of 2
                claro_sql_query("UPDATE `".$TABLELEARNPATH."`
                                SET `rank` = \"$nextLPOrder\"
                              WHERE `learnPath_id` =  \"$thisLearningPathId\"");

                break;
            }

            // STEP 1 : FIND THE ORDER OF THE ANNOUNCEMENT
            if ($LPId == $thisLearningPathId)
            {
                $thisLPOrder = $LPOrder;
                $thisLPOrderFound = true;
            }
        }
   }

  if($dialogBox)
  {
    claro_disp_message_box($dialogBox);
  }

   if($is_AllowedToEdit)
   {
        // Display links to create and import a learning path
        // and display header of the 'change order' form
   ?>
        <form action="<?= $PHP_SELF ?>" method="POST">
        <table border="0" align="center" cellpadding="2" cellspacing="2" width="100%">
         <tr>
           <td>
                 <a href="<?= $PHP_SELF ?>?cmd=create"><?= $langCreateNewLearningPath; ?></a> |
                 <a href="importLearningPath.php"><?= $langimportLearningPath; ?></a> |
                 <a href="modules_pool.php"><?= $langModulesPoolToolName ?></a>
           </td>
         </tr>
         </table>
   <?PHP
   }


   // Display list of available training paths


  /*
   This is for dealing with the block in the sequence of learning path,  the idea is to make only one request to get the credit
   of last module of learning paths to know if the rest of the sequence mut be blocked or not, does NOT work yet ;) ...

  $sql="SELECT LPM.`learnPath_module_id` AS LPMID, LPM.`learnpath_id`, MAX(`rank`) AS M, UMP.`credit` AS UMPC
                FROM `".$TABLELEARNPATHMODULE."` AS LPM
                RIGHT JOIN `".$TABLEUSERMODULEPROGRESS."` AS UMP
                ON LPM.`learnPath_module_id` = UMP.`learnPath_module_id`
                WHERE `user_id` = ".$lpUid."
                GROUP BY LPM.`learnpath_id`
                ";


   echo $sql."<br>";
   $resultB = claro_sql_query($sql);

   echo mysql_error();

   while ($listB = mysql_fetch_array($resultB))
          {
          echo "LPMID : ".$listB['LPMID']." rank : ".$listB['M']." LPID : ".$listB['learnpath_id']." credit : ".$listB['UMPC']."<br>";
          }

   $resultB = claro_sql_query($sql);
   */

   echo "<table class=\"claroTable\" width=\"100%\" border=\"0\" cellspacing=\"2\">

    <tr class=\"headerX\" align=\"center\" valign=\"top\">
     <th>".$langLearningPath."</th>";

   if($is_AllowedToEdit)
   {
        // Titles for teachers
        echo "<th>".$langModify."</th>"
               ."<th>".$langDelete."</th>"
               ."<th>".$langBlock."</th>"
               ."<th>".$langVisible."</th>"
               ."<th colspan=\"2\">".$langOrder."</th>"
               ."<th>".$langTracking."</th>";
   }
   elseif($lpUid)
   {
      // display progression only if user is not teacher && not anonymous
      echo "<th colspan=\"2\">".$langProgress."</th>";
   }
   // close title line
   echo "</tr>\n<tbody>";

  // display invisible learning paths only if user is courseAdmin
  if ($is_AllowedToEdit)
  {
      $visibility = "";
  }
  else
  {
      $visibility = " AND LP.`visibility` = 'SHOW' ";
  }
  // check if user is anonymous
  if($lpUid)
  {
      $uidCheckString = "AND UMP.`user_id` = ".$lpUid;
  }
  else // anonymous
  {
      $uidCheckString = "AND UMP.`user_id` IS NULL ";
  }

  // list available learning paths
  $sql = "SELECT LP.* , MIN(UMP.`raw`) AS minRaw, LP.`lock`
             FROM `".$TABLELEARNPATH."` AS LP
       LEFT JOIN `".$TABLELEARNPATHMODULE."` AS LPM
              ON LPM.`learnPath_id` = LP.`learnPath_id`
       LEFT JOIN `".$TABLEUSERMODULEPROGRESS."` AS UMP
              ON UMP.`learnPath_module_id` = LPM.`learnPath_module_id`
              ".$uidCheckString."
           WHERE 1=1
               ".$visibility."
        GROUP BY LP.`learnPath_id`
        ORDER BY LP.`rank`";

  $result = claro_sql_query($sql);

  // used to know if the down array (for order) has to be displayed
  $LPNumber = mysql_num_rows($result);
  $iterator = 1;

  $is_blocked = false;
   while ( $list = mysql_fetch_array($result) ) // while ... learning path list
   {

       if ( $list['visibility'] == 'HIDE' )
       {
               if ($is_AllowedToEdit)
               {
                       $style=" class=\"invisible\"";
               }
               else
               {
                       continue; // skip the display of this file
               }
       }
       else
       {
               $style="";
       }

       echo "<tr align=\"center\"".$style.">";

       //Display current learning path name

       if ( !$is_blocked )
       {
             echo "<td align=\"left\"><a href=\"learningPath.php?path_id="
                       .$list['learnPath_id']."\"><img src=\"../img/step.gif\" alt=\"".$path_alt."\"
                       border=\"0\" />  ".$list['name']."</a></td>";

             /*if( $list['lock'] == 'CLOSE' && ( $list['minRaw'] == -1 || $list['minRaw'] == "" ) )
             {
                   if($lpUid)
                   {
                       if ( !$is_AllowedToEdit )
                       {
                                $is_blocked = true;
                       } // never blocked if allowed to edit
                   }
                   else // anonymous : don't display the modules that are unreachable
                   {
                       break ;
                   }
             } */

             // --------------TEST IF FOLLOWING PATH MUST BE BLOCKED------------------
             // ---------------------(MUST BE OPTIMIZED)------------------------------

             // step 1. find last visible module of the current learning path in DB

             $blocksql = "SELECT `learnPath_module_id`
                                  FROM `".$TABLELEARNPATHMODULE."`
                                  WHERE `learnPath_id`=".$list['learnPath_id']."
                                  AND `visibility` = \"SHOW\"
                                  ORDER BY `rank` DESC
                                  LIMIT 1
                                  ";

            //echo $blocksql;

             $resultblock = claro_sql_query($blocksql);

             // step 2. see if there is a user progression in db concerning this module of the current learning path

             $number = mysql_num_rows($resultblock);
             if ($number != 0)
             {
                 $listblock = mysql_fetch_array($resultblock);
                 $blocksql2 = "SELECT `credit`
                                       FROM `".$TABLEUSERMODULEPROGRESS."`
                                       WHERE `learnPath_module_id`=".$listblock['learnPath_module_id']."
                                              AND `user_id`='".$lpUid."'
                                              ";

                 $resultblock2 = claro_sql_query($blocksql2);
                 $moduleNumber = mysql_num_rows($resultblock2);
             }
             else
             {
                //echo "no module in this path!";
                $moduleNumber = 0;
             }
                 //2.1 no progression found in DB

             if (($moduleNumber == 0)  && ($list['lock'] == 'CLOSE'))
             {
                 //must block next path because last module of this path never tried!

                 if($lpUid)
                   {
                       if ( !$is_AllowedToEdit )
                       {
                                $is_blocked = true;
                       } // never blocked if allowed to edit
                   }
                   else // anonymous : don't display the modules that are unreachable
                   {
                      $iterator++; // trick to avoid having the "no modules" msg to be displayed
                      break;
                   }
              }

                 //2.2. deal with progression found in DB if at leats one module in this path

              if ($moduleNumber!=0)
              {
                 $listblock2 = mysql_fetch_array($resultblock2);

                 if (($listblock2['credit']=="NO-CREDIT") && ($list['lock'] == 'CLOSE'))
                  {
                     //must block next path because last module of this path not credited yet!
                     if($lpUid)
                       {
                           if ( !$is_AllowedToEdit )
                           {
                                    $is_blocked = true;
                           } // never blocked if allowed to edit
                       }
                       else // anonymous : don't display the modules that are unreachable
                       {
                          break ;
                       }
                  }
               }
              //----------------------------------------------------------------------


             /*   This is for dealing with the block in the sequence of learning path,  the idea is to make only one request to get the credit
                  of last module of learning paths to know if the rest of the sequence mut be blocked or not, does NOT work yet ;) ...

             if (mysql_num_rows($resultB) != 0) {mysql_data_seek($resultB,0);}

             while ($listB = mysql_fetch_array($resultB))
             {
               echo  "lp_id listB: ".$listB['learnpath_id']." lp_id list: ".$list['learnPath_id']." creditUMP: ".$listB['UMPC']." Lplock: ".$list['lock']."<br>";

                if (($listB['learnpath_id']==$list['learnPath_id']) && ($listB['UMPC']=="NO-CREDIT") && ($list['lock'] == "CLOSE"))
                {
                   echo "ok";
                   if($lpUid)
                   {
                       if ( !$is_AllowedToEdit )
                       {
                                 echo "on va bloquer pour LPMID : ".$listB['LPMID'];
                                $is_blocked = true;
                       } // never blocked if allowed to edit
                   }
                   else // anonymous : don't display the modules that are unreachable
                   {
                       break ;
                   }
                }
             }

             //must also block if no usermoduleprogress exists in DB for this user.

             $LPMNumberB = mysql_num_rows($resultB);
             if (($LPMNumberB == 0) && ($list['lock'] == "CLOSE"))
             {
               echo "ok2";
                   if($lpUid)
                   {
                       if ( !$is_AllowedToEdit )
                       {
                                 echo "on va bloquer pour LPMID : ".$listB['LPMID'];
                                $is_blocked = true;
                       } // never blocked if allowed to edit
                   }
                   else // anonymous : don't display the modules that are unreachable
                   {
                       break ;
                   }
             }
              */
             //------------------------------------------------------------------------
       }
       else   //else of !$is_blocked condition , we have already been blocked before, so we continue beeing blocked : we don't display any links to next paths any longer
       {
             echo "<td align=\"left\"> <img src=\"../img/step.gif\" alt=\"".$path_alt."\"
                       border=\"0\" /> ".$list['name'].$list['minRaw']."</td>\n";
       }

       // DISPLAY ADMIN LINK-----------------------------------------------------------

       if($is_AllowedToEdit)
       {
            // 5 administration columns

            // Modify command / go to other page
            echo     "<td>\n",
                        "<a href=\"learningPathAdmin.php?path_id=".$list['learnPath_id']."\">\n",
                        "<img src=\"../img/edit.gif\" border=\"0\" alt=\"$langModify\" />\n",
                        "</a>\n",
                        "</td>\n";


            // DELETE link

                $real = realpath("../../".$_course['path']."/scormPackages/path_".$list['learnPath_id']);
            //}

            // check if the learning path is of a Scorm import package and add right popup:

            if (is_dir($real))
            {
               echo  "<td>\n",
                            "<a href=\"",$PHP_SELF,"?cmd=delete&del_path_id=".$list['learnPath_id']."\" ",
                            "onClick=\"return scormConfirmation('",addslashes($list['name']),"');\">\n",
                            "<img src=\"../img/delete.gif\" border=\"0\" alt=\"$langDelete\" />\n",
                            "</a>\n",
                            "</td>\n";

            }
            else
            {
               echo     "<td>\n",
                            "<a href=\"",$PHP_SELF,"?cmd=delete&del_path_id=".$list['learnPath_id']."\" ",
                            "onClick=\"return confirmation('",addslashes($list['name']),"');\">\n",
                            "<img src=\"../img/delete.gif\" border=\"0\" alt=\"$langDelete\" />\n",
                            "</a>\n",
                            "</td>\n";

            }

            // LOCK link

            echo    "<td>";
            if ( $list['lock'] == 'OPEN')
            {
                echo    "<a href=\"",$PHP_SELF,"?cmd=mkBlock&cmdid=".$list['learnPath_id']."\">\n",
                        "<img src=\"../img/unblock.gif\" alt=\"$langAltMakeBlocking\" border=\"0\">\n",
                        "</a>\n";
            }
            else
            {
                echo    "<a href=\"",$PHP_SELF,"?cmd=mkUnblock&cmdid=".$list['learnPath_id']."\">\n",
                        "<img src=\"../img/block.gif\" alt=\"$langAltMakeNotBlocking\" border=\"0\">\n",
                        "</a>\n";
            }
            echo    "</td>\n";

            // VISIBILITY link

            echo    "<td>\n";

            if ( $list['visibility'] == 'HIDE')
            {
                echo    "<a href=\"",$PHP_SELF,"?cmd=mkVisibl&visibility_path_id=".$list['learnPath_id']."\">\n",
                        "<img src=\"../img/invisible.gif\" alt=\"$langAltMakeVisible\" border=\"0\" />\n",
                        "</a>";
            }
            else
            {
                if ($list['lock']=='CLOSE')
                {
                        $onclick = "onClick=\"return confirm('".$langAlertBlockingPathMadeInvisible."');\"";
                }
                else
                {
                        $onclick = "";
                }

                echo    "<a href=\"",$PHP_SELF,"?cmd=mkInvisibl&visibility_path_id=".$list['learnPath_id']."\" ",$onclick, " >\n",
                        "<img src=\"../img/visible.gif\" alt=\"$langAltMakeInvisible\" border=\"0\" />\n",
                        "</a>\n";
            }
            echo    "</td>\n";




            // link to statistics / go to other page
            // target must be modified
            /*
            echo     "<td>",
                        "<a href=\"",$PHP_SELF,"?path_id=".$list['learningPath_id']."\">",
                        "<img src=\"../img/statistiques.gif\" alt=\"$langStatistics\" border=\"0\" />",
                        "</a>",
                        "</td>\n";
            */


            // ORDER links

            // DISPLAY MOVE UP COMMAND only if it is not the top learning path
            if ($iterator != 1)
            {
                echo     "<td>\n",
                         "<a href=\"",$PHP_SELF,"?cmd=moveUp&move_path_id=".$list['learnPath_id']."\">\n",
                         "<img src=\"../img/up.gif\" alt=\"$langAltMoveUp\" border=\"0\" />\n",
                         "</a>\n",
                         "</td>\n";
            }
            else
            {
                echo "<td>&nbsp;</td>\n";
            }
            // DISPLAY MOVE DOWN COMMAND only if it is not the bottom learning path
            if($iterator < $LPNumber)
            {
                echo    "<td>\n",
                        "<a href=\"",$PHP_SELF,"?cmd=moveDown&move_path_id=".$list['learnPath_id']."\">\n",
                        "<img src=\"../img/down.gif\" alt=\"$langAltMoveDown\" border=\"0\" />\n",
                        "</a>\n",
                         "</td>\n";
            }
            else
            {
                echo "<td>&nbsp;</td>\n";
            }
            
            // statistics links
            echo "<td>\n
              <a href=\"../tracking/learnPath_details.php?path_id=".$list['learnPath_id']."\">
              <img src=\"../img/statistiques.gif\" border=\"0\" alt=\"$langTracking\">
              </a>
              </td>\n";

       }
       elseif($lpUid)
       {
            // % progress
            $prog = get_learnPath_progress($list['learnPath_id'], $lpUid);

            if ($prog >= 0)
            {
                $globalprog += $prog;
            }
            echo "<td align=\"right\">";
                   draw_progress($prog, 1);
            echo "</td>";
            echo "<td align=\"left\">
                    <small> ".$prog."% </small>
                  </td>";
       }
       echo "</tr>";
       $iterator++;

   } // end while

   echo "</tbody>\n<tfoot>";

    if( $iterator == 1 )
   {
         echo "<tr><td align=\"center\" colspan=\"8\">".$langNoLearningPath."</td></tr>";
   }
   elseif (!$is_courseAdmin && $iterator != 1 && $lpUid)
   {
         // add a blank line between module progression and global progression
         echo "<tr><td colspan=\"3\">&nbsp;</td></tr>";
         $total = round($globalprog/($iterator-1));
         echo "<tr>
                  <td align =\"right\">
                     ".$langPathsInCourseProg." :
                  </td>
                  <td align=\"right\" >";
                     draw_progress($total, 1);
         echo     "</td>
                   <td align=\"left\">
                     <small> ".$total."% </small>
                  </td>
               </tr>
               ";
   }
   echo "</tfoot>\n";
   echo "</table>\n";
   echo "</form>\n"; // end of the change order form

   // footer

   include($includePath."/claro_init_footer.inc.php");

?>
