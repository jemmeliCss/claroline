<?php // $Id$

/**
 * CLAROLINE
 *
 * Select the good agenda waiting that two scripts are merged.
 *
 * @version     $Revision$
 * @copyright   (c) 2001-2014, Universite catholique de Louvain (UCL)
 * @license     http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @package     CLCAL
 * @author      Claro Team <cvs@claroline.net>
 */

$tlabelReq = 'CLCAL';

require_once __DIR__ . '/../../claroline/inc/claro_init_global.inc.php';

if ( claro_is_in_a_course() )
{
    claro_redirect('./agenda.php');
}
else
{
    claro_redirect('./myagenda.php');
}
exit();
