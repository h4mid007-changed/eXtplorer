Ext.define('eXtplorer.controller.Directory', {
	stores: ['DirectoryTree'],
    extend: 'Ext.app.Controller',
    refs: [{
        ref: 'filesList',
        selector: 'filelist'
    },{
        ref: 'dirTree',
        selector: 'dirtree'
    }, {
    	ref: 'dirCtx',
    	selector: 'dirctx'
    }, {
        ref: 'fileTab',
        xtype: 'filelist',
        closable: true,
        forceCreate: true,
        selector: 'filelist'
    }],
    
    init: function() {
    	var fileController = this.application.getController('File');
		this.control({
			'dirtree': {
				dirchange: { fn: function(selection) { this.loadDirectory(selection); } }, 
				itemcontextmenu: { fn: this.onDirContext },
				textchange: { fn: function(node, text, oldText) {
						if( text == oldText ) return true;
						var requestParams = getRequestParams();
						var dir = node.parentNode.id.replace( /_RRR_/g, '/' );
						if( dir == 'ext_root' ) dir = '';
						requestParams.dir = dir;
						requestParams.newitemname = text;
						requestParams.item = oldText;
						
						requestParams.confirm = 'true';
						requestParams.action = 'rename';
						handleCallback(requestParams);
						ext_itemgrid.stopEditing();
						return true;
					}	
				},
				selectionchange: this.onDirSelect
			},
			'dirtree treeview': {
			
	            drop: function(node, data, dropRec, dropPosition) {
	               var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('name') : ' on empty view';
	               console.log("Drag from right to left", 'Dropped ' + data.records[0].get('name') + dropOn);
	        	},
	               
				beforedrop: { fn: 
					function(node,data,overModel) {
						this.onCopyMoveCtx(node,data,overModel);
						return false;
					}
				}
			},
			'dirctx menuitem[action=mkitem]': {
				click: function(item, e) { fileController.loadForm(e, 'mkitem') }
			},
			'dirctx menuitem[action=rename]': {
				click: function(item, e) { fileController.loadForm(e, 'rename') }
			},
			'dirctx menuitem[action=copy]': {
				click: function(item, e) { fileController.loadForm(e, 'copy') }
			},
			'dirctx menuitem[action=move]': {
				click: function(item, e) { fileController.loadForm(e, 'move') }
			},
			'dirctx menuitem[action=chmod]': {
				click: function(item, e) { fileController.loadForm(e, 'chmod') }
			},
			'dirctx menuitem[action=archive]': {
				click: function(item, e) { fileController.loadForm(e, 'archive') }
			},
			'dirctx menuitem[action=reload]': {
				click: function(item, e) { this.getDirectoryTreeStore().load({ node: eXtplorer.view.dirCtxMenu.clickedNode, params: { node: eXtplorer.view.dirCtxMenu.clickedNode.id }}) }
			},
			'dirctx menuitem[action=delete]': {
				click: function(item, e) { 
					Ext.Msg.confirm('Confirm', Ext.String.format("<?php echo $GLOBALS['error_msg']['miscdelitems'] ?>", 1 ), 
						function(btn) { this.deleteDir( btn, eXtplorer.view.dirCtxMenu.node ) }, this
					); 
				}
			},
			'dirctx menuitem[action=hide]': {
				click: function(item, e) { eXtplorer.view.dirCtxMenu.hide() }
			},
			'copymovectx menuitem[action=copy]': {
				click: function(item, e) { this.onCopyMove('copy') }
			},
			'copymovectx menuitem[action=move]': {
				click: function(item, e) { this.onCopyMove('move') }
			},
			'copymovectx menuitem[action=hide]': {
				click: function(item, e) { eXtplorer.view.CopyMoveCtxMenu.hide() }
			}
		});
		
		// We listen for the application-wide stationstart event
		this.application.on({
			dirchange: this.loadDirectory,
			scope: this
		});
	},
	loadDirectory: function(selection) {
	
		if(  selection.getPath ) {
			this.getDirTree().expandPath( selection.getPath() ); 
		} else {
			if( selection == 'root') {
				selection = '';
			}
			this.getDirTree().expandPath( '/ &#8260; ' + selection, 'text' ) ;
		}
		
	    
	},
	
	onDirSelect: function( selModel, selection ) {
		this.application.fireEvent('dirchange', selection[0] );
	
    },
    onDirContext: function(dirTreePanel, record, el, index, e ) {
		e.preventDefault();
		// Unselect all files in the grid
		this.getFilesList().getSelectionModel().deselectAll();
		var dirCtxMenu = eXtplorer.view.dirCtxMenu;
		dirCtxMenu.clickedNode = this.getDirTree().getRootNode().findChild("id", record.get("id"), true );
		dirCtxMenu.items.get('dirCtxMenu_rename')[record.get('is_deletable') ? 'enable' : 'disable']();
		dirCtxMenu.items.get('dirCtxMenu_remove')[record.get('is_deletable') ? 'enable' : 'disable']();
		dirCtxMenu.items.get('dirCtxMenu_chmod')[record.get('is_chmodable') ? 'enable' : 'disable']();
		
		dirCtxMenu.showBy(el, 'tl-b' );
		
	},
	onCopyMove: function ( action ) {
	    var ctxMenu = eXtplorer.view.CopyMoveCtxMenu;
	    
		if( ctxMenu.data.records[0].get("name") != "" ) {
			// Dragged from the Grid
			console.log(Ext.String.camelize( action )+' ' + ctxMenu.data.records[0].get("name").replace( /_RRR_/g, '/' )
						+' to '+ ctxMenu.target.get("id").replace( /_RRR_/g, '/' ));
			requestParams = this.application.getController('File').getRequestParams();
			requestParams.new_dir = ctxMenu.target.get("id").replace( /_RRR_/g, '/' );
			requestParams.new_dir = requestParams.new_dir.replace( /ext_root/g, '' );
			requestParams.confirm = 'true';
			requestParams.action = action;
			this.application.getController('File').handleCallback(requestParams, ctxMenu.targetNode);
		} else {
			
			// Dragged from inside the tree
			console.log(Ext.String.camelize( action )+' ' + ctxMenu.data.records[0].get("id").replace( /_RRR_/g, '/' )
						+' to '+ ctxMenu.target.get("id").replace( /_RRR_/g, '/' ));
			requestParams = this.application.getController('File').getRequestParams();
			requestParams.dir = this.application.getController('File').currentDir.substring( 0, this.application.getController('File').currentDir.lastIndexOf('/'));
			requestParams.new_dir = ctxMenu.target.get("id").replace( /_RRR_/g, '/' );
			requestParams.new_dir = requestParams.new_dir.replace( /ext_root/g, '' );
			requestParams.selitems = Array(ctxMenu.data.records[0].get("id").replace( /_RRR_/g, '/' ) );
			requestParams.confirm = 'true';
			requestParams.action = action;
			this.application.getController('File').handleCallback(requestParams, ctxMenu.targetNode, ctxMenu.originNode.parentNode);
		}
	},

    onCopyMoveCtx: function (node,data,target){
        //ctxMenu.items.get('remove')[node.attributes.allowDelete ? 'enable' : 'disable']();
        var ctxMenu = eXtplorer.view.CopyMoveCtxMenu;
        ctxMenu.node = node;
        ctxMenu.data = data;
        ctxMenu.target = target;
        ctxMenu.targetNode = this.getDirTree().getRootNode().findChild("id", target.get("id"), true );
        ctxMenu.originNode = this.getDirTree().getRootNode().findChild("id", data.records[0].get("id"), true );
        ctxMenu.showBy(node, 'tl-b');
    },
	expandTreeToDir: function( dir ) {
		dir = dir ? dir : new String('<?php echo str_replace("'", "\'", extGetParam( $_SESSION,'ext_'.$GLOBALS['file_mode'].'dir', '' )) ?>');
		this.getDirTree().selectPath(dir);
	},
	
	deleteDir: function( btn, node ) {
		if( btn != 'yes') {
			return;
		}
		var dirCtxMenu = eXtplorer.view.dirCtxMenu;
		requestParams = this.application.getController('File').getRequestParams();
		requestParams.dir = this.application.getController('File').currentDir.substring( 0, this.application.getController('File').currentDir.lastIndexOf('/'));
		requestParams.selitems = Array( dirCtxMenu.clickedNode.id.replace( /_RRR_/g, '/' ) );
		requestParams.action = 'delete';
		this.application.getController('File').handleCallback(requestParams, dirCtxMenu.clickedNode.parentNode);
	}
	
});