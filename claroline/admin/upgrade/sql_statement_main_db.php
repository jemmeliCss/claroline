<?php // $Id$
/**
 * CLAROLINE
 *
 * Sql query to update main database
 *
 * @version  1.6 $Revision$
 * 
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 * 
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE
 *
 * @see http://www.claroline.net/wiki/index.php/Upgrade_claroline_1.6
 *
 * @package UPGRADE
 * 
 * @author Claro Team <cvs@claroline.net>
 * @author Mathieu Laurent   <mla@claroline.net>
 * @author Christophe Gesch� <moosh@claroline.net>
 *
 */

/*===========================================================================
 Upgrade to claroline 1.6
 ===========================================================================*/

function query_to_upgrade_main_database_to_16 ()
{
    $tbl_mdb_names = claro_sql_get_main_tbl();

    $lenForDbNameOfACourse = 20 + 30; // (max for prefix + max  for code course);

    // Update table admin 
    $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $tbl_mdb_names['admin'] . "` CHANGE `idUser` `idUser` int(11) unsigned NOT NULL default '0'";
    
    // Create new table class
    $sqlForUpdate[] = "CREATE TABLE IF NOT EXISTS `" . $tbl_mdb_names['class'] . "` (
      `id` int(11) NOT NULL auto_increment,
      `name` varchar(100) NOT NULL default '',
      `class_parent_id` int(11) default NULL,
      `class_level` int(11) NOT NULL default '0',
      PRIMARY KEY  (`id`)
    ) TYPE=MyISAM";
    
    // Create new table rel_class_user
    $sqlForUpdate[] = "CREATE TABLE IF NOT EXISTS `" . $tbl_mdb_names['rel_class_user'] . "` (
      `id` int(11) NOT NULL auto_increment,
      `user_id` int(11) NOT NULL default '0',
      `class_id` int(11) NOT NULL default '0',
      PRIMARY KEY  (`id`)
    ) TYPE=MyISAM";
    
    // Update table user_id
    $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $tbl_mdb_names['user'] . "` CHANGE `user_id` `user_id` int(11) unsigned NOT NULL auto_increment" ;
    $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $tbl_mdb_names['user'] . "` CHANGE `creatorId` `creatorId` int(11) unsigned default NULL" ;
    
    // Update table cours
    $sqlForUpdate[] = " ALTER IGNORE TABLE `" . $tbl_mdb_names['course'] . "` CHANGE `dbName` `dbName` varchar(".$lenForDbNameOfACourse.") default NULL";
    
    // Create new table config_file
    $sqlForUpdate16[] = "CREATE TABLE IF NOT EXISTS `" . $tbl_mdb_names['config_file'] . "` (
      `config_code` varchar(30) NOT NULL default '',
      `config_hash` varchar(40) NOT NULL default '',
      PRIMARY KEY  (`config_code` )
    ) TYPE=MyISAM  AVG_ROW_LENGTH=48";
    
    
    // Create new table sso
    $sqlForUpdate[] = "CREATE TABLE IF NOT EXISTS`" . $tbl_mdb_names['sso'] . "` (
      `id` int(11) NOT NULL auto_increment,
      `cookie` varchar(255) NOT NULL default '',
      `rec_time` datetime NOT NULL default '0000-00-00 00:00:00',
      `user_id` int(11) NOT NULL default '0',
      PRIMARY KEY  (`id`)
    ) TYPE=MyISAM";
    
    // Update course tool icon 
    $sqlForUpdate[] = "UPDATE `" . $tbl_mdb_names['tool'] . "` SET `icon` = 'announcement.gif' WHERE `claro_label` = 'CLANN___'";
    $sqlForUpdate[] = "UPDATE `" . $tbl_mdb_names['tool'] . "` SET `icon` = 'assignment.gif' WHERE `claro_label` = 'CLWRK___'";
    $sqlForUpdate[] = "UPDATE `" . $tbl_mdb_names['tool'] . "` SET `icon` = 'chat.gif' WHERE `claro_label` = 'CLCHT___'";
    $sqlForUpdate[] = "UPDATE `" . $tbl_mdb_names['tool'] . "` SET `icon` = 'document.gif' WHERE `claro_label` = 'CLDOC___'";
    $sqlForUpdate[] = "UPDATE `" . $tbl_mdb_names['tool'] . "` SET `icon` = 'learnpath.gif' WHERE `claro_label` = 'CLLNP___'";
    $sqlForUpdate[] = "UPDATE `" . $tbl_mdb_names['tool'] . "` SET `icon` = 'user.gif' WHERE `claro_label` = 'CLUSR___'";

    return $sqlForUpdate;

}

/*===========================================================================
 Upgrade to claroline 1.7
 ===========================================================================*/

function query_to_upgrade_main_database_to_17 ()
{
    $tbl_mdb_names = claro_sql_get_main_tbl();

    // create notification table
    $sqlForUpdate[] = "CREATE TABLE IF NOT EXISTS `" . $tbl_mdb_names['notify'] . "` (
      `id` int(11) NOT NULL auto_increment,
      `course_code` varchar(40) NOT NULL default '0',
      `tool_id` int(11) NOT NULL default '0',
      `ressource_id` varchar(255) NOT NULL default '0',
      `group_id` int(11) NOT NULL default '0',
      `user_id` int(11) NOT NULL default '0',
      `date` datetime default '0000-00-00 00:00:00',
      PRIMARY KEY  (`id`),
      KEY `course_id` (`course_code`)
    ) TYPE=MyISAM";

    // add enrollment key
    $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $tbl_mdb_names['course'] . "` ADD `enrollment_key` varchar(255) default NULL";

    // remove old columns : cahier_charges, scoreShow, description
    $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $tbl_mdb_names['course'] . "` DROP COLUMN `cahier_charges`";
    $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $tbl_mdb_names['course'] . "` DROP COLUMN `scoreShow`";
    $sqlForUpdate[] = "ALTER IGNORE TABLE `" . $tbl_mdb_names['course'] . "` DROP COLUMN `description`";

    return $sqlForUpdate;
}

?>
