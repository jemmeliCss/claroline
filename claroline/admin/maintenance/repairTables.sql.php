<?php // $Id$


/*
 * This  script   search tables  of db and  create sql to run a mysql repair of table.
 */
 
if  (!is_array($tableToRepair))
{
	$sql_getTablesNames = "SHOW TABLES";
	$res_getTablesNames = mysql_query($sql_getTablesNames);
	if($res_getTablesNames)
	{
		while ($getTablesNames = mysql_fetch_array($res_getTablesNames)) 
		{
			$tableToRepair[] = $getTablesNames[0];
		}		
	}
}


if  (is_array($tableToRepair))
{
	reset($tableToRepair);
	while(list($count,$tableName)=each($tableToRepair))
	$sqlForUpdate[] = "REPAIR TABLE  `".$tableName."`";
}




?>