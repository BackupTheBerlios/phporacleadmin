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
class Index extends Object {
    
    var $name;
    var $owner;
    var $index_type; 
    var $table_owner; 
    var $table_name; 
    var $table_type; 
    var $uniqueness; 
    var $tablespace_name; 
    var $ini_trans; 
    var $max_trans; 
    var $initial_extent; 
    var $next_extent; 
    var $min_extents; 
    var $pct_increase; 
    var $status; 
    var $buffer_pool; 

    //list vals
    var $datas;

    function Index($server){
	Object::Object($server);
    }

   function getData($name=FALSE){
       if(!$name){
	   $name=$this->name;
       }
   
       $sql="SELECT index_type,table_owner,table_name,table_type,uniqueness,tablespace_name, ".
	   "ini_trans,max_trans,initial_extent,next_extent,min_extents,max_extents, ".
	   "pct_increase,status,buffer_pool ".
	   "FROM all_indexes ".
	   "WHERE owner = '".$this->owner."' AND index_name='".$name."'";
       // set last showed query
       $GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
       $this->parse($sql);
       $this->execute();
       $result=$this->nextrow();
       $this->index_type=$result["INDEX_TYPE"];
       $this->table_owner=$result["TABLE_OWNER"];
       $this->table_name=$result["TABLE_NAME"];
       $this->table_type=$result["TABLE_TYPE"];
       $this->uniqueness=$result["UNIQUENESS"];
       $this->tablespace_name=$result["TABLESPACE_NAME"];
       $this->ini_trans=$result["INI_TRANS"];
       $this->max_trans=$result["MAX_TRANS"];
       $this->initial_extent=$result["INITIAL_EXTENT"];
       $this->next_extent=$result["NEXT_EXTENT"];
       $this->min_extents=$result["MIN_EXTENTS"];
       $this->max_extents=$result["MAX_EXTENTS"];
       $this->pct_increase=$result["PCT_INCREASE"];
       $this->status=$result["STATUS"];
       $this->buffer_pool=$result["BUFFER_POOL"];

       $sql=sprintf("SELECT column_name FROM  all_ind_columns WHERE index_name = '%s' AND index_owner = '%s'",
		    $name,
		    $this->owner);
       $GLOBALS["SQL_QUERY_TO_SHOW"].="\n\n".$sql.";";
       $this->parse($sql);
       $this->execute();
       $this->column_name=array();
       while($arr=$this->nextrow()){
	   $this->column_name[]=$arr["COLUMN_NAME"];
       }
   }

    /*------------------- FOR MULTIPLE LISTINGS ---------------------------*/

    function getNames($user=FALSE){
	$sql="SELECT index_name FROM all_indexes WHERE owner='%s' order by index_name";
	$this->setNamesSql($sql);
	Object::getNames($user);
    }

    function getDatas($user=FALSE){
	$sql="SELECT index_name, index_type, table_owner, table_name FROM all_indexes ".
	    "WHERE owner = '%s' ORDER BY index_name";
	$this->setDatasSql($sql);
	Object::getDatas($user);
    }

}// end class
