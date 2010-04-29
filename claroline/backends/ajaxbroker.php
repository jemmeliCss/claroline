<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Ajax Broker script
 *
 * Usage:
 *  1. Register Ajax remote service in module functions.php
 *      Claroline::ajaxServiceBroker()->register( .... );
 *  2. Execute AJAX requests on get_path('url').'/claroline/backends/ajaxbroker.php'
 *
 * @version     1.10 $Revision$
 * @copyright   2001-2010 Universite catholique de Louvain (UCL)
 * @author      Claroline Team <info@claroline.net>
 * @author      Frederic Minne <zefredz@claroline.net>
 * @license     http://www.gnu.org/copyleft/gpl.html
 *              GNU GENERAL PUBLIC LICENSE version 2 or later
 * @package     kernel.utils.ajax
 * @since       Claroline 1.10
 */

try
{
    require_once dirname(__FILE__) . '/../inc/claro_init_global.inc.php';

    $url = '';

    $moduleLabel = Claro_UserInput::getInstance()->get('moduleLabel',false);

    if ( $moduleLabel )
    {
        $ajaxHandler = Ajax_Remote_Module_Service::getModuleServiceInstance( $moduleLabel );
    }

    $ajaxRequest = Ajax_Request::getRequest(Claro_UserInput::getInstance());

    $response = Claroline::ajaxServiceBroker()->handle($ajaxRequest);
}
catch (Exception $e )
{
    $response = new Json_Exception( $e );
}

header('Content-type: application/json; charset=utf-8');
echo $response->toJson();
exit;


