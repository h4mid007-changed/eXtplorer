<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id$
 * @package eXtplorer
 * @copyright soeren 2007-2012
 * @author The eXtplorer project (http://extplorer.net)
 * @author The	The QuiX project (http://quixplorer.sourceforge.net)
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
 * Layout and Application Logic Functions based on ExtJS
 */
?>

function checkLoggedOut( response ) {
	
	var re = /(?:<script([^>]*)?>)((\n|\r|.)*?)(?:<\/script>)/ig;
	var match;
	while(match = re.exec(response.responseText)){
		if(match[2] && match[2].length > 0){
			eval(match[2]);
		}
	}
}
function showLoadingIndicator( el, replaceContent ) {
	if( !el ) return;
	var loadingimg = 'components/com_extplorer/images/_indicator.gif';
	var imgtag = '<' + 'img src="'+ loadingimg + '" alt="Loading..." border="0" name="Loading" align="absmiddle" />';

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

function camelReplaceFn(m, a) {
    return a.charAt(1).toUpperCase();
}

Ext.msgBoxSlider = function(){
    var msgCt;

    function createBox(t, s){
        return ['<div class="msg">',
                '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
                '<div class="x-box-ml"><div class="x-box-mr"><div id="x-box-mc-inner" class="x-box-mc"><h3>', t, '</h3>', s, '</div></div></div>',
                '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
                '</div>'].join('');
    }
    return {
        msg : function(title, format){
            if(!msgCt){
                msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
            }
            msgCt.alignTo(document, 't-t');
            var s = Ext.String.format.apply(String, Array.prototype.slice.call(arguments, 1));
            var m = Ext.DomHelper.append(msgCt, {html:createBox(title, s)}, true);
            m.setWidth(400 );
            m.position(null, 5000 );
           m.alignTo(document, 't-t');
           Ext.get('x-box-mc-inner' ).setStyle('background-image', 'url("<?php echo _EXT_URL ?>/images/_accept.png")');
           Ext.get('x-box-mc-inner' ).setStyle('background-position', '5px 10px');
           Ext.get('x-box-mc-inner' ).setStyle('background-repeat', 'no-repeat');
           Ext.get('x-box-mc-inner' ).setStyle('padding-left', '35px');
            m.slideIn('t').ghost("t", {remove:true, delay: 3000, duration: 2000 });
        }
    };
}();


function statusBarMessage( msg, isLoading, success ) {
	var statusBar = Ext.getCmp('statusPanel');
	if( !statusBar ) return;
	if( isLoading ) {
		statusBar.showBusy();
	}
	else {
		statusBar.setStatus("Done.");
	}
	if( success ) {
		statusBar.setStatus({
		    text: '<?php echo ext_Lang::msg('success', true ) ?>: ' + msg,
		    iconCls: 'success',
		    clear: true
		});
		Ext.msgBoxSlider.msg('<?php echo ext_Lang::msg('success', true ) ?>', msg );
	} else if( success != null ) {
		statusBar.setStatus({
		    text: '<?php echo ext_Lang::err('error', true ) ?>: ' + msg,
		    iconCls: 'error',
		    clear: true
		});
		
	}
	

}

function selectFile( dir, file ) {
	chDir( dir );
	var conn = datastore.proxy.getConnection();
   	if( conn.isLoading() ) {
   		setTimeout( "selectFile(\"" + dir + "\", \""+ file + "\")", 1000 );
   	}
	idx  = datastore.find( "name", file );
	if( idx >= 0 ) {
		ext_itemgrid.getSelectionModel().selectRow( idx );
	}
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