<?php // $Id$
/** 
 * CLAROLINE 
 *
 * @version 1.6
 *
 * @copyright 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license GENERAL PUBLIC LICENSE (GPL) 
 *
 * @see http://www.claroline.net/wiki/
 *
 * @package UPGRADE
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Christophe Gesch� <moosh@claroline.net>
 * @author Mathieu Laurent <laurent@cerdecam.be>
 *
 * Initialize conf settings
 * Try to read  old values in old conf files
 * Build new conf file content with these settings
 * write it.
 */

require '../../inc/claro_init_global.inc.php';

if (!$is_platformAdmin) claro_disp_auth_form();

DEFINE ('DISPLAY_WELCOME_PANEL', __LINE__);
DEFINE ('DISPLAY_RESULT_ERROR_PANEL', __LINE__);
DEFINE ('DISPLAY_RESULT_SUCCESS_PANEL', __LINE__);

DEFINE ('ERROR_WRITE_FAILED', __LINE__);

$display = DISPLAY_WELCOME_PANEL;

/**
 * include file
 */
    
include ($includePath.'/installedVersion.inc.php');
@include ($includePath.'/currentVersion.inc.php');
include ($includePath.'/lib/config.lib.inc.php');
include ($includePath.'/lib/fileManage.lib.php');
    
$thisClarolineVersion = $version_file_cvs;

$error = 0;

if ($_REQUEST['cmd'] == 'run')
{
    $backupRepositorySys = $includePath .'/conf/bak.'.date('Y-z-B').'/';
    // Main conf file

    $output = '<h3>'
            . 'Configuration file'
            . '</h3>'
            . '<ol>'."\n"
            ;
    
    // Prepare repository to backup files
    claro_mkdir($backupRepositorySys);
    // Gen conf file from def    
    
    $def_file_list = get_def_file_list();
    if(is_array($def_file_list))
    {
        
        /**
         Build table with old values in conf
         */
        
        $current_value_list = array();
        
        foreach ( $def_file_list as $def_file_bloc)
        {
            if (is_array($def_file_bloc['conf']))
            {
                foreach ( $def_file_bloc['conf'] as $config_code => $def_name)
                {
                    unset($conf_def, $conf_def_property_list);
                
                    $def_file  = get_def_file($config_code);
                
                    if ( file_exists($def_file) )
                        require($def_file);
                    // load old conf file content
                    $conf_def['old_config_file'][] = $conf_def['config_file'];
                    if (is_array($conf_def['old_config_file']))
                    {
                        foreach ($conf_def['old_config_file'] as $old_file_name) 
                        {
                            $current_value_list =array_merge($current_value_list,get_values_from_confFile($includePath.'/conf/'.$old_file_name,$conf_def_property_list));
                        }
                    }
                }
            }
        }
       
        reset( $def_file_list );
        
        foreach ( $def_file_list as $def_file_bloc)
        {
            if (is_array($def_file_bloc['conf']))
            {
                foreach ( $def_file_bloc['conf'] as $config_code => $def_name)
                {
                    $conf_file = get_conf_file($config_code);
                    $output .= '<li>'.basename($conf_file)."\n"
                            .  '<ul >' ;

                    $okToSave = TRUE;
                    
                    unset($conf_def, $conf_def_property_list);
            
                    $def_file  = get_def_file($config_code);
            
                    if ( file_exists($def_file) )
                        require($def_file);
                    
                    if ( is_array($conf_def_property_list) )
                    {
                        
                        $propertyList = array();
                        
                        foreach($conf_def_property_list as $propName => $propDef )
                        {
                            
                            if(isset($current_value_list[$propName]))
                            {  
                                $propValue = $current_value_list[$propName];
                                // get old value
                            }
                            else 
                            {
                                $propValue = $propDef['default'];                                 
                                // value never set, use default from .def
                            }

                            /**
                             * @todo user can be better informed how to react to this error.
                             */
                            if ( !validate_property($propValue, $propDef) )
                            {
                                $okToSave = FALSE;
                                $output .= '<span class="warning">'.$propName.' : '
                                        . $propValue.' is invalid </span>'
                                        . '<br>'
                                        . 'Rules : '.$propDef['type']
                                        . '<br>'
                                        . var_export($propDef['acceptedValue'],1)
                                        . '<br>'
                                        ;
                            }
                            else
                            {
                                $propertyList[] = array('propName'=>$propName
                                                       ,'propValue'=>$propValue);
                            }
                        }
                    }
                    else
                    {
                        $okToSave = FALSE;
                    }
            
                    if ($okToSave)
                    {
            
                        if ( !file_exists($conf_file) ) touch($conf_file);
            
                        if ( is_array($propertyList) && count($propertyList)>0 )
                        {
        
                            // backup old file 
                            $output .= '<li>';
                            $output .= 'Old file backup : ' ;
                            $fileBackup = $backupRepositorySys.basename($conf_file);
                            if (!@copy($conf_file, $fileBackup) )
                            {
                                $output .= '<span class="warning">failed</span>';
                            }
                            else
                            {
                                $output .= 'succeed';
                            }

                            $output .= '</li>'."\n";
                            // change permission
                            @chmod( $fileBackup, 600 );
                            @chmod( $fileBackup, 0600 );
                            $output .= '<li>'."\n";
                            $output .= 'File upgrade : ';
                            if ( write_conf_file($conf_def,$conf_def_property_list,$propertyList,$conf_file,realpath(__FILE__)) )
                            {
                                $output .= 'succeed';
                                // The Hash compute and store is differed after creation table use for this storage
                                // calculate hash of the config file
                                // $conf_hash = md5_file($conf_file); // md5_file not in PHP 4.1
                                // $conf_hash = filemtime($conf_file);
                                // save_config_hash_in_db($config_code,$conf_hash);
                            }
                            else 
                            {
                                $output .= '<span class="warning">failed</span>';
                                
                            }
                            $output .= '</li>'."\n";
                            
                        }
                    }
                    $output .= '</ul></li>'."\n";
                }
            }
        }
    }
    
    /**
    * Config file to undist
    */
    
    $arr_file_to_undist =
    array (
    $includePath.'/../../textzone_top.inc.html',
    $includePath.'/../../textzone_right.inc.html',
    $includePath.'/conf/auth.conf.php'
    );
    foreach ($arr_file_to_undist As $undist_this)
    {
        $output .='<li>'.basename ($undist_this).' : ';
        if (claro_undist_file($undist_this))
        {
            $output .='succeed';
        }
        else
        {
            $output .= '<span class="warning">failed</span>';
        }
        $output .='</li>'."\n";
    }
    $output .= '</ol>'."\n";
    
    if (!$error)
    {
        $display = DISPLAY_RESULT_SUCCESS_PANEL;
        /*
            * Update config file
            * Set version db
            */

       if (!replace_var_value_in_conf_file ("clarolineVersion",$version_file_cvs,$includePath .'/currentVersion.inc.php'))
       {
        echo '<p class="error">' . 'Can\'t save success in currentVersion.inc.php' . '</p>'  . "\n";
       }
    }
    else
    {
        $display = DISPLAY_RESULT_ERROR_PANEL;
    }
    
} // end if run 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/HTML; charset=iso-8859-1"  />
  <title>-- Claroline upgrade -- version <?php echo $clarolineVersion ?></title>  
  <link rel="stylesheet" type="text/css" href="upgrade.css" media="screen" />
  <style media="print" >
    .notethis {    border: thin double Black;    margin-left: 15px;    margin-right: 15px;}
  </style>
</head>

<body bgcolor="white" dir="<?php echo $text_dir ?>">

<center>

<table cellpadding="10" cellspacing="0" border="0" width="650" bgcolor="#E6E6E6">
<tbody>
<tr bgcolor="navy">
<td valign="top" align="left">
<div id="header">
<?php
    echo sprintf ("<h1>Claroline (%s) - upgrade</h1>",$thisClarolineVersion);
?>
</div>
</td>
</tr>
<!--
<tr bgcolor="#E6E6E6">
<td valign="top"align="left">
<div id="menu">
<?php
 echo sprintf("<p><a href=\"upgrade.php\">%s</a> - %s</p>", 'upgrade', $langUpgradeStep1);
?>
</div>
</td>
</tr>
-->
<tr valign="top" align="left">
<td>

<div id="content">    

<?php

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
        echo sprintf ('<h2>%s</h2>',$langUpgradeStep1 . ' - ' . $langSucceed);
        echo $output;
        echo '<div align="right">' . sprintf($langNextStep,'upgrade_main_db.php') . '</div>';
        break;
    
}
 
?>

</div>
</td>
</tr>
</tbody>
</table>

</body>
</html>
