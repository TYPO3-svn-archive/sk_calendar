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

class tx_skcalendar_te extends tx_skcalendar_htmlview{
	var $data;
	var $my_cObj;
	var $markers;
	var $code;
	var $subcode;
	var $TempData;
	
	function tx_skcalendar_te() {
		$this->my_cObj = t3lib_div::makeInstance('tslib_cObj');
	}
	
	function getSubpart($subpart) {
		
		$this->subcode = $this->my_cObj->getSubpart($this->code,$subpart);
		$this->markers['labels'] = array();
		$this->markers['markers'] = array();
		preg_match_all('/###[A-Z_a-z]+###/',$this->subcode,$markers); // get Markers
		while (list(,$value) = each($markers[0])) {
			
			if (strpos($value,'##LABEL_')==1) { // 0 is not a good position
				$value = substr($value,9,strlen($value)-12);
				$this->markers['labels'][]=$value; 
			}
			else {
				$value = substr($value,3,strlen($value)-6);
				$this->markers['markers'][] = $value;
			}
		}
		}
		
	function setTempData($data) {
		$this->TempData = $data;
	}
	
	function setCode ($code) {
		$this->tx_skcalendar_te(); // init Cobj
		$this->code = $code;
		}
	
	function setContainer($container) {
		$this->tx_skcalendar_feengine($container,$conf);
	}
	
	function setItem($data) {
		
		while (list($key,$value) = each($data)) {
			$this->data['e_'.$key] = $value; // e as event
			}
		if ($data['category']) {
			$temp = $this->getCategory($data['category']);
			if (!$temp) $temp = array();
			while (list($key,$value) = each($temp)) {
			$this->data['c_'.$key] = $value; // c as category
			}
			}
		if ($data['organizer']) {
			$temp = $this->getOrganizer($data['organizer']);
			if (!$temp) $temp = array();
			while (list($key,$value) = each($temp)) {
			$this->data['o_'.$key] = $value; // o as organizer
			}
			}
		if ($data['targetgroup']) {
			$temp = $this->getTargetgroup($data['targetgroup']);
			if (!$temp) $temp = array();
			while (list($key,$value) = each($temp)) {
			$this->data['t_'.$key] = $value; // t as Targetgroup
			}
			}
		if ($data['location']) {
			$temp = $this->getLocation($data['location']);
			if (!$temp) $temp = array();
			while (list($key,$value) = each($temp)) {
			$this->data['l_'.$key] = $value; // l as Location
			}
			}
		}
	
	function parseTemplate() {
		
		// Marker Ersetzen
		while (list(,$marker) = each($this->markers['labels'])) {
			$this->marker_array['###LABEL_'.$marker.'###'] = $this->pi_getLL(strtolower($marker));
			}
		while (list(,$marker) = each($this->markers['markers'])) {
			$function = 'templatefunc_'.strtolower($marker);
			if(method_exists($this,$function)) {
				$this->$function($this->data[strtolower($marker)]);
				$this->marker_array['###'.$marker.'###'] = $this->getSnippet();
				}
			else $this->marker_array['###'.$marker.'###'] = $this->data[strtolower($marker)];
			}
		$return = $this->my_cObj->substituteMarkerArrayCached($this->subcode, $this->marker_array);
		unset($this->tempData);
		unset($this->data);
		return $return;
	}
	
	function getSnippet() {
		$code = $this->snippet;
		unset ($this->snippet);
		return $code;
		}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_te.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_te.php"]);
}

?>