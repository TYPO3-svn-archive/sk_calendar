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

class tx_skcalendar_weekview extends tx_skcalendar_calendarView {

	function tx_skcalendar_weekview($container,$type,$conf) {
		// calls mothership
		$this->tx_skcalendar_calendarView($container,$type,$conf);
	}

	function parseCalendar() {
		$act_date = $this->offset;
		$this->content = '<table cellspacing=0 cellpadding=0 border=1 width=400 bordercolor="#EFEFEF"><tr><td><table cellspacing=0 cellpadding =3><tr valign=top><td><b>Wochenansicht:</b></td></tr></table></td></tr>';
		while ($act_date < $this->todate) {
			$m = intval(date('m',$act_date));
			$d = intval(date('d',$act_date));
			$this->content .= '<tr><td><table cellspacing=0 cellpadding =3><tr valign=top><td>&nbsp;</td><td>' . strftime('%A, %d.%m %Y',$act_date) . '<br>';
			if ($this->calendarArray[$m][$d]['events']) {
				while (list(,$data) = each($this->calendarArray[$m][$d]['events'])) {
					$this->content .= $data['title'] . '<br>' . $data['description'] . '<br><br>';
				}
			}
			$this->content .= '</td></tr></table></td></tr>';
			$act_date = $act_date+86400;

		}
				$this->content .= '<tr><td><table cellspacing=0 cellpadding =3 width=100%><tr valign=bottom><td>';
		$this->makeLinks(604800);
		$this->content .= '</td></tr></table></td></tr></table>';
	}

	function makeLinks($span) {
		$next['tx_skcalendar[offset]'] = $this->offset + $span;
		$next['no_cache'] = 1;
		$back['no_cache'] = 1;
		$back['tx_skcalendar[offset]'] = $this->offset - $span;
		$this->content .= '<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td align=left><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$back) . '"> << vorherige Woche</a></td><td align=right><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$next) . '"> >> nächste Woche</a></td></tr></table>';
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_weekview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_weekview.php"]);
}
