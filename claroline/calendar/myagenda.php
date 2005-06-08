<?php // $Id$
/**
 * CLAROLINE 
 *
 *    This file generates a general agenda of all items of the courses
 *    the user is registered for.
 *
 *    Based on the master-calendar code of Eric Remy (6 Oct 2003)
 *    adapted by Toon Van Hoecke (Dec 2003) and Hugues Peeters (March 2004)
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
 * @author Eric Remy <eremy@rmwc.edu>
 * @author Toon Van Hoecke <Toon.VanHoecke@UGent.be>
 * @author Hugues Peeters <peeter@ipm.ucl.ac.be>
 *
 */

$cidReset = TRUE;

require '../inc/claro_init_global.inc.php';
require_once($includePath . '/lib/agenda.lib.php');

$nameTools = $langMyAgenda;

$tbl_mdb_names       = claro_sql_get_main_tbl();

$tbl_course          = $tbl_mdb_names['course'];
$tbl_rel_course_user = $tbl_mdb_names['rel_course_user'];

if ( isset( $_uid ) )
{
    $sql = "SELECT cours.code sysCode, cours.fake_code officialCode,
                   cours.intitule title, cours.titulaires t, 
                   cours.dbName db, cours.directory dir
            FROM    `" . $tbl_course . "`     cours,
                    `" . $tbl_rel_course_user . "` cours_user
            WHERE cours.code         = cours_user.code_cours
            AND   cours_user.user_id = '" . (int) $_uid . "'";

    $userCourseList = claro_sql_query_fetch_all($sql);

    $today = getdate();

    if( isset($_REQUEST['year']) )
    {
        $year  = $_REQUEST['year' ];
    }
    else
    {
        $year = $today['year'];
    }

    if( isset($_REQUEST['month']) )
    {
        $month = $_REQUEST['month'];
    }
    else
    {
        $month = $today['mon' ];
    }

    $agendaItemList = get_agenda_items($userCourseList, $month, $year);

    $monthName   = $langMonthNames['long'][$month-1];
    $diplay_monthly_calendar = TRUE;
}
else
{
    $diplay_monthly_calendar = FALSE;
}

include($includePath . '/claro_init_header.inc.php');
claro_disp_tool_title($nameTools);

if ($diplay_monthly_calendar)
{
    claro_disp_monthly_calendar($agendaItemList, $month, $year, $langDay_of_weekNames['long'], $monthName, $langToday);
}

include($includePath . '/claro_init_footer.inc.php');

?>