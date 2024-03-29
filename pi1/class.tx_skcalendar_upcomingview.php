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

class tx_skcalendar_upcomingview extends tx_skcalendar_listview {


	function tx_skcalendar_upcomingview($container,$conf) {
		// calls mothership
		
		$this->tx_skcalendar_htmlview($container,$conf);
	}
	
	function parseCalendar() {
	$i=1;
	if ($this->container->result) {
	while ($i<=$this->conf['upcoming']['range']) {
			list(,$data) = each($this->container->result);
			if ($data) {
				$this->template->setItem($data);
				$this->template->getSubpart('UPCOMING_VIEW_ITEM');
				$this->content .= $this->template->parseTemplate();
			$i++;
			}
			else $i=$this->conf['upcoming']['range']+1; // bail out
		}
		}
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_upcomingview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_upcomingview.php"]);
}
