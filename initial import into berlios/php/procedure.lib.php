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
class Procedure extends Object {

    var $created;
    var $lastmodified;
    var $status;
    var $sql;

    //list vals
    var $datas;

    function Index($server){
	Object::Object($server);
    }

   function getData($name=FALSE){
       if(!$name){
	   $name=$this->name;
       }
   	$sql="SELECT TO_CHAR(created, 'DD-MON-YYYY') CREATED, ".
	    "TO_CHAR(last_ddl_time, 'DD-MON-YYYY') LASTMODIFIED, NLS_INITCAP(status) status ".
	    "FROM all_objects ".
	    "WHERE owner = '".$this->owner."' AND object_name='".$name."' AND object_type='PROCEDURE' ";
	// set last showed query
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	$this->execute();
	$result=$this->nextrow();
	$this->lastmodified=$result["LASTMODIFIED"];
	$this->created=$result["CREATED"];
	$this->status=$result["STATUS"];
	$sql=sprintf("SELECT TEXT FROM ALL_SOURCE WHERE OWNER='%s' AND TYPE='PROCEDURE' AND NAME='%s' ORDER BY line",
		     $this->owner,
		     $name);
	$GLOBALS["SQL_QUERY_TO_SHOW"].="\n\n".$sql.";";
	$this->parse($sql);
	$this->execute();
	$sql="";
	while($arr=$this->nextrow()){
	    $sql .=$arr["TEXT"];
	}
	$sql=substr($sql, 9,strlen($sql));
	$sql = "CREATE OR REPLACE PROCEDURE ".$this->owner.".".$sql."/";
	$this->sql=$sql;  
   }

    /*------------------- FOR MULTIPLE LISTINGS ---------------------------*/

    function getNames($user=FALSE){
	$sql="SELECT object_name FROM all_objects WHERE owner='%s' AND object_type='PROCEDURE' ".
	    "order by object_name";
	$this->setNamesSql($sql);
	Object::getNames($user);
    }

    function getDatas($user=FALSE, $name=FALSE){
	$sql="SELECT object_name, TO_CHAR(created, 'DD-MON-YYYY') CREATED, ".
	    "TO_CHAR(last_ddl_time, 'DD-MON-YYYY') LASTMODIFIED,  NLS_INITCAP(status) STATUS ".
	    "FROM all_objects WHERE object_type ='PROCEDURE' AND ".
	    "owner = '%s' ORDER BY object_name";
	$this->setDatasSql($sql);
	Object::getDatas($user);
    }


}// end class
