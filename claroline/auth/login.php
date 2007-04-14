<?php # $Id$
/**
 * CLAROLINE
 *
 * This script allows users to log on platform and back to requested ressource
 *
 * @version 1.8 $Revision$
 *
 * @copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
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
         &&   basename($_SERVER ['HTTP_REFERER']) != basename($_SERVER['PHP_SELF'])
         && ! strstr($_SERVER ['HTTP_REFERER'], 'logout=true') )
{
     $sourceUrl = base64_encode($_SERVER ['HTTP_REFERER']);
}
else
{
    $sourceUrl = null;
}

// Immediatly redirect to the CAS authentication process
// If CAS is the only authentication system enabled

if (get_conf('claro_CasEnabled',false) && ! get_conf('claro_displayLocalAuthForm',true))
{
    claro_redirect($_SERVER['PHP_SELF'] . '?authModeReq=CAS&sourceUrl='.urlencode($sourceUrl));
}

if ( $sourceUrl )
{
    $sourceUrlFormField = '<input type="hidden" name="sourceUrl" value="'.htmlspecialchars($sourceUrl).'" />';
}
else
{
    $sourceUrlFormField = '';
}

if (claro_is_in_a_course())
{
    $sourceCidFormField = '<input type="hidden" name="sourceCid" value="' . htmlspecialchars(claro_get_current_course_id()) . '" />';
}
else
{
    $sourceCidFormField = '';
}

if (claro_is_in_a_group())
{
    $sourceGidFormField = '<input type="hidden" name="sourceGid" value="' . htmlspecialchars(claro_get_current_group_id()) . '" />';
}
else
{
    $sourceGidFormField = '';
}

$cidRequired = (isset($_REQUEST['cidRequired']) ? $_REQUEST['cidRequired'] : false );
$cidRequiredFormField = ($cidRequired ? '<input type="hidden" name="cidRequired" value="true" />' : '');

$uidRequired = true; // todo : possibility to continue in anonymous

if ( ! claro_is_user_authenticated() && $uidRequired )
{
    // Display header
    require get_path('incRepositorySys') . '/claro_init_header.inc.php';

    if( get_conf('claro_displayLocalAuthForm',true) == true )
    {
        // Display login form
        echo '<table align="center">'                                     ."\n"
        .    '<tr>'                                                       ."\n"
        .    '<td>'                                                       ."\n"
        .    claro_html_tool_title(get_lang('Authentication Required'));

        if ( $claro_loginRequested && ! $claro_loginSucceeded ) // var comming from claro_init_local.inc.php
        {
            $message = get_lang('Login failed.') . ' ' . get_lang('Please try again.') . '<br />' . "\n" ;

            if ( get_conf('allowSelfReg',false))
            {
                $message .= get_lang('If you haven\'t a user account yet, use the <a href="%url">the account creation form</a>.',array('%url'=> get_path('url') . '/claroline/auth/inscription.php')) . '<br / ><br />' . "\n";
            }
            else
            {
                $message .= get_lang('Contact your administrator.');
            }
            $message .= '<small>' . get_lang('Warning the system distinguishes uppercase (capital) and lowercase (small) letters') . '</small>' . "\n" ;

            echo claro_html_message_box($message);

        }

        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">' ."\n"
        .    '<fieldset>'                                                 ."\n"
        .    $sourceUrlFormField                                          ."\n"
        .    $cidRequiredFormField
        .    $sourceCidFormField                                          ."\n"
        .    $sourceGidFormField                                          ."\n"
        .    '<legend>' . get_lang('Authentication') . '</legend>'               ."\n"

        .    '<label for="username">'.get_lang('Username').' : </label><br />'   ."\n"
        .    '<input type="text" name="login" id="username" /><br />'       ."\n"

        .    '<label for="password">'.get_lang('Password').' : </label><br />'   ."\n"
        .    '<input type="password" name="password" id="password" /><br />'."\n"
        .    '<br />'
        .    '<input type="submit" value="'.get_lang('Ok').'" />&nbsp; '                 ."\n"
        .    claro_html_button(get_path('clarolineRepositoryWeb'), get_lang('Cancel'))
        .    '</fieldset>'                                                ."\n"
        .    '</form>'                                                    ."\n"
        ;

        echo '</td>'                                                    ."\n"
        .    '</tr>'                                                    ."\n"
        .    '</table>'                                                 ."\n"
        ;
    } // end if claro_dispLocalAuthForm

    if (get_conf('claro_CasEnabled',false))
    {
        echo '<div align="center">'
        .    '<a href="login.php?'. ($sourceUrl ? 'sourceUrl='.urlencode($sourceUrl) : '').'&authModeReq=CAS">'
        .    ( '' != trim(get_conf('claro_CasLoginString','')) ? get_conf('claro_CasLoginString') : get_lang('Login'))
        .    '</a>'
        .    '</div>';
    } // end if claro_CASEnabled

    // Display footer
    require get_path('incRepositorySys') . '/claro_init_footer.inc.php';
}
elseif ( ! claro_is_in_a_course() && $cidRequired )
{
    /*
     * The script the user is trying to access
     * is only able to work inside a course
     * and no course are set.
     */

    $tbl                = claro_sql_get_main_tbl();

    $sql = "SELECT c.code                                  AS `value`,
                   CONCAT(c.intitule,' (',c.fake_code,')') AS `name`
            FROM `" . $tbl['course'] . "`          AS c ,
                 `" . $tbl['rel_course_user'] . "` AS cu
            WHERE c.code= cu.code_cours
              AND cu.user_id = '" . (int) claro_get_current_user_id() . "'" ;

    $courseList = claro_sql_query_fetch_all($sql);

    // Display header
    require get_path('incRepositorySys') . '/claro_init_header.inc.php';

    if ( $courseList !== false && count($courseList) > 0 )
    {
        // Display select course form
        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">' ."\n"
        .    '<table align="center">'                                ."\n"
        .    '<tr>'                                                  ."\n"
        .    '<td colspan="2">'                                      ."\n"
        .    claro_html_tool_title(get_lang('Choose a course to access this page.'))
        .    $sourceUrlFormField                                     ."\n"
        .    $cidRequiredFormField                                   ."\n"
        .    $sourceCidFormField                                     ."\n"
        .    $sourceGidFormField                                     ."\n"
        .    '<tr>'                                                  ."\n"
        .    '<td>'                                                  ."\n"
        .    '<label for="selectCourse">'
        .    get_lang('Course')
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
        .    '<input type="submit" value="' . get_lang('Ok') . '" />&nbsp; '."\n"
        .    claro_html_button(get_path('url') . '/index.php', get_lang('Cancel'))
        .    '</td>'                                                 ."\n"
        .    '</tr>'                                                 ."\n"
        .    '</table>'                                              ."\n"
        .    '</form>'                                               ."\n"
        ;
    }
    else
    {
        // Display link to student to enrol to this course
        echo '<p align="center">'           ."\n"
        .    get_lang('If you wish to enrol on this course') . ' : '
        .    ' <a href="' . get_path('clarolineRepositoryWeb') . 'auth/courses.php?cmd=rqReg">'
        .    get_lang('Enrolment').'</a>' ."\n"
        .    '</p>'          ."\n";
    }

    // Display footer
    require get_path('incRepositorySys') . '/claro_init_footer.inc.php';
}
else // LOGIN SUCCEEDED
{
    if(!isset($userLoggedOnCas))
        $userLoggedOnCas = false;
    //notify that a user has just loggued in
    $eventNotifier->notifyEvent('user_login', array('uid' => claro_get_current_user_id()));
    //record in tracking tables
    event_login();

    if ( claro_is_in_a_course() && ! claro_is_course_allowed() )
    {
        // Display header
        require get_path('incRepositorySys') . '/claro_init_header.inc.php';

        if ( $_course['registrationAllowed'] )
        {
            if ( claro_is_user_authenticated() )
            {
                // Display link to student to enrol to this course
                echo '<p align="center">'           ."\n"
                .    get_lang('Your user profile doesn\'t seem to be enrolled on this course').'<br />'
                .    get_lang('If you wish to enrol on this course') . ' : '
                .    ' <a href="' . get_path('clarolineRepositoryWeb') . 'auth/courses.php?cmd=rqReg&amp;keyword=' . urlencode($_course['officialCode']) . '">'
                .    get_lang('Enrolment').'</a>' ."\n"
                .    '</p>'          ."\n";

            }
            elseif ( get_conf('allowSelfReg') )
            {
                // Display a link to anonymous to register on the platform
                echo '<p align="center">' . "\n"
                .    get_lang('Create first a user account on this platform') . ' : '
                .    '<a href="' . get_path('clarolineRepositoryWeb') . 'auth/inscription.php">'
                .    get_lang('Go to the account creation page')
                .    '</a>'                                         ."\n"
                .    '</p>'                                         ."\n"
                ;
            }
            else
            {
                // Anonymous cannot register on the platform
                echo '<p align="center">'                           ."\n"
                . get_lang('Registration not allowed on the platform')
                .    '</p>'                                         ."\n";
            }
        }
        else
        {
        // Enrolment is not allowed for this course
            echo '<p align="center">'                           ."\n"
                . get_lang('Enrol to course not allowed');
            if ($_course['email'] && $_course['titular'])
            {
                echo '<br />' . get_lang('Please contact course titular(s)') . ' : ' . $_course['titular']
                .    '<br /><small>' . get_lang('Email') . ' : <a href="mailto:' . $_course['email'] .'">' . $_course['email']. '</a>';

            }

            echo '</p>' . "\n";
        }

        // Display footer
        require get_path('incRepositorySys') . '/claro_init_footer.inc.php';

    }
    elseif($userLoggedOnCas && isset($_SESSION['casCallBackUrl']))
    {
        claro_redirect(base64_decode($_SESSION['casCallBackUrl']));
    }
    elseif( isset($sourceUrl) ) // send back the user to the script authentication trigger
    {
        $sourceUrl = base64_decode($sourceUrl);
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

        claro_redirect($sourceUrl);
    }
    elseif ( claro_is_in_a_course() )
    {
        claro_redirect(get_path('coursesRepositoryWeb') . '/' . claro_get_course_path());
    }
    else
    {
        claro_redirect(get_path('clarolineRepositoryWeb'));
    }
}
?>
