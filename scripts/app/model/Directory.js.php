Ext.define('eXtplorer.model.Directory', {
    extend: 'eXtplorer.model.File',
    fields: [
        {name: "text"},
        {name: "cls"},
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
});