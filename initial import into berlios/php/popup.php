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
/*
This File manages the Popups and builds the forms
for them
 */
include("prepend.inc.php");

$mode=getData("mode");
$submode=getData("submode");

switch($mode){

 case "connection":
     switch($submode){
     case "addform":
	 $defaultdb="";
	 if(count($DB->DBDatas)>=1){
	     $defaultdb=$DB->Name(count($DB->DBDatas)-1);
	 }
	 $title="Question";
	 $head="";
	 $body="";
	 $html="<form name=\"form\" action=\"left.php?".SID."\" method=POST target=\"nav\">";
	 $html.="Please insert Connections Data:<br><table>";
	 $html.="<tr><td>Username:</td><td><input type=text name=\"DBUser\" size=15></td></tr>".
	     "<tr><td>Password:</td><td><input type=password name=\"DBPass\" size=15></td></tr>".
	     "<tr><td>DBName:</td><td><input type=text name=\"DBName\" size=15 value=\"$defaultdb\"></td></tr>".
	     "<input type=hidden name=\"mode\" value=\"connection\">".
	     "<input type=hidden name=\"submode\" value=\"add\">".	     
	     "<tr><td align=left><input type=submit value=\"Add\"></td><td align=right>".
	     "<input type=button value=\"Close\" onClick=\"window.close()\"></td></tr>".
	     "</table></form>";
	 break;
    case "dropform":
	 $title="Question";
	 $head="";
	 $body="";
	 $html="<form name=\"form\" action=\"left.php?".SID."\" method=POST target=\"nav\">".
	     "Close which Connection ?<br><select name=\"DropServer\" size=5>";
	 for($x=0; $x<count($DB->DBDatas); $x++){
	     $html.= "<option value=\"$x\">".$DB->DBDatas[$x]["DBUser"]."@".$DB->DBDatas[$x]["DBName"];
	 }
	 $html.= "</select><br><input type=hidden name=\"mode\" value=\"connection\">".
	     "<input type=hidden name=\"submode\" value=\"drop\">".	     
	     "<table><tr><td align=left><input type=submit value=\"Drop\"></td><td align=right>".
	     "<input type=button value=\"Close\" onClick=\"window.close()\"></td></tr>".
	     "</table></form>";
	 break;
     default:
	 trace(2,__LINE__,__FILE__, "wrong submode of mode connection");
     }
     break;
 case "info":
     switch($submode){
     case "aboutform":
	 $title="About";
	 $head="";
	 $body="<BODY BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\">";
	 $html="<form name=\"form\" action=\"top.php?".SID."\" method=POST target=\"topmenu\">";
	 $html.="<table><tr><td align=center valign=top colspan=2><img src=\"images/logo.gif\"></td></tr>";
	 $html.="<tr><td colspan=2 align=left>About phpOracleAdmin:</td></tr>";
	 $html.="<tr><td colspan=2>Version ".$GLOBALS["VERSION"]."</td></tr>";
	 $html.="<tr><td>Homepage</td><td><a href=\"http://phporacleadmin.org\">http://phporacleadmin.org</a></td></tr>";
	 $html.="<tr><td>Developer:</td><td>Thomas Fromm</td></tr>";
	 $html.="<tr><td>Logo:</td><td>Thomas Weinert</td></tr>";
	 $html.="<tr><td colspan=2 align=center><input type=button value=\"Close\" onClick=\"window.close()\"></td></tr>".
	     "</table></form>";
	 break;
     default:
	 trace(2,__LINE__,__FILE__, "wrong submode of mode info");
     }
     break;
 default:
     trace(2,__LINE__,__FILE__, "wrong mode");
}

$page=new Page($title);
$page->setHead($head);
$page->setBody($body);
$page->setBody($html);
$page->Display();
?>
