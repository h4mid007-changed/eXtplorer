/*
* View Application Code for the Main File Listing Grid
* $Id: $
*/

Ext.define('eXtplorer.view.FileList', {
    extend: 'Ext.grid.Panel',
	alias: "widget.filelist",
	title: "<?php echo ext_lang::msg("actdir", true ) ?>",
	autoScroll:true,
	collapsible: false,
	closeOnTab: true,
	stores: ['File', 'DirectoryTree'],
    initComponent: function() {
        Ext.apply(this, {
        	store: 'File',
        	selModel: {
        		mode: "MULTI"
        	},
			defaults: {
				sortable: true
			},
			columns: [{
	           header: "<?php echo ext_Lang::msg('nameheader', true ) ?>",
	           dataIndex: 'name',
	           width: 250,
			   sortable: true,
	           renderer: function(value,p, record){
			        var t = new Ext.Template("<img src=\"{0}\" alt=\"* \" align=\"absmiddle\" />&nbsp;<b>{1}</b>");
			        return t.apply([record.get('icon'), value] );
			    },
	        },{
	           header: "<?php echo ext_Lang::msg('sizeheader', true ) ?>",
	           dataIndex: 'size',
	           width: 50,
			   sortable: true
	        },{
	           header: "<?php echo ext_Lang::msg('typeheader', true ) ?>",
	           dataIndex: 'type',
	           width: 70,
			   sortable: true,
	           align: 'right',
	           renderer:  function(value){
			        var t = new Ext.Template("<i>{0}</i>");
			        return t.apply([value]);
			    }
	        },{
	           header: "<?php echo ext_Lang::msg('modifheader', true ) ?>",
	           dataIndex: 'modified',
	           width: 150,
			   sortable: true
	        },{
	           header: "<?php echo ext_Lang::msg('permheader', true ) ?>",
	           dataIndex: 'perms',
	           width: 100,
			   sortable: true
	        },{
	           header: "<?php echo ext_Lang::msg('miscowner', true ) ?>",
	           dataIndex: 'owner',
	           width: 100,
	           sortable: false
	        },{ 
	        	dataIndex: 'is_deletable', header: "is_deletable", hidden: true, hideable: false 
	        },{
	        	dataIndex: 'is_file', hidden: true, hideable: false 
	        },{
	        	dataIndex: 'is_archive', hidden: true, hideable: false 
	        },{
	        	dataIndex: 'is_writable', hidden: true, hideable: false 
	        },{
	        	dataIndex: 'is_chmodable', hidden: true, hideable: false 
	        },{
	        	dataIndex: 'is_readable', hidden: true, hideable: false 
	        },{	
	        	dataIndex: 'is_deletable', hidden: true, hideable: false 
	        },{	
	        	dataIndex: 'is_editable', hidden: true, hideable: false 
	        }
		    ],
			dockedItems: [{
				xtype: 'toolbar',
				itemId: 'topToolBar',
				dock: 'top',
				items: [
                         	{
                             	xtype: "button",
                         		id: 'tb_home',
                         		icon: '<?php echo _EXT_URL ?>/images/_home.png',
                         		text: '<?php echo ext_Lang::msg('homelink', true ) ?>',
                         		tooltip: '<?php echo ext_Lang::msg('homelink', true ) ?>',
                         		cls:'x-btn-text-icon',
                         		action: 'home'
                         	},
                            {
                         		xtype: "button",
                         		id: 'tb_reload',
                              	icon: '<?php echo _EXT_URL ?>/images/_reload.png',
                              	text: '<?php echo ext_Lang::msg('reloadlink', true ) ?>',
                            	tooltip: '<?php echo ext_Lang::msg('reloadlink', true ) ?>',
                              	cls:'x-btn-text-icon',
                              	action: 'reload'
                            },
                            <?php if( !ext_isFTPMode() ) { ?>
                              	{
                              		xtype: "button",
                             		id: 'tb_search',
                              		icon: '<?php echo _EXT_URL ?>/images/_filefind.png',
                              		text: '<?php echo ext_Lang::msg('searchlink', true ) ?>',
                              		tooltip: '<?php echo ext_Lang::msg('searchlink', true ) ?>',
                              		cls:'x-btn-text-icon',
                              		action: 'search'
                              	},
                            <?php } ?>
                            {
                            		xtype: 'tbseparator'
                            	},
                            {
                            	xtype: "button",
                         		id: 'tb_new',
                              		icon: '<?php echo _EXT_URL ?>/images/_filenew.png',
                              		tooltip: '<?php echo ext_Lang::msg('newlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		action: 'mkitem'
                              	},
                              	{
                              		xtype: "button",
                             		id: 'tb_edit',
                              		icon: '<?php echo _EXT_URL ?>/images/_edit.png',
                              		tooltip: '<?php echo ext_Lang::msg('editlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		action: 'edit'
                              	},
                              	{
                              		xtype: "button",
                             		id: 'tb_copy',
                              		icon: '<?php echo _EXT_URL ?>/images/_editcopy.png',
                              		tooltip: '<?php echo ext_Lang::msg('copylink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		action: 'copy'
                              	},
                              	{
                              		xtype: "button",
                             		id: 'tb_move',
                              		icon: '<?php echo _EXT_URL ?>/images/_move.png',
                              		tooltip: '<?php echo ext_Lang::msg('movelink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		action: 'move'
                              	},
                              	{
                              		xtype: "button",
                             		id: 'tb_delete',
                              		icon: '<?php echo _EXT_URL ?>/images/_editdelete.png',
                              		tooltip: '<?php echo ext_Lang::msg('dellink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		action: 'delete'
                              	},
                              	{
                              		xtype: "button",
                             		id: 'tb_rename',
                              		icon: '<?php echo _EXT_URL ?>/images/_fonts.png',
                              		tooltip: '<?php echo ext_Lang::msg('renamelink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		action: 'rename'
                              	},
                              	{
                              		xtype: "button",
                             		id: 'tb_chmod',
                              		icon: '<?php echo _EXT_URL ?>/images/_chmod.png',
                              		tooltip: '<?php echo ext_Lang::msg('chmodlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		action: 'chmod'
                              	},{
                            		xtype: 'tbseparator'
                            	},
                              	{
                              		xtype: "button",
                             		id: 'tb_view',
                              		icon: '<?php echo _EXT_URL ?>/images/_view.png',
                              		tooltip: '<?php echo ext_Lang::msg('viewlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		action: 'view'
                              	},
                              	{
                              		xtype: "button",
                             		id: 'tb_diff',
                              		icon: '<?php echo _EXT_URL ?>/images/extension/document.png',
                              		tooltip: '<?php echo ext_Lang::msg('difflink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		action: 'diff'
                              	},
                              	{
                              		xtype: "button",
                             		id: 'tb_download',
                              		icon: '<?php echo _EXT_URL ?>/images/_down.png',
                              		tooltip: '<?php echo ext_Lang::msg('downlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow ? 'false' : 'true' ?>,
                              		action: 'download'
                              	},{
                            		xtype: 'tbseparator'
                            	},
                              	{
                              		xtype: "button",
                             		id: 'tb_upload',
                              		icon: '<?php echo _EXT_URL ?>/images/_up.png',
                              		tooltip: '<?php echo ext_Lang::msg('uploadlink', true ) ?>',
                              		cls:'x-btn-icon',
                              		disabled: <?php echo $allow && ini_get('file_uploads') ? 'false' : 'true' ?>,
                              		action: 'upload' 
                              	},
                              	{
                              		xtype: "button",
                             		id: 'tb_archive',
                              		icon: '<?php echo _EXT_URL ?>/images/_archive.png',
                              		tooltip: '<?php echo ext_Lang::msg('comprlink', true ) ?>',
                          			cls:'x-btn-icon',
                          			<?php if( ($GLOBALS["zip"] || $GLOBALS["tar"] || $GLOBALS["tgz"]) && !ext_isFTPMode() ) { ?>
                              		action: 'archive'
                          			<?php } else { ?>
                          			disabled: true
                              		<?php }  ?>
                              	},{
                              		xtype: "button",
                             		id: 'tb_extract',
                              		icon: '<?php echo _EXT_URL ?>/images/_extract.gif',
                              		tooltip: '<?php echo ext_Lang::msg('extractlink', true ) ?>',
                              		cls:'x-btn-icon',
                          			<?php if( ($GLOBALS["zip"] || $GLOBALS["tar"] || $GLOBALS["tgz"]) && !ext_isFTPMode() ) { ?>
                              		action: 'extract'
                          			<?php } else { ?>
                          			disabled: true
                              		<?php }  ?>
                          		},{
                            		xtype: 'tbseparator'
                            	},
                              	{
                          			xtype: "button",
                             		id: 'tb_info',
                              		icon: '<?php echo _EXT_URL ?>/images/_help.png',
                              		tooltip: '<?php echo ext_Lang::msg('aboutlink', true ) ?>',
                              		cls:'x-btn-icon',
                         
                              		action: 'info'
                              	},
                              	{
                            		xtype: 'tbseparator'
                            	},
                              	<?php
                          		// ADMIN & LOGOUT
                          		if(!empty($GLOBALS["require_login"])) {
                          			$admin=(($GLOBALS["permissions"]&4)==4);
                          			if($admin) {
                          		
                          			?>
                          	    	{	// ADMIN
                          	    		xtype: "button",
                                 		id: 'tb_users',
                          	    		icon: '<?php echo _EXT_URL ?>/images/_users.png',
                          	    		tooltip: '<?php echo ext_Lang::msg('adminlink', true ) ?>',
                          	    		cls:'x-btn-icon',
                          	    		action: 'users'
                          	    	},
                          	    	<?php
                          			}
                          			?>
                          	    	{	// LOGOUT
                          	    		xtype: "button",
                                 		id: 'tb_logout',
                          	    		icon: '<?php echo _EXT_URL ?>/images/_logout.png',
                          	    		tooltip: '<?php echo ext_Lang::msg('logoutlink', true ) ?>',
                          	    		cls:'x-btn-icon',
                          	    		action: 'logout'
                          	    	},		
                          	    	{
                            		xtype: 'tbseparator'
                            	},
                          			<?php
                          		}
                          		?>		
                            	 {
                            	 	xtype: 'button',
                            		text: '<?php echo ext_Lang::msg('show_directories', true ) ?>',
                            		enableToggle: true,
                            		pressed: false,
                            		action: 'toggleShowDirectories' 
                            	}, {
                            		xtype: 'tbseparator'
                            	}, {
                            		xtype: 'textfield', 
                                	name: "filterValue", 
                                	id: "filterField",
                                	enableKeyEvents: true,
                                	title: "<?php echo ext_Lang::msg('filter_grid', true ) ?>",
                                },
                            	{
                            		xtype: 'button',
                            		text: '&nbsp;X&nbsp;',
                            		action: 'clearfilter'
								}
                            ]
                        },
						{
							
							dock: 'bottom',
							items: [/*{
								xtype: 'pagingtoolbar',
								store:  this.getStore(),   // same store GridPanel is using
								displayInfo: true
							},*/{
								xtype: 'statusbar',
								id: 'statusPanel'
							}
							]
						}
					]							
			
		}
	);
	this.callParent(arguments);
	
	/*,
	keys:
		[{
			key: 'c',
			ctrl: true,
			stopEvent: true,
			handler: function() { openActionDialog(this, 'copy'); }
		   
	   },{
			key: 'x',
			ctrl: true,
			stopEvent: true,
			handler: function() { openActionDialog(this, 'move'); }
		   
	   },{
		 key: 'a',
		 ctrl: true,
		 stopEvent: true,
		 handler: function() {
			ext_itemgrid.getSelectionModel().selectAll();
		 }
	}, 
	{
		key: Ext.EventObject.DELETE,
		handler: function() { openActionDialog(this, 'delete'); }
	}
	],*/
	
	}
});

Ext.define('eXtplorer.view.ActionDialog', {
    extend: 'Ext.window.Window',
	singleton: true,
	modal:true,
	width:600,
	height:400,
	shadow:true,
	minWidth:300,
	minHeight:200,
	proxyDrag: true,
	resizable: true,
	renderTo: Ext.getBody(),
	keys: {
		key: 27,
		fn  : function(){
		   	this.hide();
		   	this.removeAll(true);
	    }
	},
	title: '<?php echo ext_Lang::msg('dialog_title', true ) ?>'
});			