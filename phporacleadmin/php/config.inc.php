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
$DBUser_C[0]="";// (e.g. Scott)
$DBPass_C[0]="";// (e.g. Tiger)
$DBName_C[0]="";// (e.g. database.home)

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

// enables the different object browsing
$ENABLE_TABLES=TRUE;
$ENABLE_SEQUENCES=TRUE;
$ENABLE_TRIGGERS=TRUE;
$ENABLE_FUNCTIONS=TRUE;
$ENABLE_INDEXES=TRUE;
$ENABLE_PROCEDURES=TRUE;
$ENABLE_PACKAGES=TRUE;
$ENABLE_TYPES=TRUE;
$ENABLE_VIEWS=TRUE;
$ENABLE_TABLESPACES=TRUE;
$ENABLE_DATAFILES=TRUE;

// table rowcount
$TABLE_ROW_LIMIT=500;
$TABLE_FIELDLENGTH_LIMIT=200;

// html optimization
$ENABLE_HTML_OPTIMIZATION=TRUE;

// using html tree (otherwise javascript)
$ENABLE_HTML_TREE=FALSE;

?>
