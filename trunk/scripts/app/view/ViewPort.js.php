Ext.define('eXtplorer.view.Viewport', {
    extend: 'Ext.container.Viewport',
	requires: [
        'eXtplorer.view.FileList',
        'eXtplorer.view.DirectoryTree',
        'eXtplorer.view.Header',
    ],
	layout:'border',
	items: [
			{
				region:"north",
				xtype: "header",
				height: 50,
			},{
				xtype: "dirtree",
				region: "west"
				
			},{
					
				region: "center",
				xtype: "tabpanel",
				itemId: "mainpanel",
				enableTabScroll: true,
				activeTab: 0,
				layout: {
		            type: 'border',
		            padding: 5
       			},
				items: [{
					xtype: "filelist",
					region: "center"
					
				}]
			}]

	
});