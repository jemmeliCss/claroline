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

if(!class_exists('Question')):

		/*>>>>>>>>>>>>>>>>>>>> CLASS QUESTION <<<<<<<<<<<<<<<<<<<<*/

/**
 * This class allows to instantiate an object of type Question
 */
class Question
{
	var $id;
	var $question;
	var $description;
	var $weighting;
	var $position;
	var $type;
        var $pictureName;

	var $exerciseList;  // array with the list of exercises which this question is in

	/**
	 * constructor of the class
	 *
	 * @author - Olivier Brouckaert
	 */
	function Question()
	{
		$this->id=0;
		$this->question='';
		$this->description='';
		$this->weighting=0;
		$this->position=1;
		$this->type=2;
                $this->pictureName='';

		$this->exerciseList=array();
	}

	/**
	 * reads question informations from the data base
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $id - question ID
	 * @return - boolean - true if question exists, otherwise false
	 */
	function read($id)
	{
		global $TBL_QUESTIONS, $TBL_EXERCICE_QUESTION;

		$sql="SELECT question,description,ponderation,q_position,type,picture_name FROM `$TBL_QUESTIONS` WHERE id='$id'";
		$result=mysql_query($sql) or die("Error : SELECT in file ".__FILE__." at line ".__LINE__);

		// if the question has been found
		if($object=mysql_fetch_object($result))
		{
			$this->id=$id;
			$this->question=$object->question;
			$this->description=$object->description;
			$this->weighting=$object->ponderation;
			$this->position=$object->q_position;
			$this->type=$object->type;
                        $this->pictureName=$object->picture_name;

			$sql="SELECT exercice_id FROM `$TBL_EXERCICE_QUESTION` WHERE question_id='$id'";
			$result=mysql_query($sql) or die("Error : SELECT in file ".__FILE__." at line ".__LINE__);

			// fills the array with the exercises which this question is in
			while($object=mysql_fetch_object($result))
			{
				$this->exerciseList[]=$object->exercice_id;
			}

			return true;
		}

		// question not found
		return false;
	}

	/**
	 * returns the question ID
	 *
	 * @author - Olivier Brouckaert
	 * @return - integer - question ID
	 */
	function selectId()
	{
		return $this->id;
	}

	/**
	 * returns the question title
	 *
	 * @author - Olivier Brouckaert
	 * @return - string - question title
	 */
	function selectTitle()
	{
		return $this->question;
	}

	/**
	 * returns the question description
	 *
	 * @author - Olivier Brouckaert
	 * @return - string - question description
	 */
	function selectDescription()
	{
		return $this->description;
	}

	/**
	 * returns the question weighting
	 *
	 * @author - Olivier Brouckaert
	 * @return - integer - question weighting
	 */
	function selectWeighting()
	{
		return $this->weighting;
	}

	/**
	 * returns the question position
	 *
	 * @author - Olivier Brouckaert
	 * @return - integer - question position
	 */
	function selectPosition()
	{
		return $this->position;
	}

	/**
	 * returns the answer type
	 *
	 * @author - Olivier Brouckaert
	 * @return - integer - answer type
	 */
	function selectType()
	{
		return $this->type;
	}

	/**
	 * returns the array with the exercise ID list
	 *
	 * @author - Olivier Brouckaert
	 * @return - array - list of exercise ID which the question is in
	 */
	function selectExerciseList()
	{
		return $this->exerciseList;
	}

	/**
	 * returns the number of exercises which this question is in
	 *
	 * @author - Olivier Brouckaert
	 * @return - integer - number of exercises
	 */
	function selectNbrExercises()
	{
		return sizeof($this->exerciseList);
	}
        
        /**
         * returns the picture name
         *
         */
         
        function selectPictureName() 
        {
             return $this->pictureName;
        }
         

	/**
	 * changes the question title
	 *
	 * @author - Olivier Brouckaert
	 * @param - string $title - question title
	 */
	function updateTitle($title)
	{
		$this->question=$title;
	}

	/**
	 * changes the question description
	 *
	 * @author - Olivier Brouckaert
	 * @param - string $description - question description
	 */
	function updateDescription($description)
	{
		$this->description=$description;
	}

	/**
	 * changes the question weighting
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $weighting - question weighting
	 */
	function updateWeighting($weighting)
	{
		$this->weighting=$weighting;
	}

	/**
	 * changes the question position
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $position - question position
	 */
	function updatePosition($position)
	{
		$this->position=$position;
	}

	/**
	 * changes the answer type. If the user changes the type from "unique answer" to "multiple answers"
	 * (or conversely) answers are not deleted, otherwise yes
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $type - answer type
	 */
	function updateType($type)
	{
		global $TBL_REPONSES;

		// if we really change the type
		if($type != $this->type)
		{
			// if we don't change from "unique answer" to "multiple answers" (or conversely)
			if(!in_array($this->type,array(UNIQUE_ANSWER,MULTIPLE_ANSWER)) || !in_array($type,array(UNIQUE_ANSWER,MULTIPLE_ANSWER)))
			{
				// removes old answers
				$sql="DELETE FROM `$TBL_REPONSES` WHERE question_id='".$this->id."'";
				mysql_query($sql) or die("Error : DELETE in file ".__FILE__." at line ".__LINE__);
			}

			$this->type=$type;
		}
	}

	/**
	 * adds a picture to the question
	 *
	 * @author - Olivier Brouckaert
	 * @param - string $Picture - temporary path of the picture to upload
         * @param - string $PictureName - Name of the Picture
	 * @return - boolean - true if uploaded, otherwise false
	 */
	function uploadPicture($Picture,$PictureName)
	{
		global $picturePathSys;

		// if the question has got an ID
		if($this->id)
		{
                        $extension=substr(strrchr($PictureName, '.'), 1);
                        
                        $this->pictureName='quiz-'.$this->id.'.'.$extension;
                        
	  		return @move_uploaded_file($Picture,$picturePathSys.'/'.$this->pictureName)?true:false;
		}

		return false;
	}

	/**
	 * deletes the picture
	 *
	 * @author - Olivier Brouckaert
	 * @return - boolean - true if removed, otherwise false
	 */
	function removePicture()
	{
		global $picturePathSys;

		// if the question has got an ID and if the picture exists
		if($this->id && !empty($this->pictureName))
		{
                        $pictureName=$this->pictureName;
                        $this->pictureName='';
                        
			return @unlink($picturePathSys.'/'.$pictureName)?true:false;
		}

		return false;
	}

	/**
	 * exports a picture to another question
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $questionId - ID of the target question
	 * @return - boolean - true if copied, otherwise false
	 */
	function exportPicture($questionId)
	{
		global $TBL_QUESTIONS,$picturePathSys;

		// if the question has got an ID and if the picture exists
		if($this->id &&  !empty($this->pictureName))
                {
                    $extension=substr(strrchr($this->pictureName, '.'), 1);
                    $pictureName='quiz-'.$questionId.'.'.$extension;
                    
                    $sql="UPDATE `$TBL_QUESTIONS` SET picture_name='$pictureName' WHERE id='$questionId'";
                    mysql_query($sql) or die("Error : UPDATE in file ".__FILE__." at line ".__LINE__);
                    
                     return @copy($picturePathSys.'/'.$this->pictureName,$picturePathSys.'/'.$pictureName)?true:false;
		}

		return false;
	}

	/**
	 * saves the picture coming from POST into a temporary file
	 * Temporary pictures are used when we don't want to save a picture right after a form submission.
	 * For example, if we first show a confirmation box.
	 *
	 * @author - Olivier Brouckaert
	 * @param - string $Picture - temporary path of the picture to move
	 */
	function setTmpPicture($Picture,$PictureName)
	{
		global $picturePathSys;
                
                $extension=substr(strrchr($PictureName, '.'), 1);

		// saves the picture into a temporary file
		@move_uploaded_file($Picture,$picturePathSys.'/tmp.'.$extension);
	}

	/**
	 * moves the temporary question "tmp" to "quiz-$questionId.$extension"
	 * Temporary pictures are used when we don't want to save a picture right after a form submission.
	 * For example, if we first show a confirmation box.
	 *
	 * @author - Olivier Brouckaert
	 * @return - boolean - true if moved, otherwise false
	 */
	function getTmpPicture()
	{
		global $picturePathSys;
		// if the question has got an ID and if the picture exists
		if($this->id)
		{
                    if(file_exists($picturePathSys.'/tmp.jpg'))
                    {
                        $extension='jpg';
                    }
                    elseif(file_exists($picturePathSys.'/tmp.png'))
                    {
                        $extension='png';
                    }
                    elseif(file_exists($picturePathSys.'/tmp.gif'))
                    {
                        $extension='gif';
                    }
                    elseif(file_exists($picturePathSys.'/tmp.bmp'))
                    {
                        $extension='bmp';
                    }
                    $this->pictureName='quiz-'.$this->id.'.'.$extension;
                    return @rename($picturePathSys.'/tmp.'.$extension,$picturePathSys.'/'.$this->pictureName)?true:false;
		}

		return false;
	}

	/**
	 * updates the question in the data base
	 * if an exercise ID is provided, we add that exercise ID into the exercise list
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $exerciseId - exercise ID if saving in an exercise
	 */
	function save($exerciseId=0)
	{
		global $TBL_QUESTIONS;

		$id=$this->id;
		$question=addslashes($this->question);
		$description=addslashes($this->description);
		$weighting=$this->weighting;
		$position=$this->position;
		$type=$this->type;
                $pictureName=$this->pictureName;

		// question already exists
		if($id)
		{
			$sql="UPDATE `$TBL_QUESTIONS` SET question='$question',description='$description',ponderation='$weighting',q_position='$position',type='$type',picture_name='$pictureName' WHERE id='$id'";
			mysql_query($sql) or die("Error : UPDATE in file ".__FILE__." at line ".__LINE__);
		}
		// creates a new question
		else
		{
			$sql="INSERT INTO `$TBL_QUESTIONS`(question,description,ponderation,q_position,type,picture_name) VALUES('$question','$description','$weighting','$position','$type','$pictureName')";
			mysql_query($sql) or die("Error : INSERT in file ".__FILE__." at line ".__LINE__);

			$this->id=mysql_insert_id();
		}

		// if the question is created in an exercise
		if($exerciseId)
		{
			// adds the exercise into the exercise list of this question
			$this->addToList($exerciseId);
		}
	}

	/**
	 * adds an exercise into the exercise list
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $exerciseId - exercise ID
	 */
	function addToList($exerciseId)
	{
		global $TBL_EXERCICE_QUESTION;

		$id=$this->id;

		// checks if the exercise ID is not in the list
		if(!in_array($exerciseId,$this->exerciseList))
		{
			$this->exerciseList[]=$exerciseId;

			$sql="INSERT INTO `$TBL_EXERCICE_QUESTION`(question_id,exercice_id) VALUES('$id','$exerciseId')";
			mysql_query($sql) or die("Error : INSERT in file ".__FILE__." at line ".__LINE__);
		}
	}

	/**
	 * removes an exercise from the exercise list
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $exerciseId - exercise ID
	 * @return - boolean - true if removed, otherwise false
	 */
	function removeFromList($exerciseId)
	{
		global $TBL_EXERCICE_QUESTION;

		$id=$this->id;

		// searches the position of the exercise ID in the list
		$pos=array_search($exerciseId,$this->exerciseList);

		// exercise not found
		if($pos === false)
		{
			return false;
		}
		else
		{
			// deletes the position in the array containing the wanted exercise ID
			unset($this->exerciseList[$pos]);

			$sql="DELETE FROM `$TBL_EXERCICE_QUESTION` WHERE question_id='$id' AND exercice_id='$exerciseId'";
			mysql_query($sql) or die("Error : DELETE in file ".__FILE__." at line ".__LINE__);

			return true;
		}
	}

	/**
	 * deletes a question from the database
	 * the parameter tells if the question is removed from all exercises (value = 0),
	 * or just from one exercise (value = exercise ID)
	 *
	 * @author - Olivier Brouckaert
	 * @param - integer $deleteFromEx - exercise ID if the question is only removed from one exercise
	 */
	function delete($deleteFromEx=0)
	{
		global $TBL_EXERCICE_QUESTION, $TBL_QUESTIONS, $TBL_REPONSES;

		$id=$this->id;

		// if the question must be removed from all exercises
		if(!$deleteFromEx)
		{
			$sql="DELETE FROM `$TBL_EXERCICE_QUESTION` WHERE question_id='$id'";
			mysql_query($sql) or die("Error : DELETE in file ".__FILE__." at line ".__LINE__);

			$sql="DELETE FROM `$TBL_QUESTIONS` WHERE id='$id'";
			mysql_query($sql) or die("Error : DELETE in file ".__FILE__." at line ".__LINE__);

			$sql="DELETE FROM `$TBL_REPONSES` WHERE question_id='$id'";
			mysql_query($sql) or die("Error : DELETE in file ".__FILE__." at line ".__LINE__);

			$this->removePicture();

			// resets the object
			$this->Question();
		}
		// just removes the exercise from the list
		else
		{
			$this->removeFromList($deleteFromEx);
		}
	}

	/**
	 * duplicates the question
	 *
	 * @author - Olivier Brouckaert
	 * @return - integer - ID of the new question
	 */
	function duplicate()
	{
		global $TBL_QUESTIONS;

		$question=addslashes($this->question);
		$description=addslashes($this->description);
		$weighting=$this->weighting;
		$position=$this->position;
		$type=$this->type;

		$sql="INSERT INTO `$TBL_QUESTIONS`(question,description,ponderation,q_position,type) VALUES('$question','$description','$weighting','$position','$type')";
		mysql_query($sql) or die("Error : INSERT in file ".__FILE__." at line ".__LINE__);

		$id=mysql_insert_id();

		// duplicates the picture
		$this->exportPicture($id);

		return $id;
	}
}

endif;
?>
