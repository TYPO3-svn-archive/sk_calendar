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

class tx_skcalendar_monthview extends tx_skcalendar_calendarView {

	function tx_skcalendar_monthview($container,$type,$conf) {
		// calls mothership
		$this->tx_skcalendar_calendarView($container,$type,$conf);
	}

	function parseCalendar() {
		$month = intval(date('m',$this->offset));
		
		$this->content = '<table cellspacing=0 cellpadding=0 border=0 width=100% bordercolor="#EFEFEF"><tr><td><table cellspacing=0 cellpadding =3><tr valign=top><td><b>Monatsansicht ' . strftime('%B %Y',$this->offset) . ':</b></td></tr></table></td></tr>';
		$this->content .= '<tr><td><table cellspacing=0 cellpadding=2 border=1 width =100%><tr><td><b>Mo</b></td><td><b>Di</b></td><td><b>Mi</b></td><td><b>Do</b></td><td><b>Fr</b></td><td><b>Sa</b></td><td><b>So</b></b></td></tr>';
		$d = date('w',mktime(0,0,0,$month,1,$this->year));
		if ($d == 0) $d=7;
		$d=$d-(($d-1)*2);
		for ($w=1; $w<=5;$w++) {
			$this->content .= '<tr valign=top>';
			for ($i=1;$i<=7;$i++) {
				$this->content .= '<td height=50 width=14%';
				if ($this->calendarArray[$month][$d]['isholiday']) $this->content .= ' bgcolor=#efefef';
				$this->content .= '>' . $this->calendarArray[$month][$d]['d_name']['no'] . '&nbsp;' . $this->calendarArray[$month][$d]['d_name']['short'];
				if ($this->calendarArray[$month][$d]['events']) {
					while (list(,$data) = each($this->calendarArray[$month][$d]['events'])) {
						$this->content .= '<br>'.  $data['title'];
					}
				}
				$this->content .= '</td>';
				$d++;
			}
			$this->content .= '</tr>';
		}
		$this->content .= '</table></td></tr>';
		$this->content .= '<tr><td><table cellspacing=0 cellpadding =3 width=100%><tr valign=bottom><td>';
		$this->makeLinks();
		$this->content .= '</td></tr></table></td></tr></table>';
	}

	function makeLinks() {
		$offset = date('m',$this->offset);
		$next['tx_skcalendar[offset]'] = mktime(0,0,0,$offset+1,1,$this->year);
		$next['no_cache'] = 1;
		$back['no_cache'] = 1;
		$back['tx_skcalendar[offset]'] = mktime(0,0,0,$offset-1,1,$this->year);
		$this->content .= '<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td align=left><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$back) . '"> << vorheriger Monat</a></td><td align=right><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$next) . '"> >> nächster Monat</a></td></tr></table>';
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_monthview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_monthview.php"]);
}
