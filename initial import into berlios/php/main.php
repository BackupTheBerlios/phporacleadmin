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

$html="<H1>Welcome to phpOracleAdmin ".$GLOBALS["VERSION"]."</H1>";

if(count($DB->DBDatas)==0){
    $html.="<BR>There is no Database availiable. Please insert Username, Password and Databasename in the config.inc.php!";
} else {
    $counter=count($DB->DBDatas);
    $html .="Actual Connections:<BR><BR>";
    $active="<IMG SRC=\"images/active.gif\" ALT=\"Database is availiable\">";
    $inactive="<IMG SRC=\"images/inactive.gif\" ALT=\"Database not running or wrong Username/Password\">";
    for($x=0; $x<$counter; $x++){
	$db=new Database($x);
	$html.="<P>";
	$html.=$db->conn ? $active : $inactive;
	$html.="&nbsp;<B>".$DB->User($x)."</B>&nbsp;at&nbsp;<B>".$DB->Name($x)."</B><BR>";
	$html.=$db->version;
	$html.="\n</P>";
	$db->destruct();
    }
}
$html.="<br><br>Please read the Installation and Security hints in the INSTALL file!"; 

$html.="<BR><BR>Watch out for updates at <a href=\"http://phporacleadmin.org\" target=_new>http://phporacleadmin.org</a>.<br>";
$html.="For Bugreports and other Feedback write to <a href=\"mailto:tf@tfromm.com\">tf@tfromm.com</a>.";

$html.="<BR><BR>Please do not leave this Admintool unprotected and open for public access.<br>";
$html.="Use .htaccess or similar to avoid unfriendly Guests!<br>";

$html.="<br>Otherwise set \$HTTP_AUTH_ENABLED=TRUE at the config.inc.php and \$HTTP_AUTH_LOGIN[0]= to your Login <br>";
$html.="and \$HTTP_AUTH_LOGIN[0]= to the Password!<br>";

$page=new Page("phpOracleAdmin");
$page->setHead();
$page->setBody();
$page->setBody($html);
$page->Display();
?>




