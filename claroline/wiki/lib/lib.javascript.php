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
     * @package Wiki
     */

    function document_web_path()
    {
        return "http://" . $_SERVER['HTTP_HOST'] . dirname( $_SERVER['SCRIPT_NAME'] );
    }
    
    function document_sys_path()
    {
        return realpath( str_replace( '\\', '/', $_SERVER['DOCUMENT_ROOT'] ) . dirname( $_SERVER['SCRIPT_NAME'] ) );
    }
    
    // remove from claroline version
    
    function add_check_if_javascript_enabled_js()
    {
        return '<script type="text/javascript">document.cookie="javascriptEnabled=true";</script>';
    }
    
    function is_javascript_enabled()
    {
        return isset( $_COOKIE['javascriptEnabled'] )
            && ( $_COOKIE['javascriptEnabled'] == true )
            ;
    }
?>