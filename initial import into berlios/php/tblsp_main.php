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

$Sortorder=getData("Sortorder");
$Sortfield=rawurldecode(getData("Sortfield"));

$html ="<H1> Database ".$DB->Name($Server)." - Tablespace Overview</H1>\n";

$tbl=new DataTable($Server);
$tbl->setSql("SELECT tablespace_name \"Tablespace Name\", initial_extent \"Initial Extent\", ".
	     "next_extent \"Next Extent\", min_extents \"Min Extents\", max_extents \"Max Extents\", ".
	     "pct_increase \"% Increase\", status \"Status\" FROM dba_tablespaces order by \"Tablespace Name\"");
$tbl->setSorting(1);
$tbl->setSort($Sortfield, $Sortorder);
$tbl->loadData();
$tbl->setColorToggle(1);
$tbl->setDataLink("Tablespace Name", "tblsp_detail.php?Server=$Server&Tablespacename=\$value&".SID);
$tbl->renderHTML("tblsp_main.php?Server=$Server&".SID);
$html.=$tbl->getHTML();

$page=new Page("Tablespace Overview");
$page->setHead();
$page->setBody();
$page->setSQL();
$page->setBody($html);
$page->Display();

?>
