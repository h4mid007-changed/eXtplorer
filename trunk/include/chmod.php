<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: $
 * @package joomlaXplorer
 * @copyright soeren 2007
 * @author The joomlaXplorer project (http://joomlacode.org/gf/project/joomlaxplorer/)
 * @author The  The QuiX project (http://quixplorer.sourceforge.net)
 * 
 * @license
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * Alternatively, the contents of this file may be used under the terms
 * of the GNU General Public License Version 2 or later (the "GPL"), in
 * which case the provisions of the GPL are applicable instead of
 * those above. If you wish to allow use of your version of this file only
 * under the terms of the GPL and not to allow others to use
 * your version of this file under the MPL, indicate your decision by
 * deleting  the provisions above and replace  them with the notice and
 * other provisions required by the GPL.  If you do not delete
 * the provisions above, a recipient may use your version of this file
 * under either the MPL or the GPL."
 * 
 * 
 */

/**
 * Permission-Change Functions
 *
 */
class jx_Chmod extends jx_Action {
	function execAction($dir, $item) {		// change permissions

		if(($GLOBALS["permissions"]&01)!=01) jx_Result::sendResult( 'chmod', false, $GLOBALS["error_msg"]["accessfunc"]);
		
		if( !empty($GLOBALS['__POST']["selitems"])) {
			$cnt=count($GLOBALS['__POST']["selitems"]);
	
		}
		else {
			$GLOBALS['__POST']["selitems"][]  = $item;
			$cnt = 1;
		}
		if( !empty($GLOBALS['__POST']['do_recurse'])) {
			$do_recurse = true;
		}
		else {
			$do_recurse = false;
		}
	
		// Execute
		if(isset($GLOBALS['__POST']["confirm"]) && $GLOBALS['__POST']["confirm"]=="true") {
			$bin='';
			for($i=0;$i<3;$i++) for($j=0;$j<3;$j++) {
				$tmp="r_".$i.$j;
				if(!empty($GLOBALS['__POST'][$tmp]) ) {
					$bin.='1';
				}
				else {
					$bin.='0';
				}
			}
			if( $bin == '0') { // Changing permissions to "none" is not allowed
				jx_Result::sendResult('chmod', false, $item.": Changing Permissions to <none> is not allowed");
			}
			$old_bin = $bin;
			for($i=0;$i<$cnt;++$i) {
				if( jx_isFTPMode() ) {
					$mode = decoct(bindec($bin));
				} else {
					$mode = bindec($bin);
				}
				$item = $GLOBALS['__POST']["selitems"][$i];
				if( jx_isFTPMode() ) {
					$abs_item = get_item_info( $dir,$item);
				} else {
					$abs_item = get_abs_item($dir,$item);
				}
				if(!$GLOBALS['jx_File']->file_exists( $abs_item )) {
					jx_Result::sendResult('chmod', false, $item.": ".$GLOBALS["error_msg"]["fileexist"]);
				}
				if(!get_show_item($dir, $item)) {
					jx_Result::sendResult('chmod', false, $item.": ".$GLOBALS["error_msg"]["accessfile"]);
				}
				if( $do_recurse ) {
					$ok = $GLOBALS['jx_File']->chmodRecursive( $abs_item, $mode );
				}
				else {
					if( get_is_dir( $abs_item )) {
						// when we chmod a directory we must care for the permissions
						// to prevent that the directory becomes not readable (when the "execute bits" are removed)
						$bin = substr_replace( $bin, '1', 2, 1 ); // set 1st x bit to 1
						$bin = substr_replace( $bin, '1', 5, 1 );// set  2nd x bit to 1
						$bin = substr_replace( $bin, '1', 8, 1 );// set 3rd x bit to 1
						if( jx_isFTPMode() ) {
							$mode = decoct(bindec($bin));
						} else {
							$mode = bindec($bin);
						}
					}
					$ok = @$GLOBALS['jx_File']->chmod( $abs_item, $mode );
				}
	
				$bin = $old_bin;
			}
			if(!$ok || PEAR::isError( $ok ) ) {
				jx_Result::sendResult('chmod', false, $abs_item.": ".$GLOBALS["error_msg"]["permchange"]);
			}
			jx_Result::sendResult('chmod', true, 'Permissions were changed' );
			return;
		}
		if( jx_isFTPMode() ) {
			$abs_item = get_item_info( $dir, $GLOBALS['__POST']["selitems"][0]);
		} else {
			$abs_item = get_abs_item( $dir, $GLOBALS['__POST']["selitems"][0]);
		}
		$mode = parse_file_perms(get_file_perms( $abs_item ));
		if($mode===false) {
			jx_Result::sendResult('chmod', false, $abs_item.": ".$GLOBALS["error_msg"]["permread"]);
		}
		$pos = "rwx";
		$text = "";
		for($i=0;$i<$cnt;++$i) {		
			$s_item=get_rel_item($dir,$GLOBALS['__POST']["selitems"][$i]);
			if(strlen($s_item)>50) $s_item="...".substr($s_item,-47);
			$text .= $s_item.($i+1<$cnt ? ', ':'');
		}
		?>
	<div style="width:auto;">
	    <div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>
	    <div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc">
	
	        <h3 style="margin-bottom:5px;"><?php echo $GLOBALS["messages"]["actperms"] ?></h3>
	        <?php echo $text ?>
	        <div id="adminForm">
	
	        </div>
	    </div></div></div>
	    <div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>
	</div>
	<script type="text/javascript">
	var form = new Ext.form.Form({
	    labelWidth: 125, // label settings here cascade unless overridden
	    url:'index2.php'
	});
	
	<?php
		// print table with current perms & checkboxes to change
		for($i=0;$i<3;++$i) {
			?>
			form.column(
		        {width:70, style:'margin-left:10px', clear:true}
		    );
			form.fieldset(
			        {legend:'<?php echo jx_Lang::msg(array('miscchmod'=> $i ), true ) ?>', hideLabels:true},
			        <?php
			        for($j=0;$j<3;++$j) {
			        	?>
				        new Ext.form.Checkbox({
				            boxLabel:'<?php echo $pos{$j} ?>',
				            <?php if($mode{(3*$i)+$j} != "-") echo 'checked:true,' ?>
				            name:'<?php echo "r_". $i.$j ?>'
				        })     <?php
			        	if( $j<2 ) echo ',';
			        }
			        ?>   );
	    	form.end();
	    <?php 
		}
		?>
	form.column(
	        {width:400, style:'margin-left:10px', clear:true}
	    );
	form.add(new Ext.form.Checkbox({
		fieldLabel:'<?php echo jx_Lang::msg('recurse_subdirs', true ) ?>',
		name:'do_recurse'
	}));
	form.end();
	
	form.addButton('<?php echo jx_Lang::msg( 'btnsave', true ) ?>', function() {
	    form.submit({
	        waitMsg: 'Applying Permissions, please wait...',
	        //reset: true,
	        reset: false,
	        success: function(form, action) {
	        	Ext.MessageBox.alert('Success!', action.result.message);
	        	datastore.reload();
	    		dialog.hide();
	        	dialog.destroy();
	        },
	        failure: function(form, action) {Ext.MessageBox.alert('Error!', action.result.error);},
	        scope: form,
	        // add some vars to the request, similar to hidden fields
	        params: {option: 'com_joomlaxplorer', 
	        		action: 'chmod', 
	        		dir: '<?php echo stripslashes($GLOBALS['__POST']["dir"]) ?>', 
	        		'selitems[]': ['<?php echo implode("','", $GLOBALS['__POST']["selitems"]) ?>'], 
	        		confirm: 'true'}
	    });
	});
	form.addButton('<?php echo jx_Lang::msg( 'btncancel', true ) ?>', function() { dialog.hide();dialog.destroy(); } );
	form.render('adminForm');
	</script>
	
		<?php
	}
}
//------------------------------------------------------------------------------
?>