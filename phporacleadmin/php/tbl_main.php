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
$Userurl=rawurlencode($User);

$tbl=new Table($Server);
$tbl->getDatas($User);


$html ="<H1> Database ".$DB->Name($Server)." - Table overview of User $User</H1>\n";

$dtbl=new DataTable($Server);
$dtbl->setSorting(0);
$dtbl->setData($tbl->datas[$User]);
unset($tbl);
$dtbl->setColorToggle(1);
$dtbl->setDataLink("TABLE_NAME", "tbl_detail.php?Server=$Server&Tablename=\$value&User=$Userurl&".SID);
$dtbl->renderHTML();
$html.=$dtbl->getHTML();
unset($dtbl);

$page=new Page("Table Overview");
$page->setHead();
$page->setBody();
$page->setSQL();
$page->setBody($html);
$page->Display();
?>
