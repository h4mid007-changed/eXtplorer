<?php
/** ensure this file is being included by a parent file */
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/*------------------------------------------------------------------------------
The contents of this file are subject to the Mozilla Public License
Version 1.1 (the "License"); you may not use this file except in
compliance with the License. You may obtain a copy of the License at
http://www.mozilla.org/MPL/

Software distributed under the License is distributed on an "AS IS"
basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
License for the specific language governing rights and limitations
under the License.

The Original Code is fun_archive.php, released on 2003-03-31.

The Initial Developer of the Original Code is The QuiX project.

Alternatively, the contents of this file may be used under the terms
of the GNU General Public License Version 2 or later (the "GPL"), in
which case the provisions of the GPL are applicable instead of
those above. If you wish to allow use of your version of this file only
under the terms of the GPL and not to allow others to use
your version of this file under the MPL, indicate your decision by
deleting  the provisions above and replace  them with the notice and
other provisions required by the GPL.  If you do not delete
the provisions above, a recipient may use your version of this file
under either the MPL or the GPL."
------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------
Author: The QuiX project
quix@free.fr
http://www.quix.tk
http://quixplorer.sourceforge.net

Comment:
QuiXplorer Version 2.3
Zip & TarGzip Functions

Have Fun...
------------------------------------------------------------------------------*/

class jx_Archive extends jx_Action {

	function execAction( $dir ) {
		global $mosConfig_absolute_path;
		if(($GLOBALS["permissions"]&01)!=01) {
			jx_Result::sendResult('archive', false, $GLOBALS["error_msg"]["accessfunc"]);
		}
		if(!$GLOBALS["zip"] && !$GLOBALS["tgz"]) {
			jx_Result::sendResult('archive', false, $GLOBALS["error_msg"]["miscnofunc"]);
		}

		$allowed_types = array( 'zip', 'tgz', 'tbz', 'tar' );

		// If we have something to archive, let's do it now
		if(mosGetParam($_POST, 'confirm' ) == 'true' ) {
			$saveToDir = $GLOBALS['__POST']['saveToDir'];
			if( !file_exists( get_abs_dir($saveToDir ) )) {
				jx_Result::sendResult('archive', false, 'The Save-To Directory you have specified does not exist.');
			}
			if( !is_writable( get_abs_dir($saveToDir ) )) {
				jx_Result::sendResult('archive', false, 'Please specify a writable directory to save the archive to.');
			}
			require_once( _JX_PATH .'/libraries/Archive.php' );

			if( !in_array(strtolower( $GLOBALS['__POST']["type"] ), $allowed_types )) {
				jx_Result::sendResult('archive', false, 'Unknown Archive Format: '.htmlspecialchars($GLOBALS['__POST']["type"]));

			}

			$files_per_step = 500;

			$cnt=count($GLOBALS['__POST']["selitems"]);
			$abs_dir=get_abs_dir($dir);
			$name=basename(stripslashes($GLOBALS['__POST']["name"]));
			if($name=="") {
				jx_Result::sendResult('archive', false, $GLOBALS["error_msg"]["miscnoname"]);
			}

			$download = mosGetParam( $_REQUEST, 'download', "n" );
			$startfrom = mosGetParam( $_REQUEST, 'startfrom', 0 );

			$archive_name = get_abs_item($saveToDir,$name);
			$fileinfo = pathinfo( $archive_name );

			if( empty( $fileinfo['extension'] )) {
				$archive_name .= ".".$GLOBALS['__POST']["type"];
				$fileinfo['extension'] = $GLOBALS['__POST']["type"];

				foreach( $allowed_types as $ext ) {
					if( $GLOBALS['__POST']["type"] == $ext && @$fileinfo['extension'] != $ext ) {
						$archive_name .= ".".$ext;
					}
				}
			}

			// Tar/Gz and Tar/Bzip2 Archives must be treated as Tar first, after adding files has been finished we pack the files
			if( $GLOBALS['__POST']["type"] == 'tgz' || $GLOBALS['__POST']["type"] == 'tbz') {
				$archive_name = $fileinfo['dirname'].$GLOBALS["separator"].$fileinfo['basename'] . '.tar';
			}

			for($i=0;$i<$cnt;$i++) {

				$selitem=stripslashes($GLOBALS['__POST']["selitems"][$i]);

				if( is_dir( $abs_dir ."/". $selitem )) {
					$items = mosReadDirectory($abs_dir ."/".  $selitem, '.', true, true );
					foreach ( $items as $item ) {
						if( is_dir( $item ) || !is_readable( $item )) continue;
						$v_list[] = $item;
					}
				}
				else {
					$v_list[] = $abs_dir ."/". $selitem;
				}
			}
			$cnt_filelist = count( $v_list );

			$remove_path = $GLOBALS["home_dir"];
			if( $dir ) {
				$remove_path .= $dir.$GLOBALS['separator'];
			}
			for( $i=$startfrom;$i < $cnt_filelist && $i < ($startfrom + $files_per_step); $i++ ) {

				$filelist[] = File_Archive::read( $v_list[$i], str_replace($remove_path, '', $v_list[$i] ) );

			}
			//echo '<strong>Starting from: '.$startfrom.'</strong><br />';
			//echo '<strong>Files to process: '.$cnt_filelist.'</strong><br />';
			//print_r( $filelist );exit;

			// Do some setup stuff
			ini_set('memory_limit', '128M');
			@set_time_limit( 0 );
			error_reporting( E_ERROR | E_PARSE );

			$result = File_Archive::extract( $filelist, $archive_name );

			if( PEAR::isError( $result ) ) {
				jx_Result::sendResult('archive', false, $name.": Failed saving Archive File. Error: ".$result->getMessage() );
			}
			$json = new Services_JSON();
			if( $cnt_filelist > $startfrom+$files_per_step ) {

				$response = Array( 'startfrom' => $startfrom + $files_per_step,
				'success' => true,
				'action' => 'archive',
				'message' => sprintf( $GLOBALS['messages']['processed_x_files'], $startfrom + $files_per_step, $cnt_filelist )
				);
			}
			else {
				if( $GLOBALS['__POST']["type"] == 'tgz' || $GLOBALS['__POST']["type"] == 'tbz') {
					$compressed_archive_name = $fileinfo['dirname'].$GLOBALS["separator"].basename($archive_name, '.'.$GLOBALS['__POST']["type"].'.tar').'.'.$GLOBALS['__POST']["type"];
					$source = File_Archive::read($archive_name . '/' );
					File_Archive::extract( $source, $compressed_archive_name );
					unlink( $archive_name );
					chmod( $compressed_archive_name, 0644 );
					$archive_name = $compressed_archive_name;
				}
				$response = Array( 'action' => 'archive',
				'success' => true,
				'message' => 'The Archive File has been created!',
				'newlocation' => make_link( 'download', $dir, basename($archive_name) )
				);

			}

			echo $json->encode( $response );
			jx_exit();
		}
	?>
<div style="width:auto;">
    <div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>
    <div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc">

        <h3 style="margin-bottom:5px;"><?php echo $GLOBALS["messages"]["actarchive"] ?></h3>
        
        <div id="adminForm"></div>
    </div></div></div>
    <div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>
</div>
	<script type="text/javascript">
	var comprTypes = new Ext.data.SimpleStore({
		fields: ['type', 'typename'],
		data :  [
		['zip', 'Zip (<?php echo $GLOBALS["messages"]['normal_compression'] ?>)'],
		['tgz', 'Tar/Gz (<?php echo $GLOBALS["messages"]['good_compression']?>)'],
		<?php
		if(extension_loaded("bz2")) {
			echo "['tbz', 'Tar/Bzip2 ({$GLOBALS["messages"]['best_compression']})'],";
		}
		?>
		['tar', 'Tar (<?php echo $GLOBALS["messages"]['no_compression'] ?>)']
		]
	});
	var form = new Ext.form.Form({
		labelWidth: 125, // label settings here cascade unless overridden
		url:'index.php'
	});
	var combo = new Ext.form.ComboBox({
		fieldLabel: '<?php echo $GLOBALS["messages"]["typeheader"] ?>',
		store: comprTypes,
		displayField:'typename',
		valueField: 'type',
		name: 'type',
		hiddenName: 'type',
		disableKeyFilter: true,
		editable: false,
		mode: 'local',
		allowBlank: false,
		selectOnFocus:true,
		width: 200
	});
	form.add( new Ext.form.TextField({
		fieldLabel: '<?php echo $GLOBALS['messages']['archive_name'] ?>',
		name: 'name',
		width: 200
	}),
	combo,
	new Ext.form.TextField({
		fieldLabel: '<?php echo $GLOBALS['messages']['archive_saveToDir'] ?>',
		name: 'saveToDir',
		value: '<?php echo str_replace("'", "\'", $dir ) ?>',
		width: 200
	}),
	new Ext.form.Checkbox({
		fieldLabel: '<?php echo $GLOBALS["messages"]["downlink"] ?>?',
		name: 'download',
		checked: true
	})
	);
	combo.on('select', function(o, record ) {

		var nameField = form.findField('name').getValue();
		if( nameField.indexOf( '.' ) > 0 ) {
			form.findField('name').setValue( nameField.substring( 0, nameField.indexOf('.')+1 ) + record.get('type') );
		} else {
			form.findField('name').setValue( nameField + '.'+ record.get('type'));
		}
	});

	form.addButton('Save', function() { formSubmit(0) });
	form.addButton('Cancel', function() { dialog.hide();dialog.destroy(); } );

	form.render('adminForm');

	function formSubmit( startfrom ) {
		form.submit({
			waitMsg: 'Creating Archive, please wait...',
			//reset: true,
			reset: false,
			success: function(form, action) {
				if( action.result.startfrom ) {
					formSubmit( startfrom );
					return
				} else {

					if( form.findField('download').getValue() ) {
						datastore.reload();
						location.href = action.result.newlocation;
						dialog.hide();
						dialog.destroy();
					} else {
						Ext.MessageBox.alert('Success!', action.result.message);
						datastore.reload();
						dialog.hide();
						dialog.destroy();
					}
					alert( location.href );
					return;
				}
			},
			failure: function(form, action) {Ext.MessageBox.alert('Error!', action.result.error);},
			scope: form,
			// add some vars to the request, similar to hidden fields
			params: {option: 'com_joomlaxplorer',
			action: 'archive',
			dir: '<?php echo stripslashes($GLOBALS['__POST']["dir"]) ?>',
			'selitems[]':  [ '<?php echo implode("','", $GLOBALS['__POST']["selitems"]) ?>' ],
			startfrom: startfrom,
			confirm: 'true'}
		});
	}

	</script>

	<?php
	}
}
//------------------------------------------------------------------------------
?>
