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
$User=getData("User");
$Userurl=rawurlencode($User);
$Tablename=getData("Tablename");
$Tablenameurl=rawurlencode($Tablename);

$Sortorder=getData("Sortorder");
$Sortfield=getData("Sortfield");

$mode=getData("mode");
$submode=getData("submode");
if(!$mode){
    $mode="main";
}

$defaultget=getData("PHP_SELF")."?Server=$Server&Tablename=$Tablenameurl&User=$Userurl&";

$menu=new IconBar();
$menu->registerMenu("rownum", "Rowcount", $defaultget."mode=main&submode=rownum&".SID, "images/rowcount.gif");
$menu->registerMenu("rename1", "Rename", $defaultget."mode=main&submode=rename1&".SID, "images/rename.gif");
$menu->registerMenu("truncate1", "Truncate", $defaultget."mode=main&submode=truncate1&".SID, "images/truncate.gif");
$menu->registerMenu("drop1", "Drop", $defaultget."mode=main&submode=drop1&".SID, "images/drop.gif");
$menu->selectMenu($submode);
$menu->renderHTML();
$html=$menu->getHTML();
unset($menu);
$html .="<br>";
/*
$html.="<a href=\"".$defaultget."mode=main&submode=analyze&".SID."\">".
"<img border=0 src=\"images/analyze.gif\" alt=\"Analyze Table $Tablename\" width=32 height=32><br>Analyze</a>";
$html.="</td><td align=center>";
*/

$menu=new MenuBar();
$menu->registerMenu("main", "Main", $defaultget."mode=main&".SID);
$menu->registerMenu("column", "Column Data", $defaultget."mode=column&".SID);
$menu->registerMenu("indizes", "Indizes", $defaultget."mode=indizes&".SID);
$menu->registerMenu("info", "Table Info", $defaultget."mode=info&".SID);
$menu->registerMenu("content", "Table Content", $defaultget."mode=content&".SID);
$menu->registerMenu("constraints", "Constraints", $defaultget."mode=constraints&".SID);
$menu->registerMenu("triggers", "Triggers", $defaultget."mode=triggers&".SID);
$menu->selectMenu($mode);
$menu->renderHTML();
$html.=$menu->getHTML();
unset($menu);

$html.= "<h1> Database  ".$DB->Name($Server)." - Table  ".$Tablename."</h1>";


// different views
switch($mode){
 case "column":
     switch($submode){
     default: // COLUMN DEFAULT
	 if(isset($Tablename)){
	     $tbl=new Table($Server);
	     $tbl->setOwner($User);
	     $tbl->setName($Tablename);
	     $tbl->getData();
	     // table comment
	     if($tbl->comments){
		 $html .="<br><table border=0><tr><th bgcolor=\"#DDDDDD\">Comments</th></tr>";
		 $html .="<tr bgcolor=\"#DDDDDD\"><td>".nl2br($tbl->comments)."<td><tr>";
		 $html .="</table><br>";
	     }
	     // table
	     $data=array();
	     if(is_array($tbl->columns)){
		 foreach($tbl->columns as $column){
		     $tmp["Fieldname"]=$column->name;
		     $tmp["Types"]=$column->type;
		     $tmp["Lenght"]=$column->length;
		     $tmp["Scale"]=$column->scale;
		     $tmp["Null"]=$column->nullable;
		     $tmp["Default"]=$column->data_default;
		     $data[]=$tmp;
		 }
		 $tbl=new DataTable($Server);
		 $tbl->setSorting(0);
		 $tbl->setData($data);
		 unset($data);
		 $tbl->setColorToggle(1);
		 $tbl->renderHTML();
		 $html.=$tbl->getHTML();
		 unset($tbl);
	     }
	 }
     }// end submode column
     break;
 case "content":
     switch($submode){
     default: // CONTENT DEFAULT
	 $tbl=new DataTable($Server);
	 $tbl->setSql("SELECT * FROM ".$User.".".$Tablename);
	 $tbl->setSorting(1);
	 $tbl->setSort($Sortfield, $Sortorder);
	 $tbl->loadData();
	 $tbl->setColorToggle(1);
	 $tbl->renderHTML($defaultget."mode=$mode&".SID);
	 $html.=$tbl->getHTML();
	 unset($tbl);
     }// end submode content
     break;
 case "info":
     switch($submode){
     default: 
	 $datatbl=new DataTable($Server);
	 $tbl=new Table($Server);
	 $tbl->setOwner($User);
	 $tbl->setName($Tablename);
	 $data=$tbl->getInfo();
	 unset($tbl);
	 $datatbl->setData($data);
	 $datatbl->setColorToggle(1);
	 $datatbl->renderHTML($defaultget."mode=$mode&".SID);
	 $html.=$datatbl->getHTML();
	 unset($datatbl);
     }// end submode content
     break;
 case "indizes":
     switch($submode){
     default: 
	 $tbl=new DataTable($Server);
	 $tbl->setSql(sprintf("select INDEX_NAME \"Index Name\", status \"Status\", ".
			     "UNIQUENESS \"Uniqueness\" from all_indexes where table_name='%s' and table_owner='%s'",
			     $Tablename, $User));
	 $tbl->setSorting(1);
	 $tbl->setSort($Sortfield, $Sortorder);
	 $tbl->loadData();
	 $tbl->setColorToggle(1);
	 if($CF->get("ENABLE_INDEXES")){
	     $tbl->setDataLink("Index Name", "index_detail.php?Server=$Server&Indexname=\$value&User=$Userurl&".SID);
	 }
	 $tbl->renderHTML($defaultget."mode=$mode&".SID);
	 $html.=$tbl->getHTML();
	 unset($tbl);
     }// end submode content
     break;
 case "constraints":
     switch($submode){
     default: 
	 $tbl=new DataTable($Server);
	 $tbl->setSql(sprintf("select CONSTRAINT_NAME \"Constraint Name\", CONSTRAINT_TYPE \"Type\", ".
			      "status \"Status\", deferred \"Deferred\", to_char(last_change,'MM-DD-YYYY') \"Last Changed\"".
			      "from all_constraints where table_name='%s' and owner='%s'",
			      $Tablename, $User));
	 $tbl->setSorting(1);
	 $tbl->setSort($Sortfield, $Sortorder);
	 $tbl->loadData();
	 $tbl->setColorToggle(1);
	 $tbl->renderHTML($defaultget."mode=$mode&".SID);
	 $html.=$tbl->getHTML();
	 unset($tbl);
     }// end submode content
     break;
 case "triggers":
     switch($submode){
     default: 
	 $tbl=new DataTable($Server);
	 $tbl->setSql(sprintf("select TRIGGER_NAME \"Trigger Name\", TRIGGER_TYPE \"Type\", ".
			      "status \"Status\", triggering_event \"Triggering on\" ".
			      "from all_triggers where table_name='%s' and table_owner='%s'",
			      $Tablename, $User));
	 $tbl->setSorting(1);
	 $tbl->setSort($Sortfield, $Sortorder);
	 $tbl->loadData();
	 $tbl->setColorToggle(1);
	 if($CF->get("ENABLE_TRIGGERS")){
	     $tbl->setDataLink("Trigger Name", "tri_detail.php?Server=$Server&Triggername=\$value&User=$Userurl&".SID);
	 }
	 $tbl->renderHTML($defaultget."mode=$mode&".SID);
	 $html.=$tbl->getHTML();
	 unset($tbl);
     }// end submode content
     break;
 case "main":
     switch($submode){
     case "rownum":
	 $html.="<h1>Number of Rows</h1>";
	 $tbl=new DataTable($Server);
	 $tbl->setSql("SELECT COUNT(*) FROM $User.$Tablename");
	 $tbl->loadData();
	 $tbl->renderHTML();
	 $html.=$tbl->getHTML();
	 unset($tbl);
	 break;
     case "rename1":
	 $html.="<h1>Rename Table</h1>";
	 $html.="<P>Please insert new Tablename:<br>".
	     "<form action=\"tbl_detail.php\" method=\"post\" enctype=\"application/x-www-form-urlencoded\">".
	     "<input type=\"text\" name=\"NEWTablename\" value=\"$Tablename\" size=\"32\" maxlength=\"32\">".
	     "<input type=\"hidden\" name=\"Tablename\" value=\"$Tablename\">".  
	     "<input type=\"hidden\" name=\"Server\" value=\"$Server\">".  
	     "<input type=\"hidden\" name=\"User\" value=\"$User\">".
	     "<input type=\"hidden\" name=\"mode\" value=\"main\">".
	     "<input type=\"hidden\" name=\"submode\" value=\"rename2\">".
	     "<input type=\"Submit\" name=\"BUTTON\" value=\"Execute\">";
	 $html.="</form></td></tr></table></p>";
	 break;
     case "rename2":
	 $html.="<h1>Rename Table</h1>";
	 $tbl=new Table($Server);
	 $tbl->setOwner($User);
	 $tbl->setName($Tablename);
	 $newname=getData("NEWTablename");
	 if($tbl->Rename($newname)){
	     header("Location: ".$defaultget."&Tablename=$newname&mode=main&".SID);
	 } else {
	     $html.="Table renaming failed.<br>";
	     $html.=$tbl->errorstring;
	 }
	 break;
     case "analyze":
	 $html.="<h1>Analyze Table</h1>";
	 
	 break;
    case "execute":
	 $statement=trim(getData("statement"));
	 $html.="<form action=\"tbl_detail.php\" method=\"post\" enctype=\"application/x-www-form-urlencoded\">".
	     "<input type=\"hidden\" name=\"Tablename\" value=\"$Tablename\">".
	     "<input type=\"hidden\" name=\"Server\" value=\"$Server\">".  
	     "<input type=\"hidden\" name=\"User\" value=\"$User\">".
	     "<input type=\"hidden\" name=\"mode\" value=\"main\">".
	     "<input type=\"hidden\" name=\"submode\" value=\"execute\">".
	     "<textarea name=\"statement\" cols=\"70\" rows=\"3\" wrap=\"VIRTUAL\" style=\"width: 300px\">".
	     $statement."</textarea><br>".
	     "<input type=\"Submit\" name=\"BUTTON\" value=\"Execute\">";
	 $html.="</form></td></tr></table>";
	 $tbl=new DataTable($Server);
	 $tbl->setSql($statement);
	 $tbl->setSorting(1);
	 $tbl->setSort($Sortfield, $Sortorder);
	 $tbl->loadData();
	 $tbl->setColorToggle(1);
	 $tbl->renderHTML($defaultget."mode=$mode&submode=$submode&statement=".rawurlencode($statement)."&".SID);
	 $html.=$tbl->getHTML();
	 unset($tbl);
	 break;
     case "drop1":
	 $html.="Do you really want to DROP TABLE $User.$Tablename?<br>".
	     "<form action=\"tbl_detail.php\" method=\"post\" enctype=\"application/x-www-form-urlencoded\">".
	     "<input type=\"hidden\" name=\"Tablename\" value=\"$Tablename\">".
	     "<input type=\"hidden\" name=\"Server\" value=\"$Server\">".  
	     "<input type=\"hidden\" name=\"User\" value=\"$User\">".
	     "<input type=\"hidden\" name=\"mode\" value=\"main\">".
	     "<input type=\"hidden\" name=\"submode\" value=\"drop2\">".
	     "<input type=\"Submit\" name=\"DROP\" value=\"yes\">&nbsp;&nbsp;".
	     "<input type=\"Submit\" name=\"DROP\" value=\"no\">";
	 $html.="</form></td></tr></table>";
	 break;
     case "truncate1":
	 $html.="Do you really want to DELETE FROM $User.$Tablename?<br>".
	     "<form action=\"tbl_detail.php\" method=\"post\" enctype=\"application/x-www-form-urlencoded\">".
	     "<input type=\"hidden\" name=\"Tablename\" value=\"$Tablename\">".   
	     "<input type=\"hidden\" name=\"Server\" value=\"$Server\">".  
	     "<input type=\"hidden\" name=\"User\" value=\"$User\">".
	     "<input type=\"hidden\" name=\"mode\" value=\"main\">".
	     "<input type=\"hidden\" name=\"submode\" value=\"truncate2\">";
	 $html.="<input type=\"Submit\" name=\"TRUNCATE\" value=\"yes\">&nbsp;&nbsp;".
	     "<input type=\"Submit\" name=\"TRUNCATE\" value=\"no\">";
	 $html.="</form></td></tr></table>";
	 break;
     case "truncate2":
	 $TRUNCATE=getData("TRUNCATE");
	 if($TRUNCATE=="yes"){
	     $tbl=new Table($Server);
	     $tbl->setOwner($User);
	     $tbl->setName($Tablename);
	     if(!$tbl->Truncate()){  
		 $html.="<P><B>Table truncating failed.</B></P>";
	     }
	 } elseif($TRUNCATE=="no"){ 
	     $html.="<P><B>Table truncating cancelled.</B></P>";
	 }
     case "drop2":
	 $DROP=getData("DROP");
	 if($DROP=="yes"){
	     $tbl=new Table($Server);
	     $tbl->setOwner($User);
	     $tbl->setName($Tablename);
	     if(!$tbl->Drop()){  
		 $html.="<P><B>Table dropping failed.</B></P>";
	     }
	 } elseif($DROP=="no"){ 
	     $html.="<P><B>Table dropping cancelled.</B></P>";
	 }
     default: 
	 $html.="<P>Execute SQL Statement:<br>".
	     "<form action=\"tbl_detail.php\" method=\"post\" enctype=\"application/x-www-form-urlencoded\">".
	     "<input type=\"hidden\" name=\"Tablename\" value=\"$Tablename\">".
	     "<input type=\"hidden\" name=\"Server\" value=\"$Server\">".  
	     "<input type=\"hidden\" name=\"User\" value=\"$User\">".
	     "<input type=\"hidden\" name=\"mode\" value=\"main\">".
	     "<input type=\"hidden\" name=\"submode\" value=\"execute\">".
	     "<textarea name=\"statement\" cols=\"60\" rows=\"3\" wrap=\"VIRTUAL\" style=\"width: 300px\"></textarea><br>".
	     "<input type=\"Submit\" name=\"BUTTON\" value=\"Execute\">";
	 $html.="</form></td></tr></table></p>";
     }// end submode content
     break;
 default:
}// end switch mode


$page=new Page("Table Management - ".$User.".".$Tablename);
$page->setHead();
$page->setBody();
$page->setSQL();
$page->setBody($html);
$page->Display();
?>
