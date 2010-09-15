<?php // $Id$
/**
 * CLAROLINE
 *
 * This script present state of
 * - configuration of Claroline, PHP, Mysql, Webserver
 * - credits
 *
 * @version 1.8 $Revision$
 * @copyright (c) 2001-2010, Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @author : Christophe Gesch� <moosh@claroline.net>
 *
 * @package MAINTENANCE
 */

require '../../inc/claro_init_global.inc.php';

require_once dirname( __FILE__ ) . '/lib/phpinfo.lib.php';

// Security check
if ( ! claro_is_user_authenticated() ) claro_disp_auth_form();
if ( ! claro_is_platform_admin() ) claro_die(get_lang('Not allowed'));

$claroCreditFilePath = get_path('rootSys').'CREDITS.txt';

if( file_exists(get_path('rootSys').'platform/currentVersion.inc.php') )
{
    include (get_path('rootSys').'platform/currentVersion.inc.php');
}

require dirname(__FILE__) .'/../../inc/installedVersion.inc.php';

if( ! claro_is_platform_admin() ) claro_disp_auth_form();



if( ! isset($clarolineVersion) )  $clarolineVersion= 'X';


$nameTools = get_lang('System Info');

//ClaroBreadCrumbs::getInstance()->prepend( get_lang('Technical Tools'), get_path('rootAdminWeb').'technical' );
ClaroBreadCrumbs::getInstance()->prepend( get_lang('Administration'), get_path('rootAdminWeb') );

$is_allowedToAdmin = claro_is_platform_admin();

if ($is_allowedToAdmin)
{
    $htmlHeadXtra[] = phpinfo_getStyle();
    include get_path('incRepositorySys') . '/claro_init_header.inc.php';

    echo claro_html_tool_title( array( 'mainTitle'=>$nameTools, 'subTitle'=> get_conf('siteName') ) );

    $cmd = array_key_exists( 'cmd', $_REQUEST ) ? $_REQUEST['cmd'] : 'versions';
    $ext = array_key_exists( 'ext', $_REQUEST ) ? $_REQUEST['ext'] : '';

?>

<ul id="navlist">
    <li>
        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?cmd=versions" <?php echo ($cmd == 'versions')? 'class="current"': ''; ?>>
        <?php echo get_lang('Software versions'); ?>
        </a>
    </li>
    <li>
        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?cmd=phpinfo" <?php echo ($cmd == 'phpinfo')? 'class="current"': ''; ?>>
        <?php echo get_lang('PHP configuration'); ?>
        </a>
    </li>
    <li>
        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?cmd=secinfo" <?php echo ($cmd == 'secinfo')? 'class="current"': ''; ?>>
        <?php echo get_lang('PHP security information'); ?>
        </a>
    </li>
    <li>
        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?cmd=extensions" <?php echo ($cmd == 'extensions')? 'class="current"': ''; ?>>
        <?php echo get_lang('Loaded extensions'); ?>
        </a>
    </li>
    <li>
        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?cmd=claroconf" <?php echo ($cmd == 'claroconf')? 'class="current"': ''; ?>>
        <?php echo get_lang('Claroline configuration'); ?>
        </a>
    </li>
</ul>

<div class="phpInfoContents">
<?php

    if( $cmd == 'extensions' )
    {
        $extensions = @get_loaded_extensions();
        echo count($extensions) . ' ' . get_lang('Loaded extensions') . '<br /><br />';
        @sort($extensions);

        foreach($extensions as $extension)
        {
            echo $extension.' &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?cmd=extensions&amp;ext='.$extension.'" >'.get_lang('Function list').'</a><br />'."\n";
            if( $extension == $ext )
            {
                $functions = @get_extension_funcs($ext);
                @sort($functions);
                if( is_array($functions) )
                {
                    echo '<ol>';
                    foreach($functions as $function)
                    {
                        print '<li>' . $function . '</li>';
                    }
                    echo '</ol>';
                }
                else
                {
                    echo get_lang('No function in this extension') . '<br />';
                }
            }
        }
    }
    elseif( $cmd == 'phpinfo' )
    {
        echo '<div class="center">';
        echo phpinfoNoHtml();
        echo '</div>';
    }
    elseif( $cmd == 'secinfo' )
    {
        require_once('./lib/PhpSecInfo.lib.php');
        phpsecinfo();

    }
    elseif( $cmd == 'claroconf' )
    {
        echo '<div style="background-color: #dfdfff;">';
        highlight_file(claro_get_conf_repository() . 'claro_main.conf.php');
        echo '<hr /></div>';
    }
    else // versions
    {
        ?>
        <table class="claroTable">
            <thead>
                <tr class="headerX">
                    <th scope="col">Software</th>
                    <th scope="col">Version</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Claroline Install/Last Major Upgrade Version</th>
                    <td><?php echo $clarolineVersion ;?></td>
                </tr>
                <tr>
                    <th scope="row">Claroline Current Version</th>
                    <td><?php echo $new_version ;?></td>
                </tr>
                <tr>
                    <th scope="row">PHP</th>
                    <td><?php echo phpversion(); ?></td>
                </tr>
                <tr>
                    <th scope="row">MySQL</th>
                    <td><?php echo mysql_get_server_info();?></td>
                </tr>
                <tr>
                    <th scope="row">WebServer</th>
                    <td><?php echo $_SERVER['SERVER_SOFTWARE'] ;?></td>
                </tr>
            </tbody>
        </table>
        <?php
    }
}
else // is not allowed
{
    echo get_lang('No way');
}

?>
</div>

<?php
include get_path('incRepositorySys') . '/claro_init_footer.inc.php';
?>
