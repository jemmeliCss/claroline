<?php // $Id$
/**
 * CLAROLINE 
 *
 * This file describe the parameter for Course creation tool config file
 *
 * @version 1.6 $Revision$
 *
 * @copyright 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @see http://www.claroline.net/wiki/index.php/Config
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Christophe Gesch� <moosh@claroline.net>
 *
 * @package COURSES
 */

$conf_def['config_code']='CLCRS';
$conf_def['config_name']='Course options';
$conf_def['config_file']='course_main.conf.php';
$conf_def['old_config_file'][]='add_course.conf.php';
$conf_def['config_class']='course';

$conf_def['section']['create']['label']='Course main settings';
$conf_def['section']['create']['description']='';
$conf_def['section']['create']['properties'] = 
array ( 'defaultVisibilityForANewCourse'
      , 'human_code_needed'
      , 'human_label_needed'
      , 'course_email_needed'
      , 'extLinkNameNeeded'
      , 'extLinkUrlNeeded'
      , 'prefixAntiNumber'
      , 'prefixAntiEmpty'
      , 'showLinkToDeleteThisCourse'
      , 'nbCharFinalSuffix'
      );

$conf_def_property_list['defaultVisibilityForANewCourse'] = 
array ('label'       => 'Default visibility for new course'
      ,'description' => 'hide = the course can be acces without subscription to this course.
      open = an authenticated user on the platform can subscribe the course.
      '
      ,'default'     => '2'
      ,'type'        => 'enum'
      ,'display'     => TRUE
      ,'readonly'    => FALSE
      ,'acceptedValue' => array ('0'=>'hide and closed'
                                ,'1'=>'visible and closed'
                                ,'2'=>'visible and open'
                                ,'3'=>'hide and open'
                                )
      );

$conf_def_property_list['human_code_needed'] = 
array ('label'       => 'Course code is'
      ,'description' => 'User can leave course code (officialCode) field empty or not'
      ,'default'     => TRUE
      ,'type'        => 'boolean'
      ,'acceptedValue' => array ('TRUE' => 'Required'
                                ,'FALSE'=> 'Optional'
                                )
      );

$conf_def_property_list['human_label_needed'] = 
array ('label'       => 'Course Title is'
      ,'description' => 'User can leave course title field empty or not'
      ,'default'     => TRUE
      ,'type'        => 'boolean'
      ,'acceptedValue' => array ('TRUE'=>'Required'
                              ,'FALSE'=>'Optional'
                              )
      );

$conf_def_property_list['course_email_needed'] = 
array ('label'       => 'Course email email is'
      ,'description' => 'User can leave email field empty or not'
      ,'default'     => FALSE
      ,'type'        => 'boolean'
      ,'display'     => TRUE
      ,'readonly'    => FALSE
      ,'acceptedValue' => array ('TRUE'=>'Required'
                              ,'FALSE'=>'Optional'
                              )
      );

$conf_def_property_list['extLinkNameNeeded'] = 
array ('label'       => 'External label'
      ,'description' => 'This name is shown on the top right of course banner'
      ,'default'     => FALSE
      ,'type'        => 'boolean'
      ,'display'     => TRUE
      ,'readonly'    => FALSE
      ,'acceptedValue' => array ('TRUE'=>'Required (choose this is Url of external Link is set)'
                              ,'FALSE'=>'Optional'
                              )
      );
$conf_def_property_list['extLinkUrlNeeded'] = 
array ('label'       => 'External Label (url)'
      ,'description' => 'This url anchored into the external label '
      ,'default'     => FALSE
      ,'type'        => 'boolean'
      ,'display'     => TRUE
      ,'readonly'    => FALSE
      ,'acceptedValue' => array ('TRUE'=>'Required'
                              ,'FALSE'=>'Optional'
                              )
      );

$conf_def_property_list['prefixAntiNumber'] = 
array ('label'       => 'Prefix course code beginning with number'
      ,'description' => 'This string is prepend to course database name if it begins with a number'
      ,'default'     => 'No'
      ,'display'     => FALSE
      ,'readonly'    => TRUE
      ,'type'        => 'string'
      );

$conf_def_property_list['prefixAntiEmpty'] = 
array ('label'       => 'Prefix for empty code course'
      ,'default'     => 'Course'
      ,'display'     => FALSE
      ,'readonly'    => TRUE
      ,'type'        => 'string'
      );

$conf_def_property_list['nbCharFinalSuffix'] = 
array ('label'       => 'Lenght of course code suffix'
      ,'technicalInfo'=> 'Lenght of suffix added when key is already exist'
      ,'default'     => 3
      ,'display'     => FALSE
      ,'readonly'    => TRUE
      ,'type'        => 'integer'
      ,'acceptedValue' => array ('min'=> 1
                                ,'max'=> 10)
      );

// Course Setting Section

$conf_def_property_list['showLinkToDeleteThisCourse']
= array ('label'     => 'Course removal allowed'
        ,'description' => 'Allow course manager to delete their own courses'
        ,'default'   => TRUE
        ,'type'      => 'boolean'
        ,'container' => 'VAR'
        ,'acceptedValue' => array ('TRUE'  => 'Yes'
                                  ,'FALSE' => 'No'
                                  )
        );

?>