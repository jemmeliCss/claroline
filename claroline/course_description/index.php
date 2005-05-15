<?php // $Id$
/**
 * CLAROLINE
 *
 * This  page show  to the user, the course description
 *
 * If ist's the admin, he can access to the editing
 *
 *
 * @version 1.6 $Revision$
 *
 * @copyright 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @see http://www.claroline.net/wiki/CLDSC/
 *
 * @author Claro Team <cvs@claroline.net>
 *
 * @package CLDSC
 * 
 */

$tlabelReq = 'CLDSC___';

require '../inc/claro_init_global.inc.php';

if ( ! $_cid)             claro_disp_select_course();
if ( ! $is_courseAllowed) claro_disp_auth_form();

claro_set_display_mode_available(TRUE);
$nameTools = $langCourseProgram;

$QUERY_STRING=''; // to remove parameters in the last bredcrumb link
/*
 * DB tables definition
 */

$tbl_cdb_names           = claro_sql_get_course_tbl();
$tbl_course_description  = $tbl_cdb_names['course_description'];

include 'tiplistinit.inc.php';

//stats
include $includePath.'/lib/events.lib.inc.php';


$dialogBox = '';


         /*> > > > > > > > > > > > COMMANDS < < < < < < < < < < < < */


if ( isset($_REQUEST['cmd']) ) $cmd = $_REQUEST['cmd'];
else                           $cmd = NULL;


/******************************************************************************
                          EDIT / ADD DESCRIPTION ITEM
 ******************************************************************************/


if ($cmd == 'exEdit')
{
    if ( isset($_REQUEST['id']))
    {
        $sql ="UPDATE `".$tbl_course_description."` 
               SET   `title`   = '".trim(claro_addslashes($_REQUEST['descTitle'  ]))."',
                     `content` = '".trim(claro_addslashes($_REQUEST['descContent']))."',
                     `upDate`  = NOW()
               WHERE `id` = '". (int) $_REQUEST['id'] ."'";

        if ( claro_sql_query($sql) != FALSE)
        {
            $dialogBox .= '<p>'.$langDescUpdated.'</p>';
        }
        else
        {
            $dialogBox .= '<p>'.$langDescUnableToUpdate.'</p>';
        }
    }
    else
    {
        $sql = "SELECT MAX(id) 
                FROM `".$tbl_course_description."` ";

        $maxId = claro_sql_query_get_single_value($sql);

        $sql ="INSERT INTO `".$tbl_course_description."` 
               SET   `title`   = '".trim(claro_addslashes($_REQUEST['descTitle'  ]))."',
                     `content` = '".trim(claro_addslashes($_REQUEST['descContent']))."',
                     `upDate`  = NOW(),
                     `id` = ". (int) ($maxId + 1);

        if ( claro_sql_query($sql) !== FALSE)
        {
            $dialogBox .= '<p>'.$langDescAdded.'</p>';
        }
        else
        {
            $dialogBox .= '<p>'.$langUnableDescToAdd.'</p>';
        }
    }    
}





/******************************************************************************
                        REQUEST DESCRIPTION ITEM EDITION
 ******************************************************************************/


if($cmd == 'rqEdit')
{
    if ( isset($_REQUEST['id'] ) )
    {       
        $descItem = course_description_get_item((int)$_REQUEST['id']);
        $descPresetKey = array_search($descItem['title'] , $titreBloc);
    }
    else
    {
    	$descItem['id'     ] = NULL;
        $descItem['title'  ] = '';
        $descItem['content'] = '';

        if ( isset($_REQUEST['numBloc']) && $_REQUEST['numBloc'] > 0)
        {
            $descPresetKey = $_REQUEST['numBloc'];
        }
    }



    if ( !empty($descPresetKey) )
    {
         $descPresetTitle    = $titreBloc    [$descPresetKey];
         $descPresetQuestion = $questionPlan [$descPresetKey];
         $descPresetTip      = $info2Say     [$descPresetKey];
    }
    else
    {
         $descPresetTitle    = NULL;
         $descPresetQuestion = NULL;
         $descPresetTip      = NULL;
    }

    $displayForm = TRUE;
}




/******************************************************************************
                            DELETE DESCRIPTION ITEM
 ******************************************************************************/


if ($cmd == 'exDelete')
{
    if ( course_description_delete_item((int) $_REQUEST['id'])) 
    {
        $dialogBox .= '<p>'.$langDescDeleted.'</p>';
    }
    else
    {
        $dialogBox .= '<p>'.$langDescUnableToDelete.'</p>';
    }
}

/*---------------------------------------------------------------------------*/


event_access_tool($_tid, $_courseTool['label']);

/******************************************************************************
                           LOAD THE DESCRIPTION LIST
 ******************************************************************************/
$descList = course_description_get_item_list();

/*---------------------------------------------------------------------------*/



/*> > > > > > > > > > > > OUTPUT < < < < < < < < < < < < */



require $includePath.'/claro_init_header.inc.php';

claro_disp_tool_title( array('mainTitle' => $nameTools) );

if ( isset($dialogBox) && ! empty($dialogBox) )
{
    claro_disp_message_box($dialogBox);
    echo '<br />'."\n";
}

$is_allowedToEdit = claro_is_allowed_to_edit();

if ($is_allowedToEdit)
{
    /**************************************************************************
                               EDIT FORM DISPLAY
     **************************************************************************/


    if ( isset($displayForm) && $displayForm )
    {
        echo '<table border="0">'."\n"
            .'<tr>'              ."\n"
            .'<td>'              ."\n"

            .'<form  method="post" action="'.$_SERVER['PHP_SELF'].'">'."\n"

            .'<input type="hidden" name="cmd" value="exEdit">'

            .($descItem['id'] ? '<input type="hidden" name="id" value="'.$descItem['id'].'">' : '')

            .'<p><label for="descTitle"><b>'.$langTitle.' : </b></label><br /></p>'."\n"

            .($descPresetTitle ? $descPresetTitle
                                .'<input type="hidden" name="descTitle" value="'.$descPresetTitle.'">'
                                :
                                '<input type="text" name="descTitle" id="descTitle" size="50" value="'.$descItem['title'].'">'."\n")

            .'<p><label for="descContent"><b>'.$langContent.' : </b></label><br /></td></tr><tr><td>'."\n";

	        claro_disp_html_area('descContent', $descItem['content'], 20, 80, $optAttrib=' wrap="virtual"')."\n";

	        echo '<input type="submit" name="save" value="'.$langOk.'">'         ."\n";

	        claro_disp_button($_SERVER['PHP_SELF'], $langCancel);

	        echo '</form>'."\n"
            
            .'</td>'  ."\n"

            .'<td valign="top">'."\n";
            
            if ($descPresetQuestion)
            {
                echo '<h4>' . $langQuestionPlan . '</h4>'."\n"
                    .$descPresetQuestion;
            }
            
            if ($descPresetTip)
            {
                echo '<h4>' . $langInfo2Say . '</h4>'."\n"
                   .$descPresetTip;
            }
            

       echo '</td>'."\n"

            .'</tr>'   ."\n"
            .'</table>'."\n";

    } // end if display form

    else 
    {
    
    /**************************************************************************
                                ADD FORM DISPLAY
     **************************************************************************/

        echo "\n\n".'<form method="get" action="'.$_SERVER['PHP_SELF'].'?edIdBloc=add">'."\n"
            .'<input type="hidden" name="cmd" value="rqEdit">'."\n"
            .'<select name="numBloc">'."\n";

        foreach( $titreBloc as $key => $thisBlocTitle )
        {
            foreach( $descList as $thisDesc )
            {
              if ($thisDesc['title'] == $thisBlocTitle) $alreadyUsed = true;
              else                                      $alreadyUsed = false;
            }
            
            if ( !isset($alreadyUsed) || !$alreadyUsed )
            {
                echo '<option value="'.$key.'">'.$thisBlocTitle.'</option>'."\n";
            }
        }
            
        echo '<option value="">'.$langNewBloc.'</option>'."\n"
            .'</select>'."\n"
            .'<input type="submit" name="add" value="'.$langAdd.'">'."\n"
            .'</form>'."\n\n";
    }
} // end if is_allowedToEdit




/******************************************************************************
                            DESCRIPTION LIST DISPLAY
 ******************************************************************************/


if ( count($descList) )
{
    foreach($descList as $thisDesc)
    {
        echo "\n".'<h4>'.$thisDesc['title'].'</h4>'."\n"
            .'<blockquote>'."\n"
            . claro_parse_user_text($thisDesc['content'])."\n"
            .'<br>'."\n"
            .'</blockquote>'."\n";

        if ($is_allowedToEdit)
        {
            
            echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=rqEdit&amp;id='.$thisDesc['id'].'">'
                .'<img src="'.$imgRepositoryWeb.'edit.gif" alt="'.$langModify.'">'
                .'</a>'."\n"
                .'<a href="'.$_SERVER['PHP_SELF'].'?cmd=exDelete&amp;id='.$thisDesc['id'].'"'
                .' onClick="if(!confirm(\''.clean_str_for_javascript($langAreYouSureToDelete).' '.$thisDesc['title'].' ?\')){ return false}">'
                .'<img src="'.$imgRepositoryWeb.'delete.gif" alt="'.$langDelete.'">'
                .'</a>'."\n\n";
        }
    }
}
else
{
	echo "\n".'<p>'.$langThisCourseDescriptionIsEmpty.'</p>'."\n";
}

include $includePath.'/claro_init_footer.inc.php';


/**
 * get all the items
 * 
 * @author Christophe Gesch� <moosh@claroline.net>
 *
 * @param $dbnameGlu string  glued dbName of the course to affect default: current course
 * @return array of arrays with data of the item
 * 
 */

function course_description_get_item_list($dbnameGlu=Null)
{
    $tbl_cdb_names           = claro_sql_get_course_tbl($dbnameGlu);
    $tbl_course_description  = $tbl_cdb_names['course_description'];
    
    $sql = "SELECT `id`, `title`, `content` 
            FROM `".$tbl_course_description."` 
            ORDER BY `id`";
    return  claro_sql_query_fetch_all($sql);
}



/**
 * get the item of the given id.
 * 
 * @author Christophe Gesch� <moosh@claroline.net>
 *
 * @param $id_item   integer id of the item to get
 * @param $dbnameGlu string  glued dbName of the course to affect default: current course
 * @return array with data of the item
 * 
 */

function course_description_get_item($id_item, $dbnameGlu=Null)
{
    $tbl_cdb_names           = claro_sql_get_course_tbl($dbnameGlu);
    $tbl_course_description  = $tbl_cdb_names['course_description'];
    
    $sql = 'SELECT id, title, content
            FROM `'.$tbl_course_description.'`
            WHERE id = '.(int)$id_item;

    list($descItem) = claro_sql_query_fetch_all($sql);
    return $descItem;
}

/**
 * remove the item of the given id.
 * 
 * @author Christophe Gesch� <moosh@claroline.net>
 *
 * @param $id_item   integer id of the item to delete
 * @param $dbnameGlu string  glued dbName of the course to affect default: current course
 * @return result of query
 * 
 */

function course_description_delete_item($id_item, $dbnameGlu=Null)
{
    $tbl_cdb_names           = claro_sql_get_course_tbl($dbnameGlu);
    $tbl_course_description  = $tbl_cdb_names['course_description'];
    
    $sql = 'DELETE FROM `'.$tbl_course_description.'`
            WHERE id = '.(int)$id_item;
    
    return  claro_sql_query($sql);
}

?>