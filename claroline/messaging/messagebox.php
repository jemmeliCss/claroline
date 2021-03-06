<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * CLAROLINE
 *
 * Front controler for message box.
 *
 * @version     $Revision$
 * @copyright   (c) 2001-2014, Universite catholique de Louvain (UCL)
 * @author      Claroline Team <info@claroline.net>
 * @author      Christophe Mertens <thetotof@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 *              GNU GENERAL PUBLIC LICENSE version 2 or later
 * @package     internal_messaging
 */

$cidReset = true;
require_once __DIR__ . '/../../claroline/inc/claro_init_global.inc.php';
require_once get_path('incRepositorySys') . '/lib/user.lib.php';

// ------------- Business Logic ---------------------------
if ( ! claro_is_user_authenticated() )
{
    claro_disp_auth_form(false);
}

include claro_get_conf_repository() . 'CLMSG.conf.php';
require_once __DIR__ . '/lib/permission.lib.php';

$userId = isset($_REQUEST['userId']) ? (int)$_REQUEST['userId'] : null;

$link_arg = array();

if (!is_null($userId) && !empty($userId))
{
    $currentUserId = (int)$_REQUEST['userId'];
    $link_arg['userId'] = $currentUserId;
}
else
{
    $currentUserId = claro_get_current_user_id();
}

if ($currentUserId != claro_get_current_user_id() && !claro_is_platform_admin())
{
    claro_die(get_lang("Not allowed"));
}

// user exist ?
if ($currentUserId != claro_get_current_user_id())
{
    $userData = user_get_properties($currentUserId);
    if ($userData === false)
    {
        claro_die(get_lang("User not found"));
    }
    else
    {
        $title = get_lang('Messages of %firstName %lastName',
            array ('%firstName' =>claro_htmlspecialchars($userData['firstname']), '%lastName' => claro_htmlspecialchars($userData['lastname'])));
    }
}
else
{
    $title = get_lang('My messages');
}

$linkPage = $_SERVER['PHP_SELF'];

$acceptedValues = array('inbox','outbox','trashbox');

if (!isset($_REQUEST['box']) || !in_array($_REQUEST['box'],$acceptedValues))
{
    $_REQUEST['box'] = "inbox";
}

$link_arg['box'] = $_REQUEST['box'];


require_once __DIR__ . '/lib/tools.lib.php';

$content = "";
if ($link_arg['box'] == "inbox")
{
    include __DIR__ . '/inboxcontroler.inc.php';
}
elseif ($link_arg['box'] == "outbox")
{
    include __DIR__ . '/outboxcontroler.inc.php';
}
else
{
    include __DIR__ . '/trashboxcontroler.inc.php';
}

$GLOBALS['claroline']->display->banner->breadcrumbs->append($title,$_SERVER['PHP_SELF'].'?box='.$link_arg['box']);
$GLOBALS['claroline']->display->body->appendContent(claro_html_tool_title($title));
$GLOBALS['claroline']->display->body->appendContent(claro_text_zone::get_block ( 'textzone_messaging_top', claro_is_platform_admin () ));
$GLOBALS['claroline']->display->body->appendContent($content);
// ------------ display ----------------------

echo $GLOBALS['claroline']->display->render();