<?php // $Id$
//----------------------------------------------------------------------
// CLAROLINE
//----------------------------------------------------------------------
// Copyright (c) 2001-2004 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

// Include library file

require '../../inc/claro_init_global.inc.php';
include($includePath."/lib/debug.lib.inc.php");
include($includePath."/lib/admin.lib.inc.php");

$nameTools = $langRestoreCourseRepository;

// Security Check

if ( !$is_platformAdmin ) claro_disp_auth_form();

// Execute command

if ( $_REQUEST['cmd'] == 'exRestore' )
{
    $tbl_mdb_names = claro_sql_get_main_tbl();
    
    $tbl_course = $tbl_mdb_names['course'];
    
    $sqlListCourses = " SELECT cours.code sysCode, directory coursePath ".
                      " FROM `". $tbl_course . "` " .
                      " ORDER BY sysCode";
    
    $res_listCourses = claro_sql_query($sqlListCourses);
    
    if (mysql_num_rows($res_listCourses))
    {
        $restored_courses =  "<ol>\n";
        
        while ($course = mysql_fetch_array($res_listCourses))
        {
            $currentcoursePathSys   = $coursesRepositorySys.$course["coursePath"]."/";
            $currentCourseIDsys = $course["sysCode"];
            
            if ( restore_course_repository($currentCourseIDsys,$currentcoursePathSys) )
            {
                $restored_courses .= "<li>" . sprintf("Course repository '%s' updated",$currentcoursePathSys) . "</li>\n";       
            }
        
        }
        $restored_courses .= "</ol>\n";
    }
}

// Display

// Deal with interbredcrumps  and title variable
$interbredcrump[]  = array ("url"=>$rootAdminWeb, "name"=> $langAdministration);

include($includePath . '/claro_init_header.inc.php');

claro_disp_tool_title($nameTools);

// display result

if (isset($restored_courses)) echo $restored_courses;

// display link to launch the restore

echo '<p><a href="' . $_SERVER['PHP_SELF'] . '?cmd=exRestore">' . $langLaunchRestoreCourseRepository . '</a></p>';

include($includePath . '/claro_init_footer.inc.php');

// Functions

function restore_course_repository($courseID,$courseRepository)
{

    global $clarolineRepositorySys, $includePath;

    if ( is_writable($courseRepository) )
    {
        umask(0);

        /*
            create directory for new tools of claroline 1.5 
        */
    
        if ( !is_dir($courseRepository) ) mkdir($courseRepository, 0777);
        if ( !is_dir($courseRepository . '/chat'          ) ) mkdir($courseRepository . '/chat'          , 0777);
        if ( !is_dir($courseRepository . '/modules'       ) ) mkdir($courseRepository . '/modules'       , 0777);
        if ( !is_dir($courseRepository . '/scormPackages' ) ) mkdir($courseRepository . '/scormPackages' , 0777);

        /**
         *    add $cidReq in index.php (Missing var in claroline 1.3)
         */

        // build index.php of course
        $fd = fopen( $courseRepository . '/index.php', 'w');

        // str_replace() removes \r that cause squares to appear at the end of each line
        $string=str_replace("\r","","<?"."php
              \$cidReq = \"$courseID\";
              \$claroGlobalPath = \"$includePath\";
              include(\"".$clarolineRepositorySys."course_home/course_home.php\");
    ?>");
        
        fwrite($fd, "$string");
        fclose($fd);
        $fd=fopen($courseRepository."/group/index.php", "w");
        $string="<"."?"."php"." session_start"."()"."; ?>";
        fwrite($fd, "$string");
        fclose($fd);        
        return 1;
    
    } else {
        printf ('repository %s not writable', $courseRepository);
        return 0;
    }

}

?>
