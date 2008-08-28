<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Claroline Resource Linker ajax backend
 *
 * @version     1.9 $Revision$
 * @copyright   2001-2008 Universite catholique de Louvain (UCL)
 * @author      Claroline Team <info@claroline.net>
 * @author      Frederic Minne <zefredz@claroline.net>
 * @license     http://www.gnu.org/copyleft/gpl.html
 *              GNU GENERAL PUBLIC LICENSE version 2 or later
 * @package     core.linker
 */

try
{
    require_once dirname(__FILE__) . '/../inc/claro_init_global.inc.php';
    
    FromKernel::uses( 'core/linker.lib', 'utils/ajax.lib' );
    ResourceLinker::init();

    $locator = isset( $_REQUEST['crl'] )
        ? ClarolineResourceLocator::parse($_REQUEST['crl'])
        : ResourceLinker::$Navigator->getCurrentLocator( array() );
        ;
    
    if ( !ResourceLinker::$Navigator->isNavigable( $locator ) )
    {
        throw new Exception('Resource not navigable');
    }
    
    $resourceList = ResourceLinker::$Navigator->getResourceList( $locator );
    
    $elementList = $resourceList->toArray();
    
    $resourceArr = array();
    $resourceArr['name'] = ResourceLinker::$Resolver->getResourceName( $locator );
    $resourceArr['crl'] = $locator->__toString();
    
    $parent = ResourceLinker::$Navigator->getParent( $locator );
    
    $resourceArr['parent'] = (empty($parent) ? false : $parent->__toString());
    $resourceArr['resources'] = $elementList;
    
    $response = new Json_Response( $resourceArr );
}
catch (Exception $e )
{
    $response = new Json_Exception( $e );
}

echo $response->toJson();
exit;
