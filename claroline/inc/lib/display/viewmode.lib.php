<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * View mode block. Display view mode switch, enrolment link and login link
 *
 * @version     Claroline 1.12 $Revision$
 * @copyright   (c) 2001-2014, Universite catholique de Louvain (UCL)
 * @author      Claroline Team <info@claroline.net>
 * @author      Frederic Minne <zefredz@claroline.net>
 * @license     http://www.gnu.org/copyleft/gpl.html
 *              GNU GENERAL PUBLIC LICENSE version 2 or later
 * @package     kernel.display
 * @since       1.9
 */

require_once __DIR__.'/../course_user.lib.php';

/**
 * Claroline view mode switch, login and enroll links based on context
 * @since Claroline 1.12 the constructor is public
 */
class ClaroViewMode implements Display
{
    protected static $instance = false;
    
    /**
     * Render the view mode switch as HTML
     * @see Display
     * @return string
     */
    public function render()
    {
        $out = '';
        
        if ( !claro_is_user_authenticated() )
        {
            if( get_conf('claro_displayLocalAuthForm',true) == true )
            {
                $out .= $this->renderLoginLink();
            }
        }
        elseif ( ( !claro_is_platform_admin() )
            && ( claro_is_in_a_course() && !claro_is_course_member() )
            && claro_get_current_course_data('registrationAllowed') )
        {
            if (claro_is_current_user_enrolment_pending())
            {
                $out .= '<img src="'.get_icon_url('warning').'" alt="off" /> '
                      . '<b>'.get_lang('Enrolment pending').'</b>';
            }
            else
            {
                $out .= $this->renderRegistrationLink();
            }
        }
        elseif ( claro_is_display_mode_available() )
        {
            $out .= $this->renderViewModeSwitch();
        }
        
        return $out;
    }
    
    /**
     * Render a dropdown list to switch "student" and "course manager" mode.
     */
    private function renderViewModeSwitch()
    {
        $out = '';
        
        if ( isset($_REQUEST['View mode']) )
        {
            $out .= claro_html_tool_view_option($_REQUEST['View mode']);
        }
        else
        {
            $out .= claro_html_tool_view_option();
        }
        
        $out .= "\n";
        
        return $out;
    }
    
    /**
     * Render a link to register.
     */
    private function renderRegistrationLink()
    {
        return '<a href="'
            . claro_htmlspecialchars( get_path('clarolineRepositoryWeb')
                . 'auth/courses.php?cmd=exReg&course='
                . claro_get_current_course_id() )
            . '">'
            . claro_html_icon( 'enroll' ) . ' '
            . '<b>' . get_lang('Enrolment') . '</b>'
            . '</a>'
            ;
    }
    
    /**
     * Render a link to log in.
     */
    private function renderLoginLink()
    {
        return '<a href="' 
            . claro_htmlspecialchars( get_path('clarolineRepositoryWeb') . 'auth/login.php'
                . '?sourceUrl='
                . urlencode( base64_encode(
                    ( isset( $_SERVER['HTTPS'])
                        && ($_SERVER['HTTPS']=='on'||$_SERVER['HTTPS']==1)
                        ? 'https://'
                        : 'http://' )
                    . $_SERVER['HTTP_HOST'] . strip_tags( $_SERVER['REQUEST_URI'] ) ) ) )
            . '" target="_top">'
            . get_lang('Login')
            . '</a>'
            ;
    }
    
    /**
     * Get an instance of the view mode switch
     * @return ClaroViewMode
     */
    public static function getInstance()
    {
        if ( ! ClaroViewMode::$instance )
        {
            ClaroViewMode::$instance = new ClaroViewMode;
        }
        
        return ClaroViewMode::$instance;
    }
}
