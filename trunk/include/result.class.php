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
 */

/**
 * Allows to handle errors and send results in JSON format
 *
 */
class jx_Result {
	function add_message($message, $type='general') {
		$_SESSION['jx_message'][$type][] = $message;
	}
	function empty_messages() {
		$_SESSION['jx_message'] = array();
	}
	function count_messages() {
		
		if( empty($_SESSION['jx_message'])) {
			return 0;
		}
		$count = 0;
		foreach( $_SESSION['jx_message'] as $type ) {
			if( !empty( $type ) && is_array( $type )) {
				$count += sizeof( $type );
			}
		}
		return $count;
	}
	function add_error($error, $type='general') {
		$_SESSION['jx_error'][$type][] = $error;
	}
	function empty_errors() {
		$_SESSION['jx_error'] = array();
	}
	function count_errors() {
		$count = 0;
		foreach( $_SESSION['jx_error'] as $type ) {
			if( !empty( $type ) && is_array( $type )) {
				$count += sizeof( $type );
			}
		}
		return $count;
	}
	function sendResult( $action, $success, $msg,$extra=array() ) {		// show error-message
		
		if( jx_isXHR() ) {
			$success = (bool)$success;
			$result = array('action' => $action,
							'message' => $msg,
							'error' => $msg,//.print_r($_POST,true),
							'success' => $success 
						);
			foreach( $extra as $key => $value ) {
				$result[$key] = $value;
			}
			$json = new Services_JSON();
			$jresult = $json->encode($result);
			print $jresult;
			jx_exit();
		}

		if($extra != NULL) {
			$msg .= " - ".$extra;
		}
		jx_Result::add_error( $msg );
		
		if( empty( $_GET['error'] )) {
			session_write_close();
			mosRedirect( make_link("show_error", $GLOBALS["dir"]).'&error=1&extra='.urlencode( $extra ));
		}
		else {
			show_header($GLOBALS["error_msg"]["error"]);
			
			echo '<div class="quote">';
			echo '<a href="#errors">'.jx_Result::count_errors() .' '.$GLOBALS["error_msg"]["error"].'</a>, ';
			echo '<a href="#messages">'.jx_Result::count_messages() .' '.$GLOBALS["error_msg"]["message"].'</a><br />';
			echo "</div>\n";
			
			if( !empty( $_SESSION['jx_message'])) {
				echo "<a href=\"".str_replace('&dir=', '&ignore=', make_link("list", '' ))."\">[ "
						.$GLOBALS["error_msg"]["back"]." ]</a>";
						
				echo "<div class=\"jx_message\"><a name=\"messages\"></a>
						<h3>".$GLOBALS["error_msg"]["message"].":</strong>"."</h3>\n";
				
				foreach ( $_SESSION['jx_message'] as $msgtype ) {
					foreach ( $msgtype as $message ) {
						echo $message ."\n<br/>";
					}
					echo '<br /><hr /><br />';
				}
				jx_Result::empty_messages();
				
				if( !empty( $_REQUEST['extra'])) echo " - ".urldecode($_REQUEST['extra']);
				echo "</div>\n";
			}
			
			
			echo "<div class=\"jx_error\"><a name=\"errors\"></a>
					<h3>".$GLOBALS["error_msg"]["error"].":</strong>"."</h3>\n";
			
			foreach ( $_SESSION['jx_error'] as $errortype ) {
				foreach ( $errortype as $error ) {
					echo $error ."\n<br/>";
				}
				echo '<br /><hr /><br />';
			}
			jx_Result::empty_errors();
			
			echo "<a href=\"".str_replace('&dir=', '&ignore=', make_link("list", '' ))."\">".$GLOBALS["error_msg"]["back"]."</a>";
			if( !empty( $_REQUEST['extra'])) echo " - ".urldecode($_REQUEST['extra']);
			echo "</div>\n";
			
		}
	}
}
//------------------------------------------------------------------------------
?>
