<?php
    // $Id$
/*
  +----------------------------------------------------------------------+
  | CLAROLINE version 1.6.*                           |
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
*/

  require '../../inc/claro_init_global.inc.php'; 

  // the following constant defines the default display of the learning path browser
  // 0 : display only table of content and content
  // 1 : display claroline header and footer and table of content, and content
  define ( "USE_FRAMES" , 1 ); 
  
  $nameTools = $langLearningPath;
  if(!empty($nameTools))
  {
    $titlePage .= $nameTools.' - ';
  }
  
  if(!empty($_course['officialCode']))
  {
    $titlePage .= $_course['officialCode'].' - ';
  }
  $titlePage .= $siteName;
  
  // set charset as claro_header should do but we cannot include it here
  header('Content-Type: text/html; charset='. $charset);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
   "http://www.w3.org/TR/html4/frameset.dtd">
<html>

	<head>
		<title><?php echo $titlePage; ?></title>
	</head>
<?php
if ( !isset($_GET['frames']) )
{
    // choose default display
    // default display is without frames
    $displayFrames = USE_FRAMES;
}
else
{
    $displayFrames = $_GET['frames'];
}

if( $displayFrames )
{
?>
	<frameset border="0" rows="150,*,70" frameborder="no">
		<frame src="topModule.php" name="headerFrame" />
		<frame src="startModule.php" name="mainFrame" />         
		<frame src="bottomModule.php" name="bottomFrame" />
	</frameset>
<?php
}
else
{
?>
	<frameset cols="*" border="0">
		<frame src="startModule.php" name="mainFrame" />    
	</frameset>
<?php
}
?>
	<noframes>
	<body>
  
  	<?php
		echo $langBrowserCannotSeeFrames."<br />"
			."<a href=\"../module.php\">".$langBack."</a>";
	?>
  
	</body>
	</noframes>
</html>
