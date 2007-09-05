<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    if ( count( get_included_files() ) == 1 )
    {
        die( 'The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead' );
    }

    class ClaroFooter extends Display
    {
        private $template;
        private $hidden = false;

        public function __construct()
        {
            $file = new ClaroTemplateLoader('footer.tpl');
            $this->template = $file->load();
        }
        
        function hide()
        {
            $this->hidden = true;
        }

        function show()
        {
            $this->hidden = false;
        }

        public function render()
        {
            if ( $this->hidden )
            {
                return '<!-- footer hidden -->' . "\n";
            }
            
            $currentCourse =  claro_get_current_course_data();
            
            $contact = array();
            
            if ( claro_is_in_a_course() )
            {
                $courseManagerOutput = '<div id="courseManager">'
                    . get_lang('Manager(s) for %course_code'
                        , array('%course_code' => $currentCourse['officialCode']) )
                    . ' : '
                    ;
                    
                $currentCourseTitular = empty ( $currentCourse['titular'] )
                    ? get_lang ( 'Course manager' )
                    : $currentCourse['titular']
                    ;

                if ( empty($currentCourse['email']) )
                {
                    $courseManagerOutput .= '<a href="' . get_module_url('CLUSR') . '/user.php">'. $currentCourseTitular.'</a>';
                }
                else
                {
                    $courseManagerOutput .= '<a href="mailto:' . $currentCourse['email'] . '?body=' . $currentCourse['officialCode'] . '&amp;subject=[' . rawurlencode( get_conf('siteName')) . ']' . '">' . $currentCourseTitular . '</a>';
                }
                
                $courseManagerOutput .= '</div>';
                
                $contact['courseManager'] = $courseManagerOutput;
            }
            else
            {
                $contact['courseManager'] = '';
            }
            
            $platformManagerOutput = '<div id="platformManager">'
                . get_lang('Administrator for %site_name'
                    , array('%site_name'=>get_conf('siteName'))). ' : '
                . '<a href="mailto:' . get_conf('administrator_email')
                . '?subject=[' . rawurlencode( get_conf('siteName') ) . ']'.'">'
                . get_conf('administrator_name')
                . '</a>'
                ;

            if ( get_conf('administrator_phone') != '' )
            {
                $platformManagerOutput .= '<br />' . "\n"
                    . get_lang('Phone : %phone_number'
                        , array('%phone_number' => get_conf('administrator_phone'))) ;
            }

            $platformManagerOutput .= '</div>';
            
            $contact['platformManager'] = $platformManagerOutput;
            
            $poweredByOutput = '<div id="poweredBy">'
                . get_lang('Powered by')
                . ' <a href="http://www.claroline.net" target="_blank">Claroline</a> '
                . '&copy; 2001 - 2007'
                . '</div>'
                ;
                
            $contact['poweredBy'] = $poweredByOutput;
            
            $this->template->addReplacement( 'contact', $contact );
            
            return $this->template->render();
        }
    }
?>