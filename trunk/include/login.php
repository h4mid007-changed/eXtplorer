<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: $
 * @package eXtplorer
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
 * User Authentication Functions
 * (currently not used)
 */

//------------------------------------------------------------------------------
require _EXT_PATH."/include/users.php";
load_users();
//------------------------------------------------------------------------------

$GLOBALS['__SESSION']=&$_SESSION;

//------------------------------------------------------------------------------
function login() {
	
	if(!empty($GLOBALS['__SESSION']["s_user"])) {
		if(!activate_user($GLOBALS['__SESSION']["s_user"],$GLOBALS['__SESSION']["s_pass"])) {
			logout();
		}
	} else {
		if(isset($GLOBALS['__POST']["p_pass"])) $p_pass=$GLOBALS['__POST']["p_pass"];
		else $p_pass="";
		
		if(isset($GLOBALS['__POST']["p_user"])) {
			// Check Login
			if(!activate_user(stripslashes($GLOBALS['__POST']["p_user"]), md5(stripslashes($p_pass)))) {
				jx_Result::sendResult('login', false, 'Login Failed, try again.' );
			}
			jx_Result::sendResult('login', true, 'Login Successful' );
		} else {
			// Ask for Login
			
			$GLOBALS['mainframe']->addcustomheadtag( '
		<script type="text/javascript" src="'. _EXT_URL . '/fetchscript.php?'
			.'subdir[0]=scripts/codepress/&amp;file[0]=codepress.js'
			.'&amp;subdir[1]=scripts/extjs/&amp;file[1]=yui-utilities.js'
			.'&amp;subdir[2]=scripts/extjs/&amp;file[2]=ext-yui-adapter.js'
			.'&amp;subdir[3]=scripts/extjs/&amp;file[3]=ext-all.js&amp;gzip=1"></script>
		<script type="text/javascript" src="'. $GLOBALS['script_name'].'?option=com_extplorer&amp;action=include_javascript&amp;file=functions.js"></script>	
		<link rel="stylesheet" href="'. _EXT_URL . '/fetchscript.php?subdir[0]=scripts/extjs/css/&file[0]=ext-all.css&amp;subdir[1]=scripts/extjs/css/&file[1]=xtheme-aero.css&amp;gzip=1" />');
			
			$langs = get_languages();
			?>
			<div style="width: 400px;" id="formContainer">
	    <div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>
	    <div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc">
	
	        <h3 style="margin-bottom:5px;">eXtplorer - <?php echo jx_Lang::msg('actlogin') ?></h3>
	        <div id="adminForm">
	
	        </div><div class="jx_statusbar" id="statusBar"></div>
	    </div></div></div>
	    <div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>
	    
	</div>
	<script type="text/javascript">
	var languages = new Ext.data.SimpleStore({
	    fields: ['language', 'langname'],
	    data :  [
	    <?php foreach( $langs as $language => $name ) {
	    	echo "['$language', '$name' ],";
	    }
	      ?>
	        ]
	});
	var simple = new Ext.form.Form({
	    labelWidth: 125, // label settings here cascade unless overridden
	    url:'<?php echo basename( $GLOBALS['script_name']) ?>'
	});
	simple.add(
	    new Ext.form.TextField({
	        fieldLabel: '<?php echo jx_Lang::msg( 'miscusername', true ) ?>',
	        name: 'p_user',
	        width:175,
	        allowBlank:false
	    }),
	    new Ext.form.TextField({
	        fieldLabel: '<?php echo jx_Lang::msg( 'miscpassword', true ) ?>',
	        name: 'p_pass',
	        inputType: 'password',
	        width:175,
	        allowBlank:false
	    }),
		new Ext.form.ComboBox({
			fieldLabel: '<?php echo jx_Lang::msg( 'misclang', true ) ?>',
		    store: languages,
		    displayField:'langname',
		    valueField: 'language',
		    hiddenName: 'lang',
		    disableKeyFilter: true,
		    editable: false,
		    triggerAction: 'all',
		    mode: 'local',
		    allowBlank: false,
		    selectOnFocus:true
		})
	);
	
	simple.addButton('<?php echo jx_Lang::msg( 'btnlogin', true ) ?>', function() {
		Ext.get( 'statusBar').update( 'Please wait...' );
	    simple.submit({
	        //reset: true,
	        reset: false,
	        success: function(form, action) {	
	        	Ext.get( 'statusBar').update( action.result.message );
				location.reload();
	        },
	        failure: function(form, action) {
	        	if( !action.result ) return;
				Ext.MessageBox.alert('Error!', action.result.error);
				Ext.get( 'statusBar').update( action.result.error );
				simple.findField( 'p_pass').setValue('');
				simple.findField( 'p_user').focus();
	        },
	        scope: simple,
	        // add some vars to the request, similar to hidden fields
	        params: {option: 'com_extplorer', 
	        		action: 'login'
	        }
	    })
	});
	simple.addButton('<?php echo jx_Lang::msg( 'btnreset', true ) ?>', function() { simple.reset(); } );
	simple.render('adminForm');
	Ext.get( 'formContainer').center();
	if(document.login) document.login.p_user.focus();

</script><?php
			show_footer();
			define( '_LOGIN_REQUIRED', 1 );
		}
	}
}
//------------------------------------------------------------------------------
function logout() {
	unset( $GLOBALS['__SESSION']["s_user"] );
	unset( $GLOBALS['__SESSION']["s_pass"] );
	session_write_close();
	header("location: ".$GLOBALS["script_name"].$GLOBALS['__SESSION']["s_user"]);
}
//------------------------------------------------------------------------------
?>
