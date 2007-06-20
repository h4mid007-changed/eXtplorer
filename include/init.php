<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: $
 * @package joomlaXplorer
 * @copyright soeren 2007
 * @author The joomlaXplorer project (http://joomlacode.org/gf/project/joomlaxplorer/)
 * @author The  The QuiX project (http://quixplorer.sourceforge.net)
 * 
 * @license
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * Alternatively, the contents of this file may be used under the terms
 * of the GNU General Public License Version 2 or later (the "GPL"), in
 * which case the provisions of the GPL are applicable instead of
 * those above. If you wish to allow use of your version of this file only
 * under the terms of the GPL and not to allow others to use
 * your version of this file under the MPL, indicate your decision by
 * deleting  the provisions above and replace  them with the notice and
 * other provisions required by the GPL.  If you do not delete
 * the provisions above, a recipient may use your version of this file
 * under either the MPL or the GPL."
 * 
 * This file initializes most of the variables and constants we need in joomlaXplorer
 */
// Vars
if(isset($_SERVER)) {
	$GLOBALS['__GET']	=&$_GET;
	$GLOBALS['__POST']	=&$_POST;
	$GLOBALS['__SERVER']	=&$_SERVER;
	$GLOBALS['__FILES']	=&$_FILES;
} 
elseif(isset($HTTP_SERVER_VARS)) {
	$GLOBALS['__GET']	=&$HTTP_GET_VARS;
	$GLOBALS['__POST']	=&$HTTP_POST_VARS;
	$GLOBALS['__SERVER']	=&$HTTP_SERVER_VARS;
	$GLOBALS['__FILES']	=&$HTTP_POST_FILES;
} 
else {
	die("<strong>ERROR: Your PHP version is too old</strong><br/>".
	"You need at least PHP 4.0.0 to run joomlaXplorer; preferably PHP 4.4.4 or higher.");
}
//------------------------------------------------------------------------------

// Get Item
if(isset($_REQUEST["item"])) 
  $GLOBALS["item"]=$item = stripslashes(urldecode($_REQUEST["item"]));
else 
  $GLOBALS["item"]=$item ="";

if( !empty( $GLOBALS['__POST']["selitems"] )) {
	// Arrayfi the string 'selitems' if necessary
	if( !is_array( $GLOBALS['__POST']["selitems"] ) ) $GLOBALS['__POST']["selitems"] = array( $GLOBALS['__POST']["selitems"] );
	foreach( $GLOBALS['__POST']["selitems"] as $i => $myItem ) {
		$GLOBALS['__POST']["selitems"][$i] = urldecode( $myItem );
	}
}

// Get Sort
$GLOBALS["order"]=mosGetParam( $_REQUEST, 'order', 'name');
// Get Sortorder
$GLOBALS["direction"]=mosGetParam( $_REQUEST, 'direction', 'ASC');
$GLOBALS["start"]=mosGetParam( $_REQUEST, 'start', 0);
$GLOBALS["limit"]=mosGetParam( $_REQUEST, 'limit', 50);


// Get Language
if(isset($GLOBALS['__GET']["lang"])) 
  $GLOBALS["lang"]=$GLOBALS["language"]=$GLOBALS['__GET']["lang"];
elseif(isset($GLOBALS['__POST']["lang"])) 
  $GLOBALS["lang"]=$GLOBALS["language"]=$GLOBALS['__POST']["lang"];
//------------------------------------------------------------------------------

/** @var $GLOBALS['file_mode'] Can be 'file' or 'ftp' */
if( !isset( $_REQUEST['file_mode'] ) && !empty($_SESSION['file_mode'] )) {
	$GLOBALS['file_mode'] = mosGetParam( $_SESSION, 'file_mode', 'file' );
}
else {
	if( @$_REQUEST['file_mode'] == 'ftp' && @$_SESSION['file_mode'] == 'file') {
		if( empty( $_SESSION['ftp_login']) && empty( $_SESSION['ftp_pass'])) {
			mosRedirect( 'index2.php?option=com_joomlaxplorer&action=ftp_authentication' );
		}
		else {
			$GLOBALS['file_mode'] = $_SESSION['file_mode'] = mosGetParam( $_REQUEST, 'file_mode', 'file' );
		}
	}
	elseif( isset( $_REQUEST['file_mode'] )) {
		$GLOBALS['file_mode'] = $_SESSION['file_mode'] = mosGetParam( $_REQUEST, 'file_mode', 'file' );
	}
	else {
		$GLOBALS['file_mode'] = mosGetParam( $_SESSION, 'file_mode', 'file' );
	}
}

// Necessary files

require _JX_PATH."/config/conf.php";
if( file_exists(_JX_PATH."/languages/".$GLOBALS["language"].".php")) {
	require _JX_PATH."/languages/".$GLOBALS["language"].".php";
}
else {
	require _JX_PATH."/languages/english.php";
}
if( file_exists(_JX_PATH."/languages/".$GLOBALS["language"]."_mimes.php")) {
	require _JX_PATH."/languages/".$GLOBALS["language"]."_mimes.php";
}
else {
	require _JX_PATH."/languages/english_mimes.php";
}

require _JX_PATH . '/application.php';
require _JX_PATH."/config/mimes.php";

require _JX_PATH . '/libraries/JSON.php';
require _JX_PATH."/libraries/File_Operations.php";
require _JX_PATH."/include/functions.php";
require _JX_PATH."/include/header.php";
require _JX_PATH."/include/footer.php";
require _JX_PATH."/include/result.class.php";


//------------------------------------------------------------------------------

// Raise Memory Limit
jx_RaiseMemoryLimit( '8M' );

$GLOBALS['jx_File'] = new jx_File();

if( jx_isFTPMode() ) {
	// Try to connect to the FTP server.    	HOST,   PORT, TIMEOUT
	$ftp_host = mosGetParam( $_POST, 'ftp_host', 'localhost:21' );
	$url = @parse_url( 'ftp://' . $ftp_host);
	$port = empty($url['port']) ? 21 : $url['port'];
	$ftp = new Net_FTP( $url['host'], $port, 20 );
	/** @global Net_FTP $GLOBALS['FTPCONNECTION'] */
	$GLOBALS['FTPCONNECTION'] = new Net_FTP( $url['host'], $port, 20 );
	$res = $GLOBALS['FTPCONNECTION']->connect();
	if( PEAR::isError( $res )) {
		echo $res->getMessage();
		$GLOBALS['file_mode'] = $_SESSION['file_mode'] = 'file';
	}
	else {
		if( empty( $_SESSION['ftp_login']) && empty( $_SESSION['ftp_pass'])) {
			mosRedirect( 'index2.php?option=com_joomlaxplorer&action=ftp_authentication&file_mode=file' );
		}
		$login_res = $GLOBALS['FTPCONNECTION']->login( $_SESSION['ftp_login'], $_SESSION['ftp_pass'] );
		if( PEAR::isError( $res )) {
			echo $login_res->getMessage();
			$GLOBALS['file_mode'] = $_SESSION['file_mode'] = 'file';
		}	
	}
	
}
//------------------------------------------------------------------------------
if($GLOBALS["require_login"]) {	// LOGIN

	require _JX_PATH."/include/login.php";
	
	if($GLOBALS["action"]=="logout") {
		logout();
	} else {
		login();
	}
}
//------------------------------------------------------------------------------
if( !isset( $_REQUEST['dir'] ) ) {

	$GLOBALS["dir"] = $dir = mosGetParam( $_SESSION,'jx_'.$GLOBALS['file_mode'].'dir', '' );
	if( !empty( $dir )) {
		$dir = @$dir[0] == '/' ? substr( $dir, 1 ) : $dir;
	}
	$try_this = jx_isFTPMode() ? '/'.$dir : $GLOBALS['home_dir'].'/'.$dir;
	if( !empty( $dir ) && !$GLOBALS['jx_File']->file_exists( $try_this )) {
		$dir = '';
	}
}
else {
	$GLOBALS["dir"] = $dir = urldecode(stripslashes(mosGetParam( $_REQUEST, "dir" )));
}
if( $dir == 'jx_root') {
	$GLOBALS["dir"] = $dir = '';
}
if( jx_isFTPMode() && $dir != '' ) {
	$GLOBALS['FTPCONNECTION']->cd( $dir );
}

$abs_dir=get_abs_dir($GLOBALS["dir"]);
if(!file_exists($GLOBALS["home_dir"])) {
  if(!file_exists($GLOBALS["home_dir"].$GLOBALS["separator"])) {
	if($GLOBALS["require_login"]) {
		$extra="<a href=\"".make_link("logout",NULL,NULL)."\">".
			$GLOBALS["messages"]["btnlogout"]."</A>";
	} 
	else $extra=NULL;
	jx_Result::sendResult('', false, $GLOBALS["error_msg"]["home"]." (".$GLOBALS["home_dir"].")",$extra);
  }
}
if(!down_home($abs_dir)) {
	jx_Result::sendResult('', false, $GLOBALS["dir"]." : ".$GLOBALS["error_msg"]["abovehome"]);
}
if(!get_is_dir($abs_dir))
  if(!get_is_dir($abs_dir.$GLOBALS["separator"]))
	jx_Result::sendResult('', false, $abs_dir." : ".$GLOBALS["error_msg"]["direxist"]);
	
$_SESSION['jx_'.$GLOBALS['file_mode'].'dir'] = $dir;
//------------------------------------------------------------------------------
?>
