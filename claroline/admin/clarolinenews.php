<?php // $Id$
//----------------------------------------------------------------------
// CLAROLINE
//----------------------------------------------------------------------
// Copyright (c) 2001-2004 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------
$cidReset=true;$gidReset=true;
require '../inc/claro_init_global.inc.php';

if(file_exists($includePath.'/currentVersion.inc.php')) include ($includePath.'/currentVersion.inc.php');
include($includePath.'/lib/admin.lib.inc.php');
// rss reader library
require($includePath.'/lib/lastRSS/lastRSS.php');

//SECURITY CHECK
$is_allowedToAdmin     = $is_platformAdmin;
if (!$is_allowedToAdmin) treatNotAuthorized();

$nameTools = $langClarolineNetNews;

$interbredcrump[] = array ("url"=>$rootAdminWeb, "name"=> $langAdministration);
$noQUERY_STRING   = TRUE;


//----------------------------------
// prepare rss reader
//----------------------------------
// url where the reader will have to get the rss feed
$urlNewsClaroline = 'http://www.claroline.net/rss.php';

$rss = new lastRSS;

// where the cached file will be written
$rss->cache_dir = '.';
// how long without refresh the cache
$rss->cache_time = 1200; 

//----------------------------------
// DISPLAY
//----------------------------------
// title variable
include($includePath."/claro_init_header.inc.php");	
claro_disp_tool_title($nameTools);

if ($rs = $rss->get($urlNewsClaroline))
{
	foreach ($rs['items'] as $item) 
	{
		$href = $item['link'];
	    $title = $item['title'];
		$summary = $rss->unhtmlentities($item['description']);
		$date = $item['pubDate'];

	    echo '<div class="claroNews">'."\n"
	        .'<h4>'."\n"
	        .'<a href="'.$href.'">'.$title.'</a>'."\n"
	        .'</h4>'."\n"
	        .'<span class="claroNewsDate">('.$date.')</span>'."\n"
	        .'<br />'."\n"
	        .'<span class="claroNewsSummary">'.$summary.'</span>'."\n"
	        .'</div>'."\n"
			.'<hr />'."\n\n";
	}
}
else
{	
	claro_disp_message_box($langErrorCannotReadRSSFile);
}

include($includePath."/claro_init_footer.inc.php");
?>