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
class Database {

    // privat
    var $DBName;
    var $DBUser;
    var $DBPass;
    var $stmt="";
    var $conn="";
    var $error=TRUE;
    var $version="";
    var $errorcode=""; // error number
    var $errormessage=""; // error number
    var $errorstring=""; // komplete html error

    function Database($Server=0){ 
	global $DB;
	settype($Server, "integer");
	$this->DBName=$DB->Name($Server);
	$this->DBPass=$DB->Pass($Server);
	$this->DBUser=$DB->User($Server);
	if(!isset($GLOBALS[md5($this->DBUser.$this->DBPass.$this->DBName)]) || 
	   !$GLOBALS[md5($this->DBUser.$this->DBPass.$this->DBName)]){
	    $GLOBALS[md5($this->DBUser.$this->DBPass.$this->DBName)]=
		@ocilogon($this->DBUser,$this->DBPass,$this->DBName);
	}
	$this->conn =$GLOBALS[md5($this->DBUser.$this->DBPass.$this->DBName)];
	
	if(!$this->conn){
	    $this->error($this->conn);
	}
	
	$this->version=@OCIServerVersion($this->conn);
    }
    
    /**destructor */
    function destruct(){
	@OCIFreeStatement($this->stmt);
	@OCILogoff($this->conn);
	return TRUE;
    }
    
    /** build error output
     *    type can be stmt|conn|global
     */
    function error($type=""){
	if(!$type){
	    $type=$this->stmt;
	}
	
	$error=OCIError($type);

	if($error) {
	    $errorstring="<br>\nOCIError: ".$error["code"]." ".
		$error["message"]
		." <br>\nAction: ". $this->query."<br>\n";
	    $this->errorstring=$errorstring;
	    //trace(2,__LINE__,get_class($this), $errorstring);
	    $this->errorcode=$error["code"];
	    $this->errormessage=$error["message"];	
	    $this->error = false;
	    return FALSE;
	} else {
	    $this->errorcode=FALSE;
	    $this->errormessage=FALSE;
	    $this->error = true;
	    return TRUE;
	}
    }
    
    /** parse a query and return a statement */
    function parse($query){
	$this->query=$query;
	$stmt=@ociparse($this->conn,$query);
	$this->stmt=$stmt;
	$this->error();
	return $stmt;
    }
    
    /** executes a statement */
    function execute($stmt="",$param=OCI_COMMIT_ON_SUCCESS){
	if(!$stmt){
	    $stmt=$this->stmt;
	}
	@OCIExecute($stmt,$param);
	return $this->error();
    }
    
    
    /** returns array of assoc array's */
    function result($stmt=FALSE,$from=FALSE,$to=FALSE){
	if(!$stmt){
	    $stmt=$this->stmt;
	}
	$result=array();
	if (!$from && !$to){
	    while(@ocifetchinto($stmt,$arr,OCI_ASSOC+OCI_RETURN_NULLS)){
		$result[]=$arr;
	    }
	} else {
	    $counter=0;
	    while(@ocifetchinto($stmt,$arr,OCI_ASSOC+OCI_RETURN_NULLS)){
		if($counter>=$from && $counter<=$to){
		    $result[]=$arr;
		}
		$counter++;
	    }
	}
	@OCIFreeStatement($stmt);
	return $result;
    }
    
    /** return thge the next row based upon @ocifetchinto($stmt,$arr,OCI_ASSOC+OCI_RETURN_NULLS) */
    function nextrow($stmt=FALSE, $param=FALSE){
	if(!$stmt){
	    $stmt=$this->stmt;
	}  
	if(!$param){
	    $param=OCI_ASSOC+OCI_RETURN_NULLS;
	}
	if(@ocifetchinto($stmt,$arr,$param)){
	    return $arr;
	} 
	return FALSE;
    }

    /** returns rownum of affected rows */
    function affected($stmt=FALSE){
	if(!$stmt){
	    $stmt=$this->stmt;
	}
	return @OCIRowCount($stmt);
    }

    /** returns type of field */
    function fieldtype($field, $stmt=FALSE){
	if(!$stmt){
	    $stmt=$this->stmt;
	}
	return @OCIColumnType($stmt, $field);
    }

    /** returns type of field */
    function fieldsize($field, $stmt=FALSE){
	if(!$stmt){
	    $stmt=$this->stmt;
	}
	return @OCIColumnSize($stmt, $field);
    }

} //class Database
	
?>
