<?php
function logon(){
    Header ("WWW-Authenticate: Basic Realm=\"phpOracleAdmin\"");
    Header ("HTTP/1.0 401 Unauthorized");
    echo ("Access Denied!");
    exit;
} // logon
 
function authorized (){
    // initialize vars
    $user=$GLOBALS["CF"]->get("HTTP_AUTH_LOGIN");
    $pwds=$GLOBALS["CF"]->get("HTTP_AUTH_PASSWORD");
    if(!in_array($GLOBALS["PHP_AUTH_USER"], $user)){
	return FALSE;
    }
    foreach($user as $id => $login){
	if($login==$GLOBALS["PHP_AUTH_USER"]){
	    if($GLOBALS["PHP_AUTH_PW"]==$pwds[$id]){
		return TRUE;
	    } else {
		return FALSE;
	    }
	}
    }
    return FALSE;
} // authorized
 
Header("Expires: Mon, 26 Jul 1999 05:00:00 GMT");
Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
Header("Cache-Control: no-cache, must-revalidate");
Header("Pragma: no-cache");
if (!isset($GLOBALS["PHP_AUTH_USER"])){
    logon ();
} // if
if (!authorized()){
    logon ();
} // if
    
