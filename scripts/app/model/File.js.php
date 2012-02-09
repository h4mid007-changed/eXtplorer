Ext.define('eXtplorer.model.File', {
    extend: 'Ext.data.Model',
    proxy: {
		type: "ajax",
        url: "<?php echo $GLOBALS['script_name'] ?>",
        extraParams:{ option:"com_extplorer", action:"getdircontents" },
		reader: {
			type: "json",
			root: "items",
			totalProperty: "totalCount"
		}
    },
    // create reader that reads the File records
    fields: [
        {name: "name"},
        {name: "size"},
        {name: "type"},
        {name: "modified"},
        {name: "perms"},
        {name: "icon"},
        {name: "owner"},
        {name: "is_deletable"},
        {name: "is_file"},
        {name: "is_archive"},
        {name: "is_writable"},
        {name: "is_chmodable"},
        {name: "is_readable"},
        {name: "is_deletable"},
        {name: "is_editable"}
    ],
	paramNames: [ { 
		dir: "direction",
		sort: "order"
	}],

					 
});