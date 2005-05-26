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

class tx_skcalendar_boxview extends tx_skcalendar_htmlview {


	function tx_skcalendar_boxview($container,$conf) {
		// calls mothership
		$this->tx_skcalendar_htmlview($container,$conf);
	}

	function parseCalendar() {
		$act_date = $this->offset;
		$this->content = '';
		while ($act_date < $this->todate) {
			$m = intval(date('m',$act_date));
			$d = intval(date('d',$act_date));

			if ($this->calendarArray[$m][$d]['events']) {	
				while (list(,$data) = each($this->calendarArray[$m][$d]['events'])) {
					unset($linktext);
					if ($data['start_time']) {
							$hours = floor($data['start_time']/3600);
							$minutes = ($data['start_time']%3600)/60; // modulus
							if ($minutes < 10) $minutes = '0' . $minutes;
							$linktext =  $hours . ':' . $minutes . ' ';
					}
					$linktext .= $data['title'];
					
					$temp['linktext'] = $linktext;
					$this->template->setItem($data);
					$this->template->setTempData($temp);
					$this->template->getSubpart('BOX_VIEW_ITEM');
					$temp['wrapit'] .= $this->template->parseTemplate();				
				}
				$temp['date'] = $act_date;
				$this->template->setTempData($temp);
				$this->template->getSubpart('BOX_VIEW_DAYWRAP');
				$c_result .= $this->template->parseTemplate();
				unset($temp['wrapit']);

			}
			
			$act_date = $act_date+86400;

		}
		if ($c_result) $content .= $c_result;
		else {
			$this->template->getSubpart('BOX_VIEW_NOENTRY');
			$content .= $this->template->parseTemplate();
		}
		
		// get Navigation
		$this->template->setTempData($this->makeNavigation());
		$this->template->getSubpart('BOX_VIEW_NAVIGATION');
		$temp['wrapit'] = $content . $this->template->parseTemplate();
		
		// wrap whole thing
		$this->template->setTempData($temp);
		$this->template->getSubpart('BOX_VIEW_WHOLEWRAP');
		$this->content = $this->template->parseTemplate();
	}
	
	function makeNavigation() {
		$offset = $this->fromdate;
		$step = $this->todate - $this->fromdate;
		$next = $this->prepareTypolink();
		$back = $this->prepareTypolink();
		$next['tx_skcalendar_pi1[offset]'] = $offset + $step;
		$back['tx_skcalendar_pi1[offset]'] = $offset - $step;
		$navi['backlink'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$back) . '"><img src="' . t3lib_extMgm::siteRelPath('sk_calendar') . 'pi1/images/arrow_l.gif" border=0></a>';
		$navi['nextlink'] = '<a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$next) . '"><img src="' . t3lib_extMgm::siteRelPath('sk_calendar') . 'pi1/images/arrow_r.gif" border=0></a>';
		return $navi;
		
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_boxview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_boxview.php"]);
}
