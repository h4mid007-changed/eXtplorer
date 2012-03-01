<?php
if( !defined( '_JEXEC' )) {
	$_REQUEST['action'] = 'include_javascript';
	$_REQUEST['option'] = 'com_extplorer';
	$_GET['subdir'][] = 'app/view/forms';
	$_GET['file'][] = str_replace('.php', '', basename(__FILE__) );
	if( strstr( __FILE__, 'com_extplorer')) {
		include('../../../../../../index.php');
	}else {
		include('../../../../index.php');
	}
}
?>	
Ext.define( 'eXtplorer.view.forms.Diff', {
	extend: 'Ext.form.Panel',
	initComponent: function() {
		this.callParent();
		this.getForm().findField("item2").setValue( this.dir );
		this.title = "Diff " + this.selectedRecords[0].get("name");
		if( this.selectedRecords[1] ) {
			this.title += " and " + this.selectedRecords[1].get("name");
			this.getForm().findField("item2").setValue( this.dir + "/" + this.selectedRecords[1].get("name") );
		}
	},
	width: 300,
	autoScroll: true,
	labelWidth: 125,
	url:"<?php echo basename( $GLOBALS['script_name']) ?>",
	items: [{
		xtype: "textfield",
		fieldLabel: 'File to Compare',
		name: 'item2',
		value: "",
		width:275,
		allowBlank:false
	},{
		xtype: "hiddenfield",
		name: "confirm",
		value: "true"
	}],
    buttons: [{
		text: "<?php echo ext_Lang::msg( 'btndiff', true ) ?>", 
		handler: function() {
			statusBarMessage( 'Please wait...', true );
			var panel = this.up("form");
			var form = panel.getForm();
			
			form.submit({
				success: function(form, action) {
					panel.update( action.result.html );
					panel.setWidth( 700 );
					panel.setHeight( 500 );
					panel.center();
				},
				failure: function(form, action) {
					if( !action.result ) return;
					Ext.MessageBox.alert('Error!', action.result.error);
					statusBarMessage( action.result.error, false, true );
				},
				scope: this,
				// add some vars to the request, similar to hidden fields
				params: {
					option: "com_extplorer", 
					action: "diff", 
					dir: panel.dir, 
					item: panel.selectedRecords[0].get("name"),
					selitems: new Array( panel.selectedRecords[0].get("name") ), 
					confirm: 'true'
				}
			});
		}
	},{
		text: "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
		action: "cancel"
	}]

});