<?php // $Id$
//----------------------------------------------------------------------
// CLAROLINE 1.6.*
//----------------------------------------------------------------------
// Copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

/**
 * This tool is write to edit setting  of  claroline.
 * In the old claroline, there was a central config file 
 * in next release a conf repository was build  with conf files
 * To not owerwrite on the following release,  
 * was rename  from .conf.inc.php to .conf.inc.php.dist
 * installer was eable to rename from .conf.inc.php.dist to .conf.inc.php

 * The actual config file is build to merge new and active setting.
 * The system as more change than previous evolution
 * Tool are released with a conf definition file
 * This file define for each property a name, a place but also
 * some control for define accepted content.
 * and finally some comment, explanation or info
 *
 * this version do not include 
 * * trigered procedure (function called when a property 
 *   is switch or set to a particular value)
 * * renaming or deletion of properties from config
 * * locking  of edit file (This tools can't really be
 *   in the active part of the day in prod. )
 *   I need to change that to let  admin sleep during the night
 *
 * To make transition, 
 * * a section of tool continue to
 *   edit the main configuration (benoit's script)
 * * a section can parse old file to found old properties
 *   and his values. 
 *   This script would be continue to generate a def conf file.
 *
 * Commands
 *
 *--- cmd==dispEditConf
 * Attempd an tool parameter
 * Read existing value set in db for this tool
 * Read the de file for this tool
 * Display the panel of generic edition (form build following def parameter)
 * 
 * --- isset(cmdSaveProperties
 * call by the DISP_EDIT_CONF when user click on submit.
 *  * check if value are right for control rules in def file
 *  * store (insert/update) in properties in DB
 * 
 * --- cmd==generateConf
 * Attempd an tool parameter
 *  * Read existing value set in db for this tool
 * Write config file if all value needed are set
 *
 * Displays
 * define("DISP_LIST_CONF",      __LINE__); Print out a lis of eable action.
 * define("DISP_EDIT_CONF",__LINE__);  Edit settings of a tool.
 * define("DISP_SHOW_DEF_FILE",  __LINE__);  Display the definition file of a tool
 * define("DISP_SHOW_CONF_FILE", __LINE__);  Display the Conf file of a tool
 **/

///// CAUTION DEVS ////
define('CLARO_DEBUG_MODE',TRUE);

$lang_config_config = '�dition des fichiers de configuration';
$lang_config_config_short = 'Configuration';
$lang_nothingToConfigHere='Il n\'y a pas de param�trage pour <B>%s</B>';
$langBackToMenu = 'Retour au Menu';
$langShowConf        = 'Show Conf';
$langShowDef         = 'Show Def';

$langShowConf        = 'Afficher la configuration';
$langShowDef         = 'Afficher le fichier de d�finition';

$langNoPropertiesSet = 'Il n\'y a pas de propri�t�s propos�es';
$langShowContentFile = 'Voir le contenu du fichier';
$langFile            = 'fichier';
$langApply           = 'Appliquer';
$langApplied         = 'Appliqu�';
$langConfig          = 'Configuration';
$lang_p_defFileOf_S = 'Fichier de d�finition pour la configuration %s.';
$lang_the_active_config_has_manually_change='Version de production modifi�e';
$langFirstDefOfThisValue = '!!! Nouvelle valeur !!!';
$lang_p_config_file_creation = 'cr�ation du fichier de configuration  :<BR> %s';
$lang_p_DefNameAndContentDisruptedOnConfigCode = 'Le fichier de d�finition est probablement un copier-coller  de %s. Et n\'a pas �t� achev�.';

$langEmpty =  'empty';
$lang_p_nothing_to_edit_in_S = 'nothing to edit in %s';
$lang_p_DefNameAndContentDisruptedOnConfigCode = 'The definition file for configuration is probably copypaste from %s';
$langFirstDefOfThisValue = '!!!First definition of this value!!!';
$langNoPropertiesSet = 'No properties set';
$langShowConf        = 'Show Config file';
$langShowDef         = 'Show Definition file';
$langShowContentFile = 'Show content file';
$langFile            = 'File';
$langApply           = 'Apply';
$langApplied         = 'Applied';
$langConfig          = 'Configuration';
$lang_p_defFileOf_S = 'Show defintion file of %s config.';
$lang_p_edit_S      = 'Editing %s config.';
$lang_p_edit_S      = 'Edition de %s.';
$lang_p_Properties_of_S_saved_in_buffer = 'Properties of %s saved in buffer.';
$lang_the_active_config_has_manually_change='The config in production has manually changed';
$lang_p_config_missing_S = 'Configuration is missing %s';                    
$lang_p_ErrorOnBuild_S_for_S= 'Error in building of <em>%s</em> for <B>%s</B>';    
$lang_p_config_file_creation = 'Configuration  file creation:<BR> %s';
$lang_noSectionFoundInDefinitionFile = 'no section found in definition file';             
$lang_p_PropForConfigCommited = 'Properties for %s (%s) are now effective on server.';                   
$langPropertiesNotIncludeInSections = 'Properties not include in sections';

define('DISP_LIST_CONF',        __LINE__);
define('DISP_EDIT_CONF',        __LINE__);
define('DISP_SHOW_CONF',        __LINE__);
define('DISP_SHOW_DEF_FILE',    __LINE__);
define('DISP_SHOW_CONF_FILE',   __LINE__);






define('CONF_AUTO_APPLY_CHANGE',TRUE);
// if false, editing properties mean to change value in database.
// and wait an "apply" to commit these change in files use in production.
// False make interface more "souple" but less easy to understand


// include init and library files

require '../../inc/claro_init_global.inc.php';

include($includePath.'/lib/debug.lib.inc.php');
include($includePath.'/lib/course.lib.inc.php');
include($includePath.'/lib/config.lib.inc.php');

// define
$nameTools 			= $lang_config_config;
$interbredcrump[]	= array ('url'=>$rootAdminWeb, 'name'=> $lang_config_AdministrationTools);
$noQUERY_STRING 	= TRUE;

$htmlHeadXtra[] = '<style>
	legend {
		font-weight: bolder;
		font-size: 130%;
	}
	.firstDefine {
		color: #CC3333;
	}
	.toolDesc    {
		margin-left: 5%;
		padding-left: 2%;
		padding-right: 2%;
	}
	.msg.debug
	{
		background-color: red;
		border: 2px groove red;
	}
	.propUnit { }
	.propType {
		margin-left: 5px;
		font-variant: small-caps;
		font-size: x-small;   }
	.commandBar 
	{
		padding-bottom: 4px;
	}
	.command {padding: 1px 1px 1px 1px;}
	.command:hover { background-color: #F4F4F4; }

</style>
';

/* ************************************************************************** */
/*  INITIALISE VAR 
/* ************************************************************************** */

$tbl_mdb_names = claro_sql_get_main_tbl();
$tbl_tool            = $tbl_mdb_names['tool'];
$tbl_config_property = $tbl_mdb_names['config_property'];
$tbl_config_file     = $tbl_mdb_names['config_file'];
$tbl_rel_tool_config = $tbl_mdb_names['rel_tool_config'];

$toolNameList = array('CLANN' => $langAnnouncement,
                      'CLFRM' => $langForums,
                      'CLCAL' => $langAgenda,
                      'CLCHT' => $langChat,
                      'CLDOC' => $langDocument,
                      'CLDSC' => $langDescriptionCours,
                      'CLGRP' => $langGroups,
                      'CLLNP' => $langLearningPath,
                      'CLQWZ' => $langExercises,
                      'CLWRK' => $langWork,
                      'CLUSR' => $langUsers);

/* ************************************************************************** */
/*  SECURITY CHECKS
/* ************************************************************************** */

$is_allowedToAdmin 	= $is_platformAdmin;

if(!$is_allowedToAdmin)
{
	claro_disp_auth_form(); // display auth form and terminate script
} 

/* ************************************************************************** */
/*  REQUESTS
/* ************************************************************************** */

// Default display
$panel = DISP_LIST_CONF;

    
///////////////////////////////////////////////////
// Command on a specified config.
if ( isset($_REQUEST['config_code']) && isset($_REQUEST['cmd']) )
{

    $config_code = trim($_REQUEST['config_code']);
    // Get info def and conf file (existing or not) for this config code.
    $confDef  = claro_get_def_file($config_code);
    $confFile = claro_get_conf_file($config_code); 

    
    
    ///////////////////////////////////////////////////
    // Command : display Config editor
    if ( $_REQUEST['cmd'] == 'dispEditConf' )
    {
        // Edit Configuration
        // Definition file  would be existing
        if ( file_exists($confDef) )
        {
            $panel = DISP_EDIT_CONF;
        }
        else
        {
    		$controlMsg['error'][] = sprintf($lang_nothingToConfigHere,get_config_name($config_code));
            $panel = DISP_LIST_CONF;
        }
    }
    
    ///////////////////////////////////////////////////
    // Command : display formated content of config file
    elseif ( $_REQUEST['cmd'] == 'showConf' )
    {
        // Show Configuration

        if( file_exists($confFile) )
        {
            @require($confDef);
            @require_once($confFile);
            $interbredcrump[] = array ('url'=>$_SERVER['PHP_SELF'], 'name'=> $lang_config_config_short);
            $nameTools = get_config_name($config_code);
            $panel = DISP_SHOW_CONF;
        }
        else
        {
    		$controlMsg['error'][] = sprintf($lang_nothingToConfigHere,get_config_name($config_code));
            $panel = DISP_LIST_CONF;
        }
    }
    
    ///////////////////////////////////////////////////
    // Command : display real content of Defintion file
    elseif ( $_REQUEST['cmd'] == 'showDefFile' )
    {
        // Show Definition File

        if(file_exists($confDef))
        {
            $interbredcrump[] = array ('url'=>$_SERVER['PHP_SELF'], 'name'=> $lang_config_config_short);
            $nameTools = sprintf($lang_p_defFileOf_S,get_config_name($config_code));
            $panel = DISP_SHOW_DEF_FILE;
        }
        else
        {
    		$controlMsg['error'][] = sprintf($lang_nothingToConfigHere,get_config_name($config_code));
            $panel = DISP_LIST_CONF;
        }
    }
    
    ///////////////////////////////////////////////////
    // Command : display real content of config file
    elseif ( $_REQUEST['cmd'] == 'showConfFile' )
    {
        // Show Configuration source file

        if( file_exists($confFile) )
        {
            $interbredcrump[] = array ('url'=>$_SERVER['PHP_SELF'], 'name'=> $lang_config_config_short);
            $nameTools = get_config_name($config_code);
            $panel = DISP_SHOW_CONF_FILE;
        }
        else
        {
    		$controlMsg['error'][]=sprintf($lang_nothingToConfigHere,get_config_name($config_code));
            $panel = DISP_LIST_CONF;
        }
    }
    
    ///////////////////////////////////////////////////
    // Command : receipt data from config editor, and would be recored in buffer
    elseif ( isset($_REQUEST['cmdSaveProperties']) || isset($_REQUEST['cmdSaveAndApply']) )
    {
        unset($conf_def,$conf_def_property_list);
        
        if ( file_exists($confDef) )
            require($confDef);

        $okToSave = TRUE;

        if ( $conf_def['config_code'] == '' )
        {
            $okToSave = FALSE;
            $controlMsg['error'][] = sprintf($lang_p_config_missing_S ,basename($confDef));
        }
        if ( $config_code != $conf_def['config_code'] )
        {
            $okToSave = FALSE;
            $controlMsg['error'][] = $conf_def['config_code'].' != '.$config_code.' <br>'
            .sprintf($lang_p_DefNameAndContentDisruptedOnConfigCode,basename($confDef));
        }
        
        if ( is_array($_REQUEST['prop']) )
        {
            foreach ( $_REQUEST['prop'] as $propName => $propValue )
            {
                if (!config_checkToolProperty($propValue, $conf_def_property_list[$propName]))
                {
                    $okToSave = FALSE;
                }
            }
            if ( $okToSave ) 
            {
                reset($_REQUEST['prop']);
                foreach ( $_REQUEST['prop'] as $propName => $propValue )
                {
                    save_param_value_in_buffer($propName,$propValue, $config_code);
                }
            }
            else
            {
                $controlMsg['info'][] = 'Save aborded';
                $panel = DISP_EDIT_CONF;
            }
            if ( !isset($_REQUEST['cmdSaveAndApply']) )
                $controlMsg['info'][] = sprintf($lang_p_Properties_of_S_saved_in_buffer 
                                               ,get_config_name($config_code));
        }
        else
        {
            $okToSave = FALSE;
        }            
    }

    /////////////////////////////////////////////////////
    // Command : write config file  with data from buffer
    // There  2 commande following the value of CONF_AUTO_APPLY_CHANGE
    // 

    if ( $_REQUEST['cmd'] == 'generateConf' || ( $okToSave && isset($_REQUEST['cmdSaveAndApply']) ) )
    {
        // OK to build the conf file. 

        // 1� Get extra info from the def file.
        if ( file_exists($confDef) )
        {
            require($confDef);
            $panel = DISP_EDIT_CONF;
            $interbredcrump[] = array ('url'=>$_SERVER['PHP_SELF'], 'name'=> $nameTools);
            $nameTools = get_config_name($config_code);
        }
        else
        {
    		$controlMsg['error'][]=sprintf($lang_nothingToConfigHere,get_config_name($config_code));
            $panel = DISP_LIST_CONF;    
        }

        // 2� Perhaps it's the first creation
        if ( !$confFile )
        {
            $confFile = claro_create_conf_filename($config_code);
            $controlMsg['info'][] = sprintf($lang_p_config_file_creation
                                           ,$confFile);
            $confFile = claro_get_conf_file($config_code);
        }
        
        $storedPropertyList = read_param_value_in_buffer($config_code);
        
        if ( is_array($storedPropertyList) && count($storedPropertyList)>0 )
        {
            if ( write_conf_file($conf_def,$conf_def_property_list,$storedPropertyList,$confFile, realpath(__FILE__)) )
            {
                set_hash_confFile($confFile,$config_code);
                $controlMsg['info'][] =  sprintf($lang_p_PropForConfigCommited,$nameTools,$config_code);
                $controlMsg['debug'][] = 'file generated for <B>'.$config_code.'</B> is <em>'.$confFile.'</em>'.'<br>Signature : <TT>'.$hashConf.'</tt>';        
                $panel = DISP_LIST_CONF;
            }
            else 
            {
                $controlMsg['error'][] = sprintf($lang_p_ErrorOnBuild_S_for_S,$confFile,$config_code);
            }
        }
        else 
        {
            $controlMsg['info'][] = 'No Properties for '.$nameTools
                                   .' ('.$config_code.').<BR><em>'.$confFile.'</em> is not generated';        
            $panel = DISP_LIST_CONF;
       
        }
    }
}

/* ************************************************************************** */
//    PREPARE VIEW   
/* ************************************************************************** */

if ( $panel == DISP_LIST_CONF )
{
    $helpSection = 'help_config_menu.php';

    // List is combination of 2 sources

    // * List of definition files. each one corresponding to a config file. 
    $def_list  = get_def_list();

    // * List of Tools wich are linked to less a config.
    $conf_list = get_conf_list();

    // the two lists are merge and an array is build  with conf by tool.
    $key_list  = array_merge_recursive($def_list,$conf_list);

    $tool_list = array();

    if ( is_array($key_list) )
    {
        foreach( $key_list as $key => $config )
        {
            // The strange following line flat the array 
            // wich can be build by collision during array_merge_recursive         
            $config_item = array_merge($def_list[$key],$conf_list[$key]);
    
            // check if configuration file was edited manually
            if ( file_exists(claro_get_conf_file($config['config_code']) ) 
                 && $config['config_hash'] != md5_file(claro_get_conf_file($config['config_code'])) )
            {
                $config_item['manual_edit'] = TRUE;
            }
            else
            {
                $config_item['manual_edit'] = FALSE;
            }

            // config name
            $config_item['tool'] = get_tool_name($config['claro_label']);

            // config label
            if ( !isset($config['claro_label']) )
                $config['claro_label'] = 'Not for a tool';

            // add config for a tool to tool list
            $tool_list[$config['claro_label']][]= $config_item;
        }
        asort($tool_list);
    }
//  $debugMsg[][]= '$conf_list<pre>'.var_export($conf_list,1);
//  $debugMsg[][]= '$def_list<pre>'.var_export($def_list,1);
//  $debugMsg[][]= '$key_list<pre>'.var_export($key_list,1);
//  $debugMsg[][]= '$tool_list<pre>'.var_export($tool_list,1);


}
elseif ($panel == DISP_EDIT_CONF)
{
    require($confDef);
    $interbredcrump[] = array ('url'=>$_SERVER['PHP_SELF'], 'name'=> $lang_config_config);
    $nameTools = get_config_name($config_code);
    $conf_info = get_conf_info($config_code);    

    if ( $conf_info['manual_edit'] )
    {
        $controlMsg['info'][] = 'The config file has manually change.<br>'
                               .'<br>'
                               .'Actually the script prefill with values found in the current conf, '
                               .'and overwrite values set in the database'
                               ;        
        $currentConfContent = parse_config_file(basename(claro_get_conf_file($config_code)));
    }

    $storedPropertyList = read_param_value_in_buffer($config_code);

    if ( is_array($storedPropertyList) )
    {
        foreach ( $storedPropertyList as $storedProperty )
        {
            $conf_def_property_list[$storedProperty['propName']]['actualValue'] = $storedProperty['propValue']; 
        }
    }

    /* Search for value  existing  in conf file  but not in def file, or inverse */
    $currentConfContentKeyList = is_array($currentConfContent)?array_keys($currentConfContent):array();
    $conf_def_property_listKeyList = is_array($conf_def_property_list)?array_keys($conf_def_property_list):array();
    $unknowValueInConfigFile = array_diff($currentConfContentKeyList,$conf_def_property_listKeyList);
    $newValueInDefFile = array_diff($conf_def_property_listKeyList,$currentConfContentKeyList);

    if (is_array($conf_def['section']) ) 
    {
        foreach($conf_def['section'] as $sectionKey => $section)
        {
            if (is_array($section['properties']))
            {
                foreach($section['properties'] as $propertyName )
                {
                    $conf_def_property_list[$propertyName]['section']=$sectionKey;
                }
            }
        }
    }
    foreach ($conf_def_property_list as $_propName => $_propDescriptorList)
    {
        if (!isset($_propDescriptorList['section']))
        {
            $conf_def_property_list['section']='missingSection';
            $conf_def['section']['sectionmissing']['properties'][]=$_propName;
        }
    }    
    if (isset($conf_def['section']['sectionmissing']))
    {
        $conf_def['section']['sectionmissing']['label'] = $langPropertiesNotIncludeInSections;
        $conf_def['section']['sectionmissing']['description'] = 'This is an error in definition file. Request to the coder of this config to add theses proporties in a section of the definition file.';
        
    }

}

/* ************************************************************************** */
// OUTPUT VIEW   
/* ************************************************************************** */

// display claroline header

include($includePath."/claro_init_header.inc.php");

// display tool title

claro_disp_tool_title(array('mainTitle'=>$nameTools),(isset($helpSection)?$helpSection:false));

// display control message

unset($controlMsg['debug']);

if ( !empty($controlMsg) )
{
    claro_disp_msg_arr($controlMsg);
}

// OUTPUT

switch ($panel)
{
    case DISP_LIST_CONF : 

        echo '<table class="claroTable" cellspacing="4" >' . "\n"
            .'<thead>' . "\n"
            .'<tr class="headerX"  >' . "\n"
            .'<th  colspan="2">'.$langConfig.'</th>';

        if ( CONF_AUTO_APPLY_CHANGE == TRUE )
        {
            echo '<th colspan="2">'.$langEdit.'</th>' ; 
        } 
        else
        {
            echo '<th>'.$langEdit.'</th>' 
                .'<th>'.$langApply.'</th>' ;
        }
        echo '</tr>' . "\n"
            .'</thead>' . "\n" ;

        // display tool list

        foreach ($tool_list as $claro_label => $tool_bloc )
        {
            echo '<tr class="tool_bloc" >' . "\n"
                .'<td>'
                .'<img src="'.$imgRepositoryWeb.$tool_bloc[0]['icon'].'">'
                .'</td>' . "\n"
                .'<td>'
                .'<strong>'.get_tool_name(rtrim($claro_label,'_')).'</strong>'
                .'</td>'
                .'</tr>' . "\n"
                ;
            
            foreach( $tool_bloc as $numconf => $config )
            {
                // The strange following line flat the array 
                // wich can be build by collision during array_merge_recursive 
                $config['config_code'] = (is_array($config['config_code'])?$config['config_code'][0]:$config['config_code']);
                
                echo '<tr>' . "\n"
                    .'<td>'
                    //.($numconf+1)
                    .'</td>' . "\n"
                    .'<td>' ;

                if ( $config['conf'] ) 
                {
                    echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=showConf&amp;config_code='.$config['config_code'].'" >'.$config['name'].'</a>';
                }
                else
                {
                    echo $config['name'];
                }
                echo '</td>' . "\n" ;
                
                if ( !$config['def'] )
                {
                    echo '<td colspan="2" >'
                        .'<strike>'.$langEdit.'</strike>'
                        .'</td>' . "\n" ;
                }
                else 
                {
                    echo '<td>'
                        .'<a href="'.$_SERVER['PHP_SELF'].'?cmd=dispEditConf&amp;config_code='.$config['config_code'].'" >'
                        .'<img src="'.$clarolineRepositoryWeb.'img/edit.gif" border="0" alt="'.$langEdit.'">'
                        .'</a>';

                    if ( $config['manual_edit'] ) 
                    {
                        if ( $config['propQtyInDb']['qty_values']>0 )
                        {
                            echo '<BR><span>'.$lang_the_active_config_has_manually_change.'</span>';
                        } 
                        else
                        {
                            echo '<BR>config de l\'ancien syst�me en production';
                        }
                    }
                    echo '</td>' . "\n";

                    if ( !CONF_AUTO_APPLY_CHANGE )
                    {
                        echo '<td>';
                        if ( $config['propQtyInDb']['qty_values']>0 )
                        {
                            if ( $config['propQtyInDb']['qty_new_values']>0 )
                            {
                                echo  '<a href="'.$_SERVER['PHP_SELF'].'?cmd=generateConf&amp;config_code='.$config['config_code'].'" >'
                                    . '<img src="'.$clarolineRepositoryWeb.'img/download.gif" border="0" alt="'.$langSave.'">' .'<br>' 
                                    . '(<small>'.$config['propQtyInDb']['qty_new_values'].' new values</small>)'
                                    . '</a>' ;
                            } 
                            else
                            {
                                echo $langApplied;
                            }
                        }
                        else
                        {
                          echo '<small>'. $langNoPropertiesSet .'</small>';
                        }
                        echo '</td>';
                    }
                }
                echo '</tr>';
            }
        }
        echo '</table>';
        break;

    case DISP_EDIT_CONF : 

        if ( is_array($conf_def) )
        {
            if ( !empty($conf_def['description']) ) 
            {
                echo '<p>'.$conf_def['description'].'</p><br />';
            }

			// echo '<em><small>'. $confDef .'</small></em>';

            // display form  
            echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'" name="editConfClass">'."\n";
            echo '<input type="hidden" value="'.$config_code.'" name="config_code">'."\n";
            echo '<input type="hidden" name="cmd" value="cmdSaveProperties" >';

            if (is_array($conf_def['section']) ) 
            {

				echo '<table border="0" cellpadding="5">' . "\n";

                foreach($conf_def['section'] as $section)
                {

					// display fieldset with the label of the section
                    echo '<tr>'."\n"
                        .'<td colspan="3">' . '<h4>' . $section['label'].'&nbsp;:</h4>'. "\n";

					// display description of the section
                    if ( !empty($section['description']) )
                    {
                        echo '<p><em>' . $section['description'] . '</em></p>';
                    }
					echo '</tr>' . "\n";

                    // The default value is show in input or preselected value if there is no value set.
                    // If a value is already set the default value is show as sample.
                    if ( is_array($section['properties']) )
                    {

						// display properties
                        foreach( $section['properties'] as $property )
                        {
                            if (is_array($conf_def_property_list[$property]))
                            {
                               claroconf_disp_editbox_of_a_value($conf_def_property_list[$property], $property, $currentConfContent[$property]);
                            }
                            else 
                            {
                                echo 'Def corrupted: property '.$property.' is not defined';
                            }
                        }
                    }

                }
				echo '</table>';

                if (CONF_AUTO_APPLY_CHANGE)
                {
                    echo '<input type="submit" name="cmdSaveAndApply" value="Save" >' . "\n";
                }
                else 
                {
                    echo '<input type="submit" name="cmdSaveAndApply" value="Save and Apply" >' . "\n";
                    echo '<input type="submit" name="cmdSaveProperties" value="Save without apply" >' . "\n";
                }                   
            }
            else
            {
                echo 'no section found in definition file';                                
            }
            echo '</form>'."\n";
        }
        else
        {
            $msg = sprintf($lang_p_nothing_to_edit_in_S ,get_config_name($config_code));
			claro_disp_message($msg);
        }
        break;

    case DISP_SHOW_CONF :
 
        echo '<div class="commandBar">'
            .'<span class="command">'
            .'<a href="'.$_SERVER['PHP_SELF'].'?cmd=showConfFile&amp;config_code='.$config_code.'" >'.$langShowContentFile.'</a>'
            .'</span>'
            .'&nbsp;|&nbsp;'
            .'<span class="command">'
            .'<a href="'.$_SERVER['PHP_SELF'].'" >'
            .$langBackToMenu
            .'</a>'
            .'</span>'
            .'&nbsp;|&nbsp;'
            .'<span class="command">'
            .'<a href="'.$_SERVER['PHP_SELF'].'?cmd=dispEditConf&amp;config_code='.$config_code.'" >'.$langEdit.'</a>'
            .'</span>'
            .'</div>';

        if (is_array($conf_def))
        {

            if (isset($conf_def['description']))
            {
                echo '<p>'.$conf_def['description'].'</p><br />';
            }                
            echo '<em><small>'.$confDef.'</small></em>';

            if (is_array($conf_def['section']) ) 
            {
                foreach($conf_def['section'] as $section)
                {
                    echo '<FIELDSET>'
                        .'<LEGEND>'.$section['label'].'</LEGEND>'."\n";
                    if ($section['description'])
                    {
                        echo '<div class="sectionDesc">'.$section['description'].'</div><br />';
                    }

                    if (is_array($section['properties']))
                    {
                        foreach($section['properties'] as $property )
                        {
                            $htmlPropLabel = htmlentities($conf_def_property_list[$property]['label']);

                            $htmlPropDesc = '';
                            if ($conf_def_property_list[$property]['description'])
                            {
                                $htmlPropDesc = '<div class="propDescription">'
                                               . nl2br(htmlentities($conf_def_property_list[$property]['description'])).'<br />'
                                               .'</div>';
                            }

                            if ($conf_def_property_list[$property]['container']=='CONST')
                            {
                                 eval('$htmlPropValue = '.$property.';');
                            }
                            else
                            {
                                 eval('$htmlPropValue = $'.$property.';');
                            }

                            $htmlUnit = '';
                            if ($conf_def_property_list[$property]['unit']) 
                            { 
                                $htmlUnit = ''.htmlentities($conf_def_property_list[$property]['unit']);
                            }

                            echo '<h2 class="propLabel">'
                                .$htmlPropLabel 
                                .' <span class="propType">'
                                .'('.$conf_def_property_list[$property]['type'].')'
                                .'</span>'
                                .'</h2>'."\n"
                                .$htmlPropDesc
                                ."\n"
                                .'<em class="propName">'
                                .$property
                                .'</em>: '
                                .'<strong class="propValue" >'
                                .var_export($htmlPropValue,1)
                                .'</strong> '
                                .'<span class="propUnit">'
                                .$htmlUnit
                                .'</span>'
                                .'<br>'."\n"
                                ;
                        } // foreach($section['properties'] as $property )
                    }
                    echo '</FIELDSET><br>'."\n";
                } //foreach($conf_def['section'] as $section)
            }
            else
            {
                echo $lang_noSectionFoundInDefinitionFile ;
            }
        }
        break;

    case DISP_SHOW_CONF_FILE : 
        
        echo '<div class="links">'
            .'<a href="'.$_SERVER['PHP_SELF'].'" >'
            .$langBackToMenu
            .'</a>'
            .'</div>'
            .'<br />'."\n"
            ;
        highlight_file($confFile);

        break;

    case DISP_SHOW_DEF_FILE : 

        echo '<div class="links">'
            .'<a href="'.$_SERVER['PHP_SELF'].'" >'
            .$langBackToMenu
            .'</a>'
            .'</div>'
            .'<br />'."\n"
            ;
        highlight_file($confDef);
        break;

    default : echo 'error : panel not defined';

}

include($includePath."/claro_init_footer.inc.php");
?>