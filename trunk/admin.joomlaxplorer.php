<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * MAIN FILE! (formerly known as index.php)
 * 
 * @version $Id: $
 * 
 * @package joomlaXplorer
 * @copyright soeren 2007
 * @author The joomlaXplorer project (http://joomlacode.org/gf/project/joomlaxplorer/)
 * @author The  The QuiX project (http://quixplorer.sourceforge.net)
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
 *
 * This is a component with full access to the filesystem of your joomla Site
 * I wouldn't recommend to let in Managers
 * allowed: Superadministrator
**/
if (!$acl->acl_check( 'administration', 'config', 'users', $my->usertype )) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}
// The joomlaXplorer version number
$GLOBALS['jx_version'] = '2.0.0';
$GLOBALS['jx_home'] = 'http://joomlacode.org/gf/project/joomlaxplorer/';

/*
// Needed to keep the filelist in the XML installer file up-to-date
$path = $mosConfig_absolute_path.'/administrator/components/com_joomlaxplorer';
$filelist = mosReadDirectory( $path, '.', true, true );
$contents ='';
foreach($filelist as $file ) {
	if( is_dir( $file ) ) continue;
	$filepath = str_replace( $path.'/', '', $file );
	$contents .= '<filename>'.$filepath."</filename>\n";
}
file_put_contents( 'joomlaxplorer_filelist.txt', $contents );
*/

define ( "_JX_PATH", $mosConfig_absolute_path."/administrator/components/com_joomlaxplorer" );
define ( "_QUIXPLORER_FTPTMP_PATH", $mosConfig_absolute_path."/administrator/components/com_joomlaxplorer/ftp_tmp" );
define ( "_JX_URL", $mosConfig_live_site."/administrator/components/com_joomlaxplorer" );

//------------------------------------------------------------------------------
if( defined( 'E_STRICT' )) { // Suppress Strict Standards Warnings
	$errorlevel=error_reporting();
	error_reporting($errorlevel & ~E_STRICT);
}
//------------------------------------------------------------------------------
umask(0002); // Added to make created files/dirs group writable
//------------------------------------------------------------------------------
require_once _JX_PATH . "/include/init.php";	// Init
//------------------------------------------------------------------------------

$action = stripslashes(mosGetParam( $_REQUEST, "action" ));
if( $action == "post" )
  $action = mosGetParam( $_REQUEST, "do_action" );
elseif( empty( $action ))
  $action = "list";

if( $action != "arch" && $action != "download" ) {
	$mainframe->addCustomHeadTag( '<script type="text/javascript" src="components/com_joomlaxplorer/style/opacity.js"></script>' );
	if( $action == "archive") {
		$mainframe->addCustomHeadTag( '<script type="text/javascript" src="components/com_joomlaxplorer/scripts/mootools.ajax.js"></script>' );
	}	
}

// Empty the output buffer if this is a XMLHttpRequest
//while( @ob_end_clean() );echo 'HTTP_X_REQUESTED_WITH: '.$_SERVER['HTTP_X_REQUESTED_WITH'];jx_exit();
if( jx_isXHR() ) {
	error_reporting(0);
	while( @ob_end_clean() );
}

if( file_exists( _JX_PATH . '/include/'. strtolower(basename( $action )) .'.php') ) {
	require_once( _JX_PATH . '/include/'. strtolower(basename( $action )) .'.php');
}
$classname = 'jx_'.$action;
if( class_exists(strtolower($classname))) {
	$handler = new $classname();
	$handler->execAction( $dir, $item );
} else {

	switch($action) {		// Execute actions, which are not in class file
	
	//------------------------------------------------------------------------------
	  // COPY/MOVE FILE(S)/DIR(S)
	  case "copy":	case "move":
		  require_once _JX_PATH ."/include/copy_move.php";
		  copy_move_items($dir);
	  break;
	
	//------------------------------------------------------------------------------
	  // SEARCH FOR FILE(S)/DIR(S)
	  case "search":
		  require_once _JX_PATH ."/include/search.php";
		  search_items($dir);
	  break;
	
	//------------------------------------------------------------------------------
	  // USER-ADMINISTRATION
	  case "admin":
		  require_once _JX_PATH . "/include/admin.php";
		  show_admin($dir);
	  break;
	//------------------------------------------------------------------------------
	  // joomla System Info
	  case 'sysinfo':
		  require_once _JX_PATH . "/include/system_info.php";
	  break;
	//------------------------------------------------------------------------------
	  case 'ftp_logout':
	  	require_once( _JX_PATH.'/include/ftp_authentication.php' );
	  	ftp_logout();
	  	break;
	//------------------------------------------------------------------------------
		// BOOKMARKS
	  case 'modify_bookmark':
	  	$task = mosGetParam( $_REQUEST, 'task' );
	  	require_once( _JX_PATH.'/include/bookmarks.php' );
	  	modify_bookmark( $task, $dir );
	  	
	  	break;
	  case 'include_javascript':
	  	while (@ob_end_clean());
	  	header("Content-type: application/x-javascript; charset=iso-8859-1");
	  	include( _JX_PATH.'/scripts/'.basename(mosGetParam($_REQUEST, 'file' )).'.php');
	  	jx_exit();
	//------------------------------------------------------------------------------
	  case 'show_error':
	  	jx_Result::sendResult('', false, '');
	  	break;
	  case'get_about':
	  	show_about();
	  	break;
	//------------------------------------------------------------------------------
	  // DEFAULT: LIST FILES & DIRS
	  case "getdircontents":
	  		require_once _JX_PATH . "/include/list.php";
	  		$requestedDir = stripslashes(str_replace( '_RRR_', $GLOBALS['separator'], mosGetParam( $_REQUEST, 'node' )));
	  		if( empty($requestedDir) || $requestedDir == 'jx_root') {
	  			$requestedDir = $dir;
	  		}
	  		send_dircontents( $requestedDir, mosGetParam($_REQUEST,'sendWhat','files') );
	  		break;
	  case 'get_dir_selects':
	  		echo get_dir_selects( $dir );
	  		break;
	  case 'chdir_event':
	  		require_once( _JX_PATH.'/include/bookmarks.php' );
	  		$response = Array( 'dirselects' => get_dir_selects( $dir ),
	  							'bookmarks' => list_bookmarks($dir)
	  						);
			$json = new Services_JSON();
			echo $json->encode( $response );
			break;
	  default:
		  require_once _JX_PATH . "/include/list.php";
		  jx_List::execAction($dir);
	//------------------------------------------------------------------------------
	}
// end switch-statement
}
//------------------------------------------------------------------------------
// Disconnect from ftp server
if( jx_isFTPMode() ) {
	$GLOBALS['FTPCONNECTION']->disconnect();
}

// Empty the output buffer if this is a XMLHttpRequest
if( jx_isXHR() ) {
	jx_exit();
}


//------------------------------------------------------------------------------
?>
