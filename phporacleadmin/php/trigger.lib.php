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
class Trigger extends Object {

    var $name;
    var $owner;
    var $trigger_type;
    var $event;
    var $table_owner;
    var $table_name;
    var $referencing_names;
    var $when_clause;
    var $status;
    var $description;
    var $trigger_body;

    // listvalues
    var $names;
    var $datas;
    var $user;

    function Trigger($Server){
	Object::Object($Server);
    }

    function setName($name){
	$this->name=$name;
    }
    
    function setOwner($owner){
	$this->owner=$owner;
    }

    function getData($name=FALSE){
	if(!$name){
	    $name=$this->name;
	}
	$sql="SELECT trigger_name, trigger_type, triggering_event, ".
	    "table_owner, table_name, referencing_names, when_clause, ".
	    "status, description, trigger_body FROM all_triggers WHERE owner = '".$this->owner.
	    "' AND trigger_name = '".$name."'";
	// set last showed query
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	$this->execute();
	$result=$this->nextrow();
	$this->trigger_type=$result["TRIGGER_TYPE"];
	$this->triggering_event=$result["TRIGGERING_EVENT"];
	$this->table_owner=$result["TABLE_OWNER"];
	$this->table_name=$result["TABLE_NAME"];
	$this->referencing_names=$result["REFERENCING_NAMES"];
	$this->when_clause=$result["WHEN_CLAUSE"];
	$this->status=$result["STATUS"];
	$this->description=$result["DESCRIPTION"];
	$this->trigger_body=$result["TRIGGER_BODY"];
    }

    /*------------------- FOR MULTIPLE LISTINGS ---------------------------*/

    function getNames($user=FALSE){
	$sql="SELECT trigger_name FROM all_triggers ".
	    "WHERE owner = '%s' order by trigger_name";
	$this->setNamesSql($sql);
	Object::getNames($user);
    }

    function getDatas($user=FALSE, $name=FALSE){
	$sql="SELECT trigger_name, trigger_type || triggering_event TYPE, ".
	    "table_owner, table_name, status FROM all_triggers ".
	    "WHERE  owner = '%s' ORDER BY trigger_name";
	$this->setDatasSql($sql);
	Object::getDatas($user);
    }
}// end class
