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
Ext.define( 'eXtplorer.view.forms.Copymove', {
	extend: 'Ext.form.Panel',
	xtype: "form",
	labelWidth: 125,
	title: "<?php echo ext_lang::msg('copylink').'/'.ext_lang::msg('movelink') ?>",
	width: "340",
	url:"<?php echo basename( $GLOBALS['script_name']) ?>",
	frame: true,
	initComponent: function() {
		this.callParent();
		if( this.new_dir )
			this.getForm().findField('new_dir').setValue( this.new_dir );
		this.getForm().findField('action').setValue( this.subaction );
	},
	items: [{
		xtype: "textfield",
        fieldLabel: "Destination",
        name: "new_dir",
        value: "",
        width:350,
        allowBlank:false
    },{
		name: "action", value: "copy", xtype: "hiddenfield"		
	}],
    buttons: [{
    	text: '<?php echo ext_Lang::msg( 'btncreate', true ) ?>', 
    	action: "save"
	},{
		text: '<?php echo ext_Lang::msg( 'btncancel', true ) ?>', 
		action: "cancel"
	}
	]
});