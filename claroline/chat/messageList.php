<?php // $Id$
/**
 * CLAROLINE 
 *
 * This script  chat simply works with a flat file where lines are appended. 
 * Simple user can  just  write lines. 
 * Chat manager can reset and store the chat if $chatforgroup is true,  
 * the file  is reserved because always formed 
 * with the group id of the current user in the current course.
 *
 * @version 1.7 $Revision$
 *
 * @copyright 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @see http://www.claroline.net/wiki/index.php/CLCHT
 *
 * @package CLCHAT
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Christophe Gesch� <moosh@claroline.net>
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 *
 */

// CLAROLINE INIT
$tlabelReq = 'CLCHT___'; // required
require '../inc/claro_init_global.inc.php';

if ( ! $_cid || ( ! $is_courseAllowed && !$_uid ) )
{
die ('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'."\n"
    .'<html>'."\n"
    .'<head>'."\n"
    .'<title>'.$langChat.'</title>'."\n"
    .'</head>'."\n"
    .'<body>'."\n"."\n"
    .'<a href="./chat.php" >click</a>' . "\n"
    .'</body>'."\n"."\n"
    
);


}


/*============================================================================
        CONNECTION BLOC
============================================================================*/


$coursePath  = $coursesRepositorySys.$_course['path'];
$courseId    = $_cid;
$groupId     = $_gid;

$is_allowedToManage = $is_courseAdmin;
$is_allowedToStore  = $is_courseAdmin;
$is_allowedToReset  = $is_courseAdmin;


if ( $_user['firstName'] == '' && $_user['lastName'] == '')
{
    $nick = $langAnonymous;
} 
else
{
    $nick = $_user['firstName'] . ' ' . $_user['lastName'] ;
    if (strlen($nick) > $max_nick_length) $nick = $_user['firstName'] . ' '. $_user['lastName'][0] . '.' ;
}


// theses  line prevent missing config file
$refresh_display_rate = (int) $refresh_display_rate;
if (!isset($refresh_display_rate) || $refresh_display_rate==0)  $refresh_display_rate = 10;

/*============================================================================
        CHAT INIT
============================================================================*/


// THE CHAT NEEDS A TEMP FILE TO RECORD CONVERSATIONS.
// THIS FILE IS STORED IN THE COURSE DIRECTORY

$curChatRep = $coursePath.'/chat/';

// IN CASE OF AN UPGRADE THE DIRECTORY MAY NOT EXIST
// A PREVIOUS CHECK (AND CREATE IF NEEDED) IS THUS NECESSARY

if ( ! is_dir($curChatRep) ) mkdir($curChatRep, CLARO_FILE_PERMISSIONS);

// DETERMINE IF THE CHAT SYSTEM WILL WORK
// EITHER AT THE COURSE LEVEL OR THE GROUP LEVEL

if ($_gid)
{
    if ($is_groupAllowed)
    {
        $groupContext  = TRUE;
        $courseContext = FALSE;

        $is_allowedToManage = $is_allowedToManage|| $is_groupTutor ;
        $is_allowedToStore  = $is_allowedToStore || $is_groupTutor;
        $is_allowedToReset  = $is_allowedToReset || $is_groupTutor;

        $activeChatFile = $curChatRep.$courseId.'.'.$groupId.'.chat.html';
        $onflySaveFile  = $curChatRep.$courseId.'.'.$groupId.'.tmpChatArchive.html';
        $exportFile     = $coursePath.'/group/'.$_group['directory'].'/';
    }
    else
    {
        die('<center>'.$langNotGroupMember.'</center>');
    }
}
else
{
    $groupContext  = FALSE;
    $courseContext = TRUE;

    $activeChatFile = $curChatRep.$courseId.'.chat.html';
    $onflySaveFile  = $curChatRep.$courseId.'.tmpChatArchive.html';
    $exportFile     = $coursePath.'/document/';
}


$dateNow = claro_disp_localised_date($dateTimeFormatLong);
$timeNow = claro_disp_localised_date('[%d/%m/%y %H:%M]');

if ( ! file_exists($activeChatFile))
{
    // create the file
    $fp = @fopen($activeChatFile, 'w')
    or die ('<center>'.$langCannotInitChat.'</center>');
    fclose($fp);

    $dateLastWrite = $langNewChat;
}








/*============================================================================
        COMMANDS
============================================================================*/




/*----------------------------------------------------------------------------
        RESET COMMAND
----------------------------------------------------------------------------*/


if ( isset($_REQUEST['cmd']) && $_REQUEST['cmd'] == 'reset' && $is_allowedToReset)
{
    $fchat = fopen($activeChatFile,'w');
    fwrite($fchat, '<small>'.$timeNow.' -------- '.$langChatResetBy.' '.$nick.' --------</small><br />'."\n");
    fclose($fchat);

    @unlink($onflySaveFile);
}




/*----------------------------------------------------------------------------
        STORE COMMAND
----------------------------------------------------------------------------*/

if ( isset($_REQUEST['cmd']) && $_REQUEST['cmd'] == 'store' && $is_allowedToStore)
{
    $chatDate = 'chat.'.date('Y-m-j').'_';

    // TRY DO DETERMINE A FILE NAME THAT DOESN'T ALREADY EXISTS
    // IN THE DIRECTORY WHERE THE CHAT EXPORT WILL BE STORED

    $i = 1;
    while ( file_exists($exportFile.$chatDate.$i.'.html') ) $i++;

    $saveIn = $chatDate.$i.'.html';

    // COMPLETE THE ON FLY BUFFER FILE WITH THE LAST LINES DISPLAYED
    // BEFORE PROCEED TO COMPLETE FILE STORAGE

    buffer( implode('', file($activeChatFile) ).'</body>'."\n\n".'</html>'."\n",
    $onflySaveFile);

    if (copy($onflySaveFile, $exportFile.$saveIn) )
    {
        $cmdMsg = "\n"
                . '<blockquote>'."\n"
                . '<a href="../document/document.php" target="blank">'
                . '<strong>'.$saveIn.'</strong>'
                . '</a> '
                . $langIsNowInYourDocDir."\n"
                . '</blockquote>'."\n\n"
                ;

        @unlink($onflySaveFile);
    }
    else
    {
        $cmdMsg = '<blockquote>'.$langCopyFailed.'</blockquote>'."\n";
    }
}




/*----------------------------------------------------------------------------
    'ADD NEW LINE' COMMAND
----------------------------------------------------------------------------*/
// don't use empty() because it will prevent to post a line with only "0"
if ( isset($_REQUEST['chatLine']) && trim($_REQUEST['chatLine']) != "" )
{
    $fchat = fopen($activeChatFile,'a');
    $chatLine = htmlspecialchars( $_REQUEST['chatLine'] );
    // replace url with real html link
    $chatLine = ereg_replace("(http://)(([[:punct:]]|[[:alnum:]])*)","<a href=\"\\0\" target=\"_blank\">\\2</a>",$chatLine);

    fwrite($fchat,
    '<small>'
    .$timeNow.' &lt;<b>'.$nick.'</b>&gt; '.$chatLine
    ."</small><br />\n");

    fclose($fchat);
}








/*============================================================================
DISPLAY MESSAGE LIST
============================================================================*/

if ( !isset($dateLastWrite) )
{
    $dateLastWrite = $langDateLastWrite
                .strftime( $dateTimeFormatLong , filemtime($activeChatFile) );
}


// WE DON'T SHOW THE COMPLETE MESSAGE LIST.
// WE TAIL THE LAST LINES


$activeLineList  = file($activeChatFile);
$activeLineCount = count($activeLineList);

$excessLineCount = $activeLineCount - $max_line_to_display;
if ($excessLineCount < 0) $excessLineCount = 0;
$excessLineList = array_splice($activeLineList, 0 , $excessLineCount);
$curDisplayLineList = $activeLineList;



// DISPLAY

// CHAT MESSAGE LIST OWN'S HEADER
// add a unique number in the url to make IE believe that the url is different and to force refresh
if( !isset($_REQUEST['x']) || $_REQUEST['x'] == 1 )
{
    $x = 0;
}
else
{
    $x = 1;
}

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'."\n"
	.'<html>'."\n"
    .'<head>'."\n"
    .'<title>'.$langChat.'</title>'
	.'<meta http-equiv="refresh" content="'.$refresh_display_rate.';url=./messageList.php?x='.$x.'#final">'."\n"
	.'<link rel="stylesheet" type="text/css" href="'.$clarolineRepositoryWeb.'css/'.$claro_stylesheet.'" >'."\n"
	.'</head>'."\n"
	.'<body>'."\n"."\n"
	;

if( isset($cmdMsg) )
{
    echo $cmdMsg;
}

echo implode("\n", $curDisplayLineList) // LAST LINES
	."\n"
    .'<p align="right"><small>'
    .$dateLastWrite                 // LAST MESSAGE DATE TIME
    .'</small></p>'."\n\n"
    .'<a name="final"></a>'."\n\n"       // ANCHOR ALLOWING TO DIRECTLY POINT LAST LINE
    .'</body>'."\n\n"
    .'</html>'."\n"
    ;

// FOR PERFORMANCE REASON, WE TRY TO KEEP THE ACTIVE CHAT FILE OF REASONNABLE
// SIZE WHEN THE EXCESS LINES BECOME TOO HIGH WE REMOVE THEM FROM THE ACTIVE
// CHAT FILE AND STORE THEM IN A SORT OF 'ON FLY BUFFER' WHILE WAITHING A
// POSSIBLE EXPORT FOR DEFINITIVE STORAGE


if ($activeLineCount > $max_line_in_file)
{

    // STORE THE EXCESS LINES INTO THE 'ON FLY BUFFER'

    buffer(implode('',$excessLineList), $onflySaveFile);

    // REFRESH THE ACTIVE CHAT FILE TO KEEP ONLY NON SAVED TAIL

    $fp = fopen($activeChatFile, 'w');
    fwrite($fp, implode("\n", $curDisplayLineList));
}

//////////////////////////////////////////////////////////////////////////////

function buffer($content, $tmpFile)
{
    global $langChat, $langArchive;

    if ( ! file_exists($tmpFile) )
    {
        $content = '<html>'."\n"
                 . '<head>'."\n"
                 . '<title>'.$langChat.' - '.$langArchive.'</title>'."\n"
                 . '</head>'."\n\n"
                 . '<body>'."\n"
                 . $content
                 ;
    }

    $fp = fopen($tmpFile, 'a');
    fwrite($fp, $content);
}
?>
 
	