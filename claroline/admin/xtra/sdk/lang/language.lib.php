<?php // $Id$
//----------------------------------------------------------------------
// CLAROLINE
//----------------------------------------------------------------------
// Copyright (c) 2001-2004 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

/**
 * Get the currently time
 *
 * @return  - time in microseconds
 */

function get_time () 
{
 $mtime = microtime();
 $mtime = explode(" ",$mtime);
 $mtime = $mtime[1] + $mtime[0];

 return $mtime;
}

/**
 * Browse path with language files and extract variables name 
 * and their values (retrieve_lang_vars function).
 * Script used in extract_var_from_lang_files.php 
 *
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param  - $dirPath - directory path
 * @param  - $languageName - language name of the translation file
 */

function glance_through_dir_lang ($dirPath, $languageName)
{
	chdir ($dirPath) ;
	$handle = opendir($dirPath);

    $fileList = array();
    $dirList  = array();

	while ($element = readdir($handle) )
	{
		if ( $element == "." || $element == ".." || strstr($element,"~") 
		     || strstr($element,"#"))
		{
			continue; // skip the current and parent directories and some files
		}

        // browse only old file name .php and LANG_COMPLETE_FILENAME (complete.lang.php)

        $pos = strpos($element,'.lang.php');

		if ( is_file($element) 
             && $element != 'locale_settings.php'
             && substr(strrchr($element, '.'), 1) == 'php' 
             && ( strlen($element) != $pos + strlen('.lang.php') || $element == LANG_COMPLETE_FILENAME) 
           )
		{
			$fileList[] = $dirPath."/".$element;
		}
		if ( is_dir($element) )
		{
			$dirList[] = $dirPath."/".$element;
		}
	}

	if ( sizeof($fileList) > 0)
	{
        echo "<ol>";
		foreach($fileList as $thisFile)
		{
            echo "<li>" . $thisFile . "</li>\n";
			retrieve_lang_var($thisFile, $languageName);
		}
        echo "</ol>\n";
        echo "<p>" . sizeof($fileList) . " file(s).</p>\n";
	}

	if ( sizeof($dirList) > 0)
	{
		foreach($dirList as $thisDir)
		{
			glance_through_dir_lang ($thisDir, $languageName); // recursion
		}
	}
}

/**
 * Get defined language variables of the script and store them.
 *
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param  - string $fileName - language file where to retrieve $lang variable translation
 * @param  - string $languageName - language name of the translation
 */

function retrieve_lang_var($fileName, $languageName)
{

    $varList = array();

	include($fileName);

	$localVar = get_defined_vars(); // collect the variable instancied locally

	foreach($localVar as $thisVarKey => $thisVarContent)
	{
		if ( is_a_lang_varname($thisVarKey) )
		{
			$varList[$thisVarKey] = addslashes($thisVarContent);
		}
	}

	store_lang_var($varList, $fileName, $languageName);	
}

/**
 * store the lang variables in a centralized repository
 *
 * @author - Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param  - array $languageVarList - list of the language variable
 *           'key' is the variable name, 'content' is the variable content
 * @param  - string $sourceFileName - file name from where the variables 
 *           are coming
 * @param  - string $languageName - name of the language translation
 */

function store_lang_var($languageVarList, $sourceFileName, $languageName)
{

	global $problemMessage, $rootSys, $tbl_translation;

	foreach($languageVarList as $thisVarKey => $thisVarContent)
	{
		$sql = "INSERT INTO " . $tbl_translation . " SET 
		 VarName    = \"".$thisVarKey."\", 
		 VarContent = \"".$thisVarContent."\", 
         varFullContent  = \"".$thisVarContent."\", 
		 language   = \"".$languageName."\",
		 sourceFile = \"" . str_replace($rootSys,"",$sourceFileName) ."\"";
		mysql_query($sql) or die($problemMessage);
	}

}

/**
 * Browse a dirname and returns all files and subdirectories
 * 
 * @return - array('files'=>array(), 'directories=>array())
 *
 * @param  - string $dirname
 * @param  - boolean $recurse
 */

function scan_dir($dirname,$recurse=FALSE)
{
    static $file_array=array();
    static $dir_array=array();
    static $ret_array=array();

    if($dirname[strlen($dirname)-1]!='/')
    {
        $dirname.='/';
    }

    $handle=opendir($dirname);

    while (false !== ($element = readdir($handle)))
    {
        if( is_scannable($dirname.$element, array('claroline/inc') ) )
        {
            if(is_dir($dirname.$element))
            {
                $dir_array[]=$dirname.$element;

                if($recurse)
                {
                    scan_dir($dirname.$element.'/',$recurse);
                }
            }
            else
            {
                $file_array[]=$dirname.$element;
            }
        }
    }

    closedir($handle);

    $ret_array['files']=$file_array;
    $ret_array['directories']=$dir_array;

    return $ret_array;

}

/**
 * Check if the file or directory is an element scannable 
 *
 * @return - boolean 
 * @param  - string
 * @param  - array
 * @param  - array
 */

function is_scannable($filePath, 
                      $additionnalForbiddenDirNameList = array(), 
                      $additionnalForbiddenFileSuffixList = array() )
{
    global $rootSys;

    $baseName    = basename($filePath);
    $parentPath  = str_replace('\\', '/', dirname($filePath));
    $parentPath  = str_replace($rootSys, '', $parentPath);

    $forbiddenDirNameList    = array_merge( array('claroline/lang',
                                                  'claroline/inc/conf',
                                                  'courses',
                                                  'claroline/claroline_garbage'),
                                            $additionnalForbiddenDirNameList);
    $forbiddenParentNameList = array('CVS');

    $forbiddenFileNameList   = array('.', '..','CVS');

    $forbiddenBaseNameList   = array_merge($forbiddenFileNameList, 
                                           $forbiddenDirNameList);

    $forbiddenFileSuffixList = array_merge( array('.lang.php', '~'), 
                                            $additionnalForbiddenFileSuffixList);

    $forbiddenFilePrefixList = array('~', '#', '\\.');

    // BASENAME CHECK

    if (is_file($filePath) && ! preg_match('/.php$/i',$baseName) ) return false;

    if (in_array($baseName, $forbiddenBaseNameList) )              return false;

    foreach($forbiddenFileSuffixList as $thisForbiddenSuffix)
    {
        if (preg_match('|'.$thisForbiddenSuffix.'^|', $baseName) ) return false;
    }

    foreach($forbiddenFilePrefixList as $thisForbiddenPrefix)
    {
        if (preg_match('|$'.$thisForbiddenPrefix.'|', $baseName) ) return false;
    }
    
    // DIRECTORY CHECK
    foreach($forbiddenDirNameList as $thisDirName)
    {
        if ( strpos($filePath, $rootSys.$thisDirName) !== FALSE ) 
        {
            return false;
        }
    }


    // PARENT PATH CHECK

    $pathComponentList = explode('/', $parentPath);

    foreach($pathComponentList as $thisPathComponent)
    {
        if (in_array($thisPathComponent, $forbiddenParentNameList) ) return false;
    }

    return true;
} 

/**
 * Store the name and sourceFile of the language variable in mysql table
 *
 * @param - array $languageVarList
 * @param - string $sourcFileName
 */

function store_lang_used_in_script($languageVarList, $sourceFileName)
{

	global $problemMessage, $rootSys, $tbl_used_lang;

    $sourceFileName =  str_replace($rootSys,"",$sourceFileName);
    $languageFileName = compose_language_production_filename($sourceFileName);

	foreach($languageVarList as $thisVar)
	{
		$sql = "INSERT INTO " . $tbl_used_lang . " SET 
		 VarName    = \"".$thisVar."\", 
		 langFile    = \"".$languageFileName."\", 
		 sourceFile = \"" . $sourceFileName ."\"";
		mysql_query($sql) or die($problemMessage);
	}

}

/**
 *
 * Detect included files in the script
 *
 * @return - array $includeFileList list of included file
 * @param - array $tokenList list of token from a script
 */

function detect_included_files(&$tokenList)
{
    global $includePath;

    $includeFileList = array();

    for ($i = 0, $tokenCount =  count($tokenList); $i < $tokenCount ; $i++)
    {
        if (   $tokenList[$i][0] === T_INCLUDE 
            || $tokenList[$i][0] === T_REQUIRE
            || $tokenList[$i][0] === T_INCLUDE_ONCE
            || $tokenList[$i][0] === T_REQUIRE_ONCE )
        {
            $includeFile = '';
            $bracketPile = 0;
            $i++;

            while(       $tokenList[$i][0] != ';'
                  &&     $tokenList[$i][0] != T_LOGICAL_OR 
                  && ! ( $tokenList[$i][0] == ')' && $bracketPile == 0) )
            {
                if ( is_int($tokenList[$i][0]) ) 
                {
                    $token =  $tokenList[$i][1];
                }
                else 
                {
                    $token =  $tokenList[$i][0];
                    if     ( $token == '(' ) $bracketPile++;
                    elseif ( $token == ')' ) $bracketPile--;
                    else
                    {
                    	$token =  $tokenList[$i][0];
                    }
                    
                }
                $includeFile .= $token;
                $i++;
            }
            $includeFile = str_replace('dirname(__FILE__).','',$includeFile);
            $includeFileList[] = $includeFile;
        }
    } // end loop for

    return $includeFileList;
}

/**
 * Get the list of language variables in a script and its included files
 *
 * @return - array $languageVarList or boolean FALSE
 * @param - string $file
 */

function get_lang_vars_from_file($file)
{
    global $scannedFileList;

    // *** OPTIMISATION : start *** //
    if ( isset($GLOBALS[$file]) )
    {
        return $GLOBALS[$file];
    }
    // *** OPTIMISATION : end *** //

    if ( is_scannable( realpath($file) ) )
    {
        $languageVarList      = array();
        $includeStatementList = array();
        $includedFileList     = array();

        $sourceFile = file_get_contents($file);
        $tokenList  = token_get_all($sourceFile);

        $languageVarList      = detect_lang_var($tokenList);
        $includeStatementList = detect_included_files($tokenList);

        foreach($includeStatementList as $thisIncludeStatement)
        {
             $includeRealPath= get_real_path_from_statement($thisIncludeStatement, $file);

            if ($includeRealPath && is_file($includeRealPath) ) 
            {
                 $includedFileList[] = $includeRealPath;
            }
        }

        if (count($includedFileList) > 0)
        {
            foreach($includedFileList as $thisIncludedFile)
            {
                if (! in_array( $thisIncludedFile, $scannedFileList) )
                {
                    $includedLangVarList = get_lang_vars_from_file($thisIncludedFile);
                    $scannedFileList[]   = $thisIncludedFile;
                }

                if (is_array($includedLangVarList) )
                {
                    $languageVarList =  array_merge($languageVarList, $includedLangVarList);
                }
            }
        }

        $languageVarList = array_unique($languageVarList);

        // *** OPTIMISATION : start *** //
        $GLOBALS[$file] = $languageVarList;
        // *** OPTIMISATION : end *** //

        return $languageVarList;

    } // end if scannable
    else
    {
    	return false;
    }
}

/**
 * Extract language variables from a script
 * 
 * @return - array $languageVarList
 * @param  - array $tokenList
 */

function detect_lang_var($tokenList)
{
    $languageVarList = array();

    foreach($tokenList as $thisToken)
    {
        if (is_int($thisToken[0])) 
        {
            if ( is_a_lang_var($thisToken) )
            {
                $varName = str_replace('$','',$thisToken[1]);
                $languageVarList[]=$varName;
            }
        }
    }

    return $languageVarList;
}

/**
 * Check if a token is a language variable
 * 
 * @return - boolean 
 * @param  - token $token 
 */

function is_a_lang_var($token)
{

    // token is not a variable
    if ( $token[0] != T_VARIABLE )            return false;
    
    $varName = str_replace('$','',$token[1]);

    if ( ! is_a_lang_varname($varName) )      return false;

    // if all the condition has been successfully passed ...
    return true;

}

/**
 * Check if a token is a language variable
 * 
 * @return - boolean 
 * @param  - token $token 
 */

function is_a_lang_varname($var)
{

    $pos1 = strpos( $var, 'lang' ); 
    $pos2 = strpos( $var, 'l_'   );

    // variable is not a lang variable
    if (   ( $pos1 === FALSE || $pos1 != 0 )
        && ( $pos2 === FALSE || $pos2 != 0 ) 
       )
    {
        return false;
    }

    // these variables are not language variables
    if ( $var == 'langFile')             return false;

    $pos3 = strpos( $var, 'language');
    if ( $pos3 !== FALSE && $pos3 == 0 ) return false;

    // if all the condition has been successfully passed ...
    return true;

}

/**
 * Build the real path of the script
 * @return - string $realPath
 * @param  - string $statementString 
 * @param  - string $parsedFilePath
 */

function get_real_path_from_statement($statementString, $parsedFilePath)
{
    global $includePath, $rootSys;

    $evaluatedPath = eval("return ".$statementString.";");

    if ( ! strstr($evaluatedPath, $rootSys) )
    {
        $realPath = realpath( dirname($parsedFilePath) .'/'. $evaluatedPath);
    }
    else
    {
    	$realPath = $evaluatedPath;
    }

    if ( file_exists($realPath) )  return $realPath;
    else                           return false;
}

/**
 *
 */

function compose_language_production_filename ($file)
{
    $pos = strpos($file,'claroline/');

    if ($pos === FALSE || $pos != 0)
    {
        // if the script isn't in the claroline folder the language file base name is index
        $languageFilename = 'index';
    }
    else
    {
        // else language file basename is like claroline_folder_subfolder_...
        $languageFilename = dirname($file);
        $languageFilename = str_replace('/','_',$languageFilename);
    }

    return $languageFilename;
}

/**
 *
 */

function get_lang_path_list($path_lang)
{
    $languagePathList = array();

    $handle = opendir($path_lang);

    while ($element = readdir($handle) )
    {
	    if ( $element == "." || $element == ".." || $element == "CVS" 
            || strstr($element,"~") || strstr($element,"#") 
           )
    	{
	    	continue; // skip current and parent directories
    	}
	    if ( is_dir($element) )
    	{
	    	$path = $path_lang . '/' . $element;
		    $name = reset( explode (".", $element) );
    		$languagePathList[$name] = $path;
        }
    }

    return $languagePathList;
}

?>
