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
class Page {

    var $current="";
    var $headClosed;// state of the head

    /**
     * @desc constructor
     * @param page title
     */
    function Page($title=""){
	$this->current .="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\" \"http://www.w3.org/TR/REC-html40/loose.dtd\">\n";
	$this->current .="<HTML>\n<HEAD>\n<TITLE>$title</TITLE>\n";
	$this->headClosed=FALSE;
	
    }

    /**
     * @desc add more header information
     * @param head here add more tags
     */
    function setHead($head=FALSE){
	if($this->headClosed){
	    trace(2, __LINE__, __FILE__, "Head already closed");
	    return FALSE;
	}
	if(!$head){
	    $this->current .="<style type=\"text/css\">
//<!--
body {  font-family: Arial, Helvetica, sans-serif; font-size: 8pt}
th   {  font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold; background-color: #D3DCE3;}
td   {  font-family: Arial, Helvetica, sans-serif; font-size: 8pt;}
form   {  font-family: Arial, Helvetica, sans-serif; font-size: 8pt}
h1   {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14pt; font-weight: bold}
A:link    {  font-family: Arial, Helvetica, sans-serif; font-size: 8pt; text-decoration: none; color: blue}
A:visited {  font-family: Arial, Helvetica, sans-serif; font-size: 8pt; text-decoration: none; color: blue}
A:hover   {  font-family: Arial, Helvetica, sans-serif; font-size: 8pt; text-decoration: underline; color: red}
A:link.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}
A:visited.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}
A:hover.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: red;}
.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}
//-->
</style>\n";
	} else {
	    $this->current .=$head."\n";
	}
    }
    
    /**
     * @desc closes the head and writes the body
     * @param body, if nessesary
     */
    function setBody($body=FALSE){
	if(!$this->headClosed){
	    $this->current .="</HEAD>\n";
	    $this->headClosed=TRUE;
	}
	if(!$body){
	    $this->current .="<BODY BGCOLOR=\"#F5F5F5\" TEXT=\"#000000\" BACKGROUND=\"images/bkg.gif\">\n";
	} else {
	    $this->current .=$body;
	}
    }

    /**
     * @desc adds the sql statement to view
     */
    function setSQL(){
	if($GLOBALS["CF"]->get("ENABLE_VIEW_STATEMENTS") && strlen($GLOBALS["SQL_QUERY_TO_SHOW"])>0){
	    $this->setBody("\n<br>Executed SQL:<br><table><tr bgcolor=\"#CCCCCC\" valign=top><td>".
			   nl2br($GLOBALS["SQL_QUERY_TO_SHOW"])."</td></tr></table>");
	}
    }
     
    /**
     * @desc closes the body, and display the whole thing
     */
    function Display($optimize=TRUE){
	$data=$this->current."</BODY>\n"."</HTML>";
	if($GLOBALS["CF"]->get("ENABLE_HTML_OPTIMIZATION") && $optimize){
	    $tmp=preg_split("=<script[^>]*>(.*)</script>=simU", $data);
	    preg_match_all("=<script[^>]*>(.*)</script>=simU", $data, $scriptpart);
	    for($x=0;$x<count($tmp);$x++){
		echo trim(eregi_replace("[[:space:]]*\n[[:space:]]*", "", $tmp[$x]));
		echo isset($scriptpart[$x][0]) ? $scriptpart[$x][0] : "";
	    }
	} else {
	    echo $data;
	}
    }

}// end class
