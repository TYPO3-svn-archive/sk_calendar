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
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_calendarview.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_selection.php');

class tx_skcalendar_pi1 extends tslib_pibase {
	var $prefixId = "tx_skcalendar_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_skcalendar_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "sk_calendar";	// The extension key.

	/**
	* This is only an example output of the data, so other ways of displaying data will be developed on demand.
	*/
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		if ($GLOBALS['HTTP_POST_VARS']['tx_skcalendar']['offset']) $offset = intval($GLOBALS['HTTP_POST_VARS']['tx_skcalendar']['offset']);
		elseif ($GLOBALS['HTTP_GET_VARS']['tx_skcalendar']['offset']) $offset = intval($GLOBALS['HTTP_GET_VARS']['tx_skcalendar']['offset']);
		if (!$this->conf['type']) $this->conf['type'] = 'week';

		switch ($this->conf['type']) {
			case 'week':
			case 'weekbox';
			if (!$offset) $offset = mktime(0,0,0);			
			$offset = $offset - date('w',$offset) * 86400 + 86400; // we like mondays
			$filters['startdate'] = $offset;
			$filters['enddate'] = $offset + 604800;
			break;

			case 'day':
			if (!$offset) $offset = date('m-d-Y');
			$filters['startdate'] = $offset;
			$filters['enddate'] = $offset + 86400;
			break;

			case 'month':
			if (!$offset) $offset = date('m-d-Y');
			else $offset = date('m-d-Y',$offset);
			$offset = explode('-',$offset);
			$start = mktime(0,0,0,$offset[0],1,$offset[2]);
			$end = mktime(0,0,0,$offset[0]+1,1,$offset[2]);
			$filters['startdate'] = $start;
			$filters['enddate'] = $end;
			break;

			case 'year':
			if (!$offset) $offset = date('m-d-Y');
			else $offset = date('m-d-Y',$offset);
			$offset = explode('-',$offset);
			$start = mktime(0,0,0,1,1,$offset[2]);
			$end = mktime(0,0,0,1,1,$offset[2]+1);
			$filters['startdate'] = $start;
			$filters['enddate'] = $end;
			break;

		}

		// prepare typolinks

		$selection = new tx_skcalendar_internal();
		$selection->setFilters($filters);
		$selection->prepareQuery();
		$selection->getResults();
		
		switch ($this->conf['type']) {
			case 'week':
			$calendar = new tx_skcalendar_weekview($selection,'list',$conf);
			$calendar->setRange($filters['startdate'],$filters['enddate']);
			$calendar->createCalendar();
			$calendar->parseCalendar();
			break;

			case 'weekbox';
			$calendar = new tx_skcalendar_weekboxview($selection,'list',$conf);
			$calendar->setRange($filters['startdate'],$filters['enddate']);
			$calendar->parseCalendar();
			break;

			case 'day':
			$calendar = new tx_skcalendar_weekview($selection,'list',$conf);
			$calendar->setRange($filters['startdate'],$filters['enddate']);
			$calendar->parseCalendar();
			break;

			case 'month':
			$calendar = new tx_skcalendar_monthview($selection,'list',$conf);
			$calendar->setRange($filters['startdate'],$filters['enddate']);
			$calendar->createCalendar();
			$calendar->parseCalendar();
			break;

			case 'year':
			$calendar = new tx_skcalendar_yearview($selection,'list',$conf);
			$calendar->setRange($filters['startdate'],$filters['enddate']);
			$calendar->createCalendar();
			$calendar->parseCalendar();
			break;

		}


		return $this->pi_wrapInBaseClass($calendar->content);
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"]);
}

?>