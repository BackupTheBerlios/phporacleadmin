<?php
/***************************************************************************
    begin                : Jan 2001
    copyright            : (C) by Thomas Fromm
    email                : tf@tfromm.com
 ***************************************************************************/

/***************************************************************************
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the Lesser GNU General Public License as        *
 *   published by the Free Software Foundation; either version 2.1 of the  *
 *   License, or (at your option) any later version.                       *
 *   http://www.gnu.org/copyleft/lesser.html                               *
 ***************************************************************************/

define('HTMLTREE_GET_VAR', 'sel'); // define for the name of the getvar

@set_time_limit(150);
/**
 * class to generate HTML Tree
 *
 */
class HTMLTree {

    /**
     * iconpath without ending /
     */
    var $iconpath;
    /**
     * iconnames
     * could be:
     * OPEN (open folders)
     * CLOSED
     * DOC (default document icon)
     * BLANK (space picture)
     * NODE (line column cross)
     * LASTNODE (last document in column)
     * OPENNODE (open folder node)
     * CLOSEDNODE (closed folder node)
     * CLOSEDLASTNODE (closed folder last node in column)
     */
    var $icons;
    /**
     * table width
     */
    var $tablewidth;
    /**
     * basetarget of the treelinks
     */
    var $basetarget;
    /**
     * baseframe of the treelinks
     */
    var $baseframe;
    /**
     * basetarget of the foreign links
     */
    var $foreigntarget;
    /**
     * baseframe of the foreign links
     */
    var $foreignframe;
    /**
     * defaultvalue of einrueckung
     */
    var $defaultspace;
    /**
     * array of all entries
     */
    var $entries;
    /**
     * array of all opened folders
     */
    var $opened;
    /**
     * komplete html tree
     */
    var $htmltree;

    /**
     * internal counter of last element in treepart (for icons)
     * $last_element[]=array( $rootid => array($lastrowid, $entrycount) , $rootid => array()...
     */
    var $last_element;

    /**
     * constructor
     * @param array array of all opened folders (shipped as get params HTMLTREE_GET_VAR)
     */
    function HTMLTree($opened){
	$this->opened=$opened;
	$this->htmltree="";
	$this->baseframe="_self";
	$this->basetarget=$GLOBALS["PHP_SELF"];
	$this->foreignframe="_self";
	$this->foreigntarget=$GLOBALS["PHP_SELF"];
	$this->tablewidth=350;
	$this->defaultspace=18;
	$this->icons=array();
	$this->icons["OPEN"]="folderopen.gif";
	$this->icons["CLOSED"]="folderclosed.gif";
	$this->icons["DOC"]="ftv2doc.gif";
	$this->icons["BLANK"]="ftv2blank.gif";
	$this->icons["NODE"]="ftv2node.gif";
	$this->icons["LASTNODE"]="ftv2lastnode.gif";
	$this->icons["OPENLASTNODE"]="ftv2mlastnode.gif";
	$this->icons["CLOSEDNODE"]="ftv2pnode.gif";
	$this->icons["CLOSEDLASTNODE"]="ftv2plastnode.gif";
    }

    /**
     * sets icon path with ending /
     * @param string path
     */
    function setIconPath($path="."){
	$this->iconpath=$path."/";
    }

    /**
     * set iconnames
     * iconnames could be:
     * OPEN (open folders)
     * CLOSED
     * DOC (default document icon)
     * BLANK (space picture)
     * NODE (line column cross)
     * LASTNODE (last document in column)
     * OPENNODE (open folder node)
     * CLOSEDNODE (closed folder node)
     * CLOSEDLASTNODE (closed folder last node in column)
     * @param string iconname
     * @param string filename
     */
    function setIcon($iconname, $filename){
	$this->icons[$iconname]=$filename;
    }

    /**
     * sets base frame
     * @param string framename
     */
    function setBaseFrame($framename){
	$this->baseframe=$framename;
    }

    /**
     * sets base file
     * @param string filename
     */
    function setBaseTarget($filename){
	$this->basetarget=$filename;
    }

    /**
     * sets foreign default frame
     * @param string framename
     */
    function setForeignFrame($framename){
	$this->foreignframe=$framename;
    }

    /**
     * sets foreign default target
     * @param string filename
     */
    function setForeignTarget($filename){
	$this->foreigntarget=$filename;
    }

    /**
     * opens the tree
     * @param string title title for this tree
     * @param string 
     * @param string
     */
    function openTree($title, $target=FALSE, $frame=FALSE){
	$target=$target ? $target : $this->foreigntarget;
	$frame=$frame ? $frame : $this->foreignframe;
	$this->entries=array();
	$this->entries[1]=array (0, $title, $target, $frame, 0);
	$this->last_element[0]=1;// for icon
	return 1;
    }

    function addFolder($root, $title, $target=FALSE, $frame=FALSE){
	$frame=$frame ? $frame : $this->foreignframe;
	$retval=array_keys($this->entries);
	asort($retval);
	$retval=array_pop($retval);
	$retval++;
	$this->entries[$retval]=array($root, $title, $target, $frame, 0);
	// for icon choosing and linking
	if(!isset($this->last_element[$root])){
	    $this->last_element[$root]=array($retval, 0);
	} else {
	    $count=$this->last_element[$root][1]+1;
	    $this->last_element[$root]=array($retval, $count);
	}
	return $retval;
    }

    function addDocument($root, $title, $target=FALSE, $frame=FALSE, $icon=FALSE){
	/*
	// add only nessesary datas (not invisible entries)
	if(!in_array($root, $this->opened)){
	    if(!isset($this->last_element[$root])){
		$this->last_element[$root]=array(1, 1);
	    }
	    return TRUE;
	}
	*/
	$frame=$frame ? $frame : $this->foreignframe;
	$icon=$icon ? $icon : $this->icons["DOC"];
	$retval=array_keys($this->entries);
	asort($retval);
	$retval=array_pop($retval);
	$retval++;
	$this->entries[$retval]=array($root, $title, $target, $frame, $icon);
	// for icon choosing and linking
	if(!isset($this->last_element[$root])){
	    $this->last_element[$root]=array($retval, 0);
	} else {
	    $count=$this->last_element[$root][1]++;
	    $this->last_element[$root]=array($retval, $count);
	}
    }

    function closeTree(){
	$this->generateTree(0,$this->entries,0);
    }

    function getTree(){
	return $this->htmltree;
    }
    
    function generateTree($closed, $entry, $sp_count){
	$sp_width=$sp_count*$this->defaultspace+1;
	$sp_count++;
	$iconarray=array($this->icons["CLOSED"],
			 $this->icons["OPEN"],
			 $this->icons["DOC"]);
	foreach($entry as $rowid => $rowdata){
	    $link="";
	    if ($rowdata[0] == $closed){
		// only linked if subelements exists
		if(isset($this->last_element[$rowid][1])){
		    if ($rowdata[4]<2){
			$opened="?".SID;
			foreach($this->opened as $element){
			    if ($element!=$rowid){
				$opened.="&amp;".HTMLTREE_GET_VAR."[]=".$element;
			    }
			}
			$link=$this->basetarget.$opened;
			$link.=(!in_array($rowid, $this->opened)) ? "&amp;".HTMLTREE_GET_VAR."[]=".$rowid : "";
		    } elseif($rowdata[4]>1){
			$link=$rowdata[2];
		    }
		}

		$this->htmltree.='<table cellpadding=0 cellspacing=0 border=0 width="'.$this->tablewidth.
		    '"><tr><td><img src="'.$this->iconpath.$this->icons["BLANK"].'" width="'.($sp_width).'" height=5></td>';
	
		if(in_array($rowid, $this->opened)){
		    $rowdata[4]++;
		}
		// icons
		$icon="<nobr>";
		if(is_int($rowdata[4])){
		    // already open-symbol, when no subelements
		    if ($rowdata[4]==1 OR !isset($this->last_element[$rowid][1])){
			$icon.="<img src=\"".$this->iconpath.$this->icons["OPENLASTNODE"].
			    "\" align=\"left\" border=0 vspace=1>";
		    } elseif($rowdata[4]==0){
			if($rowid==$this->last_element[$rowdata[0]][0]){
			    $icon.="<img src=\"".$this->iconpath.$this->icons["CLOSEDLASTNODE"].
				"\" align=\"left\" border=0 vspace=1>";
			} else {
			    $icon.="<img src=\"".$this->iconpath.$this->icons["CLOSEDNODE"].
				"\" align=\"left\" border=0 vspace=1>";
			}
		    } 
		    $icon.="<img src=\"".$this->iconpath.$iconarray[$rowdata[4]]."\" align=\"left\" border=0 vspace=1>";
		}else {
		    if($rowid==$this->last_element[$rowdata[0]][0]){
			$icon.="<img src=\"".$this->iconpath.$this->icons["LASTNODE"].
			    "\" align=\"left\" border=0 vspace=1>";
		    } else {
			$icon.="<img src=\"".$this->iconpath.$this->icons["NODE"]."\" align=\"left\" border=0 vspace=1>";
		    }
		    $icon.="<img src=\"".$this->iconpath.$rowdata[4]." \" align=\"left\" border=0 vspace=1>";
		}
		$icon.="</nobr>";
		$this->htmltree.='<td width="'.($this->tablewidth - $sp_width).'">';
		if($link){
		    $this->htmltree.='<a href="'.$link.'" target="'.$this->baseframe.'">'.$icon.'</a>';
		} else {
		    $this->htmltree.= $icon;
		}
		if($rowdata[2]){
		    $this->htmltree.='<a href="'.$rowdata[2].'" target="'.$rowdata[3].'">'.$rowdata[1].'</a>';
		} else {
		    $this->htmltree.=$rowdata[1];
		}
		$this->htmltree.="</td></tr></table>\n";
		if(in_array($rowid, $this->opened)){
		    $this->generateTree($rowid, $entry, $sp_count);
		}
	    }
	}
    }// end function
} // end class
