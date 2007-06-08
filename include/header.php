<?php
/** ensure this file is being included by a parent file */
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/*------------------------------------------------------------------------------
     The contents of this file are subject to the Mozilla Public License
     Version 1.1 (the "License"); you may not use this file except in
     compliance with the License. You may obtain a copy of the License at
     http://www.mozilla.org/MPL/

     Software distributed under the License is distributed on an "AS IS"
     basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
     License for the specific language governing rights and limitations
     under the License.

     The Original Code is header.php, released on 2003-02-07.

     The Initial Developer of the Original Code is The QuiX project.

     Alternatively, the contents of this file may be used under the terms
     of the GNU General Public License Version 2 or later (the "GPL"), in
     which case the provisions of the GPL are applicable instead of
     those above. If you wish to allow use of your version of this file only
     under the terms of the GPL and not to allow others to use
     your version of this file under the MPL, indicate your decision by
     deleting  the provisions above and replace  them with the notice and
     other provisions required by the GPL.  If you do not delete
     the provisions above, a recipient may use your version of this file
     under either the MPL or the GPL."
------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------
Author: The QuiX project
	quix@free.fr
	http://www.quix.tk
	http://quixplorer.sourceforge.net

Comment:
	QuiXplorer Version 2.3
	Header File
	
	Have Fun...
-------------------------------------------------------------------------------*/
//------------------------------------------------------------------------------
function show_header($dirlinks='') {
	$url = str_replace( '&dir=', '&ignore=', $_SERVER['REQUEST_URI'] );
	echo "<link rel=\"stylesheet\" href=\""._JX_URL."/style/style.css\" type=\"text/css\" />\n";
	echo "<div id=\"jx_header\" class=\"x-layout-inactive-content\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" class=\"adminheading\">\n";
	$mode = mosGetParam( $_SESSION, 'file_mode', 'file' );
	$logoutlink = $mode == 'ftp' ? ' <a href="index2.php?option=com_joomlaxplorer&amp;action=ftp_logout" title="'.$GLOBALS['messages']['logoutlink'].'">['.$GLOBALS['messages']['logoutlink'].']</a>' : '';
	$alternate_mode = $mode == 'file' ? 'ftp' : 'file';
	echo "<tr><td width=\"25%\">".sprintf( $GLOBALS['messages']['switch_file_mode'], $mode . $logoutlink, "<a id=\"switch_file_mode\" href=\"".$url."&amp;file_mode=$alternate_mode\">$alternate_mode</a>" ). "\n";
	echo "</td>\n";
	// Logo
	echo "<td width=\"10%\">";
	//echo "<div style=\"margin-left:10px;float:right;\" width=\"305\" >";
	echo "<a href=\"".$GLOBALS['jx_home']."\" target=\"_blank\" title=\"joomlaXplorer Project\" style=\"color:white;font-weight:bold;font-size:larger;\">joomlaXplorer</a>";
	//echo "</div>";
	echo "</td>\n";
	
	echo "<td id=\"bookmark_container\" width=\"35%\"></td>\n";
	
	echo '</tr></table>';
	echo '</div>';
}
//------------------------------------------------------------------------------
?>
