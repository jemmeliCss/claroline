<?php // $Id$
if ( count( get_included_files() ) == 1 ) die( '---' );

/******************************************************************************
 * CLAROLINE
 ******************************************************************************
 * This module displays a cross course digest for the current authenticated user
 *
 * @version 1.8 $Revision$
 * @copyright (c) 2001-2007 Universite catholique de Louvain (UCL)
 * @license (GPL) GENERAL PUBLIC LICENSE - http://www.gnu.org/copyleft/gpl.html
 * @package CLCALDIGEST
 *
 * @todo add rss reader
 * @change this in a applet.
 *
 */
if ( ! claro_is_user_authenticated() ) claro_disp_auth_form();

include_once get_path('incRepositorySys') . '/lib/pear/Lite.php';
include_once claro_get_conf_repository() . 'CLKCACHE.conf.php';

// Cache_lite setting & init
$cache_options = array(
'cacheDir' => get_path('rootSys') . 'tmp/cache/CLCALdigest/',
'lifeTime' => get_conf('cache_lifeTime', 600),
'automaticCleaningFactor' =>get_conf('cache_automaticCleaningFactor', 50),
);
if (get_conf('CLARO_DEBUG_MODE',false) ) $cache_options['pearErrorMode'] = CACHE_LITE_ERROR_DIE;
if (get_conf('CLARO_DEBUG_MODE',false) ) $cache_options['lifeTime'] = 120;
if (! file_exists($cache_options['cacheDir']) )
{
    include_once get_path('incRepositorySys') . '/lib/fileManage.lib.php';
    claro_mkdir($cache_options['cacheDir'],CLARO_FILE_PERMISSIONS,true);
}
$Cache_LiteCLCALDIGEST = new Cache_Lite($cache_options);

$courseDigestList = array('courseSysCode'      => array(),
                          'courseOfficialCode' => array(),
                          'toolLabel'          => array(),
                          'date'               => array(),
                          'content'            => array());

if (false === $htmlCLCALDIGEST = $Cache_LiteCLCALDIGEST->get('CALDIGEST' . claro_get_current_user_id()))
{
    $personnalCourseList = get_user_course_list(claro_get_current_user_id());

    foreach($personnalCourseList as $thisCourse)
    {
        /*
        * ANNOUNCEMENTS : get announcements of this course since last user loggin
        */

        $tableAnn = get_conf('courseTablePrefix') . $thisCourse['db'] . get_conf('dbGlu') . 'announcement';

        $sql = "SELECT '" . addslashes($thisCourse['sysCode']     ) ."' AS `courseSysCode`,
                   '" . addslashes($thisCourse['officialCode']) ."' AS `courseOfficialCode`,
                   'CLANN'                                          AS `toolLabel`,
                   CONCAT(`temps`, ' ', '00:00:00')                 AS `date`,
                   CONCAT(`title`,' - ',`contenu`)                  AS `content`

            FROM `" . $tableAnn . "`
            WHERE CONCAT(`title`, `contenu`) != ''
              AND DATE_FORMAT( `temps`, '%Y %m %d') >= '".date('Y m d', $_user['lastLogin'])."'
              AND visibility = 'SHOW'
            ORDER BY `date` DESC
            LIMIT 1";

        $resultList = claro_sql_query_fetch_all_cols($sql);

        foreach($resultList as $colName => $colValue)
        {
            if (count($colValue) == 0) break;
            $courseDigestList[$colName] = array_merge($courseDigestList[$colName], $colValue);
        }

        /*
        * AGENDA : get the next agenda entries of this course from now
        */

        $tableCal = get_conf('courseTablePrefix') . $thisCourse['db'] . get_conf('dbGlu') . 'calendar_event';

        $sql = "SELECT '". addslashes($thisCourse['sysCode']     ) ."' AS `courseSysCode`,
                   '". addslashes($thisCourse['officialCode']) ."' AS `courseOfficialCode`,
                   'CLCAL' AS `toolLabel`,
            CONCAT(`day`, ' ',`hour`) AS `date`,
            CONCAT(`titre`,' - ',`contenu`) AS `content`
            FROM `" . $tableCal . "`
            WHERE CONCAT(`day`, ' ',`hour`) >= CURDATE()
              AND CONCAT(`titre`, `contenu`) != ''
              AND visibility = 'SHOW'
            ORDER BY `date`
            LIMIT 1";

        $resultList = claro_sql_query_fetch_all_cols($sql);

        foreach($resultList as $colName => $colValue)
        {
            if (count($colValue) == 0) break;
            $courseDigestList[$colName] = array_merge($courseDigestList[$colName], $colValue);
        }

    } // end foreach($personnalCourseList as $thisCourse)



    /*
    * Sort all these digest by date
    */

    array_multisort( $courseDigestList['toolLabel'         ],
    $courseDigestList['date'              ],
    $courseDigestList['courseOfficialCode'],
    $courseDigestList['courseSysCode'     ],
    $courseDigestList['content'           ] );

    /******************************************************************************
    DISPLAY
    ******************************************************************************/

    $title = '';

    for( $i=0, $itemCount = count($courseDigestList['toolLabel']); $i < $itemCount; $i++)
    {
        switch ($courseDigestList['toolLabel'][$i])
        {
            case 'CLANN':
                $itemIcon = 'announcement.gif';
                $url = get_module_url('CLANN') . '/announcements.php?cidReq='
                . $courseDigestList['courseSysCode'][$i];
                $name = get_lang('Latest announcements');
                break;

            case 'CLCAL':
                $itemIcon = 'agenda.gif';
                $url = get_module_url('CLCAL') . '/agenda.php?cidReq='
                . $courseDigestList['courseSysCode'][$i];
                $name = get_lang('Agenda next events');
                break;
        }

        if ($title != $name)
        {
            $title = $name;
            $htmlCLCALDIGEST .= '<h4>' . $title . '</h4>' . "\n";
        }

        $courseDigestList['content'][$i] = preg_replace('/<br( \/)?>/', ' ', $courseDigestList['content'][$i]);
        $courseDigestList['content'][$i] = strip_tags($courseDigestList['content'][$i]);
        $courseDigestList['content'][$i] = substr($courseDigestList['content'][$i],0, get_conf('max_char_from_content') );

        $htmlCLCALDIGEST .= '<p>' . "\n"
        .    '<small>'
        .    '<a href="' . $url . '">'
        .    '<img src="' . get_path('imgRepositoryWeb') . $itemIcon . '" alt="" />'
        .    '</a>' . "\n"

        .    claro_html_localised_date( get_locale('dateFormatLong'),
        strtotime($courseDigestList['date'][$i]) )
        .    '<br />' . "\n"
        .    '<a href="' . $url . '">'
        .    $courseDigestList['courseOfficialCode'][$i]
        .    '</a> : ' . "\n"
        .    '<small>'  . "\n"
        .    $courseDigestList['content'][$i]  . "\n"
        .    '</small>' . "\n"
        .    '</small>' . "\n"
        .    '</p>' . "\n"
        ;
    } // end for( $i=0, ... $i < $itemCount; $i++)

    $Cache_LiteCLCALDIGEST->save($htmlCLCALDIGEST,'CALDIGEST'.claro_get_current_user_id());
}

unset ($Cache_LiteCLCALDIGEST);
echo $htmlCLCALDIGEST;

?>