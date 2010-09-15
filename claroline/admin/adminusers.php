<?php //$Id$
/**
 * CLAROLINE
 * @version 1.9 $Revision$
 *
 * @copyright (c) 2001-2010, Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @package ADMIN
 *
 * @author Guillaume Lederer <lederer@claroline.net>
 */
$cidReset = TRUE; $gidReset = TRUE; $tidReset = TRUE;

require '../inc/claro_init_global.inc.php';

$userPerPage = get_conf('userPerPage',20); // numbers of user to display on the same page

// Security check
if ( ! claro_is_user_authenticated() ) claro_disp_auth_form();
if ( ! claro_is_platform_admin() ) claro_die(get_lang('Not allowed'));

require_once get_path('incRepositorySys') . '/lib/pager.lib.php';
require_once get_path('incRepositorySys') . '/lib/admin.lib.inc.php';
require_once get_path('incRepositorySys') . '/lib/user.lib.php';
include claro_get_conf_repository() . 'course_main.conf.php';

// CHECK INCOMING DATAS
if ((isset($_REQUEST['cidToEdit'])) && ($_REQUEST['cidToEdit']=='')) {unset($_REQUEST['cidToEdit']);}

$validCmdList = array('rqDelete', 'exDelete');
$cmd = (isset($_REQUEST['cmd']) && in_array($_REQUEST['cmd'],$validCmdList)? $_REQUEST['cmd'] : null);
$userIdReq = (int) (isset($_REQUEST['user_id']) ? $_REQUEST['user_id']: null);
// USED SESSION VARIABLES
// clean session if needed

if (isset($_REQUEST['newsearch']) && $_REQUEST['newsearch'] == 'yes')
{
    unset($_SESSION['admin_user_search'   ]);
    unset($_SESSION['admin_user_firstName']);
    unset($_SESSION['admin_user_lastName' ]);
    unset($_SESSION['admin_user_userName' ]);
    unset($_SESSION['admin_user_officialCode' ]);
    unset($_SESSION['admin_user_mail'     ]);
    unset($_SESSION['admin_user_action'   ]);
    unset($_SESSION['admin_order_crit'    ]);
}

// deal with session variables for search criteria, it depends where we come from :
// 1 ) we must be able to get back to the list that concerned the criteria we previously used (with out re entering them)
// 2 ) we must be able to arrive with new critera for a new search.

if (isset($_REQUEST['search'    ])) $_SESSION['admin_user_search'    ] = trim($_REQUEST['search'    ]);
if (isset($_REQUEST['firstName' ])) $_SESSION['admin_user_firstName' ] = trim($_REQUEST['firstName' ]);
if (isset($_REQUEST['lastName'  ])) $_SESSION['admin_user_lastName'  ] = trim($_REQUEST['lastName'  ]);
if (isset($_REQUEST['userName'  ])) $_SESSION['admin_user_userName'  ] = trim($_REQUEST['userName'  ]);
if (isset($_REQUEST['officialCode'  ])) $_SESSION['admin_user_officialCode'  ] = trim($_REQUEST['officialCode'  ]);
if (isset($_REQUEST['mail'      ])) $_SESSION['admin_user_mail'      ] = trim($_REQUEST['mail'      ]);
if (isset($_REQUEST['action'    ])) $_SESSION['admin_user_action'    ] = trim($_REQUEST['action'    ]);

if (isset($_REQUEST['order_crit'])) $_SESSION['admin_user_order_crit'] = trim($_REQUEST['order_crit']);
if (isset($_REQUEST['dir'       ])) $_SESSION['admin_user_dir'       ] = ($_REQUEST['dir'] == 'DESC' ? 'DESC' : 'ASC' );

JavascriptLoader::getInstance()->load('jquery.qtip-1.0.0-rc3');

$addToURL = ( isset($_REQUEST['addToURL']) ? $_REQUEST['addToURL'] : '');

$dialogBox = new DialogBox();

//TABLES
//declare needed tables

// Deal with interbreadcrumbs

ClaroBreadCrumbs::getInstance()->prepend( get_lang('Administration'), get_path('rootAdminWeb') );
$nameTools = get_lang('User list');


$offset       = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : 0 ;
//TABLES

//------------------------------------
// Execute COMMAND section
//------------------------------------
switch ( $cmd )
{
    case 'exDelete' :
    {
        if( user_delete($userIdReq) )
        {
           $dialogBox->success( get_lang('Deletion of the user was done sucessfully') );
        }
        else
        {
            $dialogBox->error( get_lang('You can not change your own settings!') );
        }
    }
    break;
    case 'rqDelete' :
    {
        if( empty( $userIdReq ) )
        {
            $dialogBox->error( get_lang('User id missing') );
        }
        else
        {
            $user_properties = user_get_properties( $userIdReq );
            if( is_array( $user_properties) )
            {
                $dialogBox->question( get_lang('Are you sure to delete user %firstname %lastname', array('%firstname' => $user_properties['firstname'], '%lastname' => $user_properties['lastname'])).'<br/><br/>'."\n"
                .    '<a href="'.$_SERVER['PHP_SELF'].'?cmd=exDelete&amp;user_id='.$userIdReq.'&amp;offset='.$offset. $addToURL .'">'.get_lang('Yes').'</a>'
                .    ' | '
                .    '<a href="'.$_SERVER['PHP_SELF'].'">'.get_lang('No').'</a>'."\n");
            }
        }
    }
}
$searchInfo = prepare_search();

$isSearched    = $searchInfo['isSearched'];
$addtoAdvanced = $searchInfo['addtoAdvanced'];

if(count($searchInfo['isSearched']) )
{
    $isSearched = array_map( 'strip_tags', $isSearched );
    $isSearchedHTML = implode('<br />', $isSearched);
}
else
{
    $isSearchedHTML = '';
}

//get the search keyword, if any
$search  = (isset($_REQUEST['search']) ? $_REQUEST['search'] : '');

$sql = get_sql_filtered_user_list();

$myPager      = new claro_sql_pager($sql, $offset, $userPerPage);

if ( array_key_exists( 'sort', $_GET ) )
{
    $dir = array_key_exists( 'dir', $_GET ) && $_GET['dir'] == SORT_DESC
        ? SORT_DESC
        : SORT_ASC
        ;

    $sortKey = strip_tags( $_GET['sort'] );
        
    $myPager->add_sort_key( $sortKey, $dir );
}

$defaultSortKeyList = array ('isPlatformAdmin' => SORT_DESC,
                             'name'          => SORT_ASC,
                             'firstName'       => SORT_ASC);

foreach($defaultSortKeyList as $thisSortKey => $thisSortDir)
{
    $myPager->add_sort_key( $thisSortKey, $thisSortDir);
}

$userList = $myPager->get_result_list();
if (is_array($userList))
{
    $tbl_mdb_names              = claro_sql_get_main_tbl();
    $tbl_course                 = $tbl_mdb_names['course'];
    $tbl_category               = $tbl_mdb_names['category'];
    $tbl_rel_course_user        = $tbl_mdb_names['rel_course_user'];
    $tbl_rel_course_category    = $tbl_mdb_names['rel_course_category'];
    
    foreach ($userList as $userKey => $user)
    {
        // Count number of courses
        $sql = "SELECT count(DISTINCT code_cours) AS qty_course
                FROM `" . $tbl_rel_course_user . "`
                WHERE user_id = '". (int) $user['user_id'] ."'
                GROUP BY user_id";
        
        $userList[$userKey]['qty_course'] = (int) claro_sql_query_get_single_value($sql);
        
        // Count number of categories
        $sql = "SELECT COUNT(ca.id) FROM `{$tbl_category}` AS ca
                
                LEFT JOIN `{$tbl_rel_course_category}` AS rcc
                ON ca.id = rcc.categoryId
                
                LEFT JOIN `{$tbl_course}` AS co
                ON rcc.courseId = co.cours_id
                
                LEFT JOIN `{$tbl_rel_course_user}` AS rcu
                ON rcu.code_cours = co.code
                
                WHERE rcc.rootCourse = 1
                AND rcu.user_id = ".(int) $user['user_id']."
                
                GROUP BY ca.id";
                
        $userList[$userKey]['qty_category'] = (int) claro_sql_query_get_single_value($sql);
    }
}

$userGrid = array();
if (is_array($userList))
foreach ($userList as $userKey => $user)
{

    $userGrid[$userKey]['user_id']   = $user['user_id'];
    $userGrid[$userKey]['name']      = $user['name'];
    $userGrid[$userKey]['firstname'] = $user['firstname'];
    $userEmailLabel=null;
    if ( !empty($_SESSION['admin_user_search']) )
    {
        $bold_search = str_replace('*','.*',$_SESSION['admin_user_search']);

        $userGrid[$userKey]['name'] = preg_replace('/(' . $bold_search . ')/i' , '<b>\\1</b>', $user['name']);
        $userGrid[$userKey]['firstname'] = preg_replace('/(' . $bold_search . ')/i' , '<b>\\1</b>', $user['firstname']);
        $userEmailLabel  = preg_replace('/(' . $bold_search . ')/i', '<b>\\1</b>' , $user['email']);
    }
    
    $userGrid[$userKey]['officialCode'] = empty($user['officialCode']) ? ' - ' : $user['officialCode'];
    $userGrid[$userKey]['email'] = claro_html_mailTo($user['email'], $userEmailLabel);
    
    $userGrid[$userKey]['isCourseCreator'] =  ( $user['isCourseCreator']?get_lang('Course creator'):get_lang('User'));
    
    if ( $user['isPlatformAdmin'] )
    {
        $userGrid[$userKey]['isCourseCreator'] .= '<br /><span class="highlight">' . get_lang('Administrator').'</span>';
    }
    $userGrid[$userKey]['settings'] = '<a href="adminprofile.php'
    .                                 '?uidToEdit=' . $user['user_id']
    .                                 '&amp;cfrom=ulist' . $addToURL . '">'
    .                                 '<img src="' . get_icon_url('usersetting') . '" alt="' . get_lang('User settings') . '" />'
    .                                 '</a>';
    
    
    
    if (get_conf("registrationRestrictedThroughCategories"))
    {
        $userGrid[$userKey]['qty_category'] = '<a class="showUserCategory">'
        .                                   '<span class="' . $user['user_id'] . '"></span>' . "\n"
        .                                   get_lang('%nb category(ies)', array('%nb' => $user['qty_category'])) . "\n"
        .                                   '</a>' . "\n";
    }
    
    
    $userGrid[$userKey]['qty_course'] = '<a class="showUserCourses" href="adminusercourses.php?uidToEdit=' . $user['user_id']
    .                                   '&amp;cfrom=ulist' . $addToURL . '"><span class="' . $user['user_id'] . '"></span>' . "\n"
    .                                   get_lang('%nb course(s)', array('%nb' => $user['qty_course'])) . "\n"
    .                                   '</a>' . "\n"
    ;
    
    $userGrid[$userKey]['delete'] = '<a href="' . $_SERVER['PHP_SELF']
    .                               '?cmd=rqDelete&amp;user_id=' . $user['user_id']
    .                               '&amp;offset=' . $offset . $addToURL . '" '
    //.                               ' onclick="return confirmation(\'' . clean_str_for_javascript(' ' . $user['firstname'] . ' ' . $user['name']).'\');" '
    .                               ' class="delete" id="'.$user['firstname'].'_' . $user['name'] .'_' . $user['user_id'] .'">' . "\n"
    .                               '<img src="' . get_icon_url('deluser') . '" alt="' . get_lang('Delete') . '" />' . "\n"
    .                               '</a> '."\n"
    ;
}

$sortUrlList = $myPager->get_sort_url_list($_SERVER['PHP_SELF']);

// Build the list of columns' titles
$colTitleList = array (
    'user_id'           => '<a href="' . $sortUrlList['user_id'] . '">' . get_lang('Numero') . '</a>',
    'name'              => '<a href="' . $sortUrlList['name'] . '">' . get_lang('Last name') . '</a>',
    'firstname'         => '<a href="' . $sortUrlList['firstname'] . '">' . get_lang('First name') . '</a>',
    'officialCode'      => '<a href="' . $sortUrlList['officialCode'] . '">' . get_lang('Administrative code') . '</a>',
    'email'             => '<a href="' . $sortUrlList['email'] . '">' . get_lang('Email') . '</a>',
    'isCourseCreator'   => '<a href="' . $sortUrlList['isCourseCreator'] . '">' . get_lang('Status') . '</a>',
    'settings'          => get_lang('User settings')
);

if (get_conf("registrationRestrictedThroughCategories"))
    $colTitleList['qty_category'] = get_lang('Categories');
    
$colTitleList['qty_course']    = get_lang('Courses');
$colTitleList['delete']        = get_lang('Delete');

$userDataGrid = new claro_datagrid();
$userDataGrid->set_grid($userGrid);
$userDataGrid->set_colHead('name') ;
$userDataGrid->set_colTitleList($colTitleList);

if ( count($userGrid)==0 )
{
    $userDataGrid->set_noRowMessage( '<center>'.get_lang('No user to display') . "\n"
    .    '<br />' . "\n"
    .    '<a href="advancedUserSearch.php' . $addtoAdvanced . '">' . get_lang('Search again (advanced)') . '</a></center>' . "\n"
    );
}
else
{
    $userDataGrid->set_colAttributeList(array ( 'user_id'      => array ('align' => 'center')
                                              , 'officialCode' => array ('align' => 'center')
                                              , 'settings'     => array ('align' => 'center')
                                              , 'delete'       => array ('align' => 'center')
    ));
}

//---------
// DISPLAY
//---------


//PREPARE
// javascript confirm pop up declaration
$htmlHeadXtra[] =
'<script type="text/javascript">
        function confirmation (name)
        {
            if (confirm("'.clean_str_for_javascript(get_lang('Are you sure to delete')).'" + name + "? "))
                {return true;}
            else
                {return false;}
        }'
."\n".'</script>'."\n";

$out = '';

// Display tool title
$out .= claro_html_tool_title($nameTools) . "\n\n";

//Display selectbox and advanced search link

//TOOL LINKS

//Display search form

if ( !empty($isSearchedHTML) )
{
    $dialogBox->info( ('<b>' . get_lang('Search on') . '</b> : <small>' . $isSearchedHTML . '</small>') );
}

//Display Forms or dialog box(if needed)

$out .= $dialogBox->render();

$out .= '<table width="100%">' . "\n"
.    '<tr>' . "\n"
.    '<td>' . '<a class="claroCmd" href="adminaddnewuser.php">'
.    '<img src="' . get_icon_url('user') . '" alt="" />'
.    get_lang('Create user')
.    '</a>'
.    '</td>' . "\n"
.    '<td>' . ''
.    '<td align="right">' . "\n"
.    '<form action="' . $_SERVER['PHP_SELF'] . '">' . "\n"
.    '<label for="search">' . get_lang('Make new search') . '  </label>' . "\n"
.    '<input type="text" value="' . htmlspecialchars($search).'" name="search" id="search" />' . "\n"
.    '<input type="submit" value=" ' . get_lang('Ok') . ' " />' . "\n"
.    '<input type="hidden" name="newsearch" value="yes" />' . "\n"
.    '&nbsp;[<a class="claroCmd" href="advancedUserSearch.php' . $addtoAdvanced . '" >' . get_lang('Advanced') . '</a>]' . "\n"
.    '</form>' . "\n"
.    '</td>' . "\n"
.    '</tr>' . "\n"
.    '<tr>'
.    '</tr>'
.    '</table>' . "\n\n"
;

$url = ($search =='')?$_SERVER['PHP_SELF']: $_SERVER['PHP_SELF']. '?search='.$search;
if ( count($userGrid) > 0 ) $out .= $myPager->disp_pager_tool_bar($url);

$out .= $userDataGrid->render();

if ( count($userGrid) > 0 ) $out .= $myPager->disp_pager_tool_bar($url);


$out .=
'<script type="text/javascript">
    $(document).ready(function(){
    $(".delete").each(function( i )
        {
            var _id = $(this).attr("id");
            var id = _id.substr(_id.lastIndexOf("_") + 1 );
            var firstname = _id.substr(0,_id.indexOf("_"));
            var lastname = _id.substr(_id.indexOf("_") + 1 );
            lastname = lastname.substr(0, lastname.lastIndexOf("_"));
            
            $(this).click(function()
            {
                return confirmation(" " + firstname + " " + lastname);
            });
            $(this).attr("href","'. $_SERVER['PHP_SELF'] .'?cmd=exDelete&user_id=" + id + "&offset=' . $offset . $addToURL . '");
        });
    });
    
    $("a.showUserCourses").each(function()
    {
        $(this).qtip({
            content: {
                url: "./ajax/ajax_requests.php",
                data: { action: "getUserCourseList", userId: $(this).find("span").attr("class") },
                method: "get"
            },
            
            show: "mouseover",
            hide: "mouseout",
            position: {
                corner: {
                    target: "topRight",
                    tooltip: "bottomRight"
                }
            },
            
            style: {
                width: 200,
                padding: 5,
                background: "#CCDDEE",
                color: "black",
                fontSize: "1em",
                textAlign: "center",
                border: {
                    width: 7,
                    radius: 5,
                    color: "#CCDDEE"
                }
            }
        });
    });
    
    $("a.showUserCategory").each(function()
    {
        $(this).qtip({
            content: {
                url: "./ajax/ajax_requests.php",
                data: { action: "getUserCategoryList", userId: $(this).find("span").attr("class") },
                method: "get"
            },
            
            show: "mouseover",
            hide: "mouseout",
            position: {
                corner: {
                    target: "topRight",
                    tooltip: "bottomRight"
                }
            },
            
            style: {
                width: 200,
                padding: 5,
                background: "#CCDDEE",
                color: "black",
                fontSize: "1em",
                textAlign: "center",
                border: {
                    width: 7,
                    radius: 5,
                    color: "#CCDDEE"
                }
            }
        });
    });
</script>';

$claroline->display->body->appendContent($out);

echo $claroline->display->render();

/**
 *
 * @todo: the  name would  be review  befor move to a lib
 * @todo: eject usage  in function of  $_SESSION
 *
 * @return sql statements
 */
function get_sql_filtered_user_list()
{
    if ( isset($_SESSION['admin_user_action']) )
    {
        switch ($_SESSION['admin_user_action'])
        {
            case 'plateformadmin' :
            {
                $filterOnStatus = 'plateformadmin';
            }  break;
            case 'createcourse' :
            {
               $filterOnStatus= 'createcourse';
            }  break;
            case 'followcourse' :
            {
                $filterOnStatus='followcourse';
            }  break;
            case 'all' :
            {
                $filterOnStatus='';
            }  break;
            default:
            {
                trigger_error('admin_user_action value unknow : '.var_export($_SESSION['admin_user_action'],1),E_USER_NOTICE);
                $filterOnStatus='followcourse';
            }
        }
    }
    else $filterOnStatus='';

    $tbl_mdb_names   = claro_sql_get_main_tbl();

    $sql = "SELECT U.user_id                     AS user_id,
                   U.nom                         AS name,
                   U.prenom                      AS firstname,
                   U.authSource                  AS authSource,
                   U.email                       AS email,
                   U.officialCode                AS officialCode,
                   U.phoneNumber                 AS phoneNumber,
                   U.pictureUri                  AS pictureUri,
                   U.creatorId                   AS creator_id,
                   U.isCourseCreator ,
                   U.isPlatformAdmin             AS isPlatformAdmin
           FROM  `" . $tbl_mdb_names['user'] . "` AS U
           WHERE 1=1 ";

    //deal with admin user search only

    if ($filterOnStatus=='plateformadmin')
    {
        $sql .= " AND U.isPlatformAdmin = 1";
    }

    //deal with KEY WORDS classification call

    if (isset($_SESSION['admin_user_search']))
    {
        $sql .= " AND (U.nom LIKE '%". claro_sql_escape(pr_star_replace($_SESSION['admin_user_search'])) ."%'
                  OR U.prenom LIKE '%".claro_sql_escape(pr_star_replace($_SESSION['admin_user_search'])) ."%' ";
        $sql .= " OR U.email LIKE '%". claro_sql_escape(pr_star_replace($_SESSION['admin_user_search'])) ."%'";
        $sql .= " OR U.username LIKE '". claro_sql_escape(pr_star_replace($_SESSION['admin_user_search'])) ."%'";
        $sql .= " OR U.officialCode = '". claro_sql_escape(pr_star_replace($_SESSION['admin_user_search'])) ."')";
    }

    //deal with ADVANCED SEARCH parameters call

    if ( isset($_SESSION['admin_user_firstName']) && !empty($_SESSION['admin_user_firstname']) )
    {
        $sql .= " AND (U.prenom LIKE '%". claro_sql_escape(pr_star_replace($_SESSION['admin_user_firstName'])) ."%') ";
    }

    if ( isset($_SESSION['admin_user_lastName']) && !empty($_SESSION['admin_user_lastName']) )
    {
        $sql .= " AND (U.nom LIKE '%". claro_sql_escape(pr_star_replace($_SESSION['admin_user_lastName']))."%') ";
    }

    if ( isset($_SESSION['admin_user_userName']) && !empty($_SESSION['admin_user_userName']) )
    {
        $sql.= " AND (U.username LIKE '%". claro_sql_escape(pr_star_replace($_SESSION['admin_user_userName'])) ."%') ";
    }
    
    if ( isset($_SESSION['admin_user_officialCode'])  && !empty($_SESSION['admin_user_officialCode']) )
    {
        $sql.= " AND (U.officialCode LIKE '%". claro_sql_escape(pr_star_replace($_SESSION['admin_user_officialCode'])) ."%') ";
    }

    if ( isset($_SESSION['admin_user_mail']) && !empty($_SESSION['admin_user_mail']) )
    {
        $sql.= " AND (U.email LIKE '%". claro_sql_escape(pr_star_replace($_SESSION['admin_user_mail'])) ."%') ";
    }

    if ($filterOnStatus== 'createcourse' )
    {
        $sql.=" AND (U.isCourseCreator=1)";
    }
    elseif ($filterOnStatus=='followcourse' )
    {
        $sql.=" AND (U.isCourseCreator=0)";
    }

        return $sql;
}



function prepare_search()
{
    $queryStringElementList = array();
    $isSearched = array();

    if ( !empty($_SESSION['admin_user_search']) )
    {
        $isSearched[] =  $_SESSION['admin_user_search'];
    }

    if ( !empty($_SESSION['admin_user_firstName']) )
    {
        $isSearched[] = get_lang('First name') . '=' . $_SESSION['admin_user_firstName'];
        $queryStringElementList [] = 'firstName=' . urlencode($_SESSION['admin_user_firstName']);
    }

    if ( !empty($_SESSION['admin_user_lastName']) )
    {
        $isSearched[] = get_lang('Last name') . '=' . $_SESSION['admin_user_lastName'];
        $queryStringElementList[] = 'lastName=' . urlencode($_SESSION['admin_user_lastName']);
    }

    if ( !empty($_SESSION['admin_user_userName']) )
    {
        $isSearched[] = get_lang('Username') . '=' . $_SESSION['admin_user_userName'];
        $queryStringElementList[] = 'userName=' . urlencode($_SESSION['admin_user_userName']);
    }
    if ( !empty($_SESSION['admin_user_officialCode']) )
    {
        $isSearched[] = get_lang('Official code') . '=' . $_SESSION['admin_user_officialCode'];
        $queryStringElementList[] = 'userName=' . urlencode($_SESSION['admin_user_officialCode']);
    }
    if ( !empty($_SESSION['admin_user_mail']) )
    {
        $isSearched[] = get_lang('Email') . '=' . $_SESSION['admin_user_mail'];
        $queryStringElementList[] = 'mail=' . urlencode($_SESSION['admin_user_mail']);
    }

    if ( !empty($_SESSION['admin_user_action']) && ($_SESSION['admin_user_action'] == 'followcourse'))
    {
        $isSearched[] = '<b>' . get_lang('Follow courses') . '</b>';
        $queryStringElementList[] = 'action=' . urlencode($_SESSION['admin_user_action']);
    }
    elseif ( !empty($_SESSION['admin_user_action']) && ($_SESSION['admin_user_action'] == 'createcourse'))
    {
        $isSearched[] = '<b>' . get_lang('Course creator') . '</b>';
        $queryStringElementList[] = 'action=' . urlencode($_SESSION['admin_user_action']);
    }
    elseif (isset($_SESSION['admin_user_action']) && ($_SESSION['admin_user_action']=='plateformadmin'))
    {
        $isSearched[] = '<b>' . get_lang('Platform administrator') . '  </b> ';
        $queryStringElementList[] = 'action=' . urlencode($_SESSION['admin_user_action']);
    }
    else $queryStringElementList[] = 'action=all';

    if ( count($queryStringElementList) > 0 ) $queryString = '?' . implode('&amp;',$queryStringElementList);
    else                                      $queryString = '';

    $searchInfo['isSearched'] = $isSearched;
    $searchInfo['addtoAdvanced'] = $queryString;

    return $searchInfo;
}

?>