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
$Packagename=getData("Packagename");

$html= "<h1> Database  ".$DB->Name($Server)." - Package ".$Packagename."</h1>";
if($Packagename){
    $pack=new Package($Server);
    $pack->setOwner($User);
    $pack->setName($Packagename);
    $pack->getData();
    $html .="<table border=0><tr><th>Tag</th><th>Value</th></tr>";
    $html.="<tr><td>Name</td><td>".$pack->name."</td></tr>";
    $html.="<tr><td>Owner</td><td>".$pack->owner."</td></tr>";
    $html.="<tr><td>Created</td><td>".$pack->created."</td></tr>";
    $html.="<tr><td>Last Modified</td><td>".$pack->lastmodified."</td></tr>";
    $html.="<tr><td>Status</td><td>".$pack->status."</td></tr>";
    $html .="</table>";
    
    $html .="<P><B>Package body:</b><br>";
    $html .=nl2br($pack->sql)."<br>";
    $html.="</P>";
}

$page=new Page("Package Properties");
$page->setHead();
$page->setBody();
$page->setSQL();
$page->setBody($html);
$page->Display();
?>
