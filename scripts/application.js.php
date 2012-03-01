<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @package eXtplorer
 * @copyright soeren 2007-2012
 * @author The eXtplorer project (http://extplorer.net)
 * @license
 * @version $Id$
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
$pathprepend = '';
if( ext_isJoomla() ) {
	$pathprepend = 'components/com_extplorer/';
}
?>
<script type="text/javascript">
if( typeof Ext == 'undefined' ) {
	document.location = '<?php echo basename( $GLOBALS['script_name']) ?>?option=com_extplorer&nofetchscript=1';
}
// Override the getPath function to load .js.php files
Ext.Loader.getPath = function(className) {
    var path = '',
        paths = this.config.paths,
        prefix = this.getPrefix(className);

    if (prefix.length > 0) {
        if (prefix === className) {
            return paths[prefix];
        }

        path = paths[prefix];
    	className = className.substring(prefix.length + 1);
    }

    if (path.length > 0) {
        path += '/';
    }

    return path.replace(/\/\.\//g, '/') + className.replace(/\./g, "/") + '.js.php';
}

Ext.String.camelize = function (str) {
    return str.replace (/(?:^|[-_])(\w)/g, function (_, c) {
        return c ? c.toUpperCase () : '';
      })
}
Ext.application({
    name: 'eXtplorer',
    autoCreateViewport: true,
    models: ['File', 'Directory'],
    stores: ['File', 'DirectoryTree'],
	controllers: ['File', 'Directory', 'Forms'],
    launch: function() {
        // This is fired as soon as the page is ready
    	// Hide the Admin Menu under Joomla! 1.5
    	try{ 
        		Ext.fly('header-box').hide();Ext.fly('border-top').hide();
    	} catch(e) {}
    	// Hide the Admin Menu under Joomla! 1.0
    	try{
    		Ext.fly('header').hide();Ext.select(".menubar").hide();
    	} catch(e) {}

    	Ext.tip.QuickTipManager.init();
    	
    	Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
    	    expires: new Date(new Date().getTime()+(1000*60*60*24*7)) //7 days from now
    	}));
        <?php
        	    if( $GLOBALS['require_login'] && $GLOBALS['mainframe']->getUserName() == 'admin' && ($GLOBALS['mainframe']->getPassword() == extEncodePassword('admin') || $GLOBALS['mainframe']->getPassword() == md5('admin'))) {
        	    	// Urge User to change admin password!
        	    	echo 'var config = {
                title : \''.ext_Lang::msg('password_warning_title', true ).'\',
                msg : \''.ext_Lang::msg('password_warning_text', true ) .'\',
                buttons: Ext.Msg.OK,
                fn: function(btn) { if( btn == \'ok\' ) this.getController(\'File\').loadForm( null, \'changepw\') },
                scope : this,
                model: true
            }
        	    	msgbox = Ext.Msg.alert( config );
        	    	msgbox.setIcon(Ext.MessageBox.WARNING);
        			';
        	    }
        	    ?> 
        this.getController('Directory').getDirTree().on("load", this.openDir, this ); 
        
    },
    openDir: function(store, records, successful, options) {
		this.getController('Directory').getDirTree().selectPath( '<?php echo extDirToNodePath(@$_SESSION['ext_'.$GLOBALS['file_mode'].'dir'] ) ?>', 'id', '/');
		this.getController('Directory').getDirTree().un("load", this.openDir, this );
	}
});  
Ext.onReady(function() {
	Ext.FocusManager.enable(true);
	Ext.Loader.setConfig({enabled: true});
	Ext.Loader.setPath('eXtplorer.controller', '<?php echo $pathprepend ?>scripts/app/controller' );
	Ext.Loader.setPath('eXtplorer.store', '<?php echo $pathprepend ?>scripts/app/store' );
	Ext.Loader.setPath('eXtplorer.view', '<?php echo $pathprepend ?>scripts/app/view');
});

</script>