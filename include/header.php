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
 * This is the file, which prints the header row with the Logo
 */
function show_header($dirlinks='') {
	$url = str_replace( '&dir=', '&ignore=', $_SERVER['REQUEST_URI'] );
	echo "<link rel=\"stylesheet\" href=\""._JX_URL."/style/style.css\" type=\"text/css\" />\n";
	echo "<div id=\"jx_header\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"5\">\n";
	$mode = mosGetParam( $_SESSION, 'file_mode', 'file' );
	$logoutlink = $mode == 'ftp' ? ' <a href="index2.php?option=com_joomlaxplorer&amp;action=ftp_logout" title="'.$GLOBALS['messages']['logoutlink'].'">['.$GLOBALS['messages']['logoutlink'].']</a>' : '';
	$alternate_mode = $mode == 'file' ? 'ftp' : 'file';
	echo "<tr><td width=\"20%\"><a href=\"index2.php\">Back to ".$GLOBALS['_VERSION']->PRODUCT.'</a></td>';
	// Logo
	echo "<td style=\"color:black;\" width=\"10%\">";
	//echo "<div style=\"margin-left:10px;float:right;\" width=\"305\" >";
	echo "<a href=\"".$GLOBALS['jx_home']."\" target=\"_blank\" title=\"joomlaXplorer Project\">
		<img src=\""._JX_URL."/images/joomlaXplorer.png\" alt=\"joomlaXplorer\" border=\"0\" /></a>
		</td>";
	//echo "</div>";
	echo "<td style=\"padding-left: 15px; color:black;\" id=\"bookmark_container\" width=\"35%\"></td>\n";
	echo "<td width=\"25%\" style=\"padding-left: 15px; color:black;\">".sprintf( $GLOBALS['messages']['switch_file_mode'], $mode . $logoutlink, "<a id=\"switch_file_mode\" href=\"$url&amp;file_mode=$alternate_mode\">$alternate_mode</a>" ). "
	</td>\n";
	
	echo '</tr></table>';
	echo '</div>';
}
//------------------------------------------------------------------------------
?>
