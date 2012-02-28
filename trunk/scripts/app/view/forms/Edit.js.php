<?php
if( !defined( '_JEXEC' )) {
	$_REQUEST['action'] = 'include_javascript';
	$_GET['subdir'][] = 'app/view/forms';
	$_GET['file'][] = str_replace('.php', '', basename(__FILE__) );
	include('../../../../index.php');
}
include( '../../../codemirror/lib/codemirror.js' );

include( '../../../extjs-ux/codemirror/Ext.ux.form.field.CodeMirror.js');
include( '../../../codemirror/lib/util/dialog.js' );
include( '../../../codemirror/lib/util/foldcode.js' );
include( '../../../codemirror/lib/util/search.js' );
include( '../../../codemirror/lib/util/searchcursor.js' );
?>
Ext.define( 'eXtplorer.view.forms.Edit', {
	extend: 'Ext.form.Panel',
	labelWidth: 300,
	autoScroll: true,
	
	initComponent: function() {
		this.callParent();
		this.getForm().findField('fname').setValue( this.filename );
		this.getForm().findField('item').setValue( this.filename );
		this.getForm().findField('dir').setValue( this.dir );
		
	},
	listeners: {
		render: { fn:
			function() {
				var codefield = this.getForm().findField('code');
				codefield.setValue( this.taContent );
				codefield.setWidth( this.taWidth );		
				codefield.setMode( "text/x-" + this.cp_lang );
				
				codefield.setMode( "application/" + this.cp_lang );
				codefield.setMode( "text/" + this.cp_lang );
			
			}
		}
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
		xtype:      'codemirror',
        pathModes:  '<?php echo _EXT_URL ?>/scripts/codemirror/mode',
        pathExtensions: '<?php echo _EXT_URL ?>/scripts/codemirror/lib/util',
        name:       'code',
        fieldLabel: 'Code',
        hideLabel:  true,
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