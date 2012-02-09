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
				beforenodedrop: { fn: function(e){
										dropEvent = e;
										this.onCopyMoveCtx(e);
									}
				},
				beforemove: { fn: function() { return false; } },
				selectionchange: this.onDirSelect
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
				click: function(item, e) { this.getDirectoryTreeStore().load({ node: dirCtxMenu.clickedNode, params: { node: dirCtxMenu.clickedNode.id }}) }
			},
			'dirctx menuitem[action=delete]': {
				click: function(item, e) { 
					Ext.Msg.confirm('Confirm', String.format("<?php echo $GLOBALS['error_msg']['miscdelitems'] ?>", num ), 
						function(btn) { deleteDir( btn, dirCtxMenu.node ) }
					); 
				}
			},
			'dirctx menuitem[action=hide]': {
				click: function(item, e) { eXtplorer.view.dirCtxMenu.hide() }
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
		dirCtxMenu = eXtplorer.view.dirCtxMenu;
		dirCtxMenu.clickedNode = this.getDirTree().getRootNode().findChild("id", record.get("id"), true );
		dirCtxMenu.items.get('dirCtxMenu_rename')[record.get('is_deletable') ? 'enable' : 'disable']();
		dirCtxMenu.items.get('dirCtxMenu_remove')[record.get('is_deletable') ? 'enable' : 'disable']();
		dirCtxMenu.items.get('dirCtxMenu_chmod')[record.get('is_chmodable') ? 'enable' : 'disable']();
		
		dirCtxMenu.showBy(el, 'tl-b' );
		
	},
	onCopyMove: function ( action ) {
	    var s = dropEvent.data.selections, r = [];
		if( s ) {
			// Dragged from the Grid
			requestParams = getRequestParams();
			requestParams.new_dir = dropEvent.target.id.replace( /_RRR_/g, '/' );
			requestParams.new_dir = requestParams.new_dir.replace( /ext_root/g, '' );
			requestParams.confirm = 'true';
			requestParams.action = action;
			handleCallback(requestParams);
		} else {
			// Dragged from inside the tree
			//alert('Move ' + dropEvent.data.node.id.replace( /_RRR_/g, '/' )+' to '+ dropEvent.target.id.replace( /_RRR_/g, '/' ));
			requestParams = getRequestParams();
			requestParams.dir = datastore.directory.substring( 0, datastore.directory.lastIndexOf('/'));
			requestParams.new_dir = dropEvent.target.id.replace( /_RRR_/g, '/' );
			requestParams.new_dir = requestParams.new_dir.replace( /ext_root/g, '' );
			requestParams.selitems = Array( dropEvent.data.node.id.replace( /_RRR_/g, '/' ) );
			requestParams.confirm = 'true';
			requestParams.action = action;
			handleCallback(requestParams);
		}
	},

    onCopyMoveCtx: function (e){
        //ctxMenu.items.get('remove')[node.attributes.allowDelete ? 'enable' : 'disable']();
        copyMoveCtxMenu.showAt(e.rawEvent.getXY());
    },
	expandTreeToDir: function( dir ) {
		dir = dir ? dir : new String('<?php echo str_replace("'", "\'", extGetParam( $_SESSION,'ext_'.$GLOBALS['file_mode'].'dir', '' )) ?>');
		this.getDirTree().selectPath(dir);
	}
	
});