<?php // $Id$
if ( ! defined('CLARO_INCLUDE_ALLOWED') ) die('---');
/*--protectAdminIndex--*/
if (    ("apache" ==  strtolower(substr($_SERVER['SERVER_SOFTWARE'],0,6)))
        && ($_SERVER['PHP_AUTH_USER']=="" )
        && ($_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR'] )
    )
{
    session_unregister("is_admin");
    echo "This  directory  must be protected with an .htaccess file to  works";
    echo "
            <br />
            if you wan't  unsecure/unprotect the admin  remove<br />

            <B>".__FILE__." </B> on server
            ";
    die ("");
}
/*--protectAdminIndex--*/

?>