<?php
if( !defined( '_JEXEC' )) {
	$_REQUEST['action'] = 'include_javascript';
	$_GET['subdir'][] = 'app/view/forms';
	$_GET['file'][] = str_replace('.php', '', basename(__FILE__) );
	include('../../../../index.php');
}
?>
var datastore = Ext.data.StoreManager.lookup('File');
Ext.define( 'eXtplorer.view.forms.Upload', {
	extend: 'Ext.tab.Panel',
	initComponent: function() {
		this.callParent();
	},
	width: 700,
	stateId: "upload_tabpanel",
	activeTab: 1,
	title: "<?php echo ext_Lang::msg('actupload') ?>",		
	stateful: "true",
	
	stateEvents: ["tabchange"],
	getState: function() { return {
					activeTab:this.items.indexOf(this.getActiveTab())
				};
	},
	listeners: {	
		resize: {
			fn: function(panel) {	
				panel.items.each( function(item) { item.setHeight(500);return true } );								
			}
		}
					
	},
	items: [
		{
			xtype: "swfuploadpanel",
			title: "<?php echo Ext_Lang::msg('flashupload') ?>",
			height: 300,
			itemId: "swfuploader", 
			viewConfig: {
        		forceFit: true
			},
			listeners: {
				"allUploadsComplete": {
					fn: function(panel) {	
						datastore.load();	
						statusBarMessage('<?php echo ext_Lang::msg('upload_completed', true ) ?>', false );								
					}
				}				
			},
			// Uploader Params				
			upload_url: "<?php echo _EXT_URL .'/uploadhandler.php';	?>",
			post_params: { 
				<?php echo session_name()?>: "<?php echo session_id() ?>",
				<?php echo get_cfg_var ('session.name') ?>: "<?php echo session_id() ?>",
				session_name: "<?php echo session_name()?>",
				user_agent: "<?php echo addslashes( $_SERVER['HTTP_USER_AGENT'] ) ?>",
				option: "com_extplorer", 
				action: "upload", 
				dir: datastore.currentDir, 
				requestType: "xmlhttprequest",
				confirm: "true"
			},
			
<?php
		if ( $_SERVER['SERVER_NAME'] == 'localhost' ) echo 'debug: true,';
?>				
			flash_url: "<?php echo _EXT_URL ?>/scripts/extjs-ux/swfupload/swfupload.swf",
			prevent_swf_caching: false,
			file_size_limit: "<?php echo get_max_file_size() ?>B",
			// Custom Params
			single_file_select: false, // Set to true if you only want to select one file from the FileDialog.
			confirm_delete: false, // This will prompt for removing files from queue.
			remove_completed: false // Remove file from grid after uploaded.
		},
	{
		xtype: "form",
		autoScroll: true,
		autoHeight: true,
		autoRender: true,
		frame: true,
		padding: 10,
		itemId: "standardupload",
		fileUpload: true,
		labelWidth: 125,
		url:"index.php",
		title: "<?php echo ext_Lang::msg('standardupload') ?>",
		tooltip: "<?php echo ext_Lang::msg('max_file_size').' = <strong>'. ((get_max_file_size() / 1024) / 1024).' MB<\/strong><br \/>'
				.ext_Lang::msg('max_post_size').' = <strong>'. ((get_max_upload_limit() / 1024) / 1024).' MB<\/strong><br \/>';
				?>",
		items: [
		{
			xtype: "displayfield",
			value: "<?php echo ext_Lang::msg('max_file_size').' = <strong>'. ((get_max_file_size() / 1024) / 1024).' MB<\/strong><br \/>'
				.ext_Lang::msg('max_post_size').' = <strong>'. ((get_max_upload_limit() / 1024) / 1024).' MB<\/strong><br \/>';
				?>"
		},
		<?php
		for($i=0;$i<7;$i++) {
			echo '{
				xtype: "filefield",
				fieldLabel: "'.ext_Lang::msg('file', true ).' '.($i+1).'",
				id: "userfile'.$i.'",
				name: "userfile['.$i.']",
				width:275,
			},';
		}
		?>
		{	xtype: "checkbox",
			fieldLabel: "<?php echo ext_Lang::msg('overwrite_files', true ) ?>",
			name: "overwrite_files",
			value: true
		}],
		buttons: [{
			text: "<?php echo ext_Lang::msg( 'btnsave', true ) ?>", 
			handler: function() {
				statusBarMessage( '<?php echo ext_Lang::msg( 'upload_processing', true ) ?>', true );
				form = this.up("form").getForm();
				form.submit({
					//reset: true,
					reset: false,
					success: function(form, action) {
						datastore.load();
						statusBarMessage( action.result.message, false, true );
					},
					failure: function(form, action) {
						if( !action.result ) return;
						Ext.MessageBox.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
						statusBarMessage( action.result.error, false, false );
					},
					scope: form,
					// add some vars to the request, similar to hidden fields
					params: {
						option: "com_extplorer", 
						action: "upload", 
						dir: datastore.currentDir,
						requestType: "xmlhttprequest",
						confirm: "true"
					}
				});
			}
		}, {
			text: "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
			action: "cancel" 
		}]
	},
	{
	
		xtype: "form",
		id: "transferform",
		url:"index.php",
		title: "<?php echo ext_Lang::msg('acttransfer') ?>",
		autoHeight: true,
		frame: true,
		padding: 10,
		labelWidth: 225,
		items: [
		<?php
			for($i=0;$i<7;$i++) {
				echo '{
					xtype: "textfield",
					fieldLabel: "'.ext_Lang::msg('url_to_file', true ).'",
					name: "userfile['.$i.']",
					width: 300
				},';
			}
			?>
			{	xtype: "checkbox",
				fieldLabel: "<?php echo ext_Lang::msg('overwrite_files', true ) ?>",
				name: "overwrite_files",
				value: true
			}
		],
		buttons: [{
	
			text: "<?php echo ext_Lang::msg( 'btnsave', true ) ?>", 
			handler: function() {
				statusBarMessage( '<?php echo ext_Lang::msg( 'transfer_processing', true ) ?>', true );
				transfer = this.up("form").getForm();
				transfer.submit({
					//reset: true,
					reset: false,
					success: function(form, action) {
						datastore.load();
						statusBarMessage( action.result.message, false, true );
					},
					failure: function(form, action) {
						if( !action.result ) return;
						Ext.MessageBox.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
						statusBarMessage( action.result.error, false, false );
					},
					scope: transfer,
					// add some vars to the request, similar to hidden fields
					params: {
						option: "com_extplorer", 
						action: "transfer", 
						dir: datastore.currentDir,
						confirm: 'true'
					}
				});
			}
		},{
			text: "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
			action: "cancel"
		}]
	}]

});