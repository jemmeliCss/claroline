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
// some control for define accepted content.
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
 define("DISP_GENERATE_CONF",  __LINE__);  Build conf file of a tool.
 define("DISP_SHOW_DEF_FILE",  __LINE__);  Display the definition file of a tool
 define("DISP_SHOW_CONF_FILE", __LINE__);  Display the Conf file of a tool

*/

///// CAUTION DEVS ////
///// This script use the PEAR package var_dump
// If you dont have pear, comment these lines 
// and replace Var_Dump::display by Var_Dump

define('CLARO_DEBUG_MODE',TRUE);

$lang_config_config = '�dition des fichiers de configuration';
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

define('DISP_LIST_CONF',        __LINE__);
define('DISP_EDIT_CONF_CLASS',  __LINE__);
define('DISP_GENERATE_CONF',    __LINE__);
define('DISP_SHOW_CONF',        __LINE__);
define('DISP_SHOW_DEF_FILE',    __LINE__);
define('DISP_SHOW_CONF_FILE',   __LINE__);


define('CONF_AUTO_APPLY_CHANGE',FALSE);
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
Var_Dump::displayInit(array('display_mode' => 'HTML4_Text'));

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
            $interbredcrump[] = array ('url'=>$_SERVER['PHP_SELF'], 'name'=> $nameTools);
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
            $interbredcrump[] = array ('url'=>$_SERVER['PHP_SELF'], 'name'=> $nameTools);
            $nameTools = get_config_name($config_code);
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
            $interbredcrump[] = array ('url'=>$_SERVER['PHP_SELF'], 'name'=> $nameTools);
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
            $interbredcrump[] = array ('url'=>$_SERVER['PHP_SELF'], 'name'=> $nameTools);
            $nameTools = get_config_name($config_code);
        }

        //  var_dump::display($_REQUEST['prop']);
        $okToSave = TRUE;
        if ($config_code != $conf_def['config_code'])
        {
            $okToSave = TRUE;
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
                                   lastChange  = now(), 
                                   config_code ="'.$tool.'"
                               WHERE propName    ="'.$propName.'" 
                                 AND config_code ="'.$config_code.'"
                                 AND not (propValue   ="'.$propValue.'") # do not update if same value 
                                 ';
                    }
    //              $controlMsg['info'][] = Var_dump::display($sql,1);
                    
                    claro_sql_query($sql);                 
                }
                else
                {
                    $controlMsg['info'][] = 'Save aborded';
                    $panel = DISP_EDIT_CONF_CLASS;
                }
            }
        }
        else
        {
            $okToSave = FALSE;
        }            
        $controlMsg['info'][] = 'Properties saved in DB. Generate file to apply on your platform';        
    }

    if(   $_REQUEST['cmd']=='generateConf' 
       || ( $okToSave 
            && isset($_REQUEST['cmdSaveAndApply'])))
    {
        if(file_exists($confDef))
        {
            require($confDef);
            $panel = DISP_GENERATE_CONF;
            $interbredcrump[] = array ("url"=>$_SERVER['PHP_SELF'], "name"=> $nameTools);
            $nameTools = get_config_name($config_code);
        }
        else
        {
    		$controlMsg['error'][]=sprintf($lang_nothingToConfigHere,get_tool_name($tool));
            $panel = DISP_LIST_CONF;    
        }
            
        if (!$confFile)
        {
            $confFile = claro_create_conf_filename($config_code);
            $controlMsg['info'][] = sprintf('cr�ation du fichier de configuration :<BR> %s'
                                           ,$confFile);
            $confFile = claro_get_conf_file($config_code);
        }
        
        if(file_exists($confDef))
        {
            require($confDef);
            $panel = DISP_EDIT_CONF_CLASS;
            $interbredcrump[] = array ("url"=>$_SERVER['PHP_SELF'], "name"=> $nameTools);
            $nameTools = get_config_name($config_code);
        }
        
        $storedPropertyList = readValueFromTblConf($config_code);
        
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
        if (!claro_sql_query_affected_rows($sql))
        {
            $sql =' INSERT  INTO `'.$tbl_config_file.'`          '
                 .' SET config_hash = "'.$hashConf.'"      '
                 .' , config_code = "'.$config_code.'" ';
            claro_sql_query($sql);
        }
        $controlMsg['info'][] = 'Properties for '.$nameTools.' ('.$config_code.') are now effective on server.<br />file generated is <em>'.$confFile.'</em>'.'<br>signature : '.$hashConf;        
        $panel = DISP_LIST_CONF;
    }
}

/* ************************************************************************** */
//    PREPARE VIEW   
/* ************************************************************************** */

if ($panel == DISP_LIST_CONF)
{
    $helpSection = 'help_config_menu.php';

    $config_list  = get_def_list();
    $conf_list = get_conf_list();
    if (is_array($conf_list))
    foreach($conf_list as $key => $config)
    {
        $config_list[$key]['manual_edit']                  = (bool) (file_exists(claro_get_conf_file($config['config_code']))&&$config['config_hash'] != md5_file(claro_get_conf_file($config['config_code'])));
        $config_list[$key]['tool']                         = get_tool_name($config['claro_label']);
        $tool_list[$config['claro_label']][]= $config_list[$key];
    }
}
elseif ($panel == DISP_EDIT_CONF_CLASS)
{
    require($confDef);
    $interbredcrump[] = array ("url"=>$_SERVER['PHP_SELF'], "name"=> $lang_config_config);
    $nameTools = get_config_name($config_code);
    
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
            .'<tr class="headerX" >'
            .'<th>'.$langConfig.'</th>'
            .(CONF_AUTO_APPLY_CHANGE?'<th colspan="2">'.$langEdit.'</th>'
                                    :'<th>'.$langEdit.'</th>'
                                    .'<th>'.$langApply.'</th>')
            .'</tr>'
            .'</thead>'
            ;

        foreach($config_list as $config_code => $tool)
        {
            echo '<tr>'
                .'<td>'
                .($tool['conf']
                    ?'<a href="'.$_SERVER['PHP_SELF'].'?cmd=showConf&amp;config_code='.$config_code.'" >'.$tool['name'].'</a>'
                    : $tool['name']
                 )
                .'</td>'
                ;
            
            if (!$tool['def'])
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
                    .'?cmd=dispEditConfClass&amp;config_code='.$config_code.'" >'
                    .'<img src="'.$clarolineRepositoryWeb.'img/edit.gif" border="0" alt="'.$langEdit.'">'
                    .'</a>'
                    .($tool['manual_edit']?'<BR>!!!! version de production modifi�e':'')
                    .'</td>';
                if (!CONF_AUTO_APPLY_CHANGE)
                echo '<td>'
                    . ( $tool['propQtyInDb']['qty_values']>0
                      ? ( $tool['propQtyInDb']['qty_new_values']>0
                         ? '<a href="'.$_SERVER['PHP_SELF']
                          .'?cmd=generateConf&amp;config_code='.$config_code.'" >'
                          .'<img src="'.$clarolineRepositoryWeb.'img/download.gif" border="0" alt="'.$langSave.'">'
                          .'<br>(<small>'.$tool['propQtyInDb']['qty_new_values'].' new values</small>)'
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
        echo '</table>';
//        asort($tool_list);
//        Var_Dump::display($tool_list);
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
                echo '</fieldset>';
                }
                echo '<input type="hidden" name="cmd" value="cmdSaveProperties" >'
                    .'<input type="submit" name="cmdSaveAndApply" value="Save and Apply" >'
                    .'<input type="submit" name="cmdSaveProperties" value="Save without apply" >'
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