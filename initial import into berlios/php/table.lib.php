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
class Table extends Object {
    
    // table vars
    var $comments; // tbl comment

    // holds all column data
    var $columns;
    	
    function Table($server){
	Object::Object($server);
    }//function Table->Table

    function getData($name=FALSE){
	if($name){
	    $this->name=$name;
	}
	$sql=sprintf("SELECT COMMENTS FROM ALL_TAB_COMMENTS WHERE OWNER='%s' AND TABLE_NAME='%s'",
		     $this->owner,
		     $this->name);
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	$this->execute();
	$result=$this->nextrow();
	$this->comments=$result["COMMENTS"];
	$sql = sprintf("SELECT COLUMN_NAME, DATA_TYPE, DATA_LENGTH, DATA_PRECISION, DATA_SCALE, NULLABLE,
					DATA_DEFAULT FROM ALL_TAB_COLUMNS WHERE TABLE_NAME='%s' AND OWNER='%s'", 
		       $this->name, 
		       $this->owner);
	$GLOBALS["SQL_QUERY_TO_SHOW"].="\n\n".$sql.";";
	$this->parse($sql);
	$this->execute();
	$result=$this->result();
	if (is_array($result)) {
	    foreach($result as $foo => $data){
		$temp_col = new Column;
		$temp_col->name 		= $data["COLUMN_NAME"];
		$temp_col->type 		= $data["DATA_TYPE"];
		$temp_col->length 		= $data["DATA_LENGTH"];
		$temp_col->precision 	= $data["DATA_PRECISION"];
		$temp_col->scale		= $data["DATA_SCALE"];
		$temp_col->nullable		= $data["NULLABLE"];
		$temp_col->data_default	= $data["DATA_DEFAULT"];
		$this->columns[] = $temp_col;
	    }
	}
    }

    function getInfo($name=""){
	if($name){
	    $this->name=$name;
	}
	$sql=sprintf("SELECT NVL(pct_free, 0) \"%% Free:\", NVL(pct_used, 0) \"%% Used:\", ".
		     "NVL(min_extents, 0) \"Min Extents:\", NVL(max_extents, 0) \"Max Extents:\", ".
		     "NVL(initial_extent, 0) \"Initial Extent:\", NVL(next_extent, 0) \"Next Extent:\", ".
		     "NVL(logging, '?') \"Logging:\", ".
		     "NVL(cluster_name, 'none') \"Cluster Name\", NVL(iot_name, 'none') \"IOT Name:\", ".
		     "NVL(pct_increase, 0) \"%% Increase:\", NVL(blocks, 0) \"Blocks:\", ".
		     "NVL(instances, 'none') \"Instances:\", ".
		     "NVL(cache, 'none') \"Cache:\", NVL(buffer_pool, 'none') \"Buffer Pool:\", ".
		     "NVL(ini_trans, 0) \"Init Trans\", NVL(max_trans, 0) \"Max Trans:\", ".
		     "NVL(avg_space, 0) \"Avg Space:\", NVL(chain_cnt, 0) \"Chain Count:\", ".
		     "NVL(nested, 'none') \"Nested:\", NVL(to_char(last_analyzed,'MM-DD-YYYY'),'Not Analyzed') ".
		     "\"Last Analyzed:\"".
		     "FROM all_tables ".
		     "WHERE table_name = '%s' ".
		     "AND owner = '%s' ", 
		     $this->name,
		     $this->owner);
	$GLOBALS["SQL_QUERY_TO_SHOW"].="\n".$sql.";";
	$this->parse($sql);
	$this->execute();
	$result=$this->nextrow();
	$infodatas=array();
	if(is_array($result)){
	    foreach($result as $key => $value){
		$infodatas[]=array("Tag"=>$key, "Value"=>$value);
	    }
	}
	return $infodatas;
    }

    function Drop($name=FALSE){
	if($name){
	    $this->name=$name;
	}
	$sql=sprintf("DROP TABLE %s.%s", 
		     $this->owner,
		     $this->name);
	// set last showed query
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	return $this->execute();
    }

    function Truncate($name=FALSE){
	if($name){
	    $this->name=$name;
	}
	$sql=sprintf("DELETE FROM %s.%s", 
		     $this->owner, 
		     $this->name);
	// set last showed query
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	return $this->execute();
    }

    function Rename($newname=FALSE){
	if(!$newname){
	    return FALSE;
	}
	$sql=sprintf("ALTER TABLE %s.%s RENAME TO %s", 
		     $this->owner, 
		     $this->name,
		     $newname);
	// set last showed query
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	return $this->execute();
    }

    function Analyze(){
	if(!$this->name){
	    trace(2,__LINE__, __FILE__, "Tablename not given");
	    return FALSE;
	}
	if(!$this->owner){
	    trace(2,__LINE__, __FILE__, "Tableowner not given");
	    return FALSE;
	}
	$sql=sprintf("ANALYZE TABLE %s.%s COMPUTE STATISTICS",
		     $this->owner,
		     $this->name);
	// set last showed query
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	return $this->execute();
    }

    function Estimate(){
	if(!$this->name){
	    trace(2,__LINE__, __FILE__, "Tablename not given");
	    return FALSE;
	}
	if(!$this->owner){
	    trace(2,__LINE__, __FILE__, "Tableowner not given");
	    return FALSE;
	}
	$sql=sprintf("ANALYZE TABLE %s.%s ESTIMATE STATISTICS",
		     $this->owner,
		     $this->name);
	// set last showed query
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	return $this->execute();	
    }

    function deleteStats(){
	if(!$this->name){
	    trace(2,__LINE__, __FILE__, "Tablename not given");
	    return FALSE;
	}
	if(!$this->owner){
	    trace(2,__LINE__, __FILE__, "Tableowner not given");
	    return FALSE;
	}
    }

    
    /*------------------- FOR MULTIPLE LISTINGS ---------------------------*/

    function getNames($user=FALSE){
	$sql="SELECT TABLE_NAME FROM ALL_TABLES WHERE OWNER = '%s' ORDER BY TABLE_NAME";
	$this->setNamesSql($sql);
	Object::getNames($user);
    }

    function getDatas($user=FALSE, $name=FALSE){
	$sql="SELECT t.TABLE_NAME TABLE_NAME, t.TABLESPACE_NAME TABLESPACE_NAME, t.CLUSTER_NAME CLUSTER_NAME, ".
	    "t.OWNER OWNER, c.comments comments FROM ALL_TABLES t, all_tab_comments c WHERE t.OWNER='%s' ".
	    "AND t.owner = c.owner AND t.table_name = c.table_name ORDER BY t.TABLE_NAME";
	// set last showed query
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->setDatasSql($sql);
	Object::getDatas($user);
	$count=count($this->datas[$user]);
	for($x=0; $x<$count; $x++){
	    $sql="SELECT count(*) FROM ".$this->datas[$user][$x]["OWNER"].".".$this->datas[$user][$x]["TABLE_NAME"];
	    $this->parse($sql);
	    $this->execute();
	    $result=$this->nextrow();
	    $this->datas[$user][$x]["Entries"]=$result["COUNT(*)"];
	}	
    }

    function getTriggerNames() {
	$sql = sprintf("SELECT TRIGGER_NAME FROM USER_TRIGGERS WHERE TABLE_NAME='%s'",$this->name);
	//$return_array = $this->conn->DoSelect($sql);
	if (is_array($return_array)) {
	    while (list($dummy, $tr) = each ($return_array)) {
		$this->trigger_names[] = $tr["TRIGGER_NAME"];
	    }
	}
    } // function Table->GetTriggerNames


} //class Table
?>
