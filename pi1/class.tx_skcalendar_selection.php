<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2004 Volker Biberger / sitekick.de <info@sitekick.de>
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

// this is an abstract class only to be used by extending it.
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_internal.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'calendar_functions.php');

class tx_skcalendar_selection {
	var $result;
	var $entry;
	var $dataSource;
	var $filters;
	var $query;
	var $error;
	var $offset;
	
	
	/**
	* @return void
	* @desc Connects to the Source
	*/
	function connectSource() {
	}

	/**
	* @return void
	* @desc Sets the Filters
	*/
	function setFilters ($filter_array = false) {
		$this->filters[startdate]=intval($filter_array[startdate]); // startdate unixTS
		$this->filters[enddate]=intval($filter_array[enddate]); // enddate unixTS
		$this->filters[categories]=$filter_array[categories]; // array with categories
		$this->filters[locations]=$filter_array[locations]; // array with locations
		$this->filters[organizers]=$filter_array[organizers]; // array with organizers
		$this->filters[targetgroups]=$filter_array[targetgroups]; // array with targetgroups
	}
	
	/**
	* @return void
	* @desc gets additional information like categories, etc.
	*/
	function getAddInfo () {
	}
	
	/**
	* @return void
	* @desc Prepares the query in this case builds the SQL-Statement
	*/
	function prepareQuery () {
	}
	
	/**
	* @return void
	* @desc Executes the query and gets the results
	*/
	function getResults () {
		if ($this->error) return false;
		else return true;
	}
	
	/**
	* @return void
	* @desc Alters the result Array by adding e.g. recurring events
	*/
	function postprocessQuery() {
		$this->result = addRecurringEvents($this->result);
		$this->result = filterRange($this->result,$this->filters);
		$this->getAddInfo();
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_selection.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_selection.php"]);
}

			