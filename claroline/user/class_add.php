<?php // $Id$


$tlabelReq = "CLUSR___";
require '../inc/claro_init_global.inc.php';

if (!($_cid)) 	claro_disp_select_course();

include($includePath."/lib/admin.lib.inc.php");
include($includePath."/lib/class.lib.php");

// javascript confirm pop up declaration for header

$htmlHeadXtra[] =
            "<script>
            function confirmation (name)
            {
                if (confirm(\"" . clean_str_for_javascript($langConfirmEnrollClassToCourse) . "\"))
                    {return true;}
                else
                    {return false;}
            }
            </script>";

/*
 * DB tables definition
 */

$tbl_cdb_names = claro_sql_get_course_tbl();
$tbl_mdb_names = claro_sql_get_main_tbl();
$tbl_rel_course_user = $tbl_mdb_names['rel_course_user'  ];
$tbl_users           = $tbl_mdb_names['user'             ];
$tbl_courses_users   = $tbl_rel_course_user;
$tbl_rel_users_groups= $tbl_cdb_names['group_rel_team_user'    ];
$tbl_groups          = $tbl_cdb_names['group_team'             ];
$tbl_class           = $tbl_mdb_names['user_category'];
$tbl_class_user      = $tbl_mdb_names['user_rel_profile_category'];

/*---------------------------------------------------------------------*/
/*----------------------EXECUTE COMMAND SECTION------------------------*/
/*---------------------------------------------------------------------*/

if (isset($_REQUEST['cmd']))
     $cmd = $_REQUEST['cmd'];
else $cmd = null;
  
switch ($cmd)
{  
  //Open a class in the tree
  case "exOpen" : 
      $_SESSION['class_add_visible_class'][$_REQUEST['class']]="open";      
      break;
      
  //Close a class in the tree
  case "exClose" : 
      $_SESSION['class_add_visible_class'][$_REQUEST['class']]="close";      
      break;
      
  // subscribe a class to the course    
  case "subscribe" :           
      $dialogBox = "<b>Class ".$_REQUEST['classname']." $langHasBeenEnrolled </b><br>";
      $sql = "SELECT * FROM `".$tbl_class_user."` AS CU,`".$tbl_users."` AS U WHERE CU.`user_id`=U.`user_id` AND CU.`class_id`='".$_REQUEST['class']."'  ORDER BY U.`nom`";
      $user_list = claro_sql_query_fetch_all($sql);
      
      foreach ($user_list as $user)
      {        
          if (add_user_to_course($user['user_id'], $_cid))
	  {     
	      $dialogBox .= $user['prenom']." ".$user['nom']." $langIsNowRegistered<br>";
	  }
	  else
	  {
	      $dialogBox .= $user['prenom']." ".$user['nom']." $langIsAlreadyRegistered<br>";
	  }
      }
      
      break;   
}

/*---------------------------------------------------------------------*/
/*----------------------FIND information SECTION-----------------------*/
/*---------------------------------------------------------------------*/

$sql = "SELECT * FROM `".$tbl_class."` ORDER BY `name`";
$class_list = claro_sql_query_fetch_all($sql);


/*---------------------------------------------------------------------*/
/*----------------------DISPLAY SECTION--------------------------------*/
/*---------------------------------------------------------------------*/

// find which display is to be used

$display = "tree";

// set bredcrump


$nameTools = $langAddClass;
$interbredcrump[]    = array ("url"=>"user.php", "name"=> $langUsers);

// display top banner

include($includePath."/claro_init_header.inc.php");

// Display tool title

claro_disp_tool_title($langAddAClassToCourse);

// Display Forms or dialog box (if needed)

if(isset($dialogBox)&& $dialogBox!="")
{
    claro_disp_message_box($dialogBox);
}

switch ($display)
{

    case "tree";

    
    // display tool links

    echo "<a class=\"claroCmd\" href=\"user.php\">".$langBackToList."</a><br><br>";

    // display cols headers

        echo "<table class=\"claroTable\" width=\"100%\" border=\"0\" cellspacing=\"2\">\n"
            ." <thead>\n"
            ."  <tr class=\"headerX\">\n"
            ."    <th>\n"
            ."      $langClass\n"
            ."    </th>\n"
            ."    <th>\n"
            ."      $langUsers\n"
            ."    </th>\n"
            ."    <th>\n"
            ."      $langSubscribeToCourse\n"
            ."    </th>\n"
            ."  </tr>\n"
            ."</thead>\n";

    // display Class list (or tree)
        echo "<tbody>\n";
        display_tree_class_in_user($class_list);
        echo "</tbody>\n</table>\n";
        break;

    case "class_added" :
        echo $langDispClassAdded;
        break;
}

// footer banner

include($includePath."/claro_init_footer.inc.php");
?>
