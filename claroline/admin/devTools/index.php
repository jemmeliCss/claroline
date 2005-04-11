<?php // $Id$
/**
 * @version CLAROLINE 1.6
 *
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license GENERAL PUBLIC LICENSE (GPL)
 *
 * @author Christophe Gesch� <moosh@claroline.net>
 * 
 * This is the index page of sdk tools
*/

require '../../inc/claro_init_global.inc.php';
if(file_exists($includePath.'/currentVersion.inc.php')) include ($includePath.'/currentVersion.inc.php');
$langFillToolCourses = 'Fill tools of a course (lorem ipsum filler)';

$is_allowedToUseSDK = $is_platformAdmin;

if (! $is_allowedToUseSDK) claro_disp_auth_form(); 

$nameTools = $langDevTools;

$interbredcrump[]= array ('url'=>'../index.php', 'name'=> $langAdmin);

include($includePath.'/claro_init_header.inc.php');

claro_disp_tool_title(
	array(
	'mainTitle'=>$nameTools,
	'subTitle'=>$siteName.' - '.$clarolineVersion
	)
	);

?>
<h4><?php echo $langTranslations ?></h4>
<ul>
    <li><a href="../xtra/sdk/translation_index.php"><?php echo $langTranslations ?></a></li>
</ul>
<h4><?php echo $langFilling ?></h4>
<ul>
 <li><a href="./fillUser.php"><?php echo $langFillUsers ?></a></li>
 <li><a href="./fillCourses.php"><?php echo $langFillCourses ?></a>(and  subscribe some existing students)</li>
 <li><a href="./fillTree.php"><?php echo $langFillTree ?></a></li>
 <li><a href="./fillToolCourses.php"><?php echo $langFillToolCourses ?></a></li>
</ul>

<?php
include($includePath."/claro_init_footer.inc.php");
?>