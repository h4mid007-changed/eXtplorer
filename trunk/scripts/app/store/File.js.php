Ext.define('eXtplorer.store.File', {
    extend: 'Ext.data.Store',
    model: 'eXtplorer.model.File',
	pageSize: 100,
    // turn on remote sorting
    remoteSort: true,
    currentDir: "",
    listeners: {
    	beforeload: {
    		fn: function( store, operation, opts ) {
    			if( operation.params && operation.params.dir ) {
    			 	this.currentDir = operation.params.dir
    			} else {
    				operation.params = {
    					dir: this.currentDir
    				}
    			}
    		},
    		scope: this
    	}
    }
});