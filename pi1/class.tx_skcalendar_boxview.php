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

class tx_skcalendar_boxview extends tx_skcalendar_calendarView {


	function tx_skcalendar_boxview($container,$type,$conf) {
		// calls mothership
		$this->tx_skcalendar_calendarView($container,$type,$conf);
	}

	function parseCalendar() {
		$act_date = $this->offset;
		$this->content = '<table cellspacing=0 cellpadding=0 border=0 width=100%>';
		while ($act_date < $this->todate) {
			$m = intval(date('m',$act_date));
			$d = intval(date('d',$act_date));

			if ($this->calendarArray[$m][$d]['events']) {
				$this->content .= '<tr><td><table cellspacing=0 cellpadding =3><tr valign=top><td>&nbsp;</td><td>' . strftime('%A, %d.%m %Y',$act_date) . '<br>';
				while (list(,$data) = each($this->calendarArray[$m][$d]['events'])) {
					$next['tx_skcalendar[offset]'] = mktime(0,0,0,$m,$d,$this->year);
					$next['tx_skcalendar[detail]']= 1;
					$next['tx_skcalendar[uid]']= $data['uid'];
					$next['no_cache'] = 1;
					$this->content .= $data['title'] . '<br>' . $data['description'] . '<br><div align=right><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($this->targetpage,$next) . '"> >> mehr</a></div><br><br>';
				}
				$this->content .= '</td></tr></table></td></tr>';
			}

			$act_date = $act_date+86400;

		}
		$this->content .= '<tr><td>&nbsp;</td></tr></table>';
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_boxview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_boxview.php"]);
}
