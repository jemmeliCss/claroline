<?php
/* $Id$ */
// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Insert data from one table to another one
 *
 * @param   string  the original insert statement
 *
 * @global  string  the database name
 * @global  string  the original table name
 * @global  string  the target database and table names
 * @global  string  the sql query used to copy the data
 */
function PMA_myHandler($sql_insert = '')
{
    global $db, $table, $target;
    global $sql_insert_data;

    $sql_insert = eregi_replace('INSERT INTO (`?)' . $table . '(`?)', 'INSERT INTO ' . $target, $sql_insert);
    $result     = PMA_mysql_query($sql_insert) or PMA_mysqlDie('', $sql_insert, '', $GLOBALS['err_url']);

    $sql_insert_data .= $sql_insert . ';' . "\n";
} // end of the 'PMA_myHandler()' function


/**
 * Gets some core libraries
 */
require('./libraries/grab_globals.lib.php');
require('./libraries/common.lib.php');


/**
 * Defines the url to return to in case of error in a sql statement
 */
$err_url = 'tbl_properties.php?' . PMA_generate_common_url($db, $table);


/**
 * Selects the database to work with
 */
PMA_mysql_select_db($db);


/**
 * A target table name has been sent to this script -> do the work
 */
if (isset($new_name) && trim($new_name) != '') {
    $use_backquotes = 1;
    $asfile         = 1;

    if (get_magic_quotes_gpc()) {
        if (!empty($target_db)) {
            $target_db = stripslashes($target_db);
        } else {
            $target_db = stripslashes($db);
        }
        $new_name      = stripslashes($new_name);
    }

    // Ensure the target is valid
    if (count($dblist) > 0 &&
        (PMA_isInto($db, $dblist) == -1 || PMA_isInto($target_db, $dblist) == -1)) {
        exit();
    }
    if (PMA_MYSQL_INT_VERSION < 32306) {
        PMA_checkReservedWords($target_db, $err_url);
        PMA_checkReservedWords($new_name, $err_url);
    }

    $source = PMA_backquote($db) . '.' . PMA_backquote($table);
    $target = PMA_backquote($target_db) . '.' . PMA_backquote($new_name);

    include('./libraries/build_dump.lib.php');

    $sql_structure = PMA_getTableDef($db, $table, "\n", $err_url);
    $parsed_sql =  PMA_SQP_parse($sql_structure);
    // no need to PMA_backquote()
    $parsed_sql[2]['data'] = $target;
    $sql_structure = PMA_SQP_formatHtml($parsed_sql, 'query_only'); 

    // do not create the table if dataonly
    if ($what != 'dataonly') {
        $result        = @PMA_mysql_query($sql_structure);
        if (PMA_mysql_error()) {
            include('./header.inc.php');
            PMA_mysqlDie('', $sql_structure, '', $err_url);
        } else if (isset($sql_query)) {
            $sql_query .= "\n" . $sql_structure . ';';
        } else {
            $sql_query = $sql_structure . ';';
        }
    } else {
        $sql_query='';
    }

    // Copy the data
    if ($result != FALSE && ($what == 'data' || $what == 'dataonly')) {
        // speedup copy table - staybyte - 22. Juni 2001
        if (PMA_MYSQL_INT_VERSION >= 32300) {
            $sql_insert_data = 'INSERT INTO ' . $target . ' SELECT * FROM ' . $source;
            $result          = @PMA_mysql_query($sql_insert_data);
            if (PMA_mysql_error()) {
                include('./header.inc.php');
                PMA_mysqlDie('', $sql_insert_data, '', $err_url);
            }
        } // end MySQL >= 3.23
        else {
            $sql_insert_data = '';
            PMA_getTableContent($db, $table, 0, 0, 'PMA_myHandler', $err_url,'');
        } // end MySQL < 3.23
        $sql_query .= "\n\n" . $sql_insert_data;
    }

    // Drops old table if the user has requested to move it
    if (isset($submit_move)) {
        $sql_drop_table = 'DROP TABLE ' . $source;
        $result         = @PMA_mysql_query($sql_drop_table);
        if (PMA_mysql_error()) {
            include('./header.inc.php');
            PMA_mysqlDie('', $sql_drop_table, '', $err_url);
        }
        $sql_query      .= "\n\n" . $sql_drop_table . ';';
        $db             = $target_db;
        $table          = $new_name;
    }

    $message   = (isset($submit_move) ? $strMoveTableOK : $strCopyTableOK);
    $message   = sprintf($message, $source, $target);
    $reload    = 1;
    $js_to_run = 'functions.js';
    include('./header.inc.php');
} // end is target table name


/**
 * No new name for the table!
 */
else {
    include('./header.inc.php');
    PMA_mysqlDie($strTableEmpty, '', '', $err_url);
}


/**
 * Back to the calling script
 */
require('./tbl_properties.php');
?>
