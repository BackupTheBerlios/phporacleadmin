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

$seq=new Sequence($Server);
$seq->getDatas($User);

$html ="<H1> Database ".$DB->Name($Server)." - Sequence overview of User $User</H1>";

$html .="<TABLE BORDER=0>\n<TR><TH>Sequence</TH><TH>Last Number</TH>";
$html .="<TH COLSPAN=4>Actions</TH><TH>Min Value</TH><TH>Max Value</TH>".
"<TH>Increment by</TH><TH>Cache</TH><TH>Order</TH><TH>Cycle</TH></TR>\n";
$x=0;
if(is_array($seq->datas[$User])){
    foreach($seq->datas[$User] as $sequence){
	$html .=$x % 2 ? "<tr bgcolor=\"#EEEEEE\" valign=top>" : "<tr bgcolor=\"#CCCCCC\" valign=top>";
	$html .="<td class=data><b>".$sequence["SEQUENCE_NAME"]."</b></td>";
	$html .="<td>".$sequence["LAST_NUMBER"]."</td>";
	$html .="<td>Modify</td>";
	$html .="<td><a href=\"seq_detail.php?Server=$Server&Sequencename=".
	    $sequence["SEQUENCE_NAME"]."&User=$User\">Properties</a></td>";
	$html .="<td>Drop</td>";
	$html .="<td>Reset</td>";
	
	$html .="<td align=left>".$sequence["MIN_VALUE"]."</td>";
	$html .="<td align=left>".$sequence["MAX_VALUE"]."</td>";
	$html .="<td align=left>".$sequence["INCREMENT_BY"]."</td>";
	$html .="<td align=left>".$sequence["CACHE_SIZE"]."</td>";
	$html .="<td align=left>".$sequence["ORDER_FLAG"]."</td>";
	$html .="<td align=left>".$sequence["CYCLE_FLAG"]."</td></tr>";
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
