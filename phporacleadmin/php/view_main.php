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

$view=new View($Server);
$view->getDatas($User);

$html ="<H1> Database ".$DB->Name($Server)." - View overview of User $User</H1>\n";

$html .="<TABLE BORDER=0>\n<TR><TH>Name</TH><TH>Comment</TH></TR>\n";
$x=0;
if(is_array($view->datas[$User])){
    foreach($view->datas[$User] as $views){
	$html .=$x % 2 ? "<tr bgcolor=\"#EEEEEE\" valign=top>" : "<tr bgcolor=\"#CCCCCC\" valign=top>";
	$html .="<td class=data><b>".$views["VIEW_NAME"]."</b></td>";
	$html .="<td>".$views["COMMENTS"]."</td>";
	$html .="</tr>";
	$x++;
    }
}
$html .="</table>";

$page=new Page("View Overview");
$page->setHead();
$page->setBody();
$page->setSQL();
$page->setBody($html);
$page->Display();
?>
