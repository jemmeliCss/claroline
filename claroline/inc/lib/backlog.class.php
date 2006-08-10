<?php // $Id$
    
    // vim: expandtab sw=4 ts=4 sts=4:
    
    define ( 'BACKLOG_SUCCESS', 'BACKLOG_SUCCESS' );
    define ( 'BACKLOG_FAILURE', 'BACKLOG_FAILURE' );
    define ( 'BACKLOG_DEBUG',   'BACKLOG_DEBUG' );
    
    class Backlog
    {
        var $_backlog = array();
        var $_size = array();
        
        function Backlog()
        {
            $this->_size[BACKLOG_SUCCESS] = 0;
            $this->_size[BACKLOG_FAILURE] = 0;
            $this->_size[BACKLOG_DEBUG] = 0;
        }
        
        function success( $msg )
        {
            $this->message( $msg, BACKLOG_SUCCESS );
            $this->_size[BACKLOG_SUCCESS]++;
        }
        
        function failure( $msg )
        {
            $this->message( $msg, BACKLOG_FAILURE );
            $this->_size[BACKLOG_FAILURE]++;
        }
        
        function debug( $msg )
        {
            $this->message( $msg, BACKLOG_DEBUG );
            $this->_size[BACKLOG_DEBUG]++;
        }
        
        function message( $msg, $type )
        {
            $this->_backlog[] = array( 'type' => $type, 'msg' => $msg );
        }
        
        function size( $type = null )
        {
            switch ( $type )
            {
                case BACKLOG_SUCCESS:
                case BACKLOG_FAILURE:
                case BACKLOG_DEBUG: 
                {
                    return $this->_size[$type];
                } break;
                
                default: 
                {
                    return count($this->_backlog);
                }
            }            
        }
        
        function output()
        {
            $out = '';
            
            foreach ( $this->_backlog as $entry )
            {
                $type = $entry['type'];
                $msg = $entry['msg'];
                
                switch ( $type )
                {
                    case BACKLOG_SUCCESS: 
                    {
                        $out .= '<span class="backlogSuccess">' . $msg . '</span><br />' . "\n";
                    } break;
                    case BACKLOG_FAILURE: 
                    {
                        $out .= '<span class="backlogFailure">' . $msg . '</span><br />' . "\n";
                    } break;
                    case BACKLOG_DEBUG: 
                    {
                        $out .= '<span class="backlogDebug">' . $msg . '</span><br />' . "\n";
                    } break;
                    default: 
                    {
                        $out .= '<span class="backlogMessage">' . $msg . '</span><br />' . "\n";
                    }
                }
                
                unset ($type, $msg );
            }
            
            return $out;
        }
        function main()
        {
            $bl = new Backlog;
            echo '<pre>';
            $bl->success( 'message success 1' );
            $bl->debug( 'message debug 1' );
            $bl->failure( 'message failure 1' );
            $bl->success( 'message success 2' );
            var_dump( $bl->size() );
            var_dump( $bl->_size );
            echo '</pre>';
            
            echo $bl->output();
        }
    }
    
    if ( ( isset( $argv ) && ( basename( __FILE__ ) === $argv[0] ) )
        || basename( $_SERVER['PHP_SELF'] ) === basename(__FILE__) )
    {
        Backlog::main();
    }
?>