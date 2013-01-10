<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: users.php 206 2011-08-26 19:09:47Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2012
 * @author The eXtplorer project (http://extplorer.net)
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
 * Administrative Functions regarding users
 */
class eXtplorer_User {
	
	var $_users = array();
	
	function __construct() {
		require _EXT_PATH."/config/.htusers.php";
		$this->_users = $GLOBALS['users'];
	}

	/**
	 * Saves users to htusers file
	 */
	 function save() {
		$cnt=count($this->_users);
		if($cnt>0) sort($this->_users);

		// Make PHP-File
		$content='<?php 
		// ensure this file is being included by a parent file
		if( !defined( \'_JEXEC\' ) && !defined( \'_VALID_MOS\' ) ) die( \'Restricted access\' );
		$GLOBALS["users"]=array(';
		for($i=0;$i<$cnt;++$i) {
				
			// Skip empty user entries (e.g. removed users)
			if( !is_array($this->_users[$i])) continue;
		
			// if($GLOBALS["users"][6]&4==4) $GLOBALS["users"][6]=7;	// If admin, all permissions
			$content.="\r\n\tarray('".$this->_users[$i][0]."','".
				$this->_users[$i][1]."','".$this->_users[$i][2]."','".$this->_users[$i][3]."','".
				$this->_users[$i][4]."','".$this->_users[$i][5]."','".$this->_users[$i][6]."',".
				(int)$this->_users[$i][7].'),';
		}
		$content.="\r\n); \r\n?>";
	
		// Write to File
		if( !is_writable(_EXT_PATH."/config/.htusers.php") && !chmod( _EXT_PATH."/config/.htusers.php", 0644 ) ) {
			return false;
		}
		file_put_contents( _EXT_PATH."/config/.htusers.php", $content);
	
		return true;
	}

	/**
	 * 
	 * @param string $user
	 * @param string $pass
	 */
	function &load($user,$pass) {
		$return = null;
		$cnt=count($this->_users);
		for($i=0;$i<$cnt;++$i) {
			if($user==$this->_users[$i][0]) {
				if($pass===NULL || ($pass==$this->_users[$i][1] && $this->_users[$i][7])) {
					return $this->_users[$i];
				}
			}
		}
	
		return $return;
	}

	/**
	 * 
	 * @param array $data
	 */
	function add($data) {
		if( isset( $data[0])) {
			if($this->load($data[0],NULL) !== null) return false;
			// Append new user data to user array
		} else {
			if($this->load($data['nuser'],NULL) !== null) return false;
			$data[0] = $data['nuser'];
			$data[1] = extEncodePassword(stripslashes($data['pass1']));
			$data[2] = $data['home_dir'];
			$data[3] = $data['home_url'];
			$data[4] = (int)$data['show_hidden'];
			$data[5] = $data['no_access'];
			$data[6] = $data['permissions'];
			$data[7] = $data['active'];
		}
		$this->_users[] = $data;
		return true;
	}
	
	/**
	 *
	 * @param string $user
	 * @param array $new_data
	 */
	function update($user,$new_data) {
		$data=&$this->load($user,NULL);
		if($data==NULL) return false;
		if( isset( $new_data[0])) {
			$data=$new_data;
		} else {
			$data[0] = $new_data['nuser'];
			if( !empty( $new_data['pass1'] )) {
				$data[1] = extEncodePassword(stripslashes($new_data['pass1']));
			}
			$data[2] = $new_data['home_dir'];
			$data[3] = $new_data['home_url'];
			$data[4] = (int)$new_data['show_hidden'];
			$data[5] = $new_data['no_access'];
			$data[6] = $new_data['permissions'];
			$data[7] = $new_data['active'];
		}
		return true;
	
	}
	/**
	 * 
	 * @param string $user
	 */	
	function remove($user) {
		$user = &$this->load($user,NULL);
		if($user == NULL) return false;
	
		// Remove
		$user = NULL;
		return true;
	}
	/**
	 * 
	 * @param string $user The username
	 * @param string $pass The new password
	 */
	function set_password($user, $pass) {
		$pw = extEncodePassword(stripslashes($pass));
		$user = &$this->load($user, null);
		$user[1] = $pw;
	}
	/**
	 * Returns a JSON-encoded user list
	 */
	function user_list() {
		
		$classname = class_exists('ext_Json') ? 'ext_Json' : 'Services_JSON';
		$json = new $classname();
	
		$cnt= $this->count(false);
		$users = array( "totalCount" => $cnt );
		
		for($i=0;$i<$cnt;++$i) {
	
			$users['users'][] = array( 
					'nuser' => $this->_users[$i][0],
					'nuser_old' => $this->_users[$i][0],
					'home_dir' => $this->_users[$i][2],
					'home_url' => $this->_users[$i][3],
					'show_hidden' => ($this->_users[$i][4] ? true:false),
					'no_access' => $this->_users[$i][5],
					'permissions' => $this->_users[$i][6],
					'active' =>  ($this->_users[$i][7] ? true:false)
			);
		}
	
		echo $json->encode($users);
	
		ext_exit();
	}
	function count($active=true) {
		$cnt=count($this->_users);
		if(!$active) return $cnt;
	
		for($i=0, $j=0;$i<$cnt;++$i) {
			if($this->_users[$i][7]) ++$j;
		}
		return $j;
	}
	
}