<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id$
 * @package eXtplorer
 * @copyright soeren 2007
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
 * @author The	The QuiX project (http://quixplorer.sourceforge.net)
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
 * File-Edit Functions
 *
 */
class ext_Edit extends ext_Action {
	var	$lang_tbl = Array(
		'czech' => 'cs',
		'german' => 'de',
		'danish' => 'dk',
		'english' => 'en',
		'esperanto' => 'eo',
		'spanish' => 'es',
		'french' => 'fr',
		'croatian' => 'hr',
		'italian' => 'it',
		'japanese' => 'ja',
		'macedonian' => 'mk',
		'dutch' => 'nl',
		'polish' => 'pl',
		'portuguese' => 'pt',
		'russian' => 'ru',
		'slovenian' => 'sk'
	);

	function execAction($dir, $item) {		// edit file
		global $mainframe, $mosConfig_live_site;

		if(($GLOBALS["permissions"]&01)!=01) {
			ext_Result::sendResult('edit', false, ext_Lang::err('accessfunc' ));
		}
		$fname = get_abs_item($dir, $item);

		if(!get_is_file(utf8_decode($fname)))  {
			ext_Result::sendResult('edit', false, $item.": ".ext_Lang::err('fileexist' ));
		}
		if(!get_show_item($dir, $item)) {
			ext_Result::sendResult('edit', false, $item.": ".ext_Lang::err('accessfile' ));
		}

		if(isset($GLOBALS['__POST']["dosave"]) && $GLOBALS['__POST']["dosave"]=="yes") {
			// Save / Save As
			$item=basename(stripslashes($GLOBALS['__POST']["fname"]));
			$fname2=get_abs_item($dir, $item);

			if(!isset($item) || $item=="") {
				ext_Result::sendResult('edit', false, ext_Lang::err('miscnoname' ));
			}
			if($fname!=$fname2 && @$GLOBALS['ext_File']->file_exists($fname2)) {
				ext_Result::sendResult('edit', false, $item.": ".ext_Lang::err('itemdoesexist' ));
			}

			$this->savefile($fname2);
			$fname=$fname2;

			ext_Result::sendResult('edit', true, ext_Lang::msg('savefile').': '.$item );

		}
		if(isset($GLOBALS['__POST']["doreopen"]) && $GLOBALS['__POST']["doreopen"]=="yes") {
			// File Reopen
			$extra = Array();
			$content = $GLOBALS['ext_File']->file_get_contents( $fname );
			if( get_magic_quotes_runtime()) {
				$content = stripslashes( $content );
			}

			$langs = $GLOBALS["language"];
			if ($langs == "japanese"){
				$_encoding = $GLOBALS['__POST']["file_encoding"];
				if ($content){
					$content = mb_convert_encoding($content, "UTF-8", $_encoding);
				}
				$extra["file_encoding"] = $_encoding;
			}

			$extra["content"] = $content;

			ext_Result::sendResult('edit', true, ext_Lang::msg('reopenfile').': '.$item, $extra);

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
	$content = $GLOBALS['ext_File']->file_get_contents( $fname );
	if( get_magic_quotes_runtime()) {
		$content = stripslashes( $content );
	}
	$cw = 250;
	$langs = $GLOBALS["language"];
	if ($langs == "japanese"){
		$cw = 200;
		if ($content){
			$_encoding = strtoupper(mb_detect_encoding($content, Array("ASCII", "ISO-2022-JP", "UTF-8", "EUCJP-WIN", "SJIS-WIN"), true));
			$content = mb_convert_encoding($content, "UTF-8", $_encoding);
			if ($_encoding == "SJIS-WIN"){
				$_encoding_label = "SJIS";
			} elseif ($_encoding == "EUCJP-WIN"){
				$_encoding_label = "EUC-JP";
			} elseif ($_encoding == "ISO-2022-JP"){
				$_encoding_label = "JIS";
			} elseif ($_encoding == "ASCII"){
				$_encoding_label = "UTF-8";
			} else {
				$_encoding_label = $_encoding;
			}
		} else {
			$_encoding_label = "UTF-8";
		}
	}
	?>
{
	"xtype": "form",
	"id": "simpleform",
	"labelWidth": 125,
	"height": 500,
	"width": 700,
	"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
	"dialogtitle": "<?php echo $GLOBALS["messages"]["actedit"].": /".$s_item .'&nbsp;&nbsp;&nbsp;&nbsp;' ?>",
	"frame": true,
	"items": [{

		"xtype": "textarea",
		"hideLabel": true,
		"name": "thecode",
		"id": "ext_codefield",
		"fieldClass": "x-form-field",
		"value": '<?php echo str_replace(Array("\r", "\n"), Array('\r', '\n') , addslashes($content)) ?>',
		"width": "100%",
		"height": 300,
		"plugins": new Ext.ux.plugins.EditAreaEditor({
			"id" : "ext_codefield",	
			"syntax": "<?php echo $cp_lang ?>",
			"start_highlight": true,
			"display": "later",
			"toolbar": "search, go_to_line, |, undo, redo, |, select_font,|, change_smooth_selection, highlight, reset_highlight, |, help" 
			<?php if (array_key_exists($langs, $this->lang_tbl)){?>
				,language: "<?php echo $this->lang_tbl[$langs] ?>"
				<?php 
				} ?>
		})
	},
	{
		
			width: <?php echo $cw ?>, 
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg('copyfile', true ) ?>",
			"name": "fname",
			"value": "<?php echo $item ?>",
			"clear": true
			},
			{
			"width": <?php echo $cw ?>, 
			"style":"margin-left:10px",
			"clear":true,
			"xtype": "checkbox",
			"fieldLabel": "<?php echo ext_Lang::msg('returndir', true ) ?>",
			"name": "return_to_dir",
			}
<?php if ($langs == "japanese"){ ?>
			,{
			 "width": <?php echo $cw ?>,  
			 "style":"margin-left:10px", 
			 "clear":true,
			"xtype": "combo",
			"fieldLabel": "<?php echo ext_Lang::msg('fileencoding', true ) ?>",
			"name": "file_encoding",
			"store": [
						['UTF-8', 'UTF-8'],
						['SJIS-WIN', 'SJIS'],
						['EUCJP-WIN', 'EUC-JP'],
						['ISO-2022-JP','JIS']
					],
			"value" : "<?php echo $_encoding_label ?>",
			"typeAhead": true,
			"mode": "local",
			"triggerAction": "all",
			"editable": false,
			"forceSelection": true
			}
	
<?php } ?>
		],
	"buttons": [{
		"text": "<?php echo ext_Lang::msg('btnsave', true ) ?>", 
		"handler": function() {
			statusBarMessage( '<?php echo ext_Lang::msg('save_processing', true ) ?>', true );
			form = Ext.getCmp("simpleform").getForm();
			form.submit({
				//waitMsg: 'Processing Data, please wait...',
				//reset: true,
				reset: false,
				success: function(form, action) {
					datastore.reload();
					statusBarMessage( action.result.message, false, true );
					if( form.findField('return_to_dir').getValue() ) {
						Ext.getCmp("dialog").destroy();
					}
				},
				failure: function(form, action) {
					statusBarMessage( action.result.error, false, false );
					Ext.Msg.alert('<?php echo ext_Lang::err('error', true) ?>!', action.result.error);
				},
				scope: form,
				// add some vars to the request, similar to hidden fields
				params: {option: 'com_extplorer', 
						action: 'edit', 
						code: editAreaLoader.getValue("ext_codefield"),
						dir: '<?php echo stripslashes($dir) ?>', 
						item: '<?php echo stripslashes($item) ?>', 
						dosave: 'yes'
				}
			});
		}
	},{
		"text": "<?php echo ext_Lang::msg('btnclose', true ) ?>", 
		"handler": function() { Ext.getCmp("dialog").destroy(); }
	},{
		"text": "<?php echo ext_Lang::msg('btnreopen', true ) ?>", 
		"handler": function() { 
			statusBarMessage( '<?php echo ext_Lang::msg('reopen_processing', true ) ?>', true );
			form = Ext.getCmp("simpleform").getForm();
			form.submit({
				//waitMsg: 'Processing Data, please wait...',
				//reset: true,
				reset: false,
				success: function(form, action) {
					statusBarMessage( action.result.message, false, true );
					editAreaLoader.setValue("ext_codefield", action.result.content);
				},
				failure: function(form, action) {
					statusBarMessage( action.result.error, false, false );
					Ext.Msg.alert('<?php echo ext_Lang::err('error', true) ?>!', action.result.error);
				},
				scope: form,
				// add some vars to the request, similar to hidden fields
				params: {
					option: 'com_extplorer', 
					action: 'edit', 
					dir: '<?php echo stripslashes($dir) ?>', 
					item: '<?php echo stripslashes($item) ?>', 
					doreopen: 'yes'
				}
			});
		}
	}]
}
	
<?php

	}
	function savefile($file_name) {			// save edited file
		if( get_magic_quotes_gpc() ) {
			$code = stripslashes($GLOBALS['__POST']["code"]);
		}
		else {
			$code = $GLOBALS['__POST']["code"];
		}
		$langs = $GLOBALS["language"];
		if ($langs == "japanese"){
			$_encoding = $GLOBALS['__POST']["file_encoding"];
			if ($_encoding != "UTF-8"){
				$code = mb_convert_encoding($code, $_encoding, "UTF-8");
			}
		}

		$res = $GLOBALS['ext_File']->file_put_contents( $file_name, $code );

		if( $res==false || PEAR::isError( $res )) {
			$err = basename($file_name).": ".ext_Lang::err('savefile' );
			if( PEAR::isError( $res ) ) {
				$err .= $res->getMessage();
			}
			ext_Result::sendResult( 'edit', false, $err );
		}

	}
}
//------------------------------------------------------------------------------
?>
