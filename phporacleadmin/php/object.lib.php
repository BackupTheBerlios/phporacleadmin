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
// class ever objects extens of it
// put here all generic functions
class Object extends Database {

    // name of the object
    var $name;
    // owner of the object
    var $owner;

    //list vals
    var $user;
    // listvars array("username" => array("name", ...))
    var $names;
    // listvars array("username" => array(array("NAME"=>"name", ...), ...))
    var $datas;

    function Object($server){
	Database::Database($server);
	$this->user=new User($server);
    }

    function setName($name){
	$this->name=$name;
    }
    
    function setOwner($owner){
	$this->owner=$owner;
    }

    /*------------------- FOR MULTIPLE LISTINGS ---------------------------*/
    
    function setNamesSql($sql){
	$this->namesSql=$sql;
    }

    function getNames($user){
	if(!$user){
	    $this->user->GetUserNames();
	    $users=$this->user->usernames;
	} else {
	    $users=array($user);
	}
	$this->names=array();
	foreach($users as $user){
	    $user=strtoupper($user);
	    $sql=sprintf($this->namesSql, $user);
	    // set last showed query
	    $GLOBALS["SQL_QUERY_TO_SHOW"]=$sql;
	    $this->parse($sql);
	    $this->execute();
	    while ($arr=$this->nextrow()){
		$this->names[$user][]=current($arr);
	    }
	}
    }

    function setDatasSql($sql){
	$this->datasSql=$sql;
    }

    function getDatas($user){
	if(!$user){
	    trace(2, __LINE__,__FILE__, "No User given");
	    return FALSE;
	}
	$user=strtoupper($user);
	$sql=sprintf($this->datasSql, $user);
	// set last showed query
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql;
	$stmt=$this->parse($sql);

	$this->execute($stmt);
	while($arr=$this->nextrow($stmt)){
	    $this->datas[$user][]=$arr;
	}
    }
    
}// end class
