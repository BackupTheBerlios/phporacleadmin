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
class Datafile extends Object {

    // assoc array of object data
    var $data;

    //list vals
    var $datas;

    function Datafile($server){
	Object::Object($server);
    }

    function getData(){
         $sql=sprintf("SELECT a.file_name \"Filename\", a.file_id \"File Id\", a.bytes / 1024 \"Kilobytes\", ".
		      "a.blocks \"Blocks\", a.maxbytes / 1024 \"Max. Kilobytes\", ".
		      "a.maxblocks \"Max. Blocks\", a.increment_by \"Increment by\", ".
		      "b.enabled \"Enabled\",b.status \"Status\", ".
		      "b.create_bytes / 1024 \"Initial Kilobytes\", ".
		      "to_char(b.creation_time,'MM-DD-YYYY') \"Creation Date\"".
		      "FROM dba_data_files a, v\$datafile b WHERE a.file_name = '%s' ".
		      "AND a.file_id = b.file#", $this->name);
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	$this->execute();
	$this->data=$this->nextrow();
    }

    /*------------------- FOR MULTIPLE LISTINGS ---------------------------*/

    function getNames($space){
	$sql=sprintf("SELECT file_name from dba_data_files where tablespace_name='%s' order by file_name", 
		     $space);
	$this->parse($sql);
	$this->execute();
	while($arr=$this->nextrow()){
	    $this->names[$space][]=$arr["FILE_NAME"];
	}
    }

}// end class
