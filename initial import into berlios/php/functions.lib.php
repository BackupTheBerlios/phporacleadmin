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
 * @desc validates and returns data from post/get
 * @param varname
 * @param datatype (default string)
 */
function getData($varname, $type="string"){  
    if(isset($GLOBALS["HTTP_GET_VARS"][$varname])){
	$retval=rawurldecode($GLOBALS["HTTP_GET_VARS"][$varname]);
    } elseif(isset($GLOBALS["HTTP_POST_VARS"][$varname])) {
	$retval=stripslashes($GLOBALS["HTTP_POST_VARS"][$varname]);
    }elseif(isset($GLOBALS["HTTP_SERVER_VARS"][$varname])) {
	$retval=stripslashes($GLOBALS["HTTP_SERVER_VARS"][$varname]);
    } else {
	trace(3, __LINE__, __FILE__, "Variable missed ".$varname);
	return FALSE;
    }
    settype($retval, $type);
    return $retval;
}

