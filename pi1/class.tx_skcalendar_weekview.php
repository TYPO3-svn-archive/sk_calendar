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

class tx_skcalendar_weekview extends tx_skcalendar_htmlview {

	function tx_skcalendar_weekview($container,$conf) {
		// calls mothership
		$this->tx_skcalendar_htmlview($container,$conf);
	}

	function parseCalendar() {
		$act_date = $this->offset;
		if ($this->conf['general']['showlinks']) $this->makeLinks();
		if ($this->conf['general']['showfilters']) $this->makeFilters();
				
		while ($act_date < $this->todate) {
			$m = intval(date('m',$act_date));
			$d = intval(date('d',$act_date));
			// get events if any
			if ($this->calendarArray[$m][$d]['events']) {
				while (list(,$data) = each($this->calendarArray[$m][$d]['events'])) {
						$this->template->setItem($data);
						$this->template->getSubpart('WEEK_VIEW_ITEM');
						$temp['wrapit'] .= $this->template->parseTemplate();
					}
			}
			$temp['style'] = 'week_' . $this->calendarArray[$month][$d]['style'];
			$temp['date'] = $act_date;
			$this->template->setTempData($temp);
			$this->template->getSubpart('WEEK_VIEW_DAYWRAP');
			$weekcontent .= $this->template->parseTemplate();
			unset($temp);

			$act_date = $act_date+86400;

		}
		// get Navigation
		$this->template->setTempData($this->makeNavigation(604800));
		$this->template->getSubpart('WEEK_VIEW_NAVIGATION');
		$temp['wrapit'] = $weekcontent . $this->template->parseTemplate();
		
		// wrap whole thing
		$temp['timestamp'] = $this->calendarArray[$month][$d]['d_ts'];
		$this->template->setTempData($temp);
		$this->template->getSubpart('WEEK_VIEW_WHOLEWRAP');
		$this->content .= $this->template->parseTemplate();
	}

	function makeNavigation($span) {
		
		$next = $this->prepareTypolink();
		$back = $this->prepareTypolink();
		$next['tx_skcalendar_pi1[offset]'] = $this->offset + $span;
		$back['tx_skcalendar_pi1[offset]'] = $this->offset - $span;
		$navi['backlink'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$back) . '"> << ' . $this->pi_getLL('prev_week') . '</a>';
		$navi['nextlink'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$next) . '"> >> ' . $this->pi_getLL('next_week') . '</a>';
		return ($navi);
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_weekview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_weekview.php"]);
}
