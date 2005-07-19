<?php // $Id$

/**
 * File structure checker : Display difference between list of scripts 
 * on the server and the official claroline archive
 *
 * @version 1.6
 *
 * @copyright 2001-2005 Universite catholique de Louvain (UCL)
 * @copyright 2004-2005 Centre de Recherche et de D�veloppement de l'ECAM (CERDECAM)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @package MAINTENANCE
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Mathieu Laurent <laurent@cerdecam.be>
 *
 */

/*=====================================================================
  Init Section
 =====================================================================*/ 

require '../../inc/claro_init_global.inc.php';

if (!$is_platformAdmin) claro_disp_auth_form();

// lang variables
$urlMaintenance = $rootAdminWeb . 'maintenance/';

// Include version file
if ( file_exists($includePath.'/currentVersion.inc.php') )
{
    include ($includePath.'/currentVersion.inc.php');
}

// Include array with script
// $claroline_script[]
include(dirname(__FILE__).'/claroline_script.inc.php');

// Display 
define('DISP_ARRAY',__LINE__);
define('DISP_DIFF',__LINE__);

/*=====================================================================
  Main Section
 =====================================================================*/

$scan= scan_dir ($rootSys.'claroline',$recurse=TRUE);

$local_script = $scan['files'];

if ( $_REQUEST['cmd'] == 'export' )
{
    $display_array = display_array_with_script($local_script,$clarolineVersion);
    $display = DISP_ARRAY;
} 
else
{
    $display = DISP_DIFF;

    $diff_script_missing = array_diff($claroline_script,$local_script);
    $diff_script_not_used = array_diff($local_script,$claroline_script);

}

/*=====================================================================
  Display Section
 =====================================================================*/

$nameTools = $langFileStructureChecker;

$interbredcrump[] = array ("url"=>$rootAdminWeb, "name"=> $langAdministration);
$interbredcrump[] = array ("url"=>$urlMaintenance, "name"=> $langMaintenance);

include($includePath.'/claro_init_header.inc.php');

claro_disp_tool_title($nameTools);

//echo '<h3>Claroline version: ' . $clarolineVersion . '</h3>' . "\n";

switch ($display)
{
    case DISP_ARRAY :

        echo $display_array;

        break;


    case DISP_DIFF: 
    default :

        echo '<h4>' . $langScriptMissing . '</h4>' . "\n";
        echo '<p><em>'  . $langScriptMissingComment . '</em></p>' . "\n";
    
        if ( count($diff_script_missing) > 0 )
        { 
            echo '<ul>' . "\n";
            foreach ( $diff_script_missing as $script )
            {
                echo '<li>' . $rootSys . 'claroline' . $script . '</li>' . "\n";
            }
            echo '</ul>' . "\n";
        }
        else
        {
            echo $langNoScript;
        }

        echo '<h4>' . $langScriptNotInArchive . '</h4>';
        echo '<p><em>' . $langScriptNotInArchiveComment . '</em></p>' ;

        if ( count($diff_script_not_used) > 0 )
        { 
            echo '<ul>' . "\n";
            foreach ( $diff_script_not_used as $script )
            {
                echo '<li>' . $rootSys . 'claroline' . $script . '</li>' . "\n";
            }
            echo '</ul>' . "\n";
        }
        else
        {
            echo $langNoScript;
        }
        break;
    
}


include($includePath.'/claro_init_footer.inc.php');

/*=====================================================================
  Display Section
 =====================================================================*/

/**
 * Browse a dirname and returns all files and subdirectories
 * 
 * @return - array('files'=>array(), 'directories=>array())
 *
 * @param  - string $dirname
 * @param  - boolean $recurse
 */

function scan_dir($dirname,$recurse=FALSE)
{
    global $rootSys;
    
    static $file_array=array();
    static $dir_array=array();
    static $ret_array=array();

    if($dirname[strlen($dirname)-1]!='/')
    {
        $dirname.='/';
    }

    $handle=opendir($dirname);

    while (false !== ($element = readdir($handle)))
    {
        if( is_scannable($dirname.$element) )
        {
            if(is_dir($dirname.$element))
            {
                $dir_array[]=$dirname.$element;

                if($recurse)
                {
                    scan_dir($dirname.$element.'/',$recurse);
                }
            }
            else
            {
                $file_array[]=str_replace($rootSys.'claroline','',$dirname.$element);
            }
        }
    }

    closedir($handle);

    $ret_array['files']=$file_array;
    $ret_array['directories']=$dir_array;

    return $ret_array;

}

/**
 * Check if the file or directory is an element scannable 
 *
 * @return - boolean 
 * @param  - string
 * @param  - array
 * @param  - array
 */

function is_scannable($filePath, 
                      $additionnalForbiddenDirNameList = array(), 
                      $additionnalForbiddenFileSuffixList = array() )
{
    global $rootSys;

    $baseName    = basename($filePath);
    $parentPath  = str_replace('\\', '/', dirname($filePath));
    $parentPath  = str_replace($rootSys, '', $parentPath);

    $forbiddenDirNameList    = array_merge( array('claroline/lang',
                                                  'claroline/inc/conf',
                                                  'claroline/claroline_garbage'),
                                            $additionnalForbiddenDirNameList);
    $forbiddenParentNameList = array('CVS');

    $forbiddenFileNameList   = array('.', '..','CVS');

    $forbiddenBaseNameList   = array_merge($forbiddenFileNameList, 
                                           $forbiddenDirNameList);

    $forbiddenFileSuffixList = array_merge( array( '~'), 
                                            $additionnalForbiddenFileSuffixList);

    $forbiddenFilePrefixList = array('~', '#', '\\.');

    // BASENAME CHECK

    if (is_file($filePath) && ! preg_match('/.php$/i',$baseName) ) return false;

    if (in_array($baseName, $forbiddenBaseNameList) )              return false;

    foreach($forbiddenFileSuffixList as $thisForbiddenSuffix)
    {
        if (preg_match('|'.$thisForbiddenSuffix.'^|', $baseName) ) return false;
    }

    foreach($forbiddenFilePrefixList as $thisForbiddenPrefix)
    {
        if (preg_match('|$'.$thisForbiddenPrefix.'|', $baseName) ) return false;
    }
    
    // DIRECTORY CHECK
    foreach($forbiddenDirNameList as $thisDirName)
    {
        if ( strpos($filePath, $rootSys.$thisDirName) !== FALSE ) 
        {
            return false;
        }
    }

    // PARENT PATH CHECK

    $pathComponentList = explode('/', $parentPath);

    foreach($pathComponentList as $thisPathComponent)
    {
        if (in_array($thisPathComponent, $forbiddenParentNameList) ) return false;
    }

    return true;
} 

function display_array_with_script ($files)
{
    sort($files);

    $version = str_replace('.','_',$version);

    $html = '<pre>';

    foreach ($files as $file )
    {
        $html .= '$claroline_script[] = "' . $file . '";' . "\n";
    }

    $html .= '</pre>';

    return $html;
}

?>
