<?php
// class.tree.php3 v1.0
// (c) 1999, 2000 Patrick Hess <hess@dland.de>
// rewritten by Thomas Fromm
class Tree {

    // iconpaths
    var $iconfolderopen;
    var $iconfolderclose;
    var $icondocument;

    var $tree_basefrm = "_top";
    var $tree_gbase;
    
    // internal data
    var $tree_path;
    var $tree_count = 1;
    var $tree_string="";


    function Tree($path="."){
	$this->tree_path = $path;
    }


    function setForeignFrame($t_frame){
	$this->tree_basefrm = $t_frame;
    }

    function setBaseFrame($frame){
	$this->tree_basefrm = $frame;
    }

    function setIconPath($path){
	$this->tree_gbase=$path;
    }

    function openTree($titel, $url=""){
	$tree_ftv2blank = "$this->tree_gbase/ftv2blank.gif";
	//$tree_ftv2doc = "$this->tree_gbase/ftv2doc.gif";
	$tree_ftv2doc = "$this->tree_gbase/objecticon.gif";
	//$tree_ftv2folderclosed = "$this->tree_gbase/ftv2folderclosed.gif";
	$tree_ftv2folderclosed = "$this->tree_gbase/folderclosed.gif";
	//$tree_ftv2folderopen = "$this->tree_gbase/ftv2folderopen.gif";
	$tree_ftv2folderopen = "$this->tree_gbase/folderopen.gif";
	$tree_ftv2lastnode = "$this->tree_gbase/ftv2lastnode.gif";
	$tree_ftv2link = "$this->tree_gbase/ftv2link.gif";
	$tree_ftv2mlastnode = "$this->tree_gbase/ftv2mlastnode.gif";
	$tree_ftv2mnode = "$this->tree_gbase/ftv2mnode.gif";
	$tree_ftv2node = "$this->tree_gbase/ftv2node.gif";
	$tree_ftv2plastnode = "$this->tree_gbase/ftv2plastnode.gif";
	$tree_ftv2pnode = "$this->tree_gbase/ftv2pnode.gif";
	$tree_ftv2vertline = "$this->tree_gbase/ftv2vertline.gif";

	$this->tree_string.= "<script>\n".
	    "classPath = \"".$this->tree_path."\";\n".
	    "ftv2blank = \"".$tree_ftv2blank."\";\n".
	    "ftv2doc = \"".$tree_ftv2doc."\";\n".
	    "ftv2folderclosed = \"".$tree_ftv2folderclosed."\";\n".
	    "ftv2folderopen = \"".$tree_ftv2folderopen."\";\n".
	    "ftv2lastnode = \"".$tree_ftv2lastnode."\";\n".
	    "ftv2link = \"".$tree_ftv2link."\";\n".
	    "ftv2mlastnode = \"".$tree_ftv2mlastnode."\";\n".
	    "ftv2mnode = \"".$tree_ftv2mnode."\";\n".
	    "ftv2node = \"".$tree_ftv2node."\";\n".
	    "ftv2plastnode = \"".$tree_ftv2plastnode."\";\n".
	    "ftv2pnode = \"".$tree_ftv2pnode."\";\n".
	    "ftv2vertline = \"".$tree_ftv2vertline."\";\n".
	    "basefrm = \"".$this->tree_basefrm."\";\n".
	    "</script><script src=\"".$this->tree_path."/ftiens4.js\"".
	    "type=\"text/javascript\"></script><script>\n";
 
	$jsvn = "foldersTree";
	$this->tree_string.="$jsvn = gFld(\"$titel\", \"$url\")\n";
	return ($jsvn);
    }// end function open tree

    function addFolder($root, $titel, $url=""){
	$jsvn = "aux".$this->tree_count;
	$this->tree_count++;
	$this->tree_string.= "$jsvn = insFld($root, gFld (\"$titel\", ";
	$this->tree_string.= "\"$url\"))\n";
	return ($jsvn);
    }

    function addDocument($root, $titel, $url=""){
	$this->tree_string.= "insDoc($root, gLnk ($root, \"$titel\", ";
	$this->tree_string.= "\"$url\"))\n";
    }

    function closeTree(){
	$this->tree_string.= "\ninitializeDocument()\n</script>";
    }

    function getTree(){
	return $this->tree_string;
    }

}// end class

