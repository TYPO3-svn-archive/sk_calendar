<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003 Volker Biberger (info@sitekick.de)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
* Plugin 'VCE Calendar' for the 'sk_calendar' extension.
*
* @author	Volker Biberger <info@sitekick.de>
*/


require_once(PATH_tslib."class.tslib_pibase.php");
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_feengine.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_selection.php');

class tx_skcalendar_pi1 extends tslib_pibase {
	var $prefixId = "tx_skcalendar_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_skcalendar_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "sk_calendar";	// The extension key.

	/**
	* This is only an example output of the data, so other ways of displaying data will be developed on demand.
	*/
	function main($content,$conf)	{
		$this->conf['general']=$conf['general.']; // I have no idea why there are dots all the sudden.
		$this->conf['box']=$conf['box.'];
		$this->conf['month']=$conf['month.'];
		$this->conf['userFunc'] = $conf['userFunc'];
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
	
		// view & Offset
		$offset = intval($this->piVars['offset']);
		if (!$offset) $offset = mktime(0,0,0);
		if ($this->piVars['view']) $this->conf['general']['view'] = $this->piVars['view'];
		$this->conf['offset'] = $offset;
		
		// read TS
		if ($this->conf['general']['filter_cat']) $filters['categories'][0] = intval($this->conf['general']['filter_cat']);
		if ($this->conf['general']['filter_target']) $filters['targetgroups'][0] = intval($this->conf['general']['filter_target']);
		if ($this->conf['general']['filter_loc']) $filters['locations'][0] = intval($this->conf['general']['filter_loc']);
		if ($this->conf['general']['filter_orga']) $filters['organizers'][0] = intval($this->conf['general']['filter_orga']);
		
		// Override with userinputs
		if ($this->piVars['targetgroups']) $filters['targetgroups'][0] = intval($this->piVars['targetgroups']);
		if ($this->piVars['categories']) $filters['categories'][0] = intval($this->piVars['categories']);
		if ($this->piVars['locations']) $filters['locations'][0] = intval($this->piVars['locations']);
		if ($this->piVars['organizers']) $filters['organizers'][0] = intval($this->piVars['organizers']);
		if (!$this->conf['general']['pid']) $this->conf['general']['pid'] = $GLOBALS["TSFE"]->id; // same page if no pid is given
		$filters['pid'] = $this->conf['general']['pid'];
		switch ($this->conf['general']['view']) {
			case 'week':
			$offset = $offset - date('w',$offset) * 86400 + 86400; // we like mondays
			$filters['startdate'] = $offset;
			$filters['enddate'] = $offset + 604800;
			break;

			case 'box';
			$filters['startdate'] = $offset;
			$filters['enddate'] = $offset + ($this->conf['box']['range']*86400); // default one week
			break;

			case 'day': // not yet implemented
			$filters['startdate'] = $offset;
			$filters['enddate'] = $offset + 86400;
			break;

			case 'month':
			$offset_temp = date('m-d-Y',$offset);
			$offset_temp = explode('-',$offset_temp);
			$start = mktime(0,0,0,$offset_temp[0],1,$offset_temp[2]);
			$end = mktime(0,0,0,$offset_temp[0],31,$offset_temp[2]);
			$filters['startdate'] = $start;
			$filters['enddate'] = $end;
			break;

			case 'year':
			$offset = date('Y',$offset);
			$start = mktime(0,0,0,1,1,$offset);
			$end = mktime(23,59,59,12,31,$offset);
			$filters['startdate'] = $start;
			$filters['enddate'] = $end;
			break;

			case 'detail':
			$filters['startdate'] = $offset;
			$filters['enddate'] = $offset + 86400;
			break;


		}
$this->conf['offset'] = $offset;
		$selection = new tx_skcalendar_internal();
		$selection->setFilters($filters);
		$selection->getResults();

		switch ($this->conf['general']['view']) {
			case 'week':
			$calendar = new tx_skcalendar_weekview($selection,$this->conf);
			break;

			case 'box';
			$calendar = new tx_skcalendar_boxview($selection,$this->conf);
			break;

			case 'day':
			$calendar = new tx_skcalendar_dayview($selection,$this->conf);
			break;

			case 'month':
			$calendar = new tx_skcalendar_monthview($selection,$this->conf);
			$calendar->createHolidays('de');
			break;

			case 'year':
			$calendar = new tx_skcalendar_yearview($selection,$this->conf);
			$calendar->createHolidays('de');
			break;

			case 'detail';
			$this->conf['uid'] = $this->piVars['uid'];
			$calendar = new tx_skcalendar_detailview($selection,$this->conf);
			break;
		}		
		$calendar->setRange($filters['startdate'],$filters['enddate']);
		$calendar->createCalendar();
		$calendar->parseCalendar();

		return $this->pi_wrapInBaseClass($calendar->content);
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"]);
}

?>