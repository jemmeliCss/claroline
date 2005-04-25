<?php // $Id$
/*
  +----------------------------------------------------------------------+
  | CLAROLINE version 1.6.*
  +----------------------------------------------------------------------+
  | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
  +----------------------------------------------------------------------+
  | This source file is subject to the GENERAL PUBLIC LICENSE,           |
  | available through the world-wide-web at                              |
  | http://www.gnu.org/copyleft/gpl.html                                 |
  +----------------------------------------------------------------------+
  |  Authors: Piraux Sébastien <pir@cerdecam.be>                         |
  |          Lederer Guillaume <led@cerdecam.be>                         |
  +----------------------------------------------------------------------+

  DESCRIPTION:
  ****

*/

/*======================================
       CLAROLINE MAIN
  ======================================*/
$tlabelReq = 'CLLNP___';
require '../inc/claro_init_global.inc.php';

  // if there is an auth information missing redirect to the first page of lp tool 
  // this page will do the necessary to auth the user, 
  // when leaving a course all the LP sessions infos are cleared so we use this trick to avoid other errors
  if ( ! $_cid) header("Location:./learningPathList.php");
  if ( ! $is_courseAllowed) header("Location:./learningPathList.php");

  $htmlHeadXtra[] =
            "<script>
            function confirmation (name)
            {
                if (confirm(\" $langAreYouSureDeleteModule \"+ name + \" ?\"))
                    {return true;}
                else
                    {return false;}
            }
            </script>";

  $interbredcrump[]= array ("url"=>"../learnPath/learningPathList.php", "name"=> $langLearningPathList);
  $interbredcrump[]= array ("url"=>"../learnPath/learningPathAdmin.php", "name"=> $langLearningPathAdmin);
  $nameTools = $langInsertMyDocToolName;

  //header
  @include($includePath."/claro_init_header.inc.php");



  // tables names

  $TABLELEARNPATH         = $_course['dbNameGlu']."lp_learnPath";
  $TABLEMODULE            = $_course['dbNameGlu']."lp_module";
  $TABLELEARNPATHMODULE   = $_course['dbNameGlu']."lp_rel_learnPath_module";
  $TABLEASSET             = $_course['dbNameGlu']."lp_asset";
  $TABLEUSERMODULEPROGRESS= $_course['dbNameGlu']."lp_user_module_progress";

  // document browser vars
  $TABLEDOCUMENT     = $_course['dbNameGlu']."document";


  $courseDir   = $_course['path']."/document";
  $moduleDir   = $_course['path']."/modules";
  $baseWorkDir = $coursesRepositorySys.$courseDir;
  $moduleWorkDir = $coursesRepositorySys.$moduleDir;

  //lib of this tool
  @include($includePath."/lib/learnPath.lib.inc.php");

  include($includePath."/lib/fileDisplay.lib.php");
  include($includePath."/lib/fileManage.lib.php");

  // $_SESSION
  if ( !isset($_SESSION['path_id']) )
  {
        die ("<center> Not allowed ! (path_id not set :@ )</center>");
  }


/*======================================
       CLAROLINE MAIN
  ======================================*/


      // main page

   $is_AllowedToEdit = $is_courseAdmin;

   if (! $is_AllowedToEdit ) header("Location:./learningPathList.php");




   // FUNCTION NEEDED TO BUILD THE QUERY TO SELECT THE MODULES THAT MUST BE AVAILABLE

   // 1)  We select first the modules that must not be displayed because
   // as they are already in this learning path

   function buildRequestModules()
   {

     global $TABLELEARNPATHMODULE;
     global $TABLEMODULE;

     $firstSql = "SELECT `module_id`
                    FROM `".$TABLELEARNPATHMODULE."` AS LPM
                   WHERE LPM.`learnPath_id` = ".$_SESSION['path_id'];

     $firstResult = claro_sql_query($firstSql);

     // 2) We build the request to get the modules we need

     $sql = "SELECT M.*
               FROM `".$TABLEMODULE."` AS M
              WHERE 1 = 1";

     while ($list=mysql_fetch_array($firstResult))
     {
            $sql .=" AND M.`module_id` != ".$list['module_id'];
     }

     /** To find which module must displayed we can also proceed  with only one query.
       * But this implies to use some features of MySQL not available in the version 3.23, so we use
       * two differents queries to get the right list.
       * Here is how to proceed with only one

     $query = "SELECT *
                FROM `".$TABLEMODULE."` AS M
                WHERE NOT EXISTS(SELECT * FROM `".$TABLELEARNPATHMODULE."` AS TLPM
                WHERE TLPM.`module_id` = M.`module_id`)"; */

     return $sql;
   }//end function

   //####################################################################################\\
   //################################ DOCUMENTS LIST ####################################\\
   //####################################################################################\\

   // display title

  claro_disp_tool_title($nameTools);

       // FORM SENT
       /*
        *
        * SET THE DOCUMENT AS A MODULE OF THIS LEARNING PATH
        *
        */
        // evaluate how many form could be sent

        $iterator = 0;
        while ($iterator <= $_POST['maxDocForm'])
        {
           $iterator++;

           if( $submitInsertedDocument && isset($_POST['insertDocument_'.$iterator]) )
           {
                $insertDocument = str_replace('..', '',$_POST['insertDocument_'.$iterator]);
                if (get_magic_quotes_gpc())
                  $sourceDoc =   stripslashes($baseWorkDir.$insertDocument);
                else
                  $sourceDoc =   $baseWorkDir.$insertDocument;

                if ( check_name_exist($sourceDoc) ) // source file exists ?
                {
                        // check if a module of this course already used the same document
                        $sql = "SELECT *
                                  FROM `".$TABLEMODULE."` AS M, `".$TABLEASSET."` AS A
                                 WHERE A.`module_id` = M.`module_id`
                                   AND A.`path` LIKE \"".$insertDocument."\"
                                   AND M.`contentType` = \"".CTDOCUMENT_."\"";
                        $query = claro_sql_query($sql);
                        $num = mysql_numrows($query);
                        $basename = substr($insertDocument, strrpos($insertDocument, '/') + 1);
                        if($num == 0)
                        {
                             // create new module
                             $sql = "INSERT
                                       INTO `".$TABLEMODULE."`
                                            (`name` , `comment`, `contentType`)
                                     VALUES ('".claro_addslashes($basename)."' , '".addslashes($langDefaultModuleComment)."', '".CTDOCUMENT_."' )";
                             //echo "<br /><1> ".$sql;
                             $query = claro_sql_query($sql);

                             $insertedModule_id = mysql_insert_id();

                             // create new asset
                             $sql = "INSERT
                                       INTO `".$TABLEASSET."`
                                            (`path` , `module_id` , `comment`)
                                     VALUES ('".claro_addslashes($insertDocument)."', $insertedModule_id , '')";
                             //echo "<br /><2> ".$sql;
                             $query = claro_sql_query($sql);

                             $insertedAsset_id = mysql_insert_id();

                             $sql = "UPDATE `".$TABLEMODULE."`
                                        SET `startAsset_id` = $insertedAsset_id
                                      WHERE `module_id` = $insertedModule_id";
                             //echo "<br /><3> ".$sql;
                             $query = claro_sql_query($sql);

                             // determine the default order of this Learning path
                             $result = claro_sql_query("SELECT MAX(`rank`)
                                                      FROM `".$TABLELEARNPATHMODULE."`");

                             list($orderMax) = mysql_fetch_row($result);
                             $order = $orderMax + 1;
                             // finally : insert in learning path
                             $sql = "INSERT
                                       INTO `".$TABLELEARNPATHMODULE."`
                                            (`learnPath_id`, `module_id`, `specificComment`, `rank`, `lock`)
                                     VALUES ('".$_SESSION['path_id']."', '".$insertedModule_id."','".addslashes($langDefaultModuleAddedComment)."', ".$order.", 'OPEN')";
                             //echo "<br /><4> ".$sql;
                             $query = claro_sql_query($sql);

                              if (get_magic_quotes_gpc())
                                $addedDoc =   stripslashes($basename);
                              else
                                $addedDoc =  $basename;

                             $dialogBox .= $addedDoc ." ".$langDocInsertedAsModule."<br>";
                        }
                        else
                        {
                             // check if this is this LP that used this document as a module
                             $sql = "SELECT *
                                       FROM `".$TABLELEARNPATHMODULE."` AS LPM,
                                            `".$TABLEMODULE."` AS M,
                                            `".$TABLEASSET."` AS A
                                      WHERE M.`module_id` =  LPM.`module_id`
                                        AND M.`startAsset_id` = A.`asset_id`
                                        AND A.`path` = '".claro_addslashes($insertDocument)."'
                                        AND LPM.`learnPath_id` = ".$_SESSION['path_id'];
                             $query2 = claro_sql_query($sql);
                             $num = mysql_numrows($query2);
                             if ($num == 0)     // used in another LP but not in this one, so reuse the module id reference instead of creating a new one
                             {
                                 $thisDocumentModule = mysql_fetch_array($query);
                                 // determine the default order of this Learning path
                                 $result = claro_sql_query("SELECT MAX(`rank`)
                                                          FROM `".$TABLELEARNPATHMODULE."`");

                                 list($orderMax) = mysql_fetch_row($result);
                                 $order = $orderMax + 1;
                                 // finally : insert in learning path
                                 $sql = "INSERT
                                           INTO `".$TABLELEARNPATHMODULE."`
                                                (`learnPath_id`, `module_id`, `specificComment`, `rank`,`lock`)
                                         VALUES ('".$_SESSION['path_id']."', '".$thisDocumentModule['module_id']."','".addslashes($langDefaultModuleAddedComment)."', ".$order.",'OPEN')";
                                 //echo "<br /><4> ".$sql;
                                 $query = claro_sql_query($sql);
                                  if (get_magic_quotes_gpc())
                                    $addedDoc =   stripslashes($basename);
                                  else
                                    $addedDoc =  $basename;

                                 $dialogBox .= $addedDoc ." ".$langDocInsertedAsModule."<br>";
                             }
                             else
                             {
                                 $dialogBox .= stripslashes($basename)." : ".$langDocumentAlreadyUsed."<br>";
                             }
                        }
                }


           }
       }

      /*======================================
             DEFINE CURRENT DIRECTORY
        ======================================*/

      if (isset($openDir) ) // $newDirPath is from createDir command (step 2) and $uploadPath from upload command
      {
              $curDirPath = $openDir;
              /*
               * NOTE: Actually, only one of these variables is set.
               * By concatenating them, we eschew a long list of "if" statements
               */
      }
      else
      {
              $curDirPath="";
      }

      if ($curDirPath == "/" || $curDirPath == "\\" || strstr($curDirPath, ".."))
      {
              $curDirPath =""; // manage the root directory problem

              /*
               * The strstr($curDirPath, "..") prevent malicious users to go to the root directory
               */
      }

      $curDirName = basename($curDirPath);
      $parentDir  = dirname($curDirPath);

      if ($parentDir == "/" || $parentDir == "\\")
      {
              $parentDir =""; // manage the root directory problem
      }

      /*======================================
              READ CURRENT DIRECTORY CONTENT
        ======================================*/

      /*--------------------------------------
        SEARCHING FILES & DIRECTORIES INFOS
                    ON THE DB
        --------------------------------------*/

      /* Search infos in the DB about the current directory the user is in */

      $result = claro_sql_query ("SELECT *
                                FROM `".$TABLEDOCUMENT."`
                               WHERE `path` LIKE \"".$curDirPath."/%\"
                                 AND `path` NOT LIKE \"".$curDirPath."/%/%\"");

      while($row = mysql_fetch_array($result, MYSQL_ASSOC))
      {
              $attribute['path'      ][] = $row['path'      ];
              $attribute['visibility'][] = $row['visibility'];
              $attribute['comment'   ][] = $row['comment'   ];
      }


      /*--------------------------------------
        LOAD FILES AND DIRECTORIES INTO ARRAYS
        --------------------------------------*/
      @chdir (realpath($baseWorkDir.$curDirPath))
      or die("<center>
              <b>Wrong directory !</b>
              <br /> Please contact your platform administrator.</center>");
      $handle = opendir(".");

      define('A_DIRECTORY', 1);
      define('A_FILE',      2);


      while ($file = readdir($handle))
      {
              if ($file == "." || $file == "..")
              {
                      continue; // Skip current and parent directories
              }

              $fileList['name'][] = $file;

              if(is_dir($file))
              {
                      $fileList['type'][] = A_DIRECTORY;
                      $fileList['size'][] = false;
                      $fileList['date'][] = false;
              }
              elseif(is_file($file))
              {
                      $fileList['type'][] = A_FILE;
                      $fileList['size'][] = filesize($file);
                      $fileList['date'][] = filectime($file);
              }


              /*
               * Make the correspondance between
               * info given by the file system
               * and info given by the DB
               */

              $keyDir = sizeof($dirNameList)-1;

              if ($attribute)
              {
                      $keyAttribute = array_search($curDirPath."/".$file, $attribute['path']);
              }

              if ($keyAttribute !== false)
              {
                              $fileList['comment'   ][] = $attribute['comment'   ][$keyAttribute];
                              $fileList['visibility'][] = $attribute['visibility'][$keyAttribute];
              }
              else
              {
                              $fileList['comment'   ][] = false;
                              $fileList['visibility'][] = false;
              }
      }                                // end while ($file = readdir($handle))

      /*
       * Sort alphabetically the File list
       */

      if ($fileList)
      {
              array_multisort($fileList['type'], $fileList['name'],
                              $fileList['size'], $fileList['date'],
                              $fileList['comment'],$fileList['visibility']);
      }




      /*----------------------------------------
              CHECK BASE INTEGRITY
      --------------------------------------*/


      if ($attribute)
      {
              /*
               * check if the number of DB records is greater
               * than the numbers of files attributes previously given
               */

              if (sizeof($attribute['path']) > (sizeof($fileList['comment']) + sizeof($fileList['visibility'])))
              {
                      /* SEARCH DB RECORDS WICH HAVE NOT CORRESPONDANCE ON THE DIRECTORY */
                      foreach( $attribute['path'] as $chekinFile)
                      {
                              if ($dirNameList && in_array(basename($chekinFile), $dirNameList))
                                      continue;
                              elseif ($fileNameList && in_array(basename($chekinFile), $fileNameList))
                                      continue;
                              else
                                      $recToDel[]= $chekinFile; // add chekinFile to the list of records to delete
                      }

                      /* BUILD THE QUERY TO DELETE DEPRECATED DB RECORDS */
                      $nbrRecToDel = sizeof ($recToDel);

                      for ($i=0; $i < $nbrRecToDel ;$i++)
                      {
                              $queryClause .= "path LIKE \"".$recToDel[$i]."%\"";
                              if ($i < $nbrRecToDel-1) {$queryClause .=" OR ";}
                      }

                      claro_sql_query("DELETE
                                     FROM `".$dbTable."`
                                    WHERE ".$queryClause);
                      claro_sql_query("DELETE
                                     FROM `".$dbTable."`
                                    WHERE `comment` LIKE ''
                                      AND `visibility` LIKE 'v'");
                      /* The second query clean the DB 'in case of' empty records (no comment an visibility=v)
                         These kind of records should'nt be there, but we never know... */
              }
      }                                // end if ($attribute)



      closedir($handle);
      unset($attribute);


   // display list of available documents
   display_my_documents($dialogBox) ;

   //####################################################################################\\
   //################################## MODULES LIST ####################################\\
   //####################################################################################\\


   claro_disp_tool_title($langPathContentTitle);
  echo '<a href="learningPathAdmin.php">&lt;&lt;&nbsp;'.$langBackToLPAdmin.'</a>';
  // display list of modules used by this learning path
   display_path_content($param_array, $table);

   // footer

   @include($includePath."/claro_init_footer.inc.php");
?>
