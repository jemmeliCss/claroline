<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    /**
     * Description
     *
     * @version     1.9 $Revision$
     * @copyright   2001-2008 Universite catholique de Louvain (UCL)
     * @author      Claroline Team <info@claroline.net>
     * @author      Frederic Minne <zefredz@claroline.net>
     * @license     http://www.gnu.org/copyleft/gpl.html
     *              GNU GENERAL PUBLIC LICENSE version 2 or later
     * @package     display
     */

    if ( count( get_included_files() ) == 1 )
    {
        die( 'The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead' );
    }
    
    uses ( 'core/helpers.lib' );
    
    class PHP_Error_Exception extends Exception
    {
       public function __construct ( $code, $message, $file, $line )
       {
           parent::__construct($message, $code);
           $this->file = $file;
           $this->line = $line;
       }
    }
    
    function exception_error_handler( $code, $message, $file, $line )
    {
        throw new PHP_Error_Exception( $code, $message, $file, $line );
    }
    
    function ob_exception_handler( $e )
    {
        ob_end_clean();
        
        echo '<pre>' . $e->__toString() . '</pre>'; 
    }
    
    class PhpTemplate implements Display
    {
        protected $_templatePath;
        
        public function __construct( $templatePath )
        {
            $this->_templatePath = $templatePath;
        }
        
        public function assign( $name, $value )
        {
            $this->$name = $value;
        }
        
        public function render()
        {
            if ( file_exists( $this->_templatePath ) )
            {
                // set error handlers for output buffering :
                set_error_handler('exception_error_handler');
                set_exception_handler('ob_exception_handler');
                
                ob_start();
                include $this->_templatePath;
                $render = ob_get_contents();
                ob_end_clean();
                
                // restore original error handlers
                restore_exception_handler();
                restore_error_handler();
                
                return $render;
            }
            else
            {
                throw new Exception("Template file not found {$this->templatePath}");
            }
        }
    }
    
    class CoreTemplate extends PhpTemplate
    {
        public function __construct( $templatePath )
        {
            $customTemplatePath = get_path('rootSys') . '/platform/templates/'.$templatePath;
            $defaultTemplatePath = get_path('includePath') . '/templates/'.$templatePath;
            
            if ( file_exists( $customTemplatePath ) )
            {
                parent::__construct( $customTemplatePath );
            }
            elseif ( file_exists( $defaultTemplatePath ) )
            {
                parent::__construct( $defaultTemplatePath );
            }
            else
            {
                throw new Exception("Template not found {$templatePath} "
                    . "at custom location {$customTemplatePath} "
                    . "or default location {$defaultTemplatePath} !");
            }
            
            $this->_initCommonVariables();
        }
        
        private function _initCommonVariables()
        {
            $this->course = claro_get_current_course_data();
            $this->user = claro_get_current_user_data();
        }
        
        public function showBlock( $blockName )
        {
            $this->$blockName = true;
        }
        
        public function hideBlock( $blockName )
        {
            $this->$blockName = false;
        }
    }
?>