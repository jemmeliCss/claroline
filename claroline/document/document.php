<?php // $Id$

//----------------------------------------------------------------------
// CLAROLINE
//----------------------------------------------------------------------
// Copyright (c) 2001-2003 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available 
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

/*

  DESCRIPTION:
  ****
  This PHP script allow user to manage files and directories on a remote http server.
  The user can : - navigate trough files and directories.
                 - upload a file
				 - rename, delete, copy a file or a directory

  The script is organised in four sections.

  * 1st section execute the command called by the user
                Note: somme commands of this section is organised in two step.
			    The script lines always begin by the second step,
			    so it allows to return more easily to the first step.

  * 2nd section define the directory to display

  * 3rd section read files and directories from the directory defined in part 2

  * 4th section display all of that on a HTML page
*/

/*======================================
       CLAROLINE MAIN
  ======================================*/
$langFile = 'document';
include('../inc/claro_init_global.inc.php');

$htmlHeadXtra[] =
"<style type=text/css>
<!--
.comment { margin-left: 30px}
.invisible {color: #999999}
.invisible a {color: #999999}
-->
</style>";

$htmlHeadXtra[] =
"<script>
function confirmation (name)
{
	if (confirm(\" $langAreYouSureToDelete \"+ name + \" ?\"))
		{return true;}
	else
		{return false;}
}
</script>";

$nameTools = $langDoc;

$QUERY_STRING=''; // used forthe breadcrumb 
                  // when one need to add a parameter after the filename

include('../inc/claro_init_header.inc.php');

/* 
 * Library for the file display
 */

include("../inc/lib/fileDisplay.lib.php");

/*
 * Lib for event log, stats & tracking
 * plus record of the access
 */

/*============================================================================
                     FILEMANAGER BASIC VARIABLES DEFINITION
  =============================================================================*/

$baseServDir = $rootSys;
$baseServUrl = $urlAppend.'/';

/*
 * The following variables depends on the use context
 * The document tool can be used at course or group level 
 * (one document area for each group)
 */

if ($_gid && $is_groupAllowed)
{
    $groupContext      = true;
    $courseContext     = false;

    $maxFilledSpace    = 1000000;
    $courseDir         = $_course['path'].'/group/'.$_group['directory'];

    $interbredcrump[]  = array ('url'=>'group.php', 'name'=> $langGroupManagement);

    $is_allowedToEdit  = $is_groupMember || $is_courseAdmin;
    $is_allowedToUnzip = false;
}
else
{
	$groupContext     = false;
    $courseContext    = true;

    $maxFilledSpace   = $groupDocument_maxFilledSpace;
    $courseDir   = $_course['path'].'/document';
    $dbTable     = $_course['dbNameGlu'].'document';

    $interbredcrump[] = array ();

    $is_allowedToEdit  = $is_courseAdmin;
    $is_allowedToUnzip = $is_courseAdmin;
    $maxFilledSpace    = 100000000;
}

$baseWorkDir = $baseServDir.$courseDir;

include($includePath.'/lib/events.lib.inc.php');
event_access_tool($nameTools);

if ( ! $is_courseAllowed)
	claro_disp_auth_form();


if($is_allowedToEdit) // for teacher only
{
	include ($includePath.'/lib/fileManage.lib.php');
	include ($includePath.'/lib/fileUpload.lib.php');

	if ($uncompress == 1)
	{
		include($includePath."/lib/pclzip/pclzip.lib.php");
	}
}


// $baseWorkDir = $baseServDir.$urlAppend.$courseDir;

// clean information submited by the user from antislash

stripSubmitValue($HTTP_POST_VARS);
stripSubmitValue($HTTP_GET_VARS);
stripSubmitValue($_REQUEST);


// table names for learning path (needed to check integrity)

$TABLELEARNPATH            = $_course['dbNameGlu']."lp_learnpath";
$TABLELEARNPATHMODULE      = $_course['dbNameGlu']."lp_rel_learnpath_module";
$TABLEUSERMODULEPROGRESS   = $_course['dbNameGlu']."lp_user_module_progress";
$TABLEMODULE               = $_course['dbNameGlu']."lp_module";
$TABLEASSET                = $_course['dbNameGlu']."lp_asset";


                  /* > > > > > > MAIN SECTION  < < < < < < <*/


if($is_allowedToEdit) // Document edition are reserved to certain people
{


	/*========================================================================
                                  UPLOAD FILE
	  ========================================================================*/


	/*
	 * check the request method in place of a variable from POST
	 * because if the file size exceed the maximum file upload
	 * size set in php.ini, all variables from POST are cleared !
	 */

	if ($cmd == 'exUpload')
	{
		/*
     * Check if the file is valid (not to big and exists)
     */

        if( ! is_uploaded_file($_FILES['userFile']['tmp_name']) )
        {
        	$dialogBox .= $langFileError.'<br>'.$langNotice.' : '.$langMaxFileSize.' '.get_cfg_var('upload_max_filesize');
        }

        if ($_REQUEST['uncompress'] == 1 && $is_allowedToUnzip) $unzip = 'unzip';
        else                                                    $unzip = '';

        $uploadedFileName = treat_uploaded_file($HTTP_POST_FILES['userFile'], $baseWorkDir,
                                $_REQUEST['cwd'], $maxFilledSpace, $unzip);

        if ($uploadedFileName !== false)
        {
            if ( $_REQUEST['uncompress'] == 1)
            {
                $dialogBox .= $langDownloadAndZipEnd;
            }
            else
            {
                $dialogBox .= $langDownloadEnd;

                if (trim($_REQUEST['comment']) != '') // insert additional comment
                {
                    update_db_info('update', $file, 
                                    array( 'path'    => $_REQUEST['cwd'].'/'.$uploadedFileName,
                                           'comment' => trim($_REQUEST['comment']) ) );
                }
            }
        }
        else
        {
            if (claro_failure::get_last_failure() == 'not_enough_space')
            {
                $dialogBox .= $langNoSpace;
            }
            elseif (claro_failure::get_last_failure() == 'php_file_in_zip_file')
            {
                $dialogBox .= $langZipNoPhp;
            }
        }


        /*--------------------------------------------------------------------
           IN CASE OF HTML FILE, LOOKS FOR IMAGE NEEDING TO BE UPLOADED TOO
          --------------------------------------------------------------------*/


		if (   strrchr($HTTP_POST_FILES['userFile']['name'], '.') == '.htm'
            || strrchr($HTTP_POST_FILES['userFile']['name'], '.') == '.html')
		{
            $imgFilePath = search_img_from_html($baseWorkDir.$_REQUEST['cwd'].'/'.$HTTP_POST_FILES['userFile']['name']);

			/*
			 * Generate Form for image upload
			 */

			if ( sizeof($imgFilePath) > 0)
			{
				$dialogBox .= "<br><b>".$langMissingImagesDetected."</b><br>\n"
				             ."<form method=\"post\" action=\"".$PHP_SELF."\""
				             ."enctype=\"multipart/form-data\">\n"
				             ."<input type=\"hidden\" name=\"cmd\" value=\"submitImage\">\n"
				             ."<input type=\"hidden\" name=\"relatedFile\""
				             ."value=\"".$_REQUEST['cwd']."/".$HTTP_POST_FILES['userFile']['name']."\">\n"
				             ."<table border=\"0\">\n";

				foreach($imgFilePath as $thisImgFilePath )
				{
					$dialogBox .= "<tr>\n"
					             ."<td>".basename($thisImgFilePath)." : </td>\n"
					             ."<td>"
					             ."<input type=\"file\"	name=\"imgFile[]\">"
					             ."<input type=\"hidden\" name=\"imgFilePath[]\" "
					             ."value=\"".$thisImgFilePath."\">"
					             ."</td>\n"
					             ."</tr>\n";
				}

				$dialogBox .= "</table>\n"

				             ."<div align=\"right\">"
				             ."<input type=\"submit\" name=\"cancelSubmitImage\" value=\"".$langCancel."\">\n"
				             ."<input type=\"submit\" name=\"submitImage\" value=\"".$langOk."\"><br>"
				             ."</div>\n"
				             ."</form>\n";
			}							// end if ($imgFileNb > 0)
		}								// end if (strrchr($fileName) == "htm"
	}									// end if is_uploaded_file


    if ($cmd == 'rqUpload')
    {
        $dialogBox .= "<form action=\"".$PHP_SELF."\" method=\"post\" enctype=\"multipart/form-data\">"
                     ."<input type=\"hidden\" name=\"cmd\" value=\"exUpload\">"
                     ."<input type=\"hidden\" name=\"cwd\" value=\"".$_REQUEST['cwd']."\">"
                     .$langDownloadFile." : "
                     ."<input type=\"file\" name=\"userFile\"> "
                     ."<input style=\"font-weight: bold\" type=\"submit\" value=\"".$langDownload."\"><br>";

        if ($is_allowedToUnzip)
        {
            $dialogBox .= "<input type=\"checkbox\" name=\"uncompress\" value=\"1\">"
                          .$langUncompress;
        }

        if ($courseContext)
        {
            $dialogBox .= "<p>\n"
                        ."Add Comment (optionnal) :"
                        ."<br><textarea rows=2 cols=50 name=\"comment\">"
                        .$oldComment
                        ."</textarea>\n"
                        ."</p>\n";
        }

        $dialogBox .= "</form>";
    }


	/*========================================================================
                           UPLOAD RELATED IMAGE FILES
	  ========================================================================*/

	if ($cmd == 'submitImage')
	{
		$uploadImgFileNb = sizeof($HTTP_POST_FILES['imgFile']);

		if ($uploadImgFileNb > 0)
		{
			// Try to create  a directory to store the image files

            $imgDirectory = $_REQUEST['relatedFile'].'_files';
            $imgDirectory = create_unexisting_directory($baseWorkDir.$imgDirectory);

            // set the makeInvisible command param appearing later in the script
			$mkInvisibl = str_replace($baseWorkDir, '', $imgDirectory);

			// move the uploaded image files into the corresponding image directory

            $newImgPath = move_uploaded_file_collection_into_directory($HTTP_POST_FILES['imgFile'], $imgDirectory);

            replace_img_path_in_html_file($HTTP_POST_VARS['imgFilePath'], 
                                          $newImgPath, 
                                          $baseWorkDir.$_REQUEST['relatedFile']);

		}											// end if ($uploadImgFileNb > 0)
	}										// end if ($submitImage)



    /*========================================================================
                             CREATE DOCUMENT
      ========================================================================*/

    /*------------------------------------------------------------------------
                            CREATE DOCUMENT : STEP 2
      ------------------------------------------------------------------------*/

    if ($cmd == 'exMkHtml')
    {
        $fileName = replace_dangerous_char(trim($_REQUEST['fileName']));

        if (! empty($fileName) )
        {
            if ( ! in_array( strtolower (get_file_extension($_REQUEST['fileName']) ), 
                           array('html', 'htm') ) )
            {
                $fileName = $fileName.'.htm';
            }
                        
            create_file($baseWorkDir.$_REQUEST['cwd'].'/'.$fileName,
                        $_REQUEST['htmlContent']);

            $dialogBox .= $langFileCreated;
        }
        else
        {
            $dialogBox .= $langFileNameMissing;
        }
    }
    

    /*------------------------------------------------------------------------
                            CREATE DOCUMENT : STEP 1
      ------------------------------------------------------------------------*/

      // see reqmkhtml.php ...

    /*========================================================================
                             EDIT DOCUMENT CONTENT
      ========================================================================*/

    if ($cmd == 'exEditHtml')
    {
        $fp = fopen($baseWorkDir.$_REQUEST['file'], 'w');

        if ($fp)
        {
        
          if ( fwrite($fp, $_REQUEST['htmlContent']) )
          {
            $dialogBox .= $langFileContentModified."<br>";
          }
          
        }
    }


	/*========================================================================
                                   CREATE URL
	  ========================================================================*/

	/*
	 * The code begins with STEP 2
	 * so it allows to return to STEP 1 if STEP 2 unsucceeds
	 */

	/*------------------------------------------------------------------------
                              CREATE URL : STEP 2
	--------------------------------------------------------------------------*/

	if ($cmd == 'exMkUrl')
	{
        $fileName = replace_dangerous_char(trim($_REQUEST['fileName']));
        $url = trim($_REQUEST['url']);
        
        // check for "http://", if the user forgot "http://" or "ftp://" or ...
        // the link will not be correct
        if( !ereg( "://",$url ) )
        {
            // add "http://" as default protocol for url
            $url = "http://".$url;
        }
        
        if ( ! empty($fileName) && ! empty($url) )
        {
            $linkFileExt = ".url";
            create_link_file( $baseWorkDir.$_REQUEST['cwd'].'/'.$fileName.$linkFileExt, 
                              $url);
        }
        else
        {
        	$dialogBox .= $langFileNameOrURLMissing;
            $cmd        = 'rqMkUrl';
        }
    }

	/*------------------------------------------------------------------------
                              CREATE URL : STEP 1
	--------------------------------------------------------------------------*/

    if ($cmd == 'rqMkUrl')
    {
        $dialogBox .= "<h4>".$langCreateHyperlink."</h4>\n"
                     ."<form action=\"".$PHP_SELF."\" method=\"post\">\n"
                     ."<input type=\"hidden\" name=\"cmd\" value=\"exMkUrl\">\n"
                     ."<input type=\"hidden\" name=\"cwd\" value=\"".$_REQUEST['cwd']."\">"
                     .$langName." :<br />\n"
                     ."<input type=\"text\" name=\"fileName\" value=\"".$fileName."\"><br />\n"
                     .$langURL."<br />\n"
                     ."<input type=\"text\" name=\"url\" value=\"".$url."\">\n"
                     ."<input type=\"submit\" value=\"".$langOk."\">\n"
                     ."</form>\n";

    }
    

	/*========================================================================
                             MOVE FILE OR DIRECTORY
	  ========================================================================*/


	/*------------------------------------------------------------------------
                        MOVE FILE OR DIRECTORY : STEP 2
	--------------------------------------------------------------------------*/

	if ($cmd == 'exMv')
	{
		if ( move($baseWorkDir.$_REQUEST['file'],$baseWorkDir.$_REQUEST['destination']) )
		{
			if ($courseContext)
			{
                update_db_info( 'update', $_REQUEST['file'],
                                $destination.'/'.basename($_REQUEST['file']) );
                update_Doc_Path_in_Assets("update",$_REQUEST['file'], $_REQUEST['destination'].'/'.basename($_REQUEST['file']));
			}

			$dialogBox = $langDirMv.'<br>';
		}
		else
		{
			$dialogBox = $langImpossible.'<br>';

			/* return to step 1 */

			$cmd = 'rqMv';
			unset ($_REQUEST['destination']);
		}
	}


	/*------------------------------------------------------------------------
                        MOVE FILE OR DIRECTORY : STEP 1
	--------------------------------------------------------------------------*/

	if ($cmd == 'rqMv')
	{
		$dialogBox .= form_dir_list($_REQUEST['file'], $baseWorkDir);
	}



	/*========================================================================
                            DELETE FILE OR DIRECTORY
	  ========================================================================*/


	if ($cmd == 'exRm')
	{
        $file = $_REQUEST['file'];

        if ( my_delete($baseWorkDir.$file))
		{
            if ($courseContext)
            {
                update_db_info('delete', $file);
                update_Doc_Path_in_Assets('delete', $file, '');
            }

            $dialogBox = $langDocDeleted;
		}
	}




	/*========================================================================
                                      EDIT
	  ========================================================================*/

	/*
	 * The code begin with STEP 2
	 * so it allows to return to STEP 1
	 * if STEP 2 unsucceds
	 */


    /*------------------------------------------------------------------------
                                 EDIT : STEP 2
      ------------------------------------------------------------------------*/

    if ($cmd == 'exEdit')
    {

        $file       = $_REQUEST['file'];

        if ( $_REQUEST['url'] )
        {
            // check for "http://", if the user forgot "http://" or "ftp://" or ...
            // the link will not be correct
            if( !ereg( "://",$_REQUEST['url'] ) )
            {
                // add "http://" as default protocol for url
                $url = "http://".$_REQUEST['url'];
            }
            create_link_file( $baseWorkDir.$_REQUEST['file'], 
                              $url);
        }

        $directoryName = dirname($file);
        
        if ( $directoryName == '/' || $directoryName == '\\' )
        {
            // When the dir is root, PHP dirname leaves a '\' for windows or a '/' for Unix
            $directoryName = '';
        }
        
        $newPath = $directoryName . '/' . $_REQUEST['newName']; 

        if ( my_rename($baseWorkDir.$file, $baseWorkDir.$newPath) )
        {
            $dialogBox = $langElRen.'<br>';

            if ($courseContext)
            {
                $newComment = trim($_REQUEST['newComment']); // remove spaces

                update_db_info('update', $file, array( 'path'    => $newPath,
                                                       'comment' => $newComment ) );
                update_Doc_Path_in_Assets('update', $file, $newPath);

                if ( ! empty($newComment) ) $dialogBox .= $langComMod.'<br>';
            }
        }
        else
        {
            $dialogBox .= $langFileExists;

            /* return to step 1 */

            $cmd   = 'rqEdit';
        }
    }


	/*------------------------------------------------------------------------
                                 EDIT : STEP 1
	-------------------------------------------------------------------------*/

	if ($cmd == 'rqEdit')
	{
		$fileName = basename($_REQUEST['file']);

		$dialogBox .= 	"<form action=\"".$PHP_SELF."\" method=\"post\">"
						."<input type=\"hidden\" name=\"cmd\" value=\"exEdit\">\n"
						."<input type=\"hidden\" name=\"file\" value=\"".$_REQUEST['file']."\">\n"
                        ."<p>\n"
						.$langRename." ".htmlspecialchars($fileName)." ".$langIn." :\n"
						."<br><input type=\"text\" name=\"newName\" value=\"".$fileName."\">\n"
                        ."</p>\n";

        if ('url' == get_file_extension($baseWorkDir.$_REQUEST['file']) )
        {
            $url = get_link_file_url($baseWorkDir.$_REQUEST['file']);

            $dialogBox .= "<p>\n".$langURL."<br />\n"
                         ."<input type=\"text\" name=\"url\" value=\"".$url."\">\n"
                         ."</p>\n";
        }

        if ($courseContext)
        {
            /* Search the old comment */
            $sql = "SELECT comment 
                    FROM `".$dbTable."` 
                    WHERE path = \"".$_REQUEST['file']."\"";

            $result = mysql_query ($sql);
            while( $row = mysql_fetch_array($result, MYSQL_ASSOC) ) $oldComment = $row['comment'];

            $dialogBox .= "<p>\n"
                          .$langAddComment." ".htmlspecialchars($fileName)."\n"
                          ."<br><textarea rows=2 cols=50 name=\"newComment\">"
                          .$oldComment
                          ."</textarea>\n"
                          ."</p>\n";
        }

        /*
         * Add the possibility to edit on line the content of file 
         * if it is an html file
         */

        if ( in_array( strtolower (get_file_extension($_REQUEST['file']) ), 
                       array('html', 'htm') ) )
        {
            
        	$dialogBox .= "<p>"
                          ."<a href=\"rqmkhtml.php?cmd=rqEditHtml&file=".$_REQUEST['file']."\">"
                          .$langEditFileContent
                          ."</a>"
                          ."</p>";
        }

		$dialogBox .= "<br /><input type=\"submit\" value=\"".$langOk."\">\n"
					 ."</form>\n";

	} // end if cmd == rqEdit




	/*========================================================================
                                CREATE DIRECTORY
	  ========================================================================*/

	/*
	 * The code begin with STEP 2
	 * so it allows to return to STEP 1
	 * if STEP 2 unsucceds
	 */

	/*------------------------------------------------------------------------
                                     STEP 2
	  ------------------------------------------------------------------------*/

    if ($cmd == 'exMkDir')
	{
		$newDirName = replace_dangerous_char(trim($_REQUEST['newName']));

		if( check_name_exist($baseWorkDir.$_REQUEST['cwd'].'/'.$newDirName) )
		{
			$dialogBox = $langFileExists;
			$cmd = 'rqMkDir';
		}
		else
		{
			mkdir($baseWorkDir.$_REQUEST['cwd']."/".$newDirName, 0700);
			$dialogBox = $langDirCr;
		}
	}


	/*------------------------------------------------------------------------
                                     STEP 1
	  ------------------------------------------------------------------------*/

	if ($cmd == 'rqMkDir')
	{
		$dialogBox .=	 "<form>\n"
						."<input type=\"hidden\" name=\"cmd\" value=\"exMkDir\">\n"					
						."<input type=\"hidden\" name=\"cwd\" value=\"".$_REQUEST['cwd']."\">\n"
						.$langNameDir." : \n"
						."<input type=\"text\" name=\"newName\">\n"
						."<input type=\"submit\" value=\"".$langOk."\">\n"
						."</form>\n";
	}

	/*========================================================================
                              VISIBILITY COMMANDS
	  ========================================================================*/

	if ($cmd == 'exChVis' && $courseContext)
	{
		$visibilityPath = $_REQUEST['file']; // At least one of these variables are empty.
		                                     // So it's okay to proceed this way
        
        update_db_info('update', $file, array('visibility' => $_REQUEST['vis']) );

		$dialogBox = $langViMod;

	}
} // END is Allowed to Edit




/*============================================================================
                            DEFINE CURRENT DIRECTORY
  ============================================================================*/

if (in_array($cmd, array('rqMv', 'exRm', 'rqEdit', 'exEdit', 'exEditHtml',
                         'exChVis', 'rqComment', 'exComment')))
{
	$curDirPath = claro_dirname($_REQUEST['file']);
}
elseif (in_array($cmd, array('rqMkDir', 'exMkDir', 'rqUpload', 'exUpload', 
                             'rqMkUrl', 'exMkUrl', 'reqMkHtml', 'exMkHtml')))
{
	$curDirPath = $_REQUEST['cwd'];
}
elseif ($cmd == 'exChDir')
{
		$curDirPath = $_REQUEST['file'];
}
elseif ($cmd == 'exMv')
{
	$curDirPath = $_REQUEST['destination'];
}

else
{
	$curDirPath = '';
}

if ($curDirPath == '/' || $curDirPath == '\\' || strstr($curDirPath, '..'))
{
	$curDirPath = ''; // manage the root directory problem

	/*
	 * The strstr($curDirPath, "..") prevent malicious users to go to the root directory
	 */
}

$curDirName = basename($curDirPath);
$parentDir  = dirname($curDirPath);

if ($parentDir == '/' || $parentDir == '\\')
{
	$parentDir = ''; // manage the root directory problem
}




/*============================================================================
                         READ CURRENT DIRECTORY CONTENT
  ============================================================================*/


/*----------------------------------------------------------------------------
                     LOAD FILES AND DIRECTORIES INTO ARRAYS
  ----------------------------------------------------------------------------*/

chdir (realpath($baseWorkDir.$curDirPath)) 
or die("<center>
       <b>Wrong directory !</b>
       <br> Please contact your platform administrator.
       </center>");
$handle = opendir(".");

define('A_DIRECTORY', 1);
define('A_FILE',      2);


while ($file = readdir($handle))
{
	if ($file == '.' || $file == '..')
	{
		continue;						// Skip current and parent directories
	}

	$fileList['name'][] = $file;
	
	if(is_dir($file))
	{
		$fileList['type'][] = A_DIRECTORY;
		$fileList['size'][] = false;
		$fileList['date'][] = false;
	}
	elseif(is_file($file))
	{
		$fileList['type'][] = A_FILE;
		$fileList['size'][] = filesize($file);
		$fileList['date'][] = filectime($file);
	}
}				// end while ($file = readdir($handle))

if ($courseContext && $fileList)
{
	/*--------------------------------------------------------------------------
                 SEARCHING FILES & DIRECTORIES INFOS ON THE DB
      ------------------------------------------------------------------------*/

    /* 
     * Search infos in the DB about the current directory the user is in
     */

    $sql = "SELECT path, visibility, comment FROM `$dbTable` 
            WHERE path LIKE    \"".$curDirPath."/%\" 
            AND   path NOT LIKE \"".$curDirPath."/%/%\"";

    $result = claro_sql_query($sql);

    if ($result)
    {
        $attribute = array('path'    => array(), 'visibility' => array(), 
                           'comment' => array());

        while($row = mysql_fetch_array($result, MYSQL_ASSOC))
        {   /* we should rather use claro_sql_query_fetch_all
               but it was technically impossible ... */
            $attribute['path'      ][] = $row['path'      ];
            $attribute['visibility'][] = $row['visibility'];
            $attribute['comment'   ][] = $row['comment'   ];
        }
    }

    /*
     * Make the correspondance between info given by the file system 
     * and info given by the DB
     */

    if ( count($attribute) > 0)
    {
        foreach($fileList['name'] as $thisFile)
        {
            $keyAttribute = array_search($curDirPath.'/'.$thisFile, 
                                         $attribute['path']);

            if ($keyAttribute !== false)
            {
                $fileList['comment'   ][] = $attribute['comment'   ][$keyAttribute];
                $fileList['visibility'][] = $attribute['visibility'][$keyAttribute];

                /*
                 * Progressively unset the attribut to be able to check at the 
                 * end if it remains unassigned attribute - which should mean 
                 * there is  base integrity problem
                 */

                unset ($attribute['comment'   ][$keyAttribute],
                       $attribute['visibility'][$keyAttribute],
                       $attribute['path'      ][$keyAttribute]);
            }
            else
            {
                    $fileList['comment'   ][] = false;
                    $fileList['visibility'][] = false;
            }
        }  // end foreach fileList[name] as thisFile

    } // end if count attribute > 0

    /*------------------------------------------------------------------------
                              CHECK BASE INTEGRITY
      ------------------------------------------------------------------------*/

    if ( count($attribute['path']) > 0 )
    {
        $sql = "DELETE FROM `".$dbTable."` 
                WHERE `path` IN ( \"".implode("\" , \"" , $attribute['path'])."\" )";

        claro_sql_query($sql);

        $sql = "DELETE FROM `".$dbTable."` 
               WHERE comment LIKE '' AND visibility LIKE 'v'";

        claro_sql_query($sql);
        /* The second query clean the DB 'in case of' empty records (no comment an visibility=v)
           These kind of records should'nt be there, but we never know... */

    }	// end if sizeof($attribute['path']) > 0

} // end if courseContext




/*----------------------------------------------------------------------------
                       SORT ALPHABETICALLY THE FILE LIST
  ----------------------------------------------------------------------------*/

if ($fileList)
{
	if ($courseContext)
	{
        array_multisort($fileList['type'], $fileList['name'], 
                        $fileList['size'], $fileList['date'],
                        $fileList['comment'],$fileList['visibility']);
	}
    else
    {
        array_multisort($fileList['type'], $fileList['name'], 
                        $fileList['size'], $fileList['date']);
    }
}

closedir($handle);
unset($attribute);




      /* > > > > > > END: COMMON TO TEACHERS AND STUDENTS < < < < < < <*/



/*============================================================================
                                    DISPLAY
  ============================================================================*/


	$dspCurDirName = htmlspecialchars($curDirName);
	$cmdCurDirPath = rawurlencode($curDirPath);
	$cmdParentDir  = rawurlencode($parentDir);

?>
<?php claro_disp_tool_title($langDoc) ?>
<? if($is_allowedToEdit)
{ ?>
<p align="right">
<a href="#" onClick="MyWindow=window.open('../help/help_document.php','MyWindow','toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=350,height=450,left=300,top=10'); return false;">
<?php echo $langHelp ?>
</a>
</p>
<? } ?>

<?php


	if($is_allowedToEdit)
	{
		/*--------------------------------------------------------------------
                               DIALOG BOX SECTION
		  --------------------------------------------------------------------*/

		if ($dialogBox)
		{
            claro_disp_message_box($dialogBox);
		}
	}

	$is_allowedToEdit ? $colspan = 7 : $colspan = 3;

	/*------------------------------------------------------------------------
                             CURRENT DIRECTORY LINE
	  ------------------------------------------------------------------------*/

	/* GO TO PARENT DIRECTORY */

    echo "<p>\n";
	
	if ($curDirName) /* if the $curDirName is empty, we're in the root point 
	                    and we can't go to a parent dir */
	{
		echo 	"<a href=\"$PHP_SELF?cmd=exChDir&file=".$cmdParentDir."\">\n",
				"<img src=\"../img/parent.gif\" border=\"0\" align=\"absbottom\" hspace=\"5\">\n",
				"<small>$langUp</small>\n",
				"</a>\n";
	}

	if ($is_allowedToEdit)
	{
		/* CREATE DIRECTORY - UPLOAD FILE - CREATE HYPERLINK */
		
        echo    "&nbsp;",
                "<a href=\"".$PHP_SELF."?cmd=rqMkDir&cwd=".$cmdCurDirPath."\">",
                "<img src=\"../img/dossier.gif\">",
                "<small>$langCreateDir</small>",
                "</a>\n",
                "&nbsp;",
                "<a href=\"".$PHP_SELF."?cmd=rqUpload&cwd=".$cmdCurDirPath."\">",
                "<img src=\"../img/download.gif\">",
                "<small>$langUploadFile</small>",
                "</a>\n",
                "&nbsp;",
                "<a href=\"".$PHP_SELF."?cmd=rqMkUrl&cwd=".$cmdCurDirPath."\">",
                "<img src=\"../img/liens.gif\">",
                "<small>".$langCreateHyperlink."</small>",
                "</a>\n",
                "<a href=\"rqmkhtml.php?cmd=rqMkHtml&cwd=".$cmdCurDirPath."\">",
                "<img src=\"../img/html.gif\">",
                "<small>".$langCreateDocument."</small>",
                "</a>\n";
	}

    echo "</p>";

    echo "<table class=\"claroTable\" width=\"100%\">";


	/* CURRENT DIRECTORY */
	
	if ($curDirName) /* if the $curDirName is empty, we're in the root point 
	                    and there is'nt a dir name to display */
	{
		echo	"<!-- current dir name -->\n",
				"<tr>\n",
				"<th class=\"superHeader\" colspan=\"$colspan\" align=\"left\">\n",
				"<img src=\"../img/opendir.gif\" align=\"absbottom\" vspace=2 hspace=5>\n",
                $dspCurDirName,"\n",
				"</td>\n",
				"</tr>\n";
	}

	echo		"<tr class=\"headerX\" align=\"center\" valign=\"top\">\n";

	echo		"<th>$langName</th>\n",
				"<th>$langSize</th>\n",
				"<th>$langDate</th>\n";
			
	if ($is_allowedToEdit)			
	{
		echo	"<th>".$langDelete."</th>\n"
				."<th>".$langMove."</th>\n"
				."<th>".$langModify."</th>\n";

                if ($courseContext)
                {
                	echo "<th>".$langVisible."</th>\n";
                }
                elseif ($groupContext)
                {
                    echo "<th>".$langPublish."</th>\n";
                }
	}
			
	echo		"</tr>\n"

               ."<tbody>";


	/*------------------------------------------------------------------------
                               DISPLAY FILE LIST
	  ------------------------------------------------------------------------*/

	if ($fileList)
	{
        $debug = each ($fileList['name']);

        // while (list($fileKey, $fileName) = each ($fileList['name']))
        // each seems to pose porblem on PHP 4.1 when the array contains 
        // a single element

        foreach($fileList['name'] as $fileKey => $fileName )
		{
			$dspFileName = htmlentities($fileName);
			$cmdFileName = rawurlencode($curDirPath.'/'.$fileName);
			
			if ($fileList['visibility'][$fileKey] == 'i')
			{
				if ($is_allowedToEdit)
				{
					$style=' class="invisible"';
				}
				else
				{
					continue; // skip the display of this file
				}
			}
			else 
			{
				$style='';
			}
			
			if ($fileList['type'][$fileKey] == A_FILE)
			{
				$image       = choose_image($fileName);
				$size        = format_file_size($fileList['size'][$fileKey]);
				$date        = format_date($fileList['date'][$fileKey]);
                                $urlFileName = "goto/?doc_url=".urlencode($cmdFileName);
                                //$urlFileName = "goto/?doc_url=".urlencode($cmdFileName);
                                //format_url($baseServUrl.$courseDir.$curDirPath."/".$fileName));
			}
			elseif ($fileList['type'][$fileKey] == A_DIRECTORY)
			{
				$image       = 'dossier.gif';
				$size        = '';
				$date        = '';
				$urlFileName = $PHP_SELF.'?cmd=exChDir&file='.$cmdFileName;
			}

			echo	"<tr align=\"center\"",$style,">\n",
					"<td align=\"left\">",
					"<a href=\"".$urlFileName."\"".$style.">",
					"<img src=\"./../img/",$image,"\" border=0 hspace=5>",$dspFileName,"</a>",
					"</td>\n",
					
					"<td><small>",$size,"</small></td>\n",
					"<td><small>",$date,"</small></td>\n";

			/* NB : Before tracking implementation the url above was simply
			 * "<a href=\"",$urlFileName,"\"",$style,">"
			 */

			if($is_allowedToEdit)
			{
				/* DELETE COMMAND */

				echo 	"<td>",
						"<a href=\"",$PHP_SELF,"?cmd=exRm&file=",$cmdFileName,"\" ",
						"onClick=\"return confirmation('",addslashes($dspFileName),"');\">",
						"<img src=\"../img/supprimer.gif\" border=0>",
						"</a>",
						"</td>\n";
				
				/* COPY COMMAND */

				echo	"<td>",
						"<a href=\"",$PHP_SELF,"?cmd=rqMv&file=",$cmdFileName,"\">",
						"<img src=\"../img/deplacer.gif\" border=0>",
						"</a>",
						"</td>\n";
						
				/* EDIT COMMAND */

				echo	"<td>",
						"<a href=\"",$PHP_SELF,"?cmd=rqEdit&file=",$cmdFileName,"\">",
						"<img src=\"../img/edit.gif\" border=0>",
						"</a>",
						"</td>\n";
                        
				echo	"<td>";

                if ($groupContext)
                {
                    /* PUBLISH COMMAND */

                    if ($fileList['type'][$fileKey] == A_FILE)
                    {
                        echo	"<a href=\"../work/work.php?",
                                "submitGroupWorkUrl=".rawurlencode('../../'.$courseDir).$cmdFileName."\">",
                                "<small>",$langPublish,"</small>",
                                "</a>";
                    }
                    // else noop
                }
                elseif($courseContext)
                {
                    /* VISIBILITY COMMAND */

                    if ($fileList['visibility'][$fileKey] == "i")
                    {
                        echo	"<a href=\"",$PHP_SELF,"?cmd=exChVis&file=",$cmdFileName,"&vis=v\">",
                                "<img src=\"../img/invisible.gif\" border=0>",
                                "</a>";
                    }
                    else
                    {
                        echo	"<a href=\"",$PHP_SELF,"?cmd=exChVis&file=",$cmdFileName,"&vis=i\">",
                                "<img src=\"../img/visible.gif\" border=0>",
                                "</a>";
                    }
                }
				
				echo	"</td>\n";
			}										// end if($is_allowedToEdit)
			
			echo	"</tr>\n";
			
			/* COMMENTS */
			
			if ($fileList['comment'][$fileKey] != "" )
			{
				$fileList['comment'][$fileKey] = htmlspecialchars($fileList['comment'][$fileKey]);
				$fileList['comment'][$fileKey] = nl2br($fileList['comment'][$fileKey]);

				echo	"<tr align=\"left\">\n",
						"<td colspan=\"$colspan\">",
						"<div class=\"comment\">",
						$fileList['comment'][$fileKey],
						"</div>",
						"</td>\n",
						"</tr>\n";
			}
		}				// end each ($fileList)
	}					// end if ( $fileList)
	
	echo	"</tbody>",
            "</table>\n",
			"</div>\n",
            "<br><br>\n";

include($includePath."/claro_init_footer.inc.php"); 
?>