function showLoadingIndicator( el, replaceContent ) {
	if( !el ) return;
	var loadingimg = 'components/com_joomlaxplorer/images/indicator.gif';
	var imgtag = '<img src="'+ loadingimg + '" alt="Loading..." border="0" name="Loading" align="absmiddle" />';
	
	if( replaceContent ) {
		el.innerHTML = imgtag;
	}
	else {
		el.innerHTML += imgtag;
	}
}
function getURLParam( strParamName, myWindow){
	if( !myWindow ){
		myWindow = window;
	}
  var strReturn = "";
  var strHref = myWindow.location.href;
  if ( strHref.indexOf("?") > -1 ){
    var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
    var aQueryString = strQueryString.split("&");
    for ( var iParam = 0; iParam < aQueryString.length; iParam++ ){
      if ( aQueryString[iParam].indexOf(strParamName + "=") > -1 ){
        var aParam = aQueryString[iParam].split("=");        
        strReturn = aParam[1];
        break;
      }
    }
  }
  return strReturn;
}

function openActionDialog( caller, action ) {
	var selectedRows = jx_itemgrid.getSelectionModel().getSelections();
	if( selectedRows.length < 1 ) {
		var selectedNode = dirTree.getSelectionModel().getSelectedNode();
		if( selectedNode ) {
			selectedRows = Array( dirTree.getSelectionModel().getSelectedNode().id.replace( /_RRR_/g, '/' ) );
		}
	}
	var dontNeedSelection = { new:1, get_about:1, ftp_authentication:1, upload:1 };
	if( dontNeedSelection[action] == null  && selectedRows.length < 1 ) {
		Ext.MessageBox.alert( '<?php echo $GLOBALS['error_msg']['error']."','".addslashes($GLOBALS['error_msg']['miscselitems']) ?>');
		return false;
	}

	switch( action ) {
		case 'search':
		case 'view':
		case 'mkitem':
		case 'copy':
		case 'move':
		case 'edit':
		case 'rename':
		case 'chmod':
		case 'upload':
		case 'archive':
		case 'ftp_authentication':
		case 'get_about':
			requestParams = getRequestParams();
			requestParams.action = action, 
									
            dialog = new Ext.LayoutDialog("action-dlg", { 
                    autoCreate: true,
                    modal:true,
                    width:600,
                    height:400,
                    shadow:true,
                    minWidth:300,
                    minHeight:300,
                    proxyDrag: true,
                    center: {
                        autoScroll:true
                    },
                    //animateTarget: typeof caller.getEl == 'function' ? caller.getEl() : caller,
					title: action
					
            });
            dialog.addKeyListener(27, dialog.hide, dialog);
			var dialog_panel = new Ext.ContentPanel('dialog-center', {
									autoCreate: true,
									autoScroll:true,
									fitToFrame: true
								});

			dialog_panel.load( { url: 'index3.php', 
								params: Ext.urlEncode( requestParams ),
								scripts: true,
								callback: function(oElement, bSuccess, oResponse) {
											if( oResponse && oResponse.responseText ) {
											try{ json = Ext.decode( oResponse.responseText );
												if( json.error != '' && typeof json.error != 'xml' ) {													
													Ext.MessageBox.alert( '<?php echo $GLOBALS['error_msg']['error'] ?>', json.error );
													dialog.destroy();
												}
											} catch(e) {}
											}
										}
							});
            var layout = dialog.getLayout();
            layout.beginUpdate();
            layout.add('center', dialog_panel );
            layout.endUpdate();
            
            dialog.show();
            break;
            
		case 'delete':
			var num = selectedRows.length;
			Ext.MessageBox.confirm('<?php echo $GLOBALS['messages']['dellink'] ?>?', "<?php echo $GLOBALS['error_msg']['miscdelitems'] ?>", deleteFiles);
			break;
		case 'extract':
			Ext.MessageBox.confirm('<?php echo $GLOBALS['messages']['extractlink'] ?>?', "<?php echo $GLOBALS['messages']['extract_warning'] ?>", extractArchive);
			break;
		case 'download':
			document.location = 'index3.php?option=com_joomlaxplorer&action=download&item='+ encodeURIComponent(jx_itemgrid.getSelectionModel().getSelected().get('name')) + '&dir=' + encodeURIComponent( datastore.directory );
			break;
	}
}
function handleCallback(requestParams, node) {
	var conn = new Ext.data.Connection();
	
	conn.request({
		url: 'index3.php',
		params: requestParams,
		callback: function(options, success, response ) {
			if( success ) {
				json = Ext.decode( response.responseText );
				if( json.success ) {
					Ext.MessageBox.alert( 'Success', json.message );
					try { 
						if( dropEvent) {
							dropEvent.target.parentNode.reload();
							dropEvent = null;
						}
						if( node ) {
							if( options.params.action == 'delete' || options.params.action == 'rename' ) {
								node.parentNode.select();
							}
							node.parentNode.reload();
						} else {
							datastore.reload();
						}
					} catch(e) { datastore.reload(); }
				} else {
					Ext.MessageBox.alert( 'Failure', json.error );
				}
			}
			else {
				Ext.MessageBox.alert( 'Error', 'Failed to connect to the server.');
			}
		}
	});
}
function getRequestParams() {
	var selitems, dir, node;
	var selectedRows = jx_itemgrid.getSelectionModel().getSelections();
	if( selectedRows.length < 1 ) {
		node = dirTree.getSelectionModel().getSelectedNode();
		if( node ) {
			var dir = dirTree.getSelectionModel().getSelectedNode().id.replace( /_RRR_/g, '/' );
			var lastSlash = dir.lastIndexOf( '/' );
			if( lastSlash > 0 ) {
				selitems = Array( dir.substring(lastSlash+1) );
			} else {
				selitems = Array( dir );
			}
		} else {
			selitems = {};
		}
		dir = datastore.directory.substring( 0, datastore.directory.lastIndexOf('/'));
	}
	else {
		selitems = Array(selectedRows.length);
	
		if( selectedRows.length > 0 ) {
			for( i=0; i < selectedRows.length;i++) {
				selitems[i] = selectedRows[i].get('name');
			}
		}
		dir = datastore.directory;
	}

	var requestParams = { 
		option: 'com_joomlaxplorer', 
		dir: dir,
		item: selitems.length > 0 ? selitems[0]:'',
		'selitems[]': selitems
	};
	return requestParams;
}
/**
* Function for actions, which don't require a form like download, extraction, deletion etc.
*/
function deleteFiles(btn) {
	if( btn != 'yes') {
		return;
	}
	requestParams = getRequestParams();
	requestParams.action = 'delete';
	handleCallback(requestParams);
}
function extractArchive(btn) {
	if( btn != 'yes') {
		return;
	}
	requestParams = getRequestParams();
	requestParams.action = 'extract';
	handleCallback(requestParams);
}
function deleteDir( node ) {
	requestParams = getRequestParams();
	requestParams.dir = datastore.directory.substring( 0, datastore.directory.lastIndexOf('/'));
	requestParams.selitems = Array( node.id.replace( /_RRR_/g, '/' ) );
	requestParams.action = 'delete';
	handleCallback(requestParams, node);
}

/**
*	Debug Function, that works like print_r for Objects in Javascript
*/
function var_dump(obj) {
	var vartext = "";
	for (var prop in obj) {
		if( isNaN( prop.toString() )) {
			vartext += "\t->"+prop+" = "+ eval( "obj."+prop.toString()) +"\n";
		}
    }
   	if(typeof obj == "object") {
    	return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "") + "\n" + vartext;
   	} else {
      	return "Type: "+typeof(obj)+"\n" + vartext;
	}
}//end function var_dump