<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.3.2 $Revision$                            |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2003 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or      |
      |   modify it under the terms of the GNU General Public License        |
      |   as published by the Free Software Foundation; either version 2     |
      |   of the License, or (at your option) any later version.             |
      +----------------------------------------------------------------------+
      | Authors: Olivier Brouckaert <oli.brouckaert@skynet.be>               |
      +----------------------------------------------------------------------+
*/

if(!class_exists('Exercise')):

		/*>>>>>>>>>>>>>>>>>>>> CLASS EXERCISE <<<<<<<<<<<<<<<<<<<<*/

/**
 * This class allows to instantiate an object of type Exercise
 *
 * @author - Olivier Brouckaert
 */
class Exercise
{
	var $id;
	var $exercise;
	var $description;
	var $type;
	var $random;
	var $active;
	
	var $maxTime;
	var $maxAttempt;
	var $showAnon;
	var $showAnswer;

	var $questionList;  // array with the list of this exercise's questions

	/**
	 * constructor of the class
	 *
	 * @author - Olivier Brouckaert
	 */
	function Exercise()
	{
		$this->id			= 0;
		$this->exercise		= '';
		$this->description	= '';
		$this->type			= 1;
		$this->random		= 0;
		$this->active		= 0;

		$this->maxTime		= 0;
		$this->maxAttempt	= 0;
		$this->showAnon		= '';
		$this->showAnswer	= '';

		$this->questionList=array();
	}

	/**
	 * reads exercise informations from the data base
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $id - exercise ID
	 * @return - boolean - true if exercise exists, otherwise false
	 */
	function read($id)
	{
		global $TBL_EXERCICES, $TBL_EXERCICE_QUESTION, $TBL_QUESTIONS;

		$sql="	SELECT 	`titre`,`description`,
				`type`,`random`,`active`,
				`max_time`,`max_attempt`,`show_anon`,`show_answer`
			FROM `$TBL_EXERCICES` 
			WHERE `id` = '$id'";
		$result=mysql_query($sql) or die("Error : SELECT in file ".__FILE__." at line ".__LINE__);

		// if the exercise has been found
		if($object=mysql_fetch_object($result))
		{
			$this->id 			= $id;
			$this->exercise 	= $object->titre;
			$this->description 	= $object->description;
			$this->type	 		= $object->type;
			$this->random 		= $object->random;
			$this->active 		= $object->active;
			
			$this->maxTime 		= $object->max_time;			
			$this->maxAttempt	= $object->max_attempt;
			$this->showAnon		= $object->show_anon;
			$this->showAnswer	= $object->show_answer;
			
			$sql="	SELECT 	`question_id`,`q_position` 
				FROM `$TBL_EXERCICE_QUESTION`,`$TBL_QUESTIONS`
				WHERE `question_id` = `id` AND `exercice_id` = '$id' 
				ORDER BY `q_position`";
			$result=mysql_query($sql) or die("Error : SELECT in file ".__FILE__." at line ".__LINE__);

			// fills the array with the question ID for this exercise
			// the key of the array is the question position
			while($object=mysql_fetch_object($result))
			{
				// makes sure that the question position is unique
				while(isset($this->questionList[$object->q_position]))
				{
					$object->q_position++;
				}

				$this->questionList[$object->q_position]=$object->question_id;
			}

			return true;
		}

		// exercise not found
		return false;
	}

	/**
	 * returns the exercise ID
	 *
	 * @author - Olivier Brouckaert
	 * @return - integer - exercise ID
	 */
	function selectId()
	{
		return $this->id;
	}

	/**
	 * returns the exercise title
	 *
	 * @author - Olivier Brouckaert
	 * @return - string - exercise title
	 */
	function selectTitle()
	{
		return $this->exercise;
	}

	/**
	 * returns the exercise description
	 *
	 * @author - Olivier Brouckaert
	 * @return - string - exercise description
	 */
	function selectDescription()
	{
		return $this->description;
	}

	/**
	 * returns the exercise type
	 *
	 * @author - Olivier Brouckaert
	 * @return - integer - exercise type
	 */
	function selectType()
	{
		return $this->type;
	}

	/**
	 * tells if questions are selected randomly, and if so returns the draws
	 *
	 * @author - Olivier Brouckaert
	 * @return - integer - 0 if not random, otherwise the draws
	 */
	function isRandom()
	{
		return $this->random;
	}

	/**
	 * returns the exercise status (1 = enabled ; 0 = disabled)
	 *
	 * @author - Olivier Brouckaert
	 * @return - boolean - true if enabled, otherwise false
	 */
	function selectStatus()
	{
		return $this->active;
	}

	/**
	 * returns the max allowed time to complete the exercise
	 * 
	 * @author - Piraux Sebastien <pir@cerdecam.be>
	 * @return - integer - max allowed time (in seconds)
	 */
	function get_max_time()
	{
		return $this->maxTime;
	}
	
	/**
	 * returns the max allowed attemps to complete the exercise
	 * 
	 * @author - Piraux Sebastien <pir@cerdecam.be>
	 * @return - integer - max allowed attempts count
	 */
	function get_max_attempt()
	{
		return $this->maxAttempt;
	}

	/**
	 * tells if the exercise is accessible to non course registered users
	 * 
	 * @author - Piraux Sebastien <pir@cerdecam.be>
	 * @return - boolean - 0 if hidden to anonymous users, 1 if show
	 */
	function get_show_anon()
	{
		if($this->showAnon == 'SHOW')
		{
			return true;
		}
		else
		{
			 return false;
		}
	}

	/**
	 * returns when the answers have to be shown
	 * 
	 * @author - Piraux Sebastien <pir@cerdecam.be>
	 * @return - string - string representation of the condition when 
	 *					answers have to be showned. e.g. : ALWAYS, NEVER, LASTTRY
	 */
	function get_show_answer()
	{
		return $this->showAnswer;
	}

	/**
	 * returns the array with the question ID list
	 *
	 * @author - Olivier Brouckaert
	 * @return - array - question ID list
	 */
	function selectQuestionList()
	{
		return $this->questionList;
	}

	/**
	 * returns the number of questions in this exercise
	 *
	 * @author - Olivier Brouckaert
	 * @return - integer - number of questions
	 */
	function selectNbrQuestions()
	{
		return sizeof($this->questionList);
	}

	/**
     * selects questions randomly in the question list
	 *
	 * @author - Olivier Brouckaert
	 * @return - array - if the exercise is not set to take questions randomly, returns the question list
	 *					 without randomizing, otherwise, returns the list with questions selected randomly
     */
	function selectRandomList()
	{
		// if the exercise is not a random exercise, or if there are not at least 2 questions
		if(!$this->random || $this->selectNbrQuestions() < 2)
		{
			return $this->questionList;
		}

		// takes all questions
		if($this->random == -1 || $this->random > $this->selectNbrQuestions())
		{
			$draws=$this->selectNbrQuestions();
		}
		else
		{
			$draws=$this->random;
		}

		srand((double)microtime()*1000000);

		$randQuestionList=array();
		$alreadyChosed=array();

		// loop for the number of draws
		for($i=0;$i < $draws;$i++)
		{
			// selects a question randomly
			do
			{
				$rand=rand(0,$this->selectNbrQuestions()-1);
			}
			// if the question has already been selected, continues in the loop
			while(in_array($rand,$alreadyChosed));

			$alreadyChosed[]=$rand;

			$j=0;

			foreach($this->questionList as $key=>$val)
			{
				// if we have found the question chosed above
				if($j == $rand)
				{
					$randQuestionList[$key]=$val;
					break;
				}

				$j++;
			}
		}

		return $randQuestionList;
	}

	/**
	 * returns 'true' if the question ID is in the question list
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $questionId - question ID
	 * @return - boolean - true if in the list, otherwise false
	 */
	function isInList($questionId)
	{
		return in_array($questionId,$this->questionList);
	}

	/**
	 * changes the exercise title
	 *
	 * @author - Olivier Brouckaert
	 * @param - string $title - exercise title
	 */
	function updateTitle($title)
	{
		$this->exercise=$title;
	}

	/**
	 * changes the exercise description
	 *
	 * @author - Olivier Brouckaert
	 * @param - string $description - exercise description
	 */
	function updateDescription($description)
	{
		$this->description=$description;
	}

	/**
	 * changes the exercise type
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $type - exercise type
	 */
	function updateType($type)
	{
		$this->type=$type;
	}

	/**
	 * sets to 0 if questions are not selected randomly
	 * if questions are selected randomly, sets the draws
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $random - 0 if not random, otherwise the draws
	 */
	function setRandom($random)
	{
		$this->random=$random;
	}

	/**
	 * enables the exercise
	 *
	 * @author - Olivier Brouckaert
	 */
	function enable()
	{
		$this->active=1;
	}

	/**
	 * disables the exercise
	 *
	 * @author - Olivier Brouckaert
	 */
	function disable()
	{
		$this->active=0;
	}

	/**
	 * set max allowed time to complete the exercise
   	 *
	 * @author Piraux Sebastien <pir@cerdecam.be>
	 * @param integer time in seconds
	 *
	 */
	function set_max_time($time)
	{
		$this->maxTime = $time;
		return true;
	}
	
	function set_max_attempt($attemptQty)
	{
		$this->maxAttempt = $attemptQty;
		return true;
	}

	function set_show_anon()
	{
		$this->showAnon = 'SHOW';
		return true;
	}
	
	function set_hide_anon()
	{
		$this->showAnon = 'HIDE';
		return true;
	}

	function set_show_answer($showType)
	{
		$this->showAnswer = $showType;
		return true;
	}
	
	/**
	 * updates the exercise in the data base
	 *
	 * @author - Olivier Brouckaert
	 */
	function save()
	{
		global $TBL_EXERCICES, $TBL_QUESTIONS;

		$id				= $this->id;
		$exercise		= addslashes($this->exercise);
		$description	= addslashes($this->description);
		$type			= $this->type;
		$random			= $this->random;
		$active			= $this->active;

		$maxTime 		= $this->maxTime;
		$maxAttempt		= $this->maxAttempt;
		$showAnon		= $this->showAnon;
		$showAnswer		= $this->showAnswer;

		// exercise already exists
		if($id)
		{
			$sql = "UPDATE `$TBL_EXERCICES` 
					SET `titre` = '$exercise',
						`description` = '$description',
						`type` = '$type',
 						`random` = '$random',
						`active` = '$active',
						`max_time` = $maxTime, 
						`max_attempt` = $maxAttempt,
						`show_anon` = '$showAnon',
						`show_answer` = '$showAnswer'
					WHERE `id` = '$id'";
			mysql_query($sql) or die("Error : UPDATE in file ".__FILE__." at line ".__LINE__);
		}
		// creates a new exercise
		else
		{
			$sql=	"INSERT INTO `$TBL_EXERCICES`
					(`titre`,`description`,`type`,`random`,`active`,
					 `max_time`, `max_attempt`, `show_anon`, `show_answer`) 
					VALUES('$exercise','$description','$type','$random','$active',
							$maxTime,$maxAttempt,'$showAnon','$showAnswer')";
			mysql_query($sql) or die("Error : INSERT in file ".__FILE__." at line ".__LINE__);

			$this->id=mysql_insert_id();
		}

		// updates the question position
		foreach($this->questionList as $position=>$questionId)
		{
			$sql="UPDATE `$TBL_QUESTIONS` SET `q_position` = '$position' WHERE `id` = '$questionId'";
			mysql_query($sql) or die("Error : UPDATE in file ".__FILE__." at line ".__LINE__);
		}
	}

	/**
	 * moves a question up in the list
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $id - question ID to move up
	 */
	function moveUp($id)
	{
		foreach($this->questionList as $position=>$questionId)
		{
			// if question ID found
			if($questionId == $id)
			{
				// position of question in the array
				$pos1=$position;

				prev($this->questionList);

				// position of previous question in the array
				$pos2=key($this->questionList);

				// error, can't move question
				if(!$pos2)
				{
					return;
				}

				$id2=$this->questionList[$pos2];

				// exits foreach()
				break;
			}

			// goes to next question
			next($this->questionList);
		}

		// permutes questions in the array
		$temp=$this->questionList[$pos2];
		$this->questionList[$pos2]=$this->questionList[$pos1];
		$this->questionList[$pos1]=$temp;
	}

	/**
	 * moves a question down in the list
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $id - question ID to move down
	 */
	function moveDown($id)
	{
		foreach($this->questionList as $position=>$questionId)
		{
			// if question ID found
			if($questionId == $id)
			{
				// position of question in the array
				$pos1=$position;

				next($this->questionList);

				// position of next question in the array
				$pos2=key($this->questionList);

				// error, can't move question
				if(!$pos2)
				{
					return;
				}

				$id2=$this->questionList[$pos2];

				// exits foreach()
				break;
			}

			// goes to next question
			next($this->questionList);
		}

		// permutes questions in the array
		$temp=$this->questionList[$pos2];
		$this->questionList[$pos2]=$this->questionList[$pos1];
		$this->questionList[$pos1]=$temp;
	}

	/**
	 * adds a question into the question list
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $questionId - question ID
	 * @return - boolean - true if the question has been added, otherwise false
	 */
	function addToList($questionId)
	{
		// checks if the question ID is not in the list
		if(!$this->isInList($questionId))
		{
			// selects the max position
			if(!$this->selectNbrQuestions())
			{
				$pos=1;
			}
			else
			{
				$pos=max(array_keys($this->questionList))+1;
			}

			$this->questionList[$pos]=$questionId;

			return true;
		}

		return false;
	}

	/**
	 * removes a question from the question list
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $questionId - question ID
	 * @return - boolean - true if the question has been removed, otherwise false
	 */
	function removeFromList($questionId)
	{
		// searches the position of the question ID in the list
		$pos=array_search($questionId,$this->questionList);

		// question not found
		if($pos === false)
		{
			return false;
		}
		else
		{
			// deletes the position from the array containing the wanted question ID
			unset($this->questionList[$pos]);

			return true;
		}
	}

	/**
	 * deletes the exercise from the database
	 * Notice : leaves the question in the data base
	 *
	 * @author - Olivier Brouckaert
	 */
	function delete()
	{
		global $TBL_EXERCICE_QUESTION, $TBL_EXERCICES;

		$id=$this->id;

		$sql="DELETE FROM `$TBL_EXERCICE_QUESTION` WHERE exercice_id='$id'";
		mysql_query($sql) or die("Error : DELETE in file ".__FILE__." at line ".__LINE__);

		$sql="DELETE FROM `$TBL_EXERCICES` WHERE id='$id'";
		mysql_query($sql) or die("Error : DELETE in file ".__FILE__." at line ".__LINE__);
	}
}

endif;
?>
