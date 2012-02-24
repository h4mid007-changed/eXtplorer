Ext.define('eXtplorer.controller.File', {
    extend: 'Ext.app.Controller',
	stores: ['File'],
	views: ['FileList', 'DirectoryTree', 'gridCtxMenu', 'dirCtxMenu', 'CopyMoveCtxMenu' ],
	refs: [{
        ref: 'filesList',
        selector: 'filelist'
    },{
        ref: 'dirTree',
        selector: 'dirtree'
    }, {
    	ref: 'gridCtx',
    	selector: 'gridctx'
    }, {
    	ref: 'dirCtx',
    	selector: 'dirctx'
    }],
	sendWhat: 'files',
    currentDir: '',
    init: function() {
		this.control({
			'filelist': {
				itemcontextmenu: { fn: this.onRowContextMenu },
				selectionchange: { fn: this.onHandleRowClick 	},
				itemdblclick: { fn: function( grid, record, el, rowIndex, e ) { 
					if( Ext.isOpera ) { 
						// because Opera versions 9 and lower doesn't support the right-mouse-button-clicked event (contextmenu)
						// we need to simulate it using the ondblclick event
						rowContextMenu( grid, rowIndex, e );
					} else {
						if( !record.get('is_file') ) {
							this.application.fireEvent( "dirchange" , this.currentDir + '/' + record.get('name') );
						} else if( record.get('is_editable')) {
							this.loadForm( this, 'edit' );
						} else if( record.get('is_readable')) {
							this.loadForm( this, 'view' );
						}
					}
				  }
				},
				validateedit: { fn: function(e) {
					if( e.value == e.originalValue ) return true;
					var requestParams = getRequestParams();
					requestParams.newitemname = e.value;
					requestParams.item = e.originalValue;
					
					requestParams.confirm = 'true';
					requestParams.action = 'rename';
					this.handleCallback(requestParams);
					return true;
					}	
				}
			},
			'filelist button[action=home]': {
				click: function() { this.application.fireEvent('dirchange', 'root') }
			},
			'filelist button[action=reload]': {
				click: function() { this.onLoadFileList() }
			},
			'filelist button[action=search]': {
				click: function(item, e) { this.loadForm(e, 'search'); } 
			},
			'filelist button[action=mkitem]': {
				click: function(item, e) { this.loadForm(e, 'mkitem'); } 
			},
			'filelist button[action=edit]': {
				click: function(item, e) { this.loadForm(e, 'edit'); }
			},
			'filelist button[action=copy]': {
				click: function(item, e) { this.loadForm(e, 'copy'); } 
			},
			'filelist button[action=move]': {
				click: function(item, e) { this.loadForm(e, 'move'); } 
			},
			'filelist button[action=delete]': {
				click: function(item, e) { this.loadForm(e, 'delete'); } 
			},
			'filelist button[action=rename]': {
				click: function(item, e) { this.loadForm(e, 'rename'); } 
			},
			'filelist button[action=chmod]': {
				click: function(item, e) { this.loadForm(e, 'chmod'); } 
			},
			'filelist button[action=view]': {
				click: function(item, e) { this.loadForm(e, 'view'); } 
			},
			'filelist button[action=diff]': {
				click: function(item, e) { this.loadForm(e, 'diff'); } 
			},
			'filelist button[action=download]': {
				click: function(item, e) { this.loadForm(e, 'download'); } 
			},
			'filelist button[action=upload]': {
				click: function(item, e) { this.loadForm(e, 'upload'); } 
			},
			'filelist button[action=archive]': {
				click: function(item, e) { this.loadForm(e, 'archive'); } 
			},
			'filelist button[action=extract]': {
				click: function(item, e) { this.loadForm(e, 'extract'); } 
			},
			'filelist button[action=sysinfo]': {
				click: function(item, e) { this.loadForm(e, 'sysinfo'); } 
			},
			'filelist button[action=users]': {
				click: function(item, e) { this.loadForm(e, 'users') }
			},
			'filelist button[action=logout]': {
				click: function() { document.location.href='<?php echo ext_make_link('logout', null ) ?>'; }
			},
			'filelist button[action=toggleShowDirectories]': {
				click: function(btn,e) { 
                	if( btn.pressed ) {
                    	this.sendWhat= 'both';
                    } else {
                    	this.sendWhat= 'files';
                   	}
                   	this.onLoadFileList();
                }
			},
			'filelist textfield[name=filterValue]': {
				keypress: { fn: 	function(textfield, e ) {
					if( e.getKey() == Ext.EventObject.ENTER ) {
						filterVal = textfield.getValue();
						if( filterVal.length > 1 ) {
							datastore.filter( 'name', eval('/'+filterVal+'/gi') );
						} else {
							datastore.clearFilter();
						}
					  }
	             	}
                 }
			},
			'filelist button[action=clearfilter]': {
				click: function() { 
                	this.getFileStore().clearFilter();
                	Ext.getCmp("filterField").setValue(""); 
                                
               	}
            },
            'filelist gridview': {
	            itemkeydown: { fn: 
					function( view, record, el, index, e ) {
						
						switch( e.getKey() ) {
		    				case Ext.EventObject.C:
		    					if( e.ctrlKey ) {
		    						e.preventDefault();
		    						this.loadForm(null, 'copy');
		    					}
		    					break;
		    		   		case Ext.EventObject.X:
		    					if( e.ctrlKey ) {
		    						e.preventDefault();
		    						this.loadForm(null, 'move');
		    					}
		    					break;
		    				case Ext.EventObject.A:
		    					if( e.ctrlKey ) {
		    						e.preventDefault();
		    						this.getFilesList().getSelectionModel().selectAll();
		    					}
		    					break;
		    				case Ext.EventObject.DELETE:
		    					if( e.ctrlKey ) e.preventDefault();
		    					this.loadForm(null, 'delete');
		    					break;
		    		 	}
			    	},
			    	scope: this
				},
	        	containerkeydown: { fn: 
					function( view, e ) {
						
						switch( e.getKey() ) {
		    				case Ext.EventObject.C:
		    					if( e.ctrlKey ) {
		    						e.preventDefault();
		    						this.loadForm(null, 'copy');
		    					}
		    					break;
		    		   		case Ext.EventObject.X:
		    					if( e.ctrlKey ) {
		    						e.preventDefault();
		    						this.loadForm(null, 'move');
		    					}
		    					break;
		    				case Ext.EventObject.A:
		    					if( e.ctrlKey ) {
		    						e.preventDefault();
		    						this.getFilesList().getSelectionModel().selectAll();
		    					}
		    					break;
		    				case Ext.EventObject.DELETE:
		    					e.preventDefault();
		    					this.loadForm(null, 'delete');
		    					break;
		    		 	}
			    	},
			    	scope: this
				}
			},
			'gridctx menuitem[action=edit]': {
				click: function(item, e) { this.loadForm(e, 'edit'); }
			},
			'gridctx menuitem[action=copy]': {
				click: function(item, e) { this.loadForm(e, 'copy'); } 
			},
			'gridctx menuitem[action=move]': {
				click: function(item, e) { this.loadForm(e, 'move'); } 
			},
			'gridctx menuitem[action=delete]': {
				click: function(item, e) { this.loadForm(e, 'delete'); } 
			},
			'gridctx menuitem[action=rename]': {
				click: function(item, e) { this.loadForm(e, 'rename'); } 
			},
			'gridctx menuitem[action=chmod]': {
				click: function(item, e) { this.loadForm(e, 'chmod'); } 
			},
			'gridctx menuitem[action=view]': {
				click: function(item, e) { this.loadForm(e, 'view'); } 
			},
			'gridctx menuitem[action=diff]': {
				click: function(item, e) { this.loadForm(e, 'diff'); } 
			},
			'gridctx menuitem[action=download]': {
				click: function(item, e) { this.loadForm(e, 'download'); } 
			},	
			'gridctx button[action=extract]': {
				click: function(item, e) { this.loadForm(e, 'extract'); } 
			}	
		});
		// We listen for the application-wide stationstart event
		this.application.on({
			dirchange: this.onLoadFileList,
			scope: this
		});
	},
	onLoadFileList: function(directory) {
		if( !directory ) {
			directory = this.currentDir;
		}
		if( directory.id ) {
			directory = directory.id;
		}
		directory = this.getFullClearPath( directory );
		var fileStore = this.getFileStore();
		fileStore.load({
			//callback: this.onLoadFileList,
			params: {
				sendWhat: this.sendWhat,
				dir: directory
			},
			scope: this
		});
		document.title = 'eXtplorer - ' + directory;
	    Ext.Ajax.request({
			url: '<?php echo basename( $GLOBALS['script_name']) ?>',
			params: { action:'chdir_event', dir: directory, option: 'com_extplorer' },
			method: 'GET',
			callback: function(options, success, response ) {
				if( success ) {
					checkLoggedOut( response ); // Check if current user is logged off. If yes, Joomla! sends a document.location redirect, which will be eval'd here
					var result = Ext.decode( response.responseText );						
					Ext.get('bookmark_container').update( result.bookmarks );
					Ext.select('select[name=favourites]').on( 'change', 
						function(e, el ) {
							this.application.fireEvent( 'dirchange', '/' + Ext.get(el).getValue() );
						}, this
					);
				}
			},
			scope: this
		});
		this.currentDir = directory;
		fileStore.currentDir = directory;
	},
	getFullClearPath: function(path) {
		return path.replace( /_RRR_/g, '/' ).replace(/eXtplorer\.model\.Directory-/, '' )
		.replace(/\/\//, '/' );
	},
	onHandleRowClick: function (sm, rowIndex) {
    	var selections = sm.getSelection();
    	tb = this.getFilesList().getDockedComponent('topToolBar');
    	if( selections.length > 1 ) {
    		tb.items.get('tb_edit').disable();
    		tb.items.get('tb_delete').enable();
    		tb.items.get('tb_rename').disable();
    		tb.items.get('tb_chmod').enable();
    		tb.items.get('tb_download').disable();
    		tb.items.get('tb_extract').disable();
    		tb.items.get('tb_archive').enable();
    		tb.items.get('tb_view').enable();
    	} else if(selections.length == 1) {
    	
    		tb.items.get('tb_edit')[selections[0].get('is_editable')&&selections[0].get('is_readable') ? 'enable' : 'disable']();
    		tb.items.get('tb_delete')[selections[0].get('is_deletable') ? 'enable' : 'disable']();
    		tb.items.get('tb_rename')[selections[0].get('is_deletable') ? 'enable' : 'disable']();
    		tb.items.get('tb_chmod')[selections[0].get('is_chmodable') ? 'enable' : 'disable']();
    		tb.items.get('tb_download')[selections[0].get('is_readable')&&selections[0].get('is_file') ? 'enable' : 'disable']();
    		tb.items.get('tb_extract')[selections[0].get('is_archive') ? 'enable' : 'disable']();
    		tb.items.get('tb_archive').enable();
    		tb.items.get('tb_view').enable();
    	} else {
			tb.items.get('tb_edit').disable();
    		tb.items.get('tb_delete').disable();
    		tb.items.get('tb_rename').disable();
    		tb.items.get('tb_chmod').disable();
    		tb.items.get('tb_download').disable();
    		tb.items.get('tb_extract').disable();
    		tb.items.get('tb_view').disable();
    		tb.items.get('tb_archive').disable();
    	}
    	return true;
    },
	onRowContextMenu: function (grid, record, el, rowIndex, e) {
    	if( typeof e == 'object') {
    		e.preventDefault();
    	} else {
    		e = f;
    	}
    	gridCtxMenu = eXtplorer.view.gridCtxMenu;
    	gsm = grid.getSelectionModel();
    	gsm.clickedRow = rowIndex;
    	var selections = gsm.getSelection();
    	if( selections.length > 1 ) {
    		gridCtxMenu.items.get('gc_edit').disable();
    		gridCtxMenu.items.get('gc_delete').enable();
    		gridCtxMenu.items.get('gc_rename').disable();
    		gridCtxMenu.items.get('gc_chmod').enable();
    		gridCtxMenu.items.get('gc_download').disable();
    		gridCtxMenu.items.get('gc_extract').disable();
    		gridCtxMenu.items.get('gc_archive').enable();
    		gridCtxMenu.items.get('gc_view').enable();
    	} else if(selections.length == 1) {
    		gridCtxMenu.items.get('gc_edit')[selections[0].get('is_editable')&&selections[0].get('is_readable') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_delete')[selections[0].get('is_deletable') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_rename')[selections[0].get('is_deletable') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_chmod')[selections[0].get('is_chmodable') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_download')[selections[0].get('is_readable')&&selections[0].get('is_file') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_extract')[selections[0].get('is_archive') ? 'enable' : 'disable']();
    		gridCtxMenu.items.get('gc_archive').enable();
    		gridCtxMenu.items.get('gc_view').enable();
    	}
		gridCtxMenu.showBy(e.getTarget(), 'tr-br?' );
    },
	loadForm: function( caller, action ) {
				
		if( typeof this.getFilesList().getSelectionModel == "undefined" ) { return;}
		var selectedRows = this.getFilesList().getSelectionModel().getSelection();
		if( selectedRows.length < 1 ) {
			var selectedNode = this.getDirTree().getSelectionModel().getSelection();
			if( selectedNode.length > 0 ) {
				selectedRows = Array( this.getDirTree().getSelectionModel().getSelection()[0].id.replace( /_RRR_/g, '/' ) );
			}
		}
		
		var dontNeedSelection = { mkitem:1, sysinfo:1, ftp_authentication:1, upload:1, search:1, users:1, ssh2_authentication: 1, extplorer_authentication: 1 };
		if( dontNeedSelection[action] == null  && selectedRows.length < 1 ) {
			Ext.Msg.alert( '<?php echo ext_Lang::err('error', true )."','".ext_Lang::err('miscselitems', true ) ?>');
			return false;
		}
		var formController = this.application.getController('Forms');
		switch( action ) {
			case 'edit':
				requestParams = this.getRequestParams();
				requestParams.action = action;
				Ext.Ajax.request( { 
					url: '<?php echo basename($GLOBALS['script_name']) ?>',
					params: Ext.urlEncode( requestParams ),
					scripts: true,
					method: 'GET',
					callback: function(options, success, response) {
						if( !success ) {
							msgbox = Ext.Msg.alert( "Ajax communication failure!");
							msgbox.setIcon( Ext.MessageBox.ERROR );
						}
						if( response && response.responseText ) {
							
							//Ext.Msg.alert("Debug", response.responseText );
							try{ json = Ext.decode( response.responseText );
								if( json.error && typeof json.error != 'xml' ) {
									Ext.Msg.alert( "<?php echo ext_Lang::err('error', true ) ?>", json.error );									
									return false;
								}
							} catch(e) {
								msgbox = Ext.Msg.alert( "<?php echo ext_Lang::err('error', true ) ?>", "JSON Decode Error: " + e.message );
								msgbox.setIcon( Ext.MessageBox.ERROR );
								return false; 
							}
							
							formController.editForm(json, options );
							
						} else if( !response || !response.responseText) {
							msgbox = Ext.Msg.alert( "<?php echo ext_Lang::err('error', true ) ?>", "Received an empty response");
							msgbox.setIcon( Ext.MessageBox.ERROR );
						}
					},
					scope: this
				});
	            
	            break;
	
			case 'extplorer_authentication':
			case 'ftp_authentication':
			case 'ssh2_authentication':
			case 'sysinfo':
			case 'mkitem':
			case 'move':
			case 'rename':
			case 'search':
			case 'upload':
			case 'view':
			case 'diff':
			case 'users':
			case 'archive':
			case 'chmod':
			case 'copy':
			case 'move':
				if( formController[action.replace( /(-[a-z])/gi, camelReplaceFn )] != null ) {
					formController[Ext.String.camelize( action )](action );
				} else {
					formController.genericForm( action );
				}
				break;
				
			case 'delete':
				var num = selectedRows.length;
				Ext.Msg.confirm('<?php echo ext_Lang::msg('dellink', true ) ?>?', Ext.String.format("<?php echo ext_Lang::err('miscdelitems', true ) ?>", num ), this.deleteFiles, this );
				break;
			case 'extract':
				Ext.Msg.confirm('<?php echo ext_Lang::msg('extractlink', true ) ?>?', "<?php echo ext_Lang::msg('extract_warning', true ) ?>", extractArchive, this);
				break;
			case 'download':
				document.location = '<?php echo basename($GLOBALS['script_name']) ?>?option=com_extplorer&action=download&item='+ encodeURIComponent(this.getFilesList().getSelectionModel().getSelection()[0].get('name')) + '&dir=' + encodeURIComponent( this.currentDir );
				break;
		}
	},
	handleCallback: function(requestParams, node1, node2) {
		var conn = new Ext.data.Connection();
	
		conn.request({
			url: '<?php echo basename($GLOBALS['script_name']) ?>',
			params: requestParams,
			callback: function(options, success, response ) {
				if( success ) {
					json = Ext.decode( response.responseText );
					if( json.success ) {
						statusBarMessage( json.message, false, true );
						try {
							if( node1 ) {
								this.application.getStore("DirectoryTree").load({ node: node1 });
								if( node2 ) {
									this.application.getStore("DirectoryTree").load({ node: node2 });
								}
								if( options.params.action == 'delete' || options.params.action == 'rename' ) {
									node1.parentNode.select();
								}
								
							} else {
								this.getFileStore().load();
							}
						} catch(e) { this.getFileStore().load(); }
					} else {
						Ext.Msg.alert( 'Failure', json.error );
					}
				}
				else {
					Ext.Msg.alert( 'Error', 'Failed to connect to the server.');
				}
	
			},
			scope: this
		});
	},
	getRequestParams: function(action) {
		var selitems, dir, node;
		var selectedRows = this.getFilesList().getSelectionModel().getSelection();
		if( selectedRows.length < 1 ) {
			node = this.getDirTree().getSelectionModel().getSelection();
			if( node.length == 1 ) {
				var dir = this.getDirTree().getSelectionModel().getSelection()[0].id.replace( /_RRR_/g, '/' );
				var lastSlash = dir.lastIndexOf( '/' );
				if( lastSlash > 0 ) {
					selitems = Array( dir.substring(lastSlash+1) );
				} else {
					selitems = Array( dir );
				}
			} else {
				selitems = {};
			}
			dir = this.currentDir.substring( 0, this.currentDir.lastIndexOf('/'));
		}
		else {
			selitems = Array(selectedRows.length);
	
			if( selectedRows.length > 0 ) {
				for( i=0; i < selectedRows.length;i++) {
					selitems[i] = selectedRows[i].get('name');
				}
			}
			dir = this.currentDir;
		}
		//Ext.Msg.alert("Debug", dir );
		var requestParams = {
			option: 'com_extplorer',
			dir: dir,
			item: selitems.length > 0 ? selitems[0]:'',
			'selitems[]': selitems
		};
		if( action ) {
			requestParams["action"] = action;
		}
		return requestParams;
	},
	// Function for actions, which don't require a form like download, extraction, deletion etc.
	//
	deleteFiles: function(btn) {
		if( btn != 'yes') {
			return;
		}
		requestParams = this.getRequestParams();
		requestParams.action = 'delete';
		this.handleCallback(requestParams);
	},
	extractArchive: function(btn) {
		if( btn != 'yes') {
			return;
		}
		requestParams = this.getRequestParams();
		requestParams.action = 'extract';
		this.handleCallback(requestParams);
	}
});