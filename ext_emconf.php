<?php

########################################################################
# Extension Manager/Repository config file for ext: "sk_calendar"
# 
# Auto generated 18-10-2004 19:20
# 
# Manual updates:
# Only the data in the array - anything else is removed by next write
########################################################################

$EM_CONF[$_EXTKEY] = Array (
	'title' => 'Versatile Calendar Extension (VCE)',
	'description' => 'Calendar featuring recurring events, locations and targetgroups',
	'category' => 'plugin',
	'author' => 'Volker Biberger',
	'author_email' => 'info@sitekick.de',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => 'cm1',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author_company' => '',
	'private' => '',
	'download_password' => '',
	'version' => '0.0.0',	// Don't modify this! Managed automatically during upload to repository.
	'_md5_values_when_last_written' => 'a:104:{s:9:".DS_Store";s:4:"8cf5";s:22:"calendar_functions.php";s:4:"8dc5";s:22:"class.skcalendar.class";s:4:"144d";s:27:"class.tx_skcalendar_cm1.php";s:4:"f6c3";s:28:"class.ux_localrecordlist.php";s:4:"e5b3";s:23:"class.ux_sc_alt_doc.php";s:4:"d203";s:26:"class.ux_t3lib_tcemain.php";s:4:"d370";s:12:"ext_icon.gif";s:4:"d40e";s:17:"ext_localconf.php";s:4:"100f";s:14:"ext_tables.php";s:4:"5170";s:14:"ext_tables.sql";s:4:"7a79";s:28:"ext_typoscript_constants.txt";s:4:"1c60";s:28:"ext_typoscript_editorcfg.txt";s:4:"39ac";s:24:"ext_typoscript_setup.txt";s:4:"69f5";s:31:"icon_tx_skcalendar_category.gif";s:4:"bae9";s:29:"icon_tx_skcalendar_events.gif";s:4:"0799";s:31:"icon_tx_skcalendar_events_d.gif";s:4:"4c08";s:31:"icon_tx_skcalendar_events_m.gif";s:4:"0e40";s:32:"icon_tx_skcalendar_events_m2.gif";s:4:"a4d2";s:31:"icon_tx_skcalendar_events_w.gif";s:4:"b9ae";s:31:"icon_tx_skcalendar_events_x.gif";s:4:"ed5c";s:31:"icon_tx_skcalendar_events_y.gif";s:4:"d25b";s:31:"icon_tx_skcalendar_location.gif";s:4:"4ac6";s:32:"icon_tx_skcalendar_organizer.gif";s:4:"75ae";s:34:"icon_tx_skcalendar_targetgroup.gif";s:4:"8dfa";s:13:"locallang.php";s:4:"0155";s:16:"locallang_db.php";s:4:"2e8d";s:10:"readme.txt";s:4:"aad3";s:7:"tca.php";s:4:"56b9";s:15:"uml_model.zargo";s:4:"c711";s:16:"cm1/calendar.css";s:4:"3233";s:13:"cm1/clear.gif";s:4:"91a2";s:15:"cm1/cm_icon.gif";s:4:"7cc7";s:12:"cm1/conf.php";s:4:"1d49";s:13:"cm1/index.php";s:4:"cb50";s:17:"cm1/locallang.php";s:4:"509c";s:23:"cm1/CVS.sandboxinfo/CVS";s:4:"8725";s:19:"CVS.sandboxinfo/CVS";s:4:"d9d5";s:19:"doc/wizard_form.dat";s:4:"c2be";s:20:"doc/wizard_form.html";s:4:"6966";s:23:"doc/CVS.sandboxinfo/CVS";s:4:"5d34";s:17:"fpdf152/.DS_Store";s:4:"e56a";s:15:"fpdf152/FAQ.htm";s:4:"d356";s:16:"fpdf152/fpdf.css";s:4:"490a";s:16:"fpdf152/fpdf.php";s:4:"992c";s:17:"fpdf152/histo.htm";s:4:"8f69";s:23:"fpdf152/cache/.DS_Store";s:4:"1945";s:31:"fpdf152/cache/UcZ1095927615.pdf";s:4:"830b";s:33:"fpdf152/cache/CVS.sandboxinfo/CVS";s:4:"e789";s:27:"fpdf152/CVS.sandboxinfo/CVS";s:4:"1bbd";s:24:"fpdf152/font/courier.php";s:4:"fc24";s:26:"fpdf152/font/helvetica.php";s:4:"18a8";s:27:"fpdf152/font/helveticab.php";s:4:"5363";s:28:"fpdf152/font/helveticabi.php";s:4:"8eba";s:27:"fpdf152/font/helveticai.php";s:4:"54e8";s:23:"fpdf152/font/symbol.php";s:4:"56b0";s:22:"fpdf152/font/times.php";s:4:"bbf9";s:23:"fpdf152/font/timesb.php";s:4:"6704";s:24:"fpdf152/font/timesbi.php";s:4:"7295";s:23:"fpdf152/font/timesi.php";s:4:"4ff5";s:29:"fpdf152/font/zapfdingbats.php";s:4:"0529";s:32:"fpdf152/font/CVS.sandboxinfo/CVS";s:4:"5c5b";s:32:"fpdf152/font/makefont/cp1250.map";s:4:"8a02";s:32:"fpdf152/font/makefont/cp1251.map";s:4:"ee2f";s:32:"fpdf152/font/makefont/cp1252.map";s:4:"8d73";s:32:"fpdf152/font/makefont/cp1253.map";s:4:"9073";s:32:"fpdf152/font/makefont/cp1254.map";s:4:"46e4";s:32:"fpdf152/font/makefont/cp1255.map";s:4:"c469";s:32:"fpdf152/font/makefont/cp1257.map";s:4:"fe87";s:32:"fpdf152/font/makefont/cp1258.map";s:4:"86a4";s:31:"fpdf152/font/makefont/cp874.map";s:4:"4fba";s:36:"fpdf152/font/makefont/iso-8859-1.map";s:4:"53bf";s:37:"fpdf152/font/makefont/iso-8859-11.map";s:4:"83ec";s:37:"fpdf152/font/makefont/iso-8859-15.map";s:4:"3d09";s:37:"fpdf152/font/makefont/iso-8859-16.map";s:4:"b56b";s:36:"fpdf152/font/makefont/iso-8859-2.map";s:4:"4750";s:36:"fpdf152/font/makefont/iso-8859-4.map";s:4:"0355";s:36:"fpdf152/font/makefont/iso-8859-5.map";s:4:"82a2";s:36:"fpdf152/font/makefont/iso-8859-7.map";s:4:"d071";s:36:"fpdf152/font/makefont/iso-8859-9.map";s:4:"8647";s:32:"fpdf152/font/makefont/koi8-r.map";s:4:"04f5";s:32:"fpdf152/font/makefont/koi8-u.map";s:4:"9046";s:34:"fpdf152/font/makefont/makefont.php";s:4:"934c";s:41:"fpdf152/font/makefont/CVS.sandboxinfo/CVS";s:4:"e2df";s:23:"images/cat_fallback.gif";s:4:"fd7b";s:26:"images/CVS.sandboxinfo/CVS";s:4:"e58d";s:13:"pi1/.DS_Store";s:4:"1945";s:35:"pi1/class.tx_skcalendar_boxview.php";s:4:"49db";s:38:"pi1/class.tx_skcalendar_detailview.php";s:4:"3adb";s:36:"pi1/class.tx_skcalendar_feengine.php";s:4:"eafa";s:36:"pi1/class.tx_skcalendar_htmlview.php";s:4:"a1ca";s:36:"pi1/class.tx_skcalendar_internal.php";s:4:"6e2f";s:37:"pi1/class.tx_skcalendar_monthview.php";s:4:"53a3";s:31:"pi1/class.tx_skcalendar_pi1.php";s:4:"2ee9";s:37:"pi1/class.tx_skcalendar_selection.php";s:4:"c820";s:36:"pi1/class.tx_skcalendar_weekview.php";s:4:"09f7";s:36:"pi1/class.tx_skcalendar_yearview.php";s:4:"3c37";s:17:"pi1/locallang.php";s:4:"b4ae";s:19:"pi1/sk_calendar.css";s:4:"cac7";s:17:"pi1/template.tmpl";s:4:"d4d6";s:23:"pi1/CVS.sandboxinfo/CVS";s:4:"e298";s:34:"pi1/pdf_template/logo_sitekick.jpg";s:4:"19f4";s:41:"pi1/pdf_template/pdf_example_template.php";s:4:"3532";s:36:"pi1/pdf_template/CVS.sandboxinfo/CVS";s:4:"5aa9";}',
);

?>