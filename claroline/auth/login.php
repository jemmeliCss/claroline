<?php # $Id$
/**
 * CLAROLINE 
 *
 * This script allows users to log on platform and back to requested ressource
 *
 * @version 1.7 $Revision$
 *
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @package CLAUTH
 *
 * @author Claro Team <cvs@claroline.net>
 */

require '../inc/claro_init_global.inc.php';

/* Capture the source of the authentication trigger to get back to it 
 * if the authentication succeeds 
 */

if ( isset($_REQUEST['sourceUrl']) )
{
    $sourceUrl = $_REQUEST['sourceUrl'];
}
elseif ( isset($_SERVER ['HTTP_REFERER']) 
         && basename($_SERVER ['HTTP_REFERER']) != basename($_SERVER['PHP_SELF']) )
{
     $sourceUrl = $_SERVER ['HTTP_REFERER'];
}
else
{
    $sourceUrl = null;
}

if ( $sourceUrl )
{
    $sourceUrlFormField = '<input type="hidden" name="sourceUrl" value="'.htmlspecialchars($sourceUrl).'">';
}
else
{
    $sourceUrlFormField = '';
}

if ($_cid) 
{
    $sourceCidFormField = '<input type="hidden" name="sourceCid" value="' . htmlspecialchars($_cid) . '">';
}
else
{
	$sourceCidFormField = '';
}

if ($_gid)
{
	$sourceGidFormField = '<input type="hidden" name="sourceGid" value="' . htmlspecialchars($_gid) . '">';
}
else
{
	$sourceGidFormField = '';
}

$cidRequired = (isset($_REQUEST['cidRequired']) ? $_REQUEST['cidRequired'] : false );
$cidRequiredFormField = ($cidRequired ? '<input type="hidden" name="cidRequired" value="true">' : '');


if ( is_null($_uid) )
{
    require $includePath . '/claro_init_header.inc.php';

    echo '<table align="center">'                                     ."\n"
    .    '<tr>'                                                       ."\n"
    .    '<td>'                                                       ."\n"
    .    claro_disp_tool_title('Autentication required.')
    .    '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">' ."\n"
    .    '<fieldset>'                                                 ."\n"
    .    $sourceUrlFormField                                          ."\n"
    .    $cidRequiredFormField
    .    $sourceCidFormField                                          ."\n"
    .    $sourceGidFormField                                          ."\n"
    .    '<legend>' . $langAuthentication . '</legend>'               ."\n"

    .    '<label for="username">'.$langUserName.' : </label><br />'   ."\n"
    .    '<input type="text" name="login" id="username"><br />'       ."\n"

    .    '<label for="password">'.$langPassword.' : </label><br />'   ."\n"
    .    '<input type="password" name="password" id="password"><br />'."\n"
    .    '<br />'
    .    '<input type="submit" value="'.$langOk.'"> '                 ."\n"
    .    claro_disp_button($clarolineRepositoryWeb, $langCancel)
    .    '</fieldset>'                                                ."\n"
    .    '</form>'                                                    ."\n"
    ;

    if ($loginFailed) // var comming from claro_init_local.inc.php
    {
        echo '<p>' . $langInvalidIdSelfReg . '</p>';
    }

    echo '</td>'                                                    ."\n"
    .    '</tr>'                                                    ."\n"
    .    '</table>'                                                 ."\n"
    ;
    
    require $includePath . '/claro_init_footer.inc.php';
}
elseif ( is_null($_cid) && $cidRequired )
{
    /*
     * The script the user is trying to access 
     * is only able to work inside a course 
     * and no course are set.
     */

    $mainTbl                = claro_sql_get_main_tbl();
    $tbl_courses            = $mainTbl['course'         ];
    $tbl_rel_user_courses   = $mainTbl['rel_course_user'];

    $sql = "SELECT c.code                                  `value`, 
                   CONCAT(c.intitule,' (',c.fake_code,')') `name` 
            FROM `" . $tbl_courses."`          c ,  
                 `" . $tbl_rel_user_courses . "` cu
            WHERE c.code= cu.code_cours 
              AND cu.user_id = '" . (int) $_uid . "'" ;

    $courseList = claro_sql_query_fetch_all($sql);

    require $includePath . '/claro_init_header.inc.php';


    echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">' ."\n"
    .    '<table align="center">'                                ."\n"
    .    '<tr>'                                                  ."\n"
    .    '<td colspan="2">'                                      ."\n"
    .    claro_disp_tool_title($langChooseACourseToAccessThisPage)
    .    $sourceUrlFormField                                     ."\n"
    .    $cidRequiredFormField                                   ."\n"
    .    $sourceCidFormField                                     ."\n"
    .    $sourceGidFormField                                     ."\n"
    .    '<tr>'                                                  ."\n"
    .    '<td>'                                                  ."\n"
    .    '<label for="selectCourse">' 
    .    $langCourse 
    .    '</label> : '                                           ."\n"
    .    '</td>'                                                 ."\n"
    .    '<td>'                                                  ."\n"
    .    '<select name="cidReq" id="selectCourse">'              ."\n"
    .    implode("\n", prepare_option_tags($courseList) )        ."\n"
    .    '</select>'                                             ."\n"
    .    '</td>'                                                 ."\n"
    .    '</tr>'                                                 ."\n"
    .    '<tr>'                                                  ."\n"
    .    '<td>'                                                  ."\n"
    .    '</td>'                                                 ."\n"
    .    '<td>'                                                  ."\n"
    .    '<input type="submit" value="' . $langOk . '">'         ."\n"
    .    claro_disp_button($rootWeb, $langCancel)
    .    '</td>'                                                 ."\n"
    .    '</tr>'                                                 ."\n"
    .    '</table>'                                              ."\n"
    .    '</form>'                                               ."\n"
    ;

        require $includePath . '/claro_init_footer.inc.php';
}
else
{
    if ( $_cid && ! $is_courseAllowed )
    {
        if ( $_course['registrationAllowed'] )
        {
            if ( $_uid )
            {
                require $includePath . '/claro_init_header.inc.php';

                echo '<p align="center">'           ."\n"
                .    $lang_your_user_profile_doesnt_seem_to_be_enrolled_to_this_course.'<br />'
                .    $lang_if_you_wish_to_enroll_to_this_course
                .    '<a href="' . $clarolineRepositoryWeb . 'auth/courses.php?cmd=rqReg&amp;keyword=' . urlencode($_course['officialCode']) . '">'
                .    $langReg.'</a>' ."\n"
                .    '</p>'          ."\n";
            }
            elseif( $allowSelfReg )
            {
                echo '<p align="center">'                           ."\n"
                .    $langCreate1stAccountOnPlatform ."\n"
                .    '<a href="' . $clarolineRepositoryWeb . 'auth/inscription.php">'
                .    $langGo2accountCreationPage
                .    '</a>'                                         ."\n"
                .    '</p>'                                         ."\n";
            }
        }
    }
    elseif( isset($sourceUrl) ) // send back the user to the script authentication trigger
    {
        if (isset($_REQUEST['sourceCid']) )
        {
            $sourceUrl .= ( strstr( $sourceUrl, '?' ) ? '&' : '?') 
                       .  'cidReq=' . $_REQUEST['sourceCid'];
        }
        
        if (isset($_REQUEST['sourceGid']))
        {
        	$sourceUrl .= ( strstr( $sourceUrl, '?' ) ? '&' : '?')
                       .  'gidReq=' . $_REQUEST['sourceGid'];
        }
        
        header('Location: ' . http_response_splitting_workaround( $sourceUrl ) );
    }
    elseif ( $_cid )
    {
        header('Location: ' . $coursesRepositoryWeb . '/' . $_course['path']);
    }
    else
    {
        header('Location: ' . $clarolineRepositoryWeb);
    }
}
?>