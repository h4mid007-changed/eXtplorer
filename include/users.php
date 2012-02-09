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
 * Administrative Functions regarding users
 */
class ext_Users extends ext_Action {
	var $users;
	
	function __construct() {
		require_once( _EXT_PATH.'/include/user/eXtplorer.php');
		$this->users = new eXtplorer_User();
	}
	
	function execAction($dir, $item) {
		$pwd=(($GLOBALS["permissions"]&2)==2);
		$admin=(($GLOBALS["permissions"]&4)==4);
		
		if(!$GLOBALS["require_login"]) {
			ext_Result::sendResult('admin', false, $GLOBALS["error_msg"]["miscnofunc"]);
			break;
		}
		if(!$pwd && !$admin) {
			ext_Result::sendResult('admin', false, $GLOBALS["error_msg"]["accessfunc"]);
			break;
		}
		if(isset($GLOBALS['__GET']["action2"])) $action2 = $GLOBALS['__GET']["action2"];
		elseif(isset($GLOBALS['__POST']["action2"])) $action2 = $GLOBALS['__POST']["action2"];
		else $action2="";
		
		
		switch($action2) {
			case "chpwd":
				$this->changepwd();
				break;
			case "adduser":
				if(!$admin) {
					ext_Result::sendResult('admin', false, $GLOBALS["error_msg"]["accessfunc"]); break;
				}
				$this->adduser();
				break;
			case "edituser":
				
				if(!$admin) {
					ext_Result::sendResult('admin', false, $GLOBALS["error_msg"]["accessfunc"]); break;
				}
				$this->edituser();
				break;
			case "rmuser":
				if(!$admin) {
					ext_Result::sendResult('admin', false, $GLOBALS["error_msg"]["accessfunc"]); break;
				}
				$this->removeuser();
				break;
			default:
				if(!$admin) {
					ext_Result::sendResult('admin', false, $GLOBALS["error_msg"]["accessfunc"]); break;
				}
				$this->users->user_list();
		}
	}
	function changepwd() {			// Change Password
		
		if($GLOBALS['__POST']["newpwd1"]!=$GLOBALS['__POST']["newpwd2"]) {
			ext_Result::sendResult('changepwd', false, $GLOBALS["error_msg"]["miscnopassmatch"]);
		}
		
		$username = $GLOBALS['__SESSION']['credentials_extplorer']['username'];
		
		$data = $this->users->load($username,null );
		// Username not existing
		if( $data === NULL ) {
			ext_Result::sendResult('changepwd', false, $GLOBALS["error_msg"]["miscnouserpass"]);
		}
		require_once( _EXT_PATH.'/libraries/PasswordHash.php');
		$hasher = new PasswordHash(8, FALSE);
		$result = $hasher->CheckPassword($GLOBALS['__POST']["oldpwd"], $data[1]);
		if(!$result) {
			$data= $this->users->load($username,md5(stripslashes($GLOBALS['__POST']["oldpwd"])));	
			if($data==NULL) {
				ext_Result::sendResult('changepwd', false, $GLOBALS["error_msg"]["miscnouserpass"]);
			}
		}
		$this->users->set_password($username, $GLOBALS['__POST']["newpwd1"]);
		if( !$this->users->save() ) {
			ext_Result::sendResult('changepwd', false, $data[0].": ".$GLOBALS["error_msg"]["chpass"]);;
		}
		require_once(_EXT_PATH.'/include/authentication/extplorer.php');
		$auth = new ext_extplorer_authentication();
		$auth->onAuthenticate(array('username'=>$data[0],'password'=>$data[1]));
	
		ext_Result::sendResult('changepwd', true, ext_Lang::msg('change_password_success'));
	}
	
	/**
	 * Adds a new user
	 */
	function adduser() {
		$userdata = (array)$this->readJsonInput();
		if(is_array( $userdata )) {
			
			if(empty($userdata["nuser"]) || empty($userdata["home_dir"])) {
				ext_Result::sendResult('adduser', false, $GLOBALS["error_msg"]["miscfieldmissed"]);
			}
			if($userdata["pass1"]!=$userdata["pass2"]) {
				ext_Result::sendResult('adduser', false, $GLOBALS["error_msg"]["miscnopassmatch"]);
			}
			$nuser = stripslashes($userdata["nuser"]);
			$data= $this->users->load($nuser, NULL);
			if($data!=NULL) {
				ext_Result::sendResult('adduser', false, $nuser.": ".$GLOBALS["error_msg"]["miscuserexist"]);
			}
	 		 
			if(!$this->users->add($userdata)) {
				ext_Result::sendResult('adduser', false, $nuser.": ".$GLOBALS["error_msg"]["adduser"]);
			}
			if( $this->users->save()) {
				ext_Result::sendResult('adduser', true, $nuser.": The user has been added");
			}
			return;
		}
	
	
	}
	/**
	 * Modify a user entry
	 */
	function edituser() {
		$userdata = (array)$this->readJsonInput();
		$username = $userdata['nuser_old'];
		$data=$this->users->load($username,NULL);
		if($data==NULL) {
			ext_Result::sendResult('edituser', false, $userdata['nuser_old'].": ".$GLOBALS["error_msg"]["miscnofinduser"]);
		}
	
		if($self=($GLOBALS['__SESSION']['credentials_extplorer']['username']==$username)) $dir="";
		if(is_array( $userdata )) {
			
			if(empty($userdata["nuser"]) || empty($userdata["home_dir"])) {
				ext_Result::sendResult('adduser', false, $GLOBALS["error_msg"]["miscfieldmissed"]);
			}
			if($userdata["pass1"]!=$userdata["pass2"]) {
				ext_Result::sendResult('adduser', false, $GLOBALS["error_msg"]["miscnopassmatch"]);
			}
				
			if($self) $userdata["active"]=1;
	
			if(!$this->users->update($userdata['nuser_old'], $userdata)) {
				ext_Result::sendResult('edituser', false, $username.": ".$GLOBALS["error_msg"]["saveuser"]);
			}
			/*if($self) {
				activate_user($nuser,NULL);
			}*/
			if( $this->users->save()) {
				ext_Result::sendResult('edituser', true, $username.": ".ext_Lang::msg('User Profile has been updated'), array('users' => $userdata) );
			}
		}
	
	}
	
	/**
	 * Removes a user entry
	 */
	function removeuser() {
		
		$userdata = (array)$this->readJsonInput();
		$username = $userdata['nuser'];
		if($username==$GLOBALS['__SESSION']['credentials_extplorer']['username']) {
			ext_Result::sendResult('removeuser', false, $GLOBALS["error_msg"]["miscselfremove"]);
		}
		if(!$this->users->remove($username)) {
			ext_Result::sendResult('removeuser', false, $username.": ".$GLOBALS["error_msg"]["deluser"]);
		}
		if( $this->users->save()) {
			ext_Result::sendResult('removeuser', true, $username." was successfully removed." );
		}
	
	}	
}