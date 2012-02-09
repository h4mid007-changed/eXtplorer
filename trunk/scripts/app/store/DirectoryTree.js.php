Ext.define('eXtplorer.store.DirectoryTree', {
    extend: 'Ext.data.TreeStore',
    model: 'eXtplorer.model.Directory',
	//autoLoad: true,
    // Overriding the model's default proxy
    proxy: {
		type: "ajax",
        url: "<?php echo $GLOBALS['script_name'] ?>",
        extraParams:{ option:"com_extplorer", action:"getdircontents", sendWhat: "dirs" },
		reader: {
			type: "json",
			root: "items",
			totalProperty: "totalCount"
		}
    },
   	root: {
        text: ' &#8260; ',
        expanded: true,
    }
});