<?php // $Id$
/**
      +----------------------------------------------------------------------+
      | CLAROLINE version $Revision$                                   |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2002 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      | Authors: Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Hugues Peeters    <peeters@ipm.ucl.ac.be>                   |
      |          Christophe Gesch� <gesche@ipm.ucl.ac.be>                    |
      +----------------------------------------------------------------------+
 */
/*
	if not admin ,  nothing  to do  here.
*/
session_start();

if ($_SESSION['is_platformAdmin'])
{
	//
}
{
   header("Location:../");
}
?>