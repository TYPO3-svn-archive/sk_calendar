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
		
		$this->tx_skcalendar_feengine($container,$conf);
	}

	function parseCalendar() {
		$month = intval(date('m',$this->offset));
		
		if ($this->conf['showlinks']) $this->makeLinks();
		if ($this->conf['showfilters']) $this->makeFilters();
		$this->content .= '<table cellspacing=0 cellpadding=0 border=0 width=100% bordercolor="#EFEFEF"><tr><td><table cellspacing=0 cellpadding =3><tr valign=top><td><b>' . $this->pi_getLL('month_view') . ' ' . strftime('%B %Y',$this->offset) . ':</b></td></tr></table></td></tr>';
		$this->content .= '<tr><td><table cellspacing=0 cellpadding=2 border=1 width =100%><tr><td><b>Mo</b></td><td><b>Di</b></td><td><b>Mi</b></td><td><b>Do</b></td><td><b>Fr</b></td><td><b>Sa</b></td><td><b>So</b></b></td></tr>';
		$d = date('w',mktime(0,0,0,$month,1,$this->year));
		if ($d == 0) $d=7;
		$d=$d-(($d-1)*2);
		for ($w=1; $w<=5;$w++) {
			$this->content .= '<tr valign=top>';
			for ($i=1;$i<=7;$i++) {
				$this->content .= '<td height=50 width=14% class="month_' . $this->calendarArray[$month][$d]['style'] . '">';
				$this->content .= $this->calendarArray[$month][$d]['d_name']['no'] . '&nbsp;' . $this->calendarArray[$month][$d]['d_name']['short'];
				if ($this->calendarArray[$month][$d]['events']) {
					while (list(,$data) = each($this->calendarArray[$month][$d]['events'])) {
					$next = $this->prepareTypolink();
					$next['tx_skcalendar_pi1[offset]'] = mktime(0,0,0,$month,$d,$this->year);
					$next['tx_skcalendar_pi1[view]']= 'detail';
					$next['tx_skcalendar_pi1[uid]']= $data['uid'];
					if ($this->conf['iconmode']) {
					$cat = $this->getCategory($data['category']);
					if (!$cat['icon']) $cat['icon'] = 'cat_fallback.gif';
					$linktext = '<img border=0 width=10 src="uploads/tx_skcalendar/'.  $cat['icon'] . '">';
					}
					else $linktext = $data['title'];
					
						$this->content .= '<br><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($this->conf['target'],$next) . '"><font color="' . $data['color'] . '">'. $linktext.'</font></a>';
					}
				}
				$this->content .= '</td>';
				$d++;
			}
			$this->content .= '</tr>';
		}
		$this->content .= '</table></td></tr>';
		$this->content .= '<tr><td><table cellspacing=0 cellpadding =3 width=100%><tr valign=bottom><td>';
		$this->makeNavigation();
		$this->content .= '</td></tr></table></td></tr></table>';
	}

	function makeNavigation() {
		$offset = date('m',$this->offset);
		$next = $this->prepareTypolink();
		$back = $this->prepareTypolink();
		$next['tx_skcalendar_pi1[offset]'] = mktime(0,0,0,$offset+1,1,$this->year);
		$back['tx_skcalendar_pi1[offset]'] = mktime(0,0,0,$offset-1,1,$this->year);
		$this->content .= '<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td align=left><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$back) . '"> << ' . $this->pi_getLL('prev_month') . '</a></td><td align=right><a href="' . $GLOBALS["TSFE"]->cObj->getTypoLink_URL($GLOBALS["TSFE"]->id,$next) . '"> >> ' . $this->pi_getLL('next_month') . '</a></td></tr></table>';
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_monthview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_monthview.php"]);
}
