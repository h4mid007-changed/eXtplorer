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
 * 
 */
/**
 * Allows to extract archives on the server
 *
 */
class jx_Extract extends jx_Action {

	function execAction( $dir, $item ) {
		
		global $mosConfig_absolute_path;
	
		if( !jx_isArchive( $item )) {
			jx_Result::sendResult('archive', false, jx_Lang::err('extract_noarchive'));
		}
		else {
	
			$archive_name = realpath(get_abs_item($dir,$item));
	
			$file_info = pathinfo($archive_name);
	
			if( empty( $dir )) {
				$extract_dir = realpath($GLOBALS['home_dir']);
			}
			else {
				$extract_dir = realpath( $GLOBALS['home_dir']."/".$dir );
			}
	
			$ext = $file_info["extension"];
	
			require_once(_JX_PATH . "/libraries/Archive.php");
			$archive_name .= '/';
			$result = File_Archive::extract( $archive_name, $extract_dir );
			if( PEAR::isError( $result )) {
				jx_Result::sendResult('extract', false, jx_Lang::err('extract_failure').': '.$result->getMessage() );
			}
			
			jx_Result::sendResult('extract', true, jx_Lang::msg('extract_success') );
	
		}
	}
}
?>