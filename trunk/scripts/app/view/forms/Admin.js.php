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
            'home_url',
            'home_dir',
            'permissions',
            'home_url',
            'no_access',
            { name: 'show_hidden', type: 'bool' },
            { name: 'active', type: 'bool' },
            { name: 'isNew', type: 'bool' }
        ],
		 proxy: {
            type: 'ajax',
			url: 'index.php',
			extraParams: { option: "com_extplorer", action: "admin" },
			reader: {
				type: 'json',
				root: "users",
				totalProperty: "totalCount"
			}
		}
    });
var store = Ext.create('Ext.data.Store', {
	// destroy the store if the grid is destroyed
	autoDestroy: true,
	model: 'eXtplorer.model.User',
	autoLoad: true,
	sorters: [{
		property: 'nuser',
		direction: 'ASC'
	}]
});

store.on("update", function( store, record, operation, modifiedFieldNames, eOpts ) {
	var action2 = record.get("isNew") == true ? "adduser" : "edituser";
	store.sync({
		params: {
			action2: action2,
			user: record
		}
	});
});
store.on("remove", function( store, record, operation, modifiedFieldNames, eOpts ) {
	alert( operation );
});
var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
	clicksToMoveEditor: 1,
	autoCancel: true
});
Ext.define( 'eXtplorer.view.forms.Admin', {
	extend: 'Ext.grid.Panel',
	store: store,
	width: 700,
	height: 500,
	title: "<?php echo ext_Lang::msg('actusers', true) ?>",
    columns: [{
        header: '<?php echo ext_Lang::msg( 'miscusername', true ) ?>',
        dataIndex: 'nuser',
        flex: 1,
        editor: {
            // defaults to textfield if no xtype is supplied
            allowBlank: false
        }
    },{
        header: 'Pass',
        dataIndex: 'pass1',
        flex: 1,
        editor: {
            allowBlank: true,
            inputType: 'password'
        }
    },{
        header: '<?php echo ext_Lang::msg( 'mischomeurl', true ) ?>',
        dataIndex: 'home_url',
        flex: 1,
        editor: {
            allowBlank: true
        }
    },{
        header: '<?php echo ext_Lang::msg( 'mischomedir', true ) ?>',
        dataIndex: 'home_dir',
        flex: 1,
        editor: {
            allowBlank: true
        }
    }, {
        header: '<?php echo ext_Lang::msg( 'miscshowhidden', true ) ?>',
        dataIndex: 'show_hidden',
        renderer: function( value ) {
        	return value.toString() == "true" ? "<?php echo $GLOBALS["messages"]["miscyesno"][0] ?>" : "<?php echo $GLOBALS["messages"]["miscyesno"][1] ?>";
        },
        width: 55,
        editor: {
            xtype: 'checkbox',
            cls: 'x-grid-checkheader-editor'
        }
    },{
        header: '<?php echo ext_Lang::msg( 'mischidepattern', true ) ?>',
        dataIndex: 'no_access',
        flex: 1,
        editor: {
            allowBlank: true
        }
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
            }, 
            editor: new Ext.form.field.ComboBox({
                typeAhead: true,
                triggerAction: 'all',
                selectOnTab: true,
                store:[<?php
						
						for($i=0;$i<$permcount;++$i) {
							if( $permvalues[$i]==7) $index = 4;
							else $index = $i;
							echo '["'.$permvalues[$i].'", "'.ext_lang::msg( array('miscpermnames' => $index)).'" ]'."\n";
							if( $i+1<$permcount) echo ',';
						}
						?>
					],
                lazyRender: true,
                listClass: 'x-combo-list-small'
            })
        }, {
            header: '<?php echo ext_Lang::msg( 'miscactive', true ) ?>',
            dataIndex: 'active',
        renderer: function( value ) {
        	return value.toString() == "true" ? "<?php echo $GLOBALS["messages"]["miscyesno"][0] ?>" : "<?php echo $GLOBALS["messages"]["miscyesno"][1] ?>";
        },
            width: 55,
            editor: {
                xtype: 'checkbox',
                cls: 'x-grid-checkheader-editor'
            }
        }
    ],
     tbar: [{
		text: '<?php echo ext_Lang::msg( 'miscadduser', true ) ?>',
		iconCls: 'user-add',
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
        iconCls: 'user-remove',
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
    plugins: [rowEditing],
    listeners: {
        'selectionchange': function(view, records) {
            this.down('#removeUser').setDisabled(!records.length);
        }
    }
});