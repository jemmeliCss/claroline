<?php // $Id$
// TOOL
$toolConf['config_code']='CLUSR';
$toolConf['config_file']='user.conf.inc.php';
// $toolConf['config_repository']=''; dislabed = includePath.'/conf'
$toolConf['section']['list']['label']='Listing properties';
$toolConf['section']['list']['description']='common properties for listing of users';
$toolConf['section']['list']['properties'] = 
array ( 'linkToUserInfo'
      , 'nbUsersPerPage'
      , 'CONF_COURSEADMIN_IS_ALLOWED_TO_ADD_USER'
      );

//PROPERTIES

$toolConfProperties['linkToUserInfo'] =
array ('label'         => 'Afficher le lien vers les infos suppl�mentaires de l\'utilisateur'
      ,'default'       => 'TRUE'
      ,'type'          => 'boolean'
      ,'acceptedValue' => array ('TRUE'  => 'enabled'
                                ,'FALSE' => 'dislabed'
                                )
      );

$toolConfProperties['nbUsersPerPage'] = 
array ( 'label'   => 'Nombre d\'utilisateurs par page',
        'default' => '25',
        'type'    => 'integer');

$toolConfProperties['CONF_COURSEADMIN_IS_ALLOWED_TO_ADD_USER'] =
array('label'         => 'Le professeur peut-il ajouter des utilisateurs � son cours'
     ,'default'       => 'TRUE'
     ,'type'          => 'boolean'
     ,'display'       => TRUE
     ,'readonly'      => FALSE
     ,'container'     => 'CONST'
     ,'acceptedValue' => array ('TRUE'=>'oui'
                              ,'FALSE'=>'non'
                              )
     );

$toolConf['section']['fakeuser']['label']='Add fake user properties';
$toolConf['section']['fakeuser']['description']='this  tool allow to  fix some option for the dev tools user account generator';
$toolConf['section']['fakeuser']['properties'] = 
array ( 'DEFAULT_SUFFIX_MAIL'
      , 'DEFAULT_NUMBER_CREATED_USERS'
      , 'DEFAULT_QTY_TEACHER'
      , 'DEFAULT_QTY_STUDENT'
      , 'ADD_FIRSTNAMES_FROM_BASE'
      , 'ADD_NAMES_FROM_BASE'
      , 'ADD_USERNAMES_FROM_BASE'
      , 'USE_FIRSTNAMES_AS_LASTNAMES'
      , 'CONFVAL_LIST_USER_ADDED'
      );

$toolConfProperties['DEFAULT_SUFFIX_MAIL'] =
array ( 'label'         => 'extention des emails g�n�r�s'
      , 'description'   => 'la partie avant sera g�n�r�e al�atoirement'
      , 'default'       => '@fake.zz'
      , 'type'          => 'regexp'
      , 'acceptedValue' => '@(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-z][0-9a-z-]*[0-9a-z]\.)+[a-z]{2,4})$'
      , 'container'     => 'CONST'
      );

$toolConfProperties['DEFAULT_NUMBER_CREATED_USERS'] =
array('label'       => 'le nombre de comptes g�n�r�s'
     ,'default'     => '100'
     ,'type'        => 'integer'
     ,'container'   => 'CONST'
     );

$toolConfProperties['DEFAULT_QTY_STUDENT'] =
array ( 'label'     => 'le nombre de comptes user g�n�r�s'
      , 'default'   => '5'
      , 'type'      => 'integer'
      , 'container' => 'CONST'
      );

$toolConfProperties['DEFAULT_QTY_TEACHER'] =
array ( 'label'     => 'le nombre de comptes cr�ateur de cours g�n�r�s'
      , 'default'   => '0'
      , 'type'      => 'integer'
      , 'container' => 'CONST'
      );

$toolConfProperties['ADD_FIRSTNAMES_FROM_BASE'] =
array ( 'label'         => 'cr�er les pr�noms � partir de ceux qui existent dans la base'
      , 'default'       => 'TRUE'
      , 'type'          => 'boolean'
      , 'container'     => 'CONST'
      , 'acceptedValue' => array ('TRUE'=>'oui'
                                 ,'FALSE'=>'non'
                                 )
      );

$toolConfProperties['ADD_NAMES_FROM_BASE'] =
array ( 'label'         => 'cr�er les noms � partir de ceux qui existent dans la base'
      , 'default'       => 'FALSE'
      , 'type'          => 'boolean'
      , 'container'     => 'CONST'
      , 'acceptedValue' => array ('TRUE'=>'oui'
                                 ,'FALSE'=>'non'
                                 )
      );

$toolConfProperties['ADD_USERNAMES_FROM_BASE'] = 
array ( 'label'         => 'cr�er les noms de compte � partir de ceux '
                          .'qui existent dans la base'
      , 'default'       => 'TRUE'
      , 'type'          => 'boolean'
      , 'container'     => 'CONST'
      , 'acceptedValue' => array ('TRUE'=>'oui'
                                 ,'FALSE'=>'non'
                                 )
      );

$toolConfProperties['USE_FIRSTNAMES_AS_LASTNAMES'] =
array ( 'label'         => 'Cr�er des noms � partir les pr�noms'
      , 'default'       => 'TRUE'
      , 'type'          => 'boolean'
      , 'container'     => 'CONST'
      , 'acceptedValue' => array ('TRUE'=>'oui'
                                 ,'FALSE'=>'non'
                                 )
      );

$toolConfProperties['CONFVAL_LIST_USER_ADDED'] = 
array ( 'label'         => 'Show list of new users'
      , 'default'       => 'TRUE'
      , 'type'          => 'boolean'
      , 'container'     => 'CONST'
      , 'acceptedValue' => array ('TRUE'=>'yes'
                                 ,'FALSE'=>'no'
                                 )
      );
?>