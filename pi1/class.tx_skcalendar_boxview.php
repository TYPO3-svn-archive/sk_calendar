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
		$this->tx_skcalendar_feengine($container,$conf);
	}

	function parseCalendar() {
		$act_date = $this->offset;
		$this->content = '<table cellspacing=0 cellpadding=0 border=1 width=95%>';
		while ($act_date < $this->todate) {
			$m = intval(date('m',$act_date));
			$d = intval(date('d',$act_date));

			if ($this->calendarArray[$m][$d]['events']) {
				$this->content .= '<tr><td><table cellspacing=0 cellpadding =3><tr valign=top><td>&nbsp;</td><td>' . strftime('%A, %d.%m %Y',$act_date) . '<br>';
				while (list(,$data) = each($this->calendarArray[$m][$d]['events'])) {
					$time = $this->parseTime($data['wholeday'],$data['date'],$data['start_time'],$data['end_time']);
					
					$this->content .= '<table cellspacing=0 cellpadding=0><tr valign=top><td width=30%><font color="' . $data['color'] . '">'.  $data['title'] . '</font><br>' . $time . '</td><td>' . $data['description'] . '</td></tr></table>';
					
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
