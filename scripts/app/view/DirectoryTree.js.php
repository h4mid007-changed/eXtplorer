 Ext.define('eXtplorer.view.DirectoryTree', {
    extend: 'Ext.tree.Panel',
	alias: "widget.dirtree",
	id: 'dirTree',
	stateId: 'dirTreeCache',
    stores: ['File', 'DirectoryTree'],
	viewConfig: {
        plugins: {
            ptype: 'treeviewdragdrop',
            ddGroup: "FileGrid"
        }
    },
    initComponent: function() {
        Ext.apply(this, {
			layout: { margin: 0 },
			selModel: { ignoreRightMouseSelection: false },
			useArrows: true,
			store: "DirectoryTree",
			title: '<?php echo ext_Lang::msg('directory_tree', true ) ?> <img src="<?php echo _EXT_URL ?>/images/_reload.png" hspace="20" style="cursor:pointer;" title="reload" onclick="Ext.getCmp(\'dirTree\').getStore().load();" alt="Reload" align="middle" />', 
			closable: false,
			width: 230,
			titlebar: true,
			autoScroll:true,
			animate:true, 
			containerScroll: true,
    		// If you want the root node to be visible, change this to true
    		rootVisible: true
   	
    	});
    	this.callParent(arguments);
    }
});