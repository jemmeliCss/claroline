<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.3.2 $Revision$                             |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2002 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or      |
      |   modify it under the terms of the GNU General Public License        |
      |   as published by the Free Software Foundation; either version 2     |
      |   of the License, or (at your option) any later version.             |
      +----------------------------------------------------------------------+
      | Authors: Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Hugues Peeters    <peeters@ipm.ucl.ac.be>                   |
      |          Christophe Gesch� <gesche@ipm.ucl.ac.be>                    |
      +----------------------------------------------------------------------+
 */

$langModules ="Le modules";
$lang_no_access_here ="Pas d'acc�s ";

$langFile = "admin";
require '../inc/claro_init_global.inc.php'; 
$pathHtPassword = "./.htpasswd4admin";
$nameTools = $langNomPageAddHtPass;
$interbredcrump[]= array ("url"=>"index.php", "name"=> $langAdmin);
@include("./checkIfHtAccessIsPresent.php");
/*$htmlHeadXtra[] = "<style type=\"text/css\"><!--  --></style>
<STYLE media=\"print\" type=\"text/css\"><!--  --></STYLE>";*/
@include($includePath."/claro_init_header.inc.php");
//$TABLEAGENDA 		= $_course["dbName"]."`.`agenda";
$is_allowedToEdit 	= $is_platformAdmin;
if ($is_allowedToEdit)
{
?>
<h3>
	<?php echo $nameTools ?>
</h3>
<?php include "barre.inc.php";?>
Gestion des  outils.

les  outils qui se retrouvent dans  l'accueil sont des  outils  int�gr�s � claroline 

Chaque outil dispose

	- d'un r�pertoire
	- d'une entr�e dans la table des outils du cours
	- de ses  propres bases
	- d'un auteur
	- d'une version propre
	- d'un date de mise � jour
	- de ses fichiers langues
	- une icone -> disparaitra dans le nouveau look
	
<?php
include "barre.inc.php"; 
}
else
{
	echo $lang_no_access_here;
}

@include($includePath."/claro_init_footer.inc.php");
?>