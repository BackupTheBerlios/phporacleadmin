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
The menuebar on the top
 */
include("prepend.inc.php");

//initialize vars
$head="";

$mode=getData("mode");
$submode=getData("submode");

// popup for additional connections
switch($mode){

 case "connection":
     switch($submode){
     case "addask":
	 $head= "<script language=\"JavaScript\">".
	     "<!--\n function phpOracleadminAskPopup()".
	     "{\n".
	     "var popupURL = \"popup.php?mode=connection&submode=addform&".SID."\";".
	     "var popup = window.open(popupURL,\"phpOracleadminAsk\",'toolbar=0,location=0,".
	     "directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=250,height=200');\n".
	     "popup.location = popupURL;\n".
	     "popup.focus();\n".
	     "}\n".
	     "phpOracleadminAskPopup();\n".
	     "// --></script>";
	 break;
    case "dropask":
	 $head= "<script language=\"JavaScript\">".
	     "<!--\n function phpOracleadminAskPopup()".
	     "{\n".
	     "var popupURL = \"popup.php?mode=connection&submode=dropform&".SID."\";".
	     "var popup = window.open(popupURL,\"phpOracleadminAsk\",'toolbar=0,location=0,".
	     "directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=250,height=200');\n".
	     "popup.location = popupURL;\n".
	     "popup.focus();\n".
	     "}\n".
	     "phpOracleadminAskPopup();\n".
	     "// --></script>";
	 break;
     default:
	 trace(2,__LINE__,__FILE__, "wrong submode of mode connection");
     }
     break;
 case "info":
     switch($submode){
     case "about":
	 $head= "<script language=\"JavaScript\">".
	     "<!--\n function phpOracleadminAskPopup()".
	     "{\n".
	     "var popupURL = \"popup.php?mode=info&submode=aboutform&".SID."\";".
	     "var popup = window.open(popupURL,\"About\",'toolbar=0,location=0,".
	     "directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=250,height=300');\n".
	     "popup.location = popupURL;\n".
	     "popup.focus();\n".
	     "}\n".
	     "phpOracleadminAskPopup();\n".
	     "// --></script>";
	 break;
     default:
     }
     break;
 default:
}
$html="<table width=\"100%\">\n<tr>\n<td align=left>\n";
$html.="<table><tr><td align=center>";
$html.="<a href=\"top.php?mode=connection&submode=addask&".SID."\" target=\"topmenu\">".
"<img border=0 src=\"images/user-menu.gif\" alt=\"Add Connection\" width=32 height=32><br>Add<br>Connection</a>";
$html.="</td><td align=center>";
$html.="<a href=\"top.php?mode=connection&submode=dropask&".SID."\" target=\"topmenu\">".
"<img border=0 src=\"images/drop.gif\" alt=\"Drop Connection\" width=32 height=32><br>Drop<br>Connection</a>";
$html.="</td><td align=center>";
$html.="<a href=\"configuration.php?".SID."\" target=\"phpmain\">".
"<img border=0 src=\"images/config.gif\" alt=\"Session Configuration\" width=32 height=32><br>Session<br>Configuration</a>";
$html.="</td></tr></table>\n";
$html.="</td><td align=right valign=top><a href=\"top.php?mode=info&submode=about&".SID.
"\" target=\"topmenu\"><b>About</b></a></td>\n</tr>\n</table>\n";

$page=new Page("Top Menu");
$page->setHead();
if($head){
    $page->setHead($head);
}
$page->setBody("<body bgcolor=\"#F5F5F5\" text=\"#000000\">");

$page->setBody($html);
$page->Display();
?>

