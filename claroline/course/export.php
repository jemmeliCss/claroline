<?php // $Id$

/**
 * CLAROLINE
 *
 * @version 1.8 $Revision$
 *
 * @copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @package CLEXPORT
 *
 * @author Yannick Wautelet <yannick_wautelet@hotmail.com>
 * @author Claro Team <cvs@claroline.net>
 */

//$tlabelReq = 'CLCRS';
$dialogBox = '';
require '../inc/claro_init_global.inc.php';

include_once($includePath . '/lib/export.lib.php');

// filter incoming data
$acceptCmd = array('doExport');
$cmd= (isset($_REQUEST['cmd']) && in_array($_REQUEST['cmd'],$acceptCmd)) ? $_REQUEST['cmd'] : '';

// command
switch($cmd)
{
	case 'doExport' :
	{
	 	//essai("es1","essai");
	
		if (export_all_data_course_in_file())
		{
			$dialogBox = 'Export r�ussi';
		}
		else
		{		
			$dialogBox = 'Export �chou� : <br>';	
			if(claro_failure::get_last_failure() == "can't delete dir")
			{
				$dialogBox = "impossible d'�craser le r�pertoire temporaire";
			}			
			if(claro_failure::get_last_failure() == "can't_write_xml_file")
			{
				$dialogBox = "impossible d'�crire le fichier xml d'export";
			}

			if(claro_failure::get_last_failure() == "dir doesn't exist" || claro_failure::get_last_failure() == "is not a directory")
			{
				$dialogBox = "impossible de cr�er le fichier zip";
			}		
			if(claro_failure :: get_last_failure() == "invalid course id")
			{
				$dialogBox = "Course_id invalide";
			}
			else $dialogBox = claro_failure::get_last_failure();	
		}
		;
	} break;
}
// task


// prepare display
$nameTools = 'Export course';

// Display 
include $includePath . '/claro_init_header.inc.php';

echo claro_html_tool_title($nameTools);

if ( !empty($dialogBox) ) echo claro_html_message_box($dialogBox);

echo '<a href="' . $_SERVER['PHP_SELF'] . '?cmd=doExport">'. 'Export this course' . '</a>';

include $includePath . '/claro_init_footer.inc.php';

?>
