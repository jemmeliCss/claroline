<?php # -$Id$

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

$authSourceName = 'ganesha';
$authSourceType = 'DB';

// Define the Auth driver options

$extAuthOptionList = array(

    // PUT HERE THE CORRECT DSN FOR YOUR DB SYSTEM
    'dsn'         => 'mysql://dbuser:dbpassword@domaine/ganesha',

    'table'       => 'membres', // warning ! table prefix can change from one system to another 
    'usernamecol' => 'login',
    'passwordcol' => 'password',
    'db_fields'   => array('prenom', 'nom', 'type', 'email'),
    'cryptType'   => 'none'
);


// Link additionnal external authentication attributes to the Claroline 
// user attribute.
//
// array KEYS   are the Claroline attributes and
// array VALUES are the authentication external attributes.

$extAuthAttribNameList = array (
    'lastname'  => 'nom',
    'firstname' => 'prenom',
    'email'     => 'email',
    'status'    => 'type'
);

// Array setting optionnal preliminary treatment to the data retrieved from the 
// exernal authentication source. Array KEYS are the concernend claroline 
// user table fields, and Array VALUES are either the name of a function which 
// makes the treatment or simply a default value to insert
// Note. Treatments doesn't necessary previously require data from the external 
// authentication system. They're able to be trigged from NULL value ...

$extAuthAttribTreatmentList = array ('status' => 'manage_user_status_from_ganesha_to_claroline');

function manage_user_status_from_ganesha_to_claroline($ganeshaStatus)
{
    switch ($ganeshaStatus)
    {
        case 2:       // ganesha administrator
            return 1; // claroline course manager
            break;

        case 3:       // ganesha tutor
            return 1; // claroline course manager

        case 0:       // ganesha trainee
            return 5; // claroline simple user
        default:
            return 5;
    }
}


// PROCESS AUTHENTICATION

require $clarolineRepositorySys.'/auth/extauth/extAuthProcess.inc.php';

?>