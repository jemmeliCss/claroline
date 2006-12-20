<?php // $Id$
if ( count( get_included_files() ) == 1 ) die( '---' );
/**
 * CLAROLINE
 *
 * User lib contains function to manage users on the platform
 * @version 1.8 $Revision$
 * @copyright 2001-2006 Universite catholique de Louvain (UCL)
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 * @package CLUSR
 * @author Claro Team <cvs@claroline.net>
 * @author Christophe Gesch� <moosh@claroline.net>
 * @author Mathieu Laurent <laurent@cerdecam.be>
 * @author Hugues Peeters <hugues.peeters@advalvas.be>
 */

require_once(dirname(__FILE__) . '/form.lib.php');

/**
 * Initialise user data
 * @return  array with user data
 * @author Mathieu Laurent <laurent@cerdecam.be>
 */

function user_initialise()
{
    return array(
        'lastname'        => '',
        'firstname'       => '',
        'officialCode'    => '',
        'officialEmail'   => '',
        'username'        => '',
        'password'        => '',
        'password_conf'   => '',
        'isCourseCreator' => '',
        'language'        => '',
        'email'           => '',
        'phone'           => '',
        'picture'         => '',
    );
}

/**
 * Get common user data on the platform
 * @param integer $userId id of user to fetch properties
 *
 * @return  array( `user_id`, `lastname`, `firstname`, `username`, `email`,
 *           `picture`, `officialCode`, `phone`, `isCourseCreator` ) with user data
 * @author Mathieu Laurent <laurent@cerdecam.be>
 */

function user_get_properties($userId)
{
    $tbl = claro_sql_get_main_tbl();

    $sql = "SELECT                 user_id,
                    nom         AS lastname,
                    prenom      AS firstname,
                                   username,
                                   email,
                                   language,
                    authSource  AS authsource,
                    pictureUri  AS picture,
                                   officialCode,
                                   officialEmail,
                    phoneNumber AS phone,
                                   isCourseCreator,
                                   isPlatformAdmin
            FROM   `" . $tbl['user'] . "`
            WHERE  `user_id` = " . (int) $userId;

    $result = claro_sql_query_get_single_row($sql);

    if ( $result ) return $result;
    else           return claro_failure::set_failure('user_not_found');
}

/**
 * Add a new user
 *
 * @param $settingList array to fill the form
 * @param $creatorId id of account creator
 *                  (null means created by owner)
 *                  default null
 *
 * @author Mathieu Laurent <laurent@cerdecam.be>
 */

function user_create($settingList, $creatorId = null)
{
    $requiredSettingList = array('lastname', 'firstname', 'username',
    'password', 'language', 'email', 'officialCode', 'phone', 'isCourseCreator');

    // Set non compulsory fields

    if (!isset($settingList['language']))            $settingList['language'] = '';
    if (!isset($settingList['phone']))               $settingList['phone'] = '';
    if (!isset($settingList['isCourseCreator']))     $settingList['isCourseCreator'] = false;
    if (!isset($settingList['officialEmail']))       $settingList['officialEmail'] = false;

    // Verify required fields
    foreach($requiredSettingList as $thisRequiredSetting)
    {
        if ( array_key_exists( $thisRequiredSetting, $settingList ) ) continue;
        else return trigger_error('MISSING_DATA : ',E_USER_ERROR);
    }

    // Check if the username is available
    if ( ! is_username_available($settingList['username']) )
    {
        return false ;
    }

    $password = get_conf('userPasswordCrypted')
        ? md5($settingList['password'])
        : $settingList['password']
        ;

    $tbl = claro_sql_get_main_tbl();

    $sql = "INSERT INTO `" . $tbl['user'] . "`
            SET nom             = '". addslashes($settingList['lastname'     ]) ."',
                prenom          = '". addslashes($settingList['firstname'    ]) ."',
                username        = '". addslashes($settingList['username'     ]) ."',
                language        = '". addslashes($settingList['language'     ]) ."',
                email           = '". addslashes($settingList['email'        ]) ."',
                officialCode    = '". addslashes($settingList['officialCode' ]) ."',
                officialEmail   = '". addslashes($settingList['officialEmail']) ."',
                phoneNumber     = '". addslashes($settingList['phone'        ]) ."',
                password        = '". addslashes($password) . "',
                isCourseCreator = " . (int) $settingList['isCourseCreator'] . ",
                isPlatformAdmin = 0,
                creatorId    = " . ($creatorId > 0 ? (int) $creatorId : 'NULL');
    $adminId = claro_sql_query_insert_id($sql);
    if (false !== $adminId) return $adminId;
    else return claro_failure::set_failure('Cant create user|' . mysql_error() . '|');
}

/**
 * Update user data
 * @param $user_id integer
 * @param $propertyList array
 * @author Mathieu Laurent <laurent@cerdecam.be>
 */

function user_set_properties($userId, $propertyList)
{
    $tbl = claro_sql_get_main_tbl();

    // SPECIAL CASE

    if ( array_key_exists('isCourseCreator', $propertyList) )
    {
        $propertyList['isCourseCreator'] = $propertyList['isCourseCreator'] ? 1 :0;
    }

    if ( array_key_exists('password', $propertyList) && get_conf('userPasswordCrypted'))
    {
        $propertyList['password'] = md5($propertyList['password']);
    }

    if ( array_key_exists('isPlatformAdmin', $propertyList) )
    {
        $propertyList['isPlatformAdmin'] = $propertyList['isPlatformAdmin'] ? 1 :0;
    }


    // BUILD QUERY

    $sqlColumnList = array('nom'             => 'lastname',
                           'prenom'          => 'firstname',
                           'username'        => 'username',
                           'phoneNumber'     => 'phone',
                           'email'           => 'email',
                           'officialCode'    => 'officialCode',
                           'isCourseCreator' => 'isCourseCreator',
                           'password'        => 'password',
                           'language'        => 'language',
                           'pictureUri'      => 'picture',
                           'isPlatformAdmin' => 'isPlatformAdmin');

    $setList = array();

    foreach($sqlColumnList as $columnName => $propertyName)
    {
        if ( array_key_exists($propertyName, $propertyList) )
        {
            $setList[] = $columnName . "= '"
            . addslashes($propertyList[$propertyName]). "'";
        }
    }

    if ( count($setList) > 0)
    {
        $sql = "UPDATE  `" . $tbl['user'] . "`
                SET ". implode(', ', $setList) . "
                WHERE user_id  = " . (int) $userId ;
    }

    if ( claro_sql_query_affected_rows($sql) > 0 ) return true;
    else                                           return false;
}

/**
 * Delete user form claroline platform
 * @author Mathieu Laurent <laurent@cerdecam.be>
 * @param int $userId
 * @return boolean 'true' if it succeeds, 'false' otherwise
 */

function user_delete($userId)
{
    require_once get_path('incRepositorySys') . '/lib/course_user.lib.php';

    if ( $GLOBALS['_uid'] == $userId ) // user cannot remove himself of the platform
    {
        return claro_failure::set_failure('user_cannot_remove_himself');
    }

    // main tables name

    $tbl = claro_sql_get_main_tbl();

    // get the list of course code where the user is subscribed
    $sql = "SELECT c.code                          AS code
            FROM `" . $tbl['rel_course_user'] . "` AS cu,
                 `" . $tbl['course'] . "`          AS c
            WHERE cu.code_cours = c.code
            AND  cu.user_id    = " . $userId;

    $courseList = claro_sql_query_fetch_all_cols($sql);

    $log = array();
    if ( user_remove_from_course($userId, $courseList['code'], true, true, true) == false ) return false;
    else
    {
        foreach ($courseList['code'] as $k=>$courseCode) $log['course_' . $k] = $courseCode;
        event_default( 'UNROL_USER_COURS' , array_merge( array ('USER' => $userId ) ,$log));
    }
    $sqlList = array(

    "DELETE FROM `" . $tbl['user']            . "` WHERE user_id         = " . (int) $userId ,
    "DELETE FROM `" . $tbl['track_e_default'] . "` WHERE default_user_id = " . (int) $userId ,
    "DELETE FROM `" . $tbl['track_e_login']   . "` WHERE login_user_id   = " . (int) $userId ,
    "DELETE FROM `" . $tbl['rel_class_user']  . "` WHERE user_id         = " . (int) $userId ,
    "DELETE FROM `" . $tbl['sso']             . "` WHERE user_id         = " . (int) $userId ,

    // Change creatorId to NULL
    "UPDATE `" . $tbl['user'] . "` SET `creatorId` = NULL WHERE `creatorId` = " . (int) $userId

    );
    event_default( 'USER_DELETED' , array_merge( array ('USER' => $userId ) ));

    foreach($sqlList as $thisSql)
    {
        if ( claro_sql_query($thisSql) == false ) return false;
        else                                      continue;
    }

    return true;
}

/**
 * @return list of users wich have admin status
 * @author Christophe Gesch� <Moosh@claroline.net>
 *
 */

function claro_get_uid_of_platform_admin()
{
    $tbl = claro_sql_get_main_tbl();

    $sql = "SELECT user_id AS id
            FROM `" . $tbl['user'] . "`
            WHERE isPlatformAdmin = 1 ";

    $resultList = claro_sql_query_fetch_all_cols($sql);

    return $resultList['id'];
}

/**
 * @return list of users wich have status to receipt REQUESTS
 * @author Christophe Gesch� <Moosh@claroline.net>
 *
 */

function claro_get_uid_of_request_admin()
{
    $tbl = claro_sql_get_main_tbl();

    $sql = "SELECT user_id AS id
            FROM `" . $tbl['user'] . "` AS u
            INNER JOIN `" . $tbl['user_property'] . "` AS up
            ON up.userId = u.user_id
            WHERE u.isPlatformAdmin = 1
              AND up.propertyId = 'adminContactForRequest'
              AND up.propertyValue = 1
              AND up.scope = 'contacts'
              ";
    $resultList = claro_sql_query_fetch_all_cols($sql);

    return $resultList['id'];
}


/**
 * @return list of users wich have status to receive system notification
 * @author Christophe Gesch� <Moosh@claroline.net>
 *
 */

function claro_get_uid_of_platform_contact()
{
    $tbl = claro_sql_get_main_tbl();

    $sql = "SELECT user_id AS id
            FROM `" . $tbl['user'] . "` AS u
            INNER JOIN `" . $tbl['user_property'] . "` AS up
            ON up.userId = u.user_id
            WHERE up.propertyId = 'adminContactForContactPage'
              #AND u.isPlatformAdmin = 1
              AND up.propertyValue = 1
              AND up.scope = 'contacts'
              ";
    $resutlList = claro_sql_query_fetch_all_cols($sql);

    return $resutlList['id'];
}


/**
 * @return list of users wich have status to receive system notification
 * @author Christophe Gesch� <Moosh@claroline.net>
 *
 */

function claro_get_uid_of_system_notification_recipient()
{
    $tbl = claro_sql_get_main_tbl();

    $sql = "SELECT user_id AS id
            FROM `" . $tbl['user'] . "` AS u
            INNER JOIN `" . $tbl['user_property'] . "` AS up
            ON up.userId = u.user_id
            WHERE up.propertyId = 'adminContactForSystemNotification'
              #AND u.isPlatformAdmin = 1
              AND up.propertyValue = 1
              AND up.scope = 'contacts'
              ";
    $resultList = claro_sql_query_fetch_all_cols($sql);

    return $resultList['id'];
}

function claro_set_uid_recipient_of_system_notification($user_id,$state=true)
{
   $tbl = claro_sql_get_main_tbl();

    $sql = "REPLACE INTO `" . $tbl['user_property'] . "`
            SET userId = " . (int) $user_id . ",
                propertyId = 'adminContactForSystemNotification',
                propertyValue = " . (int) $state . ",
                scope = 'contacts'
              ";

    $result = claro_sql_query_affected_rows($sql);

    return $result;

}

function claro_set_uid_of_platform_contact($user_id,$state=true)
{
   $tbl = claro_sql_get_main_tbl();

    $sql = "REPLACE INTO `" . $tbl['user_property'] . "`
            SET userId = " . (int) $user_id . ",
                propertyId = 'adminContactForContactPage',
                propertyValue = " . (int) $state . ",
                scope = 'contacts'
              ";

    $result = claro_sql_query_affected_rows($sql);

    return $result;

}

function claro_set_uid_recipient_of_request_admin($user_id,$state=true)
{
   $tbl = claro_sql_get_main_tbl();

    $sql = "REPLACE INTO `" . $tbl['user_property'] . "`
            SET userId = " . (int) $user_id . ",
                propertyId = 'adminContactForRequest',
                propertyValue = " . (int) $state . ",
                scope = 'contacts'
              ";
    $result = claro_sql_query_affected_rows($sql);

    return $result;

}


/**
 * Return true, if user is admin on the platform
 * @param $userId
 * @return boolean
 * @author Hugues Peeters <hugues.peeters@advalvas.be>
 */

function user_is_admin($userId)
{
    $userPropertyList = user_get_properties($userId);
    return (bool) $userPropertyList['isPlatformAdmin'];
}

/**
 * Set or unset platform administrator status to a specific user
 *
 * @author Hugues Peeters <hugues.peeters@advalvas.be>
 * @param  boolean $status
 * @param  int     $userId
 * @return boolean 'true' if it succeeds, 'false' otherwise
 */

function user_set_platform_admin($status, $userId)
{
    return user_set_properties($userId, array('isPlatformAdmin' => (bool) $status) );
}

/**
 * Send registration succeded email to user
 * @author Mathieu Laurent <laurent@cerdecam.be>
 *
 * @param integer $userId
 * @param mixed $data array of user data or null to keep data following $userId param.
 * @return boolean
 */

function user_send_registration_mail ($userId, $data)
{
    if ( ! empty($data['email']) )
    {
        // email subjet

        $emailSubject  = '[' . get_conf('siteName') . '] ' . get_lang('Your registration') ;

        // email body

        $emailBody = get_block('blockAccountCreationNotification',
        array(
        '%firstname'=> $data['firstname'],
        '%lastname' => $data['lastname'],
        '%username' => $data['username'],
        '%password' => $data['password'],
        '%siteName'=> get_conf('siteName'),
        '%rootWeb' => get_path('rootWeb'),
        '%administratorName' => get_conf('administrator_name'),
        '%administratorPhone'=> get_conf('administrator_phone'),
        '%administratorEmail'=> get_conf('administrator_email')
        )
        );

        if ( claro_mail_user($userId, $emailBody, $emailSubject) ) return true;
        else                                                       return false;
    }
    else
    {
        return false;
    }

}

/**
 * Current logged user send a mail to ask course creator status
 * @param string explanation message
 * @author Mathieu Laurent <laurent@cerdecam.be>
 */

function profile_send_request_course_creator_status($explanation)
{
    global $_user;

    $mailToUidList = claro_get_uid_of_request_admin();
    if(empty($mailToUidList)) $mailToUidList = claro_get_uid_of_platform_admin();

    $requestMessage_Title =
    get_block('%sitename Request - Course creator status for %firstname %lastname',
    array('%sitename'  => '['.get_conf('siteName').']',
    '%firstname' => $_user['firstName'],
    '%lastname' => $_user['lastName'] ) );

    $requestMessage_Content =
    get_block('blockRequestCourseManagerStatusMail',
    array( '%time'      => claro_disp_localised_date(get_locale('dateFormatLong')),
    '%user_id'   => claro_get_current_user_id(),
    '%firstname' => $_user['firstName'],
    '%lastname'  => $_user['lastName'],
    '%email'     => $_user['mail'],
    '%comment'   => $explanation,
    '%url'       => get_path('rootWeb') . 'claroline/admin/adminprofile.php?uidToEdit=' . claro_get_current_user_id()
    )
    );

    claro_mail_user($mailToUidList, $requestMessage_Content,
    $requestMessage_Title, $_user['mail'], $_user['firstName'] . ' ' . $_user['lastName']);

    return true;
}

/**
 * Current logged user send a mail to ask course creator status
 * @param string explanation message
 * @author Mathieu Laurent <laurent@cerdecam.be>
 */

function profile_send_request_revoquation($explanation,$login,$password)
{
    if (empty($explanation)) return claro_failure::set_failure('EXPLANATION_EMPTY');

    $_user = claro_get_current_user_data();

    $mailToUidList = claro_get_uid_of_request_admin();
    if(empty($mailToUidList)) $mailToUidList = claro_get_uid_of_platform_admin();

    $requestMessage_Title =
    get_block('%sitename Request - Revocation of %firstname %lastname',
    array('%sitename'  => '['.get_conf('siteName').']',
    '%firstname' => $_user['firstName'],
    '%lastname' => $_user['lastName'] ) );

    $requestMessage_Content =
    get_block('blockRequestUserRevoquationMail',
    array('%time'      => claro_disp_localised_date(get_locale('dateFormatLong')),
    '%user_id'   => claro_get_current_user_id(),
    '%firstname' => $_user['firstName'],
    '%lastname'  => $_user['lastName'],
    '%email'     => $_user['mail'],
    '%login'     => $login,
    '%password'  => $password,
    '%comment'   => nl2br($explanation),
    '%url'       => get_path('rootWeb') . 'claroline/admin/adminprofile.php?uidToEdit=' . claro_get_current_user_id()
    )
    );

    claro_mail_user( $mailToUidList,
                     $requestMessage_Content,
                     $requestMessage_Title,
                     $_user['mail'],
                     $_user['firstName'] . ' ' . $_user['lastName']);

    return true;
}


/**
 * Generates randomly password
 * @author Damien Seguy
 * @return string : the new password
 */

function generate_passwd($nb=8)
{

    $lettre = array();

    $lettre[0] = array( 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i',
    'j', 'k', 'l', 'm', 'o', 'n', 'p', 'q', 'r',
    's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A',
    'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
    'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'D',
    'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '9',
    '0', '6', '5', '1', '3');

    $lettre[1] =  array('a', 'e', 'i', 'o', 'u', 'y', 'A', 'E',
    'I', 'O', 'U', 'Y' , '1', '3', '0' );

    $lettre[-1] = array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k',
    'l', 'm', 'n', 'p', 'q', 'r', 's', 't',
    'v', 'w', 'x', 'z', 'B', 'C', 'D', 'F',
    'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P',
    'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Z',
    '5', '6', '9');

    $retour   = '';
    $prec     = 1;
    $precprec = -1;

    srand((double)microtime() * 20001107);

    while(strlen($retour) < $nb)
    {
        // To generate the password string we follow these rules : (1) If two
        // letters are consonnance (vowel), the following one have to be a vowel
        // (consonnace) - (2) If letters are from different type, we choose a
        // letter from the alphabet.

        $type     = ($precprec + $prec) / 2;
        $r        = $lettre[$type][array_rand($lettre[$type], 1)];
        $retour  .= $r;
        $precprec = $prec;
        $prec     = in_array($r, $lettre[-1]) - in_array($r, $lettre[1]);

    }
    return $retour;
}

/**
 * Check an email
 * @version 1.0
 * @param  string $email email to check
 *
 * @return boolean state of validity.
 * @author Christophe Gesche <moosh@claroline.net>
 */

function is_well_formed_email_address($address)
{
    $regexp = '^[0-9a-z_\.-]+@(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-z][0-9a-z-]*[0-9a-z]\.)+[a-z]{2,4})$';

    //  $regexp = '^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$';
    return eregi($regexp, $address);
}

/**
 * validate form registration
 * @author Hugues Peeters <hugues.peeters@claroline.net>
 * @param array $data from the form
 * @return array with error messages
 */

function user_validate_form_registration($data)
{
    return user_validate_form('registration', $data);
}

/**
 * validate form profile
 * @author Hugues Peeters <hugues.peeters@claroline.net>
 * @param array $data to fill the form
 * @param int $userId id of the user account currently edited
 * @return array with error messages
 */

function user_validate_form_profile($data, $userId)
{
    return user_validate_form('profile', $data, $userId);
}

/**
 * validate user form
 * @author Hugues Peeters <hugues.peeters@claroline.net>
 * @param string $mode 'registration' or 'profile'
 * @param array $data to fill the form
 * @param int $userId (optional) id of the user account currently edited
 * @return array with error messages
 */
function user_validate_form($formMode, $data, $userId = null)
{
    require_once dirname(__FILE__) .'/datavalidator.lib.php';

    $validator = new DataValidator();
    $validator->setDataList($data);

    $validator->addRule('lastname' , get_lang('You left some required fields empty'), 'required');
    $validator->addRule('firstname', get_lang('You left some required fields empty'), 'required');
    $validator->addRule('username' , get_lang('You left some required fields empty'), 'required');
    $validator->addRule('username' , get_lang('Username is too long (maximum 20 characters)'), 'maxlenght',20);

    if ( ! get_conf('userMailCanBeEmpty') )
    {
        $validator->addRule('email', get_lang('You left some required fields empty'), 'required');
    }

    if ( ! get_conf('userOfficialCodeCanBeEmpty') )
    {
        $validator->addRule('officialCode', get_lang('You left some required fields empty'), 'required');
    }

    if(array_key_exists('password',$data) || array_key_exists('password_conf',$data))
    {
        if ( get_conf('SECURE_PASSWORD_REQUIRED') )
        {
            $validator->addRule('password',
            get_lang( 'This password is too simple. Use a password like this <code>%passProposed</code>', array('%passProposed'=> generate_passwd() )),
            'is_password_secure_enough',
            array(array( $data['username'] ,
            $data['officialCode'] ,
            $data['lastname'] ,
            $data['firstname'] ,
            $data['email'] )
            )
            );
        }

        $validator->addRule('password', get_lang('You typed two different passwords'), 'compare', $data['password_conf']);
    }

    $validator->addRule('email'  , get_lang('The email address is not valid'), 'email');

    if ( 'registration' == $formMode)
    {
        $validator->addRule('password_conf', get_lang('You left some required fields empty'), 'required');
        $validator->addRule('officialCode' , get_lang('This official code is already used by another user.'), 'is_official_code_available');
        $validator->addRule('username'     , get_lang('This user name is already taken'), 'is_username_available');
        $validator->addRule('password'  , get_lang('You left some required fields empty'), 'required');
    }
    else // profile mode
    {
        /*
         * FIX for the empty password issue
         */
        if ( !empty( $data['password'] ) || !empty( $data['password_conf'] ) )
        {
            $validator->addRule('password'  , get_lang('You left some required fields empty'), 'required');
        }

        $validator->addRule('officialCode' , get_lang('This official code is already used by another user.'), 'is_official_code_available', $userId);
        $validator->addRule('username'     , get_lang('This user name is already taken'), 'is_username_available', $userId);
    }

    if ( $validator->validate() ) return array();
    else return array_unique($validator->getErrorList());
}

/**
 * Check if the password chosen by the user is not too much easy to find
 *
 * @author Hugues Peeters <hugues.peeters@advalvas.be>
 *
 * @param string requested password
 * @param array list of other values of the form we wnt to check the password
 * @return boolean true if not too much easy to find
 *
 */

function is_password_secure_enough($requestedPassword, $forbiddenValueList)
{
    foreach ( $forbiddenValueList as $thisValue )
    {
        if ( strtoupper($requestedPassword) == strtoupper($thisValue) )
        {
            return claro_failure::set_failure('ERROR_CODE_too_easy');
        }

        if ( !empty($requestedPassword) && !empty($thisValue)
        && ( false !== stristr($requestedPassword,$thisValue)
        ||   false !== stristr($thisValue,$requestedPassword) ))
        {
            return claro_failure::set_failure('ERROR_CODE_too_easy');
        }

        if ( (function_exists('soundex')) && soundex($requestedPassword) == soundex($thisValue) )
        {
            return claro_failure::set_failure('ERROR_CODE_too_easy');
        }


    }

    return true;
}

/**
 * Check if the username is available
 * @param string username
 * @param integer user_id
 * @return boolean
 */

function is_username_available($username, $userId = null)
{
    $tbl = claro_sql_get_main_tbl();

    $sql = "SELECT COUNT(username)
            FROM `" . $tbl['user'] . "`
            WHERE username='" . addslashes($username) . "' ";

    if ( ! is_null($userId) ) $sql .= " AND user_id <> "  . (int) $userId ;

    if ( claro_sql_query_get_single_value($sql) == 0 ) return true;
    else                                               return false;
}

/**
 * Check if the official code is available
 *
 * @param string official code
 * @param integer user_id
 *
 * @return boolean
 */

function is_official_code_available($official_code, $userId=null)
{
    $tbl = claro_sql_get_main_tbl();

    $sql = "SELECT COUNT(officialCode)
            FROM `" . $tbl['user'] . "`
            WHERE officialCode = '" . addslashes($official_code) . "' ";

    if ( ! is_null($userId) ) $sql .= " AND user_id <> "  . (int) $userId ;

    if ( claro_sql_query_get_single_value($sql) == 0 ) return true;
    else                                               return false;
}

/**
 * Display user form registration
 * @author Mathieu Laurent <laurent@cerdecam.be>
 * @param $data array to fill the form
 */

function user_html_form_registration($data)
{
   return user_html_form($data,'registration');
}

/**
 * Display user form profile
 * @author Mathieu Laurent <laurent@cerdecam.be>
 * @param $data array to fill the form
 */

function user_html_form_profile($data)
{
    return user_html_form($data,'profile');

}

/**
 * Display user form registration
 *
 * @param $data array to fill the form
 *
 * @author Mathieu Laurent <laurent@cerdecam.be>
 *
 */

function user_html_form_add_new_user($data)
{
    return user_html_form($data,'add_new_user');
}

/**
 * Display user admin form registration
 * @author Mathieu Laurent <laurent@cerdecam.be>
 * @param $data array to fill the form
 */

function user_html_form_admin_add_new_user($data)
{
    return user_html_form($data,'admin_add_new_user');
}

/**
 * Display user admin form registration
 * @author Mathieu Laurent <laurent@cerdecam.be>
 * @param $data array to fill the form
 */

function user_html_form_admin_user_profile($data)
{
    return user_html_form($data,'admin_user_profile');
}

/**
 * Display form to edit or add user to the platform
 * @author Mathieu Laurent <laurent@cerdecam.be>
 * @param $data array to fill the form
 */

function user_html_form($data, $form_type='registration')
{
    if ( $form_type == 'profile' )
    {
        $profile_editable = get_conf('profile_editable');
    }
    else
    {
        $profile_editable = array('name','official_code','login','password','email','phone','language');
    }

    // display registration form
    $html = '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" enctype="multipart/form-data" >' . "\n"
    .       claro_form_relay_context()

    // hidden fields
    .       form_input_hidden('cmd', 'registration')
    .       form_input_hidden('claroFormId', uniqid('') )
    ;

    if ( array_key_exists('confirmUserCreate', $data) )
    {
        $html .= form_input_hidden('confirmUserCreate', $data['confirmUserCreate'] ? 1 : 0);

    }

    // table begin
    $html .= '<table class="claroRecord" cellpadding="3" cellspacing="0" border="0">' . "\n";

    // user id
    if ( 'admin_user_profile' == $form_type )
    {
        $html .= form_input_hidden('uidToEdit', $data['user_id']);
        $html .= form_row( get_lang('User Id') . '&nbsp;: ', $data['user_id']);

    }

    if ( in_array('name',$profile_editable) )
    {
        $html .= form_input_text('lastname', $data['lastname'], get_lang('Last name'), true);
    }
    else
    {
        $html .= form_readonly_text('lastname', $data['lastname'], get_lang('Last name'));
    }


    if ( in_array('name',$profile_editable) )
    {
        $html .= form_input_text('firstname', $data['firstname'], get_lang('First name'), true);
    }
    else
    {
        $html .= form_readonly_text('firstname', $data['firstname'], get_lang('First name'));
    }

    // OFFICIAL CODE
    if ( get_conf('ask_for_official_code') )
    {
        if ( in_array('official_code',$profile_editable) )
        {
            $html .= form_input_text('officialCode', $data['officialCode'],
            get_lang('Administrative code'),
            get_conf('userOfficialCodeCanBeEmpty') ? false : true );
        }
        else
        {
            $html .= form_readonly_text('officialCode', $data['officialCode'],get_lang('Administrative code'));
        }
    }

    // Display language select box

    $language_select_box = user_display_preferred_language_select_box();

    if ( !empty($language_select_box) )
    {
        $html .= form_row('<label for="language_selector">' . get_lang('Language') . '&nbsp;:</label>',
        $language_select_box );
    }

    if (     isset($data['authsource'])
    && strtolower($form_type) == 'profile'
    && (    strtolower($data['authsource']) != 'claroline'
    && strtolower($data['authsource']) != 'clarocrypt'
    )
    )
    {
        // DISABLE MODIFICATION OF USERNAME AND PASSWORD WITH EXTERNAL AUTENTICATION
        $html .= form_readonly_text('username',htmlspecialchars($data['username']),get_lang('Username'));
    }
    else
    {
        $html .= form_row('&nbsp;', '&nbsp;');

        if ( ( strtolower($form_type) == 'profile' || strtolower($form_type) == 'admin_user_profile' ) && in_array('password',$profile_editable))
        {
            $html .= form_row('&nbsp;',
            '<small>'
            .'(' . get_lang('Enter new password twice to change, leave empty to keep it') . ')'
            .'</small>');

            $required_password = false;
        }
        else
        {
            if ( 'registration' == $form_type )
            {
                $html .= form_row('&nbsp;',
                '<small>'
                . get_lang('Choose now a username and a password for the user account') . '<br />'
                . get_lang('Memorize them, you will use them the next time you will enter to this site.') . '<br />'
                . '</small>');
            }

            $required_password = true;
        }

        if ( $required_password )
        {
            $password_label = form_required_field(get_lang('Password'));
        }
        else
        {
            $password_label = get_lang('Password');
        }

        if ( in_array('login',$profile_editable) )
        {
            $html .= form_input_text( 'username', $data['username'], get_lang('Username'), true);
        }
        else
        {
            $html .= form_readonly_text( 'username', $data['username'], get_lang('Username'));
        }

        if ( in_array('password',$profile_editable) )
        {
            // password
            $html .= form_row('<label for="password">' . $password_label . '&nbsp;:</label>',
            '<input type="password" size="40" id="password" name="password" />');

            // password confirmation
            $html .= form_row('<label for="password_conf">' . $password_label . '&nbsp;:<br/>'
            . ' <small>(' . get_lang('Confirmation') . ')</small></label>',
            '<input type="password" size="40" id="password_conf" name="password_conf" />');
        }

        $html .= form_row('&nbsp;', '&nbsp;');
    }

    // Email
    if ( in_array('email',$profile_editable) )
    {
        $html .= form_input_text('email', $data['email'], get_lang('Email'), get_conf('userMailCanBeEmpty') ? false : true);
    }
    else
    {
        $html .= form_readonly_text('email', $data['email'], get_lang('Email'));
    }

    // Phone
    if ( in_array('phone',$profile_editable) )
    {
        $html .= form_input_text('phone', $data['phone'], get_lang('Phone') );
    }
    else
    {
        $html .= form_readonly_text('phone', $data['phone'], get_lang('Phone'));
    }

    // Group Tutor
    if ( 'add_new_user' == $form_type )
    {
        $html .= form_row(get_lang('Group Tutor') . '&nbsp;: ',

        '<input type="radio" name="tutor" value="1" id="tutorYes" '
        . ($data['tutor']?'checked':'') . ' />'
        . '<label for="tutorYes">' . get_lang('Yes') . '</label>'

        . '<input type="radio" name="tutor" value="0"  id="tutorNo" '
        . (!$data['tutor']?'checked':'') . ' />'
        . '<label for="tutorNo">' . get_lang('No') . '</label>');
    }

    // Course manager of the course
    if ( 'add_new_user' == $form_type )
    {
        $html .= form_row(get_lang('Manager') . '&nbsp;: ',
        '<input type="radio" name="courseAdmin" value="1" id="courseAdminYes" '
        . ($data['courseAdmin'] ? 'checked' : '') . ' />'
        . '<label for="courseAdminYes">' . get_lang('Yes') . '</label>'
        . '<input type="radio" name="courseAdmin" value="0" id="courseAdminNo" '
        . ($data['courseAdmin'] ? '' : 'checked') . ' />'
        . '<label for="courseAdminNo">' . get_lang('No') . '</label>');
    }

    // Course Creator
    if ( ( get_conf('allowSelfRegProf') && 'registration' == $form_type) || 'admin_add_new_user' == $form_type || 'admin_user_profile' == $form_type )
    {
        $html .= form_row( get_lang('Action') .'&nbsp;: ',
        '<input type="radio" name="isCourseCreator" id="follow"'
        .' value="0" '
        . (!$data['isCourseCreator']? ' checked' : '') . ' />'
        . '<label for="follow">' . get_lang('Follow courses') . '</label>'
        . '<br />'
        . '<input type="radio" name="isCourseCreator" id="create"'
        . ' value="1"   '
        . ($data['isCourseCreator']? ' checked'  :'') . ' />'
        . '<label for="create">' . get_lang('Create course') . '</label>');
    }

    // Platform administrator
    if ( 'admin_user_profile' == $form_type)
    {
        $html .= form_row(get_lang('Is platform admin') .'&nbsp;: ',
        '<input type="radio" name="is_admin" value="1" id="admin_form_yes" ' . ($data['is_admin']?'checked':'') . ' />'
        . '<label for="admin_form_yes">' . get_lang('Yes') . '</label>'
        . '<input type="radio" name="is_admin" value="0"  id="admin_form_no" ' . (!$data['is_admin']?'checked':'') . ' />'
        . '<label for="admin_form_no">' . get_lang('No') . '</label>');
    }

    // Submit
    if ( 'registration' == $form_type )
    {
        $html .= form_row( get_lang('Create') . '&nbsp;: ',
        '<input type="submit" value="' . get_lang('Ok') . '" />&nbsp;'
        . claro_html_button(get_conf('urlAppend').'/index.php', get_lang('Cancel')) );
    }
    elseif ( 'admin_add_new_user' == $form_type)
    {
        $html .= form_row( get_lang('Create') . '&nbsp;: ' ,
        '<input type="submit" value="' . get_lang('Ok') . '" />&nbsp;'
        . claro_html_button($_SERVER['HTTP_REFERER'], get_lang('Cancel')) );
    }
    elseif ('add_new_user' == $form_type )
    {
        $html .= form_row( '<label for="applyChange">' . get_lang('Save changes') . ' : </label>'
                         , '<input type="submit" name="applyChange" id="applyChange" value="' . get_lang('Ok') . '" />&nbsp;'
                         . '<input type="submit" name="applySearch" id="applySearch" value="' . get_lang('Search') . '" />&nbsp;'
                         . claro_html_button($_SERVER['HTTP_REFERER'], get_lang('Cancel'))
                         );
    }
    else
    {
        $html .= form_row('<label for="applyChange">' . get_lang('Save changes') . ' : </label>',
        ' <input type="submit" name="applyChange" id="applyChange" value="' . get_lang('Ok') . '" />&nbsp;'
        . claro_html_button($_SERVER['HTTP_REFERER'], get_lang('Cancel')) );
    }

    $html .= form_row('&nbsp;', '<small>' . get_lang('<span class="required">*</span> denotes required field') . '</small>');

    // Personnal course list
    if ( 'admin_user_profile' == $form_type )
    {
        $html .= form_row('&nbsp;',
        '<a href="adminusercourses.php?uidToEdit=' . $data['user_id'] . '">'
        . '<img src="' . get_path('imgRepositoryWeb') . 'course.gif" alt="">' . get_lang('PersonalCourseList')
        . '</a>');
    }

    if (array_key_exists('userExtraInfoList',$data))
    {
        global $extraInfoDefList;
        foreach ($data['userExtraInfoList'] as $userExtraInfoId => $userExtraInfoValue)
        {
            if (array_key_exists($userExtraInfoId,$extraInfoDefList))
            {
                $label = $extraInfoDefList[$userExtraInfoId]['label'];
                $html .= form_row( get_lang($label) . '&nbsp:',$userExtraInfoValue);
            }
        }

        if ( 0 < count($extraInfoDefList))
        $html .= form_row( ''
                         , claro_html_cmd_link( $_SERVER['PHP_SELF'] . '?cmd=editExtraInfo' . claro_url_relay_context('&amp;')
                                              , '<img src="' . get_path('imgRepositoryWeb') . 'edit.gif" border="O" alt="' . get_lang('Modify')
                                              )
                         );
    }

    $html .= '</table>' . "\n"
    .        '</form>' . "\n"
    ;
    return $html;
}

/**
 * @param array $criterionList -
 *        Allowed keys are 'name', 'firstname', 'email', 'officialCode'
 * @param string $courseId (optional)
 *        permit check if user are already enrolled in the concerned cours
 * @param boolean $allCriterion (optional)
 *        define if all submited criterion has to be set.
 * @param boolean $strictCompare (optional)
 *        define if criterion comparison use wildcard or not
 * @return array : existing users who met the criterions
 */

function user_search( $criterionList = array() , $courseId = null, $allCriterion = true, $strictCompare = false )
{
    $validatedCritList = array('lastname' => '', 'firstname'    => '',
    'email' => ''   , 'officialCode' => '');

    foreach($criterionList as $thisCritKey => $thisCritValue)
    {
        if ( array_key_exists($thisCritKey, $validatedCritList ) )
        {
            $validatedCritList[$thisCritKey] = str_replace('%', '\%', $thisCritValue);
        }
        else claro_die('user_search(): WRONG CRITERION KEY !');
    }

    $operator = $allCriterion  ? 'AND' : 'OR';
    $wildcard = $strictCompare ? '' : '%';

    $tbl_mdb_names   = claro_sql_get_main_tbl();
    $tbl_user        = $tbl_mdb_names['user'           ];
    $tbl_course_user = $tbl_mdb_names['rel_course_user'];

    $sql =  "SELECT U.nom           lastname,
                    U.prenom        firstname,
                    U.email         email,
                    U.officialCode  officialCode,
                    U.`user_id` AS  uid
                   ". ($courseId ? ', CU.user_id AS registered' : '') . "
             FROM `" . $tbl_user . "` AS U";

    if ($courseId) $sql .= " LEFT JOIN `" . $tbl_course_user . "` AS CU
                                    ON CU.`user_id`=U.`user_id`
                                   AND CU.`code_cours` = '" . $courseId . "' ";

    $sqlCritList = array();

    if ($validatedCritList['lastname'])
    $sqlCritList[] = " U.nom    LIKE '". addslashes($validatedCritList['lastname'    ])   . $wildcard . "'";
    if ($validatedCritList['firstname'   ])
    $sqlCritList[] = " U.prenom LIKE '". addslashes($validatedCritList['firstname'   ])   . $wildcard . "'";
    if ($validatedCritList['email'])
    $sqlCritList[] = " U.email  LIKE '". addslashes($validatedCritList['email'       ])   . $wildcard . "'";
    if ($validatedCritList['officialCode'])
    $sqlCritList[] = " U.officialCode = '". addslashes($validatedCritList['officialCode']) .$wildcard . "'";

    if ( count($sqlCritList) > 0) $sql .= 'WHERE ' . implode(" $operator ", $sqlCritList);

    $sql .= " ORDER BY nom, prenom ";

    return claro_sql_query_fetch_all($sql);
}

/**
 * Get html select box for a user language preference
 *
 * @return string html
 * @since 1.8
 */
function user_display_preferred_language_select_box()
{
    $language_list = get_language_to_display_list();

    $form = '';

    if ( is_array($language_list) && count($language_list) > 1 )
    {
        // get the the current language
        $user_language = language::current_language();
        // build language selector form
        $form .= claro_html_form_select('language',$language_list,$user_language,array('id'=>'language_selector')) ;
    }

    return $form;
}


/**
 * Extended properties
 * some  info  can  be added for each user without change structure of user table.
 * To do that , add a description
 */
/**
 * Get all properties for a user
 *
 * @param int     $userId
 * @param boolean $force reload data from database.
 *                Use it if data can change between
 *                two call in same script
 * @param boolean $getUndefinedProperties. if false, function return only field where data overwrite the default value (NULL)
 *
 * @return array of properties array (array[]=array(propertyId, propertyValue,scope )
 */
function get_user_property_list($userId, $force = false, $getUndefinedProperties = false)
{
    static $userPropertyList = array();
    if (!array_key_exists($userId,$userPropertyList) || $force)
    {
        $tbl = claro_sql_get_tbl(array('user_property','property_definition'));
        if ($getUndefinedProperties)
        {
        $sql = "SELECT propertyId,
                   propertyValue,
                   scope
            FROM  `" . $tbl['user_property'] . "`
            WHERE userId = " . (int) $userId . "
            ORDER BY propertyId";

        }
        else
        {
        $sql = "SELECT up.propertyId,
                   up.propertyValue,
                   up.scope
            FROM  `" . $tbl['user_property'] . "` up
            INNER JOIN `" . $tbl['property_definition'] . "` pd
            ON up.propertyId = pd.propertyId
            WHERE up.userId = " . (int) $userId . "
            ORDER BY pd.rank, up.propertyId";
        }

        $result = claro_sql_query_fetch_all_rows($sql);
        $propertyList = array();
        foreach ($result as $userInfo) $propertyList[$userInfo['propertyId']] = $userInfo['propertyValue'];
        $userPropertyList[$userId] = $propertyList;
    }
    return $userPropertyList[$userId];
}

/**
 * return a property of a user.
 *
 * @param interger $userId
 * @param string $propertyId
 * @return mixed value of the selected property for given user
 */

function get_user_property($userId,$propertyId, $force = false)
{
    static $userPropertyList = array();
    if (!array_key_exists($userId,$userPropertyList) || !array_key_exists($propertyId,$userPropertyList[$userId]) || $force )
    {
        $tbl = claro_sql_get_tbl('user_property');
        $sql = "SELECT propertyValue
                FROM `" . $tbl['user_property'] . "`
                WHERE userId = " . (int) $userId . "
                  AND propertyId = '" . addslashes($propertyId) . "'";
        $userPropertyList[$userId][$propertyId] = claro_sql_query_get_single_value($sql);
    }
    return $userPropertyList[$userId][$propertyId];
}

function set_user_property($userId,$propertyId,$propertyValue, $scope='')
{
    $tbl = claro_sql_get_tbl('user_property');
    $sql = "REPLACE INTO `" . $tbl['user_property'] . "` SET
                userId        =  " . (int) $userId              . ",
                propertyId    = '" . addslashes($propertyId)    . "',
                propertyValue = '" . addslashes($propertyValue) . "',
                scope         = '" . addslashes($scope) . "'";

    return claro_sql_query($sql);
}

/**
 * get the list of extraProperties for user accounts
 *
 * @since claroline 1.8
 *
 * @return array('propertyId'=>array('propertyId', 'label', 'type', 'defaultValue', 'required');
 */
function get_userInfoExtraDefinitionList()
{
    $tbl = claro_sql_get_tbl('property_definition');
    $sql =  "SELECT propertyId, label, type, defaultValue, required
             FROM `" . $tbl['property_definition'] . "`
             WHERE contextScope = 'USER'
             ORDER BY rank
             ";
    $result = claro_sql_query_fetch_all_rows($sql);
    $extraInfoDefList = array();
    foreach ($result as $userPropertyDefinition)
    $extraInfoDefList[$userPropertyDefinition['propertyId']] = $userPropertyDefinition;

    return $extraInfoDefList;
}


/**
 * Set or redefine an extended data for users.
 *
 * @param integer $propertyId
 * @param string $label
 * @param string $type
 * @param mixed $defaultValue
 * @param string $contextScope
 * @param integer $rank
 * @param boolean $required
 * @return claro_sql result
 */
function update_userInfoExtraDefinition($propertyId, $label, $type, $defaultValue, $contextScope, $rank, $required )
{
    $tbl = claro_sql_get_tbl('property_definition');

    $sql = "REPLACE INTO `" . $tbl['property_definition'] . "`
            SET propertyId   = '" . addslashes($propertyId) . "',
                label        = '" . addslashes($label) . "',
                type         = '" . addslashes($type) . "',
                defaultValue = '" . addslashes($defaultValue) . "',
                contextScope = '" . addslashes($contextScope) . "',
                rank         = " . (int) $rank . ",
                required     = '" . addslashes($required) . "'
             WHERE propertyId = '" . addslashes($propertyId) . "'
             ";

    return claro_sql_query($sql);

}

/**
 * Set or redefine an extended data for users.
 *
 * @param integer $propertyId
 * @param string $contextScope
 * @return claro_sql result
 */
function delete_userInfoExtraDefinition($propertyId, $contextScope )
{
    $tbl = claro_sql_get_tbl('property_definition');

    $sql = "DELETE FROM `" . $tbl['property_definition'] . "`
            WHERE propertyId = '" . addslashes($propertyId) . "'
            AND  contextScope = '" . addslashes($contextScope) . "'";

    return claro_sql_query($sql);

}


?>