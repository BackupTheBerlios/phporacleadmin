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

include("prepend.inc.php");

//trace(3,__LINE__,__FILE__, "Session:".SID);

//trace(3,__LINE__,__FILE__, "mode:".$mode);
//trace(3,__LINE__,__FILE__, "submode:".$submode);
// initialize vars
$jswarning="";

$mode=getData("mode");
$submode=getData("submode");

// different actions
$connExists=FALSE;
$connTest=TRUE;

switch($mode){
 case "connection":
     switch($submode){
     case "add":
	 $DBPass=getData("DBPass");
	 $DBUser=strtoupper(getData("DBUser"));
	 $DBName=strtoupper(getData("DBName"));
	 for($x=0; $x<count($DB->DBDatas); $x++){
	     if($DB->Name($x)==$DBName AND $DB->User($x)==$DBUser){
		 $connExists=TRUE;
		 continue;
	     }
	 }
	 if($connExists==FALSE){
	     $DBDatas[]=array("DBUser"=>$DBUser, "DBPass"=>$DBPass, "DBName"=>$DBName);
	     $DB->Add($DBUser, $DBPass, $DBName);
	     //trace(3,__LINE__,__FILE__, "connection not exists, inserting new");
	 } else {
	     //trace(3,__LINE__,__FILE__, "connection already exists");
	 }
	 break;
     case "drop":
	 $DropServer=getData("DropServer", "integer");
	 $DB->Drop($DB->User($DropServer), $DB->Name($DropServer));
	 unset($DBDatas[$DropServer]);
	 break;
     default:
	 trace(2,__LINE__,__FILE__, "wrong submode of mode connection");
     }
     break;
 default:
}

// warnings and so on
if($connTest==FALSE){
    $jswarning="<script language=\"JavaScript\">\n //<!--\n alert(\"Database not availiable or wrong Connection Datas\")\n //-->\n</script>";
}
if($CF->get("ENABLE_HTML_TREE")){
    if(!isset($GLOBALS[HTMLTREE_GET_VAR])){
	$GLOBALS[HTMLTREE_GET_VAR]=array();
    }
    $tree = new HTMLTree($GLOBALS[HTMLTREE_GET_VAR]);
} else {
    $tree = new Tree();
}

$tree->setForeignFrame("phpmain");
$tree->setIconPath("images");
$serverroot=$tree->openTree ("Databases Overview", "main.php?".SID);

for($Server=0; $Server<count($DB->DBDatas); $Server++){
    // build the serverfolder
    ${"root".$Server}=$tree->addFolder($serverroot, 
		       $DB->User($Server)."@".$DB->Name($Server), 
		       "database_detail.php?Server=$Server&".SID);

    //functions
    if($CF->get("ENABLE_FUNCTIONS")){
	$functions = $tree->addFolder(${"root".$Server}, "Functions", "");
	if(!$CF->get("ENABLE_HTML_TREE") || in_array($functions, $tree->opened)){
	    $func=new OraFunction($Server);
	    $func->getNames();
	    foreach($func->names as $username => $names){
		$user = $tree->addFolder($functions, 
					 $username."&nbsp;(".count($names).")", 
					 "func_main.php?Server=$Server&User=$username&".SID);
		if(is_array($names)){
		    foreach($names as $name){
			$tree->addDocument($user, 
					   $name, 
					   "func_detail.php?Server=$Server&Functionname=$name&User=$username&".SID);	
		    }
		}
	    }	    
	    $func->destruct();
	} else {
	    // insert one object
	    $tree->addFolder($functions, 0, 0);
	}

    }
    //indizies
    if($CF->get("ENABLE_INDEXES")){
	$indexes = $tree->addFolder(${"root".$Server}, "Indexes", "");
	if(!$CF->get("ENABLE_HTML_TREE") || in_array($indexes, $tree->opened)){
	    $ind=new Index($Server);
	    $ind->getNames();
	    foreach($ind->names as $username => $names){
		$user = $tree->addFolder($indexes , 
					 $username."&nbsp;(".count($names).")", 
					 "index_main.php?Server=$Server&User=$username&".SID);
		if(is_array($names)){
		    foreach($names as $name){
			$tree->addDocument($user, 
					   $name, 
					   "index_detail.php?Server=$Server&Indexname=$name&User=$username&".SID);	
		    }
		}
	    }
	    $ind->destruct();
	} else {
	    // insert one object
	    $tree->addFolder($indexes, 0, 0);
	}
    }
    // package
    if($CF->get("ENABLE_PACKAGES")){
	$pacs = $tree->addFolder(${"root".$Server}, "Packages", "");
	if(!$CF->get("ENABLE_HTML_TREE") || in_array($pacs, $tree->opened)){
	    $pac=new Package($Server);
	    $pac->getNames();
	    foreach($pac->names as $username => $names){
		$user = $tree->addFolder($pacs , 
					 $username."&nbsp;(".count($names).")", 
					 "pack_main.php?Server=$Server&User=$username&".SID);
		if(is_array($names)){
		    foreach($names as $name){
			$tree->addDocument($user, 
					   $name, 
					   "pack_detail.php?Server=$Server&Packagename=$name&User=$username&".SID);	
		    }
		}
	    }	    
	    $pac->destruct();
	} else {
	    // insert one object
	    $tree->addFolder($pacs, 0, 0);
	}
    }
    //procedures
    if($CF->get("ENABLE_PROCEDURES")){
	$procs = $tree->addFolder(${"root".$Server}, "Procedures", "");
	if(!$CF->get("ENABLE_HTML_TREE") || in_array($procs, $tree->opened)){
	    $proc=new Procedure($Server);
	    $proc->getNames();
	    foreach($proc->names as $username => $names){
		$user = $tree->addFolder($procs , 
					 $username."&nbsp;(".count($names).")", 
					 "proc_main.php?Server=$Server&User=$username&".SID);
		if(is_array($names)){
		    foreach($names as $name){
			$tree->addDocument($user, 
					   $name, 
					   "proc_detail.php?Server=$Server&Procedurename=$name&User=$username&".SID);
		    }
		}
	    }
	    $proc->destruct();
	} else {
	    // insert one object
	    $tree->addFolder($procs, 0, 0);
	}
    }
    //sequencepart
    if($CF->get("ENABLE_SEQUENCES")){
	$sequences = $tree->addFolder(${"root".$Server}, "Sequences", "");
	if(!$CF->get("ENABLE_HTML_TREE") || in_array($sequences, $tree->opened)){
	    $seq=new Sequence($Server);
	    $seq->getNames();
	    foreach($seq->names as $username => $names){
		$user = $tree->addFolder($sequences, 
					 $username."&nbsp;(".count($names).")", 
					 "seq_main.php?Server=$Server&User=$username&".SID); 
		if(is_array($names)){
		    foreach($names as $name){
			$tree->addDocument($user, 
					   $name, 
					   "seq_detail.php?Server=$Server&Sequencename=$name&User=$username&".SID);
			
		    }
		}
	    }
	    $seq->destruct();
	} else {
	    // insert one object
	    $tree->addFolder($sequences , 0, 0);
	}
    }
    // tablepart
    if($CF->get("ENABLE_TABLES")){
	$tables=$tree->addFolder(${"root".$Server}, "Tables", "");
	if(!$CF->get("ENABLE_HTML_TREE") || in_array($tables, $tree->opened)){
	    $tbl=new Table($Server);
	    $tbl->getNames();
	    foreach($tbl->names as $username => $names){
		$user=$tree->addFolder($tables , $username."&nbsp;(".count($names).")", 
				       "tbl_main.php?Server=$Server&User=$username&".SID);
		if(is_array($names)){
		    foreach($names as $name){
			$tree->addDocument($user, 
					   $name, 
					   "tbl_detail.php?Server=$Server&Tablename=$name&User=$username&".SID);
		    }
		}
	    }
	    $tbl->destruct();
	} else {
	    // insert one object
	    $tree->addFolder($tables , 0, 0);
	}
    }
    
    // tablespaces
    if($CF->get("ENABLE_TABLESPACES")){ 
	if($CF->get("ENABLE_DATAFILES")){
	    $file=new Datafile($Server);
	}
	$tablespaces=$tree->addFolder(${"root".$Server}, "Tablespaces", "tblsp_main.php?Server=$Server&".SID);
	if(!$CF->get("ENABLE_HTML_TREE") || in_array($tablespaces, $tree->opened)){
	    $tblsp=new Tablespace($Server);
	    $tblsp->getNames();
	    foreach($tblsp->names as $name){
		if($CF->get("ENABLE_DATAFILES")){ 
		    $file->getNames($name);
		    $urlname=rawurlencode($name);
		    $doc = $tree->addFolder($tablespaces, 
					    $name, 
					    "tblsp_detail.php?Server=$Server&Tablespacename=$urlname&".SID);
		    if(is_array($file->names)){
			foreach($file->names[$name] as $filename){
			    $fileurlname=rawurlencode($filename);
			    $tree->addDocument($doc, 
					       $filename, 
					       "datafile_detail.php?Server=$Server&Tablename=$name&Datafilename=$fileurlname".
					       "&".SID);
			}
		    }
		} else {
		    $urlname=rawurlencode($name);
		    $doc = $tree->addDocument($tablespaces, 
					      $name, 
					      "tblsp_detail.php?Server=$Server&Tablespacename=$urlname&".SID);
		}
	    }
	    $tblsp->destruct();
	} else {
	    // insert one object
	    $tree->addFolder($tablespaces , 0, 0);
	}
    }

    //triggerpart
    if($CF->get("ENABLE_TRIGGERS")){
	$triggers = $tree->addFolder(${"root".$Server}, "Triggers", "");
	if(!$CF->get("ENABLE_HTML_TREE") || in_array($triggers, $tree->opened)){
	    $trigger=new Trigger($Server);
	    $trigger->getNames();
	    foreach($trigger->names as $username => $names){
		$user = $tree->addFolder($triggers, 
					 $username."&nbsp;(".count($names).")", 
					 "tri_main.php?Server=$Server&User=$username&".SID);
		if(is_array($names)){
		    foreach($names as $name){
			$tree->addDocument($user, 
					   $name, 
					   "tri_detail.php?Server=$Server&Triggername=$name&User=$username&".SID);	
		    }
		}
	    }
	    $trigger->destruct();
	} else {
	    // insert one object
	    $tree->addFolder($triggers , 0, 0);
	}
    }
    //types
    if($CF->get("ENABLE_TYPES")){
	$types = $tree->addFolder(${"root".$Server}, "Types", "");
	if(!$CF->get("ENABLE_HTML_TREE") || in_array($triggers, $tree->opened)){
	    $type=new Type($Server);
	    $type->getNames();
	    foreach($type->names as $username => $names){
		$user = $tree->addFolder($types, 
					 $username."&nbsp;(".count($names).")", 
					 "type_main.php?Server=$Server&User=$username&".SID);
		if(is_array($names)){
		    foreach($names as $name){
			$tree->addDocument($user, 
					   $name, 
					   "type_detail.php?Server=$Server&Typename=$name&User=$username&".SID);	
		    }
		}
	    }
	    $type->destruct();
	} else {
	    // insert one object
	    $tree->addFolder($types , 0, 0);
	}	    
    }
    //views
    if($CF->get("ENABLE_VIEWS")){
	$views = $tree->addFolder(${"root".$Server}, "Views", "");
	if(!$CF->get("ENABLE_HTML_TREE") || in_array($views, $tree->opened)){
	    $view=new View($Server);
	    $view->getNames();
	    foreach($view->names as $username => $names){
		$user = $tree->addFolder($views, 
					 $username."&nbsp;(".count($names).")", 
					 "view_main.php?Server=$Server&User=$username&".SID);
		if(is_array($names)){
		    foreach($names as $name){
			$tree->addDocument($user, 
					   $name, 
					   "view_detail.php?Server=$Server&Viewname=$name&User=$username&".SID);	
		    }
		}
	    }
	    $view->destruct();
	} else {
	    // insert one object
	    $tree->addFolder($views , 0, 0);
	}
    }

}// end foreach
$tree->closeTree();

$page=new Page("phpOracleAdmin");
$page->setHead();
if($jswarning){
    $page->setHead($jswarning);
}
$page->setBody("<BODY BGCOLOR=\"#D0DCE0\">");
$page->setBody($tree->getTree());
$page->Display(FALSE);
?>



