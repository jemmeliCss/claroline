<?php # $Id$
die("ce fichier de def n'est pas du tout pr�t");

/***** INDEX CONFIG */

//******************************************* Cat LIST option
define("CONFVAL_showNodeEmpty",TRUE);
define("CONFVAL_showNumberOfChild",TRUE); // actually  count are only for direct childs
define("CONFVAL_ShowLinkBackToTopOfTree",false);

//******************************************* Course List Option
define("CONFVAL_showCourseLangIfNotSameThatPlatform",TRUE);

//******************************************* preview of course content
// to dislab all  set CONFVAL_maxTotalByCourse = 0
define("CONFVAL_maxValvasByCourse",3); // Maximum number of entries
define("CONFVAL_maxAgendaByCourse",1); //  collected from each course
define("CONFVAL_maxTotalByCourse",4); //  and displayed in summary.
define("CONFVAL_NB_CHAR_FROM_CONTENT",80);
// order to sort datas
$orderKey =array("keyTools","keyTime","keyCourse"); // Best Choice
//$orderKey =array("keyTools","keyCourse","keyTime");
//$orderKey =array("keyCourse","keyTime","keyTools");
//$orderKey =array("keyCourse","keyTools","keyTime");

define("CONFVAL_showExtractInfo",SCRIPTVAL_UnderCourseList);
									// SCRIPTVAL_InCourseList    // /best choice if $orderKey[0]="keyCourse"
									// SCRIPTVAL_UnderCourseList //best choice
									// SCRIPTVAL_Both // probably only for debug
//$dateFormatForInfosFromCourses = $dateFormatShort;
$dateFormatForInfosFromCourses = $dateFormatLong;

//define("CONFVAL_limitPreviewTo",SCRIPTVAL_NewEntriesOfTheDay);
//define("CONFVAL_limitPreviewTo",SCRIPTVAL_NoTimeLimit);
define("CONFVAL_limitPreviewTo",SCRIPTVAL_NewEntriesOfTheDayOfLastLogin);

?>