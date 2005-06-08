<?php // $Id$
//----------------------------------------------------------------------
// CLAROLINE
//----------------------------------------------------------------------
// Copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

    require_once dirname(__FILE__) . '/resolver.lib.php';
    require_once dirname(__FILE__) . '/../inc/lib/claro_utils.lib.php';

   /**
    * Class DescriptionResolver 
    *
    *  
    *
    * @author Fallier Renaud
    **/
    class CLDSC___Resolver extends Resolver 
    {
        /*-------------------------
                 variable
         ------------------------*/
        var $_basePath;

        /*----------------------------
                public method
        ---------------------------*/

        /**
        * Constructor
        *
        * @param  $basePath string path root directory of courses 
        */
        function CLDSC___Resolver($basePath)
        {
            $basePath = preg_replace( '~/$~', "", $basePath );
            $this->_basePath = $basePath; 
        }

        /**
        * translated a crl into valid URL for the forum tool
        *
        * @param  $CRL string a crl
        * @return string a url valide who corresponds to the crl
        * @throw E_USER_ERROR if tool_name is empty
        * @throw E_USER_ERROR if it isn't for tool exercice
        * @throw E_USER_ERROR if the crl is empty     
        */
        function resolve($crl)
        {
           if($crl)
           {
                if(CRLTool::isForThisTool($crl,'CLDSC___'))
               {    
                   $elementCRLArray = CRLTool::parseCRL($crl);
                   $url = $this->_basePath . '/claroline/course_description/';
                   
                   if( isset($elementCRLArray["tool_name"])  )
                   {
                       $url .= "index.php?cidReq={$elementCRLArray['course_sys_code']}";    
                       return $url;
                   }
                   else
                   {
                       trigger_error("ERROR: crl not valide ",E_USER_ERROR);
                   }
               }
               else
               {
                   trigger_error("ERROR: isn't for tool exercice",E_USER_ERROR);
               }
           }
           else
           {
               trigger_error("ERROR: crl is required",E_USER_ERROR);
           }     
        }

        /**
        * the name of the resource which will be posted
        *
        * @param $crl a string who cotains the crl
        * @return string who contains the name of the resource
        * @global $_courseToolList
        * @throw  E_USER_ERROR if it isn't for tool exercice
        **/
        function getResourceName($crl)
        {
            if(CRLTool::isForThisTool($crl,'CLDSC___'))
            {    
                $elementCRLArray = CRLTool::parseCRL($crl);
                if( isset($elementCRLArray['resource_id']) )
                {
                    $title  = get_toolname_title( $elementCRLArray );
                    $title .= " > ". stripslashes($this->getTitle($elementCRLArray['course_sys_code'],$elementCRLArray['resource_id']));    
                }

                return $title;
            }
            else
            {
                trigger_error("Error: missing resource id for course desription",E_USER_ERROR);    
            }                      
        }

        /**
        * FIXME use same field name for title in DB tables
        *
        * @param  $course_sys_code identifies a course in data base    
        * @param  $id integer who identifies the exercice
        * @return the title of a annoncement
        */
        function _getInfo( $course_sys_code , $id )
        {
            $courseInfoArray = get_info_course($course_sys_code); 
            $tbl_cdb_names = claro_sql_get_course_tbl($courseInfoArray["dbNameGlu"]);
            $tbl_description = $tbl_cdb_names['course_description'];

            $sql = 'SELECT `id`,`title`,`content` FROM `'.$tbl_description.'` WHERE `id`='.$id;
            $descriptionInfo = claro_sql_query_fetch_all($sql);
                       
            return $descriptionInfo;
        }  

        /**
        *
        * @param  $course_sys_code identifies a course in data base
        * @param  $id integer who identifies the event
        * @return the title of a annoncement
        */ 
        function getTitle( $course_sys_code , $id )
        {        
            $descriptionInfo = $this->_getInfo( $course_sys_code , $id );
                    
            if( strlen($descriptionInfo[0]["title"]) > 0)
            {
                $title = cutstring( $descriptionInfo[0]["title"], 15 , FALSE , 3 ) ;  
            }
            else if( !empty($descriptionInfo[0]["content"])  )
            {    
                $title = cutstring( $descriptionInfo[0]["content"], 15 , FALSE , 3 ) ;  
            }
            else 
            {
                  /*------------------------------
                   *   todo : no name of annonce -
                   *-----------------------------*/
                   $title = "no name";      
               }
               
               return $title; 
        } 
    }
?>