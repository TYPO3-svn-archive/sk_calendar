<?php
########################################################################
# Userfunction for class.t3lib_tcemain ext: "sk_calendar"
# by Volker Biberger info@sitekick.de 2004
# alters the way how files are deleted
# 
# 
# 
########################################################################
include_once('calendar_functions.php');

class ux_t3lib_tcemain extends t3lib_TCEmain 
{

function deleteRecord($table,$uid, $noRecordCheck)	{
global $LANG;

if ($table == 'tx_skcalendar_events') { // only then perform action
	// There should be a function that let's the user decide wheter he wants do delete the whole data record or just a single date with recurring events. The JS popup would be a good place for this
	$suffix = stristr($uid,'_');
	$exeptdate = substr($suffix,3);
	$suffix = substr($suffix,0,3);
	if ($suffix) $uid = substr($uid,0,-13);
	
	if ($suffix == '_re') {
	        //delete exeption entries also 
		$sql = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_skcalendar_exeptions','event='.addslashes($uid));
	}
	else {
		// in case there is a recurring event link them back in
		$sql = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_skcalendar_exeptions','substitute_event='.addslashes($uid));
	}
}

// go on deleting
parent::deleteRecord($table,$uid, $noRecordCheck);
}
}


if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/class.ux_t3lib_tcemain.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/class.ux_t3lib_tcemain.php"]);
}
