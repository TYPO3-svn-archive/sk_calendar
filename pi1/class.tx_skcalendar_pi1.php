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
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_feEngine.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_selection.php');

class tx_skcalendar_pi1 extends tslib_pibase {
	var $prefixId = "tx_skcalendar_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_skcalendar_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "sk_calendar";	// The extension key.
	var $notch; // move between days/weeks/months/years
	
	/**
	 * This is only an example output of the data, so other ways of displaying data will be developed on demand.
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		if (!$GLOBALS['HTTP_POST_VARS']['fromdate']) $fromdate = '01-01';
		if (!$GLOBALS['HTTP_POST_VARS']['todate']) $todate = '12-31';
		debug($notch);
		// prepare typolinks
		$this->allowCaching = 0;
		$link_conf = $this->typolink_conf;
		
		$link_conf['parameter'] = $GLOBALS["TSFE"]->id;
		$link_conf["parameter."]["wrap"] = "|,".$GLOBALS["TSFE"]->type;
		$link_conf["useCacheHash"]=$this->allowCaching;
		$link_conf["no_cache"]=!$this->allowCaching;
		
		$selection = new tx_skcalendar_internal();
		$selection->prepareQuery();
		$selection->getResults();
		
		$calendar = new tx_skcalendar_HTMLview($selection,'list',$conf);
		$calendar->createHolidays('DE');
		$calendar->createCalendar($fromdate,$todate);
		$calendar->parseCalendar();
		
	return $this->pi_wrapInBaseClass($calendar->content);
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"]);
}

?>