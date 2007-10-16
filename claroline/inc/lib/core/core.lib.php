<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    /**
     * Main core library
     *
     * @version     1.9 $Revision$
     * @copyright   2001-2007 Universite catholique de Louvain (UCL)
     * @author      Claroline Team <info@claroline.net>
     * @author      Frederic Minne <zefredz@claroline.net>
     * @license     http://www.gnu.org/copyleft/gpl.html
     *              GNU GENERAL PUBLIC LICENSE version 2.0
     * @package     CORE
     */

    if ( count( get_included_files() ) == 1 )
    {
        die( 'The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead' );
    }
    
    /**
     * Check a value against a list of alowed value
     * @param   mixed value
     * @param   array allowedValueList
     * @return  boolean
     */
    function is_value_allowed( $value, $allowedValueList )
    {
        return in_array( $value, $allowedValueList );
    }
    
    /**
     * Check the type of a value
     * @param   mixed value
     * @param   string type (alnum, alpha, digit, lower, upper, space, xdigit,
     *  float, int, string, array, bool)
     * @return  boolean
     */
    function check_value_type( $value, $type )
    {
        $supportedType = array();

        $supportedType['ctype'] = array( 'alnum'
            , 'alpha', 'digit', 'lower'
            , 'upper', 'space', 'xdigit' );
        $supportedType['phptype'] = array( 'float'
            , 'int', 'string', 'array', 'bool' );

        if ( in_array( $type, $supportedType['ctype'] ) )
        {
            if ( call_user_func( 'ctype_' . $type, $value ) )
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        elseif ( in_array( $type, $supportedType['phptype'] ) )
        {
            switch( $type )
            {
                case 'bool':
                    return is_bool( $value );
                case 'int':
                    return is_integer( $value );
                case 'float':
                    return is_float( $value );
                case 'array':
                    return is_array( $value );
                case 'string':
                    return is_string( $value );
            }
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Protect file path against arbitrary file inclusion
     * @param   string path, untrusted path
     * @return  string secured path
     */
    function protect_against_file_inclusion( $path )
    {
        while ( false !== strpos( $path, '://' )
            || false !== strpos( $path, '..' ) )
        {
            // protect against remote file inclusion
            $path = str_replace( '://', '', $path );
            // protect against arbitrary file inclusion
            $path = str_replace( '..', '.', $path );
        }
            
        return $path;
    }

    /**
     * Imports the PHP libraries given in argument with path relative to
     * includePath or module lib/ directory. .php extension added automaticaly
     * @param   list of libraries
     * @return  array of not found libraries + generate an error in debug mode
     */
    function uses()
    {
        $args = func_get_args();
        $notFound = array();
        
        defined('INCLUDES') || define ( 'INCLUDES', dirname(__FILE__) . '/..');
        
        foreach ( $args as $lib )
        {
            $lib = protect_against_file_inclusion( $lib );
            
            $kernelPath = INCLUDES . '/' . $lib . '.php';
            $localPath = get_module_path(get_current_module_label()) . '/lib/' . $lib . '.php';
            
            if ( file_exists( $localPath ) )
            {
                require_once $localPath;
            }
            elseif ( file_exists( $kernelPath ) )
            {
                require_once $kernelPath;
            }
            else
            {
                // error not found
                if ( claro_debug_mode() ) 
                {
                    trigger_error( "Lib not found $lib", E_USER_ERROR );
                }
                
                $notFound[] = $lib;
            }
        }
        
        return $notFound;
    }
?>