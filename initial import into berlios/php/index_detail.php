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
$Indexname=getData("Indexname");

$html= "<h1> Database  ".$DB->Name($Server)." - Index ".$Indexname."</h1>";
if($Indexname){
    $ind=new Index($Server);
    $ind->setOwner($User);
    $ind->setName($Indexname);
    $ind->getData();
    $html .="<table border=0><tr><th>Tag</th><th>Value</th></tr>";
    $html.="<tr><td>Name</td><td>".$ind->name."</td></tr>";
    $html.="<tr><td>Owner</td><td>".$ind->owner."</td></tr>";
    $html.="<tr><td>Table</td><td>".$ind->table_owner.".".$ind->table_name."</td></tr>";
    $html.="<tr><td>Tablespace</td><td>".$ind->tablespace_name."</td></tr>";
    $html.="<tr><td>Table Type</td><td>".$ind->table_type."</td></tr>";
    $html.="<tr><td>Uniqueness</td><td>".$ind->uniqueness."</td></tr>";
    $html.="<tr><td>Initial Trans</td><td>".$ind->ini_trans."</td></tr>";
    $html.="<tr><td>Max Trans</td><td>".$ind->max_trans."</td></tr>";
    $html.="<tr><td>Initial Extents</td><td>".$ind->initial_extent."</td></tr>";
    $html.="<tr><td>Next Extents</td><td>".$ind->next_extent."</td></tr>";
    $html.="<tr><td>Min Extents</td><td>".$ind->min_extents."</td></tr>";
    $html.="<tr><td>Max Extents</td><td>".$ind->max_extents."</td></tr>";
    $html.="<tr><td>% Increase</td><td>".$ind->pct_increase."</td></tr>";
    $html.="<tr><td>Status</td><td>".$ind->status."</td></tr>";
    $html.="<tr><td>Buffer Pool</td><td>".$ind->buffer_pool."</td></tr>";
    $html .="</table>";
    
    $html .="<P><B>Indexed Tables:</b><br>";
    foreach($ind->column_name as $column){
	$html .=$column."<br>";
    }
    $html.="</P>";
}

$page=new Page("Index Properties");
$page->setHead();
$page->setBody();
$page->setSQL();
$page->setBody($html);
$page->Display();
?>
