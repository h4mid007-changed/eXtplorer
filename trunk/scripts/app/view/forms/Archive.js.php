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
$default_archive_type = 'zip';
?>	
Ext.define( 'eXtplorer.view.forms.Archive', {
	extend: 'Ext.form.Panel',
	initComponent: function() {
		this.callParent();
		this.getForm().findField("name").setValue( this.selectedRecords[0].get("name") + ".<?php echo $default_archive_type ?>");
	},
	autoHeight: true,
	width: 350,
	labelWidth: 125,
	url:"index.php",
	title: "<?php echo $GLOBALS["messages"]["actarchive"] ?>",
	frame: true,
	padding: 10,
	items: [{
		xtype: "textfield",
		fieldLabel: "<?php echo ext_Lang::msg('archive_name', true ) ?>",
		name: "name",
		value: "",
		width: 300
	},
	{
		xtype: "combo",
		fieldLabel: "<?php echo ext_Lang::msg('typeheader', true ) ?>",
		store: [
				['zip', 'Zip (<?php echo ext_Lang::msg('normal_compression', true ) ?>)'],
				['tgz', 'Tar/Gz (<?php echo ext_Lang::msg('good_compression', true ) ?>)'],
				<?php
				if(extension_loaded("bz2")) {
					echo "['tbz', 'Tar/Bzip2 (".ext_Lang::msg('best_compression', true ).")'],";
				}
				?>
				['tar', 'Tar (<?php echo ext_Lang::msg('no_compression', true ) ?>)']
				],
		displayField:"typename",
		valueField: "type",
		name: "type",
		value: "<?php echo $default_archive_type ?>",
		triggerAction: "all",
		name: "type",
		disableKeyFilter: true,
		editable: false,
		mode: "local",
		allowBlank: false,
		selectOnFocus:true,
		width: 300,
		listeners: { 
			select: { 
				fn: function(o, record ) {
					form = this.up("form").getForm();
					var nameField = form.findField("name").getValue();								
					if( nameField.indexOf( '.' ) > -1 ) {
						form.findField('name').setValue( nameField.substring( 0, nameField.indexOf('.')+1 ) + o.getValue() );
					} else {
						form.findField('name').setValue( nameField + '.'+ o.getValue());
					}
				}
			  }
		}
	
	}, {
		xtype: "textfield",
		fieldLabel: "<?php echo ext_Lang::msg('archive_saveToDir', true ) ?>",
		name: "saveToDir",
		value: "<?php echo str_replace("'", "\'", $dir ) ?>",
		width: 300
	},{
		xtype: "checkbox",
		fieldLabel: "<?php echo ext_Lang::msg('downlink', true ) ?>?",
		name: "download",
		checked: true
	}
	],
	buttons: [{
		text: "<?php echo ext_Lang::msg( 'btncreate', true ) ?>", 
		action: "start_archiving"
	},{
		text: "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
		action: "cancel"
	}]
});