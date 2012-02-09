<?php
if( !defined( '_JEXEC' )) {
	$_REQUEST['action'] = 'include_javascript';
	$_GET['subdir'][] = 'app/view/forms';
	$_GET['file'][] = str_replace('.php', '', basename(__FILE__) );
	include('../../../../index.php');
}
?>
Ext.define( 'eXtplorer.view.forms.Edit', {
	extend: 'Ext.form.Panel',
	labelWidth: 300,
	autoScroll: "true",
	url:"<?php echo basename( $GLOBALS['script_name']) ?>",
	
	initComponent: function() {
		this.callParent();

		this.getForm().findField('code').setValue( this.taContent );
		this.getForm().findField('code').setWidth( this.taWidth );		
		this.getForm().findField('fname').setValue( this.filename );
		this.getForm().findField('item').setValue( this.filename );
		this.getForm().findField('dir').setValue( this.dir );
	},
	frame: true,
	closable: true,
	tbar: [{
		text: "<?php echo ext_Lang::msg('btnsave', true ) ?>",
		action: "save",
		cls:"x-btn-text-icon",
		icon: "<?php echo _EXT_URL ?>/images/_save.png"
	},{
		text: "<?php echo ext_Lang::msg('btnreopen', true ) ?>",
		action: 'reload',
		cls:"x-btn-text-icon",
		icon: "<?php echo _EXT_URL ?>/images/_reload.png"
	},
	{
		text: "<?php echo ext_Lang::msg('btncancel', true ) ?>",
		action: "cancel",
		cls:"x-btn-text-icon",
		icon: "<?php echo _EXT_URL ?>/images/_cancel.png"
	}],
	items: [{
		xtype: "displayfield",
		value: "<?php echo $GLOBALS["messages"]["actedit"] ?>"
	},
	{
		xtype: "textarea",
		hideLabel: true,
		name: "code",
		fieldClass: "x-form-field",

		height: 500,
		/*plugins: Ext.create( "Ext.ux.EditAreaEditor", {
			id : "ext_codefield" + this.id,
			syntax: this.cp_lang,
			start_highlight: true,
			display: "later",
			toolbar: "search, go_to_line, |, undo, redo, |, select_font,|, change_smooth_selection, highlight, reset_highlight, |, help",
			language: this.language 
		})*/
	},
	{
		
		width: 250, 
		xtype: "textfield",
		fieldLabel: "<?php echo ext_Lang::msg('copyfile', true ) ?>",
		name: "fname",
		clear: true
	},{
		xtype: 'hiddenfield',
		name: 'item'
	}, {
		xtype: 'hiddenfield',
		name: 'dir'
	}, {
		xtype: 'hiddenfield',
		name: 'action',
		value: "edit"
	}, {
		xtype: 'hiddenfield',
		name: 'option',
		value: "com_extplorer"
	}
	]

});