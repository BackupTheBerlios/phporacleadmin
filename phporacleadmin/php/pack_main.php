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

$pack=new Package($Server);
$pack->getDatas($User);

$html ="<H1> Database ".$DB->Name($Server)." - Package overview of User $User</H1>\n";

$html .="<TABLE BORDER=0>\n<TR><TH>Name</TH><TH>Created</TH><TH>Last Modified</TH><TH>Status</TH></TR>\n";
$x=0;
if(is_array($pack->datas[$User])){
    foreach($pack->datas[$User] as $package){
	$html .=$x % 2 ? "<tr bgcolor=\"#EEEEEE\" valign=top>" : "<tr bgcolor=\"#CCCCCC\" valign=top>";
	$html .="<td class=data><b>".$package["OBJECT_NAME"]."</b></td>";
	$html .="<td>".$package["CREATED"]."</td>";
	$html .="<td>".$package["LASTMODIFIED"]."</td>";
	$html .="<td>".$package["STATUS"]."</td>";
	$html .="</tr>";
	$x++;
    }
}
$html .="</table>";

$page=new Page("Package Overview");
$page->setHead();
$page->setBody();
$page->setSQL();
$page->setBody($html);
$page->Display();
?>
