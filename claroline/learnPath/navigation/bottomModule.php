<?php // $Id$
/*
  +----------------------------------------------------------------------+
  | CLAROLINE version 
  +----------------------------------------------------------------------+
  | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
  +----------------------------------------------------------------------+
  | This source file is subject to the GENERAL PUBLIC LICENSE,           |
  | available through the world-wide-web at                              |
  | http://www.gnu.org/copyleft/gpl.html                                 |
  +----------------------------------------------------------------------+
  | Authors: Piraux S�bastien <pir@cerdecam.be>                          |
  |          Lederer Guillaume <led@cerdecam.be>                         |
  +----------------------------------------------------------------------+

  DESCRIPTION:
  ****

*/
 /**
  * This script creates the bottom frame needed when we browse a module that needs to use frame
  * This appens when the module is SCORM (@link http://www.adlnet.org )or made by the user with his own html pages.
  *
  * @package learningpath
  * @subpackage navigation
  * @author Piraux S�bastien <pir@cerdecam.be>
  * @author Lederer Guillaume <led@cerdecam.be>
  * @filesource
  * @copyright This source file is subject to the GENERAL PUBLIC LICENSE, available through the world-wide-web at @link http://www.gnu.org/copyleft/gpl.html
  */
/*======================================
       CLAROLINE MAIN
  ======================================*/

  // global variable declaration

  require '../../inc/claro_init_global.inc.php';
  
  // header
  $hide_banner = true;
  $hide_body = true;
  include($includePath."/claro_init_header.inc.php");
  // footer
  include($includePath."/claro_init_footer.inc.php");

 ?>

