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
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_listview.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_archiveview.php');

// FE-Engine
class tx_skcalendar_htmlview extends tx_skcalendar_feengine {
	var $results;
	var $calendarArray;

	/**
	* @return void
	* @desc create the differnt holidays
	*/
	function detailLink($id,$text,$color,$date) {
		$link = $this->prepareTypolink();
		
		$link['tx_skcalendar_pi1[offset]'] = $date;
		$link['tx_skcalendar_pi1[view]'] = 'detail';
		$link['tx_skcalendar_pi1[uid]'] = $id;
		$return = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($this->conf['general']['target_pid'],$link) . '"><font color="' . $data['color'] . '">'. $text.'</font></a>';
return $return;
		}

	function prepareTypolink() {
		$link['tx_skcalendar_pi1[offset]'] = $this->offset;
		$link['tx_skcalendar_pi1[targetgroups]'] = $this->container->filters['targetgroups'][0];
		$link['tx_skcalendar_pi1[categories]'] = $this->container->filters['categories'][0];
		$link['tx_skcalendar_pi1[locations]'] = $this->container->filters['locations'][0];
		$link['tx_skcalendar_pi1[organizers]'] = $this->container->filters['organizers'][0];
		$link['tx_skcalendar_pi1[sword]'] = $this->container->filters['sword'];
		$link['tx_skcalendar_pi1[view]'] = $this->view;
		$link['no_cache'] = 1;
		return $link;
	}

	function makeLinks() {
		$link_arr = array ('week' => $this->pi_getLL('week_view'), 'month' =>  $this->pi_getLL('month_view'), 'list' =>  $this->pi_getLL('list_view'), 'year' => $this->pi_getLL('pdf_view'));

		$this->content .= '<table cellspacing=0 cellpadding=0 width=100%><tr><td colspan=4><b>' . $this->pi_getLL('other_views') . '</b></td></tr><tr valign=top>';
		$link= $this->prepareTypolink();
		while (list($key,$value) = each($link_arr)) {

			$link['tx_skcalendar_pi1[view]'] = $key;
			$this->content .= '<td><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$link) . '">'. $value .'</a></td>';
		}
		$this->content .= '</tr></table>';

	}

	function parseTime($wholeday, $date, $start, $end) {
		if (!$wholeday && $start) {
			$out = $this->pi_getLL('begin') . ': ' . gmstrftime('%H:%M',$start) . ' ' . $this->pi_getLL('clock');
			if ($end) {
				$out .= ' '. $this->pi_getLL('until') .' ' . gmstrftime('%H:%M',$end) . ' ' . $this->pi_getLL('clock');
			}
		}
		else $out = $this->pi_getLL('date') . ': ' . gmstrftime('%A, %d.%m %Y',$date);
		return $out;
	}

		function makeFilters() {
		$this->content .= '<br><form action="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($this->conf['target']) . '" method="post"><input type=hidden name=tx_skcalendar_pi1[view] value=' . $this->view . '><input type=hidden name=no_cache value=1><input type=hidden name=tx_skcalendar_pi1[offset] value=' . $this->offset . '><b>' . $this->pi_getLL('filter_view') . '</b></br>';
		$this->content .= '<table class=calendar_sword><tr valign=middle><td>' . $this->pi_getLL('searchword') . '</td><td><input type=text name=tx_skcalendar_pi1[sword] value="' . $this->container->filters['sword'] . '"></td></tr><tr></table>';
			$this->content .= '<table class=calendar_filters>';
			// dropdowns
			if ((count($this->categories)>1) || (count($this->locations)>1) || (count($this->organizers)>1) || (count($this->targetgroups)>1)) {
				



				reset ($this->categories);
				if (count($this->categories)>1) {
					$cat_sel[$this->container->filters['categories'][0]] = ' selected';
					$this->content .= '<tr valign=middle><td>' . $this->pi_getLL('choose_cat') . '</td><td><select name="tx_skcalendar_pi1[categories]"><option value="">' . $this->pi_getLL('all_cat') . '</option>';
					while (list(,$data) = each ($this->categories)) $this->content .= '<option value="' . $data['id'] . '"'. $cat_sel [$data['id']] . '>' . $data['name'] . '</option>';
					$this->content .= '</select></td></tr>';
					}

				reset ($this->locations);
				if (count($this->locations)>1) {
					$loc_sel[$this->container->filters['locations'][0]] = ' selected';
					$this->content .= '<tr valign=middle><td>' . $this->pi_getLL('choose_loc') . '</td><td><select name="tx_skcalendar_pi1[locations]"><option value="">' . $this->pi_getLL('all_location') . '</option>';
					while (list(,$data) = each ($this->locations)) $this->content .= '<option value="' . $data['id'] . '"'. $loc_sel [$data['id']] . '>' . $data['name'] . '</option>';
					$this->content .= '</select></td></tr>';
				}
				reset ($this->organizers);
				if (count($this->organizers)>1) {
					$org_sel[$this->container->filters['organizers'][0]] = ' selected';
					$this->content .= '<tr valign=middle><td>' . $this->pi_getLL('choose_org') . '</td><td><select name="tx_skcalendar_pi1[organizers]"><option value="">' . $this->pi_getLL('all_organizer') . '</option>';
					while (list(,$data) = each ($this->organizers)) $this->content .= '<option value="' . $data['id'] . '"'. $org_sel [$data['id']] . '>' . $data['name'] . '</option>';
					$this->content .= '</select></td></tr>';
				}
				reset ($this->targetgroups);
				if (count($this->targetgroups)>1) {
					$tar_sel[$this->container->filters['targetgroups'][0]] = ' selected';
					$this->content .= '<tr valign=middle><td>' . $this->pi_getLL('choose_tar') . '</td><td><select name="tx_skcalendar_pi1[targetgroups]"><option value="">' . $this->pi_getLL('all_targetgroup') . '</option>';
					while (list(,$data) = each ($this->targetgroups)) $this->content .= '<option value="' . $data['id'] . '"'. $tar_sel [$data['id']] . '>' . $data['name'] . '</option>';
					$this->content .= '</select></td></tr>';
				}
				$this->content .= '</table>';
				// close form
				
			}
			elseif ($this->conf['warning']['filter']) $this->content .= '<tr valign=top><td>' . $this->pi_getLL('filter_error') . '</td></tr></table>';

			$this->content .= '<br><input type=submit value="' . $this->pi_getLL('do_filter') . '"></form>';


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

	if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_htmlview.php"])	{
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_htmlview.php"]);
	}

?>