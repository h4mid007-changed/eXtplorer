/* Ext.define('eXtplorer.view.PagingToolbar', {
    extend: 'Ext.toolbar.Paging',
	alias: "widget.pagingtoolbar",
	
        store: datastore,
        pageSize: 150,
        displayInfo: true,
        displayMsg: '<?php echo ext_Lang::msg( 'paging_info', true ) ?>',
        emptyMsg: '<?php echo ext_Lang::msg( 'paging_noitems', true ) ?>',
        beforePageText: '<?php echo ext_Lang::msg('paging_page', true ) ?>',
		afterPageText: '<?php echo ext_Lang::msg('paging_of_X', true ) ?>',
		firstText: '<?php echo ext_Lang::msg('paging_firstpage', true ) ?>',
		lastText: '<?php echo ext_Lang::msg('paging_lastpage', true ) ?>',
		nextText: '<?php echo ext_Lang::msg('paging_nextpage', true ) ?>',
		prevText: '<?php echo ext_Lang::msg('paging_prevpage', true ) ?>',
		refreshText: '<?php echo ext_Lang::msg('reloadlink', true ) ?>',
		items: ['-',' ',' ',' ',' ',' ',
			new Ext.ux.StatusBar({
			    defaultText: '<?php echo ext_Lang::msg('done', true ) ?>',
			    id: 'statusPanel'
			})]
    });
    */
 Ext.define('eXtplorer.view.gridCtxMenu', {
    extend: 'Ext.menu.Menu',
    singleton: true,
	alias: "widget.gridctx",
    items: [{
    		id: 'gc_edit',
    		icon: '<?php echo _EXT_URL ?>/images/_edit.png',
    		text: '<?php echo ext_Lang::msg('editlink', true ) ?>',
    		action: "edit"
    	},
    	{
    		id: 'gc_diff',
    		icon: '<?php echo _EXT_URL ?>/images/extension/document.png',
    		text: '<?php echo ext_Lang::msg('difflink', true ) ?>',
    		action: "diff"
    	},
    	{
    		id: 'gc_rename',
    		icon: '<?php echo _EXT_URL ?>/images/_fonts.png',
    		text: '<?php echo ext_Lang::msg('renamelink', true ) ?>',
    		action: "rename"
    	},
    	{
        	id: 'gc_copy',
    		icon: '<?php echo _EXT_URL ?>/images/_editcopy.png',
    		text: '<?php echo ext_Lang::msg('copylink', true ) ?>',
    		action: "copy"
    	},
    	{
    		id: 'gc_move',
    		icon: '<?php echo _EXT_URL ?>/images/_move.png',
    		text: '<?php echo ext_Lang::msg('movelink', true ) ?>',
    		action: "move"
    	},
    	{
    		id: 'gc_chmod',
    		icon: '<?php echo _EXT_URL ?>/images/_chmod.png',
    		text: '<?php echo ext_Lang::msg('chmodlink', true ) ?>',
    		action: "chmod"
    	},
    	{
    		id: 'gc_delete',
    		icon: '<?php echo _EXT_URL ?>/images/_editdelete.png',
    		text: '<?php echo ext_Lang::msg('dellink', true ) ?>',
    		action: "delete"
    	},
    	'-',
    	{
    		id: 'gc_view',
    		icon: '<?php echo _EXT_URL ?>/images/_view.png',
    		text: '<?php echo ext_Lang::msg('viewlink', true ) ?>',
    		action: "view"
    	},
    	{
    		id: 'gc_download',
    		icon: '<?php echo _EXT_URL ?>/images/_down.png',
    		text: '<?php echo ext_Lang::msg('downlink', true ) ?>',
    		action: "download"
    	},
    	'-',
    	<?php if( ($GLOBALS["zip"] || $GLOBALS["tar"] || $GLOBALS["tgz"]) ) { ?>
	    	{
    			id: 'gc_archive',
	    		icon: '<?php echo _EXT_URL ?>/images/_archive.png',
	    		text: '<?php echo ext_Lang::msg('comprlink', true ) ?>',
	    		action: "archive"
	    	},
	    	{
	    		id: 'gc_extract',
	    		icon: '<?php echo _EXT_URL ?>/images/_extract.gif',
	    		text: '<?php echo ext_Lang::msg('extractlink', true ) ?>',
	    		action: 'extract'
	    	},
    	<?php } ?>
    	'-',
		{
			id: 'cancel',
    		icon: '<?php echo _EXT_URL ?>/images/_cancel.png',
    		text: '<?php echo ext_Lang::msg('btncancel', true ) ?>',
    		action: "hide"
    	}
    	]
    });
 Ext.define('eXtplorer.view.dirCtxMenu', {
    extend: 'Ext.menu.Menu',
    singleton: true,
	alias: "widget.dirctx",
        items: [    	{
        	id: 'dirCtxMenu_new',
    		icon: '<?php echo _EXT_URL ?>/images/_folder_new.png',
    		text: '<?php echo ext_Lang::msg('newlink', true ) ?>',
    		action: 'mkitem'
    	},
    	{
    		id: 'dirCtxMenu_rename',
    		icon: '<?php echo _EXT_URL ?>/images/_fonts.png',
    		text: '<?php echo ext_Lang::msg('renamelink', true ) ?>',
    		action: 'rename'
    	},
    	{
        	id: 'dirCtxMenu_copy',
    		icon: '<?php echo _EXT_URL ?>/images/_editcopy.png',
    		text: '<?php echo ext_Lang::msg('copylink', true ) ?>',
    		action: 'copy'
    	},
    	{
    		id: 'dirCtxMenu_move',
    		icon: '<?php echo _EXT_URL ?>/images/_move.png',
    		text: '<?php echo ext_Lang::msg('movelink', true ) ?>',
    		action: 'move'
    	},
    	{
    		id: 'dirCtxMenu_chmod',
    		icon: '<?php echo _EXT_URL ?>/images/_chmod.png',
    		text: '<?php echo ext_Lang::msg('chmodlink', true ) ?>',
    		action: 'chmod'
    	},
    	{
    		id: 'dirCtxMenu_remove',
    		icon: '<?php echo _EXT_URL ?>/images/_editdelete.png',
    		text: '<?php echo ext_Lang::msg('btnremove', true ) ?>',
    		action: 'delete'
    	},'-',
    	<?php if( ($GLOBALS["zip"] || $GLOBALS["tar"] || $GLOBALS["tgz"]) && !ext_isFTPMode() ) { ?>
	    	{
    			id: 'dirCtxMenu_archive',
	    		icon: '<?php echo _EXT_URL ?>/images/_archive.png',
	    		text: '<?php echo ext_Lang::msg('comprlink', true ) ?>',
	    		action: 'archive'
	    	},
    	<?php } ?>
    	{
    		id: 'dirCtxMenu_reload',
    		icon: '<?php echo _EXT_URL ?>/images/_reload.png',
    		text: '<?php echo ext_Lang::msg('reloadlink', true ) ?>',
    		action: 'reload'
    	},
    	'-', 
		{
			id: 'dirCtxMenu_cancel',
    		icon: '<?php echo _EXT_URL ?>/images/_cancel.png',
    		text: '<?php echo ext_Lang::msg('btncancel', true ) ?>',
    		action: 'hide'
    	}
	]
    });
    
 Ext.define('eXtplorer.view.CopyMoveCtxMenu', {
    extend: 'Ext.menu.Menu',
    singleton: true,
	alias: "widget.copymovectx",
        items: [    	{
        	id: 'copymoveCtxMenu_copy',
    		icon: '<?php echo _EXT_URL ?>/images/_editcopy.png',
    		text: '<?php echo ext_Lang::msg('copylink', true ) ?>',
    		action: 'copy'
    	},
    	{
    		id: 'copymoveCtxMenu_move',
    		icon: '<?php echo _EXT_URL ?>/images/_move.png',
    		text: '<?php echo ext_Lang::msg('movelink', true ) ?>',
    		action: 'move'
    	},'-', 
		{
			id: 'copymoveCtxMenu_cancel',
    		icon: '<?php echo _EXT_URL ?>/images/_cancel.png',
    		text: '<?php echo ext_Lang::msg('btncancel', true ) ?>',
    		action: 'hide'
    	}
	]
    });