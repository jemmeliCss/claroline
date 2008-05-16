<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * index of administrator page
 *
 * @version     1.9 $Revision$
 * @copyright   2001-2008 Universite catholique de Louvain (UCL)
 * @author      Claroline Team <info@claroline.net>
 * @author      Christophe Mertens <thetotof@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 *              GNU GENERAL PUBLIC LICENSE version 2 or later
 * @package     internal_messaging
 */



$cidReset = TRUE; 
require_once dirname(__FILE__) . '/../../claroline/inc/claro_init_global.inc.php';
// manager of the admin message box
require_once dirname(__FILE__) . '/lib/messagebox/adminmessagebox.lib.php';

require_once dirname(__FILE__) . '/lib/tools.lib.php';

// move to kernel
$claroline = Claroline::getInstance();

// ------------- permission ---------------------------
if ( ! claro_is_user_authenticated())
{
    claro_disp_auth_form(false);
}

if ( ! claro_is_platform_admin() )
{
    claro_die(get_lang('Not allowed'));
}

// -------------- business logic ----------------------
$content = "";

// ---- display

$warningMessage = get_lang('Warning: When you delete a message keep in mind that it will be deleted for every user.
        <br /><br />You cannot retrieve deleted messages!');

$dialbox = new DialogBox();
$dialbox->warning($warningMessage);
$content .= $dialbox->render();

$content .=
        '<h4>'.get_lang('Delete').'</h4>'."\n"
        .'<ul>'."\n"
        .'<li><a href="admin_delete.php?cmd=rqDeleteAll">'.get_lang('all messages').'</a></li>' ."\n"
        .'<li><a href="admin_delete.php?cmd=rqFromUser">'.get_lang('message from a user').'</a></li>' ."\n"
        .'<li><a href="admin_delete.php?cmd=rqOlderThan">'.get_lang('message older than').'</a></li>' ."\n"
        .'<li><a href="admin_delete.php?cmd=rqPlateformMessage">'.get_lang('plateform message').'</a></li>' ."\n"
        .'</ul>'."\n"

        . '<h4>'.get_lang('Search').'</h4>'."\n"
        .'<ul>'."\n"
        .'	<li><a href="admin_search.php?search=fromUser">'.get_lang('message from a user').'</a></li>' ."\n"
        .'	<li><a href="admin_search.php?search=olderThan">'.get_lang('message older than').'</a></li>' ."\n"
        .'	<li><a href="admin_search.php?search=timeInterval">'.get_lang('message in date interval').'</a></li>' ."\n"
        .'	<li><a href="admin_search.php?search=plateformMessage">'.get_lang('plateform message').'</a></li>' ."\n"
        .'</ul>'."\n"
        ;

$claroline->display->banner->breadcrumbs->append(get_lang('Messages'),'index.php');
$claroline->display->banner->breadcrumbs->append(get_lang('Administration'),'admin.php');

$claroline->display->body->appendContent(claro_html_tool_title(get_lang('Administration')));
$claroline->display->body->appendContent($content);

echo $claroline->display->render();

?>