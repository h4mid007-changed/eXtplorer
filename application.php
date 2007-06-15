<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @package joomlaXplorer
 * @copyright soeren 2007
 * @author The joomlaXplorer project (http://joomlacode.org/gf/project/joomlaxplorer/)
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
 * Abstract Action Class
 * @abstract 
 */
class jx_Action {
	
	/**
	 * This function executes the action
	 *
	 * @param string $dir
	 * @param string $item
	 */
	function execAction( $dir, $item ) {
		// to be overridden by the child class
	}
	
}
/**
 * Wrapper Class for the Global Language Array
 * @since 2.0.0
 * @author soeren
 *
 */
class jx_Lang {
	/**
	 * Returns a string from $GLOBALS['messages']
	 *
	 * @param string $msg
	 * @param boolean $make_javascript_safe
	 * @return string
	 */
	function msg( $msg, $make_javascript_safe=false ) {
		$str = jx_Lang::_get('messages', $msg );
		if( $make_javascript_safe ) {
			return jx_Lang::escape_for_javascript( $str );
		} else {
			return $str;
		}
	}
	/**
	 * Returns a string from $GLOBALS['error_msg']
	 *
	 * @param string $err
	 * @param boolean $make_javascript_safe
	 * @return string
	 */
	function err( $err, $make_javascript_safe=false ) {
		$str = jx_Lang::_get('error_msg', $err );
		if( $make_javascript_safe ) {
			return jx_Lang::escape_for_javascript( $str );
		} else {
			return $str;
		}
	}
	function mime( $mime, $make_javascript_safe=false ) {
		$str = jx_Lang::_get('mimes', $mime );
		if( $make_javascript_safe ) {
			return jx_Lang::escape_for_javascript( $str );
		} else {
			return $str;
		}
	}
	/**
	 * Gets the string from the array
	 *
	 * @param string $array_index
	 * @param string $message
	 * @return string
	 * @access private
	 */
	function _get( $array_index, $message ) {
		if( is_array( $message )) {
			return @$GLOBALS[$array_index][key($message)][current($message)];
		}
		return @$GLOBALS[$array_index][$message];
	}
	
	function escape_for_javascript( $string ) {
		return str_replace(Array("\r", "\n" ), Array('\r', '\n' ) , addslashes($string));
	}
}