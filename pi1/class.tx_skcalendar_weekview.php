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
		reset ($this->events);
				if (!$this->conf['templateFile']) $this->conf['templateFile'] = 'typo3conf/ext/sk_calendar/pi1/template.tmpl';
				//$this->listTemplateCode = $this->cObj->fileResource($this->conf["templateFile"]);
				//$tmpl_listwrap = $this->cObj->getSubpart($this->listTemplateCode, "###LISTWRAP###");
				//$tmpl_row =  $this->cObj->getSubpart($this->listTemplateCode, "###ROW###");
				//$tmpl_detail =  $this->cObj->getSubpart($this->listTemplateCode, "###DETAIL###");
				$this->content = '<b>Listview:</b><br>';
				while (list(,$data) = each($this->events)) {
					$this->content .= date('d-m-Y',$data['date']) . ' - ' . $data['title'] . '<br>';
				}
				
				/*$this->content = '<form action="'.$this->pi_getPageLink($GLOBALS["TSFE"]->id).'" method="POST"><input type="hidden" name="no_cache" value="1">';
				$this->content .= '<input type="submit" name="notch" value="d+"/>'
				. '<input type="submit" name="notch" value="d-"/>'
				. '</form>';*/
	}
	
	function makeLinks() {
		$this->content .= '>> nächste Woche';
		// 7 Tage = 604800
		}
}