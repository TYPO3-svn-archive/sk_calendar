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
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_weekview.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_boxview.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_detailview.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_yearview.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_monthview.php');

// FE-Engine
class tx_skcalendar_calendarview {
	var $categories;
	var $targetgroups;
	var $organizers;
	var $results;
	var $calendarArray;
	var $container;
	var $type;
	var $holidays;
	var $year;
	var $content;
	var $offset;
	var $todate;
	var $targetpage;
	var $events;

	/**
	* @return void
	* @desc init stuff
	*/
	function tx_skcalendar_calendarview($container, $type,$conf) {
		if ($conf['target']) $this->targetpage = $conf['target'];
		else $this->targetpage = $GLOBALS["TSFE"]->id;

		$this->container = $container;
		$this->type = $type;
		$this->year = date('Y');
		$this->holidays = array();
		$this->events = $container->result;
		if (!$this->events) $this->events = Array();
		setlocale(LC_TIME, "de_DE"); // should be edited by TS later
	}


	/**
	* @return void
	* @desc create the differnt holidays
	*/
	function createHolidays ($lang,$add_holidays=Array()) {
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
			$holidays = array(
			'mai' => "1.5.",
			'mariaehf' => "15.8.", // Bavaria should be added by Typoscript later
			'dteinheit' => "3.10.",
			'allerheilig' => "1.11.",
			'christmas25' => "26.12."
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

	function setRange($offset,$todate) {
		$this->offset = $offset;
		$this->todate = $todate;
		$this->year = date('Y',$offset);
	}

	function makeLinks() {
	}

	/**
	* @return void
	* @desc create calendararray
	*/
	function createCalendar () {
		$from_m = intval(date('m',$this->offset));
		$to_m = intval(date('m',$this->todate));
		// forming the basic calendar array with the form $calendar[month][day]
		for ($temp=$from_m; $temp<=$to_m; $temp++)
		{
			$date_unix = mktime(0,0,0,$temp,1,$this->year);
			$count_days = date("t",$date_unix);
			for ($day=1; $day<=$count_days; $day++)
			{
				$date_unix = mktime(0,0,0,$temp,$day,$this->year);
				$this->calendarArray[$temp][$day]['d_name']['no'] = strftime("%d",$date_unix);
				$this->calendarArray[$temp][$day]['d_name']['short'] = strftime("%a",$date_unix);
				$this->calendarArray[$temp][$day]['d_name']['long'] = strftime("%A",$date_unix);
				if (date("w",$date_unix)==0) $this->calendarArray[$temp][$day]['isholiday']='1'; // sunday
			}
		}

		while (list($name,$date) = each($this->holidays))
		{
			$date = explode('.',$date);
			if ($this->calendarArray[$date[1]]) {
				$this->calendarArray[$date[1]][$date[0]]['hname'] = $name; // name in this case is an unique string that can be used in the $this->pi_getLL() function later
				$this->calendarArray[$date[1]][$date[0]]['isholiday'] = 1;
			}
		}
		while (list($id, $event) = each($this->events))
		{
			$date = date('Y-m-d',$event['date']);
			$date = explode('-',$date);
			$this->calendarArray[intval($date[1])][intval($date[2])]['events'][] = $event;
		}

	}
	
	function pdfLink() {
		$pdf['tx_skcalendar[offset]'] = $this->offset;
		$pdf['tx_skcalendar[view]'] = 'year';
		$this->content .= '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$pdf) . '">Jahreskalender als PDF</a>';
	}

	function getMonthName($month) {
		$date_unix = mktime(0,0,0,$month,1,$year);
		$name = strftime("%B",$date_unix);
		return $name;
	}

	function getCategory($catid) {
		if (!$this->categories) {
			$sql = 'SELECT * FROM tx_skcalendar_category WHERE NOT hidden AND NOT deleted';
			$result = mysql_query($sql);
			while ($data = mysql_fetch_array($result,MYSQL_ASSOC)) $this->categories[$data['uid']] = $data;
		}
		return $this->categories[$catid];
	}

	function getOrganizer($orga) {
		if (!$this->organizers) {
			$sql = 'SELECT * FROM tx_skcalendar_organizer WHERE NOT hidden AND NOT deleted';
			$result = mysql_query($sql);
			while ($data = mysql_fetch_array($result,MYSQL_ASSOC)) $this->organizers[$data['uid']] = $data;
		}
		return $this->organizers[$orga];
	}

	function getTargetgroup($target) {
		if (!$this->targetgroups) {
			$sql = 'SELECT * FROM tx_skcalendar_targetgroup WHERE NOT hidden AND NOT deleted';
			$result = mysql_query($sql);
			while ($data = mysql_fetch_array($result,MYSQL_ASSOC)) $this->targetgroups[$data['uid']] = $data;
		}
		return $this->targetgroups[$target];
	}

}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_calendarview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_calendarview.php"]);
}

