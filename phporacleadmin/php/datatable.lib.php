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
/**
 * @desc this is a newer model of the class object
 * especially for table views
 */
class DataTable extends Database {

    // html data of the table
    var $htmldata;

    var $sql;
    var $data;
    var $colortoggle=FALSE;
    var $sorting=FALSE;
    var $sortorder=FALSE;
    var $sortfield=FALSE;
    var $datalinks;
    var $notsortable; // holds fieldnames which are not sortable
    var $fieldtypes; // holds array ($fieldname=><type>, ... ) for tablehead
    var $rownum=FALSE;
    var $sortabletypes;
    var $ephtml=''; // execution plan html

    /**
     * @desc constructor
     * @param server id
     */
    function DataTable($server){
	Database::Database($server);
	$this->fieldtypes=array();
	$this->datalinks=array();
	$this->notsortable=array();
	$this->sortabletypes=array("VARCHAR2", "NVARCHAR2", "NUMBER", "LONG", 
				   "DATE", "RAW", "LONG RAW", "ROWID", "UROWID", "CHAR",
				   "NCHAR", "VARCHAR");
    }
    /**
     * @desc sets the sql statement
     * @param sql syntax
     */
    function setSql($sql){
	$sql=trim($sql);
	if(substr($sql,strlen($sql)-1, 1)==";"){
	    $sql=substr($sql, 0, strlen($sql)-1);
	}
	$this->sql=$sql;
    }
    /**
     * @desc set data array of assoc datas
     * @param data
     */
    function setData($data){
	$this->data=$data;
    }

    /**
     * @desc toggle sort support
     * @param toggle TRUE || FALSE
     */
    function setSorting($toggle){
	$this->sorting=$toggle;
    }

    /**
     * @desc sets the sort order
     * @param $field
     * @param $order
     */
    function setSort($field=FALSE, $order){
	$order=strtoupper($order);
	if($field){
	    if($order!="ASC" AND $order!="DESC"){
		$order="ASC";
	    }
	    $this->sortorder=$order;
	    $this->sortfield=$field;
	}
    }
    /**
     * @desc sets color toggle on/off
     * @param toggle TRUE || FALSE
     */
    function setColorToggle($toggle){
	$this->colortoggle=$toggle;
    }
    /**
     * @desc executes the query and form the data array
     * @param bool execution plan used (overrides of the settings)
     */
    function loadData($with_plan=true){
     	if(!$this->sql){
	    trace(2, __LINE__, __FILE__, "No Sql given");
	    return FALSE;
	}
	$sql=$this->sql;

	if(preg_match('/(SELECT|SHOW)/i', $sql)){
	    $rowsql=sprintf("SELECT count(1) FROM (%s)", $sql);
	    $stmt=$this->parse($rowsql);
	    $this->execute($stmt);
	    $foo=$this->nextrow($stmt);
	    $this->rownum=$foo["COUNT(1)"];
	}
	
	if($this->sorting && $this->sortfield && $this->sortorder){
	    $sql="SELECT * FROM (\n ".$sql." \n) ORDER BY \"".$this->sortfield.
		"\" ".$this->sortorder;
	}
	
	$GLOBALS["SQL_QUERY_TO_SHOW"].="\n".$sql."\n";
	$stmt=$this->parse($sql);
	if(!$this->execute($stmt)){
	    $this->error=FALSE;
	    $this->data=FALSE;
	    return FALSE;
	}

	// execution plan stuff
	if($with_plan && $GLOBALS["CF"]->get("ENABLE_QUERY_EXECUTION_PLAN")){
	    // add execution plan stuff
	    $planname=time();
	    $epsql="explain plan set statement_id='$planname' for ".$sql;
	    // extra db
	    $db=new Database($GLOBALS['Server']);
	    $epstmt=$db->parse($epsql);
	    if(!$db->execute($epstmt) && $db->errorcode=='2402'){
		$plansql="create table PLAN_TABLE (
		    statement_id    varchar2(30),
		    timestamp       date,
		    remarks         varchar2(80),
		    operation       varchar2(30),
		    options         varchar2(30),
		    object_node     varchar2(128),
		    object_owner    varchar2(30),
		    object_name     varchar2(30),
		    object_instance numeric,
		    object_type     varchar2(30),
		    optimizer       varchar2(255),
		    search_columns  number,
		    id              numeric,
		    parent_id       numeric,
		    position        numeric,
		    cost            numeric,
		    cardinality     numeric,
		    bytes           numeric,
		    other_tag       varchar2(255),
		    partition_start varchar2(255),
		    partition_stop  varchar2(255),
		    partition_id    numeric,
		    other           long,
		    distribution    varchar2(30))";
		$epstmt=$db->parse($plansql);
		$db->execute($epstmt);
		// build plan again
		$epstmt=$db->parse($epsql);
		$db->execute($epstmt);
	    } 

	    $tbl=new DataTable($GLOBALS['Server']);
	    $space=chr(160);
	    $epsql="select decode(id, 0, '', ".
		"lpad('$space', 2+(level-1))||level||'.'||position)||'$space'||".
		"operation||'$space'||options||'$space'||object_name||'$space'||".
		"object_type||'$space'||".
		"decode(id,0,'Cost = '||position) Query_plan ".
		"from plan_table ".
		"connect by prior id=parent_id ".
		"and statement_id='$planname' ".
		"start with id=0 and statement_id='$planname'";
	    $tbl->setSql($epsql);
	    $tbl->loadData(false);
	    $tbl->setColorToggle(1);
	    $tbl->renderHTML();
	    unset($this->ephtml);
	    $this->ephtml=$tbl->getHTML();
	    
	    $epstmt=$db->parse("delete plan_table where statement_id='$planname'");
	    if(!$db->execute($epstmt)){
		$db->error=FALSE;
		$db->data=FALSE;
		return FALSE;
	    }
	}

	if(preg_match('/(DELETE|UPDATE)/i', $this->sql)){
	    $this->rownum=$this->affected($stmt);
	}

	$this->data=array();
	$count=0;
	$fieldcounter=1;
	while($count<$GLOBALS["CF"]->get("TABLE_ROW_LIMIT") AND $arr=$this->nextrow($stmt)){
	    foreach($arr as $key => $val){
		// test obly the first row
		if($count==0){
		    $this->fieldtypes[$key]["TYPE"]=$this->fieldtype($fieldcounter);
		    $this->fieldtypes[$key]["SIZE"]=$this->fieldsize($fieldcounter);
		    if(!in_array($this->fieldtypes[$key]["TYPE"], $this->sortabletypes)){
			$this->notsortable[]=$key;
		    }
		    $fieldcounter++;
		}
		$val=@ocicolumnisnull($stmt, $key) ? "<NULL>" : $val;
		$val=is_object($val) ? $val->load() : $val;
		if(strlen($val)>$GLOBALS["CF"]->get("TABLE_FIELDLENGTH_LIMIT")){
		    $arr[$key]=substr($val, 0, $GLOBALS["CF"]->get("TABLE_FIELDLENGTH_LIMIT"));
		} else {
		    $arr[$key]=$val;
		}
	    }
	    $this->data[]=$arr;
	    $count++;
	}
    }

    /**
     * set data array
     * @param array
     */
    function setData($data){
	if(!is_array($data)){
	    trace(2, __LINE__, __FILE__, "Wrong Dataformat");
	    return FALSE;
	}
	$this->data=$data;
	return TRUE;
    }

    /**
     * set fieldnames and their links '$value' schould be the inserted value
     * @param array
     */
    function setDataLink($name, $link){
	$this->datalinks[$name]="\$link=\"".$link."\";";
    }

    /**
     * @desc render the html table
     * @param side of the tablelist
     * @param additional url params
     */
    function renderHTML($urldata=""){
	if(!$this->error){
	    $this->htmldata=$this->errorstring;
	    return TRUE;
	}
	if(!is_array($this->data)){
	    trace(2, __LINE__, __FILE__, "No Datas availiable");
	    return FALSE;
	}
	if(count($this->data)==0){
	    $this->htmldata="Query successfull executed. No entries returned.";
	    return TRUE;
	}
	$count=0;
	$html=$this->rownum ? "<br>Number of selected/affected Rows: ".$this->rownum."<br>" : "";
	$html.="<TABLE BORDER=0>\n";
	foreach($this->data as $row){
	    if($count==0){
		$html.="<TR>\n";
		foreach($row as $key => $foo){
		    if($this->sorting && !in_array($key, $this->notsortable)){
			$html.="<TH><A HREF=\"".$urldata.
			    "&Sortorder=";
			if($key==$this->sortfield){
			    $html.=($this->sortorder=="ASC") ? "DESC" : "ASC";
			} else {
			    $html.="ASC";
			}
			$html.="&Sortfield=".rawurlencode($key)."\">$key</A><BR>".
			    $this->fieldtypes[$key]["TYPE"];

		    } else {
			$html.="<TH>$key<BR>";
			$html.=isset($this->fieldtypes[$key]["TYPE"]) ? 
			    $this->fieldtypes[$key]["TYPE"] : "";
		    }
		    $html.=isset($this->fieldtypes[$key]["SIZE"]) ? 
			"(".$this->fieldtypes[$key]["SIZE"].")" :
			"";
		    $html.="</TH>";
		}
		$html.="</TR>\n";
	    }
	    if($this->colortoggle){
		$html .=$count % 2 ? "<TR VALIGN=TOP>" : 
		    "<TR BGCOLOR=\"#CCCCCC\" VALIGN=TOP>";
	    } else {
		$html.="<TR VALIGN=TOP>\n";
	    }
	    foreach($row as $key => $val){
		if(in_array($key, array_keys($this->datalinks))){
		    $value=rawurlencode($val);
		    $html.="<TD><A HREF=\"";
		    eval($this->datalinks["$key"]);
		    $html.=$link;
		    $html.="\">".((strlen($val)>0) ? htmlentities($val) : "&nbsp;")."</A></TD>";
		} else {
		    $html.="<TD>".((strlen($val)>0) ? htmlentities($val) : "&nbsp;")."</TD>";
		}
	    }
	    $html.="</TR>\n";
	    ++$count;
	}
	$html.="</TABLE>\n";
	$this->htmldata=$html;
	if($GLOBALS["CF"]->get("ENABLE_QUERY_EXECUTION_PLAN")){
	    $this->htmldata=$this->ephtml.$html;
	}
	return TRUE;
    }
    /**
     * @desc returns the datas
     */
    function getData(){
	return $this->data;
    }
    /**
     * @desc returns the html table
     */
    function getHTML(){
	return $this->htmldata;
    }

}
