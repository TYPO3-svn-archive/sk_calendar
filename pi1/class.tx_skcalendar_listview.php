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


// Manage HTML-View of Data

class tx_skcalendar_listview extends tx_skcalendar_htmlview {


	function tx_skcalendar_listview($container,$conf) {
		// calls mothership
		
		$this->tx_skcalendar_feengine($container,$conf);
	}
	
	function createCalendar() {
	return; // no calendarArray needed
	}

	function parseCalendar() {
		// set enviroment
		if (sizeof($this->container->result) < $this->conf['list']['limit']) $this->conf['list']['limit'] = sizeof($this->container->result);
		$i=1;
		if (strstr($this->conf['sorting'],'up')) {
			$sort_order = 'up';
			$sorting = substr($this->conf['sorting'],2);
			}
		elseif (strstr($this->conf['sorting'],'down')) {
			$sort_order = 'down';
			$sorting = substr($this->conf['sorting'],4);
		}

		
		
		
		// Prepare Array 
		$clean_arr = array();
		if (!$this->container->result) $this->container->result = array();
		$this->container->result = array_slice($this->container->result,$this->conf['notch']); // notch up

		while ($i<=$this->conf['list']['limit']) {
			list(,$data) = each($this->container->result);
			$clean_arr[$i . '_'  . $data[$sorting]] = $data;
			$i++;
		}
		if(next($this->container->result)) $next = 1;
		
		// sorting the array
		if ($sort_order == 'up') krsort($clean_arr,SORT_STRING);
		elseif ($sort_order == 'down') ksort($clean_arr,SORT_STRING);
		reset($clean_arr);
		
		// getting sortlink-Parameters
		$sortlink = $this->prepareTypolink();
		$sortlinkpara = Array ( 'date' => 'downdate', 'location' => 'downlocation', 'title' => 'downtitle', 'organizer' => 'downorganizer', 'category' => 'downcategory');
		if ($sort_order == 'up') $sortlinkpara[$sorting] = 'down' . $sorting;
		elseif ($sort_order == 'down') $sortlinkpara[$sorting] = 'up' . $sorting;
		
		$sortimg[$sorting] = '<img src="' . t3lib_extMgm::siteRelPath('sk_calendar') . 'pi1/images/sort_' . $sort_order . '.gif" border=0>';
		
		// output
		
		if ($this->conf['general']['showfilters']) $this->makeFilters();
		$this->content .= '<table cellspacing=0 cellpadding=0 border=0 width=95%><tr valign=top class=list_header>';
		$sortlink['tx_skcalendar_pi1[sorting]'] = $sortlinkpara['date'];
		$this->content .= '<td><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$sortlink) . '">' . $this->pi_getLL('date') . $sortimg['date'] . '</a></td><td>&nbsp;</td>';
		$sortlink['tx_skcalendar_pi1[sorting]'] = $sortlinkpara['location'];
		$this->content .= '<td><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$sortlink) . '">' . $this->pi_getLL('location') . $sortimg['location'] . '</a></td><td>&nbsp;</td>';
		$sortlink['tx_skcalendar_pi1[sorting]'] = $sortlinkpara['title'];
		$this->content .= '<td><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$sortlink) . '">' . $this->pi_getLL('title') . $sortimg['title'] . '</a></td><td>&nbsp;</td>';
		$sortlink['tx_skcalendar_pi1[sorting]'] = $sortlinkpara['organizer'];
		$this->content .= '<td><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$sortlink) . '">' . $this->pi_getLL('organizer') . $sortimg['organizer'] . '</a></td><td>&nbsp;</td>';
		$sortlink['tx_skcalendar_pi1[sorting]'] = $sortlinkpara['category'];
		$this->content .= '<td><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$sortlink) . '">' . $this->pi_getLL('category') .$sortimg['category'] .  '</a></td></tr>';
		
		if ($clean_arr) { // we do have entries
		while (list(,$data) = each($clean_arr)) {
			$this->content .= '<tr valign=top class=list_data><td>'.date('d.m.Y',$data['date']).'</td><td>&nbsp;</td><td>'.$this->getLocation($data['location']).'</td><td>&nbsp;</td><td>'.$this->detailLink($data['uid'],$data['title'],$data['color'],$data['date']).'</td><td>&nbsp;</td><td>'.$this->getOrganizer($data['organizer']).'</td><td>&nbsp;</td><td>'.$this->getCategory($data['category'],'name').'</td></tr>';
			}
			}
			else { // no entries
			$this->content .= '<tr valign=top class=list_data><td colspan=9 align=center><br>' . $this->pi_getLL('no_entries') . '</td></tr>';
			}
		$this->content .= '<tr><td>&nbsp;</td></tr><tr><td colspan=9>' . $this->makeNavigation($next,$this->conf['notch'], $sort_order . $sorting) . '</td><tr></table>';
	}
	
	function makeNavigation($up,$notch,$sorting) {
		
		$next = $this->prepareTypolink();
		$back = $this->prepareTypolink();
		$next['tx_skcalendar_pi1[notch]'] = $notch+$this->conf['list']['limit'];
		$back['tx_skcalendar_pi1[notch]'] = $notch-$this->conf['list']['limit'];
		$next['tx_skcalendar_pi1[sorting]'] = $sorting;
		$back['tx_skcalendar_pi1[sorting]'] = $sorting;
		$return = '<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td align=left>';
		if ($notch) $return .= '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$back) . '"><img src="' . t3lib_extMgm::siteRelPath('sk_calendar') . 'pi1/images/arrow_l.gif" border=0></a>';
		$return .= '</td><td align=right>';
		if ($up) $return .= '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$next) . '"><img src="' . t3lib_extMgm::siteRelPath('sk_calendar') . 'pi1/images/arrow_r.gif" border=0></a>';
		$return .= '</td></tr></table>';
		return $return;
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_listview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_listview.php"]);
}
