<?php // $Id$
/**
 * CLAROLINE
 *
 * add_course lib contain function to add a course
 * add is, find keys names aivailable, build the the course database
 * fill the course database, build the content directorys, build the index page
 * build the directory tree, register the course.
 *
 * @version 1.7 $Revision$
 * 
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 * 
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @see http://www.claroline.net/wiki/CLCRS/
 *
 * @package COURSE
 *
 * @author Claro Team <cvs@claroline.net>
 * @author Christophe Gesch� <moosh@claroline.net>
 *
 */

/**
 * with  the WantedCode we can define the 4 keys  to find courses datas
 *
 * @param string $wantedCode initial model
 * @param string $prefix4all       prefix added  for ALL keys 
 * @param string $prefix4baseName  prefix added  for basename key (after the $prefix4all)
 * @param string $prefix4path      prefix added  for repository key (after the $prefix4all)
 * @param string $addUniquePrefix  prefix randomly generated prepend to model
 * @param boolean $useCodeInDepedentKeys   whether not ignore $wantedCode param. If FALSE use an empty model.
 * @param boolean $addUniqueSuffix suffix randomly generated append to model
 * @param string $suffix4baseName  suffix added  for db key (prepend to $suffix4all)
 * @param string $suffix4path      suffix added  for repository key (prepend to $suffix4all)
 * @param string $suffix4all       suffix added  for ALL keys 
 * @return array 
 * - ["currentCourseCode"]             : Must be alphaNumeric and outputable in HTML System
 * - ["currentCourseId"]            : Must be unique in mainDb.course it's the primary key
 * - ["currentCourseDbName"]        : Must be unique it's the database name.
 * - ["currentCourseRepository"]    : Must be unique in /$coursesRepositories/
 * 
 * @todo actually if suffix is not unique  the next append and not  replace
 * @todo add param listing keyg wich wouldbe identical
 * @todo manage an error on brake for too many try
 * @todo $keysCourseCode is always 
 */

function define_course_keys ($wantedCode,          
                             $prefix4all = '',
                             $prefix4baseName = '', 
                             $prefix4path = '',
                             $addUniquePrefix = FALSE,
                             $useCodeInDepedentKeys = TRUE,
                             $addUniqueSuffix = FALSE,
                             $suffix4baseName ='', 
                             $suffix4path = '',          
                             $suffix4all = '',
                             $forceSameSuffix = TRUE
                             )
{
    $tbl_mdb_names = claro_sql_get_main_tbl();
    $tbl_course    = $tbl_mdb_names['course'];

    GLOBAL $coursesRepositories,$prefixAntiNumber,$prefixAntiEmpty,$nbCharFinalSuffix,$DEBUG,$singleDbEnabled;

    if ( !isset($nbCharFinalSuffix)
       ||!is_numeric($nbCharFinalSuffix) 
       || $nbCharFinalSuffix < 1
       )
    $nbCharFinalSuffix = 2 ; // Number of car to add on end of key

    if ($coursesRepositories == '')
    {
    };

    // $keys["currentCourseCode"] is the "public code"

    $wantedCode =  strtr($wantedCode,
    '�����������������������������������������������������������',
    'AAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');

    //$wantedCode = strtoupper($wantedCode);
    $charToReplaceByUnderscore = '- ';
    $wantedCode = ereg_replace('['.$charToReplaceByUnderscore.']', '_', $wantedCode);
    $wantedCode = ereg_replace('[^A-Za-z0-9_]', '', $wantedCode);

    if ($wantedCode=='') $wantedCode = $prefixAntiEmpty;

    $keysCourseCode    = $wantedCode;

    if (!$useCodeInDepedentKeys) $wantedCode = "";

    // $keys['currentCourseId'] would Became $cid in normal using.

    if ($addUniquePrefix) $uniquePrefix =  substr(md5 (uniqid('')),0,10);
    else                  $uniquePrefix = '';
    
    if ($addUniqueSuffix) $uniqueSuffix =  substr(md5 (uniqid('')),0,10);

    else                  $uniqueSuffix = '';

    $keysAreUnique = FALSE;

    $finalSuffix = array('CourseId'=>''
                        ,'CourseDb'=>''
                        ,'CourseDir'=>''
                        );
    $tryNewFSCId = $tryNewFSCDb = $tryNewFSCDir = 0;

    while (!$keysAreUnique)
    {
        $keysCourseId         = $prefix4all . $uniquePrefix . strtoupper($wantedCode) . $uniqueSuffix . $finalSuffix['CourseId'];
        $keysCourseDbName     = $prefix4baseName . $uniquePrefix . strtoupper($keysCourseId) . $uniqueSuffix . $finalSuffix['CourseDb'];
        $keysCourseRepository = $prefix4path . $uniquePrefix . strtoupper($wantedCode) . $uniqueSuffix . $finalSuffix['CourseDir'];

        $keysAreUnique = TRUE;
        // Now we go to check if there are unique

        $sqlCheckCourseId    = "SELECT COUNT(code) existAllready
                                FROM `" . $tbl_course . "`
                                WHERE code = '" . $keysCourseId  ."'";

        $resCheckCourseId    = claro_sql_query ($sqlCheckCourseId);
        $isCheckCourseIdUsed = mysql_fetch_array($resCheckCourseId);

        if ($isCheckCourseIdUsed[0]['existAllready'] > 0)
        {
            $keysAreUnique = FALSE;
            $tryNewFSCId++;
            $finalSuffix['CourseId'] = substr(md5 (uniqid('')), 0, $nbCharFinalSuffix);
        };

        if ($singleDbEnabled)
        {
            $sqlCheckCourseDb = "SHOW TABLES LIKE '".$keysCourseDbName."%'";
        }
        else 
        {
            $sqlCheckCourseDb = "SHOW DATABASES LIKE '".$keysCourseDbName."'";
        }

        $resCheckCourseDb = claro_sql_query ($sqlCheckCourseDb);

        $isCheckCourseDbUsed = mysql_num_rows($resCheckCourseDb);

        if ($isCheckCourseDbUsed>0)
        {
            $keysAreUnique = FALSE;
            $tryNewFSCDb++;
            $finalSuffix['CourseDb'] = substr('_'.md5 (uniqid('')), 0, $nbCharFinalSuffix);
        };

        if (file_exists($coursesRepositories . '/' . $keysCourseRepository))
        {
            $keysAreUnique = FALSE;
            $tryNewFSCDir++;
            $finalSuffix['CourseDir'] = substr(md5 (uniqid('')), 0, $nbCharFinalSuffix);
            if ($DEBUG) echo '[dir'.$coursesRepositories . '/' . $keysCourseRepository.']<br>';
        };
        
        if(!$keysAreUnique && $forceSameSuffix)
        {
            $finalSuffix['CourseDir'] = substr(md5 (uniqid ('')), 0, $nbCharFinalSuffix);
            $finalSuffix['CourseId']  = $finalSuffix['CourseDir'];
            $finalSuffix['CourseDb']  = $finalSuffix['CourseDir'];
        }
    }

    // here  we can add a counter to exit if need too many try
    $limitQtyTry = 128;

    if (($tryNewFSCId+$tryNewFSCDb+$tryNewFSCDir > $limitQtyTry)
            or ($tryNewFSCId > $limitQtyTry / 2 )
            or ($tryNewFSCDb > $limitQtyTry / 2 )
            or ($tryNewFSCDir > $limitQtyTry / 2 )
        )
    {
        return FALSE;
    }

    // dbName Can't begin with a number
    if (!strstr("abcdefghijklmnopqrstuvwyzABCDEFGHIJKLMNOPQRSTUVWXYZ",$keysCourseDbName[0]))
    {
        $keysCourseDbName = $prefixAntiNumber.$keysCourseDbName;
    }
    
    $keys['currentCourseCode']       = $keysCourseCode;         // screen code
    $keys['currentCourseId']         = $keysCourseId;        // sysCode
    $keys['currentCourseDbName']     = $keysCourseDbName;    // dbname
    $keys['currentCourseRepository'] = $keysCourseRepository;// append to course repository

    return $keys;
};

/**
 * Create directory used by course.
 *
 * @param  string $courseRepository path from $coursesRepositorySys to root of course
 * @param  string $courseId         sysId of course
 *
 * @author  Christophe Gesch� <moosh@claroline.net>
 * @version 1.0
 *
 */

function prepare_course_repository($courseRepository, $courseId)
{
    GLOBAL $coursesRepositorySys, $clarolineRepositorySys, $includePath;
    if( !is_dir($coursesRepositorySys) )
    {
        claro_mkdir($coursesRepositorySys, CLARO_FILE_PERMISSIONS, TRUE);
    }
    
    if (is_writable($coursesRepositorySys))
    {
        /*
            here would come new section of code to
            read in tools table witch directories to create
        */
        
        claro_mkdir($coursesRepositorySys . $courseRepository, CLARO_FILE_PERMISSIONS);
        claro_mkdir($coursesRepositorySys . $courseRepository . '/exercise', CLARO_FILE_PERMISSIONS);
        claro_mkdir($coursesRepositorySys . $courseRepository . '/document', CLARO_FILE_PERMISSIONS);
        claro_mkdir($coursesRepositorySys . $courseRepository . '/page', CLARO_FILE_PERMISSIONS);
        claro_mkdir($coursesRepositorySys . $courseRepository . '/work', CLARO_FILE_PERMISSIONS);
        claro_mkdir($coursesRepositorySys . $courseRepository . '/group', CLARO_FILE_PERMISSIONS);
        claro_mkdir($coursesRepositorySys . $courseRepository . '/chat', CLARO_FILE_PERMISSIONS);
 
        claro_mkdir($coursesRepositorySys . $courseRepository . '/modules', CLARO_FILE_PERMISSIONS);
        claro_mkdir($coursesRepositorySys . $courseRepository . '/scormPackages', CLARO_FILE_PERMISSIONS);

        claro_mkdir($coursesRepositorySys . $courseRepository . '/modules/module_1', CLARO_FILE_PERMISSIONS);
        // for sample learning path <- probably to delete .


        // build index.php of course
        $fd=fopen($coursesRepositorySys . $courseRepository . '/index.php', 'w');

        // str_replace() removes \r that cause squares to appear at the end of each line
        $string=str_replace("\r","","<?"."php
//        session_start();
    \$cidReq = \"$courseId\";
  \$claroGlobalPath = '$includePath';
    include(\"".$clarolineRepositorySys."course_home/course_home.php\");
    ?".">");
        fwrite($fd, $string);
        $fd=fopen($coursesRepositorySys.$courseRepository."/group/index.php", "w");
        $string='<' . '?' . 'php' . ' session_start' . '()' .'; ?'.'>';
        fwrite($fd, $string);
        return true;
    }
    else
    {
        return claro_failure::set_failure('READ_ONLY_SYSTEM_FILE');
    }

};

/**
 * Add starting files in course
 *
 * @param   string  $courseDbName partial dbName form course table tu build real DbName
 * @global  boolean singleDbEnabled   whether all campus use only one DB
 * @global  string  courseTablePrefix common prefix for all table of courses
 * @global  string  dbGlu glu between logical name of DB and logical name of table 267
 *
 * @author    Christophe Gesch� <moosh@claroline.net>
 * @version 1.0
 */

function update_db_course($courseDbName)
{
    global $singleDbEnabled;
    global $courseTablePrefix;
    global $dbGlu;

    if(!$singleDbEnabled)
    {
        claro_sql_query('CREATE DATABASE `'.$courseDbName.'`');
        if (mysql_errno()>0)
            return CLARO_ERROR_CANT_CREATE_DB;
    }

    $courseDbName = $courseTablePrefix . $courseDbName . $dbGlu;
    /**
        Here function claro_sql_get_course_tbl() from main lib would be
        called to replace the table name assignement
    */

    $tbl_cdb_names = claro_sql_get_course_tbl($courseDbName);
    $TABLECOURSEHOMEPAGE    = $tbl_cdb_names['tool'];
    $TABLEINTROS            = $tbl_cdb_names['tool_intro'];

    $TABLEGROUPS            = $tbl_cdb_names['group_team'];// $courseDbName."group_team";
    $TABLEGROUPUSER         = $tbl_cdb_names['group_rel_team_user'];//$courseDbName."group_rel_team_user";
    $TABLEGROUPPROPERTIES   = $tbl_cdb_names['group_property'];// $courseDbName."group_property";

    $TABLETOOLUSERINFOCONTENT    = $tbl_cdb_names['userinfo_content'];// $courseDbName."userinfo_content";
    $TABLETOOLUSERINFODEF        = $tbl_cdb_names['userinfo_def'];// $courseDbName."userinfo_def";

    $TABLETOOLCOURSEDESC    = $tbl_cdb_names['course_description'];// $courseDbName."course_description";
    $TABLETOOLAGENDA        = $tbl_cdb_names['calendar_event'];// $courseDbName."calendar_event";
    $TABLETOOLANNOUNCEMENTS = $tbl_cdb_names['announcement'];// $courseDbName."announcement";
    $TABLETOOLDOCUMENT      = $tbl_cdb_names['document'];// $courseDbName."document";
    $TABLETOOLWRKASSIGNMENT = $tbl_cdb_names['wrk_assignment'];// $courseDbName."wrk_assignment";
    $TABLETOOLWRKSUBMISSION = $tbl_cdb_names['wrk_submission'];// $courseDbName."wrk_submission";

    $TABLEQUIZ              = $tbl_cdb_names['quiz_test'];//  $courseDbName."quiz_test";
    $TABLEQUIZQUESTION      = $tbl_cdb_names['quiz_rel_test_question'];
    $TABLEQUIZQUESTIONLIST  = $tbl_cdb_names['quiz_question'];//  "quiz_question";
    $TABLEQUIZANSWERSLIST   = $tbl_cdb_names['quiz_answer'];//  "quiz_answer";

    $TABLEPHPBBCATEGORIES   = $tbl_cdb_names['bb_categories'];//  "bb_categories";
    $TABLEPHPBBFORUMS       = $tbl_cdb_names['bb_forums'];//  "bb_forums";
    $TABLEPHPBBNOTIFY       = $tbl_cdb_names['bb_rel_topic_userstonotify'];//  "bb_rel_topic_userstonotify"; //added for notification by email sytem for claroline 1.5
    $TABLEPHPBBPOSTS        = $tbl_cdb_names['bb_posts'];//  "bb_posts";
    $TABLEPHPBBPRIVMSG      = $tbl_cdb_names['bb_priv_msgs'];//  "bb_priv_msgs";
    $TABLEPHPBBTOPICS       = $tbl_cdb_names['bb_topics'];//  "bb_topics";
    $TABLEPHPBBUSERS        = $tbl_cdb_names['bb_users'];//  "bb_users";
    $TABLEPHPBBWHOSONLINE   = $tbl_cdb_names['bb_whosonline'];//  "bb_whosonline";

    //linker
    $TABLELINKS               = $tbl_cdb_names['links'];//  "lnk_links";
    $TABLERESOURCES           = $tbl_cdb_names['resources'];//  "lnk_resources";
    
    $TABLELEARNPATH          = $tbl_cdb_names['lp_learnPath'];//  "lp_learnPath";
    $TABLEMODULE             = $tbl_cdb_names['lp_module'];//  "lp_module";
    $TABLELEARNPATHMODULE    = $tbl_cdb_names['lp_rel_learnPath_module'];//  "lp_rel_learnPath_module";
    $TABLEASSET              = $tbl_cdb_names['lp_asset'];//  "lp_asset";
    $TABLEUSERMODULEPROGRESS = $tbl_cdb_names['lp_user_module_progress'];//  "lp_user_module_progress";
    // stats
    $TABLETRACKACCESS     = $tbl_cdb_names['track_e_access'];//  "track_e_access";
    $TABLETRACKDOWNLOADS  = $tbl_cdb_names['track_e_downloads'];//  "track_e_downloads";
    $TABLETRACKUPLOADS    = $tbl_cdb_names['track_e_uploads'];//  "track_e_uploads";
    $TABLETRACKEXERCICES  = $tbl_cdb_names['track_e_exercices'];//  "track_e_exercices";
    $TABLETRACKEXEDETAILS = $tbl_cdb_names['track_e_exe_details']; //"track_e_exe_details"
    $TABLETRACKEXEANSWERS = $tbl_cdb_names['track_e_exe_answers']; //"track_e_exe_details"
    
    //wiki
    $TABLEWIKIPROPERTIES = $tbl_cdb_names['wiki_properties']; // "wiki_properties"
    $TABLEWIKIACLS = $tbl_cdb_names['wiki_acls']; // "wiki_acls"
    $TABLEWIKIPAGES = $tbl_cdb_names['wiki_pages']; // "wiki_pages"
    $TABLEWIKIPAGESCONTENT = $tbl_cdb_names['wiki_pages_content']; // "wiki_pages_content"

    $sql ="
CREATE TABLE `".$TABLETOOLANNOUNCEMENTS."` (
  `id` mediumint(11) NOT NULL auto_increment,
  `title` varchar(80) default NULL,
  `contenu` text,
  `temps` date default NULL,
  `ordre` mediumint(11) NOT NULL default '0',
  `visibility` enum('SHOW','HIDE') NOT NULL default 'SHOW',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='announcements table'";
claro_sql_query($sql);

    $sql ="
CREATE TABLE `".$TABLETOOLUSERINFOCONTENT."` (
   `id` int(10) unsigned NOT NULL auto_increment,
   `user_id` mediumint(8) unsigned NOT NULL default '0',
   `def_id` int(10) unsigned NOT NULL default '0',
   `ed_ip` varchar(39) default NULL,
   `ed_date` datetime default NULL,
   `content` text,
   PRIMARY KEY  (`id`),
   KEY `user_id` (`user_id`)
) TYPE=MyISAM COMMENT='content of users information - organisation based on
userinf'";

claro_sql_query($sql);

    $sql ="
CREATE TABLE `".$TABLETOOLUSERINFODEF."` (
   `id` int(10) unsigned NOT NULL auto_increment,
   `title` varchar(80) NOT NULL default '',
   `comment` varchar(160) default NULL,
   `nbLine` int(10) unsigned NOT NULL default '5',
   `rank` tinyint(3) unsigned NOT NULL default '0',
   PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='categories definition for user information of a course'";
claro_sql_query($sql);

    $sql = "
    CREATE TABLE `".$TABLEPHPBBCATEGORIES."` (
        cat_id int(10) NOT NULL auto_increment,
        cat_title varchar(100),
        cat_order varchar(10),
    PRIMARY KEY (cat_id)
    )";
claro_sql_query($sql);


$sql = "
    CREATE TABLE `".$TABLEPHPBBFORUMS."`(
        forum_id int(10) NOT NULL auto_increment,
        group_id int(11) default NULL,
        forum_name varchar(150),
        forum_desc text,
        forum_access int(10) DEFAULT '1',
        forum_moderator int(10),
        forum_topics int(10) DEFAULT '0' NOT NULL,
        forum_posts int(10) DEFAULT '0' NOT NULL,
        forum_last_post_id int(10) DEFAULT '0' NOT NULL,
        cat_id int(10),
        forum_type int(10) DEFAULT '0',
    PRIMARY KEY (forum_id),
        KEY forum_last_post_id (forum_last_post_id),
        forum_order int(10) DEFAULT '0'
    )";
claro_sql_query($sql);

    $sql = "
    CREATE TABLE `".$TABLEPHPBBPOSTS."`(
        post_id int(10) NOT NULL auto_increment,
        topic_id int(10) DEFAULT '0' NOT NULL,
        forum_id int(10) DEFAULT '0' NOT NULL,
        poster_id int(10) DEFAULT '0' NOT NULL,
        post_time varchar(20),
        poster_ip varchar(16),
        nom varchar(30),
        prenom varchar(30),
    PRIMARY KEY (post_id),
        KEY post_id (post_id),
        KEY forum_id (forum_id),
        KEY topic_id (topic_id),
        KEY poster_id (poster_id)
    )";
claro_sql_query($sql);

//  Structure de la table 'priv_msgs'
    $sql = "
    CREATE TABLE `".$TABLEPHPBBPRIVMSG."` (
        msg_id int(10) NOT NULL auto_increment,
        from_userid int(10) DEFAULT '0' NOT NULL,
        to_userid int(10) DEFAULT '0' NOT NULL,
        msg_time varchar(20),
        poster_ip varchar(16),
        msg_status int(10) DEFAULT '0',
        msg_text text,
    PRIMARY KEY (msg_id),
        KEY msg_id (msg_id),
        KEY to_userid (to_userid)
    )";
claro_sql_query($sql);

//  Structure de la table 'topics'
    $sql = "
    CREATE TABLE `".$TABLEPHPBBTOPICS."` (
        topic_id int(10) NOT NULL auto_increment,
        topic_title varchar(100),
        topic_poster int(10),
        topic_time varchar(20),
        topic_views int(10) DEFAULT '0' NOT NULL,
        topic_replies int(10) DEFAULT '0' NOT NULL,
        topic_last_post_id int(10) DEFAULT '0' NOT NULL,
        forum_id int(10) DEFAULT '0' NOT NULL,
        topic_status int(10) DEFAULT '0' NOT NULL,
        topic_notify int(2) DEFAULT '0',
        nom varchar(30),
        prenom varchar(30),
    PRIMARY KEY (topic_id),
        KEY topic_id (topic_id),
        KEY forum_id (forum_id),
        KEY topic_last_post_id (topic_last_post_id)
    )";
claro_sql_query($sql);

//  Structure de la table 'users'
    $sql = "
    CREATE TABLE `".$TABLEPHPBBUSERS."` (
        user_id int(10) NOT NULL auto_increment,
        username varchar(40) NOT NULL,
        user_regdate varchar(20) NOT NULL,
        user_password varchar(32) NOT NULL,
        user_email varchar(50),
        user_icq varchar(15),
        user_website varchar(100),
        user_occ varchar(100),
        user_from varchar(100),
        user_intrest varchar(150),
        user_sig varchar(255),
        user_viewemail tinyint(2),
        user_theme int(10),
        user_aim varchar(18),
        user_yim varchar(25),
        user_msnm varchar(25),
        user_posts int(10) DEFAULT '0',
        user_attachsig int(2) DEFAULT '0',
        user_desmile int(2) DEFAULT '0',
        user_html int(2) DEFAULT '0',
        user_bbcode int(2) DEFAULT '0',
        user_rank int(10) DEFAULT '0',
        user_level int(10) DEFAULT '1',
        user_lang varchar(255),
        user_actkey varchar(32),
        user_newpasswd varchar(32),
    PRIMARY KEY (user_id)
    )";
claro_sql_query($sql);

//  Structure de la table 'whosonline'
    $sql = "
    CREATE TABLE `".$TABLEPHPBBWHOSONLINE."` (
        id int(3) NOT NULL auto_increment,
        ip varchar(255),
        name varchar(255),
        count varchar(255),
        date varchar(255),
        username varchar(40),
        forum int(10),
    PRIMARY KEY (id)
    )";
claro_sql_query($sql);

    $sql = "CREATE TABLE `".$TABLEPHPBBNOTIFY."` (
  `notify_id` int(10) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL default '0',
  `topic_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`notify_id`),
  KEY `SECONDARY` (`user_id`,`topic_id`)
  )";
claro_sql_query($sql);

//  EXERCICES
claro_sql_query("
    CREATE TABLE `".$TABLEQUIZ."` (
        `id` mediumint(8) unsigned NOT NULL auto_increment,
        `titre` varchar(200) NOT NULL,
        `description` text NOT NULL,
        `type` tinyint(4) unsigned NOT NULL default '1',
        `random` smallint(6) NOT NULL default '0',
        `active` tinyint(4) unsigned NOT NULL default '0',
        `max_time` smallint(5) unsigned NOT NULL default '0',
  `max_attempt` tinyint(3) unsigned NOT NULL default '0',
  `show_answer` enum('ALWAYS','NEVER','LASTTRY') NOT NULL default 'ALWAYS',
  `anonymous_attempts` enum('YES','NO') NOT NULL default 'YES',
  `start_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL default '0000-00-00 00:00:00',
    PRIMARY KEY  (id)
    )");

//  QUESTIONS
claro_sql_query("
    CREATE TABLE `".$TABLEQUIZQUESTIONLIST."` (
        id mediumint(8) unsigned NOT NULL auto_increment,
        question varchar(200) NOT NULL,
        description text NOT NULL,
        ponderation float unsigned default NULL,
        q_position mediumint(8) unsigned NOT NULL default '1',
        type tinyint(3) unsigned NOT NULL default '2',
   attached_file varchar(50) default '',
    PRIMARY KEY  (id)
    )");

//  REPONSES
claro_sql_query("
    CREATE TABLE `".$TABLEQUIZANSWERSLIST."` (
        id mediumint(8) unsigned NOT NULL default '0',
        question_id mediumint(8) unsigned NOT NULL default '0',
        reponse text NOT NULL,
        correct mediumint(8) unsigned default NULL,
        comment text default NULL,
        ponderation float default NULL,
        r_position mediumint(8) unsigned NOT NULL default '1',
    PRIMARY KEY  (id, question_id)
    )");

//  EXERCICE_QUESTION
claro_sql_query("
    CREATE TABLE `".$TABLEQUIZQUESTION."` (
        question_id mediumint(8) unsigned NOT NULL default '0',
        exercice_id mediumint(8) unsigned NOT NULL default '0',
    PRIMARY KEY  (question_id,exercice_id)
    )");

#######################COURSE_DESCRIPTION ################################
claro_sql_query("
    CREATE TABLE `".$TABLETOOLCOURSEDESC."` (
        `id` TINYINT UNSIGNED DEFAULT '0' NOT NULL,
        `title` VARCHAR(255),
        `content` TEXT,
        `upDate` DATETIME NOT NULL,
        `visibility` enum('SHOW','HIDE') NOT NULL default 'SHOW',
        UNIQUE (`id`)
    )
    COMMENT = 'for course description tool';");

####################### TOOL_LIST ###########################################
claro_sql_query("
    CREATE TABLE `".$TABLECOURSEHOMEPAGE."` (
      `id` int(11) NOT NULL auto_increment,
      `tool_id` int(10) unsigned default NULL,
      `rank` int(10) unsigned NOT NULL,
      `access` enum('ALL','PLATFORM_MEMBER','COURSE_MEMBER','COURSE_TUTOR','GROUP_MEMBER','GROUP_TUTOR','COURSE_ADMIN','PLATFORM_ADMIN') NOT NULL default 'ALL',
      `script_url` varchar(255) default NULL,
      `script_name` varchar(255) default NULL,
      PRIMARY KEY  (`id`)) ");

claro_sql_query("ALTER TABLE `".$TABLECOURSEHOMEPAGE."` ADD `addedTool` ENUM('YES','NO') DEFAULT 'YES';");

#################################### AGENDA ################################
claro_sql_query("
    CREATE TABLE `".$TABLETOOLAGENDA."` (
        `id` int(11) NOT NULL auto_increment,
        `titre` varchar(200),
        `contenu` text,
        `day` date NOT NULL default '0000-00-00',
        `hour` time NOT NULL default '00:00:00',
        `lasting` varchar(20),
        `visibility` enum('SHOW','HIDE') NOT NULL default 'SHOW',
    PRIMARY KEY (id))");

############################# DOCUMENTS ###########################################
claro_sql_query ("
    CREATE TABLE `".$TABLETOOLDOCUMENT."` (
        id int(4) NOT NULL auto_increment,
        path varchar(255) NOT NULL,
        visibility char(1) DEFAULT 'v' NOT NULL,
        comment varchar(255),
    PRIMARY KEY (id))");

############################# WORKS ###########################################
// original_id is used to store the author id of the original work if this is a feedback
// private_feedback
claro_sql_query("

    CREATE TABLE `".$TABLETOOLWRKSUBMISSION."` (
        `id` int(11) NOT NULL auto_increment,
        `assignment_id` int(11) default NULL,
        `parent_id` int(11) default NULL,
        `user_id` int(11) default NULL,
        `group_id` int(11) default NULL,
        `title` varchar(200) NOT NULL default '',
        `visibility` enum('VISIBLE','INVISIBLE') default 'VISIBLE',
        `creation_date` datetime NOT NULL default '0000-00-00 00:00:00',
        `last_edit_date` datetime NOT NULL default '0000-00-00 00:00:00',
        `authors` varchar(200) NOT NULL default '',
        `submitted_text` text NOT NULL,
        `submitted_doc_path` varchar(200) NOT NULL default '',
        `private_feedback` text default NULL,
        `original_id` int(11) default NULL,
        `score` smallint(3) NULL default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;");
    
claro_sql_query("

    CREATE TABLE `".$TABLETOOLWRKASSIGNMENT."` (
        `id` int(11) NOT NULL auto_increment,
        `title` varchar(200) NOT NULL default '',
        `description` text NOT NULL,
        `visibility` enum('VISIBLE','INVISIBLE') NOT NULL default 'VISIBLE',
        `def_submission_visibility` enum('VISIBLE','INVISIBLE') NOT NULL default 'VISIBLE',
        `assignment_type` enum('INDIVIDUAL','GROUP') NOT NULL default 'INDIVIDUAL',
        `authorized_content`  enum('TEXT','FILE','TEXTFILE') NOT NULL default 'FILE',
        `allow_late_upload` enum('YES','NO') NOT NULL default 'YES',
        `start_date` datetime NOT NULL default '0000-00-00 00:00:00',
        `end_date` datetime NOT NULL default '0000-00-00 00:00:00',
        `prefill_text` text NOT NULL,
        `prefill_doc_path` varchar(200) NOT NULL default '',
        `prefill_submit` enum('ENDDATE','AFTERPOST') NOT NULL default 'ENDDATE',
        PRIMARY KEY  (`id`)
    ) TYPE=MyISAM;");
############################## LIENS #############################################
/*
claro_sql_query("
    CREATE TABLE `".$TABLETOOLLINK."` (
        id int(11) NOT NULL auto_increment,
        url varchar(150),
        titre varchar(150),
        description text,
    PRIMARY KEY (id))");
*/
claro_sql_query("

    CREATE TABLE `".$TABLEGROUPS."` (
    id int(11) NOT NULL auto_increment,
        name varchar(100) default NULL,
        description text,
        tutor int(11) default NULL,
        maxStudent int(11) NOT NULL default '0',
        secretDirectory varchar(30) NOT NULL default '0',
    PRIMARY KEY  (id)
    )");
claro_sql_query("
    CREATE TABLE `".$TABLEGROUPUSER."` (
        id int(11) NOT NULL auto_increment,
        user int(11) NOT NULL default '0',
        team int(11) NOT NULL default '0',
        status int(11) NOT NULL default '0',
        role varchar(50) NOT NULL default '',
    PRIMARY KEY  (id)
    )");
claro_sql_query("
    CREATE TABLE `".$TABLEGROUPPROPERTIES."` (
    id tinyint(4) NOT NULL auto_increment,
        self_registration tinyint(4) default '1',
        `nbGroupPerUser` TINYINT UNSIGNED DEFAULT '1',
        private tinyint(4) default '0',
        forum tinyint(4) default '1',
        document tinyint(4) default '1',
        wiki tinyint(4) default '0',
        chat tinyint(4) default '1',
    PRIMARY KEY  (id)
    )");

############################## INTRODUCTION #######################################

claro_sql_query("CREATE TABLE `".$TABLEINTROS."` (
  `id` int(11) NOT NULL auto_increment,
  `tool_id` int(11) NOT NULL default '0',
  `title` varchar(255) default NULL,
  `display_date` datetime default NULL,
  `content` text,
  `rank` int(11) default '1',
  `visibility` enum('SHOW','HIDE') NOT NULL default 'SHOW',
  PRIMARY KEY  (`id`)
)");


############################# LEARNING PATHS ######################################
claro_sql_query     ("
         CREATE TABLE `".$TABLEMODULE."` (
              `module_id` int(11) NOT NULL auto_increment,
              `name` varchar(255) NOT NULL default '',
              `comment` text NOT NULL,
              `accessibility` enum('PRIVATE','PUBLIC') NOT NULL default 'PRIVATE',
              `startAsset_id` int(11) NOT NULL default '0',
              `contentType` enum('CLARODOC','DOCUMENT','EXERCISE','HANDMADE','SCORM','LABEL') NOT NULL,
              `launch_data` text NOT NULL,
              PRIMARY KEY  (`module_id`)
            ) TYPE=MyISAM COMMENT='List of available modules used in learning paths';");

claro_sql_query  ("
          CREATE TABLE `".$TABLELEARNPATH."` (
              `learnPath_id` int(11) NOT NULL auto_increment,
              `name` varchar(255) NOT NULL default '',
              `comment` text NOT NULL,
              `lock` enum('OPEN','CLOSE') NOT NULL default 'OPEN',
              `visibility` enum('HIDE','SHOW') NOT NULL default 'SHOW',
              `rank` int(11) NOT NULL default '0',
              PRIMARY KEY  (`learnPath_id`),
              UNIQUE KEY rank (`rank`)
            ) TYPE=MyISAM COMMENT='List of learning Paths';");
claro_sql_query ("
          CREATE TABLE `".$TABLELEARNPATHMODULE."` (
                `learnPath_module_id` int(11) NOT NULL auto_increment,
                `learnPath_id` int(11) NOT NULL default '0',
                `module_id` int(11) NOT NULL default '0',
                `lock` enum('OPEN','CLOSE') NOT NULL default 'OPEN',
                `visibility` enum('HIDE','SHOW') NOT NULL default 'SHOW',
                `specificComment` text NOT NULL,
                `rank` int(11) NOT NULL default '0',
                `parent` int(11) NOT NULL default '0',
                `raw_to_pass` tinyint(4) NOT NULL default '50',
                PRIMARY KEY  (`learnPath_module_id`)
              ) TYPE=MyISAM COMMENT='This table links module to the learning path using them';");
claro_sql_query ("
          CREATE TABLE `".$TABLEASSET."` (
              `asset_id` int(11) NOT NULL auto_increment,
              `module_id` int(11) NOT NULL default '0',
              `path` varchar(255) NOT NULL default '',
              `comment` varchar(255) default NULL,
              PRIMARY KEY  (`asset_id`)
            ) TYPE=MyISAM COMMENT='List of resources of module of learning paths';");
claro_sql_query ("
          CREATE TABLE `".$TABLEUSERMODULEPROGRESS."` (
              `user_module_progress_id` int(22) NOT NULL auto_increment,
              `user_id` mediumint(9) NOT NULL default '0',
              `learnPath_module_id` int(11) NOT NULL default '0',
              `learnPath_id` int(11) NOT NULL default '0',
              `lesson_location` varchar(255) NOT NULL default '',
              `lesson_status` enum('NOT ATTEMPTED','PASSED','FAILED','COMPLETED','BROWSED','INCOMPLETE','UNKNOWN') NOT NULL default 'NOT ATTEMPTED',
              `entry` enum('AB-INITIO','RESUME','') NOT NULL default 'AB-INITIO',
              `raw` tinyint(4) NOT NULL default '-1',
              `scoreMin` tinyint(4) NOT NULL default '-1',
              `scoreMax` tinyint(4) NOT NULL default '-1',
              `total_time` varchar(13) NOT NULL default '0000:00:00.00',
              `session_time` varchar(13) NOT NULL default '0000:00:00.00',
              `suspend_data` text NOT NULL,
              `credit` enum('CREDIT','NO-CREDIT') NOT NULL default 'NO-CREDIT',
              PRIMARY KEY  (`user_module_progress_id`)
            ) TYPE=MyISAM COMMENT='Record the last known status of the user in the course';");


########################## STATISTICS ##############################
        $sql = "CREATE TABLE `".$TABLETRACKACCESS."` (
                  `access_id` int(11) NOT NULL auto_increment,
                  `access_user_id` int(10) default NULL,
                  `access_date` datetime NOT NULL default '0000-00-00 00:00:00',
                  `access_tid` int(10) default NULL,
                  `access_tlabel` varchar(8) default NULL,
                  PRIMARY KEY  (`access_id`)
                ) TYPE=MyISAM COMMENT='Record informations about access to course or tools'";
        claro_sql_query($sql);

        $sql = "CREATE TABLE `".$TABLETRACKDOWNLOADS."` (
                  `down_id` int(11) NOT NULL auto_increment,
                  `down_user_id` int(10) default NULL,
                  `down_date` datetime NOT NULL default '0000-00-00 00:00:00',
                  `down_doc_path` varchar(255) NOT NULL default '0',
                  PRIMARY KEY  (`down_id`)
                ) TYPE=MyISAM COMMENT='Record informations about downloads'";
        claro_sql_query($sql);
        
        $sql = "CREATE TABLE `".$TABLETRACKEXERCICES."` (
                  `exe_id` int(11) NOT NULL auto_increment,
                  `exe_user_id` int(10) default NULL,
                  `exe_date` datetime NOT NULL default '0000-00-00 00:00:00',
                  `exe_exo_id` tinyint(4) NOT NULL default '0',
                  `exe_result` float NOT NULL default '0',
                  `exe_time`    mediumint(8) NOT NULL default '0',
                  `exe_weighting` float NOT NULL default '0',
                  PRIMARY KEY  (`exe_id`)
                ) TYPE=MyISAM COMMENT='Record informations about exercices'";
        claro_sql_query($sql);
        
        $sql = "CREATE TABLE `".$TABLETRACKEXEDETAILS."` (
                  `id` int(11) NOT NULL auto_increment,
                  `exercise_track_id` int(11) NOT NULL default '0',
                  `question_id` int(11) NOT NULL default '0',
                  `result` float NOT NULL default '0',
                  PRIMARY KEY  (`id`)
                ) TYPE=MyISAM COMMENT='Record answers of students in exercices'";
        claro_sql_query($sql);
        
        $sql = "CREATE TABLE `" . $TABLETRACKEXEANSWERS . "` (
                  `id` int(11) NOT NULL auto_increment,
                  `details_id` int(11) NOT NULL default '0',
                  `answer` text NOT NULL,
                  PRIMARY KEY  (`id`)
                ) TYPE=MyISAM COMMENT=''";
        claro_sql_query($sql);

        $sql = "CREATE TABLE `".$TABLETRACKUPLOADS."` (
                  `upload_id` int(11) NOT NULL auto_increment,
                  `upload_user_id` int(10) default NULL,
                  `upload_date` datetime NOT NULL default '0000-00-00 00:00:00',
                  `upload_work_id` int(11) NOT NULL default '0',
                  PRIMARY KEY  (`upload_id`)
                ) TYPE=MyISAM COMMENT='Record some more informations about uploaded works'";
        claro_sql_query($sql);
        
########################## linker ##############################
        $sql = "CREATE TABLE IF NOT EXISTS `".$TABLELINKS."` (
                  `id` int(11) NOT NULL auto_increment,
                    `src_id` int(11) NOT NULL default '0',
                    `dest_id` int(11) NOT NULL default '0',
                    `creation_time` timestamp(14) NOT NULL,
                    PRIMARY KEY  (`id`)
                ) TYPE=MyISAM";
        claro_sql_query($sql);
               
        $sql = "CREATE TABLE IF NOT EXISTS `".$TABLERESOURCES."` (
                   `id` int(11) NOT NULL auto_increment,
                  `crl` text NOT NULL,
                  `title` text NOT NULL,
                  PRIMARY KEY  (`id`)
                ) TYPE=MyISAM";
        claro_sql_query($sql);
        
######################## wiki ##################################

    $sql = "CREATE TABLE IF NOT EXISTS `".$TABLEWIKIPROPERTIES."`(
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL DEFAULT '',
            `description` TEXT NULL,
            `group_id` INT(11) NOT NULL DEFAULT 0,
            PRIMARY KEY(`id`)
            )"
            ;

    claro_sql_query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `".$TABLEWIKIACLS."` (
            `wiki_id` INT(11) UNSIGNED NOT NULL,
            `flag` VARCHAR(255) NOT NULL,
            `value` ENUM('false','true') NOT NULL DEFAULT 'false'
            )"
            ;

     claro_sql_query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `".$TABLEWIKIPAGES."` (
            `id` int(11) unsigned NOT NULL auto_increment,
            `wiki_id` int(11) unsigned NOT NULL default '0',
            `owner_id` int(11) unsigned NOT NULL default '0',
            `title` varchar(255) NOT NULL default '',
            `ctime` datetime NOT NULL default '0000-00-00 00:00:00',
            `last_version` int(11) unsigned NOT NULL default '0',
            `last_mtime` datetime NOT NULL default '0000-00-00 00:00:00',
            PRIMARY KEY  (`id`)
            )"
            ;
    claro_sql_query($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS `".$TABLEWIKIPAGESCONTENT."` (
            `id` int(11) unsigned NOT NULL auto_increment,
            `pid` int(11) unsigned NOT NULL default '0',
            `editor_id` int(11) NOT NULL default '0',
            `mtime` datetime NOT NULL default '0000-00-00 00:00:00',
            `content` text NOT NULL,
            PRIMARY KEY  (`id`)
            )"
            ;
            
    claro_sql_query($sql);

    return 0;
};




/**
 * Add starting files in course
 *
 * @param    string    $courseRepository        path from $coursesRepositorySys to root of course
 *
 * @author    Christophe Gesch� <moosh@claroline.net>
 * @version 1.0
 */

function     fill_course_repository($courseRepository)
{
    ############# COPIER DOCUMENTS #############
    GLOBAL $clarolineRepositorySys, $coursesRepositorySys;
  // attention : do not forget to change the queris in fill_Db_course if something changed here
    copy($clarolineRepositorySys."document/Example_document.pdf", $coursesRepositorySys.$courseRepository."/document/Example_document.pdf");
    return 0;
};



/**
 * Insert starting data in db of course.
 *
 * @param  string  $courseDbName        partial DbName. to build as $courseTablePrefix.$courseDbName.$dbGlu;
 * @param  string  $language            language request for this course
 *
 * @author    Christophe Gesch� <moosh@claroline.net>
 * @version 1.0
 *
 * note  $language would be removed soon.
 */

function fill_db_course($courseDbName,$language)
{
    global $singleDbEnabled, $courseTablePrefix, $dbGlu, 
           $clarolineRepositorySys, $_user, $includePath;

    // include the language file with all language variables
    include ($includePath.'/../lang/english/complete.lang.php');

    if ($language != 'english') // Avoid useless include as English lang is preloaded
    {
        include($includePath.'/../lang/'.$language.'/complete.lang.php');
    }

    $courseDbName=$courseTablePrefix.$courseDbName.$dbGlu;
    $tbl_cdb_names = claro_sql_get_course_tbl($courseDbName);
    $TABLECOURSEHOMEPAGE    = $tbl_cdb_names['tool'];

    $TABLEGROUPPROPERTIES    = $tbl_cdb_names['group_property'];// $courseDbName."group_property";


    $TABLEQUIZ                = $tbl_cdb_names['quiz_test'];//  $courseDbName."quiz_test";
    $TABLEQUIZQUESTION        = $tbl_cdb_names['quiz_rel_test_question'];
    $TABLEQUIZQUESTIONLIST    = $tbl_cdb_names['quiz_question'];//  "quiz_question";
    $TABLEQUIZANSWERSLIST    = $tbl_cdb_names['quiz_answer'];//  "quiz_answer";

    $TABLEPHPBBCATEGORIES    = $tbl_cdb_names['bb_categories'];//  "bb_categories";
    $TABLEPHPBBFORUMS        = $tbl_cdb_names['bb_forums'];//  "bb_forums";
    $TABLEPHPBBPOSTS        = $tbl_cdb_names['bb_posts'];//  "bb_posts";
    $TABLEPHPBBPOSTSTEXT    = $tbl_cdb_names['bb_posts_text'];//  "bb_posts_text";
    $TABLEPHPBBTOPICS        = $tbl_cdb_names['bb_topics'];//  "bb_topics";
    $TABLEPHPBBUSERS        = $tbl_cdb_names['bb_users'];//  "bb_users";

    $TABLELEARNPATH         = $tbl_cdb_names['lp_learnPath'];//  "lp_learnPath";
    $TABLEMODULE            = $tbl_cdb_names['lp_module'];//  "lp_module";
    $TABLELEARNPATHMODULE   = $tbl_cdb_names['lp_rel_learnPath_module'];//  "lp_rel_learnPath_module";
    $TABLEASSET             = $tbl_cdb_names['lp_asset'];//  "lp_asset";

    $nom = $_user['lastName'];
    $prenom = $_user['firstName'];
    $email = $_user['mail'];

    mysql_select_db($courseDbName);

// Create an example category
    claro_sql_query("INSERT INTO `".$TABLEPHPBBCATEGORIES."` VALUES (2,'".addslashes($langCatagoryMain)."',1)");

// Create a hidden category for group forums
    claro_sql_query("INSERT INTO `".$TABLEPHPBBCATEGORIES."` VALUES (1,'".addslashes($langCatagoryGroup)."',2)");
############################## GROUPS ###########################################
    claro_sql_query("INSERT INTO `".$TABLEGROUPPROPERTIES."`
(id, self_registration, private, forum, document, wiki, chat)
VALUES (NULL, '1', '0', '1', '1', '1', '1')");
    claro_sql_query("INSERT 
                        INTO `".$TABLEPHPBBFORUMS."` 
                        VALUES ( 1
                               , NULL
                               , '".addslashes($langTestForum)."'
                               , '".addslashes($langDelAdmin)."'
                               ,2,1,1,1,1,2,0,1)");
    claro_sql_query("INSERT INTO `".$TABLEPHPBBPOSTS."` VALUES (1,1,1,1,NOW(),'127.0.0.1',\"".addslashes($nom)."\",\"".addslashes($prenom)."\")");
    claro_sql_query("CREATE TABLE `".$TABLEPHPBBPOSTSTEXT."` (
        post_id int(10) DEFAULT '0' NOT NULL,
        post_text text,
        PRIMARY KEY (post_id)
        )");
    claro_sql_query("INSERT INTO `".$TABLEPHPBBPOSTSTEXT."` VALUES ('1', '".addslashes($langMessage)."')");
// Contenu de la table 'users'
    claro_sql_query("INSERT INTO `".$TABLEPHPBBUSERS."` VALUES (
       '1',
       '".addslashes($nom." ".$prenom)."',
       NOW(),
       'password',
       '".addslashes($email)."',
       NULL,
       NULL,
       NULL,
       NULL,
       NULL,
       NULL,
       NULL,
       NULL,
       NULL,
       NULL,
       NULL,
       '0',
       '0',
       '0',
       '0',
       '0',
       '0',
       '1',
       NULL,
       NULL,
       NULL
       )");
    claro_sql_query("INSERT INTO `".$TABLEPHPBBUSERS."` VALUES (
       '-1',       '".addslashes($langAnonymous)."',       NOW(),       'password',       '',
       NULL,       NULL,       NULL,       NULL,       NULL,       NULL,       NULL,
       NULL,       NULL,       NULL,       NULL,       '0',       '0',       '0',       '0',       '0',
       '0',       '1',       NULL,       NULL,       NULL       )");

##################### register tools in course ######################################

    $tbl_mdb_names   = claro_sql_get_main_tbl();
    $TABLECOURSETOOL = $tbl_mdb_names['tool'  ];

    $sql = "SELECT id, def_access, def_rank, claro_label FROM `". $TABLECOURSETOOL . "` where add_in_course = 'AUTOMATIC'";

    $result = claro_sql_query($sql);

    if (mysql_num_rows($result) > 0)
    {
        while ( $courseTool = mysql_fetch_array($result, MYSQL_ASSOC))
        {
            $sql_insert = " INSERT INTO `" . $TABLECOURSEHOMEPAGE . "` "
                        . " (tool_id, rank, access) "
                        . " VALUES ('" . $courseTool['id'] . "','" . $courseTool['def_rank'] . "','" . $courseTool['def_access'] . "')";
            claro_sql_query_insert_id($sql_insert);
        }
    }

############################## EXERCICES #######################################
    claro_sql_query("INSERT INTO `".$TABLEQUIZANSWERSLIST."` VALUES ( '1', '1', '".addslashes($langRidiculise)."', '0', '".addslashes($langNoPsychology)."', '-5', '1')");
    claro_sql_query("INSERT INTO `".$TABLEQUIZANSWERSLIST."` VALUES ( '2', '1', '".addslashes($langAdmitError)."', '0', '".addslashes($langNoSeduction)."', '-5', '2')");
    claro_sql_query("INSERT INTO `".$TABLEQUIZANSWERSLIST."` VALUES ( '3', '1', '".addslashes($langForce)."', '1', '".addslashes($langIndeed)."', '5', '3')");
    claro_sql_query("INSERT INTO `".$TABLEQUIZANSWERSLIST."` VALUES ( '4', '1', '".addslashes($langContradiction)."', '1', '".addslashes($langNotFalse)."', '5', '4')");
    claro_sql_query("INSERT INTO `".$TABLEQUIZ."` VALUES ( '1', '".addslashes($langExerciceEx)."', '".addslashes($langAntique)."', '1', '0', '0', '0', '0' , 'ALWAYS', 'NO', NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR) )");
    claro_sql_query("INSERT INTO `".$TABLEQUIZQUESTIONLIST."` VALUES ( '1', '".addslashes($langSocraticIrony)."', '".addslashes($langManyAnswers)."', '10', '1', '2','')");
    claro_sql_query("INSERT INTO `".$TABLEQUIZQUESTION."` VALUES ( '1', '1')");

############################### LEARNING PATH  ####################################
  // HANDMADE module type are not used for first version of claroline 1.5 beta so we don't show any exemple!
  claro_sql_query("INSERT INTO `".$TABLELEARNPATH."` VALUES ('1', '".addslashes($langSampleLearnPath)."', '".addslashes($langSampleLearnPathDesc)."', 'OPEN', 'SHOW', '1')");
  
  claro_sql_query("INSERT INTO `".$TABLELEARNPATHMODULE."` VALUES ('1', '1', '1', 'OPEN', 'SHOW', '', '1', '0', '50')");
  claro_sql_query("INSERT INTO `".$TABLELEARNPATHMODULE."` VALUES ('2', '1', '2', 'OPEN', 'SHOW', '', '2', '0', '50')");

  claro_sql_query("INSERT INTO `".$TABLEMODULE."` VALUES ('1', '".addslashes($langSampleDocument)."', '".addslashes($langSampleDocumentDesc)."', 'PRIVATE', '1', 'DOCUMENT', '')");
  claro_sql_query("INSERT INTO `".$TABLEMODULE."` VALUES ('2', '".addslashes($langExerciceEx)."', '".addslashes($langSampleExerciseDesc)."', 'PRIVATE', '2', 'EXERCISE', '')");

  claro_sql_query("INSERT INTO `".$TABLEASSET."` VALUES ('1', '1', '/Example_document.pdf', '')");
  claro_sql_query("INSERT INTO `".$TABLEASSET."` VALUES ('2', '2', '1', '')");

############################## FORUMS  #######################################
    claro_sql_query("INSERT INTO `".$TABLEPHPBBTOPICS."` VALUES (1,'".addslashes($langExMessage)."',-1,'2001-09-18 20:25',1,'',1,1,'0','1', '".addslashes($nom)."', '".addslashes($prenom)."')");

    return 0;
};


/**
 * To create a record in the course tabale of main database
 * @param string    $courseId
 * @param string    $courseCode
 * @param string    $courseRepository
 * @param string    $courseDbName
 * @param string    $titulaires
 * @param string    $faculte
 * @param string    $intitule            complete name of course
 * @param string    $languageCourse        lang for this course
 * @param string    $uid                uid of owner
 * @global tables names
 * @global var lang
 * @global boolean defaultVisibilityForANewCourse default Visibility For A New Course
 * @author Christophe Gesch� <moosh@claroline.net>
 */

function register_course($courseSysCode, $courseScreenCode, $courseRepository, $courseDbName, $titular, $email, $faculte, $intitule, $languageCourse, $uidCreator, $expirationDate="")
{
    GLOBAL $TABLECOURSE, $TABLECOURSUSER, $DEBUG, $defaultVisibilityForANewCourse,
    $langCourseDescription,
    $langProfessor, $includePath,
    $courseTablePrefix, $dbGlu,
    $versionDb, $clarolineVersion;
  
    $okForRegisterCourse = TRUE;

    // Check if  I have all
    if ($courseSysCode== '')
    {
        claro_failure::set_failure('courseSysCode is missing');
        $okForRegisterCourse = FALSE;
    }
    if ($courseScreenCode== '')
    {
        claro_failure::set_failure('courseScreenCode is missing');
        $okForRegisterCourse = FALSE;
    }
    if ($courseDbName== '')
    {
        claro_failure::set_failure('courseDbName is missing');
        $okForRegisterCourse = FALSE;
    }
    if ($courseRepository == '')
    {
        claro_failure::set_failure('course Repository is missing');
        $okForRegisterCourse = FALSE;
    }
    if ($titular == '')
    {
        claro_failure::set_failure('titular is missing');
    }
    if ($email == '')
    {
        claro_failure::set_failure('email is missing');
    }
    if ($faculte=='')
    {
        claro_failure::set_failure('faculte is missing');
        $okForRegisterCourse = FALSE;
    }
    if ($intitule== '')
    {
        if ($courseScreenCode== '')
        {
            claro_failure::set_failure('intitule is missing');
            $okForRegisterCourse = FALSE;
        }
        else 
        {
            $intitule = $courseScreenCode;
        }
    }
    if ($languageCourse == '')
    {
        claro_failure::set_failure('language is missing');
        $languageCourse = 'english';
    }
    if ($uidCreator== '')
    {
        claro_failure::set_failure('uidCreator is missing');
        $okForRegisterCourse = FALSE;
    }

    if ($expirationDate=='')
    {
        $expirationDate = 'NULL';
    }
    else
    {
        $expirationDate = 'FROM_UNIXTIME('.$expirationDate.')';
    }

    if ($okForRegisterCourse)
    {
        if(file_exists($includePath . '/currentVersion.inc.php')) include ($includePath . '/currentVersion.inc.php');
        // here we must add 2 fields
        $sql ="INSERT INTO `" . $TABLECOURSE . "` SET
            code           = '" . $courseSysCode . "',
            dbName         = '" . $courseDbName . "',
            directory      = '" . $courseRepository . "',
            languageCourse = '" . $languageCourse . "',
            intitule       = '" . addslashes($intitule) . "',
            faculte        = '" . $faculte."',
            visible        = '" . $defaultVisibilityForANewCourse . "',
            diskQuota      = NULL,
            creationDate   = now(),
            expirationDate = '" . $expirationDate . "',
            versionDb      = '" . $versionDb."',
            versionClaro   = '" . $clarolineVersion . "',
            lastEdit       = now(),
            lastVisit      = NULL,
            titulaires     = '" . addslashes($titular) . "',
            email          = '" . addslashes($email) . "',
            fake_code      = '" . $courseScreenCode . "'";
        claro_sql_query($sql);
        $sql = "INSERT INTO `" . $TABLECOURSUSER . "` 
                SET code_cours     = '" . $courseSysCode . "',
                    user_id = '" . (int) $uidCreator."',
                    statut  = '1',
                    role    = '" . addslashes( $langProfessor ) . "',
                    tutor   = '1'";
        claro_sql_query($sql);
    }
    else //if ($okForRegisterCourse)
    {
        return false;
    }
    return true;
};

/**
 * Search and read archive.ini file add ins archive build by claroline
 * @param   $archive       string   COMPLETE path to archive.
 * @param   $isCompressed  boolean  whether archive would be unzip before read in
 * @author  Christophe Gesch� <moosh@claroline.net>
 * @version 1.0
 */

function read_properties_in_archive($archive, $isCompressed=TRUE)
{
    global $_uid;
    include_once (dirname(__FILE__) . '/pclzip/pclzip.lib.php');
    
    /*
    string tempnam ( string dir, string prefix)
    tempnam() cr�e un fichier temporaire unique dans le dossier dir. Si le dossier n'existe pas, tempnam() va g�n�rer un nom de fichier dans le dossier temporaire du syst�me.
    Avant PHP 4.0.6, le comportement de tempnam() d�pendait de l'OS sous-jacent. Sous Windows, la variable d'environnement TMP remplace le param�tre dir; sous Linux, la variable d'environnement TMPDIR a la priorit�, tandis que pour les OS en syst�me V R4, le param�tre dir sera toujours utilis�, si le dossier qu'il repr�sente existe. Consultez votre documentation pour plus de d�tails.
    tempnam() retourne le nom du fichier temporaire, ou la cha�ne NULL en cas d'�chec.
    */
    if ($isCompressed)
    {
    $zipFile = new pclZip($archive);
    $tmpDirName = dirname($archive) . '/tmp' . $_uid . uniqid($_uid);
    if (claro_mkdir( $tmpDirName, CLARO_FILE_PERMISSIONS, TRUE ) )
    {
        $zipFile->extract($tmpDirName);
    }
    else
    {
        die ('claro_mkdir error');
    }
    $pathToArchiveIni = dirname($tmpDirName) . '/archive.ini';
    }
    else
    {
        $pathToArchiveIni = dirname($archive) . '/archive.ini';
    }
    
//    echo $pathToArchiveIni;
    if (file_exists($pathToArchiveIni))
    {
        $courseProperties = parse_ini_file( $pathToArchiveIni );
    }
    else 
    {
        claro_failure::set_failure('ARCHI_INI_NOT_FOUND');
    }
    rmdir($tmpDirName);
    return $courseProperties;
};

/**
 *
 * @author Christophe Gesch� <moosh@claroline.net>
 *
 */
function claro_get_admin_list()
{
    $tbl_mdb_names = claro_sql_get_main_tbl();
    $tbl_admin     = $tbl_mdb_names['admin'];

    $sql = "SELECT `idUser` FROM `" . $tbl_admin . "`";
    return  claro_sql_query_fetch_all($sql);
}

?>
