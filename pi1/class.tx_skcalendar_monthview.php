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

class tx_skcalendar_monthview extends tx_skcalendar_htmlview {

	function tx_skcalendar_monthview($container,$conf) {
		// calls mothership
		
		$this->tx_skcalendar_htmlview($container,$conf);
	}

	function parseCalendar() {
		$month = intval(date('m',$this->offset));
		
		if ($this->conf['general']['showlinks']) $this->makeLinks();
		if ($this->conf['general']['showfilters']) $this->makeFilters();
		
		// calculate offset
		$weekday = date('w',mktime(0,0,0,$month,1,$this->year));
		if ($weekday == 0) $weekday=7;
		$d=$weekday-(($weekday-1)*2);
		
		// amount weeks
		$days = date('t',mktime(0,0,0,$month,1,$this->year))-28;
		$days = $days+$weekday-1;// How many overhanging days?
		if ($days > 7) $count_weeks=6;
		elseif ($days == 0) $count_weeks=4;
		else $count_weeks = 5;
		
		for ($w=1; $w<=$count_weeks;$w++) {
			for ($i=1;$i<=7;$i++) {
				$temp['style'] = 'month_' . $this->calendarArray[$month][$d]['style'];
				$temp['name'] = $this->calendarArray[$month][$d]['d_name']['no'] . '&nbsp;' . $this->calendarArray[$month][$d]['d_name']['short'];
				if ($this->calendarArray[$month][$d]['d_ts']) $temp['timestamp'] = $this->calendarArray[$month][$d]['d_ts'];

				if ($this->calendarArray[$month][$d]['events']) {
					while (list(,$data) = each($this->calendarArray[$month][$d]['events'])) {
					if ($this->conf['month']['iconmode']) {
					$cat = $this->getCategory($data['category']);
					if (!$cat['icon']) $cat['icon'] = 'cat_fallback.gif';
					$linktext = '<img border=0 width=10 src="uploads/tx_skcalendar/'.  $cat['icon'] . '">';
					}
					else $linktext = $data['title'];
					$temp['linktext'] = $linktext;
					$this->template->setItem($data);
					$this->template->setTempData($temp);
					$this->template->getSubpart('MONTH_VIEW_ITEM');

					$content_day .= $this->template->parseTemplate();
					}
				}
				
				// Make my day
				$temp['wrapit'] = $content_day;
				$this->template->setTempData($temp);
				$this->template->getSubpart('MONTH_VIEW_DAYWRAP');
				$content_row .= $this->template->parseTemplate();
				unset ($content_day);
				$d++;
			}
			
			// close row
			$temp['wrapit'] = $content_row;
			$this->template->setTempData($temp);
			$this->template->getSubpart('MONTH_VIEW_ROWWRAP');
			$content .= $this->template->parseTemplate();
			unset ($content_row);

		}
		
		$temp['date'] = $this->offset;
		$temp['wrapit'] = $content;
		$this->template->setTempData($temp);
		$this->template->getSubpart('MONTH_VIEW_CALENDARWRAP');
		$content = $this->template->parseTemplate();
		
		// get Navigation
		$this->template->setTempData($this->makeNavigation());
		$this->template->getSubpart('MONTH_VIEW_NAVIGATION');
		$temp['wrapit'] = $content . $this->template->parseTemplate();
		
		// wrap whole thing
		$this->template->setTempData($temp);
		$this->template->getSubpart('MONTH_VIEW_WHOLEWRAP');
		$this->content .= $this->template->parseTemplate();
	}

	function makeNavigation() {
		$offset = date('m',$this->offset);
		$next = $this->prepareTypolink();
		$back = $this->prepareTypolink();
		$nextyear = $this->prepareTypolink();
		$backyear = $this->prepareTypolink();
		
		$next['tx_skcalendar_pi1[offset]'] = mktime(0,0,0,$offset+1,1,$this->year);
		$back['tx_skcalendar_pi1[offset]'] = mktime(0,0,0,$offset-1,1,$this->year);
		$nextyear['tx_skcalendar_pi1[offset]'] = mktime(0,0,0,$offset,1,$this->year+1);
		$backyear['tx_skcalendar_pi1[offset]'] = mktime(0,0,0,$offset,1,$this->year-1);

		$navi['backlinkyear'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$backyear) . '"> << ' . strftime("%Y",mktime(0,0,0,$offset,1,$this->year-1)) . '</a>';
		$navi['nextlinkyear'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$nextyear) . '">' . strftime("%Y",mktime(0,0,0,$offset,1,$this->year+1)) . ' >> ' .'</a>';

		$navi['backlink'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$back) . '"> << ' . $this->pi_getLL('prev_month') . '</a>';
		$navi['nextlink'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$next) . '"> >> ' . $this->pi_getLL('next_month') . '</a>';
		return $navi;
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_monthview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_monthview.php"]);
}
