<?php
/** ensure this file is being included by a parent file */
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @copyright The contents of this file are subject to the Mozilla Public License
     Version 1.1 (the "License"); you may not use this file except in
     compliance with the License. You may obtain a copy of the License at
     http://www.mozilla.org/MPL/

     Software distributed under the License is distributed on an "AS IS"
     basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
     License for the specific language governing rights and limitations
     under the License.

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

* @author: soeren
* @package joomlaXplorer
* Bookmark Functions
*
* fixes applied by pokemon
*
*/
/**
 * reads all bookmarks from the bookmark ini file
 *
 * @return array
 */


function read_bookmarks() {
	global $my;
	$bookmarkfile = _JX_PATH.'/config/bookmarks_'.$GLOBALS['file_mode'].'_'.$my->id.'.php';
	if( file_exists( $bookmarkfile )) {
		return parse_ini_file( $bookmarkfile );
	}
	else {
		if( !is_writable( dirname( $bookmarkfile ) )) {
			return;
		} else {
			file_put_contents( $bookmarkfile, ";<?php if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' ); ?>\n{$GLOBALS['messages']['homelink']}=\n" );
			return array( $GLOBALS['messages']['homelink'] => '' );
		}
	}
}
/**
 * Adds a new bookmark to the bookmark ini file
 *
 * @param string $dir
 */
function modify_bookmark( $task, $dir ) {
	global $my;
	$alias = substr( mosGetParam($_REQUEST,'alias'), 0, 150 );
	$bookmarks = read_bookmarks();
	$bookmarkfile = _JX_PATH.'/config/bookmarks_'.$GLOBALS['file_mode'].'_'.$my->id.'.php';
	while( @ob_end_clean() );

	header( "Status: 200 OK" );

	switch ( $task ) {
		case 'add':

			if( in_array( $dir, $bookmarks )) {
				echo jx_alertBox( $GLOBALS['messages']['already_bookmarked'] ); exit;
			}
			$alias = preg_replace('~[^\w-.\/\\\]~','', $alias ); // Make the alias ini-safe by removing all non-word characters
			$bookmarks[$alias] = $dir; //we deal with the flippped array here
			$msg = jx_alertBox( $GLOBALS['messages']['bookmark_was_added'] );
			break;

		case 'remove':

			if( !in_array( $dir, $bookmarks )) {
				echo jx_alertBox( $GLOBALS['messages']['not_a_bookmark'] ); exit;
			}
			$bookmarks = array_flip( $bookmarks );
			unset( $bookmarks[$dir] );
			$bookmarks = array_flip( $bookmarks );
			$msg = jx_alertBox( $GLOBALS['messages']['bookmark_was_removed'] );
	}

	$inifile = "; <?php if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' ); ?>\n";
	$inifile .= $GLOBALS['messages']['homelink']."=\n";

	foreach( $bookmarks as $alias => $directory ) { //changed by pokemon
		if( empty( $directory ) || empty( $alias ) ) continue;
		if( $directory[0] == $GLOBALS['separator']) $directory = substr( $directory, 1 );
		$inifile .= "$alias=$directory\n";
	}
	if( !is_writable( $bookmarkfile )) {
		echo jx_alertBox( sprintf( $GLOBALS['messages']['bookmarkfile_not_writable'], $task, $bookmarkfile ) ); exit;
	}
	file_put_contents( $bookmarkfile, $inifile );

	echo $msg;
	echo list_bookmarks($dir);
	exit;
}

/**
 * Lists all bookmarked directories in a dropdown list.
 *
 * @param string $dir
 */
function list_bookmarks( $dir ) {

	$bookmarks = read_bookmarks();
	$bookmarks = array_flip($bookmarks);

	foreach( $bookmarks as $bookmark ) {
		$len = strlen( $bookmark );
		if( $len > 40 ) {
			$first_part = substr( $bookmark, 0, 20 );
			$last_part = substr( $bookmark, -20 );
			$bookmarks[$bookmark] = $first_part . '...' . $last_part;
		}
	}


	$html = $GLOBALS['messages']['quick_jump'].': ';
	$html .= jx_selectList( 'favourites', $dir, $bookmarks, 1, '', 'onchange="chDir( this.options[this.options.selectedIndex].value);" style="max-width: 100px;"');

	$img_add = '<img src="'._JX_URL.'/images/bookmark_add.png" border="0" alt="'.$GLOBALS['messages']['lbl_add_bookmark'].'" align="absmiddle" />';
	$img_remove = '<img src="'._JX_URL.'/images/remove.png" border="0" alt="'.$GLOBALS['messages']['lbl_remove_bookmark'].'" align="absmiddle" />';

	$addlink=$removelink='';

	if( !isset( $bookmarks[$dir] ) && $dir != '' && $dir != '/' ) {
		$addlink = '<a href="'.make_link('modify_bookmark', $dir ).'&task=add" onclick="'
		.'Ext.Msg.prompt(\''.$GLOBALS['messages']['lbl_add_bookmark'].'\', \''.$GLOBALS['messages']['enter_alias_name'].':\', '
		.'function(btn, text){ '
			.'if (btn == \'ok\') { '
				.'Ext.get(\'bookmark_container\').load({ '
					.'url: \'index.php\', '
					.'scripts: true, '
					.'params: { '
						.'action:\'modify_bookmark\', '
						.'task: \'add\', '
						.'requestType: \'xmlhttprequest\', '
						.'alias: text, '
						.'dir: \''.$dir.'\', '
						.'option: \'com_joomlaxplorer\' '
					.'} '
				.'}); '
			.'}'
		.'}); return false;" title="'.$GLOBALS['messages']['lbl_add_bookmark'].'" >'.$img_add.'</a>';
	}
	elseif( $dir != '' && $dir != '/' ) {
		$removelink = '<a href="'.make_link('modify_bookmark', $dir ).'&task=remove" onclick="'
		.'Ext.Msg.confirm(\''.$GLOBALS['messages']['lbl_remove_bookmark'].'\',\''.$GLOBALS['messages']['lbl_remove_bookmark'].'?\', '
		.'function(btn, text){ '
			.'if (btn == \'yes\') { '
				.'Ext.get(\'bookmark_container\').load({ '
					.'url: \'index.php\', '
					.'scripts: true, '
					.'params: { '
						.'action:\'modify_bookmark\', '
						.'task: \'remove\', '
						.'dir: \''.$dir.'\', '
						.'option: \'com_joomlaxplorer\' '
					.'} '
				.'}); '
			.'}'
		.'}); return false;" title="'.$GLOBALS['messages']['lbl_remove_bookmark'].'">'.$img_remove.'</a>';
	}

	$html .= $addlink .'&nbsp;'.$removelink;

	return $html;
}

?>