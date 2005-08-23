<?php // $Id$
/**
 * CLAROLINE 
 *
 * @version version 1.7
 *
 * @copyright (c) 2001, 2005 Universite catholique de Louvain (UCL)
 *
 * @license GENERAL PUBLIC LICENSE
 *
 * @author claro team <cvs@claroline.net>
 * @author Piraux S�bastien <pir@cerdecam.be>
 * @author Lederer Guillaume <led@cerdecam.be>
 *
 * @package CLLNP
 * @subpackage navigation
 *
 */

require '../../inc/claro_init_global.inc.php';

// the following constant defines the default display of the learning path browser
// 0 : display only table of content and content
// 1 : display claroline header and footer and table of content, and content
define ( 'USE_FRAMES' , 1 );

$nameTools = $langLearningPath;
if (!isset($titlePage)) $titlePage = '';
if(!empty($nameTools))
{
    $titlePage .= $nameTools.' - ';
}

if(!empty($_course['officialCode']))
{
    $titlePage .= $_course['officialCode'] . ' - ';
}
$titlePage .= $siteName;

// set charset as claro_header should do but we cannot include it here
header('Content-Type: text/html; charset=' . $charset);
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
    $displayFrames = $_REQUEST['frames'];
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
            <?php echo $langBrowserCannotSeeFrames ?>
            <br />
            <a href="../module.php"><?php echo $langBack ?></a>
        </body>
    </noframes>
</html>