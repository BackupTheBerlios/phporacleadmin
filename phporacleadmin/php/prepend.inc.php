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
$REGISTER_GLOBALS=ini_get("register_globals");

if(function_exists("session_start")){
    session_start();
    // register vars
    session_register("DBDatas");
}

if(!isset($DBDatas)){
    $DBDatas="";
}
if(!isset($CONFIGURATION)){
    $CONFIGURATION="";
}

// set current version
$VERSION="0.1.3";

include("debug.lib.php");
include("functions.lib.php");

// configuration class
class Configuration {
    
    var $CONFIGURATION="";
    var $ENABLE_SESSION_FUNCTIONS="";
    var $HTTP_AUTH_ENABLED="";
    var $HTTP_AUTH_LOGIN="";
    var $HTTP_AUTH_PASSWORD="";
    var $DBUser_C="";
    var $DBPass_C="";
    var $DBName_C="";
    var $DEBUG_ECHO_LEVEL="";
    var $USE_ALL_USER_DATA="";


    function Configuration(){
	include("config.inc.php");
	@include("privat.inc.php");
	$this->ENABLE_SESSION_FUNCTIONS=$ENABLE_SESSION_FUNCTIONS;
	$this->CONFIGURATION=$this->getSessionData();
	$this->DBUser_C=$DBUser_C;
	$this->DBPass_C=$DBPass_C;
	$this->DBName_C=$DBName_C;
	// basic auth
	if($HTTP_AUTH_ENABLED){
	    if(!is_array($HTTP_AUTH_LOGIN) || !is_array($HTTP_AUTH_PASSWORD)){
		die("Basic http auth is enabled but HTTP_AUTH_LOGIN and ".
		    "HTTP_AUTH_PASSWORD is wrong configured!");
	    }
	    $this->HTTP_AUTH_ENABLED=$HTTP_AUTH_ENABLED;
	    $this->HTTP_AUTH_LOGIN=$HTTP_AUTH_LOGIN;
	    $this->HTTP_AUTH_PASSWORD=$HTTP_AUTH_PASSWORD;
	}
	
	$this->setConfigBaseData("DEBUG_ECHO_LEVEL", $DEBUG_ECHO_LEVEL);
	$this->setConfigBaseData("USE_ALL_USER_DATA", $USE_ALL_USER_DATA);
	$this->setConfigBaseData("ENABLE_TABLES", $ENABLE_TABLES);
	$this->setConfigBaseData("ENABLE_SEQUENCES", $ENABLE_SEQUENCES);
	$this->setConfigBaseData("ENABLE_TRIGGERS", $ENABLE_TRIGGERS);
	$this->setConfigBaseData("ENABLE_FUNCTIONS", $ENABLE_FUNCTIONS);
	$this->setConfigBaseData("ENABLE_INDEXES", $ENABLE_INDEXES);
	$this->setConfigBaseData("ENABLE_PROCEDURES", $ENABLE_PROCEDURES);
	$this->setConfigBaseData("ENABLE_PACKAGES", $ENABLE_PACKAGES);
	$this->setConfigBaseData("ENABLE_TYPES", $ENABLE_TYPES);
	$this->setConfigBaseData("ENABLE_VIEWS", $ENABLE_VIEWS);
	$this->setConfigBaseData("ENABLE_TABLESPACES", $ENABLE_TABLESPACES);
	$this->setConfigBaseData("ENABLE_DATAFILES", $ENABLE_DATAFILES);
	$this->setConfigBaseData("ENABLE_VIEW_STATEMENTS", $ENABLE_VIEW_STATEMENTS);	
	$this->setConfigBaseData("TABLE_ROW_LIMIT", $TABLE_ROW_LIMIT);
	$this->setConfigBaseData("TABLE_FIELDLENGTH_LIMIT", $TABLE_FIELDLENGTH_LIMIT);
	$this->setConfigBaseData("ENABLE_HTML_OPTIMIZATION", $ENABLE_HTML_OPTIMIZATION);
	$this->setConfigBaseData("ENABLE_HTML_TREE", $ENABLE_HTML_TREE);
	$this->setConfigBaseData("ENABLE_QUERY_EXECUTION_PLAN", $ENABLE_QUERY_EXECUTION_PLAN);
    }

    /**
     * @desc sets initial config.inc.php params to the current config (if no session data given)
     * @param varname
     * @param data
     */
    function setConfigBaseData($varname, $value){
	if(!isset($this->CONFIGURATION[$varname])){
	    $this->$varname=$value;
	} else {
	    $this->$varname=$this->CONFIGURATION[$varname];
	}
    }

    /**
     * @desc gets session data
     * @param varname
     */
    function getSessionData($varname=FALSE){
	if(!$varname){
	    session_register("CONFIGURATION");
	    if($GLOBALS["REGISTER_GLOBALS"]){
		return $GLOBALS["CONFIGURATION"];
	    } else {
		return $GLOBALS["HTTP_SESSION_VARS"]["CONFIGURATION"];
	    }
	}
	if($GLOBALS["REGISTER_GLOBALS"]){
	    return $GLOBALS["CONFIGURATION"][$varname];
	} else {
	    return $GLOBALS["HTTP_SESSION_VARS"]["CONFIGURATION"][$varname];
	}
    }

    function get($name){
	return $this->$name;
    }

    function set($name, $value){
	if(function_exists("session_start") && $this->ENABLE_SESSION_FUNCTIONS){
	    if($GLOBALS["REGISTER_GLOBALS"]){
		$GLOBALS["CONFIGURATION"][$name]=$value;
	    } else {
		$GLOBALS["HTTP_SESSION_VARS"]["CONFIGURATION"][$name]=$value;
	    }
	}
	$this->$name=$value;
    }
    
}// end class
$CF=new Configuration();

$mode=getData("mode");
$submode=getData("submode");
// system actions 
switch($mode){
 case "sessionconfig":
     switch($submode){
     case "submit":
	 // apply datas to the current settings
	 $CF->set("ENABLE_TABLES", getData("FORM_ENABLE_TABLES", "integer"));
	 $CF->set("ENABLE_SEQUENCES", getData("FORM_ENABLE_SEQUENCES", "integer"));
	 $CF->set("ENABLE_TRIGGERS", getData("FORM_ENABLE_TRIGGERS", "integer"));
	 $CF->set("ENABLE_FUNCTIONS", getData("FORM_ENABLE_FUNCTIONS", "integer"));
	 $CF->set("ENABLE_INDEXES", getData("FORM_ENABLE_INDEXES", "integer"));
	 $CF->set("ENABLE_PACKAGES", getData("FORM_ENABLE_PACKAGES", "integer"));
	 $CF->set("ENABLE_PROCEDURES", getData("FORM_ENABLE_PROCEDURES", "integer"));
	 $CF->set("ENABLE_TYPES", getData("FORM_ENABLE_TYPES", "integer"));
	 $CF->set("ENABLE_VIEWS", getData("FORM_ENABLE_VIEWS", "integer"));
	 $CF->set("ENABLE_TABLESPACES", getData("FORM_ENABLE_TABLESPACES", "integer"));
	 $CF->set("ENABLE_DATAFILES", getData("FORM_ENABLE_DATAFILES", "integer"));
	 $CF->set("USE_ALL_USER_DATA", getData("FORM_USE_ALL_USER_DATA", "integer"));
	 $CF->set("ENABLE_VIEW_STATEMENTS", getData("FORM_ENABLE_VIEW_STATEMENTS", "integer"));
	 $CF->set("TABLE_ROW_LIMIT", getData("FORM_TABLE_ROW_LIMIT", "integer"));
	 $CF->set("TABLE_FIELDLENGTH_LIMIT", getData("FORM_TABLE_FIELDLENGTH_LIMIT", "integer"));
	 $CF->set("ENABLE_HTML_OPTIMIZATION", getData("FORM_ENABLE_HTML_OPTIMIZATION", "integer"));
	 $CF->set("ENABLE_HTML_TREE", getData("FORM_ENABLE_HTML_TREE", "integer"));
	 $CF->set("ENABLE_QUERY_EXECUTION_PLAN", getData("FORM_ENABLE_QUERY_EXECUTION_PLAN", "integer"));
	 break;
     default:
     }// end switch submode
     break;
 default:
}// end switch

// building availiable dbsarray
class DB_Datas {

    var $DBDatas;

    function DB_Datas($DBDatasession, $DBUser_C=FALSE, $DBPass_C=FALSE, $DBName_C=FALSE){
	$DBDatas=array();
	if(is_array($DBDatasession)){
	    foreach($DBDatasession as $DBData){
		$DBDatas[]=$DBData;
	    }
	}
	sort($DBDatas);
	if(is_array($DBName_C)){
	    for($x=0; $x < count($DBName_C); $x++){
		if(strlen($DBUser_C[$x])>0 AND strlen($DBName_C[$x])>0){
		    $DBDatas[]=array("DBUser"=>strtoupper($DBUser_C[$x]),
				     "DBPass"=>$DBPass_C[$x],
				     "DBName"=>strtoupper($DBName_C[$x]));
		}
	    }
	}
	sort($DBDatas);
	$this->DBDatas=$DBDatas;
    }

    function Name($Server){
	return $this->DBDatas[$Server]["DBName"];
    }

    function User($Server){
	return $this->DBDatas[$Server]["DBUser"];
    }

    function Pass($Server){
	return $this->DBDatas[$Server]["DBPass"];
    }

    function Drop($User, $Name){
	for($x=0; $x<count($this->DBDatas); $x++){
	    if(($this->DBDatas[$x]["DBUser"]==$User) &&
	       ($this->DBDatas[$x]["DBName"]==$Name)){
		unset($this->DBDatas[$x]);
		continue;
	    }
	}
	// reindex
	$DBDatas=array();
	foreach($this->DBDatas as $DBData){
	    $DBDatas[]=$DBData;
	}
	sort($DBDatas);
	$this->DBDatas=$DBDatas;
    }

    function Add($User, $Pass, $Name){
	if(strlen($User)>0 AND strlen($Name)>0){
	    $this->DBDatas[]=array("DBUser"=>strtoupper($User),
				   "DBPass"=>$Pass,
				   "DBName"=>strtoupper($Name));
	    sort($this->DBDatas);
	}
    }

}// end class


// instance for usage
$DB=new DB_Datas($DBDatas, 
		 $CF->get("DBUser_C"), 
		 $CF->get("DBPass_C"), 
		 $CF->get("DBName_C"));

include("page.lib.php");
include("database.lib.php");
include("object.lib.php");
include("tree.lib.php");
if($CF->get("ENABLE_HTML_TREE")){
    include("htmltree.lib.php");
}
include("datatable.lib.php");
include("menubar.lib.php");

include("user.lib.php");
include("column.lib.php");
if($CF->get("ENABLE_TABLES")){
    include("table.lib.php");
}
if($CF->get("ENABLE_SEQUENCES")){
    include("sequence.lib.php");
}
if($CF->get("ENABLE_TRIGGERS")){
    include("trigger.lib.php");
}
if($CF->get("ENABLE_FUNCTIONS")){
    include("function.lib.php");
}
if($CF->get("ENABLE_INDEXES")){
    include("index.lib.php");
}
if($CF->get("ENABLE_PROCEDURES")){
    include("procedure.lib.php");
}
if($CF->get("ENABLE_PACKAGES")){
    include("package.lib.php");
}
if($CF->get("ENABLE_TYPES")){
    include("type.lib.php");
}
if($CF->get("ENABLE_VIEWS")){
    include("view.lib.php");
}
if($CF->get("ENABLE_TABLESPACES")){
    include("tablespace.lib.php");
}
if($CF->get("ENABLE_DATAFILES")){
    include("datafile.lib.php");
}

// basic http auth protection
if($CF->get("HTTP_AUTH_ENABLED")){
    include("auth.inc.php");
}

// default initialisation
$SQL_QUERY_TO_SHOW="";
?>
