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
Ext.define( 'eXtplorer.view.forms.Changepw', {
	extend: 'Ext.form.Panel',
	title: '<?php echo ext_Lang::msg( 'actchpwd', true ) ?>',
	frame: true,
	listeners: {
		show: { fn: function() {
		this.getForm().findField("newpwd1").focus();
		},
		scope: this
		}
	},
	items: [{
		xtype: "textfield",
		fieldLabel: "<?php echo ext_Lang::msg( 'miscoldpass', true ) ?>",
		name: "oldpwd",
		inputType: "password",
		<?php if($GLOBALS['mainframe']->getUserName() == 'admin' && ($GLOBALS['mainframe']->getPassword() == extEncodePassword('admin') || $GLOBALS['mainframe']->getPassword() == md5('admin'))) {
			echo 'value: "admin",';
		} ?>
		width:255
	},{
		xtype: "textfield",
		fieldLabel: "<?php echo ext_Lang::msg( 'miscconfpass', true ) ?>",
		name: "newpwd1",
		inputType: "password",
		width:255
	},
	{	xtype: "textfield",
		fieldLabel: "<?php echo ext_Lang::msg( 'miscconfnewpass', true ) ?>",
		name: "newpwd2",
		inputType: "password",
		width:255,
        validator: function(value) {
            var password1 = this.previousSibling('[name=newpwd1]');
            return (value === password1.getValue()) ? true : '<?php echo ext_Lang::err('miscnopassmatch',true) ?>'
        }
	},
	{
		xtype: "hiddenfield",
		name: "action",
		value: "users"
	},
	{
		xtype: "hiddenfield",
		name: "action2",
		value: "chpwd"
	}
	],
	buttons: [{
		text: "<?php echo ext_Lang::msg( 'btnsave', true ) ?>", 
		action: "save",
	},{
		text: "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
		action: "cancel"
	}]
});