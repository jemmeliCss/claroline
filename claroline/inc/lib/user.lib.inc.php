<?php // $Id$
/**
 * CLAROLINE
 * @version   version 1.7 $Revision$
 *
 * @copyright  2001 - 2005 Universite catholique de Louvain (UCL)
 *
 * @license GPL
 *
 * @author Claroline Team <info@claroline.net>
 */


/**
 * search informations of the group of two users
 * @author Muret Beno�t <muret_ben@hotmail.com>
 *
 * @param string $user1
 *        string $user2
 *
 * @return array information of the course and the group of each user
 *
 */
function searchCoursesGroup($user1,$user2)
{
    $tbl_mdb_names       = claro_sql_get_main_tbl();
    $tbl_user            = $tbl_mdb_names['user'  ];
    $tbl_courses         = $tbl_mdb_names['course'];
    $tbl_course_user     = $tbl_mdb_names['rel_course_user' ];

    GLOBAL $courseTablePrefix;
    GLOBAL $dbGlu;

    $sql_searchCourseData =
    "select `cu`.`user_id`,`cu`.`code_cours`,`cu`.`statut`,`cu`.`role`,`cu`.`tutor` titular,`c`.`cours_id`,`c`.`code` sysCode
            ,`c`.`languageCourse`,`c`.`intitule`,`c`.`faculte`,`c`.`titulaires`,`c`.`fake_code`,`c`.`directory`,`c`.`dbName`
        FROM `" . $tbl_course_user . "` cu,
             `" . $tbl_courses . "` c
        WHERE `cu`.`code_cours`=`c`.`code` 
              AND   (`cu`.`user_id`='" . $user1 . "' 
                  OR `cu`.`user_id`='" . $user2 . "')";

    $res_searchCourseData = claro_sql_query_fetch_all($sql_searchCourseData) ;

    //this is the choose course
    if($res_searchCourseData)
    {
        foreach($res_searchCourseData as $this_course)
        {
            $tbl_cdb_names = claro_sql_get_course_tbl($courseTablePrefix . $this_course['dbName'] . $dbGlu);  
            $tbl_rel_usergroup = $tbl_cdb_names['group_rel_team_user'];
            $tbl_group         = $tbl_cdb_names['group_team'];

            //search the user groups in this course
            $sql_searchCourseUserGroup =
            "select
                    `g`.`name`, `g`.`description`, `g`.`tutor`,  `g`.`secretDirectory`,
                    `g`.`id` id_group,
                    `ug`.`role`,
                    `tutor`.`nom` lastname,
                    `tutor`.`prenom` firstname,
                    `tutor`.`email`
                FROM `" . $tbl_rel_usergroup . "` ug, 
                     `" . $tbl_group . "` g
                LEFT JOIN `" . $tbl_user . "` tutor
                    ON `g`.`tutor` = `tutor`.`user_id`
                WHERE `ug`.`team`  = `g`.`id`
                AND ug.user='" . (int) $this_course['user_id'] . "'";

            $courseUserGroup[] = claro_sql_query_fetch_all($sql_searchCourseUserGroup) ;
        }
    }
    $array[0]=$res_searchCourseData;
    $array[1]=$courseUserGroup;
    return $array;
}

/*----------------------------------------
     CATEGORIES DEFINITION TREATMENT
 --------------------------------------*/
/**
 * create a new category definition for the user information
 *
 * @author - Hugues peeters <peeters@ipm.ucl.ac.be>
 * @author - Christophe Gesch� <moosh@claroline.net>
 * @param  - string $title - category title
 * @param  - string $comment - title comment
 * @param  - int    $nbline - lines number for the field the user will fill.
 * @return - bollean TRUE if succeed, else boolean FALSE
 */

function claro_user_info_create_cat_def($title='', $comment='', $nbline='5', $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_userinfo_def     = $tbl_cdb_names['userinfo_def'];

	if ( 0 == (int) $nbline || empty($title))
	{
		return FALSE;
	}

	$sql = "SELECT MAX(`rank`) maxRank FROM `" . $tbl_userinfo_def . "`";
	$result = claro_sql_query($sql);
	if ($result) $maxRank = mysql_fetch_array($result);

	$maxRank = $maxRank['maxRank'];

	$thisRank = $maxRank + 1;

	$title   = trim($title);
	$comment = trim($comment);

	$sql = "INSERT INTO `" . $tbl_userinfo_def."` SET
			`title`		= '" . addslashes($title) . "',
			`comment`	= '" . addslashes($comment) . "',
			`nbline`	= '" . (int) $nbline . "',
			`rank`		= '" . (int) $thisRank . "'";

	return claro_sql_query_insert_id($sql);
}

/**
 * modify the definition of a user information category
 *
 * @author - Hugues peeters <peeters@ipm.ucl.ac.be>
 * @author - Christophe Gesch� <moosh@claroline.net>
 * @param  - int $id - id of the category
 * @param  - string $title - category title
 * @param  - string $comment - title comment
 * @param  - int$nbline - lines number for the field the user will fill.
 * @return - boolean true if succeed, else otherwise
 */

function claro_user_info_edit_cat_def($id, $title, $comment, $nbline, $course_id=NULL)
{
	
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_userinfo_def = $tbl_cdb_names['userinfo_def'];

	if ( 0 == (int) $nbline || 0 == (int) $id )
	{
		return FALSE;
	}
	$title   = trim($title);
	$comment = trim($comment);

	$sql = "UPDATE `" . $tbl_userinfo_def."` SET
			`title`	  = '" . addslashes($title) . "',
			`comment` = '" . addslashes($comment) . "',
			`nbline`  = '" . (int) $nbline . "'
			WHERE id  = " . (int) $id;

	claro_sql_query($sql);

	return TRUE;
}

/**
 * remove a category from the category list
 *
 * @author - Hugues peeters <peeters@ipm.ucl.ac.be>
 * @author - Christophe Gesch� <moosh@claroline.net>
 *
 * @param  - int $id - id of the category
 *				or "ALL" for all category
 * @param  - boolean $force - FALSE (default) : prevents removal if users have
 *                            already fill this category
 *                            TRUE : bypass user content existence check
 * @param  - int $nbline - lines number for the field the user will fill.
 * @return - bollean  - TRUE if succeed, ELSE otherwise
 */

function claro_user_info_remove_cat_def($id, $force = false, $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_userinfo_def     = $tbl_cdb_names['userinfo_def'];
	$tbl_userinfo_content = $tbl_cdb_names['userinfo_content'];

	if ( (0 == (int) $id || $id == "ALL") || ! is_bool($force))
	{
		return false;
	}

	if ( $id != "ALL")
	{
		$sqlCondition = " WHERE id = ". (int) $id;
	}

	if ($force == FALSE)
	{
		$sql = "SELECT * FROM `" . $tbl_userinfo_content . "`  
		       ".$sqlCondition;
		$result = claro_sql_query($sql);

		if ( mysql_num_rows($result) > 0)
		{
			return FALSE;
		}
	}

	$sql = "DELETE FROM `" . $tbl_userinfo_def . "` 
	       " . $sqlCondition;
	return claro_sql_query($sql);
}

/**
 * move a category in the category list
 *
 * @author - Hugues peeters <peeters@ipm.ucl.ac.be>
 * @author - Christophe Gesch� <moosh@claroline.net>
 *
 * @param  - int $id - id of the category
 * @param  - direction "up" or "down" :
 *					"up"	decrease the rank of gived $id by switching rank with the just lower
 *					"down"	increase the rank of gived $id by switching rank with the just upper
 *
 * @return - boolean true if succeed, else bolean false
 */

function claro_user_info_move_cat_rank($id, $direction, $course_id=NULL)
{
	
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_userinfo_def     = $tbl_cdb_names['userinfo_def'];

    if ( 0 == (int) $id || ! ($direction == "up" || $direction == "down") )
	{
		return FALSE;
	}

	$sql = "SELECT rank 
	        FROM `" . $tbl_userinfo_def . "` 
	        WHERE id = ". (int) $id;
	$result = claro_sql_query($sql);

	if (mysql_num_rows($result) < 1)
	{
		return FALSE;
	}

	$cat = mysql_fetch_array($result);
	$rank = (int) $cat['rank'];
	return claro_user_info_move_cat_rank_by_rank($rank, $direction);
}

/**
 * move a category in the category list
 *
 * @author - Hugues peeters <peeters@ipm.ucl.ac.be>
 * @author - Christophe Gesch� <moosh@claroline.net>
 *
 * @param  - int $rank - actual rank of the category
 * @param  - direction "up" or "down" :
 *					"up"	decrease the rank of gived $rank by switching rank with the just lower
 *					"down"	increase the rank of gived $rank by switching rank with the just upper
 *
 * @return - boolean true if succeed, else bolean false
 */

function claro_user_info_move_cat_rank_by_rank($rank, $direction, $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_userinfo_def     = $tbl_cdb_names['userinfo_def'];

	if ( 0 == (int) $rank || ! ($direction == "up" || $direction == "down") )
	{
		return FALSE;
	}

	if ($direction == 'down') // thus increase rank ...
	{
		$sort = 'ASC';
		$compOp = '>=';
	}
	elseif ($direction == 'up') // thus decrease rank ...
	{
		$sort = 'DESC';
		$compOp = '<=';
	}

	// this request find the 2 line to be switched (on rank value)
	$sql = "SELECT id, rank 
	        FROM `" . $tbl_userinfo_def . "` 
	        WHERE rank " . $compOp." " . $rank . "
	        ORDER BY rank " . $sort . " LIMIT 2";

	$result = claro_sql_query($sql);

	if (mysql_num_rows($result) < 2)
	{
		return FALSE;
	}

	$thisCat = mysql_fetch_array($result);
	$nextCat = mysql_fetch_array($result);

	$sql1 = "UPDATE `" . $tbl_userinfo_def . "` 
	         SET rank =" . (int) $nextCat['rank'] . " 
	         WHERE id = " . (int) $thisCat['id'];
	$sql2 = "UPDATE `" . $tbl_userinfo_def . "` 
	         SET rank =" . (int) $thisCat['rank'] . "
			 WHERE id = " . (int) $nextCat['id'];

	claro_sql_query($sql1);
	claro_sql_query($sql2);

	return TRUE;
}

/*----------------------------------------
     CATEGORIES CONTENT TREATMENT
 --------------------------------------*/


 /**
 * fill a bloc for information category
 *
 * @author - Hugues peeters <peeters@ipm.ucl.ac.be>
 * @author - Christophe Gesch� <moosh@claroline.net>
 * @param  - $def_id,
 * @param  - $user_id,
 * @param  - $user_ip,
 * @param  - $content
 * @return - boolean true if succeed, else bolean false
 */

function claro_user_info_fill_new_cat_content($def_id, $user_id, $content="", $user_ip="", $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_userinfo_content = $tbl_cdb_names['userinfo_content'];

	if (empty($user_ip))
	{
		global $REMOTE_ADDR;
		$user_ip = $REMOTE_ADDR;
	}

	$content = trim($content);


	if ( 0 == (int) $def_id || 0 == (int) $user_id || $content == "")
	{
		// Here we should introduce an error handling system...

		return FALSE;
	}

	// Do not create if already exist

	$sql = "SELECT id FROM `" . $tbl_userinfo_content . "`
			WHERE	`def_id`	= " . (int) $def_id . "
			AND		`user_id`	= " . (int) $user_id;

	$result = claro_sql_query($sql);

	if (mysql_num_rows($result) > 0)
	{
		return FALSE;
	}

	$sql = "INSERT INTO `" . $tbl_userinfo_content . "` SET
			`content`	= '" . addslashes($content) . "',
			`def_id`	= " . (int) $def_id . ",
			`user_id`	= " . (int) $user_id . ",
			`ed_ip`		= '" . $user_ip . "',
			`ed_date`	= now()";

	claro_sql_query($sql);

	return TRUE;
}

/**
 * edit a bloc for information category
 *
 * @author - Hugues peeters <peeters@ipm.ucl.ac.be>
 * @author - Christophe Gesch� <moosh@claroline.net>
 * @param  - $def_id,
 * @param  - $user_id,
 * @param  - $user_ip, DEFAULT $REMOTE_ADDR
 * @param  - $content ; if empty call delete the bloc
 * @return - boolean true if succeed, else bolean false
 */

function claro_user_info_edit_cat_content($def_id, $user_id, $content ="", $user_ip="", $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
	$tbl_userinfo_content = $tbl_cdb_names['userinfo_content'];

	if (empty($user_ip))
	{
		global $REMOTE_ADDR;
		$user_ip = $REMOTE_ADDR;
	}

	if (0 == (int) $user_id || 0 == (int) $def_id)
	{
		return FALSE;
	}

	$content = trim($content);

	if ( trim($content) == "")
	{
		return claro_user_info_cleanout_cat_content($user_id, $def_id);
	}

	$sql= "UPDATE `" . $tbl_userinfo_content . "` SET
			`content`	= '" . addslashes($content) . "',
			`ed_ip`		= '" . $user_ip . "',
			`ed_date`	= now()
			WHERE def_id = " . (int) $def_id . " 
			      AND user_id = " . (int) $user_id;

	claro_sql_query($sql);

	return TRUE;
}

/**
 * clean the content of a bloc for information category
 *
 * @author - Hugues peeters <peeters@ipm.ucl.ac.be>
 * @author - Christophe Gesch� <moosh@claroline.net>
 * @param  - $def_id,
 * @param  - $user_id
 * @return - boolean true if succeed, else bolean false
 */

function claro_user_info_cleanout_cat_content($user_id, $def_id, $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
	$tbl_userinfo_content = $tbl_cdb_names['userinfo_content'];

	if (0 == (int) $user_id || 0 == (int) $def_id)
	{
		return FALSE;
	}

	$sql = "DELETE FROM `" . $tbl_userinfo_content . "`
			WHERE user_id = ". (int) $user_id ."  
			      AND def_id = " . (int) $def_id ;

	claro_sql_query($sql);

	return TRUE;
}



/*----------------------------------------
     SHOW USER INFORMATION TREATMENT
 --------------------------------------*/

/**
 * get the user info from the user id
 *
 * @param - int $user_id user id as stored in the claroline main db
 * @return - array containg user info sort by categories rank
 *           each rank contains 'title', 'comment', 'content', 'cat_id'
 *
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @author - Christophe Gesch� <moosh@claroline.net>
 *
 */

function claro_user_info_get_course_user_info($user_id, $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_userinfo_def     = $tbl_cdb_names['userinfo_def'];
	$tbl_userinfo_content = $tbl_cdb_names['userinfo_content'];

	$sql = "SELECT	cat.id catId,	cat.title,
					cat.comment ,	content.content
			FROM  	`" . $tbl_userinfo_def . "` cat 
			LEFT JOIN `" . $tbl_userinfo_content . "` content
			ON cat.id = content.def_id 	
			   AND content.user_id = '" . (int) $user_id . "'
			ORDER BY cat.rank, content.id";

	$result = claro_sql_query($sql);

	if (mysql_num_rows($result) > 0)
	{
		while ($userInfo = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$userInfos[]=$userInfo;
		}

		return $userInfos;
	}

	return FALSE;
}

/**
 * get the main user information
 * @param -  int $user_id user id as stored in the claroline main db
 * @return - array containing user info as 'lastName', 'firstName'
 *           'email', 'role'
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @author - Christophe Gesch� <moosh@claroline.net>
 */

function claro_user_info_get_main_user_info($user_id, $courseCode)
{
	if (0 == (int) $user_id)
	{
		return FALSE;
	}

	$tbl_mdb_names       = claro_sql_get_main_tbl();
    $tbl_user            = $tbl_mdb_names['user'  ];
    $tbl_rel_course_user = $tbl_mdb_names['rel_course_user' ];

	$sql = "SELECT	u.nom lastName, u.prenom firstName, 
	                u.email, u.pictureUri picture, cu.role, 
	                cu.`statut` `status`, cu.tutor
	        FROM    `" . $tbl_user . "` u, 
	                `" . $tbl_rel_course_user . "` cu
	        WHERE   u.user_id = cu.user_id
	        AND     u.user_id = " . (int) $user_id . "
	        AND     cu.code_cours = '" . $courseCode . "'";

	$result = claro_sql_query($sql);

	if (mysql_num_rows($result) > 0)
	{
		$userInfo = mysql_fetch_array($result, MYSQL_ASSOC);
		return $userInfo;
	}

	return FALSE;
}

/**
 * get the user content of a categories plus the categories definition
 * @param  - int $userId - id of the user
 * @param  - int $catId - id of the categories
 *
 * @return - array containing 'catId', 'title', 'comment',
 *           'nbline', 'contentId' and 'content'
 *
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @author - Christophe Gesch� <moosh@claroline.net>
 */

function claro_user_info_get_cat_content($userId, $catId, $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_userinfo_def     = $tbl_cdb_names['userinfo_def'];
	$tbl_userinfo_content = $tbl_cdb_names['userinfo_content'];

	$sql = "SELECT	cat.id catId,	cat.title,
					cat.comment ,	cat.nbline,
					content.id contentId, 	content.content
			FROM  	`" . $tbl_userinfo_def . "` cat 
			LEFT JOIN `" . $tbl_userinfo_content . "` content
			ON cat.id = content.def_id
			AND content.user_id = '" . (int) $userId . "'
			WHERE cat.id = '" . (int) $catId ."' ";

	$result = claro_sql_query($sql);

	if (mysql_num_rows($result) > 0)
	{
		$catContent = mysql_fetch_array($result, MYSQL_ASSOC);
		return $catContent;
	}

	return FALSE;
}

/**
 * get the definition of a category
 *
 * @author - Christophe Gesch� <moosh@claroline.net>
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param  - int $catId - id of the categories
 * @return - array containing 'id', 'title', 'comment', and 'nbline',
 */
function claro_user_info_get_cat_def($catId, $course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_userinfo_def     = $tbl_cdb_names['userinfo_def'];

	$sql = "SELECT id, title, comment, nbline, rank 
	        FROM `" . $tbl_userinfo_def . "` 
	        WHERE id = '". (int) $catId . "'";

	$result = claro_sql_query($sql);

	if (mysql_num_rows($result) > 0)
	{
		$catDef = mysql_fetch_array($result, MYSQL_ASSOC);
		return $catDef;
	}

	return FALSE;
}


/**
 * get list of all this course categories
 *
 * @author - Christophe Gesch� <moosh@claroline.net>
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @return - array containing a list of arrays.
 *           And each of these arrays contains
 *           'catId', 'title', 'comment', and 'nbline',
 */


function claro_user_info_claro_user_info_get_cat_def_list($course_id=NULL)
{
    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
    $tbl_userinfo_def = $tbl_cdb_names['userinfo_def'];
    
    $sql = "SELECT	id catId,	title,	comment , nbline
			FROM  `" . $tbl_userinfo_def . "`
			ORDER BY rank";

	$result = claro_sql_query($sql);

	if (mysql_num_rows($result) > 0)
	{
		while ($cat_def = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$cat_def_list[]=$cat_def;
		}

		return $cat_def_list;
	}

	return FALSE;
}

/**
 * transform content in a html display
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param  - string $string string to htmlize
 * @ return  - string htmlized
 */

function htmlize($phrase)
{
	return claro_parse_user_text(htmlspecialchars($phrase));
}


/**
 * replaces some dangerous character in a string for HTML use
 *
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param  - string (string) string
 * @return - the string cleaned of dangerous character
 */

function replace_dangerous_char($string)
{
	$search[]="/" ; $replace[]="-";
	$search[]="\|"; $replace[]="-";
	$search[]="\""; $replace[]=" ";

	foreach($search as $key=>$char )
	{
		$string = str_replace($char, $replace[$key], $string);
	}

	return $string;
}
?>