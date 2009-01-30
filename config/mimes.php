<?php
/** @version $Id$ */
/** ensure this file is being included by a parent file */
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
//------------------------------------------------------------------------------
// editable files:
$GLOBALS["editable_ext"]=array(
	"\.txt$|\.php$|\.php3$|\.php5$|\.phtml$|\.inc$|\.sql$|\.pl$|\.csv$",
	"\.htm$|\.html$|\.shtml$|\.dhtml$|\.xml$",
	"\.js$|\.css$|\.cgi$|\.cpp$|\.c$|\.cc$|\.cxx$|\.hpp$|\.h$",
	"\.pas$|\.p$|\.java$|\.py$|\.sh$\.tcl$|\.tk$"
);
//------------------------------------------------------------------------------
// image files:
$GLOBALS["images_ext"]="\.png$|\.bmp$|\.jpg$|\.jpeg$|\.gif$|\.ico$";
//------------------------------------------------------------------------------
// mime types: (description,image,extension)
$GLOBALS["super_mimes"]=array(
	// dir, exe, file
	"dir"	=> array($GLOBALS["mimes"]["dir"],"extension/folder.png"),
	"exe"	=> array($GLOBALS["mimes"]["exe"],"extension/exe.png","\.exe$|\.com$|\.bin$"),
	"file"	=> array($GLOBALS["mimes"]["file"],"extension/document.png")
);

$GLOBALS["used_mime_types"]=array(
	// text
	"text"	=> array($GLOBALS["mimes"]["text"],  "extension/txt.png",   "\.txt$"),

	// programming
	"php"	=> array($GLOBALS["mimes"]["php"],   "extension/php.png",   "\.php$"),
	"php3"	=> array($GLOBALS["mimes"]["php3"],  "extension/php3.png",  "\.php3$"),
	"php4"	=> array($GLOBALS["mimes"]["php4"],  "extension/php4.png",  "\.php4$"),
	"php5"	=> array($GLOBALS["mimes"]["php5"],  "extension/php5.png",  "\.php5$"),
	"phtml"	=> array($GLOBALS["mimes"]["phtml"], "extension/phtml.png", "\.phtml$"),
	"inc"	=> array($GLOBALS["mimes"]["inc"],   "extension/inc.png",   "\.inc$"),
	"sql"	=> array($GLOBALS["mimes"]["sql"],   "extension/sql.png",   "\.sql$"),
	"pl"	=> array($GLOBALS["mimes"]["pl"],    "extension/pl.png",    "\.pl$"),
	"cgi"	=> array($GLOBALS["mimes"]["cgi"],   "extension/cgi.png",   "\.cgi$"),
	"py"	=> array($GLOBALS["mimes"]["py"],    "extension/py.png",    "\.py$"),
	"sh"	=> array($GLOBALS["mimes"]["sh"],    "extension/sh.png",    "\.sh$"),
	"c" 	=> array($GLOBALS["mimes"]["c"],     "extension/c.png",     "\.c$"),
	"cc"	=> array($GLOBALS["mimes"]["cc"],    "extension/cc.png",    "\.cc$"),
	"cpp"	=> array($GLOBALS["mimes"]["cpp"],   "extension/cpp.png",   "\.cpp$"),
	"cxx"	=> array($GLOBALS["mimes"]["cxx"],   "extension/cxx.png",   "\.cxx$"),
	"h" 	=> array($GLOBALS["mimes"]["h"],     "extension/h.png",     "\.h$"),
	"hpp" 	=> array($GLOBALS["mimes"]["hpp"],   "extension/hpp.png",   "\.hpp$"),
	"java"	=> array($GLOBALS["mimes"]["java"],  "extension/java.png",  "\.java$"),
	"class"	=> array($GLOBALS["mimes"]["class"], "extension/class.png", "\.class$"),
	"jar"	=> array($GLOBALS["mimes"]["jar"],   "extension/jar.png",   "\.jar$"),

	// browser
	"htm"	=> array($GLOBALS["mimes"]["htm"],   "extension/htm.png",   "\.htm$"),
	"html"	=> array($GLOBALS["mimes"]["html"],  "extension/html.png",  "\.html$"),
	"shtml"	=> array($GLOBALS["mimes"]["shtml"], "extension/shtml.png", "\.shtml$"),
	"dhtml"	=> array($GLOBALS["mimes"]["dhtml"], "extension/dhtml.png", "\.dhtml$"),
	"xhtml"	=> array($GLOBALS["mimes"]["xhtml"], "extension/xhtml.png", "\.xhtml$"),
	"xml"	=> array($GLOBALS["mimes"]["xml"],   "extension/xml.png",   "\.xml$"),
	"js"	=> array($GLOBALS["mimes"]["js"],    "extension/js.png",    "\.js$"),
	"css"	=> array($GLOBALS["mimes"]["css"],   "extension/css.png",   "\.css$"),
	
	// images
	"gif"	=> array($GLOBALS["mimes"]["gif"],   "extension/gif.png",   "\.gif$"),
	"jpg"	=> array($GLOBALS["mimes"]["jpg"],   "extension/jpg.png",   "\.jpg$"),
	"jpeg"	=> array($GLOBALS["mimes"]["jpeg"],  "extension/jpeg.png",  "\.jpeg$"),
	"bmp"	=> array($GLOBALS["mimes"]["bmp"],   "extension/bmp.png",   "\.bmp$"),
	"png"	=> array($GLOBALS["mimes"]["png"],   "extension/png.png",   "\.png$"),
	
	// compressed
	"zip"	=> array($GLOBALS["mimes"]["zip"],   "extension/zip.png",   "\.zip$"),
	"tar"	=> array($GLOBALS["mimes"]["tar"],   "extension/tar.png",   "\.tar$"),
	"tgz"	=> array($GLOBALS["mimes"]["tgz"],   "extension/tgz.png",   "\.tgz$"),
	"gz"	=> array($GLOBALS["mimes"]["gz"],    "extension/gz.png",    "\.gz$"),


	"bz2"	=> array($GLOBALS["mimes"]["bz2"],   "extension/bz2.png",   "\.bz2$"),
	"tbz"	=> array($GLOBALS["mimes"]["tbz"],   "extension/tbz.png",   "\.tbz$"),
	"rar"	=> array($GLOBALS["mimes"]["rar"],   "extension/rar.png",   "\.rar$"),

	// music
	"mp3"	=> array($GLOBALS["mimes"]["mp3"],   "extension/mp3.png",   "\.mp3$"),
	"wav"	=> array($GLOBALS["mimes"]["wav"],   "extension/wav.png",   "\.wav$"),
	"midi"	=> array($GLOBALS["mimes"]["midi"],  "extension/midi.png",  "\.mid$"),
	"rm"	=> array($GLOBALS["mimes"]["real"],  "extension/rm.png",    "\.rm$"),
	"ra"	=> array($GLOBALS["mimes"]["real"],  "extension/ra.png",    "\.ra$"),
	"ram"	=> array($GLOBALS["mimes"]["real"],  "extension/ram.png",   "\.ram$"),
	"pls"	=> array($GLOBALS["mimes"]["pls"],   "extension/pls.png",   "\.pls$"),
	"m3u"	=> array($GLOBALS["mimes"]["m3u"],   "extension/m3u.png",   "\.m3u$"),

	// movie
	"mpg"	=> array($GLOBALS["mimes"]["mpg"],   "extension/mpg.png",   "\.mpg$"),
	"mpeg"	=> array($GLOBALS["mimes"]["mpeg"],  "extension/mpeg.png",  "\.mpeg$"),
	"mov"	=> array($GLOBALS["mimes"]["mov"],   "extension/mov.png",   "\.mov$"),
	"avi"	=> array($GLOBALS["mimes"]["avi"],   "extension/avi.png",   "\.avi$"),
	"swf"	=> array($GLOBALS["mimes"]["swf"],   "extension/swf.png",   "\.swf$"),
	
	// Micosoft / Adobe
	"doc"	=> array($GLOBALS["mimes"]["doc"],   "extension/doc.png",   "\.doc$"),
	"docx"	=> array($GLOBALS["mimes"]["docx"],  "extension/docx.png",  "\.docx$"),
	"xls"	=> array($GLOBALS["mimes"]["xls"],   "extension/xls.png",   "\.xls$"),
	"xlsx"	=> array($GLOBALS["mimes"]["xlsx"],  "extension/xlsx.png",  "\.xlsx$"),
	"pdf"	=> array($GLOBALS["mimes"]["pdf"],   "extension/pdf.png",   "\.pdf$")
);
//------------------------------------------------------------------------------
?>
