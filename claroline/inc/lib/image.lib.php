<?php // $Id$
     
    //----------------------------------------------------------------------
    // CLAROLINE
    //----------------------------------------------------------------------
    // Copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
    //----------------------------------------------------------------------
    // This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
    // as published by the FREE SOFTWARE FOUNDATION. The GPL is available
    // through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
    //----------------------------------------------------------------------
    // Authors: see 'credits' file
    //----------------------------------------------------------------------
     
    /*============================================================================
    						IMAGE MANIPULATION LIBRARY
      ============================================================================*/
    
    /**
    * @private allowedImageTypes
	*/
    // allowed image extensions
    $allowedImageTypes = 'jpg|png|gif|jpeg|bmp';

    /**
    * cut string allowing word integrity preservation
    *
    * TODO : move to a more accurate library
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  string (string) string
    * @param  length (int) length of the resulting string
    * @param  allow_cut_word (boolean) allow word cutting default : true
    * @param  extra_length (int) allow extra length to the string to
    *		preserve word integrity
    * @param  ending (string) append the given string at the end of the
	*		cutted one
    * @return (string) the cutted string
    */
    function cutstring( $str, $length, $allow_cut_word = true, 
		$extra_length = 0, $ending = "" )
    {
        if( $allow_cut_word )
        {
            return substr( $str, 0, $length );
        }
        else
        {
            $words = preg_split( "~\s~", $str );
            
            $ret = "";
            
            foreach( $words as $word )
            {
                if( strlen( $ret . $word ) + 1 <= $length + $extra_length )
                {
                    $ret.= $word. " ";
                }
                else
                {
                    $ret = trim( $ret ) . $ending;
                    break;
                }
            }
            
            return $ret;
        }
    }
     
     
    /**
    * identifies images (i.e. if file extension is an allowed image extension)
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  string (string) file name
    * @return (bool) true if the given file is an image file
    *    else return false
    * @global allowedImageTypes
    * @see    images.lib.php#$allowedImagesType
    */
    function is_image($fileName)
    {
        global $allowedImageTypes;
         
        // if file extension is an allowed image extension
        if (eregi(".(" . $allowedImageTypes . ")$", $fileName))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
     
    /**
    * get image list from fileList
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  fileList (array) list of files in the current directory
    * @param  allowed (bool) true if current user is allowed to view invisible images
    * @return (array) array containing the index of image files in fileList
    * @see    document.php#$fileList
    */
    function get_image_list($fileList, $allowed = false)
    {
        $imageList = array();
         
        if (is_array($fileList))
        {
            foreach($fileList['name'] as $num => $value)
            {
                if (is_image($value )
                    && ($fileList['visibility'][$num] != 'i' || $allowed))
                {
                    $imageList[] = $num;
                }
            }
        }
         
        return $imageList;
    }
     
    /**
    * get image color depth from image info
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  img (string) path to image file
    * @return (int) image depth in bits
    * @see    document.php#$fileList
    */
    function get_image_color_depth($img)
    {
        $info = getimagesize($img);
        return $info['bits'];
    }
     
    // THE EVIL NASTY ONE !
    /**
    * create thumbnails end return html code to display it
    *
    * this function could be modified to use any other method to
    * create thumbnails
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  file (string) image name
    * @param  thumbWidth (int) width for thumbnails
    * @param  title (string) long description of the image
    * @return (string) html code to display thumbnail
    * @global curDirPath
    * @global coursesRepositoryWeb;
    * @global coursesRepositorySys;
    * @global _course;
    */
    function create_thumbnail($file, $thumbWidth, $title = '')
    {
        global $curDirPath;
        global $coursesRepositoryWeb;
        global $coursesRepositorySys;
        global $courseDir;
         
        $imgPath = $coursesRepositorySys 
			. $courseDir
			. $curDirPath . '/' . basename( $file )
			;
         
        list($width, $height, $type, $attr) = getimagesize($imgPath);
         
        if ($width > $thumbWidth)
        {
            $newHeight = round($height * $thumbWidth / $width);
        }
        else
        {
            $thumbWidth = $width;
            $newHeight = $height;
        }
         
        $fileUrl = $curDirPath . '/' . basename( $file );
        
        $img_url = $coursesRepositoryWeb
			. $courseDir
			. implode ("/", array_map("rawurlencode", explode("/", $fileUrl ) ) )
        	;
         
        return "<img src=\"" . $img_url 
			. "\" width=\"" . $thumbWidth 
			. "\" height=\"" . $newHeight 
			. "\" " . $title . " alt=\"" 
			. $file . "\" />\n"
			;
         
    }
    
    function image_search($file, $fileList)
    {
    	$fileList = array_map( 'basename', $fileList['name'] );
    	return array_search( $file, $fileList );
    }
     
    /*-------------------------------------------------------------------------------
                                 FUNCTIONS FOR IMAGE VIEWER
      -------------------------------------------------------------------------------*/
     
    /**
    * get the index of the current image in imageList from its index in fileList
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  imageList (array) list of index of image files in the current directory
    * @param  fileIndex (array) index of image in fileList
    * @return (int) index of current image in imageList
    * @see    document.php#$fileList
    */
    function get_current_index($imageList, $fileIndex)
    {
        $index = array_search($fileIndex, $imageList);
        return $index;
    }
     
    /**
    * return true if there one or more image after the current image in imageList
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  imageList (array) list of image indices
    * @param  index (int) index of current image in imageList
    * @return (bool) true if there is one or more images after the current image
    *              in imageList, else return false
    */
    function has_next_image($imageList, $index)
    {
        return (($index >= 0) && ($index < (count($imageList) - 1 )));
    }
     
    /**
    * return true if there one or more image before the current image in imageList
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  imageList (array) list of image indices
    * @param  index (int) index of current image in imageList
    * @return (bool) true if there is one or more images before the current image
    *              in imageList, else return false
    */
    function has_previous_image($imageList, $index)
    {
        return (($index > 0) && (count($imageList) > 0));
    }
     
    /**
    * return the index of the next image in imageList
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  imageList (array) list of image indices
    * @param  index (int) index of current image in imageList
    * @return (int) index of the next image in imageList
    */
    function get_next_image_index($imageList, $index)
    {
        // @pre index is a valid index (ie 0 <= index < sizeof(imageList)
        // @pre imageList is not empty and has at least one element after index
        return $imageList[$index + 1];
        // @post return next index in imageList
    }
     
    /**
    * return the index of the previous image in imageList
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  imageList (array) list of image indices
    * @param  index (int) index of current image in imageList
    * @return (int) index of the previous image in imageList
    */
    function get_previous_image_index($imageList, $index)
    {
        // @pre index is a valid index (ie 0 <= index < sizeof(imageList)
        // @pre index is not the first index of imageList (ie index > 0)
        // @pre imageList is not empty
        return $imageList[$index - 1];
        // @post return previous index in imageList
    }
     
    /**
    * display link and thumbnail of previous image
    * TODO : see if this function can be merge with display_link_to_next_image
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  imageList (array) list of image indices
    * @param  fileList (array) list of files in the current directory
    * @param  current (int) index of current image in imageList
    * @global curDirPath
    * @global thumbnailWidth
    */
    function display_link_to_previous_image($imageList, $fileList, $current)
    {
        global $curDirPath;
        global $thumbnailWidth;
         
        // get previous image
        $prev;
        $prevStyle = 'prev';
         
        if (has_previous_image($imageList, $current))
        {
            $prev = get_previous_image_index($imageList, $current);
             
            $prevName = $fileList['name'][$prev];
             
            if ($fileList['visibility'][$prev] == 'i')
            {
                $prevStyle = 'prev invisible';
            }
             
            echo "<th class=\"". $prevStyle 
				. "\" width=\"30%\">\n"
				;
             
            echo "<a href=\"" . $_SERVER['PHP_SELF'] . "?cmd=viewImage&file=" 
				. urlencode($prevName) . "&curdir=" . $curDirPath 
				. "\">", "&lt;&lt;&nbsp;" . basename($prevName) . "</a>\n"
				;
				
			echo "<br /><br />\n";
             
            // display thumbnail
            echo "<a href=\"" . $_SERVER['PHP_SELF'] 
				. "?cmd=viewImage&file=" . urlencode($prevName)
            	. "&curdir=" . $curDirPath . "\">" 
				. create_thumbnail($prevName, $thumbnailWidth)
            	."</a>\n"
				;
             
            echo "</th>\n";
        }
        else
        {
            echo "<th class=\"". $prevStyle . "\" width=\"30%\">\n" 
				. "<!-- empty -->\n" . "</th>\n"
				;
        } // end if has previous image
    }
     
    /**
    * display link and thumbnail of next image
    * TODO : see if this function can be merge with display_link_to_previous_image
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  imageList (array) list of image indices
    * @param  fileList (array) list of files in the current directory
    * @param  current (int) index of current image in imageList
    * @global curDirPath
    * @global thumbnailWidth
    */
    function display_link_to_next_image($imageList, $fileList, $current)
    {
        global $curDirPath;
        global $thumbnailWidth;
         
        // get next image
        $next;
        $nextStyle = 'next';
         
        if (has_next_image($imageList, $current))
        {
            $next = get_next_image_index($imageList, $current);
             
            $nextName = $fileList['name'][$next];
             
            if ($fileList['visibility'][$next] == 'i')
            {
                $nextStyle = 'next invisible';
            }
             
            echo "<th class=\"". $nextStyle . "\" width=\"30%\">\n";
             
            echo "<a href=\"" . $_SERVER['PHP_SELF'] 
				. "?cmd=viewImage&file=" . urlencode($nextName)
            	. "&curdir=" . $curDirPath ."\">". basename($nextName) 
				. "&nbsp;&gt;&gt;</a>\n"
				;
				
			echo "<br /><br />\n";
             
            // display thumbnail
            echo "<a href=\"" . $_SERVER['PHP_SELF'] 
				. "?cmd=viewImage&file=" . urlencode($nextName)
            	. "&curdir=" . $curDirPath . "\">" 
				. create_thumbnail($nextName, $thumbnailWidth)
            	. "</a>\n"
				;
             
            echo "</th>\n";
        }
        else
        {
            echo "<th class=\"". $nextStyle . "\" width=\"30%\">";
            echo "<!-- empty -->\n";
            echo "</th>\n";
        } // enf if previous image
         
    }
     
     
    /*-------------------------------------------------------------------------------
                            FUNCTIONS FOR THUMBNAILS VIEWER
      -------------------------------------------------------------------------------*/
     
    /**
    * return true if there are one or more pages left to display after the current one
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  imageList (array) list of image indices
    * @param  page (int) number of current page
    * @return (bool) true if there are one or more pages left to display after the current one
    */
    function has_next_page($imageList, $page)
    {
        global $numberOfCols;
        global $numberOfRows;
         
        if (($page * $numberOfCols * $numberOfRows) < count($imageList))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
     
    /**
    * return true if there one or more pages left to display before the current one
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  imageList (array) list of image indices
    * @param  index (int) index of current image in imageList
    * @return (bool) true if there are one or more pages left to display before the current one
    */
    function has_previous_page($imageList, $page)
    {
        return ($page != 1 && count($imageList) != 0);
    }
     
    /**
    * return the index of the first image of the given page in imageList
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  page (int) number of the page
    * @return (int) index of the first image of the given page in imageList
    */
    function get_offset($page)
    {
        global $numberOfCols;
        global $numberOfRows;
         
        if ($page == 1)
        {
            $offset = 0;
        }
        else
        {
            $offset = (($page - 1) * $numberOfCols * $numberOfRows);
        }
         
        return $offset;
    }
     
    /**
    * return the number of the page on which the image is located
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param  offset (int) index of the image in imageList
    * @return (int) number of the page on which the image is located
    */
    function get_page_number($offset)
    {
        global $numberOfCols;
        global $numberOfRows;
         
        $page = floor($offset / ($numberOfCols * $numberOfRows)) + 1;
         
        return $page;
    }
     
    /**
    * display a page of thumbnails
    *
    * @author Frederic Minne <minne@ipm.ucl.ac.be>
    * @param imageList (array) list containing all image file names
    * @param fileList (array) file properties
    * @param page (int) current page number
    * @param thumbnailWidth (int) width of thumbnails
    * @param colWidth (int) width of columns
    * @param numberOfCols (int) number of columns
    * @param numberOfRows (int) number of rows
    * @global curDirPath
    */
    function display_thumbnails($imageList, $fileList, $page 
		, $thumbnailWidth, $colWidth, $numberOfCols, $numberOfRows)
    {
        global $curDirPath;
         
        // get index of first thumbnail on the page
        $displayed = get_offset($page);
         
        // loop on rows
        for($rows = 0; $rows < $numberOfRows; $rows++)
        {
            echo "<tr>\n";
             
            // loop on columns
            for($cols = 0; $cols < $numberOfCols; $cols++)
            {
                // get index of image
                $num = $imageList[$displayed];
                 
                // get file name
                $fileName = basename( $fileList['name'][$num] );
                 
                // visibility style
                if ($fileList['visibility'][$num] == i)
                {
                    $style = "style=\"font-style: italic; color: silver;\"";
                }
                else
                {
                    $style = '';
                }
                 
                // display thumbnail
                echo "<td style=\"text-align: center;\" style=\"width:" 
					. $colWidth . "%;\">\n" 
					;
                 
                echo "<a href=\"". $_SERVER['PHP_SELF'] . "?cmd=viewImage&file=" 
					. urlencode($fileName)
                	. "&curdir=". $curDirPath ."\">" 
					;
                 
                // display image description using title attribute
                if ($fileList['comment'][$num] )
                {
                    $text = $fileList['comment'][$num];
                     
                    /*if (strlen($text ) > 30 )
                    {
                        $text = substr($text , 0, 30)
                        	. "..."
							;
                    }*/
                    
                    $text = cutstring( $text, 40, false, 5, "..." );
                     
                    $title = "title=\"" . $text . "\"";
                }
                 
                echo create_thumbnail($fileName, $thumbnailWidth, $title);
                 
                // unset title for the next pass in the loop
                unset($title );
                 
                echo "</a>\n";
                 
                // display image name
                echo "<p " . $style . ">" . basename( $fileList['name'][$num] ) . "</p>";
                 
                echo "</td>\n";
                 
                // update image number
                $displayed++;
                 
                // finished ?
                if ($displayed >= count($imageList))
                {
                	echo "</tr>\n";
                    return;
                }
            } // end loop on columns
             
            echo "</tr>\n";
             
             
        } // end loop on rows
    }
     
?>
