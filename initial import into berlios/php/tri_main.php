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

$tri=new Trigger($Server);
$tri->getDatas($User);

$html ="<H1> Database ".$DB->Name($Server)." - Trigger overview of User $User</H1>\n";

$html .="<TABLE BORDER=0>\n<TR><TH>Name</TH><TH>Type</TH><TH>On</TH><TH>Status</TH></TR>\n";
$x=0;
if(is_array($tri->datas[$User])){
    foreach($tri->datas[$User] as $trigger){
	$html .=$x % 2 ? "<tr bgcolor=\"#EEEEEE\" valign=top>" : "<tr bgcolor=\"#CCCCCC\" valign=top>";
	$html .="<td class=data><b>".$trigger["TRIGGER_NAME"]."</b></td>";
	$html .="<td>".$trigger["TYPE"]."</td>";
	$html .="<td>".$trigger["TABLE_OWNER"].".".$trigger["TABLE_NAME"]."</td>";
	$html .="<td>".$trigger["STATUS"]."</td>";
	$html .="</tr>";
	$x++;
    }
}
$html .="</table>";

$page=new Page("Sequence Overview");
$page->setHead();
$page->setBody();
$page->setSQL();
$page->setBody($html);
$page->Display();
?>
