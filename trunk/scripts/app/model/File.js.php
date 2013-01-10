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
        {name: "name", type: "string"},
        {name: "size", type: "string"},
        {name: "type", type: "string"},
        {name: "modified", type: "string"},
        {name: "perms", type: "string"},
        {name: "icon", type: "string"},
        {name: "owner", type: "string"},
        {name: "is_deletable", type: "boolean"},
        {name: "is_file", type: "boolean"},
        {name: "is_archive", type: "boolean"},
        {name: "is_writable", type: "boolean"},
        {name: "is_chmodable", type: "boolean"},
        {name: "is_readable", type: "boolean"},
        {name: "is_deletable", type: "boolean"},
        {name: "is_editable", type: "boolean"}
    ],
	paramNames: [ { 
		dir: "direction",
		sort: "order"
	}],

					 
});