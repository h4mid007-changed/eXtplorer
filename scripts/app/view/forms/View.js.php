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
Ext.define( 'eXtplorer.view.forms.View', {
	extend: 'Ext.panel.Panel',
	autoScroll: true,
	height: 500,
	width: 700,
	initComponent: function() {
		
		this.callParent();
		Ext.apply( this, { 
			title: "<?php echo $GLOBALS['messages']['actview'].": " ?>" + this.selectedRecords[0].get('name'),
			loader:  {
				url: 'index.php',
				params: {
					option: 'com_extplorer',
					action: 'view',
					dir: this.dir,
					item: this.selectedRecords[0].get('name')
				},
                contentType: 'html',
                loadMask: true,
                method: 'GET'
			}
		});
		this.getLoader().load();
	}
});