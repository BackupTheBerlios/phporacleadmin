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
/**
 * @desc class to generate menubar for objects
 *
 */
class MenuBar {

    var $htmldata;

    var $menudata;
    var $selected=FALSE;
    var $defaultmenu;

    /**
     * @desc constructor
     */
    function MenuBar($defaultmenu=FALSE){
	$this->defaultmenu=$defaultmenu;
	$this->menudata=array();
    }

    /**
     * @desc register a new menu
     */
    function registerMenu($ident, $name, $link){
	$this->menudata[$ident]=array("NAME"=>$name, "LINK"=>$link);
    }

    function selectMenu($ident){
	$this->selected=$ident;
    }

    function renderHTML(){
	if(!$this->selected || !in_array($this->selected, array_keys($this->menudata))){
	    if(!in_array($this->defaultmenu, array_keys($this->menudata))){
		reset($this->menudata);
		$this->selected=key($this->menudata);
	    } else {
		$this->selected=$this->defaultmenu;
	    }
	    $GLOBALS["mode"]=$this->selected;
	}
	$html="<TABLE BORDER=1>\n<TR>\n";
	foreach($this->menudata as $ident => $button){
	    if($ident==$this->selected){
		$html.="<TD BGCOLOR=\"#D3DCE3\" ALIGN=CENTER WIDTH=60 HEIGHT=30>";
	    } else {
		$html.="<TD BGCOLOR=\"#CCCCCC\" ALIGN=CENTER WIDTH=60 HEIGHT=30>";
	    }
	    $html.="<A HREF=\"".$button["LINK"]."\">".$button["NAME"]."</A></TD>";
	}
	$html.="</TR></TABLE>";
	$this->htmldata=$html;
    }

    function getHTML(){
	return $this->htmldata;
    }
}// end class

/**
 * @desc class to generate icon menubar for objects
 *
 */
class IconBar {
    var $htmldata;

    var $menudata;
    var $selected=FALSE;

    /**
     * @desc constructor
     */
    function IconBar(){
	$this->menudata=array();
    }

    /**
     * @desc register a new menu
     */
    function registerMenu($ident, $name, $link, $icon){
	$this->menudata[$ident]=array("NAME"=>$name, "LINK"=>$link, "ICON"=>$icon);
    }

    function selectMenu($ident){
	$this->selected=$ident;
    }

    function renderHTML(){
	$html="<TABLE BORDER=0>\n<TR>\n";
	foreach($this->menudata as $ident => $button){
	    $html.="<TD ALIGN=CENTER><A HREF=\"".$button["LINK"]."\"><IMG SRC=\"".$button["ICON"]."\" ";
	    if($ident==$this->selected){
		$html.=" BORDER=1 ";
	    } else {
		$html.=" BORDER=0 ";
	    }
	    $html.="WIDTH=32 HEIGHT=32 ALT=\"".$button["NAME"]."\"><br>".$button["NAME"]."</A>\n</TD>\n";
	}
	$html.="</TR></TABLE>";
	$this->htmldata=$html;
    }

    function getHTML(){
	return $this->htmldata;
    }
}// end class
