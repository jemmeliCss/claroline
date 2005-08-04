<?php

/* vim: set expandtab tabstop=4 shiftwidth=4:
  +----------------------------------------------------------------------+
  | CLAROLINE version 1.3.0 $Revision$                             |
  +----------------------------------------------------------------------+
  | Copyright (c) 2000, 2001 Universite catholique de Louvain (UCL)      |
  +----------------------------------------------------------------------+
  | $Id$  |
  +----------------------------------------------------------------------+
  | This source file is subject to the GENERAL PUBLIC LICENSE,           |
  | available through the world-wide-web at                              |
  | http://www.gnu.org/copyleft/gpl.html                                 |
  +----------------------------------------------------------------------+
  | Authors: Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
  |          Hugues Peeters    <peeters@ipm.ucl.ac.be>                   |
  |          Christophe Gesch� <gesche@ipm.ucl.ac.be>                    |
  +----------------------------------------------------------------------+
*/


/******************************************
 GENERIC FUNCTIONS : FOR OLDER PHP VERSIONS
*******************************************/

if ( ! function_exists('array_search') )
{
	/**
	 * Searches haystack for needle and returns the key
	 * if it is found in the array, FALSE otherwise
	 *
	 * Natively implemented in PHP since 4.0.5 version.
	 * This function is intended for previous version.
	 *
	 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
	 * @param   - needle (mixed)
	 * @param   - haystack (array)
	 * @return  - array key or FALSE
	 *
	 * @see     - http://www.php.net/array_search
	 */

	function array_search($needle, $haystack)
	{
		while (list ($key, $val) = each ($haystack))
			if ($val == $needle )
				return $key;
		return false;
	}
}



/*****************************************

/**
 * Define the image to display for each file extension
 * This needs an existing image repository to works
 *
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param  - fileName (string) - name of a file
 * @retrun - the gif image to chose
 */

function choose_image($fileName)
{
	static $type, $image;

	/* TABLES INITILIASATION */

	if (!$type || !$image)
	{
		$type['word'      ] = array("doc", "dot", "rtf", "mcw", "wps");
		$type['web'       ] = array("htm", "html", "htx", "xml", "xsl", "php");
		$type['image'     ] = array("gif", "jpg", "png", "bmp", "jpeg");
		$type['audio'     ] = array("wav", "midi", "mp2", "mp3", "mp4", "vqf", "midi");
		$type['excel'     ] = array("xls", "xlt", "xls", "xlt");
		$type['compressed'] = array("zip", "tar", "rar", "gz");
		$type['code'      ] = array("js", "cpp", "c", "java");
		$type['acrobat'   ] = array("pdf");
		$type['powerpoint'] = array("ppt");
        $type['link'      ] = array("url");

		$image['word'      ] = "doc.gif";
		$image['web'       ] = "html.gif";
		$image['image'     ] = "gif.gif";
		$image['audio'     ] = "wav.gif";
		$image['excel'     ] = "xls.gif";
		$image['compressed'] = "zip.gif";
		$image['code'      ] = "js.gif";
		$image['acrobat'   ] = "pdf.gif";
		$image['powerpoint'] = "ppt.gif";
        $image['link'      ] = "link.gif";

	}

	/* FUNCTION CORE */

	if (ereg("\.([[:alnum:]]+)$", $fileName, $extension))
	{
		$extension[1] = strtolower ($extension[1]);

		foreach( $type as $genericType => $typeList)
		{
			if (in_array($extension[1], $typeList))
			{
				return$image[$genericType];
			}
		}
	}

	return "default.gif";
}

//------------------------------------------------------------------------------

/**
 * Transform the file size in a human readable format
 * 
 * @author - ???
 * @param  - fileSize (int) - size of the file in bytes
 */

function format_file_size($fileSize)
{
	// byteUnits is setted in trad4all
	global $byteUnits;
	
	if($fileSize >= 1073741824)
	{
		$fileSize = round($fileSize / 1073741824 * 100) / 100 . $byteUnits[3]; //GB
	}
	elseif($fileSize >= 1048576)
	{
		$fileSize = round($fileSize / 1048576 * 100) / 100 . $byteUnits[2]; //MB
	}
	elseif($fileSize >= 1024)
	{
		$fileSize = round($fileSize / 1024 * 100) / 100 . $byteUnits[1]; //KB
	}
	else
	{
		$fileSize = $fileSize . "b";
	}

	return $fileSize;
}


//------------------------------------------------------------------------------


/**
 * Transform a UNIX time stamp in human readable format date
 *
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param - date - UNIX time stamp 
 */

function format_date($fileDate)
{
	return date('d.m.Y', $fileDate);
}

//------------------------------------------------------------------------------


/**
 * Transform the file path in a url
 * 
 * @param - filePaht (string) - relative local path of the file on the Hard disk
 * @return - relative url
 */

function format_url($filePath)
{
	$stringArray = explode('/', $filePath);

	for ($i = 0; $i < sizeof($stringArray); $i++)
	{
		$stringArray[$i] = rawurlencode($stringArray[$i]);
	}

	return implode("/",$stringArray);
}

//------------------------------------------------------------------------------


/**
 * 
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param string $curDirPath current path in the documents tree navugation
 * @return string breadcrumb trail
 */

function claro_disp_document_breadcrumb($curDirPath)
{
    $curDirPathList = explode('/', $curDirPath);

    $urlTrail = '';

    $breadcrumbNameList = array();
    $breadcrumbUrlList  = array();

    foreach($curDirPathList as $thisDir)
    {
        if ( empty($thisDir) )
        {
            $breadcrumbNameList[] = 'Root';
            $breadcrumbUrlList[]  = '?cmd=exChDir&amp;file=';
        }
        else
        {
            $breadcrumbNameList[] = $thisDir;
            $urlTrail .= '/'.$thisDir;
            $breadcrumbUrlList[] = $_SERVER['PHP_SELF']
                                 . '?cmd=exChDir&amp;file='.rawurlencode($urlTrail);
        }
    }

    // remove the url on the last (current) element
    $breadcrumbUrlList[ count($breadcrumbUrlList) - 1] = null;

    return claro_disp_breadcrumbtrail($breadcrumbNameList, $breadcrumbUrlList);
}


?>
