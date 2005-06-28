<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    if( strtolower( basename( $_SERVER['PHP_SELF'] ) )
        == strtolower( basename( __FILE__ ) ) )
    {
        die("This file cannot be accessed directly! Include it in your script instead!");
    }

    /**
     * @version CLAROLINE 1.7
     *
     * @copyright 2001-2005 Universite catholique de Louvain (UCL)
     *
     * @license GENERAL PUBLIC LICENSE (GPL)
     * This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
     * as published by the FREE SOFTWARE FOUNDATION. The GPL is available
     * through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
     *
     * @author Frederic Minne <zefredz@gmail.com>
     *
     * @package libURL
     */

     /**
      * add a GET request variable to the given URL
      * @param string url url
      * @param string name name of the variable
      * @param string value value of the variable
      * @return string url
      */
    function add_request_variable_to_url( &$url, $name, $value )
    {
        if ( strstr( $url, "?" ) != false )
        {
            $url .= "&amp;$name=$value";
        }
        else
        {
            $url .= "?$name=$value";
        }
        
        return $url;
    }
    
    /**
      * add a GET request variable list to the given URL
      * @param string url url
      * @param array variableList list of the request variables to add
      * @return string url
      */
    function add_request_variable_list_to_url( &$url, $variableList )
    {
        foreach ( $variableList as $name => $value )
        {
            $url = add_request_variable_to_url( $url, $name, $value );
        }
        
        return $url;
    }
?>