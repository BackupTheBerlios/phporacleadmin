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

/** Errorlevel, __LINE__, __FILE__, debugtext */
function trace($level, $l, $f, $text) {
    $errortime=date("l, d.m.y G:i:s", time());
	
    if($GLOBALS["CF"]->get("DEBUG_ECHO_LEVEL")>=$level){
	//return TRUE;
    
	if($level==1) {
	    echo sprintf("\n<P>[%s,%s,%s, %s] <FONT SIZE=-1 COLOR=#FF0000>%s</FONT>", $level, $l, $f,$errortime, $text);
	    flush();
	    die();
	} elseif($level==2) {
	    echo sprintf("\n<P>[%s,%s,%s, %s] <FONT SIZE=-1 COLOR=#A00000>%s</FONT><P>", $level, $l, $f,$errortime, $text);
	    flush();
	} else {
	    echo sprintf("\n<br>[%s,%s,%s,%s] <FONT SIZE=-1 COLOR=#007000>%s</FONT>", $level, $l, $f,$errortime, $text);
	    flush();
	}
    }
}// end trace

?>
