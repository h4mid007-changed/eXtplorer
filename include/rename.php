<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id$
 * @package eXtplorer
 * @copyright soeren 2007-2012
 * @author The eXtplorer project (http://extplorer.net)
 * @author The	The QuiX project (http://quixplorer.sourceforge.net)
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
 * Allows to rename files and dirs
 *
 */
class ext_Rename extends ext_Action {

	function execAction($dir, $item) {		// rename directory or file

		if(($GLOBALS["permissions"]&01)!=01) {
			ext_Result::sendResult('rename', false, $GLOBALS["error_msg"]["accessfunc"]);
		}

		if(isset($GLOBALS['__POST']["confirm"]) && $GLOBALS['__POST']["confirm"]=="true") {

			$newitemname=$GLOBALS['__POST']["newitemname"];
			$newitemname=trim(basename(stripslashes($newitemname)));

			if($newitemname=='' ) {
				ext_Result::sendResult('rename', false, $GLOBALS["error_msg"]["miscnoname"]);
			}
			if( !ext_isFTPMode()) {
				$abs_old = get_abs_item($dir,$item);
				$abs_new = get_abs_item($dir,$newitemname);
			} else {
				$abs_old = get_item_info($dir,$item);
				$abs_new = get_item_info($dir,$newitemname);
			}

			if(@$GLOBALS['ext_File']->file_exists($abs_new)) {
				ext_Result::sendResult('rename', false, ext_TextEncoding::toUTF8($newitemname).": ".$GLOBALS["error_msg"]["itemdoesexist"]);
			}
			$perms_old = $GLOBALS['ext_File']->fileperms( $abs_old );

			$ok=$GLOBALS['ext_File']->rename( get_abs_item($dir,$item), get_abs_item($dir,$newitemname) );
			if( ext_isFTPMode()) {
				$abs_new = get_item_info($dir,$newitemname);
			}

			$GLOBALS['ext_File']->chmod( $abs_new, $perms_old );

			if($ok===false || PEAR::isError($ok)) {
				ext_Result::sendResult('rename', false, 'Could not rename '.$dir.'/'.$item.' to '.$newitemname);
			}

			$msg = sprintf( $GLOBALS['messages']['success_rename_file'], $item, $newitemname );

			ext_Result::sendResult('rename', true, $msg );
		}
		$is_dir = get_is_dir(ext_isFTPMode() ? get_item_info($dir,$item) : get_abs_item($dir,$item));


	}
}
?>