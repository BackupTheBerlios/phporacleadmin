<?php
/***************************************************************************
    begin                : Son Jan 28 18:08:45 CET 2001
    copyright            : (C) by Thomas Fromm
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

$defaultget=getData("PHP_SELF")."?Server=$Server&";

$mode=getData("mode");
$submode=getData("submode");
if(!$mode){
    $mode="main";
}

$Sortorder=getData("Sortorder");
$Sortfield=getData("Sortfield");

$menu=new MenuBar();
$menu->registerMenu("main", "Main", $defaultget."mode=main&".SID);
$menu->selectMenu($mode);
$menu->renderHTML();
$html=$menu->getHTML();
unset($menu);

$html.= "<h1> Database  ".$DB->Name($Server)." - User ".$DB->User($Server) ."</h1>";

// different views
switch($mode){
 case "main":
     switch($submode){
     case "execute":
	 $statement=trim(getData("statement"));
	 $html.="<form action=\"database_detail.php\" method=\"post\" enctype=\"application/x-www-form-urlencoded\">".
	     "<input type=\"hidden\" name=\"Server\" value=\"$Server\">".  
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
     default:
	 $html.="<P>Execute SQL Statement:<br>".
	     "<form action=\"database_detail.php\" method=\"post\" enctype=\"application/x-www-form-urlencoded\">".
	     "<input type=\"hidden\" name=\"Server\" value=\"$Server\">".
	     "<input type=\"hidden\" name=\"mode\" value=\"main\">".
	     "<input type=\"hidden\" name=\"submode\" value=\"execute\">".
	     "<textarea name=\"statement\" cols=\"60\" rows=\"3\" wrap=\"VIRTUAL\" style=\"width: 300px\"></textarea><br>".
	     "<input type=\"Submit\" name=\"BUTTON\" value=\"Execute\">";
	 $html.="</form></td></tr></table></p>";
     }
     break;
 default:
}

$page=new Page("Database  ".$DB->Name($Server)." - User ".$DB->User($Server));
$page->setHead();
$page->setBody();
$page->setSQL();
$page->setBody($html);
$page->Display();
