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
* Templateenging Provides basic functions for all other forms of displaying or providing data
*
* @author	Volker Biberger <info@sitekick.de>
*/
// Output goes into $this->snippet which is unset getting the output from each function. This is done in case an extending class needs to manipulate code after the execution of the parent function in defaultTE

class tx_skcalendar_defaultTF extends tx_skcalendar_te {
var $snippet;
	
	function templatefunc_e_date () {
			$this->snippet = date('d.m.Y',$this->data['e_date']);
			
		}
		
	function templatefunc_wrapit () {
			$this->snippet =  $this->TempData['wrapit'];
			
		}
	
	function templatefunc_detaillink() {
		if ($this->TempData['linktext']) $linktext = $this->TempData['linktext'];
		else $linktext = $this->data['e_title'];
		$this->snippet = $this->detailLink($this->data['e_uid'],$linktext,$this->data['c_color'],$this->data['e_date']);
	}
			
	function templatefunc_e_start_time () {
		if ($this->data['e_start_time']) {
			$hours = floor($this->data['e_start_time']/3600);
			$minutes = ($this->data['e_start_time']%3600)/60; // modulus
			if ($minutes < 10) $minutes = '0' . $minutes;
			$this->snippet =  $hours . ':' . $minutes . ' ';
		}
		}
		
	function templatefunc_e_end_time () {
		if ($this->data['e_end_time']) {
			$hours = floor($this->data['e_end_time']/3600);
			$minutes = ($this->data['e_end_time']%3600)/60; // modulus
			if ($minutes < 10) $minutes = '0' . $minutes;
			$this->snippet =  '- ' . $hours . ':' . $minutes;
			}
		}
		
	function templatefunc_backlink() {
		$this->snippet = $this->TempData['backlink'];
	}
		
	function templatefunc_nextlink() {
		$this->snippet = $this->TempData['nextlink'];
	}
	function templatefunc_backlinkyear() {
		$this->snippet = $this->TempData['backlinkyear'];
	}
		
	function templatefunc_nextlinkyear() {
		$this->snippet = $this->TempData['nextlinkyear'];
	}

	function templatefunc_daystyle() {
		$this->snippet = $this->TempData['style'];
	}

	function templatefunc_dayname() {
		$this->snippet = $this->TempData['name'];
	}

	function templatefunc_monthname() {
		$this->snippet = strftime('%B',$this->TempData['date']);
	}

	function templatefunc_actdate() {
		$this->snippet = strftime('%A, %d.%m.%Y',$this->TempData['date']);
	}
	
	function templatefunc_year() {
		$this->snippet = strftime('%Y',$this->TempData['date']);
	}

	function templatefunc_weekno() {
		$this->snippet = strftime('%W',$this->TempData['timestamp']-3600); // minus one hour
		if ($this->snippet==0) $this->snippet= strftime('%W',mktime(0,0,0,12,31,date('Y',$this->TempData['timestamp'])-1))+1; //there seems to be a problem with the strftime function
	}


	function templatefunc_sortlink_date() {
		$this->snippet = $this->TempData['sortlink_date'];
	}

	function templatefunc_sortlink_title() {
		$this->snippet = $this->TempData['sortlink_title'];
	}

	function templatefunc_sortlink_organizer() {
		$this->snippet = $this->TempData['sortlink_organizer'];
	}

	function templatefunc_sortlink_location() {
		$this->snippet = $this->TempData['sortlink_location'];
	}

	function templatefunc_sortlink_category() {
		$this->snippet = $this->TempData['sortlink_category'];
	}


}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_defaultTF.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_defaultTF.php"]);
}

?>