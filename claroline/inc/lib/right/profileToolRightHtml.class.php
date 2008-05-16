<?php // $Id$
if ( count( get_included_files() ) == 1 ) die( '---' );
/**
 * CLAROLINE
 *
 * Class to display manage profile and tool right (none, user, manager)
 *
 * @version 1.8 $Revision$
 *
 * @copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @package CLMAIN
 *
 * @author Claro Team <cvs@claroline.net>
 */

require_once dirname(__FILE__) . '/constants.inc.php';
require_once dirname(__FILE__) . '/profileToolRight.class.php';

class RightProfileToolRightHtml
{

    /**
     * @var $rightProfileToolRight RightProfileToolRight object
     */

    var $rightProfileToolRightList = array();

    /**
     * @var $displayMode
     */

    var $displayMode = '';

    /**
     * @var $urlParamAppend
     */

    var $urlParamAppendList = array();

    /**
     * @var $courseToolInfo
     */

    var $courseToolInfo = array();

    /**
     * Constructor
     */

    function RightProfileToolRightHtml($rightProfileToolRight=null)
    {
        if ( $rightProfileToolRight )
        {
            $this->addRightProfileToolRight($rightProfileToolRight);
        }
        $this->displayMode='edit';
    }

    /**
     * Add Right Profile object
     */

    function addRightProfileToolRight ($rightProfileToolRight)
    {
        // get profileId
        $profileId = $rightProfileToolRight->profile->getId();
        $this->rightProfileToolRightList[$profileId] = &$rightProfileToolRight;
    }

    /**
     * Set course tool info ('icon','tid','visibility','activation')
     */

    function setCourseToolInfo ($courseToolInfo)
    {
        $this->courseToolInfo = $courseToolInfo;
    }

    /**
     * Is set course Tool Info
     */

    function isSetCourseToolInfo ()
    {
        return (bool) count($this->courseToolInfo);
    }

    /**
     * Set display mode
     */

    function setDisplayMode($value)
    {
        $this->displayMode = $value ;
    }

    /**
     * Set Url param append
     */

    function addUrlParam($paramName,$paramValue)
    {
        $this->urlParamAppendList[$paramName] = $paramValue;
    }

    /**
     * Display table with tool/right of the profile
     */

    function displayProfileToolRightList()
    {

        $html = '';
        $html_table_header_list = array();
        $html_table_row_list = array();

        foreach ( $this->rightProfileToolRightList as $profile_id => $rightProfileToolRight )
        {
            $isLocked = $rightProfileToolRight->profile->isLocked();
            $className = get_class($rightProfileToolRight);

            // use strtolower for PHP4 : get_class returns class name in lowercase
            $className = strtolower($className);

            $html_table_header_list[$profile_id] = claro_get_profile_name($profile_id);

            if ( $isLocked && $className == strtolower('RightCourseProfileToolRight') )
            {
                $displayMode = 'read';
                $html_table_header_list[$profile_id] .= '&nbsp;<img src="' . get_path('imgRepositoryWeb') . '/locked.gif" alt="' . get_lang('Profile locked') . '" />';
            }
            else
            {
                $displayMode = $this->displayMode;
            }

            foreach ( $rightProfileToolRight->toolActionList as $tool_id => $action_list )
            {
                $action_right = $rightProfileToolRight->getToolRight($tool_id);

                $html_right = '';

                if ( $displayMode == 'edit' )
                {
                    $param_append = '?profile_id=' . urlencode($profile_id)
                                  . '&amp;tool_id=' . urlencode($tool_id)
                                  . '&amp;cmd=set_right'
                                  ;

                    foreach ( $this->urlParamAppendList as $name => $value )
                    {
                        $param_append .= '&amp;' . $name . '=' . $value;
                    }
                }

                if ( $action_right == 'none' )
                {
                    $action_param_value = 'user';
                    $html_right = '<img src="' . get_path('imgRepositoryWeb') . 'block.gif" alt="' . get_lang('None') . '" />' . "\n" ;
                }
                elseif ( $action_right == 'user' )
                {
                    $action_param_value = 'manager';
                    $html_right = '<img src="' . get_path('imgRepositoryWeb') . 'user.gif" alt="' . get_lang('User') . '" />' . "\n" ;
                }
                else
                {
                    $action_param_value = 'none';
                    $html_right = '<img src="' . get_path('imgRepositoryWeb') . 'manager.gif" alt="' . get_lang('Manager') . '" />' . "\n" ;
                }

                if ( $displayMode == 'edit' )
                {
                    $html_right = '<a href="' .$_SERVER['PHP_SELF'] . $param_append . '&amp;right_value=' . $action_param_value . '">' . $html_right . '</a>';
                }

                $html_table_row_list[$tool_id][$profile_id] = $html_right;
            }
        }

        // build table

        $html .= '<table class="claroTable emphaseLine" >' . "\n"
        .    '<thead>' . "\n"
        .    '<tr class="headerX">' . "\n"
        .    '<th>' . get_lang('Tools') . '</th>' . "\n";

        // visibility column
        if ( $this->isSetCourseToolInfo() )
        {
            $html .= '<th style="text-align:center; width:100px;" >' . get_lang('Visibility') . '</th>' . "\n";
        }

        foreach ( $html_table_header_list as $html_table_header  )
        {
            $html .= '<th style="text-align:center; width:100px;" >' . $html_table_header . '</th>' . "\n";
        }

        $html .= '</tr>' . "\n"
        .    '</thead>' . "\n"
        .    '<tbody>' ;

        foreach ( $html_table_row_list as $tool_id => $html_table_row )
        {
            if ( ! $this->isSetCourseToolInfo()
                || ! isset( $this->courseToolInfo[$tool_id] ) )
            {
                // Not activated in course !
                continue;
            }
            
            $html .= '<tr>' . "\n" ;

            if ( $this->isSetCourseToolInfo() )
            {
                // Add visibility and icon from courseToolInfo
                $html .= '<td ' . ($this->courseToolInfo[$tool_id]['visibility'] == true ?'':'class="invisible"') . '>'
                   . '<img src="' . $this->courseToolInfo[$tool_id]['icon'] . '" alt="" />' . get_lang(claro_get_tool_name($tool_id))
                   . '</td>';
            }
            else
            {
                $html .= '<td>'
                      . get_lang(claro_get_tool_name($tool_id))
                      . '</td>' . "\n"
                      ;
            }

            // visibility column

            if ( $this->isSetCourseToolInfo() )
            {
                if ( $this->courseToolInfo[$tool_id]['visibility'] == true )
                {
                    $html .= '<td align="center">'
                    . '<a href="' . $_SERVER['PHP_SELF'] . '?cmd=exInvisible&amp;tool_id=' . $this->courseToolInfo[$tool_id]['tid'] . '" >'
                    . '<img src="' . get_path('imgRepositoryWeb') . 'visible.gif" alt="' . get_lang('Visible') . '" />'
                    . '</a>'
                    . '</td>' . "\n";
                }
                else
                {
                    $html .= '<td align="center">'
                    . '<a href="' . $_SERVER['PHP_SELF'] . '?cmd=exVisible&amp;tool_id=' . $this->courseToolInfo[$tool_id]['tid'] .'" >'
                    . '<img src="' . get_path('imgRepositoryWeb') . 'invisible.gif" alt="' . get_lang('Invisible') . '" />'
                    . '</a></td>' . "\n" ;
                }
            }

            // profile colums

            foreach ( $html_table_row as $html_table_row_cell)
            {
                $html .= '<td align="center">' . $html_table_row_cell . '</td>';
            }
            $html .= '</tr>' . "\n" ;
        }

        $html .= '</tbody></table>';

        return $html ;
    }

}
