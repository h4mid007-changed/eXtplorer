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
Ext.define( 'eXtplorer.view.forms.Rename', {
	extend: 'Ext.form.Panel',
	initComponent: function() {
		this.callParent();
		this.getForm().findField("newitemname").setValue(this.selectedRecords[0].get("name"));
	},

	width: 350,
	height: 150,
	
	labelWidth: 125,
	url:"<?php echo basename( $GLOBALS['script_name']) ?>",
	title: "<?php echo $GLOBALS['messages']['rename_file'] ?>",
	
	items: [{
	
		xtype: "textfield",
		fieldLabel: "<?php echo ext_Lang::msg( 'newname', true ) ?>",
		name: "newitemname",
		id: "newitemname",
		value: "",
		width:275,
		margin: 10,
		allowBlank:false
	},
	{
		xtype: "hiddenfield",
		name: "confirm",
		value: "true"
	}],
	listeners: { 
		"show": { 
			fn: function( form ) {
					form.getForm().findField("newitemname").focus(true);
				}
		}
	},
	buttons: [{
		text: "<?php echo ext_Lang::msg( 'btnsave', true ) ?>", 
		action: "save"
	},{
		text: "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
		action: "cancel" 
	}]

});