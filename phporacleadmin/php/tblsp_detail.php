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

$Server=getData("Server", "integer");
$Tablespacename=getData("Tablespacename");
$Tablespacenameurl=rawurlencode($Tablespacename);

$Sortorder=getData("Sortorder");
$Sortfield=getData("Sortfield");

$mode=getData("mode");
$submode=getData("submode");

$defaultget=getData("PHP_SELF")."?Server=$Server&Tablespacename=$Tablespacenameurl&";

$menu=new MenuBar();
$menu->registerMenu("info", "Overview", $defaultget."mode=info&".SID);
$menu->registerMenu("datafiles", "Datafiles", $defaultget."mode=datafiles&".SID);
//$menu->registerMenu("users", "Users", $defaultget."mode=users&".SID);
$menu->selectMenu($mode);
$menu->renderHTML();
$html=$menu->getHTML();
unset($menu);

$html.= "<h1> Database  ".$DB->Name($Server)." - Tablespace  ".$Tablespacename."</h1>";

// different views
switch($mode){
 case "info":
     switch($submode){
     default:
	 $tblsp=new Tablespace($Server);
	 $tblsp->setName($Tablespacename);
	 $tblsp->getData();
	 $data=array();
	 if(is_array($tblsp->data)){
	     foreach($tblsp->data as $name => $val){
		 $data[]=array("Tag"=>$name, "Value"=>$val);
	     }
	 }
	 unset($tblsp);
	 $tbl=new Datatable($Server);
	 $tbl->setSorting(0);
	 $tbl->setData($data);
	 unset($data);
	 $tbl->setColorToggle(1);
	 $tbl->renderHTML();
	 $html.=$tbl->getHTML();
	 unset($tbl);
     }// end switch submode
     break;
 case "datafiles":
     switch($submode){
     default:
	 $tbl=new DataTable($Server);
	 $tbl->setSql(sprintf("SELECT file_name \"Filename\", bytes / 1024 \"Kilobytes\", ".
			      "status \"Status\"  FROM dba_data_files WHERE tablespace_name = '%s' ".
			      "ORDER BY file_name", $Tablespacename));
	 $tbl->setSorting(1);
	 $tbl->setSort($Sortfield, $Sortorder);
	 $tbl->loadData();
	 $tbl->setColorToggle(1);
	 if($CF->get("ENABLE_DATAFILES")){
	     $tbl->setDataLink("Filename", "datafile_detail.php?Server=$Server&Datafilename=\$value&".
				"Tablespacename=$Tablespacenameurl&".SID);
	 }
	 $tbl->renderHTML($defaultget."mode=$mode&".SID);
	 $html.=$tbl->getHTML();
	 unset($tbl);
     }// end switch submode
     break;
 case "users":
     switch($submode){
     default:

     }// end switch submode
     break;
}

$page=new Page("Tablespace Management - ".$Tablespacename);
$page->setHead();
$page->setBody();
$page->setSQL();
$page->setBody($html);
$page->Display();
