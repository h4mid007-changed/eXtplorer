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
 * Allows to create dirs, files and symlinks on a server
 *
 */
class jx_Mkitem extends jx_Action {
	
	function execAction($dir) {		// make new directory or file
		if(($GLOBALS["permissions"]&01)!=01) jx_Result::sendResult( 'mkitem', false, $GLOBALS["error_msg"]["accessfunc"]);
		
		if( mosGetParam($_POST,'confirm') == 'true') {
			$mkname=$GLOBALS['__POST']["mkname"];
			$mktype=$GLOBALS['__POST']["mktype"];
			$symlink_target = $GLOBALS['__POST']['symlink_target'];
			
			$mkname=basename(stripslashes($mkname));
			if($mkname=="") jx_Result::sendResult( 'mkitem', false,  $GLOBALS["error_msg"]["miscnoname"] );
			
			$new = get_abs_item($dir,$mkname);
		
			if(@$GLOBALS['jx_File']->file_exists($new)) {
				jx_Result::sendResult( 'mkitem', false, $mkname.": ".$GLOBALS["error_msg"]["itemdoesexist"]);
			}
			$err = print_r( $_POST, true );
			if($mktype=="dir") {
				$ok=@$GLOBALS['jx_File']->mkdir($new, 0777);
				$err=$GLOBALS["error_msg"]["createdir"];
			} elseif( $mktype == 'file') {
				$ok=@$GLOBALS['jx_File']->mkfile($new);
				$err=$GLOBALS["error_msg"]["createfile"];
			} elseif( $mktype == 'symlink' ) {
				if( empty( $symlink_target )) {
					jx_Result::sendResult( 'mkitem', false, 'Please provide a valid <strong>target</strong> for the symbolic link.');
				}
				if( !file_exists($symlink_target) || !is_readable($symlink_target)) {
					jx_Result::sendResult( 'mkitem', false, 'The file you wanted to make a symbolic link to does not exist or is not accessible by PHP.');
				}
				$ok = symlink( $symlink_target, $new );
				$err = 'The symbolic link could not be created.';
			}
			
			if($ok==false || PEAR::isError( $ok )) {
				if( PEAR::isError( $ok ) ) $err.= $ok->getMessage();
				jx_Result::sendResult( 'mkitem', false, $err);
			}
			$result = array('action' => 'The item '.$new.' was created', 
							'name' => $mkname,
							'success' => true );
			$json = new Services_JSON();
			$jresult = $json->encode($result);
			print $jresult;
			return;
		}
	?>
		<div>
	    <div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>
	    <div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc">
	
	        <h3 style="margin-bottom:5px;">Create New File/Directory</h3>
	        <div id="adminForm">
	
	        </div>
	    </div></div></div>
	    <div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>
	</div>
	<script type="text/javascript">
	var mktypes = new Ext.data.SimpleStore({
	    fields: ['mktype', 'type'],
	    data :  [
	        ['file', '<?php echo jx_Lang::mime( 'file', true ) ?>'],
	        ['dir', '<?php echo jx_Lang::mime( 'dir', true ) ?>']
	        <?php
	        if( !jx_isFTPMode() && !$GLOBALS['isWindows']) { ?>
	        	,['symlink', '<?php echo jx_Lang::mime( 'symlink', true ) ?>']
	        	<?php
	        } ?>
	        ]
	});
	var simple = new Ext.form.Form({
	    labelWidth: 125, // label settings here cascade unless overridden
	    url:'index2.php'
	});
	simple.add(
	    new Ext.form.TextField({
	        fieldLabel: '<?php echo jx_Lang::msg( 'nameheader', true ) ?>',
	        name: 'mkname',
	        width:175,
	        allowBlank:false
	    }),
		new Ext.form.ComboBox({
			fieldLabel: 'Type',
		    store: mktypes,
		    displayField:'type',
		    valueField: 'mktype',
		    hiddenName: 'mktype',
		    disableKeyFilter: true,
		    editable: false,
		    mode: 'local',
		    allowBlank: false,
		    selectOnFocus:true
		}),
	    new Ext.form.TextField({
	        fieldLabel: '<?php echo jx_Lang::msg( 'symlink_target', true ) ?>',
	        name: 'symlink_target',
	        width:175,
	        allowBlank:true
	    })
	);
	
	simple.addButton('<?php echo jx_Lang::msg( 'btncreate', true ) ?>', function() {
	    simple.submit({
	        waitMsg: 'Processing Data, please wait...',
	        //reset: true,
	        reset: false,
	        success: function(form, action) {	
	        	//Ext.MessageBox.alert('Success', action.result.action);
	        	try{ 
	        		dirTree.getSelectionModel().getSelectedNode().reload(); 
	        	} catch(e) {}
				datastore.reload();
				dialog.destroy();
	        },
	        failure: function(form, action) {
				Ext.MessageBox.alert('Error!', action.result.error);
	        },
	        scope: simple,
	        // add some vars to the request, similar to hidden fields
	        params: {option: 'com_joomlaxplorer', 
	        		action: 'mkitem', 
	        		dir: datastore.directory, 
	        		confirm: 'true'}
	    })
	});
	simple.addButton('<?php echo jx_Lang::msg( 'btncancel', true ) ?>', function() { dialog.destroy(); } );
	simple.render('adminForm');
	</script>
	<?php
	}
}

//------------------------------------------------------------------------------
?>
