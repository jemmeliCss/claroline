<?php # $Id$ 

//---------------------------------------------------------------------- 
// CLAROLINE 
//---------------------------------------------------------------------- 
// Copyright (c) 2001-2003 Universite catholique de Louvain (UCL) 
//---------------------------------------------------------------------- 
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL) 
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available 
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html 
//---------------------------------------------------------------------- 
// Authors: see 'credits' file 
//---------------------------------------------------------------------- 


/*====================================== 
	   CLAROLINE MAIN 
  ======================================*/ 

//$langFile = "langFile"; // name of the lang file which needs to be included 
					  //'inc.php' is automatically appended to the file name 

include('../include/claro_init_global.inc.php'); // settings initialisation 

// Optional : If you need to add some HTTP/HTML headers code 
// $httpHeadXtra[] = ""; 
// $httpHeadXtra[] = ""; 
//    ... 
// 
// $htmlHeadXtra[] = ""; 
// $htmlHeadXtra[] = ""; 
//    ... 

$nameTools = ""; // title of the page (comes from the language file) 

$QUERY_STRING=''; // used for the breadcrumb 
				  // when one needs to add a parameter after the filename 

include('../include/claro_init_header.inc.php'); 

/*======================================*/ 


// PUT YOUR CODE HERE ... 
echo "<h1><center>Hello world!<center></h1>";



/*====================================== 
	   CLAROLINE FOOTER 
  ======================================*/ 

include($includePath."/claro_init_footer.inc.php"); 

?>