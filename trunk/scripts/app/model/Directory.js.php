Ext.define('eXtplorer.model.Directory', {
    extend: 'eXtplorer.model.File',
    fields: [
        {name: "text", type: "string"},
        {name: "cls", type: "string"},
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
});