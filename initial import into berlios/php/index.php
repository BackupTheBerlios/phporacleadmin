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

// precheck that oci8 support is enabled
if(!function_exists("ocilogon")){
    echo "<HTML><HEAD><title>PHP has no 0ci8 support!</title></HEAD><BODY>".
	"<h1>Wrong PHP Installation for phpOracleAdmin!</h1>";
    echo "You must have installed PHP 4 with --with-oci8 support!<br>";
    echo "For further Informations please read the PHP Installation Manual ".
	"on <a href=\"http://www.php.net/manual/installation.php\">http://php.net</a>";
    echo "</body></html>";
    die();
}

include("prepend.inc.php");

// normal output
if(function_exists("session_start") && $CF->get("ENABLE_SESSION_FUNCTIONS")){
    $html="<frameset rows=\"80,*\" rows=\"*\" border=\"0\" frameborder=\"0\">\n".
	"<frame src=\"top.php?".SID."\" name=\"topmenu\">\n".
	"<frameset cols=\"23%,*\" rows=\"*\" border=\"0\" frameborder=\"0\">\n".
	"<frame src=\"left.php?Server=0&".SID."\" name=\"nav\">\n".
	"<frame src=\"main.php?".SID."\" name=\"phpmain\">\n".
	"</frameset>\n".
	"</frameset>\n";
} else {
    $html="<frameset cols=\"23%,*\" rows=\"*\" border=\"0\" frameborder=\"0\">\n".
	"<frame src=\"left.php?Server=0&".SID."\" name=\"nav\">\n".
	"<frame src=\"main.php?".SID."\" name=\"phpmain\">\n".
	"</frameset>\n";
}
$html .="<noframes>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</noframes>\n";

$page=new Page("phpOracleAdmin");
$page->setBody($html);
$page->Display();
?>

