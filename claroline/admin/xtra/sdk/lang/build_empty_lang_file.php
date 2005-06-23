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

require '../../../../inc/claro_init_global.inc.php';

// SECURITY CHECK

if (!$is_platformAdmin) claro_disp_auth_form();

/*
 * This script build the lang files with var without translation for all languages.
 */

// include configuration and library file

include ('language.conf.php');
include ('language.lib.php');

// table

$tbl_used_lang = '`' . $mainDbName . '`.`' . $mainTblPrefix . TABLE_USED_LANG_VAR . '`';

// get start time

$starttime = get_time();

// start html content

$nameTools = 'Build an empty language file';

$urlSDK = $rootAdminWeb . 'xtra/sdk/'; 
$urlTranslation = $urlSDK . 'translation_index.php';
$interbredcrump[] = array ("url"=>$rootAdminWeb, "name"=> $langAdministration);
$interbredcrump[] = array ("url"=>$urlSDK, "name"=> $langSDK);
$interbredcrump[] = array ("url"=>$urlTranslation, "name"=> $langTranslationTools);

include($includePath."/claro_init_header.inc.php");

echo claro_disp_tool_title($nameTools);

// go to lang folder 

$path_lang = $rootSys . "claroline/lang";
chdir ($path_lang);


// get the different variables 
$sql = " SELECT DISTINCT u.varName 
         FROM ". $tbl_used_lang . " u 
         ORDER BY u.varName";
	
$result = mysql_query($sql) or die ("QUERY FAILED: " .  __LINE__);
	         
if ($result) 
{
    echo '<p>Create file: ' . $path_lang . '/' . LANG_EMPTY_FILENAME . '</p>' . "\n";
	
    $fileHandle = fopen(LANG_EMPTY_FILENAME, 'w') or die("FILE OPEN FAILED: ". __LINE__);	
    if ($fileHandle)
    {
        fwrite($fileHandle, "<?php \n");
		
        while ( $row=mysql_fetch_array($result) )
        {
	        $string = '$'. $row['varName'] . ' = "";' . "\n";
    	    fwrite($fileHandle, $string) or die ("FILE WRITE FAILED: ". __LINE__);
	    }
	
    	fwrite($fileHandle, "?>");
    }
}
	
// build language files
fclose($fileHandle) or die ("FILE CLOSE FAILED: ". __LINE__);

echo '<p><a href="' . $urlAppend . '/claroline/lang/' . LANG_EMPTY_FILENAME . '">Download it</a></p>';

// get end time
$endtime = get_time();
$totaltime = ($endtime - $starttime);

echo "<p><em>Execution time: $totaltime</em></p>\n";

// display footer
include($includePath."/claro_init_footer.inc.php");

?>
