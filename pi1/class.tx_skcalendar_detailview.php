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
		$this->tx_skcalendar_feengine($container,$conf);
	}

	function parseCalendar() {
		$act_date = $this->offset;
		$this->content = '<table cellspacing=0 cellpadding=0 border=0 width=100%>';
		while ($act_date < $this->todate) {
			$m = intval(date('m',$act_date));
			$d = intval(date('d',$act_date));

			if ($this->calendarArray[$m][$d]['events']) {
				$this->content .= '<tr><td><table cellspacing=0 cellpadding =3><tr valign=top><td>&nbsp;</td><td>';
				while (list(,$data) = each($this->calendarArray[$m][$d]['events'])) {
					
					if ($data['uid'] == $this->showID) {
						$this->content .= '<b>' . $data['title'] . '</b><br>';
						if (!$data['wholeday'] && $data['starttime'] && $data['endtime']) {
							$start = $data['date'] + $data['starttime'];
							$end = $data['date'] + $data['endtime'];
							$this->content .= 'Zeit: ' . strftime('%A, %d.%m %Y',$start) . ' - ' . strftime('%M:%H',$start) . ' bis ' . strftime('%M:%H',$end) . ' Uhr<br>';
						}
						else $this->content .= $this->pi_getLL('date') . ': ' . strftime('%A, %d.%m %Y',$data['date']) . '<br>';
						if ($data['cost']) $this->content .= $this->pi_getLL('cost') . ': ' . $data['cost'] . ' &euro;<br>';
						if ($data['description']) $this->content .= '<br>' . $data['description'] . '<br>';
						if ($data['highlight']) $this->content .= 'Highlight!<br>';
						if ($data['image']) $this->content .= 'Image: ' . $data['image'] . '<br>';
						if ($data['pages']) $this->content .= 'pages: ' . $data['pages'] . '<br>';
						if ($data['pages']) $this->content .= $this->pi_getLL('further_information') . ': <a href="' . $data['link'] . '" target="new">' . $data['link'] . '</a><br>';
						if ($data['category']) {
							$cat = $this->getCategory($data['category']);
							$this->content .= $this->pi_getLL('category') . ': ' . $cat['title'] . '<br>';
						}
						if ($data['organizer']) {
							$orga = $this->getOrganizer($data['organizer']);
							$this->content .= $this->pi_getLL('organizer') . ': ' . $orga['name'] . '<br>';
						}
						if ($data['targetgroup']) {
							$target = $this->getTargetgroup($data['targetgroup']);
							$this->content .= $this->pi_getLL('targetgroup') . ': ' . $target['title'] . '<br>';
						}						
						$this->content .= '<div align=right><a href="' . "javascript:history.back()" . '"> << ' . $this->pi_getLL('back') . '</a></div><br><br>';

					}
				}
				$this->content .= '</td></tr></table></td></tr>';
			}

			$act_date = $act_date+86400;

		}
		$this->content .= '<tr><td>&nbsp;</td></tr></table>';
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_detailview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_detailview.php"]);
}
