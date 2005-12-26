<?php // $Id$
/**
 * CLAROLINE
 *
 * Filler for tools in course
 *
 * @version 1.8 $Revision$
 *
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @package SDK
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Christophe Gesch� <moosh@claroline.net>
 *
 */

/**
 * This script allows to switch on the fly the wysiwyg editor. It retrieves the 
 * source url  and the textarea content, and after storing in session a value 
 * disabling the wysiwyg editor, it trigs a relocation to the source page with 
 * the area content.
 */

require '../inc/claro_init_global.inc.php';

$sourceUrl = preg_replace('|[&?]areaContent=.*|', '', $_REQUEST['sourceUrl'] );

$urlBinder = strpos($sourceUrl, '?') ? '&' : '?';
//$urlBinder = '&';

$content = stripslashes($_REQUEST['areaContent']);
if($_REQUEST['switch'] == 'off')
{
    $_SESSION['htmlEditor'] = 'disabled';
    $areaContent = urlencode( html2txt($content) );
}
elseif ($_REQUEST['switch'] == 'on' )
{
    $_SESSION['htmlEditor'] = 'enabled';
    $areaContent = urlencode(str_replace("\n", '<br />', '<!-- content: html -->'.$content));
}

header('Cache-Control: no-store, no-cache, must-revalidate');   // HTTP/1.1
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');                                     // HTTP/1.0
header('Location: '. $sourceUrl . $urlBinder . 'areaContent=' . $areaContent);

function html2txt($content)
{
    static $ruleList = array(
                             '<br[^>]*>'          => "\n"  ,
                             '<p[^>]*>'           => "\n\n",
                             '<blockquote[^>]*>'  => "\n\n",
                             '</blockquote[^>]*>' => "\n\n",
                             '<table[^>]*>'       => "\n\n",
                             '</table[^>]*>'      => "\n\n",
                             '<tr[^>]*>'          => "\n"  ,
                             '<td[^>]*>'          => "\t"  ,
                             '<hr[^>]*>'          => "\n--------------------------------------------------\n"
                            );

    foreach($ruleList as $pattern => $replace)
    {
        $content = preg_replace('|'.$pattern.'|i', $replace , $content);
    }

    return strip_tags($content);
}





?>