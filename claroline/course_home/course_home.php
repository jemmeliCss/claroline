<?php # $Id$

//----------------------------------------------------------------------
// CLAROLINE 1.6
//----------------------------------------------------------------------
// Copyright (c) 2001-2004 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available 
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

$langFile = 'course_info';
$course_homepage = TRUE;

// If user is here, it means he isn't neither in specific group space 
// nor a specific course tool now. So it's careful to to reset the group 
// and tool settings

$gidReset = true; 
$tidReset = true;
if ((bool) stristr($_SERVER['PHP_SELF'],"course_home"))
die("---");

if ( !isset($claroGlobalPath) ) $claroGlobalPath = '../claroline/inc';
require $claroGlobalPath.'/claro_init_global.inc.php';

$toolRepository = $clarolineRepositoryWeb;
$imgRepository  = $clarolineRepositoryWeb."/img/";

include($includePath.'/claro_init_header.inc.php');
include($includePath.'/lib/course_home.lib.php');

if ( ! $is_courseAllowed) claro_disp_auth_form();


/*
 * Tracking - Count only one time by course and by session
 */
// following instructions are used to prevent statistics to be recorded more than needed
// for course access
// check if the user as already visited this course during this session (
if ( ! isset($_SESSION['tracking']['coursesAlreadyVisited'][$_cid]))
{
    @include($includePath."/lib/events.lib.inc.php");
    event_access_course();
    $_SESSION['tracking']['coursesAlreadyVisited'][$_cid] = 1;
}
// for tool access
// unset the label of the last visited tool 
unset($_SESSION['tracking']['lastUsedTool']);
// end of tracking

?>
<table border="0" cellspacing="10" cellpadding="10" width="100%">
<tr>
<td valign="top" style="border-right: gray solid 1px;" width="220">
<?php

/*
 * Language initialisation of the tool names
 */

$toolNameList = array('CLANN___' => $langAnnouncement,
                      'CLFRM___' => $langForums,
                      'CLCAL___' => $langAgenda,
                      'CLCHT___' => $langChat,
                      'CLDOC___' => $langDocument,
                      'CLDSC___' => $langDescriptionCours,
                      'CLGRP___' => $langGroups,
                      'CLLNP___' => $langLearningPath,
                      'CLQWZ___' => $langExercises,
                      'CLWRK___' => $langWork,
                      'CLUSR___' => $langUsers);

/*
 * Initialisation for the access level types
 */

$accessLevelList = array('ALL'            => 0, 
                         'COURSE_MEMBER'  => 1, 
                         'GROUP_TUTOR'    => 2, 
                         'COURSE_ADMIN'   => 3, 
                         'PLATFORM_ADMIN' => 4);




/*----------------------------------------------------------------------------
                                   TOOL LIST
  ----------------------------------------------------------------------------*/

if     ($is_courseAdmin  )   $is_allowedToEdit = true;
else    $is_allowedToEdit = false;

if     ($is_platformAdmin)   $reqAccessLevel   = 'PLATFORM_ADMIN';
elseif ($is_courseAdmin  )   $reqAccessLevel   = 'COURSE_ADMIN';
else                         $reqAccessLevel   = 'ALL';

$toolList = get_course_tool_list($reqAccessLevel);

foreach($toolList as $thisTool)
{
    if ( ! empty($thisTool['label']))   // standart claroline tool
    {
        $toolName = $toolNameList[ $thisTool['label'] ];
        $url      =  trim($toolRepository.$thisTool['url']);
    }
    elseif( ! empty($thisTool['name']) ) // external tool added by course manager
    {
        $toolName = $thisTool['name'];
        $url      = trim($thisTool['url']);
    }
    else
    {
        $toolName = '<i>no name</i>';
        $url      = trim($thisTool['url']);
    }

    if (! empty($thisTool['icon']))
    {
        $icon = $imgRepository.$thisTool['icon'];
    }
    else
    {
    	$icon = $imgRepository.'external.gif';
    }

    if ($accessLevelList[$thisTool['access']] > $accessLevelList['ALL'])
    {
        $style = ' class ="invisible" ';
    }
    else
    {
        $style = '';
    }

    if ( ! empty($url) )
    {
        echo "<a $style href=\"".$url."\">"
            ."<img src=\"".$icon."\" hspace=\"5\" alt=\"\">".$toolName
            ."</a>"
            ."<br>\n";
    }
    else
    {
        echo "<span".$style.">"
            ."<img src=\"".$icon."\" alt=\"\">"
            .$toolName
            ."</span><br>\n";
    }
}

if ($is_allowedToEdit)
{
    echo '<p>';
    claro_disp_button($clarolineRepositoryWeb."course_home/course_home_edit.php",
                      "<img align=\"absbottom\" src=\"".$imgRepository."edit.gif\" alt=\"\">"
                      .$langEditToolList);
    echo '</p><p></p>'; // the empty <p></p> is necessary otherwise the button
                        // bottom is bitten in IE

}

?>
</td>
<td width="20">
&nbsp;
</td>
<td valign="top">
<?php




/*----------------------------------------------------------------------------
                         INTRODUCTION TEXT SECTION
  ----------------------------------------------------------------------------*/

// the module id for course_home equal -1 (course_home is not a tool in tool_list)
$moduleId = -1;
$helpAddIntroText=$langIntroCourse;
include($includePath."/introductionSection.inc.php");
?>
</td>
</tr>
</table>


<?php
/*----------------------------------------------------------------------------
                         COURSE ADMINISTRATION SECTION
  ----------------------------------------------------------------------------*/

if ($is_allowedToEdit)
{
    echo "<hr noshade size=\"1\">";

    echo "Course Administration :&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "<img src=\"".$imgRepository."/referencement.gif\" alt=\"\">";
    echo "<a href=\"".$toolRepository."course_info/infocours.php\">".$langCourseSettings."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "<img src=\"".$imgRepository."/statistiques.gif\" alt=\"\">";

    echo "<a href=\"".$toolRepository."tracking/courseLog.php\">".$langStatistics."</a>";

}


@include $includePath.'/claro_init_footer.inc.php';

?>
