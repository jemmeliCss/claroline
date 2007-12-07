<?php // $Id$
if ( count( get_included_files() ) == 1 ) die( '---' );
/**
 * CLAROLINE
 *
 * @version 1.8 $Revision$
 *
 * @copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @author Claro Team <cvs@claroline.net>
 *
 */

class Exercise
{
	/**
     * @var $id id of exercise, -1 if exercise doesn't exist already
     */
    var $id;

	/**
     * @var $title name of the exercise
     */
    var $title;

	/**
     * @var $description statement of the exercise
     */
    var $description;

    /**
     * @var $visibility visibility of the exercise
     */
    var $visibility;

    /**
     * @var $sequential if exercise is displayed on one page or several
     */
    var $displayType;

    /**
     * @var $shuffle expected submission type (text, text and file, file)
     */
    var $shuffle;

    /**
     * @var $allowLateUpload is upload allowed after assignment end date
     */
    var $showAnswers;

    /**
     * @var $startDate submissions are not possible before this date
     */
    var $startDate;

    /**
     * @var $endDate submissions are not possible after this date (except if $allowLateUpload is true)
     */
    var $endDate;

    /**
     * @var $timeLimit text of automatic feedback
     */
    var $timeLimit;

    /**
     * @var $attempts file of automatic feedback
     */
    var $attempts;

	/**
     * @var $anonymousAttempts file of automatic feedback
     */
    var $anonymousAttempts;

    /**
     * @var $tblExercise
     */
    var $tblExercise;

	/**
     * @var $tblRelExerciseQuestion
     */
    var $tblRelExerciseQuestion;

    /**
     * @var $tblQuestion
     */
    var $tblQuestion;

    function Exercise($course_id = null)
    {
    	$this->id = (int) -1;
    	$this->title = '';
	    $this->description = '';
	    $this->visibility = 'INVISIBLE';
	    $this->displayType = 'ONEPAGE';
	    $this->shuffle = 0;
	    $this->showAnswers = 'ALWAYS';
	    $this->startDate = time(); // now as unix timestamp
	    $this->endDate = null; // means that endDate is not used
	    $this->timeLimit = 0;
	    $this->attempts = 0;
	    $this->anonymousAttempts = 'NOTALLOWED';

	    $tbl_cdb_names = claro_sql_get_course_tbl(claro_get_course_db_name_glued($course_id));
		$this->tblExercise = $tbl_cdb_names['qwz_exercise'];
		$this->tblQuestion = $tbl_cdb_names['qwz_question'];
		$this->tblRelExerciseQuestion = $tbl_cdb_names['qwz_rel_exercise_question'];
    }

	/**
     * load an exercise from DB
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param integer $id id of exercise
     * @return boolean load successfull ?
     */
    function load($id)
    {
	    $sql = "SELECT
					`id`,
	                `title`,
	                `description`,
	                `visibility`,
	                `displayType`,
	                `shuffle`,
	                `showAnswers`,
                	UNIX_TIMESTAMP(`startDate`) AS `unix_start_date`,
					UNIX_TIMESTAMP(`endDate`) AS `unix_end_date`,
	                `timeLimit`,
	                `attempts`,
	                `anonymousAttempts`
	        FROM `".$this->tblExercise."`
	        WHERE `id` = ".(int) $id;

	    $data = claro_sql_query_get_single_row($sql);

	    if( !empty($data) )
	    {
	    	// from query
	        $this->id = (int) $data['id'];
	    	$this->title = $data['title'];
		    $this->description = $data['description'];
		    $this->visibility = $data['visibility'];
		    $this->displayType = $data['displayType'];
		    $this->shuffle = $data['shuffle'];
		    $this->showAnswers = $data['showAnswers'];
		    $this->startDate = $data['unix_start_date'];

		    // unix_end_date is null if the query returns 0 (UNIX_TIMESTAP('0000-00-00 00:00:00') == 0)
            // for this value
		    if( $data['unix_end_date'] == '0' ) $this->endDate = null;
            else                                $this->endDate = $data['unix_end_date'];
            
            
		    $this->timeLimit = $data['timeLimit'];
		    $this->attempts = $data['attempts'];
		    $this->anonymousAttempts = $data['anonymousAttempts'];

			return true;
	    }
	    else
	    {
	        return false;
	    }
    }

    /**
     * save exercise to DB
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return mixed false or id of the record
     */
    function save()
    {
    	// TODO method to validate data
    	if( $this->id == -1 )
    	{
    		// insert
		    $sql = "INSERT INTO `".$this->tblExercise."`
		            SET `title` = '".addslashes($this->title)."',
		                `description` = '".addslashes($this->description)."',
		                `visibility` = '".addslashes($this->visibility)."',
		                `displayType` = '".addslashes($this->displayType)."',
		                `shuffle` = ".(int) $this->shuffle.",
		                `showAnswers` = '".addslashes($this->showAnswers)."',
		                `startDate` = FROM_UNIXTIME(".addslashes($this->startDate)."),
		                `endDate` = ".(is_null($this->endDate)?"'0000-00-00 00:00:00'":"FROM_UNIXTIME(".addslashes($this->endDate).")").",
						`timeLimit` = ".(int) $this->timeLimit.",
						`attempts` = ".(int) $this->attempts.",
						`anonymousAttempts` = '".addslashes($this->anonymousAttempts)."'";

		    // execute the creation query and get id of inserted assignment
		    $insertedId = claro_sql_query_insert_id($sql);

		    if( $insertedId )
		    {
		    	$this->id = (int) $insertedId;

		        return $this->id;
		    }
		    else
		    {
		        return false;
		    }
    	}
    	else
    	{
    		// update, main query
		    $sql = "UPDATE `".$this->tblExercise."`
		            SET `title` = '".addslashes($this->title)."',
		                `description` = '".addslashes($this->description)."',
		                `visibility` = '".addslashes($this->visibility)."',
		                `displayType` = '".addslashes($this->displayType)."',
		                `shuffle` = ".(int) $this->shuffle.",
		                `showAnswers` = '".addslashes($this->showAnswers)."',
		                `startDate` = FROM_UNIXTIME('".addslashes($this->startDate)."'),
		                `endDate` = ".(is_null($this->endDate)?"'0000-00-00 00:00:00'":"FROM_UNIXTIME(".addslashes($this->endDate).")").",
						`timeLimit` = ".(int) $this->timeLimit.",
						`attempts` = ".(int) $this->attempts.",
						`anonymousAttempts` = '".addslashes($this->anonymousAttempts)."'
		            WHERE `id` = '".$this->id."'";

		    // execute and return main query
		    if( claro_sql_query($sql) )
		    {
		    	return $this->id;
		    }
		    else
		    {
		    	return false;
		    }
    	}
    }

    /**
     * delete exercise from DB && delete all occurences of exercise in learning path
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return boolean
     */
	function delete()
	{
		$sql = "DELETE FROM `" . $this->tblRelExerciseQuestion . "`
				WHERE `exerciseId` = " . (int) $this->id ;

		if( claro_sql_query($sql) == false ) return false;


		$sql = "DELETE FROM `".$this->tblExercise."`
				WHERE `id` = " . (int) $this->id;
				
		if( claro_sql_query($sql) == false ) return false;
		
		// for learning path
		global $includePath;
		require_once $includePath . '/lib/learnPath.lib.inc.php';
		delete_exercise_asset($this->id);
		
		$this->id = -1;
		return true;
	}

    /**
     * check if data are valide
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return boolean
     */
	function validate()
	{
	    // title is a mandatory element
	    $title = strip_tags($this->title);

	    if( empty($title) )
	    {
	        claro_failure::set_failure('exercise_no_title');
	        return false;
	    }
	    else
	    {
			// dates : check if start date is lower than end date else we will have a paradox
		    if( !is_null($this->endDate) && $this->endDate <= $this->startDate )
		    {
		        claro_failure::set_failure('exercise_incorrect_dates');
		        return false;
		    }
	    }

	    return true; // no errors, form is valide
    }

	/**
     * update visibility of an exercise
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param integer $exerciseId
     * @param string $visibility
     * @return boolean
     */
    function updateExerciseVisibility($exerciseId, $visibility)
    {
    	// this method is not used in object context so we cannot access $this->$tblAssignment
    	$tbl_cdb_names = claro_sql_get_course_tbl();
		$tblExercise = $tbl_cdb_names['qwz_exercise'];

	    $acceptedValues = array('VISIBLE', 'INVISIBLE');

		if( in_array($visibility, $acceptedValues) )
		{
		    $sql = "UPDATE `" . $tblExercise . "`
		               SET `visibility` = '" . $visibility . "'
		             WHERE `id` = " . (int) $exerciseId . "
		               AND `visibility` != '" . $visibility . "'";

		    return  claro_sql_query($sql);
		}

		return false;
    }


    /**
     * get question list
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return array list of id of question used in this exercise
     */
	function getQuestionList()
	{
		$sql = "SELECT Q.`id`, Q.`title`, Q.`type`, REQ.`rank`
				 FROM `" . $this->tblRelExerciseQuestion . "` AS REQ,
				      `" . $this->tblQuestion . "` AS Q
				WHERE REQ.`exerciseId` = " . (int) $this->id . "
				  AND REQ.`questionId` = Q.`id`
				ORDER BY `rank`";

		$questionList = claro_sql_query_fetch_all($sql);

		if( is_array($questionList) )
		{
			return $questionList;
		}
		else
		{
			$emptyArray = array();
			return $emptyArray;
		}
	}

    /**
     * get random question list
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return array list of id of question used in this exercise
     */
	function getRandomQuestionList()
	{
		$questionList = $this->getQuestionList();

		$randomQuestionList = array();

		if( $this->shuffle > 0 && !empty($questionList) )
		{
			// shuffle the list
			shuffle($questionList);

			$limit = min( $this->shuffle,count($questionList) );

			for( $i = 0; $i < $limit; $i++ )
			{
				$randomQuestionList[] = $questionList[$i];
			}
		}

		return $randomQuestionList;
	}

	/**
     * get the rank of a question
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param $questionId id of the question
     * @return int or boolean if query failed
     */
	function getQuestionRank($questionId)
	{
		$sql = "SELECT `rank`
				FROM `".$this->tblRelExerciseQuestion."`
 			WHERE `exerciseId` = ". (int) $this->id."
				AND `questionId` = ". (int) $questionId;
		return claro_sql_query_get_single_value($sql);
	}

	/**
     * get the higher rank of a question in the exercise
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param $questionId id of the question
     * @return int or boolean if query failed
     */
	function getRankMax()
	{
		$sql = "SELECT max(`rank`)
				FROM `" . $this->tblRelExerciseQuestion . "`
				WHERE `exerciseId` = ". (int) $this->id;

		$rankMax = claro_sql_query_get_single_value($sql);

		if( is_null($rankMax) )	return 0;
		else 					return $rankMax;
	}

	/**
     * change rank of a question in the exercise, jump one position up in the list
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param $questionId id of the question to move
     * @return boolean
     */
	function moveQuestionUp($questionId)
	{
		$questionList = $this->getQuestionList();

		// find question
		$i = 0;
		while( $i < count($questionList) )
		{
			if( $questionList[$i]['id'] == $questionId )
			{
				$questionRank = $questionList[$i]['rank'];
				break;
			}

			$i++;
		}

		// question is first of the list, cannot move upper
		// or question has not been found
		if( $i == 0 || !isset($questionRank) )
		{
			return false;
		}

		if( isset($questionList[$i-1]['rank']) )
		{

			// previous question
			$newRank = $questionList[$i-1]['rank'];

			$sql = "UPDATE `".$this->tblRelExerciseQuestion."`
					SET `rank` = ".(int) $questionRank."
					WHERE `exerciseId` = ".(int) $this->id."
					  AND `rank` = ".(int) $newRank;

			if( claro_sql_query($sql) == false ) return false;

			$sql = "UPDATE `".$this->tblRelExerciseQuestion."`
					SET `rank` = ".(int) $newRank."
					WHERE `exerciseId` = ".(int) $this->id."
					  AND `questionId` = ".(int) $questionId;

			if( claro_sql_query($sql) == false ) return false;

			return true;
		}
		return false;
	}

	/**
     * change rank of a question in the exercise, jump one position down in the list
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param $questionId id of the question to move
     * @return boolean
     */
	function moveQuestionDown($questionId)
	{
		$questionList = $this->getQuestionList();

		// find question
		$i = 0;
		while( $i < count($questionList) )
		{
			if( $questionList[$i]['id'] == $questionId )
			{
				$questionRank = $questionList[$i]['rank'];
				break;
			}

			$i++;
		}

		// question is last of the list, cannot move down anymore
		// or question has not been found
		if( $i == count($questionList)-1 || !isset($questionRank) )
		{
			return false;
		}

		if( isset($questionList[$i+1]['rank']) )
		{

			// previous question
			$newRank = $questionList[$i+1]['rank'];

			$sql = "UPDATE `".$this->tblRelExerciseQuestion."`
					SET `rank` = ".(int) $questionRank."
					WHERE `exerciseId` = ".(int) $this->id."
					  AND `rank` = ".(int) $newRank;

			if( claro_sql_query($sql) == false ) return false;

			$sql = "UPDATE `".$this->tblRelExerciseQuestion."`
					SET `rank` = ".(int) $newRank."
					WHERE `exerciseId` = ".(int) $this->id."
					  AND `questionId` = ".(int) $questionId;

			if( claro_sql_query($sql) == false ) return false;

			return true;
		}
		return false;
	}

	/**
     * add a question in the exercise
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return string
     */
	function addQuestion($questionId)
	{
		$rankMax = $this->getRankMax();
		$rank = $rankMax + 1 ;

		$sql = "INSERT INTO `".$this->tblRelExerciseQuestion."`

				SET `exerciseId` = ".(int) $this->id.",
					`questionId` = ".(int) $questionId.",
					`rank` = ".(int) $rank;

		return claro_sql_query($sql);
	}


	/**
     * remove a question from the exercise, the question stays available in question pool
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return string
     */
	function removeQuestion($questionId)
	{

		$sql = "DELETE FROM `".$this->tblRelExerciseQuestion."`
				WHERE `exerciseId` = ".(int) $this->id."
				AND `questionId` = ".(int) $questionId;

		return claro_sql_query($sql);
	}

 	/**
     * get id
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return int
     */
	function getId()
	{
		return (int) $this->id;
	}

	/**
     * get title
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return string
     */
	function getTitle()
	{
		return $this->title;
	}

	/**
     * set title
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param string $value
     */
	function setTitle($value)
	{
		$this->title = trim($value);
	}

	/**
     * get description
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return string
     */
	function getDescription()
	{
		return $this->description;
	}

	/**
     * set description
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param string $value
     */
	function setDescription($value)
	{
		$this->description = trim($value);
	}

	/**
     * get visibility ('VISIBLE', 'INVISIBLE')
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return string
     */
	function getVisibility()
	{
		return $this->visibility;
	}

	/**
     * set visibility
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param string $value
     */
	function setVisibility($value)
	{
		$acceptedValues = array('VISIBLE', 'INVISIBLE');

		if( in_array($value, $acceptedValues) )
		{
			$this->visibility = $value;
			return true;
		}
		return false;
	}

	/**
     * get display type ('SEQUENTIAL', 'ONEPAGE')
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return string
     */
	function getDisplayType()
	{
		return $this->displayType;
	}

	/**
     * set display type
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param string $value
     */
	function setDisplayType($value)
	{
		$acceptedValues = array('SEQUENTIAL', 'ONEPAGE');

		if( in_array($value, $acceptedValues) )
		{
			$this->displayType = $value;
			return true;
		}
		return false;
	}

	/**
     * get shuffle
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return int
     */
	function getShuffle()
	{
		return (int) $this->shuffle;
	}

	/**
     * set shuffle
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param int $value
     */
	function setShuffle($value)
	{
		$this->shuffle = (int) $value;
	}

	/**
     * get show answer ('ALWAYS', 'NEVER', 'LASTTRY')
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return string
     */
	function getShowAnswers()
	{
		return $this->showAnswers;
	}

	/**
     * set show answer
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param string $value
     */
	function setShowAnswers($value)
	{
		$acceptedValues = array('ALWAYS', 'NEVER', 'LASTTRY');

		if( in_array($value, $acceptedValues) )
		{
			$this->showAnswers = $value;
			return true;
		}
		return false;
	}


	/**
     * get start date (as unix timestamp)
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return integer a unix time stamp
     */
	function getStartDate()
	{
		return $this->startDate;
	}

	function setStartDate($value)
	{
		$this->startDate = $value;
	}

	/**
     * get end date (as unix timestamp)
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return integer a unix time stamp
     */
	function getEndDate()
	{
		return $this->endDate;
	}

	function setEndDate($value)
	{
		$this->endDate = $value;
	}

	/**
     * get time limit
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return int
     */
	function getTimeLimit()
	{
		return (int) $this->timeLimit;
	}

	/**
     * set time limit
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param int $value
     */
	function setTimeLimit($value)
	{
		$this->timeLimit = (int) $value;
	}

	/**
     * get attempts number
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return int
     */
	function getAttempts()
	{
		return (int) $this->attempts;
	}

	/**
     * set attempts number
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param int $value
     */
	function setAttempts($value)
	{
		$this->attempts = (int) $value;
	}

	/**
     * get show answer ('ALLOWED','NOTALLOWED')
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @return string
     */
	function getAnonymousAttempts()
	{
		return $this->anonymousAttempts;
	}

	/**
     * set show answer
     *
     * @author Sebastien Piraux <pir@cerdecam.be>
     * @param string $value
     */
	function setAnonymousAttempts($value)
	{
		$acceptedValues = array('ALLOWED','NOTALLOWED');

		if( in_array($value, $acceptedValues) )
		{
			$this->anonymousAttempts = $value;
			return true;
		}
		return false;
	}
}
?>
