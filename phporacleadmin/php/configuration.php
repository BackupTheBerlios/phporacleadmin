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

$html="<h1>Session Configuration</h1>\n";

$html.="\n<form name=\"form\" action=\"index.php?mode=sessionconfig&submode=submit&".SID."\" method=POST target=\"_top\">\n";
$html.="<table><tr><th><b>Parameter</b></th><th><b>Value</b></th><th><b>Description</b></th></tr>";
$html.="\n<tr><td>Object Settings:</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
$html.="\n<tr><td>Enable Tables</td><td><input type=checkbox name=\"FORM_ENABLE_TABLES\" value=\"1\" ";
$html.=$CF->get("ENABLE_TABLES") ? "checked" : "";
$html.="></td><td>";
$html.="These Checkboxes enables viewing of these Objects. Disable everything what you don't need.";
$html.="</td></tr>";
$html.="\n<tr><td>Enable Sequences</td><td><input type=checkbox name=\"FORM_ENABLE_SEQUENCES\" value=\"1\" ";
$html.=$CF->get("ENABLE_SEQUENCES") ? "checked" : "";
$html.="></td><td>&nbsp;</td></tr>";
$html.="\n<tr><td>Enable Triggers</td><td><input type=checkbox name=\"FORM_ENABLE_TRIGGERS\" value=\"1\" ";
$html.=$CF->get("ENABLE_TRIGGERS") ? "checked" : "";
$html.="></td><td>&nbsp;</td></tr>";    
$html.="\n<tr><td>Enable Functions</td><td><input type=checkbox name=\"FORM_ENABLE_FUNCTIONS\" value=\"1\" ";
$html.=$CF->get("ENABLE_FUNCTIONS") ? "checked" : "";
$html.="></td><td>&nbsp;</td></tr>";
$html.="\n<tr><td>Enable Indexes</td><td><input type=checkbox name=\"FORM_ENABLE_INDEXES\" value=\"1\" ";
$html.=$CF->get("ENABLE_INDEXES") ? "checked" : "";
$html.="></td><td>&nbsp;</td></tr>";
$html.="\n<tr><td>Enable Packages</td><td><input type=checkbox name=\"FORM_ENABLE_PACKAGES\" value=\"1\" ";
$html.=$CF->get("ENABLE_PACKAGES") ? "checked" : "";
$html.="></td><td>&nbsp;</td></tr>";
$html.="\n<tr><td>Enable Procedures</td><td><input type=checkbox name=\"FORM_ENABLE_PROCEDURES\" value=\"1\" ";
$html.=$CF->get("ENABLE_PROCEDURES") ? "checked" : "";
$html.="></td><td>&nbsp;</td></tr>";
$html.="\n<tr><td>Enable Types</td><td><input type=checkbox name=\"FORM_ENABLE_TYPES\" value=\"1\" ";
$html.=$CF->get("ENABLE_TYPES") ? "checked" : "";
$html.="></td><td>&nbsp;</td></tr>";

$html.="\n<tr><td>Enable Tablespaces</td><td><input type=checkbox name=\"FORM_ENABLE_TABLESPACES\" value=\"1\" ";
$html.=$CF->get("ENABLE_TABLESPACES") ? "checked" : "";
$html.="></td><td>&nbsp;</td></tr>";

$html.="\n<tr><td>Enable Views</td><td><input type=checkbox name=\"FORM_ENABLE_VIEWS\" value=\"1\" ";
$html.=$CF->get("ENABLE_VIEWS") ? "checked" : "";
$html.="></td><td>&nbsp;</td></tr>";

$html.="\n<tr><td>Enable Datafiles</td><td><input type=checkbox name=\"FORM_ENABLE_DATAFILES\" value=\"1\" ";
$html.=$CF->get("ENABLE_DATAFILES") ? "checked" : "";
$html.="></td><td>&nbsp;</td></tr>";

$html.="\n<tr><td>Additional Settings:</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
$html.="\n<tr><td>View All Users</td><td><input type=checkbox name=\"FORM_USE_ALL_USER_DATA\" value=\"1\" ";
$html.=$CF->get("USE_ALL_USER_DATA") ? "checked" : "";
$html.="></td><td>Enable this, and you can see all availiable datas also from other users (if possible)</td></tr>";
$html.="\n<tr><td>View Executed SQL</td><td><input type=checkbox name=\"FORM_ENABLE_VIEW_STATEMENTS\" value=\"1\" ";
$html.=$CF->get("ENABLE_VIEW_STATEMENTS") ? "checked" : "";
$html.="></td><td>Enable this, and you can see the executed statements</td></tr>";

$html.="\n<tr><td>Set Limit for Tablecontent</td><td><input type=text name=\"FORM_TABLE_ROW_LIMIT\" value=\"".$CF->get("TABLE_ROW_LIMIT")."\"";
$html.="></td><td>&nbsp;</td></tr>";

$html.="\n<tr><td>Set Limit for Fieldcontentlength</td><td><input type=text name=\"FORM_TABLE_FIELDLENGTH_LIMIT\" value=\"".$CF->get("TABLE_FIELDLENGTH_LIMIT")."\"";
$html.="></td><td>&nbsp;</td></tr>";

$html.="\n<tr><td>HTML Optimization</td><td><input type=checkbox name=\"FORM_ENABLE_HTML_OPTIMIZATION\" value=\"1\" ";
$html.=$CF->get("ENABLE_HTML_OPTIMIZATION") ? "checked" : "";
$html.="></td><td>The HTML Output is no longer well-formated (so far it is). All unnessesary Elements are removed. (decreases sitesizes up to 10%)</td></tr>";

$html.="\n<tr><td>Enable HTML Tree</td><td><input type=checkbox name=\"FORM_ENABLE_HTML_TREE\" value=\"1\" ";
$html.=$CF->get("ENABLE_HTML_TREE") ? "checked" : "";
$html.="></td><td>If you don't want to use Javascript Tree (or your Browser not works correctly with it) you can use the HTML Tree</td></tr>";

$html.="\n</table>";
$html.="<br><center><input type=submit value=\"Apply\"></center>";
$html.="\n</form>";


$page=new Page("Session Configuration");
$page->setHead();
$page->setBody();
$page->setBody($html);
$page->Display();
?>
