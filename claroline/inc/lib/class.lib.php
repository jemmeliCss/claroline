<?php // $Id$
/**
 * CLAROLINE
 *
 * Library for class
 *
 * @version 1.8 $Revision$
 *
 * @copyright 2001-2006 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Guillaume Lederer <guillaume@claroline.net>
 *
 * @since 1.6
 */

/**
 * This function delete a class and all this trace
 *
 * @author Damien Garros <dgarros@univ-catholyon.fr>
 *
 * @param  int class_id
 *
 * @return true if everything is good or an error string 
*/  

function delete_class($class_id)
{
    $tbl_mdb_names      = claro_sql_get_main_tbl();
    $tbl_user           = $tbl_mdb_names['user'];
    $tbl_class_user     = $tbl_mdb_names['rel_class_user'];
    $tbl_course_class     = $tbl_mdb_names['rel_course_class'];
    $tbl_class          = $tbl_mdb_names['class'];
    $tbl_course            = $tbl_mdb_names['course'];
    
    // 1 - See if there is a class with such ID in the main DB

    $sql = "SELECT `id`
            FROM `" . $tbl_class . "`
            WHERE `id` = '" . $class_id . "' ";
    $result = claro_sql_query_fetch_all($sql);
    
    if ( !isset($result[0]['id']))
    {
        return claro_failure::set_failure('CLASS_NOT_FOUND'); // the class doesn't exist
    }

    // 2 - check if class contains some children
    
    $sql = "SELECT count(id)
            FROM `" . $tbl_class . "`
            WHERE class_parent_id = " . (int) $class_id ;
    $has_children = (bool) claro_sql_query_get_single_value($sql);

    if ($has_children)
    {
        return get_lang('Error : the class has sub-classes');
    }
    else
    {
        
        // 3 - Get the list of user and remove each from class 

        $sql = "SELECT * 
            FROM `".$tbl_class_user."` `rel_c_u`, `".$tbl_user."` `u` 
            WHERE `class_id`='". (int) $class_id ."'
            AND `rel_c_u`.`user_id` = `u`.`user_id`";
            
        $userList = claro_sql_query_fetch_all($sql);
        
        // 4 - Get the list of course
        $sql = "SELECT * 
            FROM `".$tbl_course_class."` `rel_c_c`, `".$tbl_course."` `c` 
            WHERE `rel_c_c`.`class_id`='". (int) $class_id ."'
            AND `rel_c_c`.`cours_id` = `c`.`cours_id`";
            
        $courseList = claro_sql_query_fetch_all($sql);
    
        // Unsuscribe each user to each course

        foreach ($userList as $user)
        {
            foreach ($courseList as $course)
            {
                user_remove_from_course($user['user_id'], $course['code'], false , TRUE); 
            }
        }
        
        // Clean the class table
        $sql = "DELETE FROM `" . $tbl_class . "`
                WHERE id = " . (int) $class_id ;

        claro_sql_query($sql);
            
        // Clean the rel_course_class
        $sql = "DELETE FROM `" . $tbl_course_class . "`
                WHERE class_id = " . (int) $class_id ;
        
        claro_sql_query($sql);
        
        // Clean the rel_class_user
        $sql = "DELETE FROM `" . $tbl_class_user . "`
                WHERE class_id = " . (int) $class_id;
        claro_sql_query($sql);

        return true;
    }
}

/**
 * This function move a class in the class tree
 *
 * @author Damien Garros <dgarros@univ-catholyon.fr>
 *
 * @param  int class_id , id of the class what you want to move
 * @param  int class_id_towards, id of the parent destination class
 *
 * @return true if everything is good or an error string 
**/  

function move_class($class_id, $class_id_towards)
{
    $tbl_mdb_names      = claro_sql_get_main_tbl();
    $tbl_user           = $tbl_mdb_names['user'];
    $tbl_class_user     = $tbl_mdb_names['rel_class_user'];
    $tbl_class          = $tbl_mdb_names['class'];
        
    // 1 - Check if $class_id is different with $move_class_id
    if ($class_id == $class_id_towards)
    {
        return get_lang('ErrorMove');
    }
    
    // 2 - Check if $class_id and $moved_class_id are in the main DB
    
    $sql = "SELECT `id`,`class_parent_id`
            FROM `" . $tbl_class . "`
            WHERE `id` = '" . (int) $class_id . "' ";

    $result = claro_sql_query_fetch_all($sql);
    
    if ( !isset($result[0]['id']))
    {
        return claro_failure::set_failure('CLASS_NOT_FOUND'); // the class doesn't exist
    }
    
    if ( $class_id_towards !== "root" )
    {    
        $sql = "SELECT `id`
                FROM `" . $tbl_class . "`
                WHERE `id` = '" .(int) $class_id_towards . "' ";
        $result = claro_sql_query_fetch_all($sql);
        
        if ( !isset($result[0]['id']))
        {
        return claro_failure::set_failure('CLASS_NOT_FOUND'); // the class doesn't exist
        }
    }
    else
    {
        //if $class_id_parent is root 
        $class_id_towards = "NULL";
    }
    
    //Move class
    $sql_update="UPDATE `" . $tbl_class . "`
                 SET class_parent_id= " . $class_id_towards . "
                 WHERE id= " . (int) $class_id;
    claro_sql_query($sql_update);
    
    //Get user list
    $sql = "SELECT * 
        FROM `".$tbl_class_user."` `rel_c_u`, `".$tbl_user."` `u` 
        WHERE `class_id`='". (int) $class_id ."'
        AND `rel_c_u`.`user_id` = `u`.`user_id`";
            
    $userList = claro_sql_query_fetch_all($sql);
    
    //suscribe each user to parent class
    foreach($userList as $user)
    {
        user_add_to_class($user['user_id'],$class_id_towards);
    }
    
    return get_lang('ClassMoved');
}



/**
 * Enter description here...
 *
 * @param integer $class_id
 * @param string $course_code
 * @return unknown
 */

function register_class_to_course($class_id, $course_code)
{
    $tbl_mdb_names  = claro_sql_get_main_tbl();
    $tbl_user       = $tbl_mdb_names['user'];
    $tbl_class_user = $tbl_mdb_names['rel_class_user'];
    $tbl_class      = $tbl_mdb_names['class'];
    
    $tbl_class_user   = $tbl_mdb_names['rel_class_user'];
    $tbl_course_class = $tbl_mdb_names['rel_course_class'];
  	$tbl_class        = $tbl_mdb_names['class'];
  	$tbl_course       = $tbl_mdb_names['course'];

    //1.get cours_id with cours_code in cl_cours and check course
	
	$sql = "SELECT `cours_id`, `code`
				FROM `".$tbl_course."` 
				WHERE `code` = '". addslashes($course_code) ."'";
	
	$course_identifier = claro_sql_query_fetch_all($sql);
	
    if ( !isset($course_identifier[0]['code']))
    {
        return claro_failure::set_failure('COURSE_NOT_FOUND');
		//TODO : a�m�liorer la d�tection d'erreur
    }
	
	$course_id = $course_identifier[0]['cours_id'];

    // 2. See if there is a class with such ID in the main DB

    $sql = "SELECT `id`
            FROM `" . $tbl_class . "`
            WHERE `id` = '" . $class_id . "' ";
    $result = claro_sql_query_fetch_all($sql);
	
    if ( !isset($result[0]['id']))
    {
        return claro_failure::set_failure('CLASS_NOT_FOUND'); // the class doesn't exist
    }

    // 3. get the list of users in this class

    $sql = "SELECT *
            FROM `" . $tbl_class_user . "` AS `rel_c_u`,
                 `" . $tbl_user . "`       AS `u`
                    WHERE `class_id`= " . (int) $class_id . "
               AND `rel_c_u`.`user_id` = `u`.`user_id`";
    
    $result = claro_sql_query_fetch_all($sql);

    // 4. subscribe each users of class to course

    $resultLog = array();

    foreach ($result as $user)
    {
        $done = user_add_to_course($user['user_id'], $course_code, false, false, true);

        if ($done)
        {
            $resultLog['OK'][] = $user;
        }
        else
        {
            $resultLog['KO'][] = $user;
        }
    }

    // 5 - Record link between class and course
	
	// check if link already exist 
	$sql = "SELECT `cours_id`
				FROM `".$tbl_course_class."`
				WHERE `cours_id` = ".$course_id."
				AND `class_id` = ".$class_id;	
	
	$result = claro_sql_query_fetch_all($sql);
		
	if ( count($result) == 0 )
	{	
		//Insert value in table if not exist
		$sql = "INSERT INTO `".$tbl_course_class."` (`cours_id`,`class_id`)
		VALUES ('".$course_id."', '".$class_id."')";
		
		claro_sql_query($sql);	
	}

    //find subclasses of current class

    $sql = "SELECT `id`
            FROM `" . $tbl_class . "`
            WHERE `class_parent_id`=" . (int) $class_id;

    $subClassesList = claro_sql_query_fetch_all($sql);

    //RECURSIVE CALL to register subClasses too

    if (!isset($resultLog['OK'])) $resultLog['OK'] = array();
    if (!isset($resultLog['KO'])) $resultLog['KO'] = array();

    foreach ($subClassesList as $subClass)
    {
        $subClassResultLog = register_class_to_course($subClass['id'], $course_code);

        if (isset($subClassResultLog['OK'])) $resultLog['OK'] = array_merge($resultLog['OK'],$subClassResultLog['OK']);
        if (isset($subClassResultLog['KO'])) $resultLog['KO'] = array_merge($resultLog['KO'],$subClassResultLog['KO']);
    }

    return $resultLog;
}

/**
 * unregister a class to course
 *
 * @author Damien Garros <dgarros@univ-catholyon.fr>
 *
 * @param int class_id
 * @param string course_code
 * 
 * @return a string of log  
 *
 **/

function unregister_class_to_course($class_id, $course_code)
{

	$tbl_mdb_names  	= claro_sql_get_main_tbl();
    $tbl_user       	= $tbl_mdb_names['user'];
    $tbl_class_user 	= $tbl_mdb_names['rel_class_user'];
	$tbl_course_class 	= $tbl_mdb_names['rel_course_class'];
    $tbl_class      	= $tbl_mdb_names['class'];
	$tbl_course			= $tbl_mdb_names['course'];
	
    // 1 - check class in cl_class

	$sql = "SELECT `name`
				FROM `".$tbl_class."`
				WHERE `id` = '".$class_id."'";
				
	$class_name = claro_sql_query_get_single_value($sql);

    if ( is_null($class_name) || !isset($class_name))
    {
        return claro_failure::set_failure('CLASS_NOT_FOUND'); 
    }

    // 2 - Check course and get course_id

	$sql = "SELECT `cours_id`
				FROM `".$tbl_course."`
				WHERE `code` = '".$course_code."'";
				
	$course_id = claro_sql_query_get_single_value($sql);

    if ( is_null($course_id) || !isset($course_id) )
    {
        return claro_failure::set_failure('COURSE_NOT_FOUND'); 
    }
	
    //3 - get the list of users in this class

    $sql = "SELECT * 
			FROM `".$tbl_class_user."` `rel_c_u`, `".$tbl_user."` `u`
            WHERE `class_id`='". (int)$class_id."'
            AND `rel_c_u`.`user_id` = `u`.`user_id`";

    $result = claro_sql_query_fetch_all($sql);
        
    // 4 - Unsubscribe the each users
    
    $resultLog = array();
    
    foreach ($result as $user)
    {
        $done = user_remove_from_course($user['user_id'], $course_code, false, false, true);
        if ($done)
        {
            $resultLog['OK'][] = $user;
        }
        else
        {
            $resultLog['KO'][] = $user;
        } 
    }
	
    // 5 - Remove link between class and course in rel_course_class

	$sql = "DELETE FROM `".$tbl_course_class."`
			WHERE `cours_id` = '".$course_id."' 
			AND `class_id` = '".$class_id."'";
	
	claro_sql_query($sql);
	
    return $resultLog;

}

/**
 * Display the tree of classes
 *
 * @param unknown_type $class_list list of all the classes informations of the platform
 * @param unknown_type $parent_class
 * @param unknown_type $deep
 * @return unknown
 */

function display_tree_class_in_admin ($class_list, $parent_class = null, $deep = 0)
{

    //global variables needed

    global $clarolineRepositoryWeb;
    global $imgRepositoryWeb;

    foreach ($class_list as $cur_class)
    {

        if (($parent_class == $cur_class['class_parent_id']))
        {

            //Set space characters to add in name display

            $blankspace = '&nbsp;&nbsp;&nbsp;';
            for ($i = 0; $i < $deep; $i++)
            {
                $blankspace .= '&nbsp;&nbsp;&nbsp;';
            }

            //see if current class to display has children

            $has_children = FALSE;
            foreach ($class_list as $search_parent)
            {
                if ($cur_class['id'] == $search_parent['class_parent_id'])
                {
                    $has_children = TRUE;
                    break;
                }
            }

            //Set link to open or close current class

            if ($has_children)
            {
                if (isset($_SESSION['admin_visible_class'][$cur_class['id']]) && $_SESSION['admin_visible_class'][$cur_class['id']]=="open")
                {
                    $open_close_link = '<a href="' . $_SERVER['PHP_SELF'] . '?cmd=exClose&amp;class=' . $cur_class['id'] . '">' . "\n"
                    .                  '<img src="' . $imgRepositoryWeb . 'minus.gif" border="0" />' . "\n"
                    .                  '</a>' . "\n"
                    ;
                }
                else
                {
                    $open_close_link = '<a href="' . $_SERVER['PHP_SELF'] . '?cmd=exOpen&amp;class=' . $cur_class['id'] . '">' . "\n"
                    .                  '<img src="' . $imgRepositoryWeb . 'plus.gif" border="0" />' . "\n"
                    .                  '</a>' . "\n"
                    ;
                }
            }
            else
            {
                $open_close_link =" � ";
            }

            //DISPLAY CURRENT ELEMENT (CLASS)

            //Name
            $qty_user = get_class_user_number($cur_class['id']);
            $qty_cours = get_class_cours_number($cur_class['id']);

            echo '<tr>' . "\n"
            .    '<td>' . "\n"
            .    '    ' . $blankspace . $open_close_link . ' ' . $cur_class['name']
            .    '</td>' . "\n"
            .    '<td align="center">' . "\n"
            .    '<a href="' . $clarolineRepositoryWeb . 'admin/admin_class_user.php?class=' . $cur_class['id'] . '">' . "\n"
            .    '<img src="' . $imgRepositoryWeb . 'user.gif" border="0" />' . "\n"
            .    '(' . $qty_user . '  ' . get_lang('UsersMin') . ')' . "\n"
            .    '</a>' . "\n"
            .    '</td>' . "\n"
            .    '<td align="center">' . "\n"
  	        .    '<a href="'.$clarolineRepositoryWeb.'admin/admin_class_cours.php?class='.$cur_class['id'].'">' . "\n"
  	        .    '<img src="'.$imgRepositoryWeb.'course.gif" border="0"> '
  	        .    '('.$qty_cours.'  '.get_lang('Course').') ' . "\n"
  	        .    '</a>' . "\n"
  	        .    '</td>' . "\n"
            .    '<td align="center">' . "\n"
            .    '<a href="' . $_SERVER['PHP_SELF'] . '?cmd=edit&amp;class=' . $cur_class['id'] . '">' . "\n"
            .    '<img src="' . $imgRepositoryWeb . 'edit.gif" border="0" />' . "\n"
            .    '</a>' . "\n"
            .    '</td>' . "\n"
            .    '<td align="center">' . "\n"
            .    '<a href="' . $_SERVER['PHP_SELF'] . '?cmd=move&amp;class=' . $cur_class['id'] . '&classname=' . $cur_class['name'] . '">' . "\n"
            .    '<img src="' . $imgRepositoryWeb . 'move.gif" border="0" />' . "\n"
            .    '</a>' . "\n"
            .    '</td>' . "\n"
            .    '<td align="center">' . "\n"
            .    '<a href="' . $_SERVER['PHP_SELF']
            .    '?cmd=del&amp;class=' . $cur_class['id'] . '"'
            .    ' onClick="return confirmation(\'' . clean_str_for_javascript($cur_class['name']) . '\');">' . "\n"
            .    '<img src="' . $imgRepositoryWeb . 'delete.gif" border="0" />' . "\n"
            .    '</a>' . "\n"
            .    '</td>' . "\n"
            .    '</tr>' . "\n"
            ;
            // RECURSIVE CALL TO DISPLAY CHILDREN

            if (isset($_SESSION['admin_visible_class'][$cur_class['id']]) && ($_SESSION['admin_visible_class'][$cur_class['id']]=="open"))
            {
                display_tree_class_in_admin($class_list, $cur_class['id'], $deep+1);
            }
        }
    }
}

/**
 * Get the number of users in a class, including sublclasses
 *
 * @author Guillaume Lederer
 * @param  id of the (parent) class ffrom which we want to know the number of users
 * @return (int) number of users in this class and its subclasses
 *
 */

function get_class_user_number($class_id)
{
    $tbl_mdb_names  = claro_sql_get_main_tbl();
    $tbl_class_user = $tbl_mdb_names['rel_class_user'];
    $tbl_class      = $tbl_mdb_names['class'];
    //1- get class users number

    $sqlcount = "SELECT COUNT(`user_id`) AS qty_user
                 FROM `" . $tbl_class_user . "`
                 WHERE `class_id`=" . (int) $class_id;

    $qty_user =  claro_sql_query_get_single_value($sqlcount);

    $sql = "SELECT `id`
            FROM `" . $tbl_class . "`
            WHERE `class_parent_id`=" . (int) $class_id;

    $subClassesList = claro_sql_query_fetch_all($sql);

    //2- recursive call to get subclasses'users too

    foreach ($subClassesList as $subClass)
    {
        $qty_user += get_class_user_number($subClass['id']);
    }

    //3- return result of counts and recursive calls

    return $qty_user;
}

/**
 * Get the number of cours link with class
 *
 * @author Damien Garros <dgarros@univ-catholyon.fr>
 *
 * @param  id of the class from which we want to know the number of cours
 *
 * @return (int) number of cours in this class
 *
*/

function get_class_cours_number($class_id)
{
    $tbl_mdb_names   = claro_sql_get_main_tbl();
    $tbl_course_class = $tbl_mdb_names['rel_course_class'];

    // 1- get class users number

    $sqlcount = " SELECT COUNT(`cours_id`) AS qty_cours
                  FROM `".$tbl_course_class ."`
                  WHERE `class_id`='" . (int)$class_id . "'";

    $resultcount = claro_sql_query_fetch_all($sqlcount);

    $qty_cours = $resultcount[0]['qty_cours'];

    return $qty_cours;
}

/**
 * Display the tree of classes
 *
 * @author Guillaume Lederer
 * @param  list of all the classes informations of the platform
 * @param  list of the classes that must be visible
 * @return
 *
 * @see
 *
 */

function display_tree_class_in_user($class_list, $course_code, $parent_class = null, $deep = 0)
{

    global $clarolineRepositoryWeb;
    global $imgRepositoryWeb;

    $tbl_mdb_names  = claro_sql_get_main_tbl();

	$tbl_cours_class 	= $tbl_mdb_names['rel_cours_class'];
	$tbl_cours			= $tbl_mdb_names['course'];

	//Get the course id with cours code
	$sql = "SELECT `cours_id`
				FROM `".$tbl_cours."`
				WHERE `code` = '".$course_code."'";
				
	$cours_id = claro_sql_query_get_single_value($sql);

    foreach ($class_list as $cur_class)
    {
        if (($parent_class==$cur_class['class_parent_id']))
        {

            //Set space characters to add in name display

            $blankspace = '&nbsp;&nbsp;&nbsp;';
            for ($i = 0; $i < $deep; $i++)
            {
                $blankspace .= '&nbsp;&nbsp;&nbsp;';
            }

            //see if current class to display has children

            $has_children = FALSE;
            foreach ($class_list as $search_parent)
            {
                if ($cur_class['id'] == $search_parent['class_parent_id'])
                {
                    $has_children = TRUE;
                    break;
                }
            }

            //Set link to open or close current class

            if ($has_children)
            {
                if (isset($_SESSION['class_add_visible_class'][$cur_class['id']]) && $_SESSION['class_add_visible_class'][$cur_class['id']]=="open")
                {
                    $open_close_link = '<a href="' . $_SERVER['PHP_SELF']
                    .                  '?cmd=exClose&amp;class=' . $cur_class['id'] . '">' . "\n"
                    .                  '<img src="' . $imgRepositoryWeb . 'minus.gif" border="0" />' . "\n"
                    .                  '</a>' . "\n"
                    ;
                }
                else
                {
                    $open_close_link = '<a href="' . $_SERVER['PHP_SELF'] . '?cmd=exOpen&amp;class=' . $cur_class['id'] . '">' . "\n"
                    .                  '<img src="' . $imgRepositoryWeb . 'plus.gif" border="0" />' . "\n"
                    .                  '</a>' . "\n"
                    ;
                }
            }
            else
            {
                $open_close_link = '�';
            }

/*
            $sqlcount="SELECT COUNT(`user_id`) AS qty_user
                       FROM `" . $tbl_class_user . "`
                       WHERE `class_id`= " . (int) $cur_class['id'];
            $qty_user = claro_sql_query_get_single_value($sqlcount);
*/


            //DISPLAY CURRENT ELEMENT (CLASS)

            //Name

            echo '<tr>' . "\n"
            .    '<td>' . "\n"
            .    $blankspace.$open_close_link." ".$cur_class['name'] . "\n"
            .    '</td>' . "\n"
            .    '<td align="center">' . "\n"
            .    $qty_user . '  ' . get_lang('UsersMin') . "\n"
            .    '</td>' . "\n"
            .    '<td align="center">' . "\n"
            .    '<a onClick="return confirmation(\'' . clean_str_for_javascript($cur_class['name']) . '\');" href="' . $_SERVER['PHP_SELF'] . '?cmd=subscribe&amp;class=' . $cur_class['id'] . '&amp;classname=' . $cur_class['name'] . '">' . "\n"
            .    '<img src="' . $imgRepositoryWeb . 'enroll.gif" border="0" alt="' . get_lang('Subscribe to course') . '" />' . "\n"
            .    '</a>' . "\n"
            .    '</td>' . "\n"
            .    '</tr>' . "\n"
            ;
            // RECURSIVE CALL TO DISPLAY CHILDREN

            if (isset($_SESSION['class_add_visible_class'][$cur_class['id']]) && ($_SESSION['class_add_visible_class'][$cur_class['id']]=="open"))
            {
                display_tree_class_in_user($class_list, $cur_class['id'], $deep+1);
            }
        }
    }
}


/**
 *This function create the select box to choose the parent class
 *
 * @param  the pre-selected class'id in the select box
 * @param  space to display for children to show deepness
 * @global $tbl_class
 * @global get_lang('TopLevel')
 * @return void
*/

function displaySelectBox($selected=null,$space="&nbsp;&nbsp;&nbsp;")
{
    $tbl_mdb_names  = claro_sql_get_main_tbl();
    $tbl_class      = $tbl_mdb_names['class'];

    $sql = " SELECT *
             FROM `" . $tbl_class . "`
             ORDER BY `name`";
    $classes = claro_sql_query_fetch_all($sql);

    $result = '<select name="theclass">' . "\n"
    .         '<option value="root">' . get_lang('Root') . '</option>';
    $result .= buildSelectClass($classes,$selected,null,$space);
    $result .= '</select>' . "\n";
    return $result;
}

/**
 * This function create the list for the select box to choose the parent class
 *
 * @author Guillaume Lederer
 * @param  tab containing at least all the classes with their id, parent_id and name
 * @param  parent_id of the class we want to display the children of
 * @param  the pre-selected class'id in the select box
 * @param  space to display for children to show deepness
 * @return string to output
 *
*/
function buildSelectClass($classes,$selected,$father=null,$space="&nbsp;&nbsp;&nbsp;")
{
    $result = '';
    if($classes)
    {
        foreach($classes as $one_class)
        {
            //echo $one_class["class_parent_id"]." versus ".$father."<br>";

            if($one_class['class_parent_id']==$father)
            {
                $result .= '<option value="'.$one_class['id'].'" ';
                if ($one_class['id'] == $selected)
                {
                    $result .= 'selected ';
                }
                $result .= '> '.$space.$one_class['name'].' </option>'."\n";
                $result .=  buildSelectClass($classes,$selected,$one_class['id'],$space.'&nbsp;&nbsp;&nbsp;');
            }
        }
    }
    return $result;
}

function getSubClasses($class_id)
{
    $tbl_mdb_names  = claro_sql_get_main_tbl();
    $tbl_class      = $tbl_mdb_names['class'];

    $sub_classes_list = array();

    $sql = "SELECT `id`
            FROM `" . $tbl_class . "`
            WHERE `class_parent_id`=" . (int) $class_id;

    $query_result = claro_sql_query($sql);

    while ( ( $this_sub_class = mysql_fetch_array($query_result) ) )
    {
        // add this subclass id to array
        $sub_classes_list[] = $this_sub_class['id'];
        // add children of this subclass id to array
        $this_sub_classes_list = getSubClasses($this_sub_class['id']);
        $sub_classes_list = array_merge($this_sub_classes_list,$sub_classes_list);
    }

    return $sub_classes_list;
}

?>
