<?php // $Id$
/**
 * CLAROLINE 
 *
 * - For a Student -> View angeda Content
 * - For a Prof    -> - View agenda Content
 *         - Update/delete existing entries
 *         - Add entries
 *         - generate an "announce" entries about an entries
 *
 * @version 1.7 $Revision$
 *
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @package CLCAL
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Christophe Gesch� <moosh@claroline.net>
 */

/**
 * get list of all agenda item in the given or current course
 *
 * @param string $order  'ASC' || 'DESC' : ordering of the list.
 * @param string $course_id current :sysCode of the course (leaveblank for current course) 
 * @author Christophe Gesch� <moosh@claroline.net>
 * @return array of array(`id`, `titre`, `contenu`, `day`, `hour`, `lasting`, `visibility`)
 * @since  1.7
 */

function agenda_get_item_list($order='DESC', $course_id=NULL)
{
    $tbl_c_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_calendar_event = $tbl_c_names['calendar_event'];
    $sql = "SELECT `id`, `titre` `title`, `contenu` `content`, `day`, `hour`, `lasting`, `visibility`
        FROM `" . $tbl_calendar_event . "`
        ORDER BY `day` " . ($order=='DESC'?'DESC':'ASC') . " 
        , `hour` " . ($order=='DESC'?'DESC':'ASC');

    return claro_sql_query_fetch_all($sql);
}

/**
 * Delete an event in the given or current course
 *
 * @param integer $event_id id the requested event
 * @param string $course_id current :sysCode of the course (leaveblank for current course) 
 * @author Christophe Gesch� <moosh@claroline.net>
 * @return result of deletion query
 * @since  1.7
 */
function agenda_delete_item($event_id, $course_id=NULL)
{
    $tbl_c_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_calendar_event = $tbl_c_names['calendar_event'];

    $sql = "DELETE FROM  `" . $tbl_calendar_event . "`
            WHERE id='" . (int) $event_id . "'";
    return claro_sql_query($sql);
}


/**
 * Delete an event in the given or current course
 *
 * @param integer $event_id id the requested event
 * @param string $course_id current :sysCode of the course (leaveblank for current course) 
 * @author Christophe Gesch� <moosh@claroline.net>
 * @return result of deletion query
 * @since  1.7
 */
function agenda_delete_all_items($course_id=NULL)
{
    $tbl_c_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_calendar_event = $tbl_c_names['calendar_event'];

    $sql = "DELETE FROM  `" . $tbl_calendar_event . "`";
    return claro_sql_query($sql);
}

/**
 * add an new event in the given or current course
 *
 * @param string   $title   title of the new item        
 * @param string   $content content of the new item
 * @param date     $time    publication dat of the item def:now
 * @param string   $course_id sysCode of the course (leaveblank for current course) 
 * @author Christophe Gesch� <moosh@claroline.net>
 * @return id of the new item
 * @since  1.7
 */

function agenda_add_item($title='',$content='', $day=NULL, $hour=NULL, $lasting='', $visibility='SHOW', $course_id=NULL)
{
    $tbl_c_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_calendar_event = $tbl_c_names['calendar_event'];

    if (is_null($day)) $day = date('Y-m-d');
    if (is_null($hour)) $hour =  date('H:i:s');
    $sql = "INSERT INTO `" . $tbl_calendar_event . "`
        SET   titre   = '" . addslashes(trim($title)) . "',
              contenu = '" . addslashes(trim($content)) . "',
              day     = '" . $day . "',
              hour    = '" . $hour . "',
              visibility = '" . ($visibility=='HIDE'?'HIDE':'SHOW') . "',
              lasting = '" . addslashes(trim($lasting)) . "'";

    return claro_sql_query_insert_id($sql);
}


/**
 * Update an announcement in the given or current course
 *
 * @param string     $title     title of the new item        
 * @param string     $content   content of the new item
 * @param date       $time      publication dat of the item def:now
 * @param string     $course_id sysCode of the course (leaveblank for current course) 
 * @author Christophe Gesch� <moosh@claroline.net>
 * @return handler of query
 * @since  1.7
 */

function agenda_update_item($event_id, $title=NULL,$content=NULL, $day=NULL, $hour=NULL, $lasting= NULL, $visibility=NULL, $course_id=NULL)
{
    $tbl_c_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_calendar_event = $tbl_c_names['calendar_event'];

    $sqlSet = array();
    if(!is_null($title))      $sqlSet[] = " `titre` = '" . addslashes(trim($title)) . "' ";
    if(!is_null($content))    $sqlSet[] = " `contenu` = '" . addslashes(trim($content)) . "' ";
    if(!is_null($day))        $sqlSet[] = " `day` = '" . addslashes(trim($day)) . "' ";
    if(!is_null($hour))       $sqlSet[] = " `hour` = '" . addslashes(trim($hour)) . "' ";
    if(!is_null($lasting))    $sqlSet[] = " `lasting` = '" . addslashes(trim($lasting)) . "' ";
    if(!is_null($visibility)) $sqlSet[] = " `visibility` = '" . ($visibility=='HIDE'?'HIDE':'SHOW') . "' ";

    if (count($sqlSet)>0)
    {
        $sql = "UPDATE `" . $tbl_calendar_event . "`
                SET " . implode(', ',$sqlSet) ."
                WHERE `id` = '" . (int) $event_id ."'";        

        return claro_sql_query($sql);
    }
    else return NULL;
}


/**
 * return data for the event  of the given id of the given or current course
 *
 * @param integer $event_id id the requested event
 * @param string  $course_id sysCode of the course (leaveblank for current course) 
 * @author Christophe Gesch� <moosh@claroline.net>
 * @return array(`id`, `title`, `content`, `dayAncient`, `hourAncient`, `lastingAncient`) of the event
 * @since  1.7
 */

function agenda_get_item($event_id, $course_id=NULL)
{
    $tbl_c_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_calendar_event = $tbl_c_names['calendar_event'];
    $sql = "SELECT `id`,
                   `titre` `title`, 
                   `contenu` `content`,
                   `day` as `dayAncient`,
                   `hour` as `hourAncient`,
                   `lasting` as `lastingAncient`
            FROM `" . $tbl_calendar_event . "` 

            WHERE `id` = '" . (int) $event_id . "'";

    $event =  claro_sql_query_fetch_all($sql);
    return  $event[0];
}

/**
 * return data for the event  of the given id of the given or current course
 *
 * @param integer $event_id id the requested event
 * @param string  $visibility 'SHOW' || 'HIDE'  ordering of the list.
 * @param string  $course_id  sysCode of the course (leaveblank for current course) 
 * @author Christophe Gesch� <moosh@claroline.net>
 * @return result handler
 * @since  1.7
 */

function agenda_set_item_visibility($event_id, $visibility, $course_id=NULL)
{
    $tbl_c_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_calendar_event = $tbl_c_names['calendar_event'];

    $sql = "UPDATE `" . $tbl_calendar_event . "`
            SET   visibility = '" . ($visibility=='HIDE'?'HIDE':'SHOW') . "'
                  WHERE id =  '" . (int) $event_id . "'";
    return  claro_sql_query($sql);
}


//////////////////////////////////////////////////////////////////////////////

function get_agenda_items($userCourseList, $month, $year)
{
    global $courseTablePrefix, $dbGlu;

    $items = array();

    // get agenda-items for every course

    foreach( $userCourseList as $thisCourse)
    {
        $tbl_c_names = claro_sql_get_course_tbl($courseTablePrefix. $thisCourse['db'].$dbGlu);

        //$tbl_c_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($thisCourse['code']));
        $courseAgendaTable          = $tbl_c_names['calendar_event'];

        $sql = "SELECT `id`, `titre` AS `title`, `contenu` AS content,
                       `day`, `hour`, `lasting`
                FROM `" . $courseAgendaTable . "`
                WHERE MONTH(`day`) = " . (int)$month . "
                  AND YEAR(`day`)  = " . (int)$year  . "
                  AND visibility   = 'SHOW'";
        
        $courseEventList = claro_sql_query_fetch_all($sql);

        if ( is_array($courseEventList) )

        foreach($courseEventList as $thisEvent )
        {
            $eventLine = trim(strip_tags($thisEvent['title']));

            if ( $eventLine == '' )
            {
                $eventContent = trim(strip_tags($thisEvent['content']));
                $eventLine    = substr($eventContent, 0, 60) . (strlen($eventContent) > 60 ? ' (...)' : '');
            }

            $eventDate = explode('-', $thisEvent['day']);
            $day       = intval($eventDate[2]);
            $eventTime = explode(':', $thisEvent['hour']);
            $time      = $eventTime[0] . ':' . $eventTime[1];
            $url       = 'agenda.php?cidReq=' . $thisCourse['sysCode'];

            if( ! isset($items[$day][$thisEvent['hour']]) ) 
            {
                $items[$day][$thisEvent['hour']] = '';
            }

            $items [ $day ] [ $thisEvent['hour'] ] .=
            '<br><small><i>' . $time . ' : </small><br></i> '
            . $eventLine
            . ' - <small><a href="' . $url . '">' . $thisCourse['officialCode'] . '</a></small>' . "\n"
            ;
        } // end foreach courseEventList
    }

    // sorting by hour for every day
    $agendaItemList = array();

    while ( list($agendaday, $tmpitems) = each($items))
    {
        sort($tmpitems);

        while ( list(,$val) = each($tmpitems))
        {
            if( !isset($agendaItemList[$agendaday]) ) $agendaItemList[$agendaday] = '';
            $agendaItemList[$agendaday] .= $val;
        }
    }

    return $agendaItemList;
}

function claro_disp_monthly_calendar($agendaItemList, $month, $year, $weekdaynames, $monthName )
{

    //Handle leap year
    $numberofdays = array(0,31,28,31,30,31,30,31,31,30,31,30,31);

    if ( ($year%400 == 0) || ( $year%4 == 0 && $year%100 != 0 ) )
    {
        $numberofdays[2] = 29;
    }

    //Get the first day of the month
    $dayone = getdate(mktime(0,0,0,$month,1,$year));

    //Start the week on monday
    $startdayofweek = $dayone['wday']<> 0 ? ($dayone['wday']-1) : 6;

    $backwardsURL = $_SERVER['PHP_SELF']
    .'?month='.($month==1 ? 12 : $month-1)
    .'&amp;year='.($month==1 ? $year-1 : $year);

    $forewardsURL = $_SERVER['PHP_SELF']
    .'?month='.($month==12 ? 1 : $month+1)
    .'&amp;year='.($month==12 ? $year+1 : $year);

    echo '<table class="claroTable" width="100%">' . "\n"
    .    '<tr class="superHeader">' . "\n"
    .    '<th width="13%">'
    .    '<center>' . "\n"
    .    '<a href="' . $backwardsURL . '">&lt;&lt;</a>'
    .    '</center>' . "\n"
    .    '</th>' . "\n"
    .    '<th width="65%" colspan="5">'
    .    '<center>'
    .    $monthName . ' ' . $year
    .    '</center>'
    .    '</th>' . "\n"
    .    '<th width="13%"><center>'
    .    '<a href="' . $forewardsURL . '">&gt;&gt;</a></center>'
    .    '</th>' . "\n"
    .    '</tr>' . "\n"
    .    '<tr class="headerX">' ."\n"
    ;

    for ( $iterator = 1; $iterator < 8; $iterator++)
    {
        echo  '<th width="13%">' . $weekdaynames[$iterator%7] . '</th>' . "\n";
    }

    echo '</tr>' . "\n\n";

    $curday = -1;

    $today = getdate();

    while ($curday <= $numberofdays[$month])
    {
        echo '<tr>' . "\n";

        for ($iterator = 0; $iterator < 7 ; $iterator++)
        {
            if ( ($curday == -1) && ($iterator == $startdayofweek) )
            {
                $curday = 1;
            }

            if ( ($curday > 0) && ($curday <= $numberofdays[$month]) )
            {
                if (   ($curday == $today['mday'])
                && ($year   == $today['year'])
                && ($month  == $today['mon' ]) )
                {
                    $weekdayType = 'highlight'; // today
                }
                elseif ( $iterator < 5 )
                {
                    $weekdayType = 'workingWeek';
                }
                else
                {
                    $weekdayType = 'weekEnd';
                }

                $dayheader = '<small>' . $curday . '</small>';


                echo '<td height="40" width="12%" valign="top" class="' . $weekdayType . '">'
                .    $dayheader
                ;
                
                if( isset($agendaItemList[$curday]) ) echo $agendaItemList[$curday];
                
                echo '</td>' . "\n";

                $curday++;
            }
            else
            {
                echo '<td width="12%">&nbsp;</td>' . "\n";
            }
        }
        echo '</tr>' . "\n\n";
    }
    echo  '</table>';
}

?>
