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
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_HTMLview.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_PDFview.php');

// FE-Engine
class tx_skcalendar_feEngine {
	var $results;
	var $calendarArray;
	var $container;
	var $type;
	var $holidays;
	var $year;
	var $content;
	var $notch;

	/**
	* @return void
	* @desc init stuff
	*/
	function tx_skcalendar_feEngine($container, $type) {
		$this->container = $container;
		$this->type = $type;
		$this->year = date('Y');
		$this->holidays = array();
		$this->events = $container->result;
		setlocale(LC_TIME, "de_DE"); // should be edited by TS later

	}

	/**
	* @return void
	* @desc set the year
	*/
	function setYear($year) {
		$this->year = intval($year);
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

	/**
	* @return void
	* @desc create calendararray
	*/
	function createCalendar ($fromdate=false,$todate) { //Dates have form 'month-day'
	if (!$fromdate) $fromdate = date('m-d');
	// one never knows
	if ($fromdate >$todate) {
		$temp = $todate;
		$todate = $fromdate;
		$fromdate = $temp;
		unset ($temp);
	}
	$fromdate = explode('-',$fromdate);
	$todate = explode('-',$todate);
	// forming the basic calendar array with the form $calendar[month][day]
	for ($temp=intval($fromdate[0]); $temp<=(intval($todate[0])); $temp++)
	{
		$date_unix = mktime(0,0,0,$temp,1,$this->year);
		$count_days = date("t",$date_unix);
		for ($day=1; $day<=$count_days; $day++)
		{
			$date_unix = mktime(0,0,0,$temp,$day,$this->year);
			$d_name = strftime("%d %a",$date_unix);
			$d_name[strlen($d_name)-1] = '';
			$this->calendarArray[$temp][$day]['d_name'] = $d_name;
			if (date("w",$date_unix)==0) $this->calendarArray[$temp][$day]['isholiday']='1'; // sunday
		}
	}

	while (list($name,$date) = each($this->holidays))
	{
		$date = explode('.',$date);
		$this->calendarArray[$date[1]][$date[0]]['hname'] = $name; // name in this case is an unique string that can be used in the $this->pi_getLL() function later
		$this->calendarArray[$date[1]][$date[0]]['isholiday'] = 1;
	}
	while (list($id, $event) = each($this->events))
	{
		$date = date('Y-m-d',$event['date']);
		$date = explode('-',$date);
		$this->calendarArray[intval($date[1])][intval($date[2])]['events'][] = $event;
	}
	
	}
	
	function getMonthName($month) {
			$date_unix = mktime(0,0,0,$month,1,$year);
	$name = strftime("%B",$date_unix);
	return $name;
}
}
