<?php // $Id$
if ( count( get_included_files() ) == 1 ) die( '---' );

// define timezone

$tz = ini_get('date.timezone');

if ( empty( $tz ) )
{
    ini_set('date.timezone','UTC');
    date_default_timezone_set('UTC');
}
else
{
    ini_set('date.timezone',date_default_timezone_get());
    date_default_timezone_set(date_default_timezone_get());
}


// Most PHP package has increase the error reporting.
// The line below set the error reporting to the most fitting one for Claroline
//error_reporting(error_reporting() & ~ E_NOTICE);

// Determine the directory path where this current file lies
// This path will be useful to include the other intialisation files

$includePath = realpath(__DIR__.'/../../inc');

if ( file_exists($includePath . '/conf/claro_main.conf.php') )
{
    include $includePath . '/conf/claro_main.conf.php';
}
elseif ( file_exists($includePath . '/../../platform/conf/claro_main.conf.php') )
{
    include $includePath . '/../../platform/conf/claro_main.conf.php';
}
else
{
    die ('<center>'
       .'WARNING ! SYSTEM UNABLE TO FIND CONFIGURATION SETTINGS.'
       .'<p>'
       .'If it is your first connection to your Claroline platform, '
       .'read thoroughly INSTALL.txt file provided in the Claroline package.'
       .'</p>'
       .'</center>');
}

// Path to the PEAR library. PEAR stands for "PHP Extension and Application
// Repository". It is a framework and distribution system for reusable PHP
// components. More on http://pear.php.net.
// Claroline is provided with the basic PEAR components needed by the
// application in the "claroline/inc/lib/pear" directory. But, server
// administator can redirect to their own PEAR library directory by setting
// its path to the PEAR_LIB_PATH constant.

define('PEAR_LIB_PATH', $includePath.'/lib/pear');

// Add the Claroline PEAR path to the php.ini include path
// This action is mandatory because PEAR inner include() statements
// rely on the php.ini include_path settings

set_include_path( '.' . PATH_SEPARATOR . PEAR_LIB_PATH . PATH_SEPARATOR . get_include_path() );

// Unix file permission access ...

define('CLARO_FILE_PERMISSIONS', 0777);

// verbose mode

if ( defined('CLARO_DEBUG_MODE') && CLARO_DEBUG_MODE )
{
    $verbose = true;
}
else
{
    $verbose = false;
}

/*----------------------------------------------------------------------
  Start session
  ----------------------------------------------------------------------*/

if ( isset($platform_id) )
{
    session_name($platform_id);
}

session_start();

/*----------------------------------------------------------------------
  Include main library
  ----------------------------------------------------------------------*/

require_once $includePath . '/lib/core/core.lib.php';
require_once $includePath . '/lib/claro_main.lib.php';
require_once $includePath . '/lib/core/claroline.lib.php';
require_once $includePath . '/lib/fileManage.lib.php';

// conf variables

$coursesRepositorySys   = get_conf('rootSys') . $coursesRepositoryAppend;
$coursesRepositoryWeb   = get_conf('urlAppend') . '/' . $coursesRepositoryAppend;
$clarolineRepositorySys = get_conf('rootSys') . $clarolineRepositoryAppend;

/*----------------------------------------------------------------------
  Include upgrade library
  ----------------------------------------------------------------------*/

require_once $includePath . '/lib/config.lib.inc.php';
require_once __DIR__ . '/configUpgrade.class.php';
require_once __DIR__ . '/upgrade.lib.php';

/**
 * List of accepted error - See MySQL error codes :
 *
 * Error: 1017 SQLSTATE: HY000 (ER_FILE_NOT_FOUND) : already upgraded
 * Error: 1050 SQLSTATE: 42S01 (ER_TABLE_EXISTS_ERROR) : already upgraded
 * Error: 1060 SQLSTATE: 42S21 (ER_DUP_FIELDNAME)  : already upgraded
 * Error: 1062 SQLSTATE: 23000 (ER_DUP_ENTRY) : duplicate entry '%s' for key %d
 * Error: 1065 SQLSTATE: 42000 (ER_EMPTY_QUERY) : when  sql contain only a comment
 * Error: 1091 SQLSTATE: 42000 (ER_CANT_DROP_FIELD_OR_KEY) : Can't DROP '%s'; check that column/key exists
 * Error: 1146 SQLSTATE: 42S02 (ER_NO_SUCH_TABLE) : already upgraded
 * @see http://dev.mysql.com/doc/mysql/en/error-handling.html
 */

$accepted_error_list = array(1060,1061);

/*
 * Initialize version variables
 */

// Current Version
$current_version = get_current_version();
$currentClarolineVersion = $current_version['claroline'];
$currentDbVersion = $current_version['db'];

// New Version
$this_new_version = get_new_version();
$new_version = $this_new_version['complete'];
$new_version_branch = $this_new_version['branch'];
$patternVarVersion = $this_new_version['patternVarVersion'];
$patternSqlVersion = $this_new_version['patternSqlVersion'];

/*----------------------------------------------------------------------
  Unquote GET, POST AND COOKIES if magic quote gpc is enabled in php.ini
  ----------------------------------------------------------------------*/

claro_unquote_gpc();

/*----------------------------------------------------------------------
  Connect to the server database and select the main claroline DB
  ----------------------------------------------------------------------*/

$db = @($GLOBALS["___mysqli_ston"] = mysqli_connect($dbHost,  $dbLogin,  $dbPass))
or die ('<center>'
       .'WARNING ! SYSTEM UNABLE TO CONNECT TO THE DATABASE SERVER.'
       .'</center>');

$selectResult = ((bool)mysqli_query($db, "USE $mainDbName"))
or die ( '<center>'
        .'WARNING ! SYSTEM UNABLE TO SELECT THE MAIN CLAROLINE DATABASE.'
        .'</center>');

if ($statsDbName == '')
{
    $statsDbName = $mainDbName;
}

/*----------------------------------------------------------------------
  Load language files
  ----------------------------------------------------------------------*/

$languageInterface = $platformLanguage;

// include the language file with all language variables

include($includePath . '/../lang/english/complete.lang.php');

if ($languageInterface  != 'english') // Avoid useless include as English lang is preloaded
{
    include($includePath.'/../lang/' . $languageInterface . '/complete.lang.php');
}

// include the locale settings language

include($includePath.'/../lang/english/locale_settings.php');

if ( $languageInterface  != 'english' ) // // Avoid useless include as English lang is preloaded
{
   include($includePath.'/../lang/'.$languageInterface.'/locale_settings.php');
}

/*----------------------------------------------------------------------
  Authentification as platform administrator
  ----------------------------------------------------------------------*/

$tbl_mdb_names = claro_sql_get_main_tbl();
$tbl_user      = $tbl_mdb_names['user' ];
$tbl_admin     = get_conf('mainDbName') . '`.`' . get_conf('mainTblPrefix') . 'admin' ;

// default variables initialization
$claro_loginRequested = false;
$claro_loginSucceeded = null;

if ( isset($_REQUEST['login']) ) $login = $_REQUEST['login'];
else                             $login = null;

if ( isset($_REQUEST['password']) ) $password = $_REQUEST['password'];
else                                $password = null;

if ( ! empty($_SESSION['_uid']) && ! ($login) )
{
    // uid is in session => login already done, continue with this value
    $_uid = $_SESSION['_uid'];

    if ( !empty($_SESSION['is_platformAdmin']) ) $is_platformAdmin = $_SESSION['is_platformAdmin'];
    else                                         $is_platformAdmin = false;
}
else
{
    $_uid = null; // uid not in session ? prevent any hacking
    $is_platformAdmin = false;

    if ( $login && $password ) // $login && $password are given to log in
    {
        // lookup the user in the Claroline database
        $sql = "SHOW TABLES FROM `". $mainDbName."` LIKE '" . get_conf('mainTblPrefix') . "admin'";

        if(claro_sql_query_get_single_row($sql))
        {
            $sql = "SELECT user_id, username, password, authSource, creatorId
                        FROM `".$tbl_user."` `user`, `" . $tbl_admin . "` `admin`
                        WHERE BINARY username = '". claro_sql_escape($login) ."'
                        AND `user`.`user_id` = `admin`.`idUser` ";
        }
        else
        {
            $sql = "SELECT user_id, username, password, authSource, creatorId, isPlatformAdmin
                FROM `".$tbl_user."` `user`
                WHERE BINARY username = '". claro_sql_escape($login) ."'
                AND `user`.`isPlatformAdmin` = '1' ";
        }
        $result = claro_sql_query($sql) or die ('WARNING !! DB QUERY FAILED ! '.__LINE__);

        if ( mysqli_num_rows($result) > 0)
        {
            $uData = mysqli_fetch_array($result);

            // the authentification of this user is managed by claroline itself
            $password = stripslashes( $password );
            $login    = stripslashes( $login    );

            // determine if the password needs to be crypted before checkin
            // $userPasswordCrypted is set in main configuration file

            if ( $userPasswordCrypted ) $password = md5($password);

            // check the user's password
            if ( $password == $uData['password'] )
            {
                $_uid = $uData['user_id'];
                $is_platformAdmin = true;
                $claro_loginRequested = true;
                $claro_loginSucceeded = true;
            }
            else // abnormal login -> login failed
            {
                $_uid                 = null;
                $is_platformAdmin     = false;
                $claro_loginRequested = true;
                $claro_loginSucceeded = false;
            }
        }
    }
}

if ( isset($_uid) ) $_SESSION['_uid'] = $_uid;
else                $_SESSION['_uid'] = null; // unset

if ( isset($is_platformAdmin) ) $_SESSION['is_platformAdmin'] = $is_platformAdmin;
else                            $_SESSION['is_platformAdmin'] = null;

// allow to force upgrade in development mode
if ( defined('DEVEL_MODE') && DEVEL_MODE === true && isset($_REQUEST['force_upgrade']) && (bool) $_REQUEST['force_upgrade'] )
{
    $forceUpgrade = true;
    $_SESSION['forceUpgrade'] = true;
}
elseif ( isset($_SESSION['forceUpgrade']) && $_SESSION['forceUpgrade'] = true )
{
    $forceUpgrade = true;
}
else
{
    $forceUpgrade = false;
}
