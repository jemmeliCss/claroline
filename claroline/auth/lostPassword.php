<?php // $Id$
/**
 * CLAROLINE 
 *
 * This script allows users to retrieve the password of their profile(s) 
 * on the basis of their e-mail address. The password is send via email 
 * to the user.
 *
 * Special case : If the password are encrypted in the database, we have 
 * to generate a new one.
 *
 * @version 1.7 $Revision$
 *
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @package CLAUTH
 *
 * @author Claro Team <cvs@claroline.net>
 */

require '../inc/claro_init_global.inc.php';

$nameTools = $langLostPassword;

// DB tables definition
$tbl_mdb_names = claro_sql_get_main_tbl();
$tbl_user      = $tbl_mdb_names['user'];

// library for authentification and mail
include_once($includePath . '/lib/auth.lib.inc.php');
include_once($includePath . '/lib/claro_mail.lib.inc.php');

// Initialise variables

$passwordFound = FALSE;
$msg = '';

// Get the forgotten email from the form

if ( isset ($_REQUEST['Femail']) ) $Femail = strtolower(trim($_REQUEST['Femail']));
else                               $Femail = '';

// Main section

if ( isset($_REQUEST['searchPassword']) && !empty($Femail) )
{
    // search user with this email

    $sql = "SELECT  `user_id`   `uid`       ,
                    `nom`       `lastName`  ,
                    `prenom`    `firstName` ,
                    `username`  `loginName` ,
                    `password`              ,
                    `email`                 ,
                    `authSource`            ,
                    `creatorId`
             FROM `" . $tbl_user . "`
             WHERE LOWER(email) LIKE '" . addslashes($Femail) . "'
                   AND   `email` != '' ";

    $user = claro_sql_query_fetch_all($sql);

    $extAuthPasswordCount = 0;

    if ( count($user) > 0 )
    {
        for ($i = 0, $j = count($user); $i < $j; $i++)
        {
            if ( in_array(strtolower($user[$i]['authSource']), 
                          array('claroline', 'clarocrypt')))
            {
                if ($userPasswordCrypted)
                {
                    /*
                     * If password are crypted, we can not send them as such.
                     * We have to generate new ones.
                     */

                    $user[$i]['password'] = generate_passwd();
                    
                    // UPDATE THE DB WITH THE NEW GENERATED PASSWORD

                    $sql = 'UPDATE `'.$tbl_user.'`
                            SET   `password` = "'. addslashes(md5($user[$i]['password'])) .'"
                             WHERE `user_id` = "'.$user[$i]['uid'].'"';

                    $result = claro_sql_query($sql)
                              or die('<p align="center">Unable to record new generated password !</p>');
                }
            }
            else
            {
                unset($user[$i]); // remove 
                $extAuthPasswordCount ++;
            }
        }

        // recount if there are still password found
        if (count($user) > 0) $passwordFound = true;

        /*
         * Prepare the email message wich has to be send to the user
         */

        // mail subject
        $emailSubject = $langLoginRequest . ' ' . $siteName;


        // mail body
        foreach($user as $thisUser)
        {
            $userAccountList [] = 
                $thisUser['firstName'].' ' . $thisUser['lastName']  . "\r\n\r\n"
                ."\t" . $langUserName . ' : ' . $thisUser['loginName'] . "\r\n"
                ."\t" . $langPassword . ' : ' . $thisUser['password']  . " \r\n";
        }

        if ($userAccountList)
        {
            $userAccountList = implode ("\r\n\r\n", $userAccountList);
        }

        $emailBody = $emailSubject."\r\n"
                    .$rootWeb."\r\n"
                    .$langYourAccountParam."\r\n\r\n"
                    .$userAccountList;


            // send message
            $emailTo = $user[0]['uid'];

            if( claro_mail_user($emailTo, $emailBody, $emailSubject) )
            {
                $msg = $langPasswordHasBeenEmailed.$Femail;
            }
            else
            {
                $msg = $langEmailNotSent
                .   '<a href="mailto:'.$administrator_email.'?BODY='.$Femail.'">'
                .   $langPlatformAdministrator
                .   '</a>';
            }
    }
    else
    {
        $msg = $langEmailAddressNotFound;
    }

    if ($extAuthPasswordCount > 0 )
    {
    	if ( count ($user) > 0 )
    	{
        	$msg .= '<p>'
                 .  'Passwords of some of your user account(s) are recorded an in external 
                    authentication system outside the platform.
                    <br />For more information take contact with the platform administrator.'
                 .  '</p>';
    	}
        else
        {
            $msg .= '<p>'
                 . 'Your password(s) is (are) recorded in an external authentication 
                   system outside the platform. 
                   <br />For more information take contact with the platform administrator.'
                 . '</p>';
        }
    }
}
else
{
    $msg = '<p>'.$langEnterMail.'</p>';
}


////////////////////////////////////////////////////
// display section

include($includePath . '/claro_init_header.inc.php');

// display title

echo claro_disp_tool_title($nameTools);

// display message box

if ( ! $passwordFound )
{ 
    $msg .= '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">'
         .  '<input type="hidden" name="searchPassword" value="1">'
         .  '<label for="Femail">' . $langEmail . ' : </label>'
         .  '<br />'
         .  '<input type="text" name="Femail" id="Femail" size="50" maxlength="100" value="'. htmlspecialchars($Femail).'">'
         .  '<br /><br />'
         .  '<input type="submit" name="retrieve" value="' . $langOk . '"> '
         .  claro_disp_button('../../index.php', $langCancel)
         .  '</form>';
}

if ( ! empty($msg) ) echo claro_disp_message_box($msg);

// display form

include($includePath . '/claro_init_footer.inc.php');
?>
