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
include($includePath."/lib/admin.lib.inc.php");

$nameTools = $langTranslationTools;
$urlSDK = $rootAdminWeb . 'xtra/sdk/'; 

// SECURITY CHECK

if (!$is_platformAdmin) treatNotAuthorized();

// DISPLAY

// Deal with interbredcrumps  and title variable

$interbredcrump[] = array ("url"=>$rootAdminWeb, "name"=> $langAdministration);
$interbredcrump[] = array ("url"=>$urlSDK, "name"=> $langSDK);

include($includePath."/claro_init_header.inc.php");

claro_disp_tool_title('<img src="lang/language.png" style="vertical-align: middle;" alt="" /> '.$nameTools);

?>

<h4><?php echo $langExtractLangVariable?></h4>
<ul>
<li><a href="lang/extract_var_from_lang_file.php"><?php echo $langExtractFromLangFile?></a></li>
<li><a href="lang/extract_var_from_script_file.php"><?php echo $langExtractFromScriptFile?></a></li>
</ul>

<h4><?php echo $langBuildLangFile?></h4>
<ul>
<li><a href="lang/build_devel_lang_file.php"><?php echo $langBuildCompleteLangFile?></a></li>
<li><a href="lang/build_prod_lang_file.php"><?php echo $langBuildProductionLangFile?></a></li>
<li><a href="lang/build_missing_lang_file.php"><?php echo $langBuildMissingLangFile?></a></li>
</ul>

<h4><?php echo $langFindDoubledVariable?></h4>
<ul>
<li><a href="lang/display_var_diff.php"><?php echo $langFindVarWithSameNameAndDifferentContent?></a></li>
<li><a href="lang/display_content_diff.php"><?php echo $langFindVarWithSameContentAndDifferentName?></a></li>
</ul>

<h4><?php echo $langTranslationStatistics?></h4>
<ul>
<li><a href="lang/progression_translation.php"><?php echo $langTranslationStatistics?></a></li>
</ul>

<?php
include($includePath."/claro_init_footer.inc.php");
?>
