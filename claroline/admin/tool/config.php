<?php # $Id$
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

/*
// This tool is write to edit setting  of  claroline.
// In the old claroline, there was a central config file 
// in next release a conf repository was build  with conf files
// To not owerwrite on theF following release,  
//   was rename  from .conf.inc.php to .conf.inc.php.dist
// installer was eable to rename from .conf.inc.php.dist to .conf.inc.php

// the actual config file is build to merge new and active setting.
// The system as more change than previous evolution
// Tool are released with a conf definition file
// This file define for each property a name, a place but also
// some control for define accepted con?tent.
// and finally some comment, explanation or info

// this version do not include 
// * trigered procedure (function called when a property 
//   is switch or set to a particular value)
// * renaming or deletion of properties from config
// * locking  of edit file (This tools can't really be
//   in the active part of the day in prod. )
//   I need to change that to let  admin sleep during the night

// To make transition, 
// * a section of tool continue to
//   edit the main configuration (benoit's script)
// * a section can parse old file to found old properties
//   and his values. 
//   This script would be continue to generate a def conf file.

// Commands

--- cmd==dispEditConfClass
Attempd an tool parameter
Read existing value set in db for this tool
Read the de file for this tool
Display the panel of generic edition (form build following def parameter)

--- isset(cmdSaveProperties
call by the DISP_EDIT_CONF_CLASS when user click on submit.
* check if value are right for control rules in def file
* store (insert/update) in properties in DB

--- cmd==generateConf
Attempd an tool parameter
Read existing value set in db for this tool
Write config file if all value needed are set

// Displays
 define("DISP_LIST_CONF",      __LINE__); Print out a lis of eable action.
 define("DISP_EDIT_CONF_CLASS",__LINE__);  Edit settings of a tool.
 define("DISP_SHOW_DEF_FILE",  __LINE__);  Display the definition file of a tool
 define("DISP_SHOW_CONF_FILE", __LINE__);  Display the Conf file of a tool

*/

///// CAUTION DEVS ////
///// This script use the PEAR package var_dump
// If you dont have pear, comment these lines 
// and replace Var_Dump::display by Var_Dump

define('CLARO_DEBUG_MODE',TRUE);

$lang_config_config = '�dition des fichiers de configuration';
$lang_config_config_short = 'Configuration';
$lang_nothingToConfigHere='Il n\'y a pas de param�trage pour <B>%s</B>';
$langBackToMenu = 'Retour au Menu';
$langShowConf        = 'Show Conf';
$langShowDef         = 'Show Def';
$langNoPropertiesSet = 'No properties set';
$langShowContentFile = 'Voir le contenu du fichier';
$langSagasu          = 'fichier';
$langApply           = 'Appliquer';
$langApplied         = 'Appliqu�';
$langConfig          = 'Configuration';
$lang_p_defFileOf_S = 'Show defintion file of %s config.';
$lang_p_edit_S      = 'Editing %s config.';
$lang_p_defFileOf_S = 'Fichier de d�finition pour la configuration %s.';
$lang_p_edit_S      = 'Edition de %s.';
$lang_p_Properties_of_S_saved_in_buffer = 'Properties of %s saved in buffer.';
$lang_the_active_config_has_manually_change='Version de production modifi�e';
$lang_the_active_config_has_manually_change='The config in production has manually changed';
                    
define('DISP_LIST_CONF',        __LINE__);
define('DISP_EDIT_CONF_CLASS',  __LINE__);
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

// use Var_Dump PEAR package

require_once('Var_Dump.php');
Var_Dump::displayInit(array('display_mode' => 'HTML4_Table'));

// define

$nameTools 			= $lang_config_config;
$interbredcrump[]	= array ('url'=>$rootAdminWeb, 'name'=> $lang_config_AdministrationTools);
$noQUERY_STRING 	= TRUE;

$htmlHeadXtra[] = '<style>
label    {

}
fieldset    {
	background-color: #FFFFCF;
}
.firstDefine{
	color: #CC3333;

}
.toolDesc    {
	border: 1px solid Gray;
	background-color: #FFDAB9;
	margin-left: 5%;
	padding-left: 2%;
	padding-right: 2%;
}
.msg.debug
{
	background-color: red;
	border: 2px groove red;
}
.sectionDesc {
	border: 1px solid Gray;
	background-color: #00FA9A;
	margin-left: 5%;
	padding-left: 2%;
	padding-right: 2%;
}
.propDesc    {
	border: 1px solid Gray;
	background-color: #AFEEEE;
	margin-left: 5%;
	padding-left: 2%;
	padding-right: 2%;
}
</style>
';

/* ************************************************************************** */
/*  
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
                      'CLLNP' => $langLearnPath,
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

if ( isset($_REQUEST['config_code']) && isset($_REQUEST['cmd']) )
{
    $config_code = $_REQUEST['config_code'];
    $confDef  = claro_get_def_file($config_code);
    $confFile = claro_get_conf_file($config_code); 

    if( $_REQUEST['cmd']=='dispEditConfClass' )
    {
        // Edit settings of a config_code 
 
        if(file_exists($confDef))
        {
            $panel = DISP_EDIT_CONF_CLASS;
        }
        else
        {
    		$controlMsg['error'][]=sprintf($lang_nothingToConfigHere,get_config_name($config_code));
            $panel = DISP_LIST_CONF;
        }
    }
    elseif( $_REQUEST['cmd']=='showConf' )
    {
        // Show Configuration

        if(file_exists($confFile))
        {
            @require($confDef);
            @require($confFile);
            $interbredcrump[] = array ('url'=>$_SERVER['PHP_SELF'], 'name'=> $lang_config_config_short);
            $nameTools = get_config_name($config_code);
            $panel = DISP_SHOW_CONF;
        }
        else
        {
    		$controlMsg['error'][]=sprintf($lang_nothingToConfigHere,get_config_code_name($config_code));
            $panel = DISP_LIST_CONF;
        }
    }
    elseif( $_REQUEST['cmd']=='showDefFile' )
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
    		$controlMsg['error'][]=sprintf($lang_nothingToConfigHere,get_config_name($config_code));
            $panel = DISP_LIST_CONF;
        }
    }
    elseif($_REQUEST['cmd']=='showConfFile')
    {
        // Show configuration file

        if(file_exists($confFile))
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
    elseif(isset($_REQUEST['cmdSaveProperties']) || isset($_REQUEST['cmdSaveAndApply']))
    {
        if(file_exists($confDef))
        {
            require($confDef);
        }

        //  var_dump::display($_REQUEST['prop']);
        $okToSave = TRUE;
        if ($conf_def['config_code']=='')
        {
            $okToSave = FALSE;
            $controlMsg['error'][] = 'Configuration is missing '.basename($confDef);
    
        }
        if ($config_code != $conf_def['config_code'])
        {
            $okToSave = FALSE;
            $controlMsg['error'][] = $conf_def['config_code'].' != '.$config_code.' <br>
            The definition file for configuration is probably copypaste from '.basename($confDef);
        }
        if (is_array($_REQUEST['prop']) )
        {
            foreach($_REQUEST['prop'] as $propName => $propValue )
            {
                $validator     = $conf_def_property_list[$propName]['type'];
                $acceptedValue = $conf_def_property_list[$propName]['acceptedValue'];
                $container     = $conf_def_property_list[$propName]['container'];
                //      config_checkToolProperty($propValue, $conf_def_property_list[$propName]);
                //      $controlMsg['log'][] = $propName.' '.$validator.' '.var_export($acceptedValue,1);
                switch($validator)
                {
                    case 'boolean' : 
                        if (!($propValue=='TRUE'||$propValue=='FALSE') )
                        {
                            $controlMsg['error'][] = $propName.' would be boolean';
                            $okToSave = FALSE;
                        }   
                        break;
                    case 'integer' : 
                        $propValue = (int) $propValue;
                        if (!is_integer($propValue)) 
                        {
                            $controlMsg['error'][] = $propName.' would be integer';
                            $okToSave = FALSE;
                        }
                        elseif (isset($acceptedValue['max'])&& $acceptedValue['max']<$propValue)
                        {
                            $controlMsg['error'][] = $propName.' would be integer inferior or equal to '.$acceptedValue['max'];
                            $okToSave = FALSE;
                        }   
                        elseif (isset($acceptedValue['min'])&& $acceptedValue['min']>$propValue)
                        {
                            $controlMsg['error'][] = $propName.' would be integer superior or equal to '.$acceptedValue['min'];
                            $okToSave = FALSE;
                        }   
                        break;
                    case 'enum' : 
                        if (!in_array($propValue,array_keys($acceptedValue))) 
                        {
                            $controlMsg['error'][] = $propName.' would be in enum list';
                            $okToSave = FALSE;
                        }   
                        break;
                    case 'relpath' :
                    case 'syspath' :
                    case 'wwwpath' :
                        if (empty($propValue))
                        {
                            $controlMsg['error'][] = $propName.' is empty';
                            $okToSave = FALSE;
                        }   
                        break;
                    case 'regexp' :
                        if (!eregi( $acceptedValue, $propValue )) 
                        {
                            $controlMsg['error'][] = $propName.' would be valid';
                            $controlMsg['error'][] = $acceptedValue.' '.$propValue;
                            $okToSave = FALSE;
                        }   
                        break;
                    default :
                }
                if ($okToSave) 
                {
                    $sqlParamExist = 'SELECT count(id_property) nbline
                                      FROM `'.$tbl_config_property.'` 
                                      WHERE propName    ="'.$propName.'" 
                                        AND config_code ="'.$config_code.'"';
                    $exist = claro_sql_query_fetch_all($sqlParamExist);
                    if ($exist[0]['nbline']==0) 
                    {
                        $sql ='INSERT 
                               INTO `'.$tbl_config_property.'` 
                               SET propName    = "'.$propName.'", 
                                   propValue   = "'.$propValue.'", 
                                   lastChange  = now(), 
                                   config_code = "'.$config_code.'"';
                    }
                    else
                    {
                        $sql ='UPDATE 
                                `'.$tbl_config_property.'` 
                               SET propName    ="'.$propName.'", 
                                   propValue   ="'.$propValue.'", 
                                   lastChange  = now()
                               WHERE propName    ="'.$propName.'" 
                                 AND config_code ="'.$config_code.'"
                                 AND not (propValue   ="'.$propValue.'") # do not update if same value 
                                 ';
                    }
                    claro_sql_query($sql);                 
                }
                else
                {
                    $controlMsg['info'][] = 'Save aborded';
                    $panel = DISP_EDIT_CONF_CLASS;
                }
            }
            $controlMsg['info'][] = sprintf($lang_p_Properties_of_S_saved_in_buffer 
                           ,get_config_name($config_code));
        }
        else
        {
            $okToSave = FALSE;
        }            
    }

    if(   $_REQUEST['cmd']=='generateConf' 
       || ( $okToSave 
            && isset($_REQUEST['cmdSaveAndApply'])))
    {
        // OK to build the conf file. 
        // 1� Get extra info from the def file.
        if(file_exists($confDef))
        {
            require($confDef);
            $panel = DISP_SHOW_CONF_FILE;
            $interbredcrump[] = array ("url"=>$_SERVER['PHP_SELF'], "name"=> $nameTools);
            $nameTools = get_config_name($config_code);
        }
        else
        {
    		$controlMsg['error'][]=sprintf($lang_nothingToConfigHere,get_tool_name($tool));
            $panel = DISP_LIST_CONF;    
        }
        // 2� Perhaps it's the first creation
        if (!$confFile)
        {
            $confFile = claro_create_conf_file($config_code);
            $controlMsg['info'][] = sprintf('cr�ation du fichier de configuration :<BR> %s'
                                           ,$confFile);
            $confFile = claro_get_conf_file($config_code);
        }
        //3� use the extra infos
        if(file_exists($confDef))
        {
            require($confDef);
            $panel = DISP_EDIT_CONF_CLASS;
            $interbredcrump[] = array ("url"=>$_SERVER['PHP_SELF'], "name"=> $nameTools);
            $nameTools = get_config_name($config_code);
        }
        
        $storedPropertyList = readValueFromTblConf($config_code);
        
        if (is_array($storedPropertyList)&& count($storedPropertyList)>0)
        {
            $generatorFile = realpath(__FILE__);
            if (strlen($generatorFile)>50) 
            {
                $generatorFile = str_replace("\\","/",$generatorFile);
                $generatorFile = "\n\t\t".str_replace("/","\n\t\t/",$generatorFile);
            }
            $fileHeader = '<?php '."\n"
                        . '/* '
                        . 'DONT EDIT THIS FILE - NE MODIFIEZ PAS CE FICHIER '."\n"      
                        . '-------------------------------------------------'."\n"      
                        . 'Generated by '.$generatorFile.' '."\n"      
                        . 'User UID:'.$_uid.' '.str_replace("array (","",str_replace("'","",str_replace('=>',"\t",var_export($_user,1))))."\n"      
                        . 'Date '.claro_disp_localised_date($dateTimeFormatLong)."\n"      
                        . '-------------------------------------------------'."\n"      
                        . 'DONT EDIT THIS FILE - NE MODIFIEZ PAS CE FICHIER '."\n"      
                        . ' */'."\n\n"
                        . '$'.$config_code.'GenDate = "'.time().'";'."\n\n"
                        . (isset($conf_def['technicalInfo'])
                        ? '/*'
                        . str_replace('*/', '* /', $conf_def['technicalInfo'])
                        . '*/'
                        : '')
                        ;
        
            $handleFileConf = fopen($confFile,'w');
            fwrite($handleFileConf,$fileHeader);
            
            
            foreach($storedPropertyList as $storedProperty)
            {
                $valueToWrite  = $storedProperty['propValue']; 
                $container     = $conf_def_property_list[$storedProperty['propName']]['container'];
                $description   = $conf_def_property_list[$storedProperty['propName']]['$description'];
                if ($conf_def_property_list[$storedProperty['propName']]['type']!='boolean') 
                {
                    $valueToWrite = "'".$valueToWrite."'";   
                }
                if(strtoupper($container)=='CONST')
                {
                    $propertyLine = 'define("'.$storedProperty['propName'].'",'.$valueToWrite.');'."\n";
                }
                else
                {
                    $propertyLine = '$'.$storedProperty['propName'].' = '.$valueToWrite.';'."\n";
                }
                
                $propertyDesc = (isset($description)
                                ?'/* '.$storedProperty['propName'].' : '.str_replace("\n","",$description).' */'."\n"
                                : (isset($conf_def_property_list[$storedProperty['propName']]['label'])
                                  ?'/* '.$storedProperty['propName'].' : '.str_replace("\n","",$conf_def_property_list[$storedProperty['propName']]['label']).' */'."\n"
                                  :''
                                  )
                                );
                $propertyDesc .= ( isset($conf_def_property_list[$storedProperty['propName']]['technicalInfo'])
                        ? '/*'."\n"
                        . str_replace('*/', '* /', $conf_def_property_list[$storedProperty['propName']]['technicalInfo'])
                        . '*/'."\n"
                        : '' )
                        ;
        
                $propertyGenComment = '// Update on '
                                     .claro_disp_localised_date($dateTimeFormatLong,$storedProperty['lastChange'])
                                     ."\n"."\n"
                                     ;
        
                fwrite($handleFileConf,$propertyLine);
                fwrite($handleFileConf,$propertyDesc);
                fwrite($handleFileConf,$propertyGenComment);
        
            }
            fwrite($handleFileConf,"\n".'?>');
            fclose($handleFileConf);
            $hashConf = md5_file($confFile);
            $sql =' UPDATE `'.$tbl_config_file.'`          '
                 .' SET config_hash = "'.$hashConf.'"      '
                 .' WHERE config_code = "'.$config_code.'" ';
            $controlMsg['debug'][] = '<tt>'.$sql.'</tt>';        
            
            if (!claro_sql_query_affected_rows($sql))
            {
                $sql =' INSERT  INTO `'.$tbl_config_file.'`          '
                     .' SET config_hash = "'.$hashConf.'"      '
                     .' , config_code = "'.$config_code.'" ';
                claro_sql_query($sql);
            }
            $controlMsg['info'][] = 'Properties for '.$nameTools.' ('.$config_code.') are now effective on server.';
            $controlMsg['debug'][] = 'file generated for <B>'.$config_code.'</B> is <em>'.$confFile.'</em>'.'<br>Signature : <TT>'.$hashConf.'</tt>';        
            $panel = DISP_LIST_CONF;
        }
        else 
        {
            $controlMsg['info'][] = 'No Properties for '.$nameTools.' ('.$config_code.'). <BR>
 <em>'.$confFile.'</em> is not generated';        
            $panel = DISP_LIST_CONF;
       
        }
    }
}

/* ************************************************************************** */
//    PREPARE VIEW   
/* ************************************************************************** */

if ($panel == DISP_LIST_CONF)
{
    // List is combination of 2 sources
    // * List of definition files. each one corresponding to a config file. 
    // * List of Tools wich are linked to less a config.
    // the two lists are merge and an array is build  with conf by tool.
    // 
    
    
    
    $helpSection = 'help_config_menu.php';

    $def_list  = get_def_list();
    $conf_list = get_conf_list();
    $key_list = array_merge_recursive($def_list,$conf_list);
    $config_list = array();
    if (is_array($key_list))
    foreach($key_list as $key => $config)
    {
        // The strange following line flat the array 
        // wich can be build by collision during array_merge_recursive 
        $config['config_code'] = (is_array($config['config_code'])?$config['config_code'][0]:$config['config_code']);
        
//        $controlMsg['info'][] =  var_dump::display($config,1);
        $config_item = array_merge($def_list[$key],$conf_list[$key]);
        $config_item['manual_edit'] = (bool) (file_exists(claro_get_conf_file($config['config_code']))&&$config['config_hash'] != md5_file(claro_get_conf_file($config['config_code'])));
        $config_item['tool']        = get_tool_name($config['claro_label']);
        if(!isset($config['claro_label'])) $config['claro_label'] = 'Not for a tool';
        $tool_list[$config['claro_label']][]= $config_item;
    }
}
elseif ($panel == DISP_EDIT_CONF_CLASS)
{
    require($confDef);
    $interbredcrump[] = array ("url"=>$_SERVER['PHP_SELF'], "name"=> $lang_config_config);
    $nameTools = get_config_name($config_code);
    
    $sql_get_conf_info = 'SELECT `cfg`.`config_code` `config_code`, 
                                 `cfg`.`config_hash` `config_hash`,  
                                 `r_t_cfg`.*, 
                                 `r_t_cfg`.`claro_label` `claro_label`, 
                                 `t`.`icon` `icon`
                                 
                          FROM `'.$tbl_config_file.'` `cfg`
                          LEFT JOIN `'.$tbl_rel_tool_config.'` `r_t_cfg`

                           ON `cfg`.`config_code` = `r_t_cfg`.`config_code` 
                          LEFT JOIN `'.$tbl_tool.'` `t`
                           ON `t`.`claro_label`  = `r_t_cfg`.`claro_label`
                           
                           WHERE `cfg`.config_code = "'.$config_code.'"';    

    
    $conf_info = claro_sql_query_fetch_all($sql_get_conf_info);
    
    $is_manual_edit = (bool) (file_exists(claro_get_conf_file($config_code))&&$conf_info[0]['config_hash'] != md5_file(claro_get_conf_file($config_code)));
    $controlMsg['debug'][] = '<small>'
                             .$conf_info[0]['config_hash'].'<BR>'
                             .claro_get_conf_file($config_code).' : '
                             .md5_file(claro_get_conf_file($config_code))
                             .'</small>'
                             ;        

    if ($is_manual_edit)
    {
        $controlMsg['info'][] = 'The config file has manually change.<BR>'
                               .'<BR>'
                               .'Actually the script prefill with values found in the database, and overwrite effective values'
                               ;        
        $currentConfContent = parse_config_file(basename(claro_get_conf_file($config_code)));
        $controlMsg['info'][] = Var_Dump::display($currentConfContent,1);        

    }
    $storedPropertyList = readValueFromTblConf($config_code);
    if (is_array($storedPropertyList))
    {
        foreach($storedPropertyList as $storedProperty)
        {
            $conf_def_property_list[$storedProperty['propName']]['actualValue'] = $storedProperty['propValue']; 
        }
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

//unset($controlMsg['debug']);
if (!empty($controlMsg))
{
    claro_disp_msg_arr($controlMsg);
}

// OUTPUT

switch ($panel)
{
    case DISP_LIST_CONF : 
        echo '<table class="claroTable" cellspacing="4" >'
            .'<thead>'
            .'<tr class="headerX"  >'
            .'<th  colspan="2">'.$langConfig.'</th>'
            .(CONF_AUTO_APPLY_CHANGE?'<th colspan="2">'.$langEdit.'</th>'
                                    :'<th>'.$langEdit.'</th>'
                                    .'<th>'.$langApply.'</th>')
            .'</tr>'
            .'</thead>'
            ;
        asort($tool_list);
        foreach($tool_list as $claro_label => $tool_bloc)
        {
            echo '<tr class="tool_bloc" >'
                .'<td>'
                .'<img src="'.$imgRepositoryWeb.$tool_bloc[0]['icon'].'">'
                .'</td>'
                .'<td>'
                .'<b>'.get_tool_name(rtrim($claro_label,'_')).'</b>'
                .'</td>'
                .'</tr>'
                ;
            //echo '<tr class="tool_bloc" >'
            //    .'<td colspan="3">'.Var_Dump::display($tool_bloc).'</td>'
            //    .'</tr>'
            //    ;
            
            foreach($tool_bloc as $numconf => $config)
            {
                echo '<tr>'
                    .'<td>'
                    //.($numconf+1)
                    .'</td>'
                    .'<td>'
                    .($config['conf']
                        ?'<a href="'.$_SERVER['PHP_SELF'].'?cmd=showConf&amp;config_code='.$config['config_code'].'" >'.$config['name'].'</a>'
                        : $config['name']
                     )
                    .'</td>'
                    ;
                
                if (!$config['def'])
                {
                    echo '<td colspan="2" >'
                        .'<strike>'.$langEdit.'</strike>'
                        .'</td>'
                        ;
                }
                else 
                {
                    echo '<td>'
                        .'<a href="'.$_SERVER['PHP_SELF']
                        .'?cmd=dispEditConfClass&amp;config_code='.$config['config_code'].'" >'
                        .'<img src="'.$clarolineRepositoryWeb.'img/edit.gif" border="0" alt="'.$langEdit.'">'
                        .'</a>'
                        .($config['manual_edit']
                        ?($config['propQtyInDb']['qty_values']>0
                        ?'<BR><span>'.$lang_the_active_config_has_manually_change.'</span>'
                        :'<BR>config de l\'ancien syst�me en production'):'')
                        .'</td>';
                    if (!CONF_AUTO_APPLY_CHANGE)
                    echo '<td>'
                        . ( $config['propQtyInDb']['qty_values']>0
                          ? ( $config['propQtyInDb']['qty_new_values']>0
                             ? '<a href="'.$_SERVER['PHP_SELF']
                              .'?cmd=generateConf&amp;config_code='.$config['config_code'].'" >'
                              .'<img src="'.$clarolineRepositoryWeb.'img/download.gif" border="0" alt="'.$langSave.'">'
                              .'<br>(<small>'.$config['propQtyInDb']['qty_new_values'].' new values</small>)'
                              .'</a>'
                             : $langApplied
                             )
                          : '<small>'
                           .$langNoPropertiesSet
                           .'</small>'
                          )
                       .'</td>';
                }
                echo '</tr>';
            }
        }
        echo '</table>';
        break;
    case DISP_EDIT_CONF_CLASS : 

        if (is_array($conf_def))
        {
            echo (isset($conf_def['description'])?'<div class="toolDesc">'.$conf_def['description'].'</div><br />':'')                
                .'<em><small><small>'.$confDef.'</small></small></em>'
                .'<form method="POST" action="'.$_SERVER['PHP_SELF'].'" name="editConfClass">'."\n"
                //.'<input type="hidden" value="dispEditConfClass" name="cmd">'."\n"
                .'<input type="hidden" value="'.$config_code.'" name="config_code">'."\n"
                ;
            if (is_array($conf_def['section']) ) 
            {
                foreach($conf_def['section'] as $section)
                {
                    echo '<fieldset>'."\n"
                        .'<legend>'.$section['label'].'</legend>'."\n"
                        .($section['description']
                         ?'<div class="sectionDesc">'.$section['description'].'</div><br/>'
                         :'')
                        ."\n"
                        ;
//                  The default value is show in input or preselected value if there is no value set.
//                  If a value is already set the default value is show as sample.
                    if (is_array($section['properties']))
                    foreach($section['properties'] as $property )
                    if (is_array($conf_def_property_list[$property]))
                    {
                        $htmlPropDesc = ($conf_def_property_list[$property]['description']?'<div class="propDesc">'.nl2br(htmlentities($conf_def_property_list[$property]['description'])).'</div><br />':'');
                        $htmlPropName = 'prop['.($property).']';
                        $htmlPropLabel = (isset($conf_def_property_list[$property]['label'])?htmlentities($conf_def_property_list[$property]['label']):$htmlPropName);
                        $htmlPropValue = isset($conf_def_property_list[$property]['actualValue'])?$conf_def_property_list[$property]['actualValue']:$conf_def_property_list[$property]['default'];
                        $size = (int) strlen($htmlPropValue);
                        $size = 2+(($size > 90)?90:(($size < 8)?8:$size));
                        $htmlPropDefault = isset($conf_def_property_list[$property]['actualValue'])?'<span class="default"> Default : '.$conf_def_property_list[$property]['default'].'</span><br />':'<span class="firstDefine">!!!First definition of this value!!!</span><br />';
            
                        if (isset($conf_def_property_list[$property]['display']) 
                               &&!$conf_def_property_list[$property]['display']) 
                        {
                            echo '<input type="hidden" value="'.$htmlPropValue.'" name="'.$htmlPropName.'">'."\n";
                        } 
                        elseif ($conf_def_property_list[$property]['readonly']) 
                        {
                            echo '<H2>'
                                .$htmlPropLabel
                                .'</H2>'."\n"
                                .$htmlPropDesc."\n"
                                .'<span>'
                                ;
                            switch($conf_def_property_list[$property]['type'])
                            {
                           	    case 'boolean' : 
                       	        case 'enum' : 
                                    echo (isset($conf_def_property_list[$property]['acceptedValue'][$htmlPropValue])?$conf_def_property_list[$property]['acceptedValue'][$htmlPropValue]:$htmlPropValue);
                            		break;
                           	    case 'integer' : 
                       	        case 'string' : 
                             	default:
                                	// probably a string or integer
                                    echo $conf_def_property_list[$property]['default'];
                        } // switch
                        echo '</span>'."\n"
                            .'<input type="hidden" value="'.$htmlPropValue.'" name="'.$htmlPropName.'">'."\n";
                        } 
                        else
                        // Prupose a form following the type 
                        switch($conf_def_property_list[$property]['type'])
                        {
                       	    case 'boolean' : 
                                $htmlPropDefault = isset($conf_def_property_list[$property]['actualValue'])
                                                   ?'<span class="default"> Default : '
                                                   .($conf_def_property_list[$property]['acceptedValue'][$conf_def_property_list[$property]['default']]?$conf_def_property_list[$property]['acceptedValue'][$conf_def_property_list[$property]['default']]:$conf_def_property_list[$property]['default'] )
                                                   .'</span><br />'
                                                   :'<span class="firstDefine">!!!First definition of this value!!!</span><br />'
                                                   ;
                                echo '<H2>'
                                    .$htmlPropLabel
                                    .'</H2>'."\n"
                                    .$htmlPropDesc."\n"
                                    .$htmlPropDefault."\n"
                                    .'<span>'
                                    .'<input id="'.$property.'_TRUE"  type="radio" name="'.$htmlPropName.'" value="TRUE"  '.($htmlPropValue=='TRUE'?' checked="checked" ':' ').' >'
                                    .'<label for="'.$property.'_TRUE"  >'
                                    .($conf_def_property_list[$property]['acceptedValue']['TRUE' ]?$conf_def_property_list[$property]['acceptedValue']['TRUE' ]:'TRUE' )
                                    .'</label>'
                                    .'</span>'."\n"
                                    .'<span>'
                                    .'<input id="'.$property.'_FALSE" type="radio" name="'.$htmlPropName.'" value="FALSE" '.($htmlPropValue=='TRUE'?' ':' checked="checked" ').' ><label for="'.$property.'_FALSE" >'.($conf_def_property_list[$property]['acceptedValue']['FALSE']?$conf_def_property_list[$property]['acceptedValue']['FALSE']:'FALSE').'</label></span>'."\n"
                                    ;
                        		break;
                       	    case 'enum' : 
                                $htmlPropDefault = isset($conf_def_property_list[$property]['actualValue'])
                                                   ?'<span class="default"> Default : '
                                                   .($conf_def_property_list[$property]['acceptedValue'][$conf_def_property_list[$property]['default']]?$conf_def_property_list[$property]['acceptedValue'][$conf_def_property_list[$property]['default']]:$conf_def_property_list[$property]['default'] )
                                                   .'</span><br />'
                                                   :'<span class="firstDefine">!!!First definition of this value!!!</span><br />'
                                                   ;
                                echo '<H2>'
                                    .$htmlPropLabel
                                    .'</H2>'."\n"
                                    .$htmlPropDesc."\n"
                                    .$htmlPropDefault."\n";
                                foreach($conf_def_property_list[$property]['acceptedValue'] as  $keyVal => $labelVal)
                                {
                                    echo '<span>'
                                        .'<input id="'.$property.'_'.$keyVal.'"  type="radio" name="'.$htmlPropName.'" value="'.$keyVal.'"  '.($htmlPropValue==$keyVal?' checked="checked" ':' ').' >'
                                        .'<label for="'.$property.'_'.$keyVal.'"  >'.($labelVal?$labelVal:$keyVal ).'</label>'
                                        .'</span>'
                                        .'<br>'."\n";
                                }   
                        		break;
                        		
//TYPE : integer, an integer is attempt
                        	case 'integer' : 
                                $htmlPropDefault = isset($conf_def_property_list[$property]['actualValue'])?'<span class="default"> Default : '.$conf_def_property_list[$property]['default'].'</span><br />':'<span class="firstDefine">!!!First definition of this value!!!</span><br />';
                                echo '<H2>'
                                    .'<label for="'.$property.'">'
                                    .$conf_def_property_list[$property]['label']
                                    .'</label>'
                                    .'</H2>'."\n"
                                    .'<br>'."\n"
                                    .$htmlPropDesc."\n"
                                    .$htmlPropDefault."\n"
                                    .'<input size="'.$size.'"  align="right" id="'.$property.'" type="text" name="'.$htmlPropName.'" value="'.$htmlPropValue.'"> '.$conf_def_property_list[$property]['type']."\n"
                                    .'<br>'
                                    ;
                        		;
                        		break;
                        	default:
                        	// probably a string
                                $htmlPropDefault = isset($conf_def_property_list[$property]['actualValue'])?'<span class="default"> Default : '.$conf_def_property_list[$property]['default'].'</span><br />':'<span class="firstDefine">!!!First definition of this value!!!</span><br />';
                                echo '<h2>'."\n"
                                    .'<label for="'.$property.'">'
                                    .$conf_def_property_list[$property]['label']
                                    .'</label>'."\n"
                                    .'</h2>'."\n"
                                    .$htmlPropDesc."\n"
                                    .$htmlPropDefault."\n"
                                    .'<input size="'.$size.'"  id="'.$property.'" type="text" name="'.$htmlPropName.'" value="'.$htmlPropValue.'"> '.$conf_def_property_list[$property]['type']."\n"
                                    .'<br>'."\n"
                                    ;
                        		;
                        } // switch
                    }
                    else 
                    {
                        echo 'Def corrupted: property '.$property.' is not defined';
                    }
                echo '</fieldset>';
                }
                if (CONF_AUTO_APPLY_CHANGE)
                {
                    echo '<input type="submit" name="cmdSaveAndApply" value="Save" >';
                }
                else 
                {
                    echo '<input type="submit" name="cmdSaveAndApply" value="Save and Apply" >'
                        .'<input type="submit" name="cmdSaveProperties" value="Save without apply" >'
                        ;
                }
                echo '<input type="hidden" name="cmd" value="cmdSaveProperties" >'
                    .'</form>'."\n"
                    ;
                   
            }
            else
            {
                echo 'no section found in definition file';                                
            }
    }
    else
    {
        echo '<div >nothing to edit in '.$tool. '</div>';
    }

        break;

    case DISP_SHOW_CONF : 
        echo '<div>'
            .'[<a href="'.$_SERVER['PHP_SELF'].'?cmd=showConfFile&amp;config_code='.$config_code.'" >'.$langShowContentFile.'</a>]'
            .'[<a href="'.$_SERVER['PHP_SELF'].'" >'
            .$langBackToMenu
            .'</a>]</div>'
            ;
        if (is_array($conf_def))
        {
            echo (isset($conf_def['description'])?'<div class="toolDesc">'.$conf_def['description'].'</div><br />':'')                
                .'<em><small><small>'.$confDef.'</small></small></em>'
                ;
            if (is_array($conf_def['section']) ) 
            {
                foreach($conf_def['section'] as $section)
                {
                    echo '<FIELDSET>'
                        .'<LEGEND>'.$section['label'].'</LEGEND>'."\n"
                        .($section['description']?'<div class="sectionDesc">'.$section['description'].'</div><br />':'')."\n"
                        ;
                    if (is_array($section['properties']))
                    foreach($section['properties'] as $property )
                    {
                        $htmlPropLabel = htmlentities($conf_def_property_list[$property]['label']);
                        $htmlPropDesc = ($conf_def_property_list[$property]['description']?'<div class="propDesc">'.nl2br(htmlentities($conf_def_property_list[$property]['description'])).'</div><br />':'');
                        if ($conf_def_property_list[$property]['container']=='CONST')
                             eval('$htmlPropValue = '.$property.';');
                        else eval('$htmlPropValue = $'.$property.';');
                        $htmlUnit = ($conf_def_property_list[$property]['unit']?''.htmlentities($conf_def_property_list[$property]['unit']):'');
                        echo '<H2>'
                            .$htmlPropLabel 
                            .'('.$conf_def_property_list[$property]['type'].')'
                            .'</H2>'."\n"
                            .$htmlPropDesc."\n"
                            .'<em>'.$property.'</em>: '
                            .'<strong>'.var_export($htmlPropValue,1).'</strong> '.$htmlUnit.'<br>'."\n"
                            ;
                    } // foreach($section['properties'] as $property )
                    echo '</FIELDSET><br>'."\n";
                } //foreach($conf_def['section'] as $section)
            }
            else
            {
                echo 'no section found in definition file';                                
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

//var_dump::display($config_list);
//var_dump::display($conf_def);
//var_dump::display($conf_def_property_list);
include($includePath."/claro_init_footer.inc.php");
?>