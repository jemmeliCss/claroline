<?php // $Id$

// vim: expandtab sw=4 ts=4 sts=4:

/**
 * Authentication Manager
 *
 * @version     1.9 $Revision$
 * @copyright   2001-2008 Universite catholique de Louvain (UCL)
 * @author      Claroline Team <info@claroline.net>
 * @author      Frederic Minne <zefredz@claroline.net>
 * @license     http://www.gnu.org/copyleft/gpl.html
 *              GNU GENERAL PUBLIC LICENSE version 2 or later
 * @package     kernel.auth
 */

// Get required libraries
FromKernel::uses('core/claroline.lib','database/database.lib','kernel/user.lib');

class AuthManager
{
    public function authenticate( $username, $password )
    {
        if ( $authSource = self::getAuthSource( $username ) )
        {
            Console::debug("Found authentication source {$authSource}");
            $driverList = array( AuthDriverManager::getDriver( $authSource ) );
        }
        else
        {
            $authSource = null;
            $driverList = AuthDriverManager::getRegisteredDrivers();
        }
        
        foreach ( $driverList as $driver )
        {
            if ( $driver->authenticate( $username, $password ) )
            {
                if ( $uid = self::registered( $username, $authSource ) )
                {
                    $driver->update( $uid );
                    
                    return $driver->getUser();
                }
                else
                {
                    $driver->register();
                    
                    return $driver->getUser();
                }
            }
        }
        
        // authentication failed
        return false;
    }
    
    public static function registered( $username, $authSourceName = null )
    {
        if ( empty( $authSourceName ) )
        {
            return false;
        }
        
        $tbl = claro_sql_get_main_tbl();
        
        $sql = "SELECT user_id\n"
            . "FROM `{$tbl['user']}`\n"
            . "WHERE "
            . ( get_conf('claro_authUsernameCaseSensitive',true) ? 'BINARY ' : '')
            . "username = ". Claroline::getDatabase()->quote($username) . "\n"
            . "AND\n"
            . "authSource = " . Claroline::getDatabase()->quote($authSourceName)
            ;
            
        $res = Claroline::getDatabase()->query( $sql );
        
        if ( $res->numRows() )
        {
            return $res->fetch(Database_ResultSet::FETCH_VALUE);
        }
        else
        {
            return false;
        }
    }
    
    public static function getAuthSource( $username )
    {
        $tbl = claro_sql_get_main_tbl();
        
        $sql = "SELECT authSource\n"
            . "FROM `{$tbl['user']}`\n"
            . "WHERE "
            . ( get_conf('claro_authUsernameCaseSensitive',true) ? "BINARY " : "" )
            . "username = ". Claroline::getDatabase()->quote($username)
            ;
            
        return Claroline::getDatabase()->query( $sql )->fetch(Database_ResultSet::FETCH_VALUE);
    }
}

class AuthDriverManager
{
    protected static $drivers = false;
    
    public static function getRegisteredDrivers()
    {
        if ( ! self::$drivers )
        {
            self::initDriverList();
        }
        
        return  self::$drivers;
    }
    
    public static function getDriver( $authSource )
    {
        if ( ! self::$drivers )
        {
            self::initDriverList();
        }
        
        if ( array_key_exists( $authSource, self::$drivers ) )
        {
            return self::$drivers[$authSource];
        }
        else
        {
            throw new Exception("No auth driver found for {$authSource} !");
        }
    }
    
    protected static function initDriverList()
    {
        // todo : get from config
        self::$drivers = array(
            'claroline' => new ClarolineLocalAuthDriver(),
            'clarocrypted' => new ClarolineLocalAuthDriver(true)
        );
        
        if ( ! file_exists ( get_path('rootSys') . 'platform/conf/extauth' ) )
        {
            FromKernel::uses('fileManage.lib');
            claro_mkdir(get_path('rootSys') . 'platform/conf/extauth', CLARO_FILE_PERMISSIONS, true );
        }
        
        $it = new DirectoryIterator( get_path('rootSys') . 'platform/conf/extauth' );
        
        $driverConfig = array();
        
        foreach ( $it as $file )
        {
            if ( $file->isFile() )
            {
                include $file->getPathname();
                
                if ( $driverConfig['driver']['enabled'] == true )
                {
                    if ( $driverConfig['driver']['class'] == 'PearAuthDriver' )
                    {
                        self::$drivers[$driverConfig['driver']['authSourceName']] = PearAuthDriver::fromConfig( $driverConfig );
                    }
                    else
                    {
                        if ( class_exists( $driverConfig['driver']['class'] ) )
                        {
                            $driverClass = $driverConfig['driver']['class'];
                            
                            self::$drivers[$driverConfig['driver']['authSourceName']] = new $driverClass( $driverConfig );
                        }
                        else
                        {
                            if ( claro_debug_mode() )
                            {
                                throw new Exception("Driver class {$driverClass} not found");
                            }
                            
                            Console::error( "Driver class {$driverClass} not found" );
                        }
                    }
                }
            }
            
            $driverConfig = array();
        }
    }
}

abstract class AbstractAuthDriver
{
    protected $userId = null;
    protected $extAuthIgnoreUpdateList = array();
    
    abstract public function getUserData();
    
    protected function registerUser( $userAttrList, $uid = null )
    {
        $preparedList = array();
        
        // Map database fields
        $dbFieldToClaroMap = array(
            'nom' => 'lastname',
            'prenom' => 'firstname',
            'username' => 'loginName',
            'email' => 'email',
            'officialCode' => 'officialCode',
            'phoneNumber' => 'phoneNumber',
            'isCourseCreator' => 'isCourseCreator',
            'authSource' => 'authSource');
            
        foreach ( $dbFieldToClaroMap as $dbFieldName => $claroAttribName )
        {
            if ( ! is_null($userAttrList[$claroAttribName])
                && ( !$uid || !in_array($claroAttribName, $this->extAuthIgnoreUpdateList ) ) )
            {
                $preparedList[] = $dbFieldName
                    . ' = '
                    . Claroline::getDatabase()->quote($userAttrList[$claroAttribName])
                    ;
            }
        }
        
        $tbl = claro_sql_get_main_tbl();
        
        $sql = ( $uid ? 'UPDATE' : 'INSERT INTO' ) 
            . " `{$tbl['user']}`\n"
            . "SET " . implode(",\n", $preparedList ) . "\n"
            . ( $uid ? "WHERE  user_id = " . (int) $uid : '' )
            ;
        
        try
        {
            Claroline::getDatabase()->exec($sql);
            
            $this->userId = $uid ? $uid : Claroline::getDatabase()->insertId();
            
            return $this->userId;
        }
        catch( Exception $e )
        {
            throw new Exception("Fail to insert or update user in database !!!!");
        }
    }
    
    public function getUser()
    {
        if ( $this->getUSerId() )
        {
            return Claro_CurrentUser::getInstance($this->getUserId());
        }
        else
        {
            return null;
        }
    }
    
    public function update( $uid )
    {
        $this->userId = $this->registerUser( $this->getUserData(), $uid );
    }
    
    public function register()
    {
        $this->userId = $this->registerUser( $this->getUserData() );
    }
    
    public function getUserId()
    {
        return $this->userId;
    }
}

class ClarolineLocalAuthDriver extends AbstractAuthDriver
{
    protected $alwaysCrypted = false;
    
    public function __construct( $alwaysCrypted = false )
    {
        $this->alwaysCrypted = $alwaysCrypted;
    }
    
    public function authenticate( $username, $password )
    {
        $tbl = claro_sql_get_main_tbl();
        
        $sql = "SELECT user_id, username, password, authSource\n"
            . "FROM `{$tbl['user']}`\n"
            . "WHERE "
            . ( get_conf('claro_authUsernameCaseSensitive',true) ? 'BINARY ' : '')
            . "username = ". Claroline::getDatabase()->quote($username)
            ;
            
        $userDataList = Claroline::getDatabase()->query( $sql );
        
        if ( $userDataList->numRows() > 0 )
        {
            foreach ( $userDataList as $userData )
            {
                if ( $this->alwaysCrypted || get_conf('userPasswordCrypted',false) )
                {
                    $password = md5($password);
                }
                
                if ( $password === $userData['password'] )
                {
                    $this->userId = $userData['user_id'];
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        else
        {
            return false;
        }
    }
    
    public function getUserData()
    {
        return $this->getUser()->getRawData();
    }
    
    public function registered()
    {
        $userId = $this->getUserId();
        return !empty($userId);
    }
    
    public function update()
    {
        return $this->getUserId();
    }
    
    public function register()
    {
        return $this->getUserId();
    }
}

class PearAuthDriver extends AbstractAuthDriver
{
    protected $authType;
    protected $authSourceName;
    protected $extAuthOptionList; 
    protected $extAuthAttribNameList;
    protected $extAuthAttribTreatmentList;
    
    protected $auth;
    
    public function __construct( 
        $authType,
        $authSourceName,
        $extAuthOptionList,
        $extAuthAttribNameList,
        $extAuthAttribTreatmentList,
        $extAuthIgnoreUpdateList = array() )
    {
        $this->authType = $authType;
        $this->authSourceName = $authSourceName;
        $this->extAuthOptionList = $extAuthOptionList;
        $this->extAuthAttribNameList = $extAuthAttribNameList;
        $this->extAuthAttribTreatmentList = $extAuthAttribTreatmentList;
        $this->extAuthIgnoreUpdateList = $extAuthIgnoreUpdateList;
    }
    
    public function authenticate( $username, $password )
    {
        $_POST['username'] = $username;
        $_POST['password'] = $password;
        
        if ( $this->authType === 'LDAP')
        {
            // CASUAL PATCH (Nov 21 2005) : due to a sort of bug in the
            // PEAR AUTH LDAP container, we add a specific option wich forces
            // to return attributes to a format compatible with the attribute
            // format of the other AUTH containers

            $this->extAuthOptionList ['attrformat'] = 'AUTH';
        }
        
        require_once 'Auth/Auth.php';

        $this->auth = new Auth( $this->authType, $this->extAuthOptionList, '', false);

        $this->auth->start();
        
        return $this->auth->getAuth();
    }
    
    public function getUserData()
    {
        $userAttrList = array('lastname'     => NULL,
                          'firstname'    => NULL,
                          'loginName'    => NULL,
                          'email'        => NULL,
                          'officialCode' => NULL,
                          'phoneNumber'  => NULL,
                          'isCourseCreator' => NULL,
                          'authSource'   => NULL);

        foreach($this->extAuthAttribNameList as $claroAttribName => $extAuthAttribName)
        {
            if ( ! is_null($extAuthAttribName) )
            {
                $userAttrList[$claroAttribName] = $this->auth->getAuthData($extAuthAttribName);
            }
        }
        
        foreach($userAttrList as $claroAttribName => $claroAttribValue)
        {
            if ( array_key_exists($claroAttribName, $this->extAuthAttribTreatmentList ) )
            {
                $treatmentCallback = $this->extAuthAttribTreatmentList[$claroAttribName];

                if ( is_callable( $treatmentCallback ) )
                {
                    $claroAttribValue = $treatmentCallback($claroAttribValue);
                }
                else
                {
                    $claroAttribValue = $treatmentCallback;
                }
            }

            $userAttrList[$claroAttribName] = $claroAttribValue;
        } // end foreach

        /* Two fields retrieving info from another source ... */

        $userAttrList['loginName' ] = $this->auth->getUsername();
        $userAttrList['authSource'] = $this->authSourceName;
        
        if ( isset($userAttrList['status']) )
        {
            $userAttrList['isCourseCreator'] = ($userAttrList['status'] == 1) ? 1 : 0;
        }
        
        return $userAttrList;
    }
    
    public static function fromConfig( $driverConfig )
    {
        $driver = new self(
            $driverConfig['driver']['authSourceType'],
            $driverConfig['driver']['authSourceName'],
            $driverConfig['extAuthOptionList'],
            $driverConfig['extAuthAttribNameList'],
            $driverConfig['extAuthAttribTreatmentList'],
            $driverConfig['extAuthAttribToIgnore']
        );
        
        return $driver;
    }
}
