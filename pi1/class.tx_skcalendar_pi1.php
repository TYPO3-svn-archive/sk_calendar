<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003 Volker Biberger (info@sitekick.de)
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
/**
* Plugin 'VCE Calendar' for the 'sk_calendar' extension.
*
* @author	Volker Biberger <info@sitekick.de>
*/


require_once(PATH_tslib."class.tslib_pibase.php");
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_feengine.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_selection.php');

class tx_skcalendar_pi1 extends tslib_pibase {
	var $prefixId = "tx_skcalendar_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_skcalendar_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "sk_calendar";	// The extension key.

	/**
	* This is only an example output of the data, so other ways of displaying data will be developed on demand.
	*/
	function main($content,$conf)	{
	
		$this->pi_initPIflexForm();
		$this->conf = ARRAY(
			'general' => array(
				'showlinks' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'showlinks','sDEF'),
				'view' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'default_view','sDEF'),
				'htmltemplate' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'html_template','sDEF'),
				'pdftemplate' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'pdf_template','sDEF'),
				'overrideTF' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'overrideTF','sDEF'),
				'showfilters' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'showfilters','sDEF'),
				'rtefield_stdWrap' => $conf['general.']['rtefield_stdWrap.'], // for RTE
				),
			'box' => array(
				'range' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'boxrange','s_view'),
				),
			'upcoming' => array(
				'range' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'upcomingrange','s_view'),
				),
			'list' => array(
				'limit' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'listrange','s_view'),
				'filter_month' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'listmonths','s_view'),
				),
			'warning' => array(
				'filters' => $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'filters','s_warning'),
				),
		);
		
		// defaultvalues
		if (!$this->conf['box']['range']) $this->conf['box']['range'] = 7;
		if (!$this->conf['upcoming']['range']) $this->conf['upcoming']['range'] = 10;
		if (!$this->conf['list']['limit']) $this->conf['list']['limit'] = 20;
		if (!$this->conf['general']['htmltemplate']) $this->conf['general']['htmltemplate'] = 'EXT:sk_calendar/pi1/html_template.html';
		else $this->conf['general']['htmltemplate'] = 'uploads/tx_skcalendar/' . $this->conf['general']['htmltemplate'];
		if (!$this->conf['general']['pdftemplate']) $this->conf['general']['pdftemplate'] = t3lib_div::getFileAbsFileName('EXT:sk_calendar/pi1/pdf_template.pdf');
		else $this->conf['general']['pdftemplate'] = t3lib_div::getFileAbsFileName('uploads/tx_skcalendar/' . $this->conf['general']['pdftemplate']);
		if (!$this->conf['general']['overrideTF']) $this->conf['general']['overrideTF'] = 0; // needs to be otherwise directory will be included (results in an error)
		
		// switch around for no default values in FF are possible (yet).
		if ($this->conf['general']['showfilters']) $this->conf['general']['showfilters'] = 0;
		else $this->conf['general']['showfilters'] =1;
		if ($this->conf['general']['showlinks']) $this->conf['general']['showlinks'] = 0;
		else $this->conf['general']['showlinks'] =1;		
		if ($this->conf['warning']['filters']) $this->conf['warning']['filters'] = 0;
		else $this->conf['warning']['filters'] =1;				

		$this->conf['userFunc'] = $conf['userFunc'];
				
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$pages = $this->pi_getPidList($this->cObj->data['pages'],$this->cObj->data['recursive']);
		if (!$pages) $pages = $GLOBALS["TSFE"]->id;
			
		// view & Offset
		$offset = intval($this->piVars['offset']);
		if (!$offset) $offset = mktime(0,0,0);
		if ($this->piVars['view']) $this->conf['general']['view'] = $this->piVars['view'];
		$this->conf['offset'] = $offset;
		$this->conf['notch'] = $this->piVars['notch']; // listview
		$this->conf['sorting'] = $this->piVars['sorting']; // listview
		
		// read config
		if ($this->conf['general']['filter_cat']) $filters['categories'][0] = intval($this->conf['general']['filter_cat']);
		if ($this->conf['general']['filter_target']) $filters['targetgroups'][0] = intval($this->conf['general']['filter_target']);
		if ($this->conf['general']['filter_loc']) $filters['locations'][0] = intval($this->conf['general']['filter_loc']);
		if ($this->conf['general']['filter_orga']) $filters['organizers'][0] = intval($this->conf['general']['filter_orga']);
		
		// Override with userinputs
		$filters['sword'] = $this->piVars['sword'];
		if ($this->piVars['targetgroups']) $filters['targetgroups'][0] = intval($this->piVars['targetgroups']);
		if ($this->piVars['categories']) $filters['categories'][0] = intval($this->piVars['categories']);
		if ($this->piVars['locations']) $filters['locations'][0] = intval($this->piVars['locations']);
		if ($this->piVars['organizers']) $filters['organizers'][0] = intval($this->piVars['organizers']);
		if ($this->piVars['monthfilter']) $filters['monthfilter'] = intval($this->piVars['monthfilter']); // month filters in Listview
		if ($pages) $filters['pid'] = $pages; 
		else $filters['pid'] = $GLOBALS["TSFE"]->id; // same page if no pid is given
		
		switch ($this->conf['general']['view']) {
			case 'week':
			$offset = $offset - date('w',$offset) * 86400 + 86400; // we like mondays
			$filters['startdate'] = $offset;
			$filters['enddate'] = $offset + 604800;
			break;

			case 'box';
			$filters['startdate'] = $offset;
			$filters['enddate'] = $offset + ($this->conf['box']['range']*86400); // default one week
			break;

			case 'day': // not yet implemented
			$filters['startdate'] = $offset;
			$filters['enddate'] = $offset + 86400;
			break;

			case 'month':
			$offset_temp = date('m-d-Y',$offset);
			$offset_temp = explode('-',$offset_temp);
			$start = mktime(0,0,0,$offset_temp[0],1,$offset_temp[2]);
			$end = mktime(0,0,0,$offset_temp[0],31,$offset_temp[2]);
			$filters['startdate'] = $start;
			$filters['enddate'] = $end;
			break;

			case 'year':
			$offset_temp = date('Y',$offset);
			$start = mktime(0,0,0,1,1,$offset_temp);
			$end = mktime(23,59,59,12,31,$offset_temp);
			$filters['startdate'] = $start;
			$filters['enddate'] = $end;
			break;

			case 'detail':
			$filters['startdate'] = $offset;
			$filters['enddate'] = $offset + 86400;
			break;
			
			case 'upcoming': // same selection like listview.
			case 'list':
			if ($filters['monthfilter']) {
				$filters['startdate'] = $filters['monthfilter'];
				$offset_temp = date('m-d-Y',$filters['monthfilter']);
				$offset_temp = explode('-',$offset_temp);
				$filters['enddate'] = mktime(0,0,0,$offset_temp[0]+1,$offset_temp[1],$offset_temp[2]);
				$offset = $filters['monthfilter'];
			}
			else {
				$filters['startdate'] = $offset;
				$offset_temp = date('m-d-Y',$offset);
				$offset_temp = explode('-',$offset_temp);
				$filters['enddate'] = mktime(0,0,0,$offset_temp[0],$offset_temp[1],$offset_temp[2]+5); // 5 years should result enough entries for the list. Cannot select unlimited because of possible infinite recurring events
			}
			break;
			
			case 'archive':
			if ($filters['monthfilter']) {
				$filters['startdate'] = $filters['monthfilter'];
				$offset_temp = date('m-d-Y',$filters['monthfilter']);
				$offset_temp = explode('-',$offset_temp);
				$filters['enddate'] = mktime(0,0,0,$offset_temp[0]+1,$offset_temp[1],$offset_temp[2]);
				$offset = $filters['monthfilter'];
			}
			else {
				$filters['startdate'] = 1; // show us everything (0 would disable filter)
				$offset_temp = date('m-d-Y');
				$filters['enddate'] = mktime(); // ... until today
			}
			break;


		}
		$this->conf['offset'] = $offset;
		
		// initiate selection
		$selection = new tx_skcalendar_internal();
		$selection->setFilters($filters);
		$selection->getResults();
		
		switch ($this->conf['general']['view']) {
			case 'week':
			$calendar = new tx_skcalendar_weekview($selection,$this->conf);
			break;

			case 'box';
			$calendar = new tx_skcalendar_boxview($selection,$this->conf);
			break;

			case 'day':
			$calendar = new tx_skcalendar_dayview($selection,$this->conf);
			break;

			case 'month':
			$calendar = new tx_skcalendar_monthview($selection,$this->conf);
			$calendar->createHolidays('de');
			break;

			case 'year':
			$calendar = new tx_skcalendar_yearview($selection,$this->conf);
			$calendar->createHolidays('de');
			break;

			case 'detail';
			$this->conf['uid'] = $this->piVars['uid'];
			$calendar = new tx_skcalendar_detailview($selection,$this->conf);
			break;

			case 'list';
			$calendar = new tx_skcalendar_listview($selection,$this->conf);
			break;

			case 'archive';
			$calendar = new tx_skcalendar_archiveview($selection,$this->conf);
			break;
	
			case 'upcoming';
			$calendar = new tx_skcalendar_upcomingview($selection,$this->conf);
			break;
			
			default:
			$calendar = new tx_skcalendar_monthview($selection,$this->conf);
			$calendar->createHolidays('de');
			break;
		}		
		$calendar->setRange($filters['startdate'],$filters['enddate']);
		$calendar->createCalendar();
		$calendar->parseCalendar();

		return $this->pi_wrapInBaseClass($calendar->content);
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"]);
}

?>