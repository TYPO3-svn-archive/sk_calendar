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

class tx_skcalendar_detailview extends tx_skcalendar_htmlview {
	var $showID;

	function tx_skcalendar_detailview($container,$conf) {
		$this->showID = $conf['uid'];
		// calls mothership
		$this->tx_skcalendar_htmlview($container,$conf);
	}

	function parseCalendar() {
	
	$act_date = $this->offset;
	
	debug($this->conf['general']['rtefield_stdWrap']);
	// topwrap
	
	// show data
	$this->template->getSubpart('DETAIL_VIEW_MAIN');
	while ($act_date < $this->todate) {
		
			$m = intval(date('m',$act_date));
			$d = intval(date('d',$act_date));
			
			if ($this->calendarArray[$m][$d]['events']) {
			
			while (list(,$data) = each($this->calendarArray[$m][$d]['events'])) {
				if ($data['uid'] == $this->showID) {
					$data['description'] = $this->myCobj->stdWrap($data['description'],$this->conf['general']['rtefield_stdWrap']);
					$this->template->setItem($data);
					$content .= $this->template->parseTemplate();
					}
				}
			}
			$act_date = $act_date+86400;
			} 
		
	// Wrap
	$temp['wrapit'] = $content;
	$this->template->setTempData($temp);
	$this->template->getSubpart('DETAIL_VIEW_WRAP');
	$this->content .= $this->template->parseTemplate();

	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_detailview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_detailview.php"]);
}
