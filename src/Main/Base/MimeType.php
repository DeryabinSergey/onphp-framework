<?php
/***************************************************************************
 *   Copyright (C) 2012 by Georgiy T. Kutsurua                             *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

namespace OnPHP\Main\Base;

use OnPHP\Core\Base\Enum;
use OnPHP\Core\Exception\MissingElementException;

/**
 * Based on /etc/mime.types
 * 
 * @ingroup Helpers
**/
final class MimeType extends Enum
{
	/*
	 * Mime Type map
	 */
	protected static $names = array(
		1				=>	'application/andrew-inset',
		2				=>	'application/annodex',
		3				=>	'application/atom+xml',
		4				=>	'application/atomcat+xml',
		5				=>	'application/atomserv+xml',
		6				=>	'application/bbolin',
		7				=>	'application/cap',
		8				=>	'application/cu-seeme',
		9				=>	'application/davmount+xml',
		10				=>	'application/dsptype',
		11				=>	'application/ecmascript',
		12				=>	'application/futuresplash',
		13				=>	'application/hta',
		14				=>	'application/java-archive',
		15				=>	'application/java-serialized-object',
		16				=>	'application/java-vm',
		17				=>	'application/javascript',
		18				=>	'application/m3g',
		19				=>	'application/mac-binhex40',
		20				=>	'application/mac-compactpro',
		21				=>	'application/mathematica',
		22				=>	'application/msaccess',
		23				=>	'application/msword',
		24				=>	'application/mxf',
		25				=>	'application/octet-stream',
		26				=>	'application/oda',
		27				=>	'application/ogg',
		28				=>	'application/pdf',
		29				=>	'application/pgp-keys',
		30				=>	'application/pgp-signature',
		31				=>	'application/pics-rules',
		32				=>	'application/postscript',
		33				=>	'application/rar',
		34				=>	'application/rdf+xml',
		35				=>	'application/rss+xml',
		36				=>	'application/rtf',
		37				=>	'application/smil',
		38				=>	'application/xhtml+xml',
		39				=>	'application/xml',
		40				=>	'application/xspf+xml',
		41				=>	'application/zip',
		42				=>	'application/vnd.android.package-archive',
		43				=>	'application/vnd.cinderella',
		44				=>	'application/vnd.google-earth.kml+xml',
		45				=>	'application/vnd.google-earth.kmz',
		46				=>	'application/vnd.mozilla.xul+xml',
		47				=>	'application/vnd.ms-excel',
		48				=>	'application/vnd.ms-pki.seccat',
		49				=>	'application/vnd.ms-pki.stl',
		50				=>	'application/vnd.ms-powerpoint',
		51				=>	'application/vnd.oasis.opendocument.chart',
		52				=>	'application/vnd.oasis.opendocument.database',
		53				=>	'application/vnd.oasis.opendocument.formula',
		54				=>	'application/vnd.oasis.opendocument.graphics',
		55				=>	'application/vnd.oasis.opendocument.graphics-template',
		56				=>	'application/vnd.oasis.opendocument.image',
		57				=>	'application/vnd.oasis.opendocument.presentation',
		58				=>	'application/vnd.oasis.opendocument.presentation-template',
		59				=>	'application/vnd.oasis.opendocument.spreadsheet',
		60				=>	'application/vnd.oasis.opendocument.spreadsheet-template',
		61				=>	'application/vnd.oasis.opendocument.text',
		62				=>	'application/vnd.oasis.opendocument.text-master',
		63				=>	'application/vnd.oasis.opendocument.text-template',
		64				=>	'application/vnd.oasis.opendocument.text-web',
		65				=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		66				=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		67				=>	'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		68				=>	'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		69				=>	'application/vnd.openxmlformats-officedocument.presentationml.template',
		70				=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		71				=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		72				=>	'application/vnd.rim.cod',
		73				=>	'application/vnd.smaf',
		74				=>	'application/vnd.stardivision.calc',
		75				=>	'application/vnd.stardivision.chart',
		76				=>	'application/vnd.stardivision.draw',
		77				=>	'application/vnd.stardivision.impress',
		78				=>	'application/vnd.stardivision.math',
		79				=>	'application/vnd.stardivision.writer',
		80				=>	'application/vnd.stardivision.writer-global',
		81				=>	'application/vnd.sun.xml.calc',
		82				=>	'application/vnd.sun.xml.calc.template',
		83				=>	'application/vnd.sun.xml.draw',
		84				=>	'application/vnd.sun.xml.draw.template',
		85				=>	'application/vnd.sun.xml.impress',
		86				=>	'application/vnd.sun.xml.impress.template',
		87				=>	'application/vnd.sun.xml.math',
		88				=>	'application/vnd.sun.xml.writer',
		89				=>	'application/vnd.sun.xml.writer.global',
		90				=>	'application/vnd.sun.xml.writer.template',
		91				=>	'application/vnd.symbian.install',
		92				=>	'application/vnd.visio',
		93				=>	'application/vnd.wap.wbxml',
		94				=>	'application/vnd.wap.wmlc',
		95				=>	'application/vnd.wap.wmlscriptc',
		96				=>	'application/vnd.wordperfect',
		97				=>	'application/vnd.wordperfect5.1',
		98				=>	'application/x-123',
		99				=>	'application/x-7z-compressed',
		100				=>	'application/x-abiword',
		101				=>	'application/x-apple-diskimage',
		102				=>	'application/x-bcpio',
		103				=>	'application/x-bittorrent',
		104				=>	'application/x-cab',
		105				=>	'application/x-cbr',
		106				=>	'application/x-cbz',
		107				=>	'application/x-cdf',
		108				=>	'application/x-cdlink',
		109				=>	'application/x-chess-pgn',
		110				=>	'application/x-cpio',
		111				=>	'application/x-csh',
		112				=>	'application/x-debian-package',
		113				=>	'application/x-director',
		114				=>	'application/x-dms',
		115				=>	'application/x-doom',
		116				=>	'application/x-dvi',
		117				=>	'application/x-httpd-eruby',
		118				=>	'application/x-font',
		119				=>	'application/x-freemind',
		120				=>	'application/x-futuresplash',
		121				=>	'application/x-gnumeric',
		122				=>	'application/x-go-sgf',
		123				=>	'application/x-graphing-calculator',
		124				=>	'application/x-gtar',
		125				=>	'application/x-hdf',
		126				=>	'application/x-httpd-php',
		127				=>	'application/x-httpd-php-source',
		128				=>	'application/x-httpd-php3',
		129				=>	'application/x-httpd-php3-preprocessed',
		130				=>	'application/x-httpd-php4',
		131				=>	'application/x-httpd-php5',
		132				=>	'application/x-ica',
		133				=>	'application/x-info',
		134				=>	'application/x-internet-signup',
		135				=>	'application/x-iphone',
		136				=>	'application/x-iso9660-image',
		137				=>	'application/x-jam',
		138				=>	'application/x-java-jnlp-file',
		139				=>	'application/x-jmol',
		140				=>	'application/x-kchart',
		141				=>	'application/x-killustrator',
		142				=>	'application/x-koan',
		143				=>	'application/x-kpresenter',
		144				=>	'application/x-kspread',
		145				=>	'application/x-kword',
		146				=>	'application/x-latex',
		147				=>	'application/x-lha',
		148				=>	'application/x-lyx',
		149				=>	'application/x-lzh',
		150				=>	'application/x-lzx',
		151				=>	'application/x-maker',
		152				=>	'application/x-mif',
		153				=>	'application/x-ms-wmd',
		154				=>	'application/x-ms-wmz',
		155				=>	'application/x-msdos-program',
		156				=>	'application/x-msi',
		157				=>	'application/x-netcdf',
		158				=>	'application/x-ns-proxy-autoconfig',
		159				=>	'application/x-nwc',
		160				=>	'application/x-object',
		161				=>	'application/x-oz-application',
		162				=>	'application/x-pkcs7-certreqresp',
		163				=>	'application/x-pkcs7-crl',
		164				=>	'application/x-python-code',
		165				=>	'application/x-qgis',
		166				=>	'application/x-quicktimeplayer',
		167				=>	'application/x-redhat-package-manager',
		168				=>	'application/x-ruby',
		169				=>	'application/x-sh',
		170				=>	'application/x-shar',
		171				=>	'application/x-shockwave-flash',
		172				=>	'application/x-silverlight',
		173				=>	'application/x-stuffit',
		174				=>	'application/x-sv4cpio',
		175				=>	'application/x-sv4crc',
		176				=>	'application/x-tar',
		177				=>	'application/x-tcl',
		178				=>	'application/x-tex-gf',
		179				=>	'application/x-tex-pk',
		180				=>	'application/x-texinfo',
		181				=>	'application/x-troff',
		182				=>	'application/x-troff-man',
		183				=>	'application/x-troff-me',
		184				=>	'application/x-troff-ms',
		185				=>	'application/x-ustar',
		186				=>	'application/x-wais-source',
		187				=>	'application/x-wingz',
		188				=>	'application/x-x509-ca-cert',
		189				=>	'application/x-xcf',
		190				=>	'application/x-xfig',
		191				=>	'application/x-xpinstall',
		192				=>	'audio/amr',
		193				=>	'audio/amr-wb',
		194				=>	'audio/amr',
		195				=>	'audio/amr-wb',
		196				=>	'audio/annodex',
		197				=>	'audio/basic',
		198				=>	'audio/flac',
		199				=>	'audio/midi',
		200				=>	'audio/mpeg',
		201				=>	'audio/mpegurl',
		202				=>	'audio/ogg',
		203				=>	'audio/prs.sid',
		204				=>	'audio/x-aiff',
		205				=>	'audio/x-gsm',
		206				=>	'audio/x-mpegurl',
		207				=>	'audio/x-ms-wma',
		208				=>	'audio/x-ms-wax',
		209				=>	'audio/x-pn-realaudio',
		210				=>	'audio/x-realaudio',
		211				=>	'audio/x-scpls',
		212				=>	'audio/x-sd2',
		213				=>	'audio/x-wav',
		214				=>	'chemical/x-alchemy',
		215				=>	'chemical/x-cache',
		216				=>	'chemical/x-cache-csf',
		217				=>	'chemical/x-cactvs-binary',
		218				=>	'chemical/x-cdx',
		219				=>	'chemical/x-cerius',
		220				=>	'chemical/x-chem3d',
		221				=>	'chemical/x-chemdraw',
		222				=>	'chemical/x-cif',
		223				=>	'chemical/x-cmdf',
		224				=>	'chemical/x-cml',
		225				=>	'chemical/x-compass',
		226				=>	'chemical/x-crossfire',
		227				=>	'chemical/x-csml',
		228				=>	'chemical/x-ctx',
		229				=>	'chemical/x-cxf',
		230				=>	'chemical/x-daylight-smiles',
		231				=>	'chemical/x-embl-dl-nucleotide',
		232				=>	'chemical/x-galactic-spc',
		233				=>	'chemical/x-gamess-input',
		234				=>	'chemical/x-gaussian-checkpoint',
		235				=>	'chemical/x-gaussian-cube',
		236				=>	'chemical/x-gaussian-input',
		237				=>	'chemical/x-gaussian-log',
		238				=>	'chemical/x-gcg8-sequence',
		239				=>	'chemical/x-genbank',
		240				=>	'chemical/x-hin',
		241				=>	'chemical/x-isostar',
		242				=>	'chemical/x-jcamp-dx',
		243				=>	'chemical/x-kinemage',
		244				=>	'chemical/x-macmolecule',
		245				=>	'chemical/x-macromodel-input',
		246				=>	'chemical/x-mdl-molfile',
		247				=>	'chemical/x-mdl-rdfile',
		248				=>	'chemical/x-mdl-rxnfile',
		249				=>	'chemical/x-mdl-sdfile',
		250				=>	'chemical/x-mdl-tgf',
		251				=>	'chemical/x-mif',
		252				=>	'chemical/x-mmcif',
		253				=>	'chemical/x-mol2',
		254				=>	'chemical/x-molconn-Z',
		255				=>	'chemical/x-mopac-graph',
		256				=>	'chemical/x-mopac-input',
		257				=>	'chemical/x-mopac-out',
		258				=>	'chemical/x-mopac-vib',
		259				=>	'chemical/x-ncbi-asn1',
		260				=>	'chemical/x-ncbi-asn1-ascii',
		261				=>	'chemical/x-ncbi-asn1-binary',
		262				=>	'chemical/x-ncbi-asn1-spec',
		263				=>	'chemical/x-pdb',
		264				=>	'chemical/x-rosdal',
		265				=>	'chemical/x-swissprot',
		266				=>	'chemical/x-vamas-iso14976',
		267				=>	'chemical/x-vmd',
		268				=>	'chemical/x-xtel',
		269				=>	'chemical/x-xyz',
		270				=>	'image/gif',
		271				=>	'image/ief',
		272				=>	'image/jpeg',
		273				=>	'image/pcx',
		274				=>	'image/png',
		275				=>	'image/svg+xml',
		276				=>	'image/tiff',
		277				=>	'image/vnd.djvu',
		278				=>	'image/vnd.wap.wbmp',
		279				=>	'image/x-canon-cr2',
		280				=>	'image/x-canon-crw',
		281				=>	'image/x-cmu-raster',
		282				=>	'image/x-coreldraw',
		283				=>	'image/x-coreldrawpattern',
		284				=>	'image/x-coreldrawtemplate',
		285				=>	'image/x-corelphotopaint',
		286				=>	'image/x-epson-erf',
		287				=>	'image/x-icon',
		288				=>	'image/x-jg',
		289				=>	'image/x-jng',
		290				=>	'image/x-ms-bmp',
		291				=>	'image/x-nikon-nef',
		292				=>	'image/x-olympus-orf',
		293				=>	'image/x-photoshop',
		294				=>	'image/x-portable-anymap',
		295				=>	'image/x-portable-bitmap',
		296				=>	'image/x-portable-graymap',
		297				=>	'image/x-portable-pixmap',
		298				=>	'image/x-rgb',
		299				=>	'image/x-xbitmap',
		300				=>	'image/x-xpixmap',
		301				=>	'image/x-xwindowdump',
		302				=>	'message/rfc822',
		303				=>	'model/iges',
		304				=>	'model/mesh',
		305				=>	'model/vrml',
		306				=>	'model/x3d+vrml',
		307				=>	'model/x3d+xml',
		308				=>	'model/x3d+binary',
		309				=>	'text/cache-manifest',
		310				=>	'text/calendar',
		311				=>	'text/css',
		312				=>	'text/csv',
		313				=>	'text/h323',
		314				=>	'text/html',
		315				=>	'text/iuls',
		316				=>	'text/mathml',
		317				=>	'text/plain',
		318				=>	'text/richtext',
		319				=>	'text/scriptlet',
		320				=>	'text/texmacs',
		321				=>	'text/tab-separated-values',
		322				=>	'text/vnd.sun.j2me.app-descriptor',
		323				=>	'text/vnd.wap.wml',
		324				=>	'text/vnd.wap.wmlscript',
		325				=>	'text/x-bibtex',
		326				=>	'text/x-boo',
		327				=>	'text/x-c++hdr',
		328				=>	'text/x-c++src',
		329				=>	'text/x-chdr',
		330				=>	'text/x-component',
		331				=>	'text/x-csh',
		332				=>	'text/x-csrc',
		333				=>	'text/x-dsrc',
		334				=>	'text/x-diff',
		335				=>	'text/x-haskell',
		336				=>	'text/x-java',
		337				=>	'text/x-literate-haskell',
		338				=>	'text/x-moc',
		339				=>	'text/x-pascal',
		340				=>	'text/x-pcs-gcd',
		341				=>	'text/x-perl',
		342				=>	'text/x-python',
		343				=>	'text/x-scala',
		344				=>	'text/x-setext',
		345				=>	'text/x-sh',
		346				=>	'text/x-tcl',
		347				=>	'text/x-tex',
		348				=>	'text/x-vcalendar',
		349				=>	'text/x-vcard',
		350				=>	'video/3gpp',
		351				=>	'video/annodex',
		352				=>	'video/dl',
		353				=>	'video/dv',
		354				=>	'video/fli',
		355				=>	'video/gl',
		356				=>	'video/mpeg',
		357				=>	'video/mp4',
		358				=>	'video/quicktime',
		359				=>	'video/ogg',
		360				=>	'video/vnd.mpegurl',
		361				=>	'video/x-flv',
		362				=>	'video/x-la-asf',
		363				=>	'video/x-mng',
		364				=>	'video/x-ms-asf',
		365				=>	'video/x-ms-wm',
		366				=>	'video/x-ms-wmv',
		367				=>	'video/x-ms-wmx',
		368				=>	'video/x-ms-wvx',
		369				=>	'video/x-msvideo',
		370				=>	'video/x-sgi-movie',
		371				=>	'video/x-matroska',
		372				=>	'x-conference/x-cooltalk',
		373				=>	'x-epoc/x-sisx-app',
		374				=>	'x-world/x-vrml',
		375				=>	'image/jpeg',
	);

	/*
	 * Extension map
	 */
	protected static $extensions	= array(
		1				=>	'ez',
		2				=>	'anx',
		3				=>	'atom',
		4				=>	'atomcat',
		5				=>	'atomsrv',
		6				=>	'lin',
		7				=>	'cap',
		8				=>	'cu',
		9				=>	'davmount',
		10				=>	'tsp',
		11				=>	'es',
		12				=>	'spl',
		13				=>	'hta',
		14				=>	'jar',
		15				=>	'ser',
		16				=>	'class',
		17				=>	'js',
		18				=>	'm3g',
		19				=>	'hqx',
		20				=>	'cpt',
		21				=>	'nb',
		22				=>	'mdb',
		23				=>	'doc',
		24				=>	'mxf',
		25				=>	'bin',
		26				=>	'oda',
		27				=>	'ogx',
		28				=>	'pdf',
		29				=>	'key',
		30				=>	'pgp',
		31				=>	'prf',
		32				=>	'ps',
		33				=>	'rar',
		34				=>	'rdf',
		35				=>	'rss',
		36				=>	'rtf',
		37				=>	'smi',
		38				=>	'xhtml',
		39				=>	'xml',
		40				=>	'xspf',
		41				=>	'zip',
		42				=>	'apk',
		43				=>	'cdy',
		44				=>	'kml',
		45				=>	'kmz',
		46				=>	'xul',
		47				=>	'xls',
		48				=>	'cat',
		49				=>	'stl',
		50				=>	'ppt',
		51				=>	'odc',
		52				=>	'odb',
		53				=>	'odf',
		54				=>	'odg',
		55				=>	'otg',
		56				=>	'odi',
		57				=>	'odp',
		58				=>	'otp',
		59				=>	'ods',
		60				=>	'ots',
		61				=>	'odt',
		62				=>	'odm',
		63				=>	'ott',
		64				=>	'oth',
		65				=>	'xlsx',
		66				=>	'xltx',
		67				=>	'pptx',
		68				=>	'ppsx',
		69				=>	'potx',
		70				=>	'docx',
		71				=>	'dotx',
		72				=>	'cod',
		73				=>	'mmf',
		74				=>	'sdc',
		75				=>	'sds',
		76				=>	'sda',
		77				=>	'sdd',
		78				=>	'sdf',
		79				=>	'sdw',
		80				=>	'sgl',
		81				=>	'sxc',
		82				=>	'stc',
		83				=>	'sxd',
		84				=>	'std',
		85				=>	'sxi',
		86				=>	'sti',
		87				=>	'sxm',
		88				=>	'sxw',
		89				=>	'sxg',
		90				=>	'stw',
		91				=>	'sis',
		92				=>	'vsd',
		93				=>	'wbxml',
		94				=>	'wmlc',
		95				=>	'wmlsc',
		96				=>	'wpd',
		97				=>	'wp5',
		98				=>	'wk',
		99				=>	'7z',
		100				=>	'abw',
		101				=>	'dmg',
		102				=>	'bcpio',
		103				=>	'torrent',
		104				=>	'cab',
		105				=>	'cbr',
		106				=>	'cbz',
		107				=>	'cdf',
		108				=>	'vcd',
		109				=>	'pgn',
		110				=>	'cpio',
		111				=>	'csh',
		112				=>	'deb',
		113				=>	'dcr',
		114				=>	'dms',
		115				=>	'wad',
		116				=>	'dvi',
		117				=>	'rhtml',
		118				=>	'pfa',
		119				=>	'mm',
		120				=>	'spl',
		121				=>	'gnumeric',
		122				=>	'sgf',
		123				=>	'gcf',
		124				=>	'gtar',
		125				=>	'hdf',
		126				=>	'phtml',
		127				=>	'phps',
		128				=>	'php3',
		129				=>	'php3p',
		130				=>	'php4',
		131				=>	'php5',
		132				=>	'ica',
		133				=>	'info',
		134				=>	'ins',
		135				=>	'iii',
		136				=>	'iso',
		137				=>	'jam',
		138				=>	'jnlp',
		139				=>	'jmz',
		140				=>	'chrt',
		141				=>	'kil',
		142				=>	'skp',
		143				=>	'kpr',
		144				=>	'ksp',
		145				=>	'kwd',
		146				=>	'latex',
		147				=>	'lha',
		148				=>	'lyx',
		149				=>	'lzh',
		150				=>	'lzx',
		151				=>	'frm',
		152				=>	'mif',
		153				=>	'wmd',
		154				=>	'wmz',
		155				=>	'com',
		156				=>	'msi',
		157				=>	'nc',
		158				=>	'pac',
		159				=>	'nwc',
		160				=>	'o',
		161				=>	'oza',
		162				=>	'p7r',
		163				=>	'crl',
		164				=>	'pyc',
		165				=>	'qgs',
		166				=>	'qtl',
		167				=>	'rpm',
		168				=>	'rb',
		169				=>	'sh',
		170				=>	'shar',
		171				=>	'swf',
		172				=>	'scr',
		173				=>	'sit',
		174				=>	'sv4cpio',
		175				=>	'sv4crc',
		176				=>	'tar',
		177				=>	'tcl',
		178				=>	'gf',
		179				=>	'pk',
		180				=>	'texinfo',
		181				=>	't',
		182				=>	'man',
		183				=>	'me',
		184				=>	'ms',
		185				=>	'ustar',
		186				=>	'src',
		187				=>	'wz',
		188				=>	'crt',
		189				=>	'xcf',
		190				=>	'fig',
		191				=>	'xpi',
		192				=>	'amr',
		193				=>	'awb',
		194				=>	'amr',
		195				=>	'awb',
		196				=>	'axa',
		197				=>	'au',
		198				=>	'flac',
		199				=>	'mid',
		200				=>	'mpga',
		201				=>	'm3u',
		202				=>	'oga',
		203				=>	'sid',
		204				=>	'aif',
		205				=>	'gsm',
		206				=>	'm3u',
		207				=>	'wma',
		208				=>	'wax',
		209				=>	'ra',
		210				=>	'ra',
		211				=>	'pls',
		212				=>	'sd2',
		213				=>	'wav',
		214				=>	'alc',
		215				=>	'cac',
		216				=>	'csf',
		217				=>	'cbin',
		218				=>	'cdx',
		219				=>	'cer',
		220				=>	'c3d',
		221				=>	'chm',
		222				=>	'cif',
		223				=>	'cmdf',
		224				=>	'cml',
		225				=>	'cpa',
		226				=>	'bsd',
		227				=>	'csml',
		228				=>	'ctx',
		229				=>	'cxf',
		230				=>	'smi',
		231				=>	'emb',
		232				=>	'spc',
		233				=>	'inp',
		234				=>	'fch',
		235				=>	'cub',
		236				=>	'gau',
		237				=>	'gal',
		238				=>	'gcg',
		239				=>	'gen',
		240				=>	'hin',
		241				=>	'istr',
		242				=>	'jdx',
		243				=>	'kin',
		244				=>	'mcm',
		245				=>	'mmd',
		246				=>	'mol',
		247				=>	'rd',
		248				=>	'rxn',
		249				=>	'sd',
		250				=>	'tgf',
		251				=>	'mif',
		252				=>	'mcif',
		253				=>	'mol2',
		254				=>	'b',
		255				=>	'gpt',
		256				=>	'mop',
		257				=>	'moo',
		258				=>	'mvb',
		259				=>	'asn',
		260				=>	'prt',
		261				=>	'val',
		262				=>	'asn',
		263				=>	'pdb',
		264				=>	'ros',
		265				=>	'sw',
		266				=>	'vms',
		267				=>	'vmd',
		268				=>	'xtel',
		269				=>	'xyz',
		270				=>	'gif',
		271				=>	'ief',
		272				=>	'jpeg',
		273				=>	'pcx',
		274				=>	'png',
		275				=>	'svg',
		276				=>	'tiff',
		277				=>	'djvu',
		278				=>	'wbmp',
		279				=>	'cr2',
		280				=>	'crw',
		281				=>	'ras',
		282				=>	'cdr',
		283				=>	'pat',
		284				=>	'cdt',
		285				=>	'cpt',
		286				=>	'erf',
		287				=>	'ico',
		288				=>	'art',
		289				=>	'jng',
		290				=>	'bmp',
		291				=>	'nef',
		292				=>	'orf',
		293				=>	'psd',
		294				=>	'pnm',
		295				=>	'pbm',
		296				=>	'pgm',
		297				=>	'ppm',
		298				=>	'rgb',
		299				=>	'xbm',
		300				=>	'xpm',
		301				=>	'xwd',
		302				=>	'eml',
		303				=>	'igs',
		304				=>	'msh',
		305				=>	'wrl',
		306				=>	'x3dv',
		307				=>	'x3d',
		308				=>	'x3db',
		309				=>	'manifest',
		310				=>	'ics',
		311				=>	'css',
		312				=>	'csv',
		313				=>	'323',
		314				=>	'html',
		315				=>	'uls',
		316				=>	'mml',
		317				=>	'asc',
		318				=>	'rtx',
		319				=>	'sct',
		320				=>	'tm',
		321				=>	'tsv',
		322				=>	'jad',
		323				=>	'wml',
		324				=>	'wmls',
		325				=>	'bib',
		326				=>	'boo',
		327				=>	'h',
		328				=>	'c',
		329				=>	'h',
		330				=>	'htc',
		331				=>	'csh',
		332				=>	'c',
		333				=>	'd',
		334				=>	'diff',
		335				=>	'hs',
		336				=>	'java',
		337				=>	'lhs',
		338				=>	'moc',
		339				=>	'p',
		340				=>	'gcd',
		341				=>	'pl',
		342				=>	'py',
		343				=>	'scala',
		344				=>	'etx',
		345				=>	'sh',
		346				=>	'tcl',
		347				=>	'tex',
		348				=>	'vcs',
		349				=>	'vcf',
		350				=>	'3gp',
		351				=>	'axv',
		352				=>	'dl',
		353				=>	'dif',
		354				=>	'fli',
		355				=>	'gl',
		356				=>	'mpeg',
		357				=>	'mp4',
		358				=>	'qt',
		359				=>	'ogv',
		360				=>	'mxu',
		361				=>	'flv',
		362				=>	'lsf',
		363				=>	'mng',
		364				=>	'asf',
		365				=>	'wm',
		366				=>	'wmv',
		367				=>	'wmx',
		368				=>	'wvx',
		369				=>	'avi',
		370				=>	'movie',
		371				=>	'mpv',
		372				=>	'ice',
		373				=>	'sisx',
		374				=>	'vrm',
		375				=>	'jpg',
	);

	/**
	 * @return MimeType
	 */
	public static function wrap($id)
	{
		return new self($id);
	}


	/**
	 * Return MimeType object by mime-type string
	 * @param string $value
	 * @throws MissingElementException
	 * @return MimeType
	 */
	public static function getByMimeType($value)
	{
		$list = static::getNameList();

		$id = array_search(mb_strtolower($value), $list);
		if ($id === false)
			throw new MissingElementException('Can not find similar mime type "'.$value.'" !');

		return new self($id);
	}

	/**
	 * Return MimeType object by extension without [dot] prefix
	 * @param string $value
	 * @throws MissingElementException
	 * @return MimeType
	 */
	public static function getByExtension($value)
	{
		$list = static::getExtensionList();

		$id = array_search(mb_strtolower($value), $list);
		if ($id === false)
			throw new MissingElementException('Can not find similar extension "'.$value.'" !');

		return new self($id);
	}


	/**
	 * Extension list
	 * @return array
	 */
	public static function getExtensionList()
	{
		return static::$extensions;
	}

	/**
	 * Return extension without [dot] prefix.
	 * @throws MissingElementException
	 * @return string
	 */
	public function getExtension()
	{
		if(
			isset( static::$extensions[$this->id] )
		)
			return static::$extensions[$this->id];

		throw new MissingElementException(
			'Can not find "'.$this->id.'" in extensions map!'
		);
	}

	/**
	 * Return Mime type string
	 * @return string
	 */
	public function getMimeType()
	{
		return $this->getName();
	}
}
?>
