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
class tx_skcalendar_htmlview extends fe_engine {
	var $results;
	var $calendarArray;

	/**
	* @return void
	* @desc create the differnt holidays
	*/

	
	function prepareTypolink() {
		$link['tx_skcalendar_pi1[offset]'] = $this->offset;
		$link['tx_skcalendar_pi1[targetgroups]'] = $this->container->filters['targetgroups'][0];
		$link['tx_skcalendar_pi1[categories]'] = $this->container->filters['categories'][0];
		$link['tx_skcalendar_pi1[locations]'] = $this->container->filters['locations'][0];
		$link['tx_skcalendar_pi1[organizers]'] = $this->container->filters['organizers'][0];
		$link['tx_skcalendar_pi1[view]'] = $this->view;
		$link['no_cache'] = 1; 
		return $link;
	}

	function makeLinks() {
			$link_arr = array ('week' => 'Wochenansicht', 'month' => 'Monatsansicht', 'year' => 'Jahresansicht als PDF');
	
		$this->content .= '<table cellspacing=0 cellpadding=0 width=100%><tr><td colspan=4><b>Alternative Ansichten</b></td></tr><tr valign=top>';
		$link= $this->prepareTypolink();
		while (list($key,$value) = each($link_arr)) {

		$link['tx_skcalendar_pi1[view]'] = $key;
		$this->content .= '<td><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$link) . '">'. $value .'</a></td>';
	}
	$this->content .= '</tr></table>';

	}
	
	function makeFilters() {
	if ((count($this->categories)>1) || (count($this->locations)>1) || (count($this->organizers)>1) || (count($this->targetgroups)>1)) {
	$this->content .= '<br><form action="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($this->conf['target']) . '" method="post"><input type=hidden name=tx_skcalendar_pi1[view] value=' . $this->type . '><input type=hidden name=tx_skcalendar_pi1[offset] value=' . $this->offset . '><table><tr><td nowrap colspan=5><b>Anzeige Filtern</b></td></tr><tr><td>';
	
	reset ($this->categories);
	if (count($this->categories)>1) {
	$cat_sel[$this->container->filters['categories'][0]] = ' selected';
	$this->content .= '<td><select name="tx_skcalendar_pi1[categories]"><option value="">Alle Kategorien</option>';
	while (list(,$data) = each ($this->categories)) $this->content .= '<option value="' . $data[0] . '"'. $cat_sel [$data[0]] . '>' . $data[1] . '</option>';
	$this->content .= '</select></td>';
	}
	reset ($this->locations);
	if (count($this->locations)>1) {
	$loc_sel[$this->container->filters['locations'][0]] = ' selected';
	$this->content .= '<td><select name="tx_skcalendar_pi1[locations]"><option value="">Alle Veranstaltungsorte</option>';
	while (list(,$data) = each ($this->locations)) $this->content .= '<option value="' . $data[0] . '"'. $loc_sel [$data[0]] . '>' . $data[1] . '</option>';
	$this->content .= '</select></td>';
	}
	reset ($this->organizers);
	if (count($this->organizers)>1) {
	$org_sel[$this->container->filters['organizers'][0]] = ' selected';
	$this->content .= '<td><select name="tx_skcalendar_pi1[organizers]"><option value="">Alle Veranstalter</option>';
	while (list(,$data) = each ($this->organizers)) $this->content .= '<option value="' . $data[0] . '"'. $org_sel [$data[0]] . '>' . $data[1] . '</option>';
	$this->content .= '</select></td>';
	}
	reset ($this->targetgroups);
	if (count($this->targetgroups)>1) {
	$tar_sel[$this->container->filters['targetgroups'][0]] = ' selected';
	$this->content .= '<td><select name="tx_skcalendar_pi1[targetgroups]"><option value="">Alle Zielgruppen</option>';
	while (list(,$data) = each ($this->targetgroups)) $this->content .= '<option value="' . $data[0] . '"'. $tar_sel [$data[0]] . '>' . $data[1] . '</option>';
	$this->content .= '</select></td>';
	}

	// close form
	$this->content .= '<td><input type=submit value="Anzeige Filtern"></td></tr></table></form>';
	}
	else $this->content .= '<br>Kein Filtern möglich, da mindestens zwei Kategorien, zwei Zielgruppen, zwei Veranstaltungsorte oder zwei Veranstalter angelegt sein müssen. Holen Sie dies entweder nach oder deaktivieren Sie die Filter mit TypoScript.';
	
	
	}
	function makeNavigation() {
}

	/**
	* @return void
	* @desc create calendararray
	*/
	function createCalendar () {
		$from_m = intval(date('m',$this->from));
		$to_m = intval(date('m',$this->todate));
		// forming the basic calendar array with the form $calendar[month][day]
		for ($temp=$from_m; $temp<=$to_m; $temp++)
		{
			$date_unix = mktime(0,0,0,$temp,1,$this->year);
			$now = mktime(0,0,0);
			$count_days = date("t",$date_unix);
			for ($day=1; $day<=$count_days; $day++)
			{
				$date_unix = mktime(0,0,0,$temp,$day,$this->year);
				$this->calendarArray[$temp][$day]['d_name']['no'] = strftime("%d",$date_unix);
				$this->calendarArray[$temp][$day]['d_name']['short'] = strftime("%a",$date_unix);
				$this->calendarArray[$temp][$day]['d_name']['long'] = strftime("%A",$date_unix);
				if ($date_unix < $now) $this->calendarArray[$temp][$day]['style'] = 'past_weekday';
				else $this->calendarArray[$temp][$day]['style'] = 'weekday';
				if (date("w",$date_unix)==0) $this->calendarArray[$temp][$day]['style']='holiday'; // sunday
			}
		}

		while (list($name,$date) = each($this->holidays))
		{
			$date = explode('.',$date);
			if ($this->calendarArray[$date[1]]) {
				$this->calendarArray[$date[1]][$date[0]]['d_name']['short'] = $this->pi_getLL($name);
				$this->calendarArray[$date[1]][$date[0]]['d_name']['long'] = $this->pi_getLL($name);
				$this->calendarArray[$date[1]][$date[0]]['style']='holiday';
			}
		}
		while (list($id, $event) = each($this->events))
		{
			$date = date('Y-m-d',$event['date']);
			$date = explode('-',$date);
			$this->calendarArray[intval($date[1])][intval($date[2])]['events'][] = $event;
		}
	}


}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_calendarview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_calendarview.php"]);
}

?>