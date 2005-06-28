<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    /**
      * Wiki Administration Index
      *
      * @module Wiki
      * @access public
      * @author Frederic Minne <zefredz@gmail.com>
      * @copyright  (c) 2005 Frederic Minne under the terms of the GNU General
      *     Public License as published by the Free Software Foundation; either
      *     version 2 of the License, or (at your option) any later version.
      * @version 0.1
      */
      
    $tlabelReq = 'CLWIKI__';

    require_once "../inc/claro_init_global.inc.php";
    
    // check and set user access level for the tool
    
    if ( is_null( $_cid ) )
    {
        // user not logged in
        if ( is_null( $_uid ) )
        {
            claro_disp_auth_form();
        }
        // user logged in with no course selected
        else
        {
            claro_disp_select_course();
        }
    }
    
    // set admin mode and groupId
    
    $is_allowedToAdmin = false;

    if ( $_gid && $is_groupAllowed )
    {
        // group context
        $groupId = (int) $_gid;

        $is_allowedToAdmin = $is_groupTutor || $is_courseAdmin || $is_platformAdmin;
    }
    elseif ( $_gid && ! $is_groupAllowed )
    {
        die( "<center>You are not allowed to see this group's wiki !!!</center>" );
    }
    else
    {
        // course context
        $groupId = 0;

        $is_allowedToAdmin = $is_courseAdmin || $is_platformAdmin;
    }

    // unquote GPC if magic quote gpc enabled
    
    claro_unquote_gpc();

    // require wiki files
    
    require_once "lib/class.clarodbconnection.php";
    require_once "lib/class.wiki.php";
    require_once "lib/class.wikistore.php";
    require_once "lib/class.wikipage.php";
    require_once "lib/lib.requestfilter.php";
    require_once "lib/lib.wiki.php";
    require_once "lib/lib.wikisql.php";
    require_once "lib/lib.javascript.php";
    require_once "lib/lib.wikidisplay.php";
    
    // filter request variables
    
    // filter allowed actions using user status
    if ( $is_allowedToAdmin )
    {
        $valid_actions = array( "show", "list", "rqEdit", "exEdit", "rqDelete", "exDelete" );
    }
    else
    {
        $valid_actions = array( "show", "list" );
    }

    $_CLEAN = filter_by_key( 'action', $valid_actions, "R", false );
    
    $action = ( isset( $_CLEAN['action'] ) ) ? $_CLEAN['action'] : 'list';
    
    $wikiId = ( isset( $_REQUEST['wikiId'] ) ) ? (int) $_REQUEST['wikiId'] : 0;
    
    $creatorId = $_uid;

    // get request variable for wiki edition
    if ( $action == "exEdit" )
    {
        $wikiTitle = ( isset( $_POST['title'] ) ) ? strip_tags( $_POST['title'] ) : '';
        $wikiDesc = ( isset( $_POST['desc'] ) ) ? strip_tags( $_POST['desc'] ) : '';
        $acl = ( isset( $_POST['acl'] ) ) ? $_POST['acl'] : null;
        
        // initialise access control list
        
        $wikiACL = WikiAccessControl::emptyWikiACL();

        if ( is_array( $acl ) )
        {
            foreach ( $acl as $key => $value )
            {
                if ( $value == 'on' )
                {
                    $wikiACL[$key] = true;
                }
            }
        }
        
        // force Wiki ACL coherence
        
        if ( $wikiACL['course_read'] == false && $wikiACL['course_edit'] == true )
        {
            $wikiACL['course_edit'] = false;
        }
        if ( $wikiACL['group_read'] == false && $wikiACL['group_edit'] == true )
        {
            $wikiACL['group_edit'] = false;
        }
        if ( $wikiACL['other_read'] == false && $wikiACL['other_edit'] == true )
        {
            $wikiACL['other_edit'] = false;
        }
        
        if ( $wikiACL['course_edit'] == false  && $wikiACL['course_create'] == true )
        {
            $wikiACL['course_create'] = false;
        }
        if ( $wikiACL['group_edit'] == false  && $wikiACL['group_create'] == true )
        {
            $wikiACL['group_create'] = false;
        }
        if ( $wikiACL['other_edit'] == false  && $wikiACL['other_create'] == true )
        {
            $wikiACL['other_create'] = false;
        }
    }

    // Database nitialisation
    
    $tblList = claro_sql_get_course_tbl();

    $config = array();
    $config["tbl_wiki_properties"] = $tblList[ "wiki_properties" ];
    $config["tbl_wiki_pages"] = $tblList[ "wiki_pages" ];
    $config["tbl_wiki_pages_content"] = $tblList[ "wiki_pages_content" ];
    $config["tbl_wiki_acls"] = $tblList[ "wiki_acls" ];

    $con = new ClarolineDatabaseConnection();
    
    // DEVEL_MODE database initialisation
    // DO NOT FORGET TO REMOVE FOR PROD !!!
    if( defined("DEVEL_MODE") && ( DEVEL_MODE == true ) )
    {
        init_wiki_tables( $con, false );
    }

    // Objects instantiation

    $wikiStore = new WikiStore( $con, $config );
    $wikiList = array();
    
    // --------- Start of command processing ----------------
    
    switch ( $action )
    {
        // request delete
        case "rqDelete":
        {
            if ( ! $wikiStore->wikiIdExists( $wikiId ) )
            {
                die ( "Invalid wiki Id" );
            }
            else
            {
                $wiki = $wikiStore->loadWiki( $wikiId );
                $wikiTitle = $wiki->getTitle();
            }
            
            break;
        }
        // execute delete
        case "exDelete":
        {
            if ( $wikiStore->wikiIdExists( $wikiId ) )
            {
                $wiki = $wikiStore->deleteWiki( $wikiId );
            }
            else
            {
                die( "Invalid Wiki Id" );
            }

            if ( $groupId == 0 )
            {
                $wikiList = $wikiStore->getCourseWikiList();
            }
            else
            {
                $wikiList = $wikiStore->getWikiListByGroup( $groupId );
            }

            $message = 'Wiki deleted successfully';

            $action = 'list';

            break;
        }
        // show wiki properties
        case "show":
        {
            if ( $wikiStore->wikiIdExists( $wikiId ) )
            {
                $wiki = $wikiStore->loadWiki( $wikiId );
                $wikiTitle = $wiki->getTitle();
                $wikiDesc = $wiki->getDescription();
            }
            else
            {
                die( "Invalid Wiki Id" );
            }
            break;
        }
        // request edit
        case "rqEdit":
        {
            if ( $wikiId == 0 )
            {
                $wikiTitle = '';
                $wikiDesc = '';
                $wikiACL = null;
            }
            elseif ( $wikiStore->wikiIdExists( $wikiId ) )
            {
                $wiki = $wikiStore->loadWiki( $wikiId );
                $wikiTitle = $wiki->getTitle();
                $wikiDesc = $wiki->getDescription();
                $wikiACL = $wiki->getACL();
                $groupId = $wiki->getGroupId();
            }
            else
            {
                die( "Invalid Wiki Id" );
            }
            break;
        }
        // execute edit
        case "exEdit":
        {
            if ( $wikiId == 0 )
            {
                $wiki = new Wiki( $con, $config );
                $wiki->setTitle( $wikiTitle );
                $wiki->setDescription( $wikiDesc );
                $wiki->setACL( $wikiACL );
                $wiki->setGroupId( $groupId );
                $wikiId = $wiki->save();
                
                $wikiPage = new WikiPage( $con, $config, $wikiId );
                $wikiPage->create( $creatorId, '__MainPage__'
                    , $langWikiMainPageContent, date( "Y-m-d H:i:s" ), true );
            
                $message = "Wiki creation succeed";
            }
            elseif ( $wikiStore->wikiIdExists( $wikiId ) )
            {
                $wiki = $wikiStore->loadWiki( $wikiId );
                $wiki->setTitle( $wikiTitle );
                $wiki->setDescription( $wikiDesc );
                $wiki->setACL( $wikiACL );
                $wiki->setGroupId( $groupId );
                $wikiId = $wiki->save();
            
                $message = "Wiki edition succeed";
            }
            else
            {
                die( "Invalid Wiki Id" );
            }
            
            $action = 'list';
            
            // no break
        }
        // list wiki
        case "list":
        {
            if ( $groupId == 0 )
            {
                $wikiList = $wikiStore->getCourseWikiList();
            }
            else
            {
                $wikiList = $wikiStore->getWikiListByGroup( $groupId );
            }
            break;
        }
    }

    // ------------ End of command processing ---------------
    
    // javascript
    
    if ( $action == 'rqEdit' )
    {
        $jspath = document_web_path() . '/lib/javascript';
        $htmlHeadXtra[] = '<script type="text/javascript" src="'.$jspath.'/wiki_acl.js"></script>';
        $claroBodyOnload[] = 'initBoxes();';
    }
    
    // Breadcrumps
    
    // $interbredcrump[]= array ("url"=>"wiki.php", 'name'=> "Wiki");
    
    switch( $action )
    {
        case "show":
        {
            $interbredcrump[]= array ("url"=>"wiki.php", 'name'=> "Wiki");
            $nameTools = $wikiTitle;
            $noPHP_SELF = true;
            break;
        }
        case "rqEdit":
        {
            $interbredcrump[]= array ("url"=>"wiki.php", 'name'=> "Wiki");
            $interbredcrump[]= array ("url"=>"wiki.php?action=show&amp;wikiId=".$wikiId
                , 'name'=> $wikiTitle);
            $nameTools = 'Properties';
            $noPHP_SELF = true;
            break;
        }
        case "rqDelete":
        {
            $interbredcrump[]= array ("url"=>"wiki.php", 'name'=> "Wiki");
            $interbredcrump[]= array ("url"=>"wiki.php?action=show&amp;wikiId=".$wikiId
                , 'name'=> $wikiTitle);
            $nameTools = 'Delete';
            $noPHP_SELF = true;
            break;
        }
        case "list":
        default:
        {
            $nameTools = 'Wiki';
        }
    }
    
    // Claro header and banner

    require_once "../inc/claro_init_header.inc.php";

    // --------- Start of display ----------------
    
    if ( ! empty( $message ) )
    {
        echo claro_disp_message_box( $message );
    }

    switch( $action )
    {
        // edit form
        case "rqEdit":
        {
            // display title
            $toolTitle = array();
            
            if ( $wikiId == 0 )
            {
                $toolTitle['mainTitle'] = 'Wiki : New';
            }
            else
            {
                $toolTitle['mainTitle'] = 'Wiki : Edit';
            }

            echo claro_disp_tool_title( $toolTitle, false );
            
            // display form
            
            echo claro_disp_wiki_properties_form( $wikiId, $wikiTitle
                , $wikiDesc, $groupId, $wikiACL );
            
            break;
        }
        // show properties
        case "show":
        {
            // tool title
            
            $toolTitle = array();
            $toolTitle['mainTitle'] = 'Wiki : ' . $wikiTitle;

            echo claro_disp_tool_title( $toolTitle, false );
            
            echo '<p>' . "\n";

            echo '<a href="page.php?wikiId='
                . $wikiId
                . '" class="claroCmd">Enter wiki</a>'
                . "\n"
                ;

            if ( $is_allowedToAdmin )
            {

                echo '&nbsp;|&nbsp;<a href="'
                    . $_SERVER['PHP_SELF']
                    . '?wikiId=' . $wikiId
                    . '&amp;action=rqEdit'
                    . '" class="claroCmd">'
                    . '<img src="'.$imgRepositoryWeb.'edit.gif" border="0" alt="edit" />'
                    . 'Edit properties</a>'
                    . "\n"
                    ;

                echo '&nbsp;|&nbsp;<a href="'
                    . $_SERVER['PHP_SELF']
                    . '?wikiId=' . $wikiId
                    . '&amp;action=rqDelete'
                    . '" class="claroCmd">'
                    . '<img src="'.$imgRepositoryWeb.'delete.gif" border="0" alt="edit" />'
                    . 'Delete Wiki</a>'
                    . "\n"
                    ;
            }

            echo '</p>' . "\n";
            
            // wiki desc
            
            echo '<blockquote>'.$wikiDesc.'</blockquote>' . "\n";
            
            break;
        }
        // delete form
        case "rqDelete":
        {
            // list wiki
            $toolTitle = array();
            $toolTitle['mainTitle'] = 'Delete Wiki';

            echo claro_disp_tool_title( $toolTitle, false ) . "\n";
            
            echo '<form method="POST" action="'
                . $_SERVER['PHP_SELF']
                . '" id="rqDelete">'
                . "\n"
                ;
                
            echo '<p>'
                . 'WARNING : you are going to delete this wiki and all its pages. '
                . 'Are you sure to want to continue ?'
                . '</p>'
                . "\n"
                ;
                
            echo '<div style="padding: 5px">'
                . '<input type="hidden" name="wikiId" value="' . $wikiId . '" />' . "\n"
                . '<input type="submit" name="action[exDelete]" value="Continue" />' . "\n"
                . claro_disp_button ($_SERVER['PHP_SELF'], 'Cancel')
                . '</div>'
                ;

            echo '</form>' . "\n";
            
            break;
        }
        // list wiki
        case "list":
        {
            // tool title
            
            $toolTitle = array();
            $toolTitle['mainTitle'] = 'List of Wiki';
            
            echo claro_disp_tool_title( $toolTitle, false ) . "\n" . "\n";
            
            // display list in a table
            
            echo '<table class="claroTable emphaseLine" style="width: 100%">' . "\n";
            
            // if admin, display title, edit and delete
            if ( $is_allowedToAdmin )
            {
                echo '<thead>' . "\n"
                    . '<tr class="headerX" style="text-align: center;">' . "\n"
                    . '<th style="width: 55%">Title</th>' . "\n"
                    . '<th style="width: 15%">Edit</th>' . "\n"
                    . '<th style="width: 15%">Delete</th>' . "\n"
                    . '</tr>' . "\n"
                    . '</thead>' . "\n"
                    ;
            }
            // else display title only
            else
            {
                echo '<thead>' . "\n"
                    . '<tr class="headerX" style="text-align: center;">' . "\n"
                    . '<th style="width: 100%">Title</th>' . "\n"
                    . '</tr>' . "\n"
                    . '</thead>' . "\n"
                    ;
            }
            
            echo '<tbody>' . "\n";
            
            // wiki list not empty
            if ( is_array( $wikiList ) && count( $wikiList ) > 0 )
            {
                
                foreach ( $wikiList as $entry )
                {
                    echo '<tr>' . "\n";
                
                    // display title for all users
                    
                    echo '<td>';
                    echo '<a href="'.$_SERVER['PHP_SELF'].'?wikiId='
                        . $entry['id'].'&amp;action=show'
                        . '">'
                        . $entry['title'] . '</a>'
                        ;
                    echo '</td>' . "\n";
                    
                    // if admin, display edit and delete links
                    
                    if ( $is_allowedToAdmin )
                    {
                        // edit link
                        
                        echo '<td>';
                        echo '<a href="'.$_SERVER['PHP_SELF'].'?wikiId='
                            . $entry['id'].'&amp;action=rqEdit'
                            . '">'
                            . '<img src="'.$imgRepositoryWeb.'edit.gif" border="0" alt="edit" />'
                            . '</a>'
                            ;
                        echo '</td>' . "\n";
                
                        // delete link
                        
                        echo '<td>';
                        echo '<a href="'.$_SERVER['PHP_SELF'].'?wikiId='
                            . $entry['id'].'&amp;action=rqDelete'
                            . '">'
                            . '<img src="'.$imgRepositoryWeb.'delete.gif" border="0" alt="delete" />'
                            . '</a>'
                            ;
                        echo '</td>' . "\n";
                    }

                    echo '</tr>' . "\n";
                }
            }
            // wiki list empty
            else
            {
                echo '<tr><td colspan="3">No wiki</td></tr>' . "\n";
            }
            
            echo '</tbody>' . "\n";
            echo '</table>' . "\n" . "\n";
            
            // if admin, display add new wiki link
            if ( $is_allowedToAdmin )
            {
                echo '<p><a href="'
                    . $_SERVER['PHP_SELF']
                    . '?action=rqEdit'
                    . '" class="claroCmd">Create a new Wiki</a></p>'
                    . "\n"
                    ;
            }
            
            break;
        }
        default:
        {
            trigger_error( "Invalid action supplied to " . $_SERVER['PHP_SELF']
                , E_USER_ERROR
                );
        }
    }

    // ------------ End of display ---------------

    // Claroline footer

    require_once "../inc/claro_init_footer.inc.php";
?>