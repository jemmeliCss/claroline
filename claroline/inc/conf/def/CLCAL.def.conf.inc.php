<?php // $Id$
$toolConf['label']='CLCAL';
$toolConf['section']['log']['label']='Track activity';

$toolConf['config_file']='agenda.conf.inc.php';
// $toolConf['config_repository']=''; dislabed = includePath.'/conf'

$toolConf['section']['log']['properties'] = 
array ( 'CONFVAL_LOG_CALENDAR_INSERT'
      , 'CONFVAL_LOG_CALENDAR_DELETE'
      , 'CONFVAL_LOG_CALENDAR_UPDATE'
      );

$toolConfProperties['CONFVAL_LOG_CALENDAR_INSERT'] = 
array ('label'       => 'Logguer les ajouts d\'agenda'
      ,'default'     => 'TRUE'
      ,'type'        => 'boolean'
      ,'display'     => TRUE
      ,'readonly'    => FALSE
      ,'technicalInfo' => 'lnqr qerg i qerio ijr�gihq�go  qriuhgh  qerighjq�eh� qre�i �ir�iohrq�g �oihqr g�ihj � �iqrg�ihq�e ihjg� �iohj�iher� g�ih �jqer�gihqmrhbg�porkjhgbuqh �ijhg �iqjre�ih �qer�piogj�ijher�oi �ihjnq�erhg�oih�i */ �ijh� �qirg�ih�ihrg �ijhq �ihg�qiher�gihq�ih qer�igjh� i�jhq�ioeg'
      ,'container'   => 'CONST'
      ,'acceptedval' => array ('TRUE'=>'enabled'
                              ,'FALSE'=>'dislabed'
                              )
      );
$toolConfProperties['CONFVAL_LOG_CALENDAR_DELETE'] = 
array ('label'       => 'Logguer les suppressions dans l\'agenda'
      ,'default'     => 'TRUE'
      ,'type'        => 'boolean'
      ,'display'     => TRUE
      ,'readonly'    => FALSE
      ,'container'   => 'CONST'
      ,'acceptedval' => array ('TRUE'=>'enabled'
                              ,'FALSE'=>'dislabed'
                              )
      );
      
$toolConfProperties['CONFVAL_LOG_CALENDAR_UPDATE'] = 
array ('label'       => 'Logguer les �ditions dans l\'agenda'
      ,'default'     => 'TRUE'
      ,'type'        => 'boolean'
      ,'display'     => TRUE
      ,'readonly'    => FALSE
      ,'container'   => 'CONST'
      ,'acceptedval' => array ('TRUE'=>'enabled'
                              ,'FALSE'=>'dislabed'
                              )
      );

?>