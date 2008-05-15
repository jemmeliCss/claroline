<?php // $Id: userLog.php 9858 2008-03-11 07:49:45Z gregk84 $
/**
 * CLAROLINE
 *
 * @version 1.9 $Revision: 9858 $
 *
 * @copyright (c) 2001-2007 Universite catholique de Louvain (UCL)
 *
 * @author Sebastien Piraux <piraux_seb@hotmail.com>
 *
 * @package CLTRACK
 */

/*
 * Kernel
 */
require_once dirname( __FILE__ ) . '../../inc/claro_init_global.inc.php';



/*
 * Permissions
 */
if( ! get_conf('is_trackingEnabled') ) claro_die(get_lang('Tracking has been disabled by system administrator.')); 
if( ! claro_is_in_a_course() || ! claro_is_course_allowed() ) claro_disp_auth_form(true);
if( ! claro_is_course_manager() ) claro_die(get_lang('Not allowed'));

/*
 * Libraries
 */

require_once dirname( __FILE__ ) . '/lib/trackingRenderer.class.php';
require_once dirname( __FILE__ ) . '/lib/trackingRendererRegistry.class.php';
/*
 * Init some other vars
 */
$tbl_mdb_names = claro_sql_get_main_tbl();
$tbl_rel_course_user         = $tbl_mdb_names['rel_course_user'  ];

$tbl_cdb_names = claro_sql_get_course_tbl();
$tbl_course_tracking_event = $tbl_cdb_names['tracking_event'];


/*
 * Output
 */
$cssLoader = CssLoader::getInstance();
$cssLoader->load( 'tracking', 'screen');

// initialize output
$claroline->setDisplayType( CL_PAGE );

$nameTools = get_lang('Statistics');

$html = '';

$html .= claro_html_tool_title(
                array(
                    'mainTitle' => $nameTools,
                    'subTitle'  => get_lang('Statistics of course : %courseCode', array('%courseCode' => claro_get_current_course_data('officialCode')))
                )
            );

            
/*
 * Prepare rendering : 
 * Load and loop through available tracking renderers
 * Order of renderers blocks is arranged using "first found, first display" in the registry
 * Modify the registry to change the load order if required
 */
// get all renderers by using registry
$trackingRendererRegistry = TrackingRendererRegistry::getInstance();

// here we need course tracking renderers
$courseTrackingRendererList = $trackingRendererRegistry->getCourseRendererList();

foreach( $courseTrackingRendererList as $ctr )
{
    $renderer = new $ctr( claro_get_current_course_id() );
    $html .= $renderer->render();
}


/*
 * Output rendering
 */
$claroline->display->body->setContent($html);

echo $claroline->display->render();

?>