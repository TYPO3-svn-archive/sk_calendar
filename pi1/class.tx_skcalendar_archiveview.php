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

class tx_skcalendar_archiveview extends tx_skcalendar_listview {


	function tx_skcalendar_archiveview($container,$conf) {
		// calls mothership
		
		$this->tx_skcalendar_htmlview($container,$conf);
	}
	
	function parseCalendar() {
	
		if ($this->conf['list']['filter_month']) $this->conf['list']['filter_month'] = 'reverse'; // we want 2 years in the past
		if ($this->conf['general']['showlinks']) $this->makeLinks();
		$this->makeFilters(); // filters are a must
		$this->conf['general']['showfilters'] = FALSE; // filters already shown
		$this->conf['general']['showlinks'] = FALSE; // links already shown

		if ($this->container->filters['sword'] || $this->container->filters['categories'] || $this->container->filters['targetgroups'] || $this->container->filters['organizers'] || $this->container->filters['locations'] || $this->container->filters['monthfilter']) parent::parseCalendar();
		else {
			$this->template->getSubpart('ARCHIVE_VIEW_NOSELECTION');
			$this->content .= $this->template->parseTemplate();
			}
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_listview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_listview.php"]);
}
