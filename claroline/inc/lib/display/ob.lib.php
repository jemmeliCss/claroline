<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Output buffering functions to provide output
 * buffering with error and exception handling
 *
 * @version     Claroline 1.12 $Revision$
 * @copyright   (c) 2001-2014, Universite catholique de Louvain (UCL)
 * @author      Claroline Team <info@claroline.net>
 * @author      Frederic Minne <zefredz@claroline.net>
 * @license     http://www.gnu.org/copyleft/gpl.html
 *              GNU GENERAL PUBLIC LICENSE version 2 or later
 * @package     kernel.display
 */

require_once __DIR__ . '/../core/exception.lib.php';

/**
 * Exception handler to be used inside an output buffer
 * @param Exception $e
 */
function claro_ob_exception_handler( $e )
{
    // get buffer contents
    $buffer = ob_get_contents();
    // close the output buffer
    ob_end_clean();
    // display the buffer contents
    echo $buffer;
    // display the exception
    if ( claro_debug_mode() )
    {
        echo '<pre>' . $e->__toString() . '</pre>';
    }
    else
    {
        echo '<p>' . $e->getMessage() . '</p>';
    }
}

/**
 * Start output buffering
 */
function claro_ob_start()
{
    // set error handlers for output buffering :
    set_error_handler('exception_error_handler', error_reporting() & ~E_STRICT);
    set_exception_handler('claro_ob_exception_handler');
    // start output buffering
    ob_start();
}

/**
 * Stop output buffering
 */
function claro_ob_end_clean()
{
    // end output buffering
    ob_end_clean();
    // restore original error handlers
    restore_exception_handler();
    restore_error_handler();
}

/**
 * Return buffer contents
 */
function claro_ob_get_contents()
{
    return ob_get_contents();
}
