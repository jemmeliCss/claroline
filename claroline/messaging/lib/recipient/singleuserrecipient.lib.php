<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * singleuser recipient class
 *
 * @version     1.9 $Revision$
 * @copyright   (c) 2001-2014, Universite catholique de Louvain (UCL)
 * @author      Claroline Team <info@claroline.net>
 * @author      Christophe Mertens <thetotof@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html
 *              GNU GENERAL PUBLIC LICENSE version 2 or later
 * @package     internal_messaging
 */

//load recipientlist class
require_once __DIR__ . '/recipientlist.lib.php';

class SingleUserRecipient extends RecipientList
{
    private $userId;

    /**
     * create a single user adressee.
     *
     * @param int $userId user identification
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @see recipientList
     */
    public function getRecipientList()
    {
        return array($this->userId);
    }
    
    /**
     * @see RecpientList
     */
    protected function addRecipient($messageId,$userId)
    {
        $tableName = get_module_main_tbl(array('im_recipient'));
        
        $sql = "INSERT INTO `".$tableName['im_recipient']."` "
            . "(message_id, user_id, sent_to) \n"
            . "VALUES (" . (int)$messageId . ", " . (int)$userId . ", 'toUser')\n"
            ;
        claro_sql_query($sql);
    }
}
