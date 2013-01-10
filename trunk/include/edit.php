<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id$
 * @package eXtplorer
 * @copyright soeren 2007-2012
 * @author The eXtplorer project (http://extplorer.net)
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
 */

/**
 * File-Edit Functions
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
		$fname = ext_TextEncoding::fromUTF8(get_abs_item($dir, $item));

		if(!get_is_file($fname))  {
			ext_Result::sendResult('edit', false, ext_TextEncoding::toUTF8($item).": ".ext_Lang::err('fileexist' ));
		}
		if(!get_show_item($dir, $item)) {
			ext_Result::sendResult('edit', false, $item.": ".ext_Lang::err('accessfile' ));
		}

		if(isset($GLOBALS['__POST']["dosave"]) && $GLOBALS['__POST']["dosave"]=="yes") {
			// Save / Save As
			$item=basename(stripslashes($GLOBALS['__POST']["fname"]));
			$fname2=ext_TextEncoding::fromUTF8(get_abs_item($dir, $item));

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
		
		$s_item=get_rel_item($dir,$item);	if(strlen($s_item)>50) $s_item="...".substr($s_item,-47);
		$id_hash = substr('f'.md5($s_item),0, 10);
			
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
		$extra['item'] = $item;
		$extra['id_hash'] = $id_hash;
		$extra["content"] = $content;
		$extra['title'] = strlen($s_item) > 50 ? substr( $s_item, strlen($s_item)-30, 30 ) : $s_item;


		// header
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
			case 'xhtml':
				$cp_lang = 'html'; break;
			case 'java':
				$cp_lang = 'java'; break;
			case 'js':
				$cp_lang = 'js'; break;
			case 'pl': 
				$cp_lang = 'perl'; break;
			case 'py': 
				$cp_lang = 'python'; break;
			case 'ruby': 
				$cp_lang = 'ruby'; break;
			case 'sql':
				$cp_lang = 'mysql'; break;
			case 'vb':
			case 'vbs':
				$cp_lang = 'vb'; break;
			case 'php':
				$cp_lang = 'php'; break;				
			case 'xml':
				$cp_lang = 'xml'; break;
			default: 
				$cp_lang = '';
		}
		if (array_key_exists($langs, $this->lang_tbl)) {
			$extra['language'] = $this->lang_tbl[$langs];
		} else {
			$extra['language'] = '';
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
		$extra['_encoding_label'] = $_encoding_label;
		$extra['width'] = $cw;
		$extra['cp_lang'] = $cp_lang;
		ext_Result::sendResult('edit', true, ext_Lang::msg('reopenfile').': '.$item, $extra);
	

	

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
			$err = $file_name.": ".ext_Lang::err('savefile' );
			if( PEAR::isError( $res ) ) {
				$err .= $res->getMessage();
			}
			ext_Result::sendResult( 'edit', false, $err );
		}

	}
}
//------------------------------------------------------------------------------
