Ext.define('eXtplorer.store.File', {
    extend: 'Ext.data.Store',
    model: 'eXtplorer.model.File',
	pageSize: 100,
    // turn on remote sorting
    remoteSort: true,
    currentDir: ""
});