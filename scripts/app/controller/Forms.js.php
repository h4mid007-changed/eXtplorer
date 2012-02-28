<?php
if( !defined( '_JEXEC' )) {
	$_REQUEST['action'] = 'include_javascript';
	$_GET['subdir'][] = 'app/controller';
	$_GET['file'][] = str_replace('.php', '', basename(__FILE__) );
	include('../../../index.php');
}

/**
 * @version $Id: functions.php 202 2011-07-20 12:15:45Z soeren $
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
?>
Ext.define('eXtplorer.controller.Forms', {
	extend: 'Ext.app.Controller',
	
	views: ['FileList', 'DirectoryTree', 'gridCtxMenu', 'dirCtxMenu', 'CopyMoveCtxMenu' ],
	refs: [{
		ref: 'filesList',
		selector: 'filelist'
	},{
		ref: 'dirTree',
		selector: 'dirtree'
	}, {
		ref: 'gridCtx',
		selector: 'gridctx'
	}, {
		ref: 'dirCtx',
		selector: 'dirctx'
	}],
	
	getMainPanel: function() {
		tabpanel = Ext.ComponentQuery.query('tabpanel')[0];
		return tabpanel;
	},
	getSelectedRecords: function() {
		var selectedRows = new Array();
		selectedRows = this.getFilesList().getSelectionModel().getSelection();
		if( selectedRows.length < 1 ) {
			var selectedNode = this.getDirTree().getSelectionModel().getSelection();
			if( selectedNode.length > 0 ) {
				selectedRows = Array( this.getDirTree().getSelectionModel().getSelection()[0].id.replace( /_RRR_/g, '/' ) );
			}
		}
		return selectedRows;
	},
	genericForm: function( action ) {
		// we expect the returned JSON to be an object that
		var selectedRecords = this.getSelectedRecords();
		if( action == 'copy' || action == 'move') {
			var actionCls = 'Copymove';
		}else {
			var actionCls = Ext.String.camelize( action );
		}
		var formPanel = Ext.create('eXtplorer.view.forms.' + actionCls, {
							selectedRecords: selectedRecords,
							dir: this.getCurrentDir(),
							action: action 
						});
		
		this.dialog = Ext.create('Ext.window.Window', {
		    title: formPanel.title,
		    layout: 'fit',
		    items: formPanel
		}).show();
        formPanel.setTitle("");
		var cancelBtn = formPanel.query('button[action=cancel]')[0];
		if( typeof cancelBtn != 'undefined' ) {
			cancelBtn.on('click', function() { this.dialog.destroy() }, this );
		}
		var saveBtn = formPanel.query('button[action=save]')[0];
		if( typeof saveBtn != 'undefined' ) {
			saveBtn.on("click", function() {
					
				statusBarMessage( '<?php echo ext_Lang::msg('save_processing', true ) ?>', true );
				var frm = formPanel.getForm();
				frm.submit({
					url: "index.php",
					waitMsg: 'Please wait...',
					reset: false,
					success: function(frm, action) {
						this.application.getController('File').onLoadFileList();
						statusBarMessage( action.result.message, false, true );
						this.dialog.destroy();
					},
					failure: function(frm, action) {
						statusBarMessage( action.result.error, false, false );
						Ext.Msg.alert('<?php echo ext_Lang::err('error', true) ?>!', action.result.error);
					},
					scope: this,
					// add some vars to the request, similar to hidden fields
					params: this.application.getController('File').getRequestParams(action)
				});
			}, this );
		}
		var archiveBtn = formPanel.query('button[action=start_archiving]')[0];
		if( typeof archiveBtn != 'undefined' ) {
			archiveBtn.on("click", function() {
				this.submitArchiveForm(formPanel.getForm(), 0 );
			}, this );
		}
		//firstComponent = dialog.getComponent(0);
		//dialog.setSize( firstComponent.getSize() );
											
		// center the window
		this.dialog.center();
	},
	getFileStore: function() {
		return this.application.getController('File').getFileStore();
	},
	getCurrentDir: function() {
		return this.application.getController('File').currentDir;
	},
	editForm: function( json, options ) {
		
		var form = Ext.create("eXtplorer.view.forms.Edit", {
			title: json.title,
			id: json.id_hash,
			taContent: json.content,
			dir: this.getCurrentDir(),
			filename: json.item,
			cp_lang: json.cp_lang,
			language: json.language,
			taWidth: this.getMainPanel().getWidth() - 100,
			taHeight: this.getMainPanel().getHeight() - 100
		});
		this.getMainPanel().add(form);
		this.getMainPanel().setActiveTab(form.id);
		
		<?php if ($GLOBALS["language"] == "japanese"){ ?>
			form.add({
					 width: response.cw,  
					 style:"margin-left:10px", 
					 clear:"true",
					xtype: "combo",
					fieldLabel: "<?php echo ext_Lang::msg('fileencoding', true ) ?>",
					name: "file_encoding",
					store: [
								["UTF-8", "UTF-8"],
								["SJIS-WIN", "SJIS"],
								["EUCJP-WIN", "EUC-JP"],
								["ISO-2022-JP","JIS"]
							],
					value : json._encoding_label,
					typeAhead: "true",
					mode: "local",
					triggerAction: "all",
					editable: "false",
					forceSelection: "true"
				});
			
		<?php } ?>
		this.control('#' + json.id_hash + ' button[action=save]', {
				click: function() { 
					
					statusBarMessage( '<?php echo ext_Lang::msg('save_processing', true ) ?>', true );
					var frm = form.getForm();
					frm.submit({
						url: "index.php",
						waitMsg: 'Saving the File, please wait...',
						reset: false,
						success: function(frm, action) {
							this.getFileStore().load();
							statusBarMessage( action.result.message, false, true );
						},
						failure: function(frm, action) {
							statusBarMessage( action.result.error, false, false );
							Ext.Msg.alert('<?php echo ext_Lang::err('error', true) ?>!', action.result.error);
						},
						scope: this,
						// add some vars to the request, similar to hidden fields
						params: {
							//code: editAreaLoader.getValue("ext_codefield" + this.id),
							dosave: 'yes'
						}
					});
				}
			});
		this.control('#' + json.id_hash + ' button[action=reload]', {
			click: function() {
			statusBarMessage( '<?php echo ext_Lang::msg('reopen_processing', true ) ?>', true );
			var frm = form.getForm();
			frm.submit({
				url: "index.php",
				waitMsg: 'Processing Data, please wait...',
				reset: false,
				success: function(frm, action) {
					statusBarMessage( action.result.message, false, true );
					frm.findField('code').setValue( action.result.content );
				},
				failure: function(form, action) {
					statusBarMessage( action.result.error, false, false );
					Ext.Msg.alert('<?php echo ext_Lang::err('error', true) ?>!', action.result.error);
				},
				scope: frm,
				// add some vars to the request, similar to hidden fields
				params: {
					option: 'com_extplorer',
					action: 'edit',
					dir: frm.findField('dir').getValue(),
					item: frm.findField('item').getValue(),
				}
			});
		}
		});
		this.control('#' + json.id_hash + ' button[action=cancel]', {
			click: function() {
				this.getMainPanel().remove( json.id_hash );
			}
		});
	},
	submitArchiveForm: function( form, startfrom, msg ) {
		if( startfrom == 0 ) {
			Ext.Msg.show({
				title: 'Please wait',
				msg: msg ? msg : '<?php echo ext_Lang::msg( 'creating_archive', true ) ?>',
				progressText: 'Initializing...',
				width:300,
				progress:true,
				closable:false,
			});
		}
		params = this.application.getController('File').getRequestParams();
		params.action = 'archive',
		params.startfrom = startfrom,
		params.confirm = 'true'
		
		form.submit({
			url: "index.php",
			reset: false,
			success: function(form, action) {
				if( !action.result ) return;
	
				if( action.result.startfrom > 0 ) {
					submitArchiveForm( action.result.startfrom, action.result.message );
	
					i = action.result.startfrom/action.result.totalitems;
					Ext.Msg.updateProgress(i, action.result.startfrom + " of "+action.result.totalitems + " (" + Math.round(100*i)+'% completed)');
	
					return
				} else {
	
					if( form.findField('download').getValue() ) {
						this.getFileStore().load();
						Ext.Msg.hide();
						this.dialog.destroy();
						location.href = action.result.newlocation;
						
					} else {
						Ext.Msg.alert('<?php echo ext_Lang::msg('success', true) ?>!', action.result.message);
						this.dialog.destroy();
						this.getFileStore().load();
						Ext.Msg.hide();
					}
					return;
				}
			},
			failure: function(form, action) {
				Ext.Msg.hide();
				if( action.result ) {
					Ext.Msg.alert('<?php echo ext_Lang::err('error', true) ?>', action.result.error);
				} else {
					Ext.Msg.alert('<?php echo ext_Lang::err('error', true) ?>', "An unknown Error occured" );
				}
			},
			scope: this,
			
			params: params
		});
	}
	
});