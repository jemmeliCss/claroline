<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * CLAROLINE
 *
 * Course home page: MyCalendar portlet
 *
 * @version     $Revision$
 * @copyright   (c) 2001-2011, Universite catholique de Louvain (UCL)
 * @license     http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @package     CLCHP
 * @author      Antonin Bourguignon <antonin.bourguignon@claroline.net>
 * @author      Claroline Team <info@claroline.net>
 * @since       1.10
 */

require_once get_module_path( 'CLCAL' ) . '/lib/agenda.lib.php';

class CLCAL_Portlet extends CourseHomePagePortlet
{
    public function renderContent()
    {
        $output = '<div id="portletMycalendar">' . "\n"
            . '<img src="'.get_icon_url('loading').'" alt="'.get_lang('Loading').'" />' . "\n"
            . '</div>' . "\n";
        
        $output .= "<script type=\"text/javascript\">
$(document).ready( function(){
    $('#portletMycalendar').load('"
        .get_module_url('CLCAL')."/ajaxHandler.php', { location : 'coursehomepage', courseCode : '".$this->courseCode."' });
});
</script>";
        
        return $output;
    }
    
    public function renderTitle()
    {
        $output = '<img '
                . 'src="' . get_icon_url('agenda', 'CLCAL') . '" '
                . 'alt="' . get_lang('Agenda') . '" /> '
                . get_lang('Next course events');
        
        if (claro_is_allowed_to_edit())
        {
            $output .= ' <span class="separator">|</span> <a href="'
                     . htmlspecialchars(Url::Contextualize(get_module_url( 'CLCAL' ) . '/agenda.php'))
                     . '">'
                     . '<img src="' . get_icon_url('settings') . '" alt="'.get_lang('Settings').'" /> '
                     . get_lang('Manage').'</a>';
        }
        
        return $output;
    }
}