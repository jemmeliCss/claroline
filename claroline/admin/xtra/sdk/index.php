<?php // $Id$
//----------------------------------------------------------------------
// CLAROLINE
//----------------------------------------------------------------------
// Copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

$cidReset=true;
$gidReset=true;

require '../../../inc/claro_init_global.inc.php';
include($includePath."/lib/debug.lib.inc.php");
require_once $includePath . '/lib/admin.lib.inc.php';

$nameTools = get_lang('SDK');

// SECURITY CHECK

if (!$is_platformAdmin) claro_disp_auth_form();

// DISPLAY

// Deal with interbredcrumps  and title variable
$interbredcrump[]  = array ('url' => $rootAdminWeb, 'name' => get_lang('Administration'));

include $includePath . '/claro_init_header.inc.php';

echo claro_html::tool_title($nameTools);
?>

<p><img src="<?php echo 'lang/language.png'?>" style="vertical-align: middle;" alt="" /> <a href="translation_index.php"><?php echo get_lang('Translation Tools')?></a></p>

<?php
include $includePath . '/claro_init_footer.inc.php';
?>
