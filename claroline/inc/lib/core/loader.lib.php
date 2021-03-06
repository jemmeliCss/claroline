<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * CLAROLINE
 *
 * Loader classes for CSS and Javascript.
 *
 * @version     Claroline 1.12 $Revision$
 * @copyright   (c) 2001-2014, Universite catholique de Louvain (UCL)
 * @author      Claroline Team <info@claroline.net>
 * @author      Frederic Minne <zefredz@claroline.net>
 * @license     http://www.gnu.org/copyleft/gpl.html
 *              GNU GENERAL PUBLIC LICENSE version 2 or later
 * @package     kernel.core
 */

/**
 * Javascript loader singleton class
 */
class JavascriptLoader
{
    private static $instance = false;

    private $libraries, $pathList;

    private function __construct()
    {
        $this->libraries = array();
        $this->pathList = array();
    }
    
    /**
     * Get the list of loaded libraries
     * @return array
     */
    public function getLibraries()
    {
        return $this->libraries;
    }
    
    /**
     * Get the list of the name of the loaded libraries
     * @return array
     */
    public function loadedLibraries()
    {
        return array_keys( $this->libraries );
    }

    /**
     * Load a javascript source file
     * @param   string lib javascript lib url relative to one of the
     *  declared javascript paths
     * @return  boolean true if the library was found, false else
     */
    public function load( $lib )
    {
        $this->pathList = array(
            get_module_path( get_current_module_label() ) . '/js' => get_module_url( get_current_module_label() ) . '/js',
            get_path( 'rootSys' ) . 'web/js' => get_path('url') . '/web/js',
            './js' => './js'
        );
        
        $lib = secure_file_path( $lib );
        
        foreach ( $this->pathList as $tryPath => $tryUrl )
        {
            /*if ( claro_debug_mode() )
            {
                pushClaroMessage(__Class__."::Try to find {$lib} in {$tryPath}", 'debug');
            }*/
            
            
            if ( file_exists ( $tryPath . '/' . $lib . '.js' ) )
            {
                if ( array_key_exists( $tryPath . '/' . $lib . '.js', $this->libraries ) )
                {
                    return false;
                }
                
                $mtime = '';
               

                $this->libraries[$tryPath . '/' . $lib . '.js'] = $tryUrl . '/' . $lib . '.js';

                $mtime = filemtime($tryPath . '/' . $lib . '.js');
                
                ClaroHeader::getInstance()->addHtmlHeader(
                    '<script src="'.$this->libraries[$tryPath . '/' . $lib . '.js'].'?'.$mtime.'" type="text/javascript"></script>'
                );
                
                return true;
            }
        }

        if ( claro_debug_mode() )
        {
            pushClaroMessage(__Class__."::NotFound ".$lib.'.js', 'error');
        }

        return false;
    }
    
    /**
     * Load module javascript libraries
     * @param string $moduleLabel
     * @param string $lib
     * @return boolean
     */
    public function loadFromModule( $moduleLabel, $lib )
    {
        $lib = secure_file_path( $lib );
        
        /*if ( claro_debug_mode() )
        {
            pushClaroMessage(__Class__."::Try to find {$lib} in {$moduleLabel}", 'debug');
        }*/
        
        $path = get_module_path( $moduleLabel ) . '/js/' . $lib . '.js';
        $url = get_module_url( $moduleLabel ) . '/js/' . $lib . '.js';
        
        if ( file_exists( $path ) )
        {
            if ( array_key_exists( $path, $this->libraries ) )
            {
                return false;
            }
            
            $this->libraries[$path] = $url;
            
            /* if ( claro_debug_mode() )
            {
                pushClaroMessage(__Class__."::Use {$path}::{$url}", 'debug');
            } */
            
            ClaroHeader::getInstance()->addHtmlHeader(
                '<script src="'.$url.'?'.filemtime($path).'" type="text/javascript"></script>'
            );
            
            return true;
        }
        else
        {
            if ( claro_debug_mode() )
            {
                pushClaroMessage(__Class__."::Cannot found {$lib} in {$moduleLabel}", 'error');
            }
            
            return false;
        }
    }
    
    /**
     * Get an instance of the loader
     * @return JavascriptLoader
     */
    public static function getInstance()
    {
        if ( ! JavascriptLoader::$instance )
        {
            JavascriptLoader::$instance = new JavascriptLoader;
        }

        return JavascriptLoader::$instance;
    }
}

/**
 * CSS loader class
 */
class CssLoader
{
    private static $instance = false;

    private $css, $pathList;

    private function __construct()
    {
        $this->css = array();
        $this->pathList = array();
    }
    
    /**
     * Get the loaded css
     * @return type
     */
    public function getCss()
    {
        return $this->css;
    }
    
    /**
     * Get a list of the names of the loaded css
     * @return type
     */
    public function loadedCss()
    {
        return array_keys( $this->css );
    }

    /**
     * Load a css file
     * @param   string lib css file url relative to one of the
     *  declared css paths
     * @return  boolean true if the library was found, false if not
     */
    public function load( $css, $media = 'all' )
    {
        $this->pathList = array(
            get_path('rootSys') . 'platform/css/' . get_current_module_label()
                => get_path('url') . '/platform/css/' . get_current_module_label(),
            get_module_path( get_current_module_label() ) . '/css'
                => get_module_url( get_current_module_label() ) . '/css',
            get_path('rootSys') . 'platform/css'
                => get_path('url') . '/platform/css', // <-- is this useful or not ?
            get_path( 'rootSys' ) . 'web/css'
                => get_path('url') . '/web/css',
            './css' => './css'
        );
        
        $css = secure_file_path( $css );

        foreach ( $this->pathList as $tryPath => $tryUrl )
        {
            /* if ( claro_debug_mode() )
            {
                pushClaroMessage(__Class__."::Try ".$tryPath.'/'.$css.'.css', 'debug');
            } */

            if ( file_exists ( $tryPath . '/' . $css . '.css' ) )
            {
                if ( array_key_exists( $tryPath . '/' . $css . '.css', $this->css ) )
                {
                    return false;
                }
                
                /* if ( claro_debug_mode() )
                {
                    pushClaroMessage(__Class__."::Use ".$tryPath.'/'.$css.'.css', 'debug');
                } */

                $this->css[$tryPath . '/' . $css . '.css'] = array(
                    'url' => $tryUrl . '/' . $css . '.css' . '?' . filemtime($tryPath . '/' . $css . '.css'),
                    'media' => $media
                );
                
                ClaroHeader::getInstance()->addHtmlHeader(
                    '<link rel="stylesheet" type="text/css"'
                    . ' href="'. $this->css[$tryPath . '/' . $css . '.css']['url'].'"'
                    . ' media="'.$this->css[$tryPath . '/' . $css . '.css']['media'].'" />'
                );

                return true;
                // break;
            }
        }

        if ( claro_debug_mode() )
        {
            pushClaroMessage(__Class__."::NotFound ".$css.'.css', 'error');
        }

        return false;
    }
    
    /**
     * Load a css from a module
     * @param string $moduleLabel
     * @param string $lib name of the css
     * @param media $media media to which the css applies
     * @return boolean
     */
    public function loadFromModule( $moduleLabel, $lib, $media = 'all' )
    {
        $lib = secure_file_path( $lib );
        $moduleLabel = secure_file_path( $moduleLabel );

        if ( ! get_module_data( $moduleLabel ) )
        {
            pushClaroMessage(__Class__."::{$moduleLabel} does not exists", 'error');
            return false;
        }
        
        if ( claro_debug_mode() )
        {
            pushClaroMessage(__Class__."::Try to find {$lib} for {$moduleLabel}", 'debug');
        }

        $cssPath = array(
            0 => array(
                'path' => get_path('rootSys') . 'platform/css/' . $moduleLabel . '/' . $lib . '.css',
                'url' => get_path('url') . '/platform/css/' . $moduleLabel . '/' . $lib . '.css',
            ),
            1 => array(
                'path' => get_module_path( $moduleLabel ) . '/css/' . $lib . '.css',
                'url' => get_module_url( $moduleLabel ) . '/css/' . $lib . '.css'
            )
        );
        
        /*$path = get_module_path( $moduleLabel ) . '/css/' . $lib . '.css';
        $url = get_module_url( $moduleLabel ) . '/css/' . $lib . '.css';*/

        foreach ( $cssPath as $cssTry )
        {
            $path = $cssTry['path'];
            $url = $cssTry['url'];

            /*if ( claro_debug_mode() )
            {
                pushClaroMessage(__Class__."::Try {$path}::{$url} for {$moduleLabel}", 'debug');
            }*/

            if ( file_exists( $path ) )
            {
                if ( array_key_exists( $path, $this->css ) )
                {
                    return false;
                }

                $this->css[$path] = array(
                    'url' => $url . '?' . filemtime($path),
                    'media' => $media
                );

                /*if ( claro_debug_mode() )
                {
                    pushClaroMessage(__Class__."::Use {$path}::{$url} for {$moduleLabel}", 'debug');
                }*/

                ClaroHeader::getInstance()->addHtmlHeader(
                    '<link rel="stylesheet" type="text/css"'
                    . ' href="'. $url.'"'
                    . ' media="'.$media.'" />'
                );

                return true;
            }
            else
            {
                // continue
            }
        }
        
        if ( claro_debug_mode() )
        {
            pushClaroMessage(__Class__."::Cannot found css {$lib} for {$moduleLabel}", 'error');
        }

        return false;
    }
    
    /**
     * Get an instance of the css loader
     * @return CssLoader
     */
    public static function getInstance()
    {
        if ( ! CssLoader::$instance )
        {
            CssLoader::$instance = new CssLoader;
        }

        return CssLoader::$instance;
    }
}
