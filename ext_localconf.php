<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");
 
// Extending TypoScript from static template uid=43 to set up userdefined tag:
t3lib_extMgm::addTypoScript($_EXTKEY,"editorcfg","
	tt_content.CSS_editor.ch.tx_skcalendar_pi1 = < plugin.tx_skcalendar_pi1.CSS_editor
",43);
// save'and'new buttons
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_skcalendar_category=1
	options.saveDocNew.tx_skcalendar_organizer=1
	options.saveDocNew.tx_skcalendar_location=1
	options.saveDocNew.tx_skcalendar_targetgroup=1
	options.saveDocNew.tx_skcalendar_events=1
');
// Alters Listview adding ghost copies
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/class.db_list_extra.inc'] = t3lib_extMgm::extPath($_EXTKEY)."class.ux_localrecordlist.php";

// alters editmode - checks for ghostcopy first
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/alt_doc.php'] = t3lib_extMgm::extPath($_EXTKEY)."class.ux_sc_alt_doc.php";

// alters deletion process
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['t3lib/class.t3lib_tcemain.php'] = t3lib_extMgm::extPath($_EXTKEY)."class.ux_t3lib_tcemain.php";

// VCE Context Edit
t3lib_extMgm::addPItoST43($_EXTKEY,"pi1/class.tx_skcalendar_pi1.php","_pi1","list_type",1);


?>