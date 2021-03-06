<?php //$Id$

/**
 * Utility function to manipulate Claroline user classes
 * @version Claroline 1.12 $Revision$
 * @copyright (c) 2001-2014 Universite catholique de Louvain (UCL)
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @package kernel.users
 * @author Frederic Minne <zefredz@claroline.net>
 */

/**
 * Get the number of users in a class. 
 * @param int $class_id id of the class
 * @param bool $include_children if set to true recursively count users in subclasses
 * @return int
 */
function class_get_number_of_users( $class_id, $include_children = true )
{
    $tbl_mdb_names  = claro_sql_get_main_tbl();
    $tbl_class_user = $tbl_mdb_names['rel_class_user'];
    $tbl_class      = $tbl_mdb_names['class'];
    //1- get class users number

    $sqlcount = "SELECT COUNT(`user_id`) AS qty_user
                 FROM `" . $tbl_class_user . "`
                 WHERE `class_id`=" . (int) $class_id;

    $qty_user =  Claroline::getDatabase()->query($sqlcount)->setFetchMode ( Database_ResultSet::FETCH_VALUE )->fetch();
    
    if ( $include_children )
    {

        $sql = "SELECT `id`
                FROM `" . $tbl_class . "`
                WHERE `class_parent_id`=" . (int) $class_id;

        $subClassesList = Claroline::getDatabase()->query($sql);

        //2- recursive call to get subclasses'users too

        foreach ( $subClassesList as $subClass )
        {
            $qty_user += class_get_number_of_users( $subClass['id'], true );
        }
    }

    //3- return result of counts and recursive calls

    return $qty_user;
}

/**
 * Count the number of student in both the given course and the given class
 * @param string $course_id
 * @param int $class_id
 * @param bool $include_children recursively count students from subclasses
 * @return int
 */
function class_get_number_of_users_in_course( $course_id, $class_id, $include_children = true )
{
    $tbl_mdb_names  = claro_sql_get_main_tbl();
    $tbl_class_user = $tbl_mdb_names['rel_class_user'];
    $tbl_class      = $tbl_mdb_names['class'];
    $tbl_course_user = $tbl_mdb_names['rel_course_user' ];
    
    $sqlCourseId = Claroline::getDatabase()->quote( $course_id );
    
    //1- get class users number

    $sqlcount = "SELECT COUNT(clu.`user_id`) AS qty_user
                 FROM `{$tbl_class_user}` AS clu
                 INNER JOIN `{$tbl_course_user}` AS cu 
                 ON `cu`.`user_id` = `clu`.`user_id`
                 AND `cu`.`code_cours` = {$sqlCourseId}
                 WHERE clu.`class_id` = " . (int) $class_id;

    $qty_user =  Claroline::getDatabase()->query($sqlcount)->setFetchMode ( Database_ResultSet::FETCH_VALUE )->fetch();
    
    if ( $include_children )
    {

        $sql = "SELECT `id`
                FROM `" . $tbl_class . "`
                WHERE `class_parent_id`=" . (int) $class_id;

        $subClassesList = Claroline::getDatabase()->query($sql);

        //2- recursive call to get subclasses'users too

        foreach ( $subClassesList as $subClass )
        {
            $qty_user += class_get_number_of_users_in_course( $subClass['id'], true );
        }
    }

    //3- return result of counts and recursive calls

    return $qty_user;
}
