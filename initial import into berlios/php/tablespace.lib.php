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
class Tablespace extends Object {

    // assoc array of object data
    var $data;

    //list vals
    var $datas;

    function Tablespace($server){
	Object::Object($server);
    }

    function getData(){
	$sql=sprintf("SELECT initial_extent \"Initial Extent\", ".
		     "next_extent \"Next Extent\", min_extents \"Min Extents\", max_extents \"Max Extents\", ".
		     "pct_increase \"%% Increase\", status \"Status\",CONTENTS \"Contents\", ".
		     "logging \"Logging\" FROM dba_tablespaces WHERE ".
		     "tablespace_name='%s' ORDER BY tablespace_name", 
		     $this->name);
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	$this->execute();
	$this->data=$this->nextrow();
    }

    /*------------------- FOR MULTIPLE LISTINGS ---------------------------*/

    function getNames($user=FALSE){
	$sql="select tablespace_name from dba_tablespaces order by tablespace_name";
	$this->parse($sql);
	$this->execute();
	$names=array();
	while($arr=$this->nextrow()){
	    $names[]=$arr["TABLESPACE_NAME"];
	}
	$this->names=$names;
    }

}// end class
