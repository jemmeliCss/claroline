<?php // $Id$
/**
 * @version  CLAROLINE version 1.6
 *
 * @copyright (c) 2001, 2005 Universite catholique de Louvain (UCL)
 *
 * @license GENERAL PUBLIC LICENSE
 *
 * @author Piraux S�bastien <pir@cerdecam.be>
 * @author Lederer Guillaume <led@cerdecam.be>
 *
 * @package CLLNP
 *
 * This is the page where the list of modules of the course present 
 * on the platform can be browsed
 * user allowed to edit the course can 
 * delete the modules form this page
 */

/*======================================
       CLAROLINE MAIN
  ======================================*/

$tlabelReq = 'CLLNP___';
require '../inc/claro_init_global.inc.php';
$is_AllowedToEdit = $is_courseAdmin;
if (! $is_AllowedToEdit or ! $is_courseAllowed ) claro_disp_auth_form();

$htmlHeadXtra[] =
        "<script>
        function confirmation (name)
        {
            if (confirm(\"".clean_str_for_javascript($langAreYouSureDeleteModule)."\"+ name))
                {return true;}
            else
                {return false;}
        }
        </script>";

$interbredcrump[]= array ("url"=>"../learnPath/learningPathList.php", "name"=> $langLearningPathList);
$nameTools = $langModulesPoolToolName;


// tables names
/*
 * DB tables definition
 */

$tbl_cdb_names = claro_sql_get_course_tbl();
$tbl_lp_learnPath            = $tbl_cdb_names['lp_learnPath'           ];
$tbl_lp_rel_learnPath_module = $tbl_cdb_names['lp_rel_learnPath_module'];
$tbl_lp_user_module_progress = $tbl_cdb_names['lp_user_module_progress'];
$tbl_lp_module               = $tbl_cdb_names['lp_module'              ];
$tbl_lp_asset                = $tbl_cdb_names['lp_asset'               ];

//this bloc would be removed later by direct use of var name
$TABLELEARNPATH         = $tbl_lp_learnPath;
$TABLEMODULE            = $tbl_lp_module;
$TABLELEARNPATHMODULE   = $tbl_lp_rel_learnPath_module;
$TABLEASSET             = $tbl_lp_asset;
$TABLEUSERMODULEPROGRESS= $tbl_lp_user_module_progress;


//lib of this tool
include($includePath."/lib/learnPath.lib.inc.php");

/*======================================
       CLAROLINE MAIN
  ======================================*/

//header
include($includePath."/claro_init_header.inc.php");

// display title
echo claro_disp_tool_title($nameTools);

// display use explication text
echo $langUseOfPool."<br /><br />";

// HANDLE COMMANDS:
$cmd = ( isset($_REQUEST['cmd']) )? $_REQUEST['cmd'] : '';

switch( $cmd )
{
    // MODULE DELETE
    case "eraseModule" :
        // used to physically delete the module  from server
        include($includePath."/lib/fileManage.lib.php");

        $moduleDir   = $_course['path']."/modules";
        $moduleWorkDir = $coursesRepositorySys.$moduleDir;

        // delete all assets of this module
        $sql = "DELETE
                FROM `".$TABLEASSET."`
                WHERE `module_id` = ".$_REQUEST['cmdid'];
        claro_sql_query($sql);

        // delete from all learning path of this course but keep there id before
        $sql = "SELECT *
                FROM `".$TABLELEARNPATHMODULE."`
                WHERE `module_id` = ".$_REQUEST['cmdid'];
        $result = claro_sql_query($sql);

        $sql = "DELETE
                FROM `".$TABLELEARNPATHMODULE."`
                WHERE `module_id` = ".$_REQUEST['cmdid'];
        claro_sql_query($sql);

        // delete the module in modules table
        $sql = "DELETE
                FROM `".$TABLEMODULE."`
                WHERE `module_id` = ".$_REQUEST['cmdid'];
        claro_sql_query($sql);

        //delete all user progression concerning this module
        $sql = "DELETE
                FROM `".$TABLEUSERMODULEPROGRESS."`
                WHERE 1=0 ";

        while ($list = mysql_fetch_array($result))
        {
            $sql.=" OR `learnPath_module_id`=".$list['learnPath_module_id'];
        }
        claro_sql_query($sql);

        // This query does the same as the 3 previous queries but does not work with MySQL versions before 4.0.0
        // delete all asset, all learning path module, and from module table
        /*
        claro_sql_query("DELETE
                       FROM `".$TABLEASSET."`, `".$TABLELEARNPATHMODULE."`, `".$TABLEMODULE."`
                      WHERE `module_id` = ".$_REQUEST['cmdid'] );
        */

        // delete directory and it content
        claro_delete_file($moduleWorkDir."/module_".$_REQUEST['cmdid']);
        break;

    // COMMAND RENAME :
    //display the form to enter new name
    case "rqRename" :
        //get current name from DB
        $query= "SELECT `name`
                 FROM `".$TABLEMODULE."`
                 WHERE `module_id` = '".$_REQUEST['module_id']."'";
        $result = claro_sql_query($query);
        $list = mysql_fetch_array($result);
        echo "
            <form method=\"post\" name=\"rename\" action=\"".$_SERVER['PHP_SELF']."\">
            <label for=\"newName\">".$langInsertNewModuleName."</label> :
            <input type=\"text\" name=\"newName\" id=\"newName\" value=\"".htmlspecialchars($list['name'])."\"></input>
            <input type=\"submit\" value=\"".$langOk."\" name=\"submit\">
            <input type=\"hidden\" name=\"cmd\" value=\"exRename\">
            <input type=\"hidden\" name=\"module_id\" value=\"".$_REQUEST['module_id']."\">
            </form>
            ";
        break;

     //try to change name for selected module
    case "exRename" :
        //check if newname is empty
        if( isset($_REQUEST["newName"]) && $_REQUEST["newName"] != "" )
        {
            //check if newname is not already used in another module of the same course
            $sql="SELECT `name`
                  FROM `".$TABLEMODULE."`
                  WHERE `name` = '".claro_addslashes($_POST['newName'])."'
                    AND `module_id` != '".$_REQUEST['module_id']."'";

            $query = claro_sql_query($sql);
            $num = mysql_numrows($query);
            if($num == 0 ) // "name" doesn't already exist
            {
                // if no error occurred, update module's name in the database
                $query="UPDATE `".$TABLEMODULE."`
                        SET `name`= '".claro_addslashes($_POST['newName'])."'
                        WHERE `module_id` = '".$_REQUEST['module_id']."'";

                $result = claro_sql_query($query);
            }
            else
            {
                echo claro_disp_message_box($langErrorNameAlreadyExists);
                echo "<br />";
            }
        }
        else
        {
            echo claro_disp_message_box($langErrorEmptyName);
            echo "<br />";
        }
        break;

    //display the form to modify the comment
    case "rqComment" :
        if( isset($_REQUEST['module_id']) )
        {
            //get current comment from DB
            $query="SELECT `comment`
                    FROM `".$TABLEMODULE."`
                    WHERE `module_id` = '".$_REQUEST['module_id']."'";
            $result = claro_sql_query($query);
            $comment = mysql_fetch_array($result);
            
            if( isset($comment['comment']) )
            {
                echo "<form method=\"get\" action=\"".$_SERVER['PHP_SELF']."\">\n"
                    .claro_disp_html_area('comment', $comment['comment'], 15, 55)
                    ."<br />\n"
                    ."<input type=\"hidden\" name=\"cmd\" value=\"exComment\">\n"
                    ."<input type=\"hidden\" name=\"module_id\" value=\"".$_REQUEST['module_id']."\">\n"
                    ."<input type=\"submit\" value=\"".$langOk."\">\n"
                    ."<br /><br />\n"
                    ."</form>\n";
            }
        } // else no module_id
        break;

    //make update to change the comment in the database for this module
    case "exComment":
        if( isset($_REQUEST['module_id']) && isset($_REQUEST['comment']) )
        {
            $sql = "UPDATE `".$TABLEMODULE."`
                    SET `comment` = \"".claro_addslashes($_REQUEST['comment'])."\"
                    WHERE `module_id` = '".$_REQUEST['module_id']."'";
            claro_sql_query($sql);
        }
        break;
}


echo "<table class=\"claroTable\" width=\"100%\" border=\"0\" cellspacing=\"2\">
    <thead>
        <tr class=\"headerX\" align=\"center\" valign=\"top\">
          <th>
            ".$langModule."
          </th>
          <th>
            ".$langDelete."
          </th>
          <th>
            ".$langRename."
          </th>
          <th>
            ".$langComment."
          </th>";
echo      "</tr>\n",
      "</thead>\n",
          "<tbody>\n";

$sql = "SELECT M.*, count(M.`module_id`) AS timesUsed
        FROM `".$TABLEMODULE."` AS M
          LEFT JOIN `".$TABLELEARNPATHMODULE."` AS LPM ON LPM.`module_id` = M.`module_id`
        WHERE M.`contentType` != \"".CTSCORM_."\"
          AND M.`contentType` != \"".CTLABEL_."\"
        GROUP BY M.`module_id`
        ORDER BY M.`name` ASC, M.`contentType`ASC, M.`accessibility` ASC";

$result = claro_sql_query($sql);
$atleastOne = false;

// Display modules of the pool of this course

while ($list = mysql_fetch_array($result))
{
    //DELETE , RENAME, COMMENT

    $contentType_img = selectImage($list['contentType']);
    $contentType_alt = selectAlt($list['contentType']);
    echo "
         <tr>
            <td align=\"left\">
            <img src=\"".$imgRepositoryWeb.$contentType_img."\" alt=\"".$contentType_alt."\" />".$list['name']."
            </td>
            <td align='center'>
             <a href=\"",$_SERVER['PHP_SELF'],"?cmd=eraseModule&amp;cmdid=".$list['module_id']."\"
                onClick=\"return confirmation('".clean_str_for_javascript($list['name'] . $langUsedInLearningPaths . $list['timesUsed'])."');\">
                <img src=\"".$imgRepositoryWeb."delete.gif\" border=\"0\" alt=\"".$langDelete."\" />
                </a>
            </td>
            <td align=\"center\">
               <a href=\"",$_SERVER['PHP_SELF'],"?cmd=rqRename&amp;module_id=".$list['module_id']."\"><img src=\"".$imgRepositoryWeb."edit.gif\" border=0 alt=\"$langRename\" /></a>
            </td>
            <td align=\"center\">
               <a href=\"",$_SERVER['PHP_SELF'],"?cmd=rqComment&amp;module_id=".$list['module_id']."\"><img src=\"".$imgRepositoryWeb."comment.gif\" border=0 alt=\"$langComment\" /></a>
            </td>";
    echo "</tr>";

    if ( isset($list['comment']) )
    {
        echo "
              <tr>
                 <td colspan=\"5\">
                        <small>".$list['comment']."</small>
                 </td>
              </tr>";
    }

    $atleastOne = true;

} //end while another module to display

if ($atleastOne == false) {echo "<tr><td align=\"center\" colspan=\"5\">".$langNoModule."</td></tr>";}

// Display button to add selected modules

echo "</tbody>\n</table>";

// footer

include($includePath."/claro_init_footer.inc.php");
?>
