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
		if ($GLOBALS['HTTP_POST_VARS']['tx_skcalendar']['offset']) $offset = $GLOBALS['HTTP_POST_VARS']['tx_skcalendar']['offset'];
		elseif ($GLOBALS['HTTP_GET_VARS']['tx_skcalendar']['offset']) $offset = $GLOBALS['HTTP_GET_VARS']['tx_skcalendar']['offset'];
		if (!$offset) $offset = '01-01-' . date('Y');
		if (!$this->conf['type']) $this->conf['type'] = 'week';
		
		switch ($this->conf['type']) {
			case 'week':
			$unixdate = explode('-',$offset);
			$filters['startdate'] = mktime(0,0,0,$unixdate[0],$unixdate[1],$unixdate[2]);
			$filters['enddate'] = mktime(0,0,0,$unixdate[0],$unixdate[1]+7,$unixdate[2]);
			break;
			}
		
		// prepare typolinks
		$this->allowCaching = 0;
		$link_conf = $this->typolink_conf;
		
		$link_conf['parameter'] = $GLOBALS["TSFE"]->id;
		$link_conf["parameter."]["wrap"] = "|,".$GLOBALS["TSFE"]->type;
		$link_conf["useCacheHash"]=$this->allowCaching;
		$link_conf["no_cache"]=!$this->allowCaching;
		
		// prepare filters
		
		
		$selection = new tx_skcalendar_internal();
		$selection->setFilters($filters);
		$selection->prepareQuery();
		$selection->getResults();
		
		$calendar = new tx_skcalendar_weekview($selection,'list',$conf);
		$calendar->setOffset($offset);
		$calendar->createCalendar($offset);
		$calendar->parseCalendar();
		
	return $this->pi_wrapInBaseClass($calendar->content);
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"]);
}

?>