<?php
if( !defined( '_JEXEC' )) {
	$_REQUEST['action'] = 'include_javascript';
	$_GET['subdir'][] = 'app/view/forms';
	$_GET['file'][] = str_replace('.php', '', basename(__FILE__) );
	include('../../../../index.php');
}
$permvalues = array(0,1,2,3,7);
$permcount = count($GLOBALS["messages"]["miscpermnames"]);
?>	
  Ext.define('eXtplorer.model.User', {
        extend: 'Ext.data.Model',
        fields: [
            'nuser',
            'nuser_old',
            'home_url',
            'home_dir',
            'permissions',
            'home_url',
            'no_access',
            "pass1", "pass2",
            { name: 'show_hidden', type: 'bool' },
            { name: 'active', type: 'bool' },
            { name: 'isNew', type: 'bool' }
        ],
		 proxy: {
            type: 'ajax',
			extraParams: { option: "com_extplorer", action: "users" },
            api: {
                read: 'index.php',
                create: 'index.php?action2=adduser',
                update: 'index.php?action2=edituser',
                destroy: 'index.php?action2=removeuser'
            },
			reader: {
                type: 'json',
                successProperty: 'success',
                messageProperty: 'message',
				root: "users",
				totalProperty: "totalCount"
			}
		}
    });
var store = Ext.create('Ext.data.Store', {
	// destroy the store if the grid is destroyed
	//autoDestroy: true,
	model: 'eXtplorer.model.User',
	autoLoad: true,
	sorters: [{
		property: 'nuser',
		direction: 'ASC'
	}],
    listeners: {
        write: function(proxy, operation){
            Ext.Msg.alert(operation.action, operation.resultSet.message);
        }
    }
});

store.on("update", function( store, record, operation, modifiedFieldNames, eOpts ) {
	
	store.sync();
});
store.on("remove", function( store, record, operation, modifiedFieldNames, eOpts ) {
	Ext.Msg.confirm('Are you sure?', 'Are you sure you want to remove this user account?', 
		function( btn ) {
			if( btn == 'ok' ) {
				this.sync();
			}
		}, store );
}, this);
var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
	clicksToMoveEditor: 1,
	autoCancel: true
});
Ext.define( 'eXtplorer.view.forms.Users', {
	extend: 'Ext.form.Panel',
	width: 700,
	height: 500,
	layout: 'column',
	bodyPadding: 5,
    frame: true,
	title: "<?php echo ext_Lang::msg('actusers', true) ?>",
	items: [{
        columnWidth: 0.5,
        xtype: 'gridpanel',
        height: 400,
		store: store,
	    columns: [{
	        header: '<?php echo ext_Lang::msg( 'miscusername', true ) ?>',
	        dataIndex: 'nuser',
	    },{
	        header: 'Pass',
	        dataIndex: 'pass1',
	        hidden: true
	    },{
	        header: 'Pass',
	        dataIndex: 'pass2',
	        hidden: true
	    },{
	        header: '<?php echo ext_Lang::msg( 'mischomeurl', true ) ?>',
	        dataIndex: 'home_url',
	        hidden: true
	    },{
	        header: '<?php echo ext_Lang::msg( 'mischomedir', true ) ?>',
	        dataIndex: 'home_dir',
	        hidden: true
	    }, {
	        header: '<?php echo ext_Lang::msg( 'miscshowhidden', true ) ?>',
	        dataIndex: 'show_hidden',
	        renderer: function( value ) {
	        	return value.toString() == "true" ? "<?php echo $GLOBALS["messages"]["miscyesno"][0] ?>" : "<?php echo $GLOBALS["messages"]["miscyesno"][1] ?>";
	        },
	        hidden: true
	    },{
	        header: '<?php echo ext_Lang::msg( 'mischidepattern', true ) ?>',
	        dataIndex: 'no_access',
	        hidden: true
	    }, {
            header: '<?php echo ext_Lang::msg( 'miscperms', true ) ?>',
            dataIndex: 'permissions',
            width: 130,
            renderer: function(value) {
            	switch( value ) {
            	<?php 
            		for($i=0;$i<$permcount;++$i) {
						if( $permvalues[$i]==7) $index = 4;
						else $index = $i;
						echo 'case "'.$permvalues[$i].'": return "'.ext_lang::msg( array('miscpermnames' => $index)).'";'."\n";
					}
					?>
				}
            }
        }, {
            header: '<?php echo ext_Lang::msg( 'miscactive', true ) ?>',
            dataIndex: 'active',
	        renderer: function( value ) {
	        	return value.toString() == "true" ? "<?php echo $GLOBALS["messages"]["miscyesno"][0] ?>" : "<?php echo $GLOBALS["messages"]["miscyesno"][1] ?>";
	        },
	        width: 55
	    }
	    ],
	     tbar: [{
			text: '<?php echo ext_Lang::msg( 'miscadduser', true ) ?>',
			icon: '<?php echo _EXT_URL ?>/images/user_add.png',
			handler : function() {
				rowEditing.cancelEdit();
	
				// Create a model instance
				var r = Ext.create('eXtplorer.model.User', {
					nuser: 'NewUser',
	                show_hidden: true,
	                active: true,
	                isNew: true
	            });
	
	            store.insert(0, r);
	            rowEditing.startEdit(0, 0);
	        }
	    }, {
	        itemId: 'removeUser',
	        text: 'Remove User',
	        icon: '<?php echo _EXT_URL ?>/images/user_delete.png',
	        handler: function() {
	            var sm = this.up("gridpanel").getSelectionModel();
	            rowEditing.cancelEdit();
	            store.remove(sm.getSelection());
	            if (store.getCount() > 0) {
	                sm.select(0);
	            }
	        },
	        disabled: true
	    }],
	    
	    listeners: {
	        'selectionchange': function(view, records) {
	            this.down('#removeUser').setDisabled(!records.length);
	            if (records[0]) {
	                this.up('form').getForm().loadRecord(records[0]);
	            }
	        },
	        'validateedit': function(editor, e) {
				if (e.field == "pass1" ) {
					this.e = e;
					Ext.Msg.prompt( '<?php echo ext_Lang::msg('miscconfnewpass') ?>', '<?php echo ext_Lang::msg('miscconfpass') ?>', 
						function( btn, value ) {
							if( btn == "ok" && value != "" && e.record.data["pass1"] == value) {
								this.e.record.data["pass2"] = value;
							} else {
								Ext.Msg.alert("<?php echo ext_Lang::err('error') ?>", "<?php echo Ext_Lang::msg('miscnopassmatch') ?>" );
								this.e.cancel = true;
							}	
						}, this );
				   	
				}
			}
	    }
	},
    {
        columnWidth: 0.5,
        margin: '0 0 0 10',
        xtype: 'panel',
        title:'User details',
        defaults: {
            width: 240,
            labelWidth: 90,
            margin: 5,
            size: 20
        },
		items: [{
			xtype: "textfield",
			fieldLabel: "<?php echo ext_Lang::msg( 'miscusername', true ) ?>",
			name: "nuser",
			width:235,
			allowBlank:false
		},{
			xtype: "textfield",
			fieldLabel: "<?php echo ext_Lang::msg( 'miscconfpass', true ) ?>",
			name: "pass1",
			inputType: "password",
			width:235
		},
		{	xtype: "textfield",
			fieldLabel: "<?php echo ext_Lang::msg( 'miscconfnewpass', true ) ?>",
			name: "pass2",
			inputType: "password",
			width:235,
            validator: function(value) {
                var password1 = this.previousSibling('[name=pass1]');
                return (value === password1.getValue()) ? true : '<?php echo ext_Lang::err('miscnopassmatch',true) ?>'
            }
		},
		{
			xtype: "textfield",
			fieldLabel: "<?php echo ext_Lang::msg( 'mischomedir', true ) ?>",
			name: "home_dir",
			width:275,
			allowBlank:false
		},
		{ 	xtype: "textfield",
			fieldLabel: "<?php echo ext_Lang::msg( 'mischomeurl', true ) ?>",
			name: "home_url",
			width:275,
			allowBlank:false
		},{
			xtype: "checkbox",
			fieldLabel: "<?php echo ext_Lang::msg( 'miscshowhidden', true ) ?>",
			name: "show_hidden",
			
		},
		{ 	xtype: "textfield",
			fieldLabel: "<?php echo ext_Lang::msg( 'mischidepattern', true ) ?>",
			name: "no_access",
			width:275,
			allowBlank:true
		},
		{
			xtype: "combo",
			fieldLabel: "<?php echo ext_Lang::msg( 'miscperms', true ) ?>",
			store: [<?php
						$permvalues = array(0,1,2,3,7);
						$permcount = count($GLOBALS["messages"]["miscpermnames"]);
						for($i=0;$i<$permcount;++$i) {
							if( $permvalues[$i]==7) $index = 4;
							else $index = $i;
							echo '["'.$permvalues[$i].'", "'.ext_lang::msg( array('miscpermnames' => $index)).'" ]'."\n";
							if( $i+1<$permcount) echo ',';
						}
						?>
					],
			name: "permissions",
			disableKeyFilter: true,
			editable: false,
			triggerAction: "all",
			mode: "local"
		},
		{ 	xtype: "checkbox",
			fieldLabel: "<?php echo ext_Lang::msg( 'miscactive', true ) ?>",
			name: "active",
			disabled: <?php echo !empty($self) ? 'true' : 'false' ?>
		}
	],
	dockedItems: [{
        xtype: 'toolbar',
        dock: 'bottom',
		items: [ { xtype: "tbspacer" }, { xtype: "tbspacer" }, { xtype: "tbspacer" }, { xtype: "tbspacer" }, {
			xtype: "button",
            formBind: true,
            disabled: true,
			icon: "<?php echo _EXT_URL.'/images/accept.png'?>",	
			text: "<?php echo ext_Lang::msg( 'btnsave', true ) ?>", 
			handler: function() {
				record = this.up("form").getForm().getRecord();
				if( record ) {
					this.up("form").getForm().updateRecord(record);
					record = this.up("form").getForm().getRecord();
				
					var data = store.data.getRange();
					Ext.Msg.alert("Status", record.get("nuser") );
					data[store.indexOfTotal(record)] = record;
					store.loadRecords( data );
					
				}
			}
		}]
	}]
    }]
});