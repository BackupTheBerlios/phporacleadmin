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
class User extends Database {

    var $name;

    var $usernames;

    function User($server){
	Database::Database($server);
	$this->usernames=array();
    }

    function GetUserNames(){
	$this->usernames=array();
	if($GLOBALS["CF"]->get("USE_ALL_USER_DATA")){
	    $sql="SELECT USERNAME FROM ALL_USERS WHERE USERNAME!='TRACESVR' AND ".
		"USERNAME!='DBSNMP' AND USERNAME!='AURORA\$ORB\$UNAUTHENTICATED' AND ".
		"USERNAME!='ORDPLUGINS'";
	    $this->parse($sql);
	    $this->execute();
	    while($arr=$this->nextrow()){
		$this->usernames[]=$arr["USERNAME"];
	    }
	} else {
	    $this->usernames[]=strtoupper($this->DBUser);
	}
    }

}
