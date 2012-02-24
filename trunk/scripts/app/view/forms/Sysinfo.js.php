<?php
if( !defined( '_JEXEC' )) {
	$_REQUEST['action'] = 'include_javascript';
	$_GET['subdir'][] = 'app/view/forms';
	$_GET['file'][] = str_replace('.php', '', basename(__FILE__) );
	include('../../../../index.php');
}
?>	
Ext.define( 'eXtplorer.view.forms.Sysinfo', {
	extend: "Ext.tab.Panel",
	height: 350,
	width: 700,
	activeTab: 0,
	items: [{
		title: "<?php echo ext_Lang::msg( 'aboutlink' ) ?>",
		autoScroll: true,
		loader: { 
			url: "index.php",
			params: {
				option: "com_extplorer",
				action: "get_about",
				action2: "about"
			}
		},
        listeners: {
        	render: function(tab) {
                tab.loader.load();
       		}
        }
	},{
		title: "<?php echo ext_Lang::msg( 'sisysteminfo' ) ?>",
		autoScroll: true,
		loader: { 
			url: "index.php",
			params: {
				option: "com_extplorer",
				action: "get_about",
				action2: "systeminfo"
			}
		},
        listeners: {
        	render: function(tab) {
                tab.loader.load();
       		}
        }
	},{
		title: "<?php echo ext_Lang::msg('siphpinfo' ); ?>",
		autoScroll: true,
		loader: { 
			url: "index.php",
			params: {
				option: "com_extplorer",
				action: "get_about",
				action2: "phpinfo"
			}
		},
        listeners: {
        	render: function(tab) {
                tab.loader.load();
       		}
        }
	}]
});