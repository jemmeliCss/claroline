<?php // $Id$
/**
 * CLAROLINE
 *
 * List courses aivailable on the platform and prupose admin link to it
 *
 * @version 1.7 $Revision$
 *
 * @copyright 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @see http://www.claroline.net/wiki/COURSE/
 *
 * @author Claro Team <cvs@claroline.net>
 *
 * @package COURSE
 * 
 */


$cidReset = TRUE;$gidReset = TRUE;$tidReset = TRUE;
$coursePerPage= 20;

require '../inc/claro_init_global.inc.php';

//SECURITY CHECK
$is_allowedToAdmin     = $is_platformAdmin;
if (!$is_allowedToAdmin) claro_disp_auth_form();
// initialisation of global variables and used libraries
include($includePath . '/lib/admin.lib.inc.php');
include($includePath . '/lib/events.lib.inc.php');
include($includePath . '/lib/pager.lib.php');
$tbl_mdb_names       = claro_sql_get_main_tbl();
$tbl_user            = $tbl_mdb_names['user'  ];
$tbl_course          = $tbl_mdb_names['course'];
$tbl_admin           = $tbl_mdb_names['admin' ];
$tbl_rel_course_user = $tbl_mdb_names['rel_course_user' ];

// javascript confirm pop up declaration

$htmlHeadXtra[] =
'<script type="text/javascript">
function confirmation (name)
{
    if (confirm("' . clean_str_for_javascript($langAreYouSureToDelete) . '" + name + \'"? \'))
        {return true;}
    else
        {return false;}
}
</script>';

// Deal with interbredcrumps

$interbredcrump[]= array ('url'=>$rootAdminWeb, 'name'=> $langAdministration);
$nameTools = $langCourseList;

//SECURITY CHECK

if (!$is_platformAdmin) claro_disp_auth_form();

$is_allowedToAdmin = $is_platformAdmin;

//------------------------
//  USED SESSION VARIABLES
//------------------------
// deal with session variables for search criteria, it depends where we come from :
// 1 ) we must be able to get back to the list that concerned the criteria we previously used (with out re entering them)
// 2 ) we must be able to arrive with new critera for a new search.

// clean session if needed from  previous search

if (isset($_REQUEST['newsearch']) && $_REQUEST['newsearch']=='yes')
{
    unset($_SESSION['admin_course_code']);
    unset($_SESSION['admin_course_letter']);
    unset($_SESSION['admin_course_intitule']);
    unset($_SESSION['admin_course_category']);
    unset($_SESSION['admin_course_language']);
    unset($_SESSION['admin_course_access']);
    unset($_SESSION['admin_course_subscription']);
    unset($_SESSION['admin_course_dir']);
    unset($_SESSION['admin_course_order_crit']);
}

if (isset($_REQUEST['code']))    {$_SESSION['admin_course_code']         = trim($_REQUEST['code']);}
if (isset($_REQUEST['letter']))  {$_SESSION['admin_course_letter']       = trim($_REQUEST['letter']);}
if (isset($_REQUEST['search']))  {$_SESSION['admin_course_search']       = trim($_REQUEST['search']);}
if (isset($_REQUEST['intitule'])){$_SESSION['admin_course_intitule']     = trim($_REQUEST['intitule']);}
if (isset($_REQUEST['category'])){$_SESSION['admin_course_category']     = trim($_REQUEST['category']);}
if (isset($_REQUEST['language'])){$_SESSION['admin_course_language']     = trim($_REQUEST['language']);}
if (isset($_REQUEST['access']))  {$_SESSION['admin_course_access']       = trim($_REQUEST['access']);}
if (isset($_REQUEST['subscription'])) 
                                 {$_SESSION['admin_course_subscription'] = trim($_REQUEST['subscription']);}
if (isset($_REQUEST['order_crit']))   
                                 {$_SESSION['admin_course_order_crit']   = trim($_REQUEST['order_crit']) ;}
if (isset($_REQUEST['dir']))     {$_SESSION['admin_course_dir']          = ($_REQUEST['dir']=='DESC'?'DESC':'ASC');}
if (isset($_REQUEST['search']))  {$search = $_REQUEST['search'];} else $search='';   
   
// clean session if we come from a course

session_unregister('_cid');
unset($_cid);

//set the reorder parameters for colomuns titles

if (!isset($order['code']))    $order['code']   = '';
if (!isset($order['label']))   $order['label']  = '';
if (!isset($order['cat']))     $order['cat']    = '';

// Set parameters to add to URL to know where we come from and what options will be given to the user

$addToURL = '';
if (!isset($_REQUEST['offsetC']))
{ 
   $offsetC = '0'; 
}
else
{
   $offsetC = $_REQUEST['offsetC'];
}

if (!isset($cfrom) || $cfrom!='clist') //offset not kept when come from another list
{
   $addToURL .= '&amp;offsetC=' . $offsetC;
}


//----------------------------------
// EXECUTE COMMAND
//----------------------------------

if (isset($_REQUEST['cmd']))
     $cmd = $_REQUEST['cmd'];
else $cmd = null;

switch ($cmd)
{
  case 'delete' :
        $delCode = $_REQUEST['delCode'];
        $the_course = claro_get_course_data($delCode);

        if ($the_course) 
        {
            delete_course($delCode);
            $dialogBox = $langCourseDelete;
            $noQUERY_STRING = true;
        }
        else 
        {
            switch(claro_failure::get_last_failure())
            {
                case 'course_not_found': 
                {
                    $dialogBox = $langCourseNotFound;
                }
                break;
                default  :
                {
                    $dialogBox = $langCourseNotFound;
                }
            }
            
        }
        break;
}

//----------------------------------
// Build query and find info in db
//----------------------------------

   // main query to know what must be displayed in the list

$sql = "SELECT  C.*,
                C.`fake_code` `officialCode`, 
                C.`code`      `sysCode`, 
                C.`directory` `repository`, 
                count(IF(`CU`.`statut`=5,1,null)) `qty_stu` , 
                #count only lines where statut of user is 5
                
                count(IF(`CU`.`statut`=1,1,null)) `qty_cm` 
                #count only lines where statut of user is 1

        FROM `".$tbl_course."` AS C 
        LEFT JOIN `".$tbl_rel_course_user."` AS CU
            ON `CU`.`code_cours` = `C`.`code` 
        WHERE 1=1 ";

//deal with LETTER classification call

if (isset($_SESSION['admin_course_letter']))
{
    $toAdd = " AND C.`intitule` LIKE '". addslashes($_SESSION['admin_course_letter']) ."%' ";
    $sql.=$toAdd;
}

//deal with KEY WORDS classification call
if (isset($_SESSION['admin_course_search']))
{
    $toAdd = " AND (      C.`intitule`  LIKE '%". addslashes(pr_star_replace($_SESSION['admin_course_search'])) ."%' 
                       OR C.`fake_code` LIKE '%". addslashes(pr_star_replace($_SESSION['admin_course_search'])) ."%' 
                       OR C.`faculte`   LIKE '%". addslashes(pr_star_replace($_SESSION['admin_course_search'])) ."%' 
               )";
    $sql.=$toAdd;

}

//deal with ADVANCED SEARCH parmaters call

if (isset($_SESSION['admin_course_intitule']))    // title of the course keyword is used
{
    $toAdd = " AND (C.`intitule` LIKE '%". addslashes(pr_star_replace($_SESSION['admin_course_intitule'])) ."%') ";
    $sql.=$toAdd;

}

if (isset($_SESSION['admin_course_code']))        // code keyword is used
{
    $toAdd = " AND (C.`fake_code` LIKE '%". addslashes(pr_star_replace($_SESSION['admin_course_code'])) ."%') ";
    $sql.=$toAdd;

}

if (isset($_SESSION['admin_course_category']))     // course category keyword is used
{
    $toAdd = " AND (C.`faculte` LIKE '%". addslashes(pr_star_replace($_SESSION['admin_course_category'])) ."%') ";
    $sql.=$toAdd;

}

if (isset($_SESSION['admin_course_language']))    // language filter is used
{
    $toAdd = " AND (C.`languageCourse` LIKE '%". addslashes($_SESSION['admin_course_language']) ."%') ";
    $sql.=$toAdd;

}

if (isset($_SESSION['admin_course_access']))     // type of access to course filter is used
{
    $toAdd = "";
    if ($_SESSION['admin_course_access'] == 'private')
    {
       $toAdd = " AND NOT (C.`visible`=2 OR C.`visible`=3) ";
    }
    elseif ($_SESSION['admin_course_access'] == 'public')
    {
       $toAdd = " AND (C.`visible`=2 OR C.`visible`=3) ";
    }

    $sql.=$toAdd;

}

if (isset($_SESSION['admin_course_subscription']))   // type of subscription allowed is used
{
    $toAdd = "";
    if ($_SESSION['admin_course_subscription']=="allowed")
    {
       $toAdd = " AND (C.`visible`=1 OR C.`visible`=2) ";
    }
    elseif ($_SESSION['admin_course_subscription']=="denied")
    {
       $toAdd = " AND NOT (C.`visible`=1 OR C.`visible`=2) ";
    }

    $sql.=$toAdd;

}
    $sql.=' GROUP BY C.code';

// deal with REORDER
if (isset($_SESSION['admin_course_order_crit']))
{
    switch ($_SESSION['admin_course_order_crit'])
    {
        case 'code'    : $fieldSort = 'fake_code'; break;
        case 'label'   : $fieldSort = 'intitule';  break;
        case 'cat'     : $fieldSort = 'faculte';   break;
        case 'titular' : $fieldSort = 'titulaire'; break;
        case 'email'   : $fieldSort = 'email';
    }
    $toAdd = " ORDER BY `".$fieldSort."` ".$_SESSION['admin_course_dir'];
    $order[$_SESSION['admin_course_order_crit']] = ($_SESSION['admin_course_dir']=='ASC'?'DESC':'ASC');
    $sql.=$toAdd;
}

//echo $sql."<br />";

//USE PAGER

$myPager = new claro_sql_pager($sql, $offsetC, $coursePerPage);
$myPager->set_pager_call_param_name('offsetC');
$resultList = $myPager->get_result_list();

//----------------------------------
// DISPLAY
//----------------------------------
include($includePath . '/claro_init_header.inc.php');

//display title

echo claro_disp_tool_title($nameTools);

// display forms and dialogBox, alphabetic choice,...

if (isset($dialogBox))
{
   echo claro_disp_message_box($dialogBox);
}
   //TOOL LINKS

   //Display search form


  //see passed search parameters :

$advanced_search_query_string = array();  
$isSearched ='';
      
if ( !empty($_REQUEST['search']) )              
{
    $isSearched .= trim($_REQUEST['search']) . ' ';
}

if ( !empty($_REQUEST['code']) )                   
{
    $isSearched .= $langCode . ' = ' . $_REQUEST['code'] . ' ';
    $advanced_search_query_string[] = 'code=' . urlencode($_REQUEST['code']);
}

if ( !empty($_REQUEST['intitule']) )           
{
    $isSearched .= $langCourseTitle . ' = ' . $_REQUEST['intitule'] . ' ';
    $advanced_search_query_string[] = 'intitule=' . urlencode($_REQUEST['intitule']);
}

if ( !empty($_REQUEST['category']) )           
{
    $isSearched .= $langCategory . ' = ' . $_REQUEST['category'] . ' ';
    $advanced_search_query_string[] = 'category=' . urlencode($_REQUEST['category']);
}
if ( !empty($_REQUEST['language']) )           
{
    $isSearched .= $langLanguage . ' : ' . $_REQUEST['language'] . ' ';
    $advanced_search_query_string[] = 'language=' . urlencode($_REQUEST['language']);
}
if (isset($_REQUEST['access'])   && $_REQUEST['access'] == 'public')         
{
    $isSearched .= ' <b><br />' . $langPublicOnly . ' </b> ';
    
}
if (isset($_REQUEST['access']) && $_REQUEST['access'] == 'private')        
{
    $isSearched .= ' <b><br />' . $langPrivateOnly . ' </b>  ';
}
if (isset($_REQUEST['subscription']) && $_REQUEST['subscription'] == 'allowed') 
{
    $isSearched .= ' <b><br />' . $langSubscriptionAllowedOnly . ' </b>  ';
}
if (isset($_REQUEST['subscription']) && $_REQUEST['subscription'] == 'denied')  
{
    $isSearched .= ' <b><br />' . $langSubscriptionDeniedOnly . ' </b>  ';
}

//see what must be kept for advanced links

if ( !empty($_REQUEST['access']) )
{
   $advanced_search_query_string[] ='access=' . urlencode($_REQUEST['access']);
}
if ( !empty($_REQUEST['subscription']) )
{
   $advanced_search_query_string[] ='subscription=' . urlencode($_REQUEST['subscription']);
}

if ( count($advanced_search_query_string) > 0 )
{
    $addtoAdvanced = '?' . implode('&',$advanced_search_query_string);
}
else
{
    $addtoAdvanced = '';
}

//finaly, form itself

if( empty($isSearched) )
{
    $title = '&nbsp;';
    $isSearched = '&nbsp;';
} 
else 
{
    $title = $langSearchOn . ' : ';
}

echo "\n".'<table width="100%">'."\n\n"
.    '<tr>'."\n"
.    '<td align="left">'."\n"
.    '<b>' 
.    $title 
.    '</b>'
.    '<small>'
.    $isSearched 
.    '</small>'
.    '</td>'."\n"
.    '<td align="right">'."\n\n"
.    '<form action="' . $_SERVER['PHP_SELF'] . '">'."\n"
.    '<label for="search">' . $langMakeNewSearch . '</label>'."\n"
.    '<input type="text" value="' . htmlspecialchars($search) . '" name="search" id="search">'."\n"
.    '<input type="submit" value=" ' . $langOk . ' ">'."\n"
.    '<input type="hidden" name="newsearch" value="yes">'."\n"
.    '[<a class="claroCmd" href="advancedCourseSearch.php' . $addtoAdvanced . '">' 
.    $langAdvanced
.    '</a>]'."\n"
.    '</form>'."\n\n"
.    '</td>'."\n"
.    '</tr>'."\n\n"
.    '</table>'."\n\n"
;


   //Pager

$myPager->disp_pager_tool_bar($_SERVER['PHP_SELF']);

// display list

echo "\n\n".'<table class="claroTable emphaseLine" width="100%" border="0" cellspacing="2">'."\n\n"
.    '<thead>'."\n"
.    '<tr class="headerX" align="center" valign="top">'."\n"


//add titles for the table
.    '<th>'
.    '<a href="' . $_SERVER['PHP_SELF'] . '?order_crit=code&amp;dir=' . $order['code'] . '">'
.    $langCode 
.    '</a>'
.    '</th>'."\n"

.    '<th>'
.    '<a href="' . $_SERVER['PHP_SELF'] . '?order_crit=label&amp;dir=' . $order['label'] . '">' 
.    $langCourseTitle
.    '</a>'
.    '</th>'."\n"

.    '<th>'
.    '<a href="' . $_SERVER['PHP_SELF'] . '?order_crit=cat&amp;dir=' . $order['cat'] . '">' 
.    $langCategory 
.    '</a>'
.    '</th>'."\n"

.    '<th>' . $langAllUsersOfThisCourse . '</th>'."\n"
.    '<th>' . $langCourseSettings . '</th>'."\n"
.    '<th>' . $langDelete . '</th>'."\n"
.    '</tr>'."\n"
.    '</thead>' . "\n\n"

// Display list of the course of the user :

.    '<tbody>' ."\n\n"
;

foreach($resultList as $courseLine)
{
    echo '<tr>'."\n";


    if (    isset($_SESSION['admin_course_search']) 
        && $_SESSION['admin_course_search'] != '' ) 
        //trick to prevent "//1" display when no keyword used in search
    {
        $bold_search = str_replace('*', '.*', $_SESSION['admin_course_search']);

        //  Code

        echo '<td >'
        .    eregi_replace("(".$bold_search.")","<b>\\1</b>", $courseLine['officialCode'])
        .    '</td>'."\n"

        // title

        .    '<td align="left">'
        .    '<a href="' . $coursesRepositoryWeb . $courseLine['directory'] . '">'
        .    eregi_replace("(".$bold_search.")","<b>\\1</b>", $courseLine['intitule'])
        .    '</a></td>'."\n"

        //  Category
        .    '<td align="left">'
        .    eregi_replace("(".$bold_search.")","<b>\\1</b>", $courseLine['faculte'])
        .    '</td>'."\n";
    }
    else
    {
        //  Code

        echo '<td >'
        .    $courseLine['officialCode']
        .    '</td>'."\n"

        // title

        .    '<td align="left">'
        .    '<a href="' . $coursesRepositoryWeb . $courseLine['directory'] . '">'
        .    $courseLine['intitule']
        .    '</a>'
        .    '</td>'."\n"

        //  Category

        .    '<td align="left">' . $courseLine['faculte'] . '</td>'."\n"
        ;
    }



     //  All users of this course

    echo  '<td align="right">' . "\n"
    .     '<a href="admincourseusers.php'
    .     '?cidToEdit=' . $courseLine['code'] . $addToURL . '&amp;cfrom=clist">'
    .     sprintf( ( $courseLine['qty_cm'] + $courseLine['qty_stu'] > 1 ? $lang_p_d_course_members : $lang_p_d_course_member)
                 , ( $courseLine['qty_stu'] + $courseLine['qty_cm'] ) )
    .     '</a>'
    .     '<br />'."\n".'<small><small>'."\n"
    .     sprintf( ( $courseLine['qty_cm'] > 1 ? $lang_p_d_course_managers : $lang_p_d_course_manager)
                 , $courseLine['qty_cm']) . "\n"
    .     sprintf( ( $courseLine['qty_stu'] > 1 ? $lang_p_d_students : $lang_p_d_student)
                 , $courseLine['qty_stu']) . "\n"
    .     '</small></small>' . "\n"
    .     '</td>' . "\n"

    // Modify course settings

    .    '<td align="center">' ."\n"
    .    '<a href="../course_info/infocours.php?adminContext=1'
    .    '&amp;cidReq=' . $courseLine['code'] . $addToURL . '&amp;cfrom=clist">'
    .    '<img src="' . $imgRepositoryWeb . 'settings.gif" alt="' . $langCourseSettings. '">'
    .    '</a>'
    .    '</td>' . "\n"

    //  Delete link


    .    '<td align="center">' . "\n"
    .    '<a href="' . $_SERVER['PHP_SELF'] 
    .    '?cmd=delete&amp;delCode=' . $courseLine['code'] . $addToURL . '" '
    .    ' onClick="return confirmation(\'' . clean_str_for_javascript($courseLine['intitule']) . '\');">' . "\n"
    .    '<img src="' . $imgRepositoryWeb . 'delete.gif" border="0" alt="' . $langDelete . '" />' . "\n"
    .    '</a>' . "\n"
    .    '</td>' . "\n"
    .    '</tr>' . "\n\n"
    ;
    $atleastOneResult = TRUE;
}

if (!isset($atleastOneResult))
{
   echo '<tr>'."\n"
   .    '<td colspan="6" align="center">'
   .    $langNoCourseResult . '<br />'
   .    '<a href="advancedCourseSearch.php' . $addtoAdvanced . '">' . $langSearchAgain . '</a>'
   .    '</td>'."\n"
   .    '</tr>'."\n\n"
   ;
}
echo '</tbody>'."\n\n"
.    '</table>'."\n\n"
;

//Pager

$myPager->disp_pager_tool_bar($_SERVER['PHP_SELF']);

// display footer

include($includePath . '/claro_init_footer.inc.php');
?>
