<?php
########################################################################
# Userfunction for alt_doc ext: "sk_calendar"
# by Volker Biberger info@sitekick.de 2004
# Calendar will not work recurrent events without this function
# Use Contextmenu instead
# 
# 
########################################################################
include_once('calendar_functions.php');

class ux_sc_alt_doc extends SC_alt_doc 
{
	
	function processData() {
	$table = 'tx_skcalendar_events';
	$data_arr = t3lib_div::_GP('data');
	list(,$data)  = each ($data_arr[$table]); // get first entry
	$exeptdate = $data['date'];
	
	
	// should we write an exeption?
	if (strpos($data['exeptions'],'_to_')) $uid = substr($data['exeptions'],strpos($data['exeptions'],'exept_to_')+9); // get the ID

	if ($uid) {
		// get data
		$record = t3lib_BEfunc::getRecord($table,$uid);
		// write exeption
		$updatefields['exeptions'] = addToExeptions($record['exeptions'],$exeptdate);
		$where = "uid='$uid'";
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table,$where,$updatefields);

	}
		// go on saving
		parent::processData();
	}
	
	
	
	function makeEditForm()
	{
		$table = 'tx_skcalendar_events';
		if (!$this->editconf[$table]) return parent::makeEditForm();
		else 
		{
		
		global $LANG;
		
		// Quick and dirty, can (and should) be removed and the Lang-Arr should be in EXT:locallang.php. 
		global $LOCAL_LANG; 
		$aux_lang = Array (
			"default" => Array (
				"rec_titel" => "Edit recurring Event",
				"rec_help" => "Do you want to edit only one event, or all events?",
				"ed_one" => "-> Edit only THIS event (create exeption)",
				"ed_all" => "-> Edit all Events",
			),
			"de" => Array (
				"rec_titel" => "Wiederholungstermin editieren",
				"rec_help" => "Möchten Sie nur diesen einen oder alle Termine bearbeiten?",
				"ed_one" => "-> Nur diesen einen Termin editieren (Ausnahme der Wiederholungsserie)",
				"ed_all" => "-> Alle Termine editieren",
			),
		);
		
		$LOCAL_LANG = array_merge($LOCAL_LANG, $aux_lang);
		// end Q&D
		list($uid, $action) = each($this->editconf['tx_skcalendar_events']);
		
		$suffix = stristr($uid,'_');
		$exeptdate = substr($suffix,3);
		$suffix = substr($suffix,0,3);
		$uid = substr($uid,0,-13);
			
		if (!$this->editconf['tx_skcalendar_events'] || $action != 'edit' || !$suffix) return parent::makeEditForm(); // not my plugin || not editing || no recurring event -> not my problem
		else {
			switch($suffix)
				{
					case '_re':
					// setup
					$backPath = '';
					$params_one = '&edit[tx_skcalendar_events][' . $uid . '_ex' . $exeptdate . ']=edit';
					$params_all = '&edit[tx_skcalendar_events][' . $uid . ']=edit';
					
					// make dok
					$this->doc = t3lib_div::makeInstance("mediumDoc");
					$content = '<a href=# onclick="' . t3lib_BEfunc::editOnClick($params_all,$backPath) . '">' . $LANG->getLL('ed_all') . '</a><br><a href=# onclick="' . t3lib_BEfunc::editOnClick($params_one,$backPath) . '">' . $LANG->getLL('ed_one') . '</a><br>';
				
					$this->content.=$this->doc->startPage($LANG->getLL("rec_titel"));
					$this->content.=$this->doc->header($LANG->getLL("rec_titel"));
					$this->content.=$this->doc->spacer(5);
					$this->content.=$this->doc->section($LANG->getLL("rec_help"),$content,0,1);
					break;
					
					case '_ex':
					
					// get data
					$record = t3lib_BEfunc::getRecord($table,$uid);
					
					// prepare data
					$record['exeptions']= 'exept_to_' . $uid;
					$record['date'] = $exeptdate;
					$pid = $record['pid']; // save this one for later
unset($record['uid'],$record['pid'],$record['tstamp'],$record['crdate'],$record['cruser_id'],$record['sorting'],$record['deleted'],$record['hidden'],$record['recurring'],$record['recurr_until']);
					
					
					// set data as default for new entry
					$this->defVals['tx_skcalendar_events'] = $record;
					
					// prepare new entry
					$this->editconf['tx_skcalendar_events'] = Array($pid => 'new');

					// proceed to making new 
					return parent::makeEditForm();
					break;
					
					default:
					// should not happen, but one never knows
					return parent::makeEditForm(); 
					break;
				}
			}
			}
	}
}