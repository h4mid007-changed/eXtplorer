<?php
// eXtplorer Flash Upload Handler for Joomla!
if( @$_POST['option'] == 'com_extplorer' && !empty($_POST[session_name()]) && !defined('_JEXEC') ) {
	$sess_id = substr( $_POST[session_name()], 0 , 32 );
	$_COOKIE[session_name()] = $sess_id;
	session_id( $sess_id );
	/**DEBUG
	$res = "\r\nTime: ".time()."\r\nSession Name: ".session_name().', Session ID: '.session_id();
	$res .= "\r\nCOOKIE: ".print_r( $_COOKIE, true )."\r\nPOST: ".print_r( $_POST, true );
	file_put_contents( 'debug.txt', $res, FILE_APPEND );
	**/
	
	if( file_exists('/../../configuration.php') ){
		require( '/../../index.php' ); 
	} else {
		require( dirname(__FILE__).'/index.php' ); 
	}
	
}
