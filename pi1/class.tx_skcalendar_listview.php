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
		
		$this->tx_skcalendar_htmlview($container,$conf);
	}
	
	function createCalendar() {
	return; // no calendarArray needed
	}

	function parseCalendar() {
		// set enviroment
		
		$result_count = sizeof($this->container->result);
		if ($result_count < $this->conf['list']['limit']) {
			$this->conf['list']['limit'] = $result_count;
			$this->conf['notch'] = FALSE;
		}
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
		if ($this->conf['notch']) $this->container->result = array_slice($this->container->result,$this->conf['notch']); // notch up

		while ($i<=$this->conf['list']['limit']) {
			list(,$data) = each($this->container->result);
			if ($data) {
		
			// get basis for sorting
			switch ($sorting) {
				case 'date':
					$prefix = $data['date'];
					break;
					
				case 'location':
					$prefix = $this->getLocation($data['location'],'title');
					break;
				
				case 'organizer':
					$prefix = $this->getOrganizer($data['organizer'],'name');
					break;
				
				case 'category':
					$prefix = $this->getCategory($data['category'],'title');
					break;
			}
			
			$clean_arr[$prefix . '_' . $i] = $data;
			$i++;
			}
			else $i=$this->conf['list']['limit']+1; // bail out
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
		if ($this->conf['general']['showlinks']) $this->makeLinks();
		if ($this->conf['general']['showfilters']) $this->makeFilters();

		// get 123
		$temp['wrapit'] = $this->make123($result_count);
		$this->template->setTempData($temp);
		$this->template->getSubpart('LIST_VIEW_123');
		$content .= $this->template->parseTemplate();

		$sortlink['tx_skcalendar_pi1[sorting]'] = $sortlinkpara['date'];
		$temp['sortlink_date'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$sortlink) . '">' . $this->pi_getLL('date') . $sortimg['date'] . '</a>';
		$sortlink['tx_skcalendar_pi1[sorting]'] = $sortlinkpara['title'];
		$temp['sortlink_title'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$sortlink) . '">' . $this->pi_getLL('title') . $sortimg['title'] . '</a>';
		$sortlink['tx_skcalendar_pi1[sorting]'] = $sortlinkpara['organizer'];
		$temp['sortlink_organizer'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$sortlink) . '">' . $this->pi_getLL('organizer') . $sortimg['organizer'] . '</a>';
		$sortlink['tx_skcalendar_pi1[sorting]'] = $sortlinkpara['location'];
		$temp['sortlink_location'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$sortlink) . '">' . $this->pi_getLL('location') . $sortimg['location'] . '</a>';
		$sortlink['tx_skcalendar_pi1[sorting]'] = $sortlinkpara['category'];
		$temp['sortlink_category'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$sortlink) . '">' . $this->pi_getLL('category') .$sortimg['category'] .  '</a>';

		$this->template->setTempData($temp);
		$this->template->getSubpart('LIST_VIEW_SORTLINKS');
		$content .= $this->template->parseTemplate();
		
		
		if ($clean_arr) { // we do have entries
		while (list(,$data) = each($clean_arr)) {
				$this->template->setItem($data);
				$this->template->getSubpart('LIST_VIEW_ITEM');
				$content .= $this->template->parseTemplate();				
				}
			}
			else { // no entries
				$this->template->getSubpart('LIST_VIEW_NOENTRY');
				$content .= $this->template->parseTemplate();				
			}
			
		// get 123
		$temp['wrapit'] = $this->make123($result_count);
		$this->template->setTempData($temp);
		$this->template->getSubpart('LIST_VIEW_123');
		$content .= $this->template->parseTemplate();

		// get Navigation
		$this->template->setTempData($this->makeNavigation($next,$this->conf['notch'], $sort_order . $sorting));
		$this->template->getSubpart('LIST_VIEW_NAVIGATION');
		$temp['wrapit'] = $content . $this->template->parseTemplate();

		// wrap whole thing
		$this->template->setTempData($temp);
		$this->template->getSubpart('LIST_VIEW_WHOLEWRAP');
		$this->content .= $this->template->parseTemplate();
	}
	
	function makeNavigation($up,$notch,$sorting) {
		
		$next = $this->prepareTypolink();
		$back = $this->prepareTypolink();
		$next['tx_skcalendar_pi1[notch]'] = $notch+$this->conf['list']['limit'];
		$back['tx_skcalendar_pi1[notch]'] = $notch-$this->conf['list']['limit'];
		$next['tx_skcalendar_pi1[sorting]'] = $sorting;
		$back['tx_skcalendar_pi1[sorting]'] = $sorting;
		
		if ($notch) $navi['backlink'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$back) . '"><img src="' . t3lib_extMgm::siteRelPath('sk_calendar') . 'pi1/images/arrow_l.gif" border=0></a>';
		if ($up) $navi['nextlink'] =  '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$next) . '"><img src="' . t3lib_extMgm::siteRelPath('sk_calendar') . 'pi1/images/arrow_r.gif" border=0></a>';
		return $navi;
	}
	
	function make123($count,$sorting=False) {
		$limit = $this->conf['list']['limit'];
		$notch = $this->conf['notch'];
		$link = $this->prepareTypolink();
		$link['tx_skcalendar_pi1[sorting]'] = $sorting;
		$from = $notch+1;
		
		$to = $notch+$limit;
		if ($to > $count) $to = $count;
		if ($count>0) $return = $this->pi_getLL('show') . ': ' . $from . '-' . $to . ' ' . $this->pi_getLL('of') . ' ' . $count;
		if ($count > $limit) {
			$return .= ' < ';
			$steps = ceil($count/$limit);
			for ($i=0; $i<$steps;) {
				$linknotch = $i*$limit;
				$link['tx_skcalendar_pi1[notch]'] = $linknotch;
				$i++;
				if ($linknotch != $notch) $return .= '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$link) . '">' . $i . '</a> ';
				else $return .= $i . ' ';
			}
			$return .= ' >';
		}
		return $return;
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_listview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_listview.php"]);
}
