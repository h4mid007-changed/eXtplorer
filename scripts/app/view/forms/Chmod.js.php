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
Ext.define( 'eXtplorer.view.forms.Chmod', {
	extend: 'Ext.form.Panel',
	initComponent: function() {
		this.callParent();
		perms = this.selectedRecords[0].get("perms").substr( 5, 9 );
		var mapping = new Object();
		mapping[0] = "00";mapping[1] = "01";mapping[2] = "02";mapping[3] = "10";mapping[4] = "11";mapping[5] = "12";mapping[6] = "20";mapping[7] = "21";mapping[8] = "22";
		
		for( var i = 0; i < perms.length; i++ ) {
			if( perms.charAt(i) != "-" ) {
				this.getForm().findField("r_" + mapping[i] ).setValue(true);
			}
		}
	},
	labelWidth: 125,
	width: 330,
	height: 300,
	url:"<?php echo basename( $GLOBALS['script_name']) ?>",
	title: "<?php echo ext_Lang::msg('actperms') ?>",
	items: [{
		layout: "column",
		frame: true,
		padding: 10,
			items: [{
	<?php
		$pos = "rwx";
		// print table with current perms & checkboxes to change
		for($i=0;$i<3;++$i) {
			?>
			width:80, 
			title:"<?php echo ext_Lang::msg(array('miscchmod'=> $i ), true ) ?>",					
			items: [{
				<?php
				for($j=0;$j<3;++$j) {
					?>
					xtype: "checkbox",
					boxLabel:"&nbsp;<?php echo $pos{$j}  ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
					
					name:"<?php echo "r_". $i.$j ?>"
					}	<?php
					if( $j<2 ) echo ',{';
				}
				?>	
				]
			}
		<?php 
			if( $i<2 ) echo ',{';
		}
		?>]

	},{
		xtype: "checkbox",
		fieldLabel:"<?php echo ext_Lang::msg('recurse_subdirs', true ) ?>",
		name:"do_recurse"
	},
	{
		xtype: "hiddenfield",
		value: "true",
		name: "confirm"
	}],
	buttons: [{
		text: "<?php echo ext_Lang::msg( 'btnsave', true ) ?>", 
		action: "save",
	},{
		text: "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
		action: "cancel"
	}]
	}
);