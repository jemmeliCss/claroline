<?php // $Id$

if ( count( get_included_files() ) == 1 )
{
    die( 'The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead' );
}

/**
 * CLAROLINE
 *
 * @version 1.9 $Revision$
 * @copyright (c) 2001-2008 Universite catholique de Louvain (UCL)
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @package CLCOURSELIST
 * @author Claro Team <cvs@claroline.net>
 */

class category_browser
{
    /**
     * constructor
     *
     * @param mixed $categoryCode null or valid category_code
     * @param mixed $userId null or valid user_id
     * @return category_browser object
     */
    function category_browser($categoryCode = null, $userId = null)
    {
        $this->categoryCode = $categoryCode;
        $this->userId       = $userId;

        $tbl_mdb_names         = claro_sql_get_main_tbl();
        $tbl_courses           = $tbl_mdb_names['course'  ];
        $tbl_courses_nodes     = $tbl_mdb_names['category'];

        $sql = "SELECT `faculte`.`code`  , `faculte`.`name`,
                       `faculte`.`code_P`, `faculte`.`nb_childs`,
                       COUNT( `cours`.`cours_id` ) AS `nbCourse`
                FROM `" . $tbl_courses_nodes . "` AS `faculte`

                LEFT JOIN `" . $tbl_courses_nodes . "` AS `subCat`
                       ON (`subCat`.`treePos` >= `faculte`.`treePos`
                      AND `subCat`.`treePos` <= (`faculte`.`treePos`+`faculte`.`nb_childs`) )

                LEFT JOIN `" . $tbl_courses . "` AS `cours`
                       ON `cours`.`faculte` = `subCat`.`code`
                       AND `cours`.visibility = 'VISIBLE'
                       ";

        if ($categoryCode)
        {
            $sql .= "WHERE UPPER(`faculte`.`code_P`) = UPPER('" . addslashes($categoryCode) . "')
                        OR UPPER(`faculte`.`code`)   = UPPER('" . addslashes($categoryCode) . "') \n";
        }
        else
        {
            $sql .= "WHERE `faculte`.`code`   IS NULL
                        OR `faculte`.`code_P` IS NULL \n";
        }

        $sql .= "GROUP  BY `faculte`.`code`
                 ORDER BY  `faculte`.`treePos`";

        $this->categoryList = claro_sql_query_fetch_all($sql);
    }

    /**
     * @since 1.8
     * @return array list of setting of the current category
     */
    function get_current_category_settings()
    {
        if ($this->categoryCode) return $this->categoryList[0];
        else                     return null;
    }

    /**
     * @since 1.8
     * @return array list of sub category of the current category
     */
    function get_sub_category_list()
    {
        if ($this->categoryCode) return array_slice($this->categoryList, 1);
        else                     return $this->categoryList;
    }

    /**
     * Fetch list of courses of the current category
     *
     * This list include main data about
     * the user but also registration status
     *
     * @since 1.8
     * @return array list of courses of the current category
     */
    function get_course_list()
    {
        $tbl_mdb_names = claro_sql_get_main_tbl();
        $tbl_courses   = $tbl_mdb_names['course'];
        $tbl_rel_course_user = $tbl_mdb_names['rel_course_user'];

        $sql = "SELECT intitule             AS title,
                       titulaires           AS titular,
                       code                 AS sysCode,
                       administrativeNumber AS officialCode,
                                               directory,
                                               visibility,
                                               access,
                                               registration,
                                               email,
                       "
              . ( $this->userId ? "cu.user_id" : "NULL") . " AS enroled "

              . " FROM `" . $tbl_courses . "` AS c
                "
              . ($this->userId
                 ? "LEFT JOIN `" . $tbl_rel_course_user . "` AS `cu`
                           ON  `c`.`code`    = `cu`.`code_cours`
                          AND `cu`.`user_id` = " . (int) $this->userId . "
                   "
                 : " ")

              . "WHERE c.`faculte` = '" . addslashes($this->categoryCode) . "'
                 AND (visibility = 'VISIBLE' "
                 . ($this->userId ? "OR NOT (cu.user_id IS NULL)" :"") .
                 ")
                 ORDER BY UPPER(c.administrativeNumber)";

        return claro_sql_query_fetch_all($sql);
    }
}

/**
 * Search a specific course based on his course code
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 *
 * @param  string  $keyword course code from the cours table
 * @param  mixed   $userId  null or valid id of a user (default:null)
 *
 * @return array    course parameters
 */

function search_course($keyword, $userId = null)
{
    $tbl_mdb_names        = claro_sql_get_main_tbl();
    $tbl_course           = $tbl_mdb_names['course'         ];
    $tbl_rel_course_user  = $tbl_mdb_names['rel_course_user'];

    $keyword = trim($keyword);

    if (empty($keyword) ) return array();

    $upperKeyword = addslashes(strtoupper($keyword));

    $sql = "SELECT c.intitule             AS title,
                   c.titulaires           AS titular,
                   c.code                 AS sysCode,
                   c.administrativeNumber AS officialCode,
                   c.directory            AS directory,
                   c.code                 AS code,
                   c.email                AS email,
                   c.visibility,
                   c.access,
                   c.registration"

         .  ($userId ? ", cu.user_id AS enroled" : "")
         . " \n "
         .  "FROM `" . $tbl_course . "` c "
         . " \n "
         .  ($userId ? "LEFT JOIN `" . $tbl_rel_course_user . "` AS cu
                        ON  c.code = cu.code_cours
                        AND cu.user_id = " . (int) $userId
                     :  "")
         . " \n "
         . "WHERE (  visibility = 'VISIBLE'
                  OR ".(claro_is_platform_admin()?"1":"0") ." "
         .  ($userId ? "OR cu.user_id" : "") . "

                  )
              AND (  (UPPER(administrativeNumber)  LIKE '%" . $upperKeyword . "%'
                  OR  UPPER(intitule)              LIKE '%" . $upperKeyword . "%'
                  OR  UPPER(titulaires)            LIKE '%" . $upperKeyword . "%')
                  )
            ORDER BY officialCode";

    $courseList = claro_sql_query_fetch_all($sql);

    if (count($courseList) > 0) return $courseList;
    else                        return array() ;
}

/**
 * Return the list of course of a user.
 *
 * @param int $userId valid id of a user
 * @param boolean $renew whether true, force to read databaseingoring an existing cache.
 * @return array (list of course) of array (course settings) of the given user.
 * @todo search and merge other instance of this functionality
 */

function get_user_course_list($userId, $renew = false)
{
    static $cached_uid = null, $userCourseList = null;

    if ($cached_uid != $userId || is_null($userCourseList) || $renew)
    {
        $cached_uid = $userId;

        $tbl_mdb_names         = claro_sql_get_main_tbl();
        $tbl_courses           = $tbl_mdb_names['course'         ];
        $tbl_link_user_courses = $tbl_mdb_names['rel_course_user'];

        $sql = "SELECT course.code                 AS `sysCode`,
                       course.directory            AS `directory`,
                       course.administrativeNumber AS `officialCode`,
                       course.dbName               AS `db`,
                       course.intitule             AS `title`,
                       course.titulaires           AS `titular`,
                       course.language             AS `language`,
                       course.faculte              AS `categoryCode`,
                       course_user.isCourseManager

                       FROM `" . $tbl_courses . "`           AS course,
                            `" . $tbl_link_user_courses . "` AS course_user

                       WHERE course.code         = course_user.code_cours
                         AND course_user.user_id = " . (int) $userId . " \n " ;

        if ( get_conf('course_order_by') == 'official_code' )
        {
            $sql .= " ORDER BY UPPER(`administrativeNumber`), `title`";
        }
        else
        {
            $sql .= " ORDER BY `title`, UPPER(`administrativeNumber`)";
        }

        $userCourseList = claro_sql_query_fetch_all($sql);
    }

    return $userCourseList;
}

/**
 * return the editable textzone for a course where subscript are denied
 *
 * @param string $course_id
 * @return string : html content
 */

function get_locked_course_explanation($course_id=null)
{
    $explanation = claro_text_zone::get_content('course_subscription_locked', array(CLARO_CONTEXT_COURSE => $course_id));
    return  (empty($explanation)) ? claro_text_zone::get_content('course_subscription_locked'):$explanation;
}


/**
 * Return the editable textzone for a course where subscript are locked
 *
 * @param string $course_id
 *
 * @return string : html content
 */

function get_locked_course_by_key_explanation($course_id=null)
{
    $explanation = claro_text_zone::get_content('course_subscription_locked_by_key', array(CLARO_CONTEXT_COURSE => $course_id));
    return  (empty($explanation)) ? claro_text_zone::get_content('course_subscription_locked_by_key'):$explanation;
}
