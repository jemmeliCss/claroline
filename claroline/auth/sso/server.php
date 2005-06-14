<?php # -$Id$

/*
 * SOAP server available for Single Sign On (SSO) process.
 * 
 * Once a user logs to the Claroline platform a cookie is sent to the 
 * user browser if the authentication process succeeds. The cookie value 
 * is also stored in a internal table of the Claroline platform for a certain 
 * time.
 *
 * The function of this script is providing a way to retrieve the user 
 * parameter from another server on the internet on the base of this 
 * cookie value.
 *
 */

/******************************************************************************
                                SOAP SERVER INIT
 ******************************************************************************/

// $langFile = 'trad4all'; // this line is mandatory to work in claroline 1.5
                           // but can be skipped in claroline 1.6

require_once '../../inc/claro_init_global.inc.php';
require_once $includePath.'/conf/auth.conf.php';
require_once $includePath.'/lib/nusoap.php';

$server = new soap_server();

$server->register('get_user_info_from_cookie', 
                   array('auth'   => 'xsd:string', 
                         'cookie' => 'xsd:string', 
                         'cid'    => 'xsd:string',
                         'gid'    => 'xsd:string' ) );

$server->service($HTTP_RAW_POST_DATA);




/*----------------------------------------------------------------------------
                            SSO FUNCTION DEFINITION
  ----------------------------------------------------------------------------*/


/**
 * get user parameter on the base of a cookie value
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param string $auth
 * @param string $cookie
 * @param string $cid
 * @param int    $gid
 * @return array   user parameters if it suceeds
 *         boolean false otherwise
 */

function get_user_info_from_cookie($auth, $cookie, $cid, $gid)
{
    if (! is_allowed_to_recieve_user_info($auth) )
    {
        return null;
    }

    $res = array('userLastname'             => null,
                 'userFirstname'            => null,
                 'userLoginName'            => null,
                 'userEmail'                => null,
                 'userOfficialCode'         => null,
                 'ssoCookieName'            => null,
                 'ssoCookieValue'           => null,
                 'ssoCookieExpireTime'      => null,
                 'ssoCookieDomain'          => null,
                 'ssoCookiePath'            => null,
                 'courseTitle'              => null,
                 'courseTitular'            => null,
                 'courseCode'               => null,
                 'courseDbName'             => null,
                 'courseRegistrationAllowed'=> null,
                 'courseVisibility'         => null,
                 'is_courseMember'          => null,
                 'is_courseTutor'           => null,
                 'is_courseAdmin'           => null,
                 'is_courseAllowed'         => null,
                 'groupName'                => null,
                 'groupDescription'         => null,
                 'groupPrivate'             => null,
                 'is_groupMember'           => null,
                 'is_groupTutor'            => null,
                 'is_groupAllowed'          => null);


    $ssoCookieName       = $GLOBALS['ssoCookieName'  ];
    $ssoCookieDomain     = $GLOBALS['ssoCookieDomain'];
    $ssoCookiePath       = $GLOBALS['ssoCookiePath'  ];

    $ssoCookieExpireTime = time() + $GLOBALS['ssoCookiePeriodValidity'];

    $mainTblList = claro_sql_get_main_tbl();
    $tbl_user    = $mainTblList['user'];
    $tbl_sso     = $mainTblList['sso' ];

    $sql = "SELECT user.nom          lastname, 
                   user.prenom       firstname, 
                   user.username     loginName, 
                   user.email        email, 
                   user.officialCode officialCode,
                   user.user_id      userId

            FROM `".$tbl_sso."`  AS sso,
                 `".$tbl_user."` AS user
            WHERE cookie = '".$cookie."'
              AND user.user_id = sso.user_id";

    $userResult = claro_sql_query_fetch_all($sql);

    if (count($userResult) > 0)
    {
        $user = $userResult[0];
        $uid  = $user['userId'];

        $res['userLastname'    ] = $user['lastname'    ];
        $res['userFirstname'   ] = $user['firstname'   ];
        $res['userLoginName'   ] = $user['loginName'   ];
        $res['userEmail'       ] = $user['email'       ];
        $res['userOfficialCode'] = $user['officialCode'];

        $newSsoCookieValue = generate_cookie();

        record_sso_cookie( $uid, $newSsoCookieValue );

        $res['ssoCookieName'      ] = $ssoCookieName;
        $res['ssoCookieValue'     ] = $newSsoCookieValue;
        $res['ssoCookieExpireTime'] = $ssoCookieExpireTime;
        $res['ssoCookieDomain'    ] = $ssoCookieDomain;
        $res['ssoCookiePath'      ] = $ssoCookiePath;
    }
    else
    {
        return null;
    }


    if( $uid && $cid ) // search for the user status in course
    {
        $tbl_course          = $mainTblList['course'         ]; // for claroline 1.6
        // $tbl_course          = $mainTblList['cours'       ]; // for claroline 1.5
        $tbl_rel_course_user = $mainTblList['rel_course_user'];

        $sql = "SELECT `c`.`intitule`   title, 
                       `c`.`fake_code`  officialCode, 
                       `c`.`titulaires` titular, 
                       `c`.`dbName`,
                       `c`.`visible`    visibility, 
                       `cu`.`statut`    userStatus, 
                       `cu`.`role`      userRole, 
                       `cu`.`tutor`
                FROM      `".$tbl_course."`          c
                LEFT JOIN `".$tbl_rel_course_user."` cu
                ON    `c`.`code`     = `cu`.`code_cours`
                AND   `cu`.`user_id` = '".$uid."'
                WHERE `c`.`code`     = '".$cid."'";

        $courseResult = claro_sql_query_fetch_all($sql);
    
        if( count($courseResult > 0) )
        {
            $course = $courseResult[0];
            
            $res['courseTitle'  ] = $course['title'       ];
            $res['courseTitular'] = $course['titular'     ];
            $res['courseCode'   ] = $course['officialCode'];
            $res['courseDbName' ] = $course['dbName'      ];

            $res['courseRegistrationAllowed'] = (bool) (   $course['visibility'] == 1 
                                                        || $course['visibility'] == 2 );

            $res['courseVisibility'         ] = (bool) (   $course['visibility'] == 2 
                                                        || $course['visibility'] == 3 );

            $res['is_courseMember' ] = (bool) ( ! is_null($course['userStatus']) );
            $res['is_courseTutor'  ] = (bool) (   $course['tutor'     ] == 1  );
            $res['is_courseAdmin'  ] = (bool) (   $course['userStatus'] ==  1 );
            $res['is_courseAllowed'] = (bool) (   $course['visibility'     ] 
                                               || $course['is_courseMember']  ); 
        }
    }

    if ($uid && $cid && $gid)
    {
        global $courseTablePrefix, $dbGlu; // from config.

        $courseTblList = claro_sql_get_course_tbl(claro_get_course_db_name_glued($cid));

        $tbl_group_team          = $courseTblList['group_team'         ];
        $tbl_group_property      = $courseTblList['group_property'     ];
        $tbl_group_rel_team_user = $courseTblList['group_rel_team_user'];

        $sql = "SELECT g.`name`, 
                       g.`description`, 
                       g.`tutor` tutorId,
                       gp.`private`, 
                       gp.`self_registration`, 
                       gtu.`user`, 
                       gtu.`team`, 
                       gtu.`status`, 
                       gtu.`role`
                FROM `".$tbl_group_team."`            AS g, 
                     `".$tbl_group_property."`       AS gp, 
                     `".$tbl_group_rel_team_user."` AS gtu
                WHERE gtu.`user` = '".$uid."'
                  AND gtu.`team` = '".$gid."'
                  AND gtu.`team` = g.`id`";
        
        $groupResult = claro_sql_query_fetch_all($sql);

        if (count($groupResult) > 0)
        {
            $group = $groupResult[0];

            $res['groupName'       ] = $group['name'       ];
            $res['groupDescription'] = $group['description'];

            $res['groupPrivate'    ] = (bool) ($group['private'] == 1   );
            $res['is_groupMember'  ] = (bool) ($group['user'   ] == $uid);
            $res['is_groupTutor'   ] = (bool) ($group['tutorId'] == $uid);

            $res['is_groupAllowed' ] = (bool) (   (  $group['is_groupMember']) 
                                               || (  $group['is_groupTutor' ]) 
                                               || (! $group['private'       ]) );
        }
    }

    return $res;
}


/**
 * generate a crypted aleatoric cookie value
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @return string
 */


function generate_cookie()
{
    return md5(mktime());
}

/**
 * records the cookie value of specific user during authentication
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param int    $userId
 * @param string $cookie
 */


function record_sso_cookie($userId, $ssoCookie)
{
    $tbl_sso = 'icampus.sso';

    $sql = "UPDATE ".$tbl_sso." 
            SET cookie    = '".$ssoCookie."',
                rec_time  = NOW()
            WHERE user_id = ". (int) $userId;

    $affectedRowCount = claro_sql_query_affected_rows($sql);

    if ($affectedRowCount < 1)
    {
        $sql = "INSERT INTO ".$tbl_sso." 
                SET cookie    = '".$ssoCookie."',
                    rec_time  = NOW(),
                    user_id   = ". (int) $userId;

        claro_sql_query($sql);
    }
}


/**
 * check if the soap client is allowed to recieve the user information 
 * recorded into the system
 *
 * @author Hugues Peeters <peeters@ipm.ucl.ac.be>
 * @param string $auth
 * @return boolean true if is allowed, false otherwise
 */


function is_allowed_to_recieve_user_info($auth)
{
    if ( in_array($auth, $GLOBALS['ssoAuthenticationKeyList']) )
    {
        return true;
    }
    else
    {
    	return false;
    }
}
?>
