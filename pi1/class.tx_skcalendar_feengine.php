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
* Frontendengine Provides basic functions for all other forms of displaying or providing data
*
* @author	Volker Biberger <info@sitekick.de>
*/
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_htmlview.php');

class fe_engine {
	var $scriptRelPath = "pi1/class.tx_skcalendar_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "sk_calendar";	// The extension key.
	var $categories = array();
	var $targetgroups = array();
	var $organizers = array();
	var $locations = array();
	var $holidays = array();
	var $conf;
	var $container;
	var $view;
	var $events;
	var $offset;
	var $fromdate;
	var $todate;
	var $year;
	var $content;
	
	function tx_skcalendar_feengine ($container,$conf) {
	$this->conf = $conf;
	$this->container = $container;
	if (!$conf['target']) $this->conf['target'] = $GLOBALS["TSFE"]->id;
	$this->view = $conf['view'];
	$this->events = $container->result;
	if (!$this->events) $this->events = Array();
	
	$this->categories = $container->categories;
	$this->locations = $container->locations;
	$this->organizers = $container->organizers;
	$this->targetgroups = $container->targetgroups;
	$this->offset = $conf['offset'];
	$this->year = date('Y',$this->offset);
}

	function createHolidays ($lang) {
	$lang = strtolower($lang);
		//international christian holidays
		$this->holidays = array(
		'newyear' => "1.1.",
		'threekings' => "6.1.",
		'christmas25' => "25.12."
		);

		// Advent
		$start_date = mktime(0,0,0,12,24,$this->year);
		$weekday = date("w", $start_date);
		if ($weekday == 0) $weekday=6;
		$this->holidays['advent4'] = date("j.n.", ($start_date-($weekday*86400)));
		$this->holidays['advent3'] = date("j.n.", ($start_date-($weekday*86400)-604800));
		$this->holidays['advent2'] = date("j.n.", ($start_date-($weekday*86400)-1209600));
		$this->holidays['advent1'] = date("j.n.", ($start_date-($weekday*86400)-1814400));


		// Easter
		// Sometimes easter_date() is not available, that is why we use a fallback here.
		// I hope that easter_date() will be standardfunction on all PHP installations.
		// Also there is a offset from 50 Seconds here. Believe it or not, some PHP-Versions produce wrong
		// dates by adding a second here and there.
		$easter_arr = array(
		2004 => 1081634422,
		2005 => 1111878050,
		2006 => 1145138450,
		2007 => 1175983250,
		2008 => 1206226850,
		2009 => 1239487250,
		2010 => 1270332050
		);

		if (!function_exists('easter_date')) $easter = $easter_arr[$this->year];
		else $easter = easter_date($this->year);
		$this->holidays['easter'] = date("j.n.", $easter);
		$this->holidays['easterfr'] = date("j.n.", $easter-172800);
		$this->holidays['eastermo'] = date("j.n.", $easter+86400);

		//Pfingsten
		$pfingsten['day'] = date("d", $easter);
		$pfingsten['month'] = date("m", $easter);
		$pfingsten_date = mktime(0,0,0,$pfingsten['month'],$pfingsten['day']+49,$this->year);
		$this->holidays['pentacost'] = date("j.n.", $pfingsten_date);
		$this->holidays['pentacostmo'] = date("j.n.", ($pfingsten_date+86400));

		// local Holidays
		switch ($lang) {
			case 'de': // german holidays
			$this->holidays = array(
			'mai' => "1.5.",
			//'mariaehf' => "15.8.", // Bavaria should be added by Typoscript later
			'dteinheit' => "3.10.",
			'allerheilig' => "1.11.",
			'christmas26' => "26.12."
			);

			// Christi Himmelfahrt
			$himmelfahrt = mktime(0,0,0,$pfingsten['month'],$pfingsten['day']+39,$this->year);
			$this->holidays['christihf'] = date("j.n.", $himmelfahrt);

			// Fronleichnam
			$fronleichnam['day'] = date("d", $easter);
			$fronleichnam['month'] = date("m", $easter);
			$fronleichnam_date = mktime(0,0,0,$fronleichnam['month'],$fronleichnam['day']+60,$this->year);
			$this->holidays['fronleich'] = date("j.n.", $fronleichnam_date);

			break;
		}
	}
	
	function pi_getLL($id) {
		tslib_pibase::tslib_pibase();
		tslib_pibase::pi_setPiVarDefaults();
		tslib_pibase::pi_loadLL();
		return tslib_pibase::pi_getLL($id);
	}
	
		function getMonthName($month) {
		$date_unix = mktime(0,0,0,$month,1,$year);
		$name = strftime("%B",$date_unix);
		return $name;
	}

	function getCategory($catid) {
		return $this->categories[$catid];
	}

	function getOrganizer($orga) {
		return $this->organizers[$orga];
	}

	function getTargetgroup($target) {
		return $this->targetgroups[$target];
	}


	function setRange($offset,$todate) {
		$this->fromdate = $offset;
		$this->todate = $todate;
	}
}
?>