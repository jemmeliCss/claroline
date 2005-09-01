<?php // $Id$

	// vim: expandtab sw=4 ts=4 sts=4:
	
	/**
     * CLAROLINE
     *
     * @version 1.7 $Revision$
     *
     * @copyright 2001-2005 Universite catholique de Louvain (UCL)
     *
     * @license GENERAL PUBLIC LICENSE (GPL)
     * This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
     * as published by the FREE SOFTWARE FOUNDATION. The GPL is available
     * through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
     *
     * @author Frederic Minne <zefredz@gmail.com>
     *
     * @package Wiki
     */
     
    require_once "../inc/claro_init_global.inc.php";
     
	$referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
	
	$requestedFile = isset( $_REQUEST['requestedFile'] ) ? $_REQUEST['requestedFile'] : 'unknown';
	
	// $noBanner = true;
	$toolName = "File not found";
	
	require_once $includePath . "/claro_init_header.inc.php";
	
	echo '<p style="text-align: center;padding-top:1em; padding-left: 1em;">'
		. '<strong>File ' . $requestedFile . ' not found !</strong>'
		. '</p>'
		;
		
	echo '<p style="text-align: center;padding-top:1em; padding-left: 1em;" class="">'
		. 'The file you have requested has not been found on this server. '
		. 'Maybe it has been moved or deleted.'
		. '</p>'
		;
	
	echo '<p style="padding-left: 2em;">'
		. 'Back to :'
		. '<ul>'
		. '<li><a href="'.$clarolineRepositoryWeb.'document/document.php">'.$langDocument.'</a></li>'
		. ( (! empty($referer)) ? '<li><a href="'.$referer.'">Previous page</a></li>' : '' )
		. '</ul>'
		.'</p>'
		;
	
	require_once $includePath . "/claro_init_footer.inc.php";
?>