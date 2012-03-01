<?php
// $Id: Persian.php 149 2012-02-28 9:39:27AM Mohammad Reza Mahmoudi $
// Persian Language Module for v2.3 (translated by the Mohammad Reza Mahmoudi)
// G! : mrezam2pm@gmail.com

global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "rtl"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y / m / d H:i";
$GLOBALS["error_msg"] = array(

	//ADD mrezam
	
	"Destination" => "مقصد",



	// error
	"error"				=> "خطا (s)",
	"message"			=> "پیام (s)",
	"back"				=> " برگشت ",

	// root
	"home"				=> "پوشه پیشخوان قابل دسترس نمی باشد ، لطفا تنظیمات را بررسی نمایید",
	"abovehome"			=> "این فولدر شاید پوشه قبل از پیشخوان باشد.",
	"targetabovehome"	=> "پوشه وارد شده در پوشه پیشخوان وجود ندارد و دسترسی به آن امکان پذبر نیست.",

	// exist
	"direxist"			=> "این پوشه وجود ندارد",
	//"filedoesexist"	=> "This file already exists.",
	"fileexist"			=> "این فایل وجود ندارد",
	"itemdoesexist"		=> "این فایل از قبل وجود  دارد",
	"itemexist"			=> "این فایل وجود ندارد",
	"targetexist"		=> "مسیر پوشه وارد شده ، وجود ندارد",
	"targetdoesexist"	=> "این فایل  از قبل در این آدرس وجود دارد",

	// open
	"opendir"			=> "مشکل در باز کردن پوشه",
	"readdir"			=> "مشکل در خواندن کردن پوشه",

	// access
	"accessdir"			=> "شما دسترسی به این پوشه ندارید",
	"accessfile"		=> "شما دسترسی به این فایل ندارید",
	"accessitem"		=> "شما دسترسی به این گزینه ندارید ",
	"accessfunc"		=> "شما اجازه استفاده از این فانشن را ندارید",
	"accesstarget"		=> "شما دسترسی به این پوشه ندارید",

	// actions
	"permread"			=> "عدم دسترسی به تغییر سطوح دسترسی",
	"permchange"		=> "عدم تغییر سطوح دسترسی ( احتمال دارد نام کاربری و یا دسترسی به فایل محدود شده باشد)",
	"openfile"			=> "باز کردن فایل لغو شد.",
	"savefile"			=> "ذخیره کردن فایل لغو شد",
	"createfile"		=> "ایجاد فایل لغو شد",
	"createdir"			=> "ایجاد پوشه لغو شد",
	"uploadfile"		=> "آپلود فایل لغو شد",
	"copyitem"			=> "کپی کردن فایل لغو شد",
	"moveitem"			=> "انتقال فایل لغو شد",
	"delitem"			=> "حذف فایل لغو شد",
	"chpass"			=> "تغییر رمز عبور لغو شد",
	"deluser"			=> "حذف کاربر لغو شد",
	"adduser"			=> "اضافه کردن کاربر لغو شد",
	"saveuser"			=> " ذخیره کاربر لغو شد",
	"searchnothing"		=> "شما باید چیزی را برای جستجو فراهم کند.",

	// misc
	"miscnofunc"		=> "تابع در دسترس نیست.",
	"miscfilesize"		=> "فایل بیش از حداکثر اندازه است.",
	"miscfilepart"		=> "فایل ناقص ارسال شده است",
	"miscnoname"		=> "لطفا یک نام انتخاب نمایید",
	"miscselitems"		=> "لطفا یک فایل انتخاب نمایید",
	"miscdelitems"		=> "آیا شما از حذف این {0}  فایل مطمئن هستید؟ ",
	"miscdeluser"		=> "آیا شما از حذف این {0}  کاربر مطمئن هستید؟",
	"miscnopassdiff"	=> "رمز عبور جدید با رمز عبور فعلی مغایرت دراد",
	"miscnopassmatch"	=> "رمز عبور ها یکسان نیستند",
	"miscfieldmissed"	=> "لطفا تمام فیلدها را کامل وارد نمایید",
	"miscnouserpass"	=> " نام کاربری و رمز عبور رت صحیح وارد نمایید",
	"miscselfremove"	=> "شما نمی توانید پروفایل خود را حذف نمایید",
	"miscuserexist"		=> "این کاربر قبلا در سیستم ثبت گردیده است",
	"miscnofinduser"	=> "این کاربر یافت نشد",
	"extract_noarchive"		=> "عدم توانایی در باز کردن فایل فشرده",
	"extract_unknowntype"	=> "نوع و فرمت فایل فشرده شما مشخصص نیست",
	
	'chmod_none_not_allowed'	=> 'عدم دسترسی شما به تغییر سطوح دسترسی فایل',
	'archive_dir_notexists'		=> 'پوشه ای جهت ذخیره فایل انتخاب نشده است',
	'archive_dir_unwritable'	=> 'لطفا یک فولدر با قابلیت نوشتن و خواندن برای ذخیره فایل فشرده بسازید',
	'archive_creation_failed'	=> 'ذخیره فایل فشرده لغو گردید'

);
$GLOBALS["messages"] = array(
	// links
	"permlink"			=> " تغییر سطوح دسترسی ",
	"editlink"			=> "ویرایش",
	"downlink"			=> "دانلود",
	"uplink"			=> "برگشت",
	"homelink"			=> "پیشخوان",
	"reloadlink"		=> "بارگذاری مجدد",
	"copylink"			=> "کپی",
	"movelink"			=> "انتقال",
	"dellink"			=> "حذف",
	"comprlink"			=> "تبدیل بصورت فایل فشرده",
	"adminlink"			=> "مدیریت",
	"logoutlink"		=> "خروج",
	"uploadlink"		=> "آپلود",
	"searchlink"		=> "جستجو",
	'difflink'     		=> 'مقایسه فایل',
	"extractlink"		=> "باز کردن فایل فشرده",
	'chmodlink'			=> ' تنظیم دسترسی فایل / پوشه (chmod)  تغییر', // new mic
	'mossysinfolink'	=>  ' نمایش اطلاعات eXtplorer   (eXtplorer, سرور , PHP, mySQL)', // new mic
	'logolink'			=> 'مشاهده وب سایت', // new mic

	// list
	"nameheader"		=> "نام",
	"sizeheader"		=> "سایز",
	"typeheader"		=> "نوع",
	"modifheader"		=> "آخرین زمان ویرایش",
	"permheader"		=> "دسترسی",
	"actionheader"		=> "تنظیمات",
	"pathheader"		=> "مسیر",

	// buttons
	"btncancel"			=> "لغو",
	"btnsave"			=> "ذخیره",
	"btnchange"			=> "تغییر",
	"btnreset"			=> "بارگذاری مجدد",
	"btnclose"			=> "بستن",
	"btnreopen"			=> "باز کردن مجدد",
	"btncreate"			=> "ایجاد",
	"btnsearch"			=> "جستجو",
	"btnupload"			=> "آپلود",
	"btncopy"			=> "کپی",
	"btnmove"			=> "جابجا کردن",
	"btnlogin"			=> "ورود",
	"btnlogout"			=> "خروج",
	"btnadd"			=> "اضافه",
	"btnedit"			=> "ویرایش",
	"btnremove"			=> "حذف",
	"btndiff"			=> "مقایسه فایل",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'		=> 'تغییر نام',
	'confirm_delete_file' => 'آیا از حذف این فایل مطئمن هستید? <br />%s',
	'success_delete_file' => 'فایل با موفقیت حذف گردید',
	'success_rename_file' => 'فایل /پوشه %s با موفقیت به   %s تغییر نام یافت.',
	
	// actions
	"actdir"			=> "پوشه ها",
	"actperms"			=> "تغییر دسترسی ها ",
	"actedit"			=> "ویرایش فایل",
	"actsearchresults"	=> "نتایج جستجو",
	"actcopyitems"		=> "کپی کردن ",
	"actcopyfrom"		=> "کپی کردن از /%s به  /%s ",
	"actmoveitems"		=> "انتقال فایل ها",
	"actmovefrom"		=> "انتقال از /%s به /%s ",
	"actlogin"			=> "ورود",
	"actloginheader"	=> "ورود به  eXtplorer",
	"actadmin"			=> "مدیریت",
	"actchpwd"			=> "تغییر پسورد",
	"actusers"			=> "کاربران",
	"actarchive"		=> "فایل های فشرده",
	"actupload"			=> " آپلود فایل ",

	// misc
	"miscitems"			=> "گزینه ها",
	"miscfree"			=> "رها",
	"miscusername"		=> " نام کاربری",
	"miscpassword"		=> " رمز عبور",
	"miscoldpass"		=> "رمز عبور قبلی",
	"miscnewpass"		=> "رمز عبور جدید",
	"miscconfpass"		=> "تکرار رمز عبور قبلی",
	"miscconfnewpass"	=> "تکرار رمز عبور جدید",
	"miscchpass"		=> "تغییر رمز عبور",
	"mischomedir"		=> "پوشه پیش فرض",
	"mischomeurl"		=> "آدرس پوشه نرم افزار",
	"miscshowhidden"	=> "hidden نمایش فایل های   ",
	"mischidepattern"	=> "مخفی کردن الگوها",
	"miscperms"			=> " دسترسی ",
	"miscuseritems"		=> "(name, home directory, show hidden items, permissions, active)",
	"miscadduser"		=> " اضافه کردن کاربر",
	"miscedituser"		=> "ویرایش کاربر :  '%s'",
	"miscactive"		=> "فعال",
	"misclang"			=> "زبان",
	"miscnoresult"		=> "نتیجه ای در بر نداشت",
	"miscsubdirs"		=> "جستجو در زیر شاخه ها",
	"miscpermnames"		=> array("فقط مشاهده","ویرایش","تغییر رمز عبور","تغییر رمز عبور و ویرایش "," مدیریت کامل "),
	"miscyesno"			=> array("بله","خیر","Y","N"),
	"miscchmod"			=> array("Owner", "Group", "Public"),
	'misccontent'		=> "محتویات فایل",

	// from here all new by mic
	'miscowner'			=> 'صاحب',
	'miscownerdesc'		=> '<strong>توضیحات :</strong><br />User (UID) /<br />Group (GID)<br />سطوح دسترسی فعلی :<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> " eXtplorer مشخصات نرم افزار",
	'sisysteminfo'		=> ' مشخصات سیستم',
	'sibuilton'			=> 'سیستم عامل',
	'sidbversion'		=> 'نسخه پایگاه داده (MySQL)',
	'siphpversion'		=> 'PHP نسخه',
	'siphpupdate'		=> 'توجه : <span style="color: red;">نسخه PHP شما غیر استاندارد می باشد.  </span><br /> ممکن است نرم افزار بصورت کامل بر روی سرور شما راه اندازی نشود. حداقل نسخه PHP سرور شما باید نسخه 4.3 باشد.</strong>!',
	'siwebserver'		=> 'وب یرور ',
	'siwebsphpif'		=> 'WebServer - PHP Interface',
	'simamboversion'	=> 'نسخه نرم افزار eXtplorer ',
	'siuseragent'		=> 'نسخه مرورگر شما',
	'sirelevantsettings'	=> 'تنظیمات مهم PHP',
	'sisafemode'		=> 'Safe Mode',
	'sibasedir'			=> 'Open basedir',
	'sidisplayerrors'	=> 'PHP Errors',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'File Uploads',
	'simagicquotes'		=> 'Magic Quotes',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Output Buffer',
	'sisesssavepath'	=> 'Session Savepath',
	'sisessautostart'	=> 'Session auto start',
	'sixmlenabled'		=> 'XML enabled',
	'sizlibenabled'		=> 'ZLIB enabled',
	'sidisabledfuncs'	=> 'Disabled functions',
	'sieditor'			=> 'WYSIWYG Editor',
	'siconfigfile'		=> 'Config file',
	'siphpinfo'			=> 'PHP Info',
	'siphpinformation'	=> 'PHP Information',
	'sipermissions'		=> 'Permissions',
	'sidirperms'		=> 'سطوح دسترسی پوشه',
	'sidirpermsmess'	=> 'برای کارکرد صحیح نرم افزار eXtplorer باید فولدرهای مربوط به آن سطح دسترسی 777 داشته باشند',
	'sionoff'			=> array( 'روشن', 'خاموش' ),
	
	'extract_warning'	=> " آیا میخواهید محتویات این فایل را در همین پوشه باز کنید ؟ <br /> در این حالت فایل قدیمی هم نام با فایل های جدید جایگزین خواهد شد ، لطفا دقت نمایید.",
	'extract_success'	=> "باز کردن فایلهای فشرده با موفقیت انجام شد ",
	'extract_failure'	=> "باز کردن فایلهای فشرده لغو گردید ",  
	
	'overwrite_files'	=> 'آیا میخواهید این فایل ها جایگزین شوند?',
	"viewlink"			=> " مشاهده ",
	"actview"			=> "نمایش سورس فایل",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'این تنظیمات به زیر شاخه ها هم اعمال شود',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'		=> 'بررسی آخرین نسخه نرم افزار',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'		=>	'تغییر نام فایل یا پوشه',
	'newname'			=>	'نام جدید ',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'			=>	'برگشت به پوشه بعد از ذخیره؟',
	'line'				=> 	'ردیف ها ',
	'column'			=>	' ستون ها ',
	'wordwrap'			=>	'خط چین : (IE فقط در)',
	'copyfile'			=>	'کپی کردن فایل با نام جدید',
	
	// Bookmarks
	'quick_jump' 		=> 'پرش سریع به : ',
	'already_bookmarked' => 'این پوشه قبلا در لیست شما ثبت شده است',
	'bookmark_was_added' => ' پوشه مورد نظر در لیست علاقه مندی ها اضافه گردید',
	'not_a_bookmark'	=> 'این پوشه در لیست علاقه مندی ها وجود ندارد',
	'bookmark_was_removed' => 'پوشه مورد نظر از لیست علاقه مندی ها حذف گردید',
	'bookmarkfile_not_writable' => "مشکل ذخیره %s در لیست علاقه مندی ها.\n فایل '%s'  قابل نوشتن نیست",
	
	'lbl_add_bookmark'	=> 'اضافه کردن به لیست علاقه مندی ها',
	'lbl_remove_bookmark' => 'حذف از لیست علاقه مندی ها',
	
	'enter_alias_name'	=> '  برای این پوشه نام مستعار وارد نمایید ',
	
	'normal_compression' => 'normal فشرده سازی بصورت ',
	'good_compression'	=> 'فشرده سازی بصورت خوب',
	'best_compression'	=> 'فشرده سازی بصورت عالی',
	'no_compression'	=> 'بدون فشرده سازی',
	
	'creating_archive'	=> 'ایجاد قایل بصورت فشرده',
	'processed_x_files'	=> 'Processed %s of %s Files',
	
	'ftp_header'		=> 'احضار هویت در سیستم',
	'ftp_login_lbl'		=> 'لطفا اطلاعات خود را برای اتصال به سرور وارد نمایید.',
	'ftp_login_name'	=> 'FTP نام کاربری',
	'ftp_login_pass'	=> 'FTP رمز عبور',
	'ftp_hostname_port'	=> 'اطلاعات سرور همراه با پورت <br />( نیازی به وارد کردن پورت نیست )',
	'ftp_login_check'	=> 'درحال اتصال به سرور ',
	'ftp_connection_failed' => " عدم اتصال به سرور ، لطفا سرور خود را بررسی نمایید  .",
	'ftp_login_failed'	=> "لطفا نام کاربری و رمز عبور خود را بصورت صحیح وارد نمایید.",
		
	'switch_file_mode'	=> 'نوع نمایش : <strong>%s</strong>. شما میتوانید به حالت  %s تغییر دهید.',
	'symlink_target'	=> 'مقصد لینک سمبلیک',
	
	"permchange"		=> "تغییرات CHMOD با موفقیت انجام گرفت",
	"savefile"			=> "فایل مورد نظر ذخیره گردید ",
	"moveitem"			=> "فایل با موفقیت انتقال یافت ",
	"copyitem"			=> "فایل با موفقیت کپی شد",
	'archive_name'	 	=> 'انتخاب نام فایل', 
	'archive_saveToDir'	=> 'پوشه و محل ذخیره فایل',
	
	'editor_simple'		=> 'حالت ویرایشگر ساده',
	'editor_syntaxhighlight'	=> 'حالت ویرایشگر پیشرفته',

	'newlink'			=> 'ایجاد فایل / پوشه',
	'show_directories'	=> 'نمایش پوشه ها',
	'actlogin_success'	=> 'به مدیریت خوش آمدید',
	'actlogin_failure'	=> 'لطفا مجددا برای ورود تلاش نمایید',
	'directory_tree'	=> 'لیست پوشه ها',
	'browsing_directory'	=> 'نمایش محتوای ',
	'filter_grid'		=> 'Filter',
	'paging_page'		=> 'صفحه',
	'paging_of_X'		=> ' از  {0}',
	'paging_firstpage'	=> 'صفحه نخست',
	'paging_lastpage'	=> 'صفحه پایانی',
	'paging_nextpage'	=> 'صفحه بعدی',
	'paging_prevpage'	=> 'صفحه قبلی',
	
	'paging_info'		=> 'نمایش رکوردها {0} - {1} از {2}',
	'paging_noitems'	=> 'رکوردی برای نمایش یافت نگردید',
	'aboutlink'			=> 'درباره ما ',
	'password_warning_title'	=> 'اخطار مهم - لطفا رمز عبور خود را تغییر دهید.',
	'password_warning_text'		=> 'شما برای اولین بار به سیستم وارد شده اید و از نام کاربری و رمز عبور پیش فرض استفاده نموده اید ، برای افزایش ضریب امنیتی لطفا نام کاربری و رمز عبور خود را تغییر دهید. ',
	'change_password_success'	=> 'رمز عبور شما تغییر یافت',
	'success'			=> 'موفق',
	'failure'			=> 'خطا',
	'dialog_title'		=> 'سایر تنظیمات',
	'upload_processing'	=> 'درحال آپلود ، لطفا صبر نمایید...',
	'upload_completed'	=> 'فایل شما با موفقیت آپلود شد.',
	'acttransfer'		=> 'انتقال فایل از سایر سرورها',
	'transfer_processing'	=> 'در حال انتقال فایل از سایر سرورها ، بطفا صبر نمایید...',
	'transfer_completed'	=> 'انتقال فایل با موفقیت انجام شد.',
	'max_file_size'		=> 'حداکثر حجم فایل : ',
	'max_post_size'		=> 'حداکثر حجم فایل برای آپلود : ',
	'done'				=> 'انجام شده',
	'permissions_processing' => 'در حال تنظیم سطوح دسترسی ، لطفا صبر نمایید...',
	'archive_created'	=> 'فایل فشرده شما ، ساخته شد',
	'save_processing'	=> 'در حال ذخیره فایل...',
	'current_user'		=> 'این نرم افزار توسط کاربران زیر در حال استفاده می باشد : :',
	'your_version'		=> '<div><a target="_blank" href="http://www.ako.ir/" title="ترجمه شده توسط محمدرضا محمودی">Translated By Mohammad Reza Mahmoudi</a></div>   نسخه فعلی نرم افزار   ',
	'search_processing'	=> 'در حال جستجو ، لطفا صبر نمایید....',
	'url_to_file'		=> ' لینک فایل ',
	'file'				=> ' فایل '
	
);
?>
