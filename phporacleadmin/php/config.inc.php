<?php
/***************************************************************************
    begin                : Sam Nov 25 18:08:45 CET 2000
    copyright            : (C) 2000 by Thomas Fromm
    email                : tf@tfromm.com
 ***************************************************************************/

/***************************************************************************
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/


// Database default
// additional databases in the same way


$DBUser_C[0]="florad";// (e.g. Scott)
$DBPass_C[0]="inubit";// (e.g. Tiger)
$DBName_C[0]="flora.inubit";// (e.g. database.home)

$DBUser_C[1]="geod";// (e.g. Scott) 
$DBPass_C[1]="inubit";// (e.g. Tiger) 
$DBName_C[1]="geo.inubit";// (e.g. database.home)

$DBUser_C[2]="berod";
$DBPass_C[2]="inubit";
$DBName_C[2]="bero.inubit";

$DBUser_C[3]="regusd";      
$DBPass_C[3]="inubit";  
$DBName_C[3]="regus.inubit";  

$DBUser_C[4]="trvisiond";  
$DBPass_C[4]="inubit";      
$DBName_C[4]="trvision.inubit"; 

$DBUser_C[5]="geox"; 
$DBPass_C[5]="inubit"; 
$DBName_C[5]="geo.inubit";

$DBUser_C[6]="florax"; 
$DBPass_C[6]="inubit"; 
$DBName_C[6]="flora.inubit"; 

$DBUser_C[7]="ibis_test";
$DBPass_C[7]="inubit";
$DBName_C[7]="regus.inubit";

$DBUser_C[8]="floray";
$DBPass_C[8]="inubit";
$DBName_C[8]="flora.inubit";

// enables display of executed statements
$ENABLE_VIEW_STATEMENTS=FALSE;

// Debug configuration
// prits Debug output (1= only critical, 2= warnings, 3=normal output)
$DEBUG_ECHO_LEVEL=0;

// basic protection via http auth (not configurable via session)
// $HTTP_AUTH_LOGIN[0]="login";
// $HTTP_AUTH_PASSWORD[0]="password";
$HTTP_AUTH_ENABLED=FALSE;
$HTTP_AUTH_LOGIN[0]="";
$HTTP_AUTH_PASSWORD[0]="";


// shows top menu (only availiable if sessions supported by the php 4 installation)
$ENABLE_SESSION_FUNCTIONS=TRUE;

// show all datas (if possible) (not only these of the current user)
$USE_ALL_USER_DATA=FALSE;

// query execution plan
// a plan table is generated, when not found
$ENABLE_QUERY_EXECUTION_PLAN=true;

// enables the different object browsing
$ENABLE_TABLES=TRUE;
$ENABLE_SEQUENCES=TRUE;
$ENABLE_TRIGGERS=TRUE;
$ENABLE_FUNCTIONS=FALSE;
$ENABLE_INDEXES=TRUE;

$ENABLE_PROCEDURES=FALSE;
$ENABLE_PACKAGES=FALSE;
$ENABLE_TYPES=FALSE;
$ENABLE_VIEWS=FALSE;
$ENABLE_PROCEDURES=FALSE;
$ENABLE_PACKAGES=FALSE;
$ENABLE_TYPES=FALSE;
$ENABLE_VIEWS=TRUE;
$ENABLE_TABLESPACES=FALSE;
$ENABLE_DATAFILES=FALSE;

// table rowcount

$TABLE_ROW_LIMIT=500;
$TABLE_FIELDLENGTH_LIMIT=200;

// html optimization
$ENABLE_HTML_OPTIMIZATION=TRUE;

// using html tree (otherwise javascript)
$ENABLE_HTML_TREE=TRUE;

?>
