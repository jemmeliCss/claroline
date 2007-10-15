<?php // $Id$
/**
 * CLAROLINE
 *
 * - For a Student -> View agenda Content
 * - For a Prof    -> - View agenda Content
 *         - Update/delete existing entries
 *         - Add entries
 *         - generate an "announce" entries about an entries
 *
 * @version 1.9 $Revision$
 *
 * @copyright (c) 2001-2007 Universite catholique de Louvain (UCL)
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @package CLCAL
 *
 * @author Claro Team <cvs@claroline.net>
 */

$tlabelReq = 'CLCAL';
$gidReset=true;
require '../inc/claro_init_global.inc.php';
$_user = claro_get_current_user_data();
$_course = claro_get_current_course_data();

//**//

if (claro_is_in_a_group()) $currentContext = claro_get_current_context(array('course','group'));
else                       $currentContext = claro_get_current_context('course');
//**/

require_once get_path('clarolineRepositorySys') . '/linker/linker.inc.php';
require_once './lib/agenda.lib.php';
require_once get_path('incRepositorySys') . '/lib/form.lib.php';

require claro_get_conf_repository() . 'ical.conf.php';
require claro_get_conf_repository() . 'rss.conf.php';

$context = claro_get_current_context(CLARO_CONTEXT_COURSE);
define('CONFVAL_LOG_CALENDAR_INSERT', FALSE);
define('CONFVAL_LOG_CALENDAR_DELETE', FALSE);
define('CONFVAL_LOG_CALENDAR_UPDATE', FALSE);

if ( ! claro_is_in_a_course() || !claro_is_course_allowed() ) claro_disp_auth_form(true);

$nameTools = get_lang('Agenda');

claro_set_display_mode_available(TRUE);

$is_allowedToEdit = claro_is_course_manager();

$cmdList[]=  '<a class="claroCmd" href="' . $_SERVER['PHP_SELF'] . '#today">'
.    get_lang('Today')
.    '</a>'
;

if ( $is_allowedToEdit )
{
    if ( !isset($_REQUEST['cmd']) )
    {
        linker_init_session();
    }

    if( claro_is_jpspan_enabled() )
    {
        linker_set_local_crl( isset ($_REQUEST['id']) );
    }

// 'rqAdd' ,'rqEdit', 'exAdd','exEdit', 'exDelete', 'exDeleteAll', 'mkShow', 'mkHide'


    if ( isset($_REQUEST['cmd'])
    && ( 'rqAdd' == $_REQUEST['cmd'] || 'rqEdit' == $_REQUEST['cmd'] )
    )
    {
        linker_html_head_xtra();
    }
}

//stats
event_access_tool(claro_get_current_tool_id(), claro_get_current_course_tool_data('label'));

$tbl_c_names = claro_sql_get_course_tbl();
$tbl_calendar_event = $tbl_c_names['calendar_event'];

$cmd = ( isset($_REQUEST['cmd']) ) ?$_REQUEST['cmd']: null;

$dialogBox = new DialogBox();

if     ( 'rqAdd' == $cmd ) $subTitle = get_lang('Add an event');
elseif ( 'rqEdit' == $cmd ) $subTitle = get_lang('Edit Event');
else                       $subTitle = '&nbsp;';

//-- order direction
if( !empty($_REQUEST['order']) )
    $orderDirection = strtoupper($_REQUEST['order']);
elseif( !empty($_SESSION['orderDirection']) )
    $orderDirection = strtoupper($_SESSION['orderDirection']);
else
    $orderDirection = 'ASC';

$acceptedValues = array('DESC','ASC');

if( ! in_array($orderDirection, $acceptedValues) )
{
    $orderDirection = 'ASC';
}

$_SESSION['orderDirection'] = $orderDirection;


$is_allowedToEdit = claro_is_allowed_to_edit();
/**
 * COMMANDS SECTION
 */

$display_form = FALSE;
$display_command = FALSE;

if ( $is_allowedToEdit )
{
    if ( isset($_REQUEST['id']) ) $id = (int) $_REQUEST['id'];
    else                          $id = 0;

    if ( isset($_REQUEST['title']) ) $title = trim($_REQUEST['title']);
    else                             $title = '';

    if ( isset($_REQUEST['content']) ) $content = trim($_REQUEST['content']);
    else                               $content = '';

    $lasting = ( isset($_REQUEST['content']) ? trim($_REQUEST['lasting']) : '');

    $autoExportRefresh = FALSE;
    if ( 'exAdd' == $cmd )
    {
        $date_selection = $_REQUEST['fyear'] . '-' . $_REQUEST['fmonth'] . '-' . $_REQUEST['fday'];
        $hour           = $_REQUEST['fhour'] . ':' . $_REQUEST['fminute'] . ':00';

        $entryId = agenda_add_item($title,$content, $date_selection, $hour, $lasting) ;
        if ( $entryId != false )
        {
            $dialogBox->success( get_lang('Event added to the agenda') );

            // update linker and get error log
            $linkerUpdateLog = linker_update();
            if( !empty($linkerUpdateLog) ) $dialogBox->info( $linkerUpdateLog );

            if ( CONFVAL_LOG_CALENDAR_INSERT )
            {
                event_default('CALENDAR', array ('ADD_ENTRY' => $entryId));
            }

            // notify that a new agenda event has been posted

            $eventNotifier->notifyCourseEvent('agenda_event_added', claro_get_current_course_id(), claro_get_current_tool_id(), $entryId, claro_get_current_group_id(), '0');
            $autoExportRefresh = TRUE;

        }
        else
        {
            $dialogBox->error( get_lang('Unable to add the event to the agenda') );
        }
    }

    /*------------------------------------------------------------------------
    EDIT EVENT COMMAND
    --------------------------------------------------------------------------*/


    if ( 'exEdit' == $cmd )
    {
        $date_selection = $_REQUEST['fyear'] . '-' . $_REQUEST['fmonth'] . '-' . $_REQUEST['fday'];
        $hour           = $_REQUEST['fhour'] . ':' . $_REQUEST['fminute'] . ':00';

        if ( !empty($id) )
        {
            if ( agenda_update_item($id,$title,$content,$date_selection,$hour,$lasting))
            {
                $dialogBox->success( get_lang('Event updated into the agenda') );

				// update linker and get error log
	            $linkerUpdateLog = linker_update();
	            if( !empty($linkerUpdateLog) ) $dialogBox->info( $linkerUpdateLog );

                $eventNotifier->notifyCourseEvent('agenda_event_modified', claro_get_current_course_id(), claro_get_current_tool_id(), $id, claro_get_current_group_id(), '0'); // notify changes to event manager
                $autoExportRefresh = TRUE;
            }
            else
            {
                $dialogBox->error( get_lang('Unable to update the event into the agenda') );
            }
        }
    }

    /*------------------------------------------------------------------------
    DELETE EVENT COMMAND
    --------------------------------------------------------------------------*/

    if ( 'exDelete' == $cmd && !empty($id) )
    {

        if ( agenda_delete_item($id) )
        {
            $dialogBox->success( get_lang('Event deleted from the agenda') );

            $eventNotifier->notifyCourseEvent('agenda_event_deleted', claro_get_current_course_id(), claro_get_current_tool_id(), $id, claro_get_current_group_id(), '0'); // notify changes to event manager
            $autoExportRefresh = TRUE;
            if ( CONFVAL_LOG_CALENDAR_DELETE )
            {
                event_default('CALENDAR',array ('DELETE_ENTRY' => $id));
            }
        }
        else
        {
            $dialogBox->error( get_lang('Unable to delete event from the agenda') );
        }

        linker_delete_resource();
    }

    /*----------------------------------------------------------------------------
    DELETE ALL EVENTS COMMAND
    ----------------------------------------------------------------------------*/

    if ( 'exDeleteAll' == $cmd )
    {
        if ( agenda_delete_all_items())
        {
            $dialogBox->success( get_lang('All events deleted from the agenda') );

            if ( CONFVAL_LOG_CALENDAR_DELETE )
            {
                event_default('CALENDAR', array ('DELETE_ENTRY' => 'ALL') );
            }
        }
        else
        {
            $dialogBox->error( get_lang('Unable to delete all events from the agenda') );
        }

        linker_delete_all_tool_resources();
    }
    /*-------------------------------------------------------------------------
    EDIT EVENT VISIBILITY
    ---------------------------------------------------------------------------*/

    if ( 'mkShow' == $cmd  || 'mkHide' == $cmd )
    {
        if ($cmd == 'mkShow')
        {
            $visibility = 'SHOW';
            $eventNotifier->notifyCourseEvent('agenda_event_visible', claro_get_current_course_id(), claro_get_current_tool_id(), $id, claro_get_current_group_id(), '0'); // notify changes to event manager
            $autoExportRefresh = TRUE;
        }

        if ($cmd == 'mkHide')
        {
            $visibility = 'HIDE';
            $eventNotifier->notifyCourseEvent('agenda_event_invisible', claro_get_current_course_id(), claro_get_current_tool_id(), $id, claro_get_current_group_id(), '0'); // notify changes to event manager
            $autoExportRefresh = TRUE;
        }

        if ( agenda_set_item_visibility($id, $visibility)  )
        {
            //$dialogBox->success( get_lang('Visibility modified') );
        }
        //        else
        //        {
        //            //error on delete
        //        }
    }

    /*------------------------------------------------------------------------
    EVENT EDIT
    --------------------------------------------------------------------------*/

    if ( 'rqEdit' == $cmd  || 'rqAdd' == $cmd  )
    {
        claro_set_display_mode_available(false);

        if ( 'rqEdit' == $cmd  && !empty($id) )
        {
            $editedEvent = agenda_get_item($id) ;
            // get date as unixtimestamp for claro_dis_date_form and claro_html_time_form
            $editedEvent['date'] = strtotime($editedEvent['dayAncient'].' '.$editedEvent['hourAncient']);
            $nextCommand = 'exEdit';
        }
        else
        {
            $editedEvent['id'            ] = '';
            $editedEvent['title'         ] = '';
            $editedEvent['content'       ] = '';
            $editedEvent['date'] = time();
            $editedEvent['lastingAncient'] = FALSE;

            $nextCommand = 'exAdd';
        }
        $display_form =TRUE;
    } // end if cmd == 'rqEdit' && cmd == 'rqAdd'


    if ('rqEdit' != $cmd  && 'rqAdd' != $cmd ) // display main commands only if we're not in the event form
    {
        $display_command = TRUE;
    } // end if diplayMainCommands

    if ( $autoExportRefresh)
    {
        // rss update
        if ( get_conf('enableRssInCourse',1))
        {

            require_once get_path('incRepositorySys') . '/lib/rss.write.lib.php';
            build_rss( array(CLARO_CONTEXT_COURSE => claro_get_current_course_id()));
        }

        // ical update
        if (get_conf('enableICalInCourse',1) )
        {
            require_once get_path('incRepositorySys') . '/lib/ical.write.lib.php';
            buildICal( array(CLARO_CONTEXT_COURSE => claro_get_current_course_id()));
        }
    }

} // end id is_allowed to edit

/**
 *     DISPLAY SECTION
 *
 */

$noQUERY_STRING = true;

$eventList = agenda_get_item_list($currentContext,$orderDirection);

/**
 * Add event button
 */

$cmdList[]=  '<a class="claroCmd" href="' . $_SERVER['PHP_SELF'] . '?cmd=rqAdd">'
.            '<img src="' . get_conf('imgRepositoryWeb') . 'agenda.gif" alt="" />'
.            get_lang('Add an event')
.            '</a>'
;

/*
* remove all event button
*/
if ( count($eventList) > 0 )
{
    $cmdList[]=  '<a class= "claroCmd" href="' . $_SERVER['PHP_SELF'] . '?cmd=exDeleteAll" '
    .    ' onclick="javascript:if(!confirm(\'' . clean_str_for_javascript(get_lang('Clear up event list ?')) . '\')) return false;">'
    .    '<img src="' . get_path('imgRepositoryWeb') . 'delete.gif" alt="" />'
                                   . get_lang('Clear up event list')
    .    '</a>'
    ;
}
else
{
    $cmdList[]=  '<span class="claroCmdDisabled" >'
    .    '<img src="' . get_path('imgRepositoryWeb') . 'delete.gif" alt="" />'
    .    get_lang('Clear up event list')
    .    '</span>'
    ;
}


// Display header
include get_path('incRepositorySys') . '/claro_init_header.inc.php';

echo claro_html_tool_title(array('mainTitle' => $nameTools, 'subTitle' => $subTitle));

echo $dialogBox->render();


if ($display_form)
{
    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">'
    .    claro_form_relay_context()
    .    '<input type="hidden" name="claroFormId" value="' . uniqid('') . '" />'
    .    '<input type="hidden" name="cmd" value="' . $nextCommand . '" />'
    .    '<input type="hidden" name="id"  value="' . $editedEvent['id'] . '" />'
    .    '<table>' . "\n"
    .    '<tr valign="top">' . "\n"
    .    '<td align="right">' . get_lang('Date') . ' : '
    .    '</td>' . "\n"
    .    '<td>'
    .    claro_html_date_form('fday', 'fmonth', 'fyear', $editedEvent['date'], 'long' ) . ' '
    .    claro_html_time_form('fhour','fminute', $editedEvent['date']) . '&nbsp;'
    .    '<small>' . get_lang('(d/m/y hh:mm)') . '</small>'
    .    '</td>' . "\n"
    .    '</tr>' . "\n"
    .    '<tr>' . "\n"
    .    '<td align="right">'
    .    '<label for="lasting">' . get_lang('Lasting') . '</label> : '
    .    '</td>' . "\n"
    .    '<td>'
    .    '<input type="text" name="lasting" id="lasting" size="20" maxlength="20" value="' . htmlspecialchars($editedEvent['lastingAncient']) . '" />'
    .    '</td>' . "\n"
    .    '</tr>' . "\n"
    .    '<tr valign="top">' . "\n"
    .    '<td align="right">' . "\n"
    .    '<label for="title">' . "\n"
    .    get_lang('Title') . "\n"
    .    ' : </label>' . "\n"
    .    '</td>' . "\n"
    .    '<td>' . "\n"
    .    '<input size="80" type="text" name="title" id="title" value="'
    .    htmlspecialchars($editedEvent['title']). '" />' . "\n"
    .    '</td>' . "\n"
    .    '</tr>' . "\n"
    .    '<tr valign="top">' . "\n"
    .    '<td align="right">' . "\n"
    .    '<label for="content">' . "\n"
    .    get_lang('Detail')
    .    ' : ' . "\n"
    .    '</label>' . "\n"
    .    '</td>' . "\n"
    .    '<td>' . "\n"
    .    claro_html_textarea_editor('content', $editedEvent['content'], 12, 67 ) . "\n"
    .    '</td>' . "\n"
    .    '</tr>' . "\n"
    .    '<tr valign="top">' . "\n"
    .    '<td>&nbsp;</td>' . "\n"
    .    '<td>' . "\n"
    ;


    //---------------------
    // linker

    if( claro_is_jpspan_enabled() )
    {
        linker_set_local_crl( isset ($_REQUEST['id']) );
        echo linker_set_display();
    }
    else // popup mode
    {
        if(isset($_REQUEST['id'])) echo linker_set_display($_REQUEST['id']);
        else                       echo linker_set_display();
    }

    echo '</td></tr>' . "\n"
    .    '<tr valign="top"><td>&nbsp;</td><td>' . "\n"
    ;

    if( claro_is_jpspan_enabled() )
    {
        echo '<input type="submit" onclick="linker_confirm();"  class="claroButton" name="submitEvent" value="' . get_lang('Ok') . '" />' . "\n";
    }
    else // popup mode
    {
        echo '<input type="submit" class="claroButton" name="submitEvent" value="' . get_lang('Ok') . '" />' . "\n";
    }

    // linker
    //---------------------
    echo claro_html_button($_SERVER['PHP_SELF'], 'Cancel') . "\n"
    .    '</td>' . "\n"
    .    '</tr>' . "\n"
    .    '</table>' . "\n"
    .    '</form>' . "\n"
    ;
}

if ( $display_command ) echo '<p>' . claro_html_menu_horizontal($cmdList) . '</p>';

$monthBar     = '';

if ( count($eventList) < 1 )
{
    echo "\n" . '<br /><blockquote>' . get_lang('No event in the agenda') . '</blockquote>' . "\n";
}
else
{
    if ( $orderDirection == 'DESC' )
    {
        echo '<br /><a href="' . $_SERVER['PHP_SELF'] . '?order=asc" >' . get_lang('Oldest first') . '</a>' . "\n";
    }
    else
    {
        echo '<br /><a href="' . $_SERVER['PHP_SELF'] . '?order=desc" >' . get_lang('Newest first') . '</a>' . "\n";
    }

    echo "\n" . '<table class="claroTable" width="100%">' . "\n";
}

$nowBarAlreadyShowed = FALSE;

if (claro_is_user_authenticated()) $date = $claro_notifier->get_notification_date(claro_get_current_user_id());

foreach ( $eventList as $thisEvent )
{

    if (('HIDE' == $thisEvent['visibility'] && $is_allowedToEdit) || 'SHOW' == $thisEvent['visibility'])
    {
        //modify style if the event is recently added since last login
        if (claro_is_user_authenticated() && $claro_notifier->is_a_notified_ressource(claro_get_current_course_id(), $date, claro_get_current_user_id(), claro_get_current_group_id(), claro_get_current_tool_id(), $thisEvent['id']))
        {
            $cssItem = 'item hot';
        }
        else
        {
            $cssItem = 'item';
        }

        $cssInvisible = '';
        if ($thisEvent['visibility'] == 'HIDE')
        {
            $cssInvisible = ' invisible';
        }

        // TREAT "NOW" BAR CASE
        if ( ! $nowBarAlreadyShowed )
        if (( ( strtotime($thisEvent['day'] . ' ' . $thisEvent['hour'] ) > time() ) &&  'ASC' == $orderDirection )
        ||
        ( ( strtotime($thisEvent['day'] . ' ' . $thisEvent['hour'] ) < time() ) &&  'DESC' == $orderDirection )
        )
        {
            if ($monthBar != date('m',time()))
            {
                $monthBar = date('m',time());

                echo '<tr>' . "\n"
                .    '<th class="superHeader" colspan="2" valign="top">' . "\n"
                .    ucfirst(claro_html_localised_date('%B %Y', time()))
                .    '</th>' . "\n"
                .    '</tr>' . "\n"
                ;
            }


            // 'NOW' Bar

            echo '<tr>' . "\n"
            .    '<td>' . "\n"
            .    '<img src="' . get_path('imgRepositoryWeb') . 'pixel.gif" width="20" alt=" " />'
            .    '<span class="highlight">'
            .    '<a name="today">'
            .    '<i>'
            .    ucfirst(claro_html_localised_date( get_locale('dateFormatLong'))) . ' '
            .    ucfirst(strftime( get_locale('timeNoSecFormat')))
            .    ' -- '
            .    get_lang('Now')
            .    '</i>'
            .    '</a>'
            .    '</span>' . "\n"
            .    '</td>' . "\n"
            .    '</tr>' . "\n"
            ;

            $nowBarAlreadyShowed = true;
        }

        /*
        * Display the month bar when the current month
        * is different from the current month bar
        */

        if ( $monthBar != date( 'm', strtotime($thisEvent['day']) ) )
        {
            $monthBar = date('m', strtotime($thisEvent['day']));

            echo '<tr>' . "\n"
            .    '<th class="superHeader" valign="top">'
            .    ucfirst(claro_html_localised_date('%B %Y', strtotime( $thisEvent['day']) ))
            .    '</th>' . "\n"
            .    '</tr>' . "\n"
            ;
        }

        /*
        * Display the event date
        */

        echo '<tr class="headerX" valign="top">' . "\n"
        .    '<th>' . "\n"
        .    '<span class="'. $cssItem . $cssInvisible .'">' . "\n"
        .    '<a href="#form" name="event' . $thisEvent['id'] . '"></a>' . "\n"
        .    '<img src="' . get_path('imgRepositoryWeb') . 'agenda.gif" alt=" " />&nbsp;'
        .    ucfirst(claro_html_localised_date( get_locale('dateFormatLong'), strtotime($thisEvent['day']))) . ' '
        .    ucfirst( strftime( get_locale('timeNoSecFormat'), strtotime($thisEvent['hour']))) . ' '
        .    ( empty($thisEvent['lasting']) ? '' : get_lang('Lasting') . ' : ' . $thisEvent['lasting'] )
        .    '</span>';

        /*
        * Display the event content
        */

        echo '</th>' . "\n"
        .    '</tr>' . "\n"
        .    '<tr>' . "\n"
        .    '<td>' . "\n"
        .    '<div class="content ' . $cssInvisible . '">' . "\n"
        .    ( empty($thisEvent['title']  ) ? '' : '<p><strong>' . htmlspecialchars($thisEvent['title']) . '</strong></p>' . "\n" )
        .    ( empty($thisEvent['content']) ? '' :  claro_parse_user_text($thisEvent['content']) )
        .    '</div>' . "\n"
        ;

        echo linker_display_resource();
    }

    if ($is_allowedToEdit)
    {
        echo '<a href="' . $_SERVER['PHP_SELF'].'?cmd=rqEdit&amp;id=' . $thisEvent['id'] . '">'
        .    '<img src="' . get_path('imgRepositoryWeb') . 'edit.gif" border="O" alt="' . get_lang('Modify') . '" />'
        .    '</a> '
        .    '<a href="' . $_SERVER['PHP_SELF'] . '?cmd=exDelete&amp;id=' . $thisEvent['id'] . '" '
        .    ' onclick="javascript:if(!confirm(\'' . clean_str_for_javascript(get_lang('Are you sure to delete "%title" ?', array('%title' => $thisEvent['title']))) . '\')) return false;">'
        .    '<img src="' . get_path('imgRepositoryWeb') . 'delete.gif" border="0" alt="' . get_lang('Delete') . '" />'
        .    '</a>'
        ;

        //  Visibility
        if ('SHOW' == $thisEvent['visibility'])
        {
            echo '<a href="' . $_SERVER['PHP_SELF'] . '?cmd=mkHide&amp;id=' . $thisEvent['id'] . '">'
            .    '<img src="' . get_path('imgRepositoryWeb') . 'visible.gif" alt="' . get_lang('Invisible') . '" />'
            .    '</a>' . "\n";
        }
        else
        {
            echo '<a href="' . $_SERVER['PHP_SELF'] . '?cmd=mkShow&amp;id=' . $thisEvent['id'] . '">'
            .    '<img src="' . get_path('imgRepositoryWeb') . 'invisible.gif" alt="' . get_lang('Visible') . '" />'
            .    '</a>' . "\n"
            ;
        }
    }
    echo '</td>'."\n"
    .    '</tr>'."\n"
    ;

}   // end while

if ( count($eventList) > 0 ) echo '</table>';

include get_path('incRepositorySys') . '/claro_init_footer.inc.php';

?>
