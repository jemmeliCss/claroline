<?php // $Id$
/** 
 * CLAROLINE 
 *
 * Initialize conf settings
 * Try to read  current values in current conf files
 * Build new conf file content with these settings
 * write it.
 *
 * @version 1.7
 *
 * @copyright 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @see http://www.claroline.net/wiki/index.php/Upgrade_claroline_1.7
 *
 * @package UPGRADE
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Christophe Gesch� <moosh@claroline.net>
 * @author Mathieu Laurent <laurent@cerdecam.be>
 *
 */

/*=====================================================================
  Init Section
 =====================================================================*/ 

if ( ! file_exists('../../currentVersion.inc.php') )
{
    // if this file doesn't exist, the current version is < claroline 1.6
    // in 1.6 we need a $platform_id for session handling
    $platform_id =  md5(realpath('../../inc/conf/def/CLMAIN.def.conf.inc.php'));
}

// Initialise Claroline
require '../../inc/claro_init_global.inc.php';

// Security Check
if (!$is_platformAdmin) claro_disp_auth_form();

// Define display
DEFINE ('DISPLAY_WELCOME_PANEL', __LINE__);
DEFINE ('DISPLAY_RESULT_ERROR_PANEL', __LINE__);
DEFINE ('DISPLAY_RESULT_SUCCESS_PANEL', __LINE__);
DEFINE ('ERROR_WRITE_FAILED', __LINE__);
$display = DISPLAY_WELCOME_PANEL;

// Include library
include ($includePath.'/lib/config.lib.inc.php');
include ($includePath.'/lib/fileManage.lib.php');
include ('upgrade.lib.php');

// Initialise Upgrade
upgrade_init_global();

/*=====================================================================
  Main Section
 =====================================================================*/ 

$error = FALSE;

$cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : ''; 

if ( $cmd == 'run' )
{
    // Prepare repository to backup files
    $backupRepositorySys = $includePath .'/conf/bak.'.date('Y-z-B').'/';
    claro_mkdir($backupRepositorySys);

    $output = '<h3>' . $langConfigurationFile . '</h3>' . "\n" ;  
    $output.= '<ol>' . "\n" ;
    
    // Generate configuration file from definition file
    $def_file_list = get_def_file_list();

    if ( is_array($def_file_list) )
    {        
        // Build table with current values in configuration files       
        $current_value_list = array();
        
        foreach ( array_keys($def_file_list) as $config_code )
        {
            unset($conf_def, $conf_def_property_list);
        
            $def_file = get_def_file($config_code);
        
            if ( file_exists($def_file) ) 
            {
                require($def_file);
            }

            // Add current config file to old config file list
            $conf_def['old_config_file'][] = $conf_def['config_file'];

            if ( is_array($conf_def['old_config_file']) )
            {
                // Browse configuration files
                foreach ( $conf_def['old_config_file'] as $current_file_name ) 
                {
                    // Add config name an value in array current value list
                    $current_value_list = array_merge( $current_value_list,
                                                       get_values_from_confFile($includePath . '/conf/' . $current_file_name,$conf_def_property_list)
                                                      );
                }
            }

        }

        // Set platform_id if not set in current claroline version (new in 1.6)
        if ( ! isset($current_value_list['platform_id']) )
        {
            $current_value_list['platform_id'] = $platform_id;
        }
       
        // Browse definition file and build them
        
        reset( $def_file_list );
        
        foreach ( $def_file_list as $config_code => $def)
        {
            // read configuration file
            $conf_file = get_conf_file($config_code);

            $output .= '<li>'. basename($conf_file)
                    .  '<ul >' . "\n";

            $okToSave = TRUE;
            
            unset($conf_def, $conf_def_property_list);
    
            // read definition file
            $def_file = get_def_file($config_code);
    
            if ( file_exists($def_file) ) require($def_file);
            
            if ( isset($conf_def_property_list) && is_array($conf_def_property_list) )
            {
                
                $propertyList = array();
                
                // Browse each property of the definition files

                foreach ( $conf_def_property_list as $propName => $propDef )
                {

                    // Get current property value if exists 
                    // else get its default value

                    if ( isset($current_value_list[$propName]) )
                    {  
                        $propValue = $current_value_list[$propName];
                    }
                    else 
                    {
                        $propValue = $propDef['default'];                                 
                    }

                    /**
                     * Validate property value
                     * @todo user can be better informed how to react to this error.
                     */
                    if ( !validate_property($propValue, $propDef) )
                    {
                        // Validation failed - Alert users
                        $okToSave = FALSE;
                        $error = TRUE;
                        $output .= '<span class="warning">'.sprintf($lang_p_s_s_isInvalid, $propName, $propValue) . '</span>' . '<br>' . "\n"
                                . sprintf( $lang_rules_s_in_s,$propDef['type'] ,basename($def_file)).' <br>' . "\n"
                                . var_export($propDef['acceptedValue'],1) . '<br>' . "\n" ;
                    }
                    else
                    {
                        // Validation succeed 
                        $propertyList[] = array('propName'  => $propName
                                               ,'propValue' => $propValue);
                    }
                }
            }
            else
            {
                $okToSave = FALSE;
                $error = TRUE;
            }
    
            // We save the upgraded configuration file

            if ($okToSave)
            {
                if ( !file_exists($conf_file) )
                {
                    // Create a file empty if not exists
                    touch($conf_file);
                }
                else
                {
                    // Backup current file 
                    $output .= '<li>' . $lang_oldFileBackup . ' ' ;

                    $fileBackup = $backupRepositorySys . basename($conf_file);
                    if (!@copy($conf_file, $fileBackup) )
                    {
                        $output .= '<span class="warning">' . $langFailed . '</span>';
                    }
                    else
                    {
                        $output .= '<span class="success">'. $langSucceeded . '</span>';
                    }
                    $output .= '</li>' . "\n" ;

                    // Change permission of the backup file
                    @chmod( $fileBackup, 0777 );
                    @chmod( $fileBackup, 0777 );
                }

    
                if ( is_array($propertyList) && count($propertyList)>0 )
                {
                    // Save the new configuration file 

                    $output .= '<li>' . $lang_fileUpgrade . ' ';

                    if ( write_conf_file($conf_def,$conf_def_property_list,$propertyList,$conf_file,realpath(__FILE__)) )
                    {
                        $output .= '<span class="success">'. $langSucceeded . '</span>';
                    }
                    else 
                    {
                        $output .= '<span class="warning">' . $langFailed . '</span>';
                        $error = TRUE;
                    }
                    $output .= '</li>'."\n";
                }
            }
            $output .= '</ul>' . "\n" 
                     . '</li>' . "\n";

        } // End browse definition file and build them

    }
    
    /**
     * Config file to undist
     */
    
    $arr_file_to_undist = array ( $includePath.'/../../textzone_top.inc.html',
                                 $includePath.'/../../textzone_right.inc.html',
                                 $includePath.'/conf/auth.conf.php'
                                );

    foreach ( $arr_file_to_undist as $undist_this )
    {
        $output .= '<li>'. basename ($undist_this) . "\n"
                . '<ul><li>'.$langUndist.' : ' . "\n" ;

        if ( claro_undist_file($undist_this) )
        {
            $output .= '<span class="success">'. $langSucceeded . '</span>';
        }
        else
        {
            $output .= '<span class="warning">' . $langFailed . '</span>';
            $error = TRUE;
        }
        $output .= '</li>' . "\n" . '</ul>' . "\n"
                 . '</li>' . "\n";
    }

    $output .= '</ol>' . "\n";
    
    if ( !$error )
    {
        $display = DISPLAY_RESULT_SUCCESS_PANEL;

        // Update current version file
        save_current_version_file($new_version,$currentDbVersion);
    }
    else
    {
        $display = DISPLAY_RESULT_ERROR_PANEL;
    }
    
} // end if run 

/*=====================================================================
  Display Section
 =====================================================================*/ 

// Display Header
echo upgrade_disp_header();

// Display Content

switch ($display)
{
    case DISPLAY_WELCOME_PANEL :
        echo sprintf ('<h2>%s</h2>',$langUpgradeStep1);
        echo $langIntroStep1;
        echo '<center>' . sprintf ($langLaunchStep1, $_SERVER['PHP_SELF'].'?cmd=run') . '</center>';
        break;
        
    case DISPLAY_RESULT_ERROR_PANEL :
        echo sprintf ('<h2>%s</h2>',$langUpgradeStep1 . ' - ' . $langFailed);
        echo $output;
        break;

    case DISPLAY_RESULT_SUCCESS_PANEL :
        echo sprintf ('<h2>%s</h2>',$langUpgradeStep1 . ' - ' . '<span class="success">' . $langSucceeded . '</span>');
        echo $output;
        echo '<div align="right">' . sprintf($langNextStep,'upgrade_main_db.php') . '</div>';
        break;
    
}

// Display footer
echo upgrade_disp_footer();

?>
