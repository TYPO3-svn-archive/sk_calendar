<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Sven Wilhelm / Icecrash.com <wilhelm@icecrash.com>
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
/*
 * $Id$
 */

require_once('class.tx_skcalendar_error.php');

/**
 * Class Event
 *
 * @author Sven Wilhelm <wilhelm@icecrash.com>
 */

class tx_skcalendar_event {

	var $id;
	
	var $title;
	
	var $startTime;
	
	var $endTime;
	
	var $location;

	var $cyle;
	
	var $cycleEnd;	
	
	var $reminder;
	
	var $readAccess;
	
	var $writeAccess;
	
	var $appointmentType;
	
	function tx_skcalendar_event() {
		
	}

	
	/**
	 * 
	 */	
	function setId($id) {
		if(preg_match('/.*#.*/', $id)) {
			$this->id = $id;
			return $this->id;
		} else {
			return new tx_skcalendar_error('Not a valid event id');
		}
	}
	
	
	/**
	 * 	
	 * @param $title
	 * @return uncknown
	 */
	function setTitle($title) {
		$this->title = $title;
		return $this->title;
	}
	
	
	/**
	 * 	
	 * @param $time
	 * @return mixed tx_skcalendar_error or uncknown
	 */
	function setStartTime($time) {
		if(!is_int($time)) {
			return new tx_skcalendar_error('Not a unix timestamp');
		} else {
			$this->startTime = $time;
			return $this->startTime;
		}
	}

	
	/**
	 * 	
	 * @param $time
	 * @return mixed tx_skcalendar_error or uncknown
	 */
	function setEndTime($time) {
		if(!is_int($time)) {
			return new tx_skcalendar_error('Not a unix timestamp');
		} else {
			$this->endTime = $time;
			return $this->endTime;
		}
	}
	
	
	/**
	 * 	
	 * @param $location
	 * @return uncknown
	 */
	function setLocation($location) {
		$this->location = $location;
		return $this->location;
	}

}

?>