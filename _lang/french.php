<?php

// French Language Module for joomlaXplorer (translated by Olivier Pariseau and Alexandre PRIETO)

$GLOBALS["charset"] = "iso-8859-1";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "d/m/Y H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "ERREUR(S)",
	"back"			=> "Page pr&eacute;c&eacute;dente",
	
	// root
	"home"			=> "Le r&eacute;pertoire Home n'existe pas, v&eacute;rifiez vos pr&eacute;f&eacute;rences.",
	"aboveHome"		=> "Le r&eacute;pertoire courant n'a pas l'air d'&ecirc;tre au-dessus du r&eacute;pertoire Home.",
	"targetaboveHome"	=> "Le r&eacute;pertoire cible n'a pas l'air d'&ecirc;tre au-dessus du r&eacute;pertoire Home.",
	
	// exist
	"direxist"		=> "Ce r&eacute;pertoire n'existe pas.",
	//"filedoesexist"	=> "Ce fichier existe deja.",
	"fileexist"		=> "Ce fichier n'existe pas.",
	"itemdoesexist"		=> "Cet item existe deja.",
	"itemexist"		=> "Cet item n'existe pas.",
	"targetexist"		=> "Le r&eacute;pertoire cible n'existe pas.",
	"targetdoesexist"	=> "L'item cible existe deja.",
	
	// open
	"opendir"		=> "Impossible d'ouvrir le r&eacute;pertoire.",
	"readdir"		=> "Impossible de lire le r&eacute;pertoire.",
	
	// access
	"accessdir"		=> "Vous n'&ecirc;tes pas autoris&eacute; &agrave; acceder &agrave; ce r&eacute;pertoire.",
	"accessfile"		=> "Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der &agrave; ce fichier.",
	"accessitem"		=> "Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der &agrave; cet item.",
	"accessfunc"		=> "Vous ne pouvez pas utiliser cette fonction.",
	"accesstarget"		=> "Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der au r&eacute;pertoire cible.",
	
	// actions
	"permread"		=> "Lecture des permissions &eacute;chou&eacute;e.",
	"permchange"		=> "Changement des permissions &eacute;chou&eacute;.",
	"openfile"		=> "Ouverture du fichier &eacute;chou&eacute;e.",
	"savefile"		=> "Sauvegarde du fichier &eacute;chou&eacute;e.",
	"createfile"		=> "Cr&eacute;ation du fichier &eacute;chou&eacute;e.",
	"createdir"		=> "Cr&eacute;ation du r&eacute;pertoire &eacute;chou&eacute;e.",
	"uploadfile"		=> "Envoie du fichier &eacute;chou&eacute;.",
	"copyitem"		=> "La copie a &eacute;chou&eacute;e.",
	"moveitem"		=> "Le d&eacute;placement a &eacute;chou&eacute;.",
	"delitem"		=> "La supression a &eacute;chou&eacute;e.",
	"chpass"		=> "Le changement de mot de passe a &eacute;chou&eacute;.",
	"deluser"		=> "La supression de l'usager a &eacute;chou&eacute;e.",
	"adduser"		=> "L'ajout de l'usager a &eacute;chou&eacute;e.",
	"saveuser"		=> "La sauvegarde de l'usager a &eacute;chou&eacute;e.",
	"searchnothing"		=> "Vous devez entrer quelque chose &eacute; chercher.",
	
	// misc
	"miscnofunc"		=> "Fonctionalit&eacute; non disponible.",
	"miscfilesize"		=> "La taille du fichier exc&egrave;de la taille maximale autoris&eacute;e.",
	"miscfilepart"		=> "L'envoi du fichier n'a pas &eacute;t&eacute; compl&eacute;t&eacute;.",
	"miscnoname"		=> "Vous devez entrer un nom.",
	"miscselitems"		=> "Vous n'avez s&eacute;lectionn&eacute; aucuns item(s).",
	"miscdelitems"		=> "&eacute;tes-vous certain de vouloir supprimer ces \"+num+\" item(s)?",
	"miscdeluser"		=> "&eacute;tes-vous certain de vouloir supprimer l'usager '\"+user+\"'?",
	"miscnopassdiff"	=> "Le nouveau mot de passe est indentique au pr&eacute;c&eacute;dent.",
	"miscnopassmatch"	=> "Les mots de passe diff&eacute;rent.",
	"miscfieldmissed"	=> "Un champs requis n'a pas &eacute;t&eacute; rempli.",
	"miscnouserpass"	=> "Nom d'usager ou mot de passe invalide.",
	"miscselfremove"	=> "Vous ne pouvez pas supprimer votre compte.",
	"miscuserexist"		=> "Ce nom d'usager existe d&eacute;j&eacute;.",
	"miscnofinduser"	=> "Usager non trouv&eacute;.",
	"extract_noarchive" => "Ce fichier n'est pas une archive extractible.",
	"extract_unknowntype" => "Type Archive Inconnue"
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "CHANGER LES PERMISSIONS",
	"editlink"		=> "&Eacute;DITER",
	"downlink"		=> "T&eacute;L&eacute;CHARGER",
	"uplink"		=> "PARENT",
	"Homelink"		=> "Home",
	"reloadlink"		=> "RAFRA&Icirc;CHIR",
	"copylink"		=> "COPIER",
	"movelink"		=> "D&Eacute;PLACER",
	"dellink"		=> "SUPPRIMER",
	"comprlink"		=> "ARCHIVER",
	"adminlink"		=> "ADMINISTRATION",
	"logoutlink"		=> "D&Eacute;CONNECTER",
	"uploadlink"		=> "ENVOYER",
	"searchlink"		=> "RECHERCHER",
	"extractlink"	=> "Extraire Archive",
	'chmodlink'		=> 'Changer (chmod) Droits (R&eacute;pertoire/Fichier(s))', // new mic
	'mossysinfolink'	=> $_VERSION->PRODUCT.' System Information ('.$_VERSION->PRODUCT.', Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Go to the joomlaXplorer Website (new window)', // new mic
	
	// list
	"nameheader"		=> "Nom",
	"sizeheader"		=> "Taille",
	"typeheader"		=> "Type",
	"modifheader"		=> "Modifi&eacute;",
	"permheader"		=> "Perm's",
	"actionheader"		=> "Actions",
	"pathheader"		=> "Chemin",
	
	// buttons
	"btncancel"		=> "Annuler",
	"btnsave"		=> "Sauver",
	"btnchange"		=> "Changer",
	"btnreset"		=> "R&eacute;initialiser",
	"btnclose"		=> "Fermer",
	"btncreate"		=> "Cr&eacute;er",
	"btnsearch"		=> "Chercher",
	"btnupload"		=> "Envoyer",
	"btncopy"		=> "Copier",
	"btnmove"		=> "D&eacute;placer",
	"btnlogin"		=> "Connecter",
	"btnlogout"		=> "D&eacute;connecter",
	"btnadd"		=> "Ajouter",
	"btnedit"		=> "&eacute;diter",
	"btnremove"		=> "Supprimer",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'RENAME',
	'confirm_delete_file' => '&ecirc;tes-vous certain de vouloir supprimer ce fichier? \\n%s',
	'success_delete_file' => 'Fichier supprim&eacute; avec succes.',
	'success_rename_file' => 'Le r&eacute;pertoire/fichier %s a &eacute;t&eacute; renomm&eacute; %s.',
	
	
	// actions
	"actdir"		=> "R&eacute;pertoire",
	"actperms"		=> "Changer les permissions",
	"actedit"		=> "&Eacute;diter le fichier",
	"actsearchresults"	=> "R&eacute;sultats de la recherche",
	"actcopyitems"		=> "Copier le(s) item(s)",
	"actcopyfrom"		=> "Copier de /%s &eacute; /%s ",
	"actmoveitems"		=> "D&eacute;placer le(s) item(s)",
	"actmovefrom"		=> "D&eacute;placer de /%s &eacute; /%s ",
	"actlogin"		=> "Connecter",
	"actloginheader"	=> "Connecter pour utiliser QuiXplorer",
	"actadmin"		=> "Administration",
	"actchpwd"		=> "Changer le mot de passe",
	"actusers"		=> "Usagers",
	"actarchive"		=> "Archiver le(s) item(s)",
	"actupload"		=> "Envoyer le(s) fichier(s)",
	
	// misc
	"miscitems"		=> "Item(s)",
	"miscfree"		=> "Disponible",
	"miscusername"		=> "Usager",
	"miscpassword"		=> "Mot de passe",
	"miscoldpass"		=> "Ancien mot de passe",
	"miscnewpass"		=> "Nouveau mot de passe",
	"miscconfpass"		=> "Confirmer le mot de passe",
	"miscconfnewpass"	=> "Confirmer le nouveau mot de passe",
	"miscchpass"		=> "Changer le mot de passe",
	"miscHomedir"		=> "R&eacute;pertoire Home",
	"miscHomeurl"		=> "URL Home",
	"miscshowhidden"	=> "Voir les items cach&eacute;s",
	"mischidepattern"	=> "Cacher pattern",
	"miscperms"		=> "Permissions",
	"miscuseritems"		=> "(nom, r&eacute;pertoire Home, Voir les items cach&eacute;s, permissions, actif)",
	"miscadduser"		=> "Ajouter un usager",
	"miscedituser"		=> "&Eacute;diter l'usager '%s'",
	"miscactive"		=> "Actif",
	"misclang"		=> "Langage",
	"miscnoresult"		=> "Aucun r&eacute;sultats.",
	"miscsubdirs"		=> "Rechercher dans les sous-r&eacute;pertoires",
	"miscpermnames"		=> array("Lecture seulement","Modifier","Changement le mot de passe","Modifier et Changer le mot de passe",
					"Administrateur"),
	"miscyesno"		=> array("Oui","Non","O","N"),
	"miscchmod"		=> array("Propri&eacute;taire", "Groupe", "Publique"),
	// from here all new by mic
	'miscowner'			=> 'Owner',
	'miscownerdesc'		=> '<strong>Description:</strong><br />User (UID) /<br />Group (GID)<br />Current rights:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> $_VERSION->PRODUCT.' System Info',
	'sisysteminfo'		=> 'System Info',
	'sibuilton'			=> 'OS',
	'sidbversion'		=> 'Version Base de Donn&eacute;e(MySQL)',
	'siphpversion'		=> 'Version PHP',
	'siphpupdate'		=> 'INFORMATION: <span style="color: red;">La version de PHP que vous utilisez n\'est <strong>plus</strong> d\'actualit&eacute;!</span><br />Afin de garantir un fonctionnement maximum de '.$_VERSION->PRODUCT.' et addons,<br />Vous devez utiliser au minimum <strong>PHP.Version 4.3</strong>!',
	'siwebserver'		=> 'Webserver',
	'siwebsphpif'		=> 'WebServer - Interface PHP',
	'simamboversion'	=> ' Version'.$_VERSION->PRODUCT,
	'siuseragent'		=> 'Version du Navigateur',
	'sirelevantsettings' => 'Param&eacute;tres PHP avanc&eacute;s',
	'sisafemode'		=> 'Safe Mode',
	'sibasedir'			=> 'Ouvrir r&eacute;pertoire de base',
	'sidisplayerrors'	=> 'Erreurs PHP',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'Datei Uploads',
	'simagicquotes'		=> 'Magic Quotes',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Output Buffer',
	'sisesssavepath'	=> 'Session Savepath',
	'sisessautostart'	=> 'Session auto start',
	'sixmlenabled'		=> 'XML activ&eacute;',
	'sizlibenabled'		=> 'ZLIB activ&eacute;',
	'sidisabledfuncs'	=> 'Non enabled functions',
	'sieditor'			=> 'Editeur WYSIWYG',
	'siconfigfile'		=> 'Config file',
	'siphpinfo'			=> 'PHP Info',
	'siphpinformation'	=> 'PHP Information',
	'sipermissions'		=> 'Permissions',
	'sidirperms'		=> 'Permissions R&eacute;pertoire',
	'sidirpermsmess'	=> 'Pour obtenir un fonctionnement correcte de '.$_VERSION->PRODUCT.' assurez vous que vous poss&eacute;de les droits en &eacute;criture sur l\'ensemble des r&eacute;pertoires. [chmod 0777]',
	'sionoff'			=> array( 'On', 'Off' ),
	
	'extract_warning' => "Voulez-vous r&eacute;ellement extraire ce fichier? Ici?\\nCe fichier &eacute;crasera le fichier existant!",
	'extract_success' => "Extraction r&eacute;ussie",
	'extract_failure' => "Extraction &eacute;chou&eacute;e",
	
	'overwrite_files' => 'Ecraser le(s) fichier(s) existant(s)?',
	"viewlink"		=> "APERCU",
	"actview"		=> "Aper&eacute;u source du fichier",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Recurse into subdirectories?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'V&eacute;rifier si une version sup&eacute;rieure existe',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Renommer le r&eacute;pertoire ou fichier...',
	'newname'		=>	'Nouveau Nom',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Retourner au r&eacute;pertoire apr&eacute;s sauvegarde?',
	'line'		=> 	'Ligne',
	'column'	=>	'Colonne',
	'wordwrap'	=>	'Wordwrap: (IE seulement)',
	'copyfile'	=>	'Copier fichier avec ce nom de fichier',
	
	// Bookmarks
	'quick_jump' => 'Saut rapide:',
	'already_bookmarked' => 'Ce r&eacute;pertoire existe d&eacute;j&agrave; dans le signet',
	'bookmark_was_added' => 'R&eacute;pertoire ajout&eacute; &agrave; la liste de signet.',
	'not_a_bookmark' => 'Ce r&eacute;pertoire n\'est pas un signet.',
	'bookmark_was_removed' => 'Ce r&eacute;pertoire &agrave; &eacute;t&eacute; supprim&eacute; de la liste de signet.',
	'bookmarkfile_not_writable' => "&Eacute;chec lors de %s dans le signet.\n Le fichier signet '%s' \nn\'est pas accesible en &eacute;criture.",
	
	'lbl_add_bookmark' => 'Ajouter ce r&eacute;pertoire au signet',
	'lbl_remove_bookmark' => 'Supprimer ce r&eacute;pertoire de la liste de signet',
	
	'enter_alias_name' => 'SVP, entrez un alias pour ce signet',
	
	'normal_compression' => 'compression normale',
	'good_compression' => 'compression moyenne',
	'best_compression' => 'compression sup&eacute;rieure',
	'no_compression' => 'pas de compression',
	
	'creating_archive' => 'Cr&eacute;ation du Fichier Archive...',
	'processed_x_files' => '%s of %s Fichiers trait&eacute;s',
	
	'ftp_header' => 'Local FTP Authentication',
	'ftp_login_lbl' => 'SVP, entrez un login de connexion pour le serveur FTP',
	'ftp_login_name' => 'Nom Utilisateur FTP',
	'ftp_login_pass' => 'Mot de passe FTP',
	'ftp_hostname_port' => 'FTP Server Hostname and Port <br />(Port is optional)',
	'ftp_login_check' => 'Test connexion serveur FTP...',
	'ftp_connection_failed' => "Serveur FTP impossible &agrave; contacter. \nSVP, v&eacute;rifiez que le service FTP est lanc&eacute; sur le serveur.",
	'ftp_login_failed' => "Login FTP incorrect. SVP, V&eacute;rifiez le nom et le mot de passe Utilisateur et r&eacute;essayez.",
		
	'switch_file_mode' => 'Mode courant: <strong>%s</strong>. Vous pouvez passer en mode %s.',
	'symlink_target' => 'Cible du lien symbolique',
	
	"permchange"		=> "CHMOD Success:",
	"savefile"		=> "Le fichier a &eacute;tƒ sauvegard&eacute;.",
	"moveitem"		=> "DŽplac&eacute; avec succ&egrave;s.",
	"copyitem"		=> "Copi&eacute; avec succ&egrave;s.",
	'archive_name' 	=> 'Name of the Archive File',
	'archive_saveToDir' 	=> 'Save the Archive in this directory',
	
	'editor_simple'	=> 'Simple Editor Mode',
	'editor_syntaxhighlight'	=> 'Syntax-Highlighted Mode'
);
?>
