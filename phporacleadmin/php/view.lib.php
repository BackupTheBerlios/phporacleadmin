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
class View extends Object {
    
    var $name, $owner;
    var $comment;
    var $sql;

    // sequence listvars array("username" => array("name", ...))
    var $names;
    // sequence listvars array("username" => array(array("VIEW_NAME"=>"name", ...), ...))
    var $datas;

    function Type($Server){
	Object::Object($server);
    }

    function getData($name=""){
	if($name){
	    $this->name=$name;
	}
	$sql=sprintf("SELECT comments FROM  all_views, all_tab_comments WHERE ".
		     "all_views.owner = '%s' AND all_views.owner = all_tab_comments.owner(+) ".
		     "AND all_views.view_name = all_tab_comments.table_name(+) AND view_name='%s'", 
		     $this->owner, 
		     $this->name);
	// set last showed query
	$GLOBALS["SQL_QUERY_TO_SHOW"]=$sql.";";
	$this->parse($sql);
	$this->execute();
	$return_array=$this->nextrow();
	$this->comment=$return_array["COMMENTS"];
	$sql=sprintf("SELECT text FROM  all_views WHERE view_name = '%s' AND owner = '%s'",
		     $this->name,
		     $this->owner);
	$GLOBALS["SQL_QUERY_TO_SHOW"].="\n\n".$sql.";";
	$this->parse($sql);
	$this->execute();
	$return_array=$this->nextrow();
	$this->sql=sprintf("CREATE OR REPLACE VIEW %s.%s AS\n %s",
			   $this->owner,
			   $this->name,
			   $return_array["TEXT"]);
    }

    /*------------------- FOR MULTIPLE LISTINGS ---------------------------*/

    function getNames($user=FALSE){
	$sql="SELECT VIEW_NAME FROM ALL_VIEWS WHERE OWNER='".
	      "%s' ORDER BY VIEW_NAME";
	$this->setNamesSql($sql);
	Object::getNames($user);	
    }

    function getDatas($user=FALSE){
	$sql="SELECT view_name, comments FROM  all_views, all_tab_comments WHERE ".
	    "all_views.owner = '%s' AND all_views.owner = all_tab_comments.owner(+) ".
	    "AND all_views.view_name = all_tab_comments.table_name(+) ".
	    "ORDER BY view_name";
	$this->setDatasSql($sql);
	Object::getDatas($user);
    }

}// class Sequence
	
?>
