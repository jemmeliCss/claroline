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

require '../../../inc/claro_init_global.inc.php';
include($includePath."/lib/debug.lib.inc.php");

$nameTools = $langSDK;

// SECURITY CHECK

if (!$is_platformAdmin) treatNotAuthorized();

// DISPLAY

// Deal with interbredcrumps  and title variable
$interbredcrump[]  = array ("url"=>$rootAdminWeb, "name"=> $langAdministration);

include($includePath."/claro_init_header.inc.php");

claro_disp_tool_title($nameTools);
?>

<p><img src="<?php echo 'lang/language.png'?>" style="vertical-align: middle;" alt="" /> <a href="translation_index.php"><?php echo $langTranslationTools?></a></p>

<?php
include($includePath."/claro_init_footer.inc.php");
?>
