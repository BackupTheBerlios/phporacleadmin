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
class Sequence extends Object {
    
    var $name, $owner, $minval, $maxval, $incrementval, $cycleflag, $orderflag, $cachesize, $lastnumber;
    

    // sequence listvars array("username" => array("sequencename", ...))
    var $names;
    // sequence listvars array("username" => array(array("SEQUENCE_NAME"=>"sequencename", ...), ...))
    var $datas;


    function Sequence($Server){
	Object::Object($Server);
    }

    function getData($name=""){
	if($name){
	    $this->name=$name;
	}
	$sql=sprintf("SELECT  min_value, max_value, increment_by, ".
		     "cycle_flag, order_flag, cache_size, last_number ".
		     "FROM all_sequences WHERE sequence_name = '%s' and sequence_owner='%s'", 
		     $this->name, 
		     $this->owner);
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	$this->execute();
	$return_array=$this->nextrow();
	$this->minval=$return_array["MIN_VALUE"];
	$this->maxval=$return_array["MAX_VALUE"];
	$this->incrementval=$return_array["INCREMENT_BY"];
	$this->cycleflag=$return_array["CYCLE_FLAG"];
	$this->orderflag=$return_array["ORDER_FLAG"];
	$this->cachesize=$return_array["CACHE_SIZE"];
	$this->lastnumber=$return_array["LAST_NUMBER"];
    }

    /*------------------- FOR MULTIPLE LISTINGS ---------------------------*/

    function getNames($user=FALSE){
	$sql="SELECT SEQUENCE_NAME FROM ALL_SEQUENCES WHERE SEQUENCE_OWNER='".
	      "%s' ORDER BY SEQUENCE_NAME";
	$this->setNamesSql($sql);
	Object::getNames($user);	
    }

    function getDatas($user=FALSE){
	$sql="SELECT  MIN_VALUE, MAX_VALUE, INCREMENT_BY, ".
		     "CYCLE_FLAG, ORDER_FLAG, CACHE_SIZE, LAST_NUMBER, SEQUENCE_NAME ".
	    "FROM ALL_SEQUENCES WHERE SEQUENCE_OWNER='%s'";
	$this->setDatasSql($sql);
	Object::getDatas($user);
    }

}// class Sequence
	
?>
