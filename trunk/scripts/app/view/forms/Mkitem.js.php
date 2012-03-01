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
Ext.define( 'eXtplorer.view.forms.Mkitem', {
	extend: 'Ext.form.Panel',
	labelWidth: 125,
	title: "<?php echo ext_Lang::msg('newlink') ?>",
	url:"<?php echo basename( $GLOBALS['script_name']) ?>",
	frame: true,
	items: [{
		xtype: "textfield",
		fieldLabel: "<?php echo ext_Lang::msg( "nameheader", true ) ?>",
		name: "mkname",
		width:250,
		allowBlank:false
	},{
		xtype: "combo",
		fieldLabel: "Type",
		store: [["file", "<?php echo ext_Lang::mime( 'file', true ) ?>"],
			["dir", "<?php echo ext_Lang::mime( 'dir', true ) ?>"]
			<?php
			if( !ext_isFTPMode() && !$GLOBALS['isWindows']) { ?>
			,["symlink", "<?php echo ext_Lang::mime( 'symlink', true ) ?>"]
			<?php
			} ?>
		],
		displayField:"type",
		valueField: "mktype",
		value: "file",
		name: "mktype",
		disableKeyFilter: true,
		editable: false,
		triggerAction: "all",
		mode: "local",
		allowBlank: false,
		selectOnFocus:true
	},{
		xtype: "textfield",
		fieldLabel: "<?php echo ext_Lang::msg( 'symlink_target', true ) ?>",
		name: "symlink_target",
		width:250,
		allowBlank:true
	},
	{
		xtype: "hiddenfield",
		value: "mkitem",
		name: "action"
	},
	{
		xtype: "hiddenfield",
		value: "true",
		name: "confirm"
	}, {
		xtype: 'hiddenfield',
		name: 'option',
		value: "com_extplorer"
	}],
	buttons: [{
		text: "<?php echo ext_Lang::msg( 'btncreate', true ) ?>", 
		action: "save",
	},{
		text: "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
		action: "cancel"
	}]
	}
);