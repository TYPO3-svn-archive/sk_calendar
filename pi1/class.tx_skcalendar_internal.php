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


class tx_skcalendar_internal extends tx_skcalendar_selection {
var $prefix = 'INT';
var $categories =array();
var $locations = array();
var $organizers = array();
var $targetgroups = array();


	/**
	* @return void
	* @desc Prepares the query in this case builds the SQL-Statement
	*/
	function prepareQuery () {
		$this->query = "SELECT * FROM tx_skcalendar_events WHERE NOT deleted AND NOT hidden";
		if (is_array($this->filters[targetgroups]))
		{
			$this->query .= 'AND targetgroup in (' . $this->filters[targetgroups][0];
			next ($this->filters[targetgroups]);
			while (list($id, $value) = each ($this->filters[targetgroups])) $this->query .= ',' . $value;
			$this->query .= ') ';
		}
		if (is_array($this->filters[categories]))
		{
			$this->query .= 'AND category in (' . $this->filters[categories][0];
			next ($this->filters[categories]);
			while (list($id, $value) = each ($this->filters[categories])) $this->query .= ',' . $value;
			$this->query .= ') ';
		}
		if (is_array($this->filters[locations]))
		{
			$this->query .= 'AND category in (' . $this->filters[locations][0];
			next ($this->filters[locations]);
			while (list($id, $value) = each ($this->filters[locations])) $this->query .= ',' . $value;
			$this->query .= ') ';
		}
		if (is_array($this->filters[organziers]))
		{
			$this->query .= 'AND organizer in (' . $this->filters[organziers][0];
			next ($this->filters[organziers]);
			while (list($id, $value) = each ($this->filters[organziers])) $this->query .= ',' . $value;
			$this->query .= ') ';
		}
		
		parent::prepareQuery();
	}
	
		function getAddInfo () {
	// retrieve data
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,title,color','tx_skcalendar_category','NOT deleted AND NOT hidden','','title');
	while (list($id,$name,$color) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result)) $this->categories[$id] = array($id,$name,$color);
	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,title','tx_skcalendar_location','NOT deleted AND NOT hidden','','title');
	while (list($id,$name) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result)) $this->locations[$id] = array($id,$name);
	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,name','tx_skcalendar_organizer','NOT deleted AND NOT hidden','','name');
	while (list($id,$name) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result)) $this->organizers[$id] = array($id,$name);
	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,title','tx_skcalendar_targetgroup','NOT deleted AND NOT hidden','','title');
	while (list($id,$name) = $GLOBALS['TYPO3_DB']->sql_fetch_row($result)) $this->targetgroups[$id] = array($id,$name);
}


	/**
	* @return boolean
	* @desc Executes the query and gets the results
	*/
	function getResults () {
		$this->prepareQuery();
		$result = mysql_query($this->query);
		if ($result) {
			while ($event = mysql_fetch_array($result,MYSQL_ASSOC))
			{
				$event['color'] = $this->categories[$event['category']][2]; // put some color into the event
				$this->result[] = $event;

			}
			$this->postprocessQuery();
		}
		else $this->error = 'There is a Problem with the database';

		// return operation result
		parent::getResults();
	}

}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_internal.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_internal.php"]);
}

