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
$Sequencename=getData("Sequencename");

$html= "<H1> Database ".$DB->Name($Server)." - Sequence ".$Sequencename."</H1>";
if(isset($Sequencename)){
    $seq=new Sequence($Server);
    $seq->setOwner($User);
    $seq->setName($Sequencename);
    $seq->getData();
    $html.="<table border=0><tr><th>Last Number</th><th>Min Value</th><th>Max Value</th><th>Increment by</th><th>Cycle</th><th>Order</th><th>Cache Size</th></tr>";
    $html.="<tr bgcolor=\"#DDDDDD\"><td>";
    $html.=$seq->lastnumber;
    $html.="</td><td>";
    $html.=$seq->minval;
    $html.="</td><td>";
    $html.=$seq->maxval;
    $html.="</td><td>";
    $html.=$seq->incrementval;
    $html.="</td><td>";
    $html.=$seq->cycleflag;
    $html.="</td><td>";
    $html.=$seq->orderflag;
    $html.="</td><td>";
    $html.=$seq->cachesize;
    $html.="</td></tr></table>";
}
$page=new Page("Sequence Properties");
$page->setHead();
$page->setBody();
$page->setSQL();
$page->setBody($html);
$page->Display();
?>
