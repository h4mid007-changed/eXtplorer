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

     The Original Code is fun_edit.php, released on 2003-03-31.

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
	File-Edit Functions
	
	Have Fun...
------------------------------------------------------------------------------*/
class jx_Edit extends jx_Action {
	
	function execAction($dir, $item) {		// edit file
		global $mainframe, $mosConfig_live_site;
		
		$editor_mode = mosGetParam($_REQUEST,'editor_mode', 'simple');
		
		if(($GLOBALS["permissions"]&01)!=01) {
			jx_Result::sendResult('edit', false, $GLOBALS["error_msg"]["accessfunc"]);
		}
		$fname = get_abs_item($dir, $item);
		
		if(!get_is_file($fname))  {
			jx_Result::sendResult('edit', false, $item.": ".$GLOBALS["error_msg"]["fileexist"]);
		}
		if(!get_show_item($dir, $item)) {
			jx_Result::sendResult('edit', false, $item.": ".$GLOBALS["error_msg"]["accessfile"]);	
		}
		
		if(isset($GLOBALS['__POST']["dosave"]) && $GLOBALS['__POST']["dosave"]=="yes") {
			// Save / Save As
			$item=basename(stripslashes($GLOBALS['__POST']["fname"]));
			$fname2=get_abs_item($dir, $item);
			
			if(!isset($item) || $item=="") {
				jx_Result::sendResult('edit', false, $GLOBALS["error_msg"]["miscnoname"]);
			}
			if($fname!=$fname2 && @$GLOBALS['jx_File']->file_exists($fname2)) {
				jx_Result::sendResult('edit', false, $item.": ".$GLOBALS["error_msg"]["itemdoesexist"]);
			}
			  
			$this->savefile($fname2);
			$fname=$fname2;
			
			jx_Result::sendResult('edit', true, 'The File '.$item.' was saved.');
			
		}
		
		// header
		$s_item=get_rel_item($dir,$item);	if(strlen($s_item)>50) $s_item="...".substr($s_item,-47);
		$s_info = pathinfo( $s_item );
		$s_extension = str_replace('.', '', $s_info['extension'] );
		switch (strtolower($s_extension)) {
			case 'txt':
				$cp_lang = 'text'; break;
			case 'cs':
				$cp_lang = 'csharp'; break;
			case 'css':
				$cp_lang = 'css'; break;
			case 'html':
			case 'htm':
			case 'xml':
			case 'xhtml':
				$cp_lang = 'html'; break;
			case 'java':
				$cp_lang = 'java'; break;
			case 'js':
				$cp_lang = 'javascript'; break;
			case 'pl': 
				$cp_lang = 'perl'; break;
			case 'ruby': 
				$cp_lang = 'ruby'; break;
			case 'sql':
				$cp_lang = 'sql'; break;
			case 'vb':
			case 'vbs':
				$cp_lang = 'vbscript'; break;
			case 'php':
				$cp_lang = 'php'; break;
			default: 
				$cp_lang = 'generic';
		}
	?>
	<div style="width:auto;">
	    <div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>
	    <div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc">
	
	        <h3 style="margin-bottom:5px;"><?php 
	        	echo $GLOBALS["messages"]["actedit"].": /".$s_item .'&nbsp;&nbsp;&nbsp;&nbsp;';
	        	echo '[<a href="javascript:;" onclick="Ext.get(\'positionIndicator\').toggle(); ext_codefield.toggleEditor();return false;">'.$GLOBALS['messages']['editor_simple'].' / '.$GLOBALS['messages']['editor_syntaxhighlight'].'</a>]';
	        	?></h3>
	    <div id="positionIndicator">
		<?php echo $GLOBALS["messages"]["line"] ?>: <input type="text" value="" name="txtLine" id="txtLine" class="inputbox" size="6" onchange="setCaretPosition( codetextarea, this.value);return false;" />&nbsp;&nbsp;&nbsp;
		<?php echo $GLOBALS["messages"]["column"] ?>: <input type="text" value="" name="txtColumn" id="txtColumn" class="inputbox" size="6" readonly="readonly" />
		</div>
    <br />


	        <div id="adminForm">
	
	        </div>
	    </div></div></div>
	    <div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>
	</div>
	
	<?php	
	// Show File In TextArea
	$content = $GLOBALS['jx_File']->file_get_contents( $fname );
	if( get_magic_quotes_runtime()) {
		$content = stripslashes( $content );
	}
	//$content = htmlspecialchars( $content );
		
	?><script type="text/javascript">//<!--
	dialog.setContentSize( 700, 500 );
	simple = new Ext.form.Form({
	    labelAlign: 'top',
	    url:'index2.php'
	});
	simple.add(		
	    new Ext.form.TextArea({
	        fieldLabel: 'File Contents',
	        name: 'code',
	        id: 'ext_codefield',
	        fieldClass: 'codepress <?php echo $cp_lang ?> x-form-field',
	        value: '<?php echo str_replace(Array("\r", "\n", '<', '>'), Array('\r', '\n', '&lt;', '&gt;') , addslashes($content)) ?>',
	        width: 650,
	        height: 300
	    })		
	);
	simple.column( {width: 250 }, 
		new Ext.form.TextField({
	        fieldLabel: '<?php echo $GLOBALS["messages"]["copyfile"] ?>',
	        name: 'fname',
	        value: '<?php echo $item ?>',
	        width:175
		})
	);
	simple.column( {width: 250, style:'margin-left:10px', clear:true }, 
		new Ext.form.Checkbox({
	        fieldLabel: '<?php echo $GLOBALS["messages"]["returndir"] ?>',
	        name: 'return_to_dir',
	        width:175
		})
	);
	simple.addButton('<?php echo $GLOBALS["messages"]["btnsave"] ?>', function() {

	    simple.submit({
	        //waitMsg: 'Processing Data, please wait...',
	        //reset: true,
	        reset: false,
	        success: function(form, action) {
	        	datastore.reload();
	        	Ext.MessageBox.alert('Success!', action.result.message);
	        	if( simple.findField('return_to_dir').getValue() ) {
	        		dialog.hide();dialog.destroy();
	        	}
	        },
	        failure: function(form, action) {
	        	Ext.MessageBox.alert('Error!', action.result.error);
	        },
	        scope: simple,
	        // add some vars to the request, similar to hidden fields
	        params: {option: 'com_joomlaxplorer', 
	        		action: 'edit', 
	        		code: ext_codefield.getCode(),
	        		dir: '<?php echo stripslashes($GLOBALS['__POST']["dir"]) ?>', 
	        		item: '<?php echo stripslashes($GLOBALS['__POST']["item"]) ?>', 
	        		dosave: 'yes'
	        }
	    });
	});
	
	simple.addButton('<?php echo $GLOBALS["messages"]["btnclose"] ?>', function() { dialog.destroy(); } );
	simple.render('adminForm');
	simple.findField('code').setValue(simple.findField( 'code').getValue().replace( /&gt;/g, '>').replace( /&lt;/g, '<'));
	CodePress.run();
	//Ext.get('positionIndicator').setVisibilityMode(Element.DISPLAY);
	//Ext.get('positionIndicator').setDisplayed('none');
	Ext.get('positionIndicator').hide();
	codetextarea = ext_codefield.textarea;
	Ext.EventManager.addListener( codetextarea, 'keyup', function() { updatePosition( codetextarea ) } );
	Ext.EventManager.addListener( codetextarea, 'click', function() { updatePosition( codetextarea ) } );
	// -->
	</script><?php
	
	}
	function savefile($file_name) {			// save edited file
		if( get_magic_quotes_gpc() ) {
			$code = stripslashes($GLOBALS['__POST']["code"]);
		}
		else {
			$code = $GLOBALS['__POST']["code"];
		}
		
		$res = $GLOBALS['jx_File']->file_put_contents( $file_name, $code );
		
		if( $res==false || PEAR::isError( $res )) {
			$err = basename($file_name).": ".$GLOBALS["error_msg"]["savefile"];
			if( PEAR::isError( $res ) ) {
				$err .= $res->getMessage();
			}
			jx_Result::sendResult( 'edit', false, $err.print_r( $_POST, true ) );
		}
		
	}
}
//------------------------------------------------------------------------------
?>
