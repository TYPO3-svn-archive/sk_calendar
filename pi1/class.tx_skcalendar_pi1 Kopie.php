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

class tx_skcalendar_pi1 extends tslib_pibase {
	var $prefixId = "tx_skcalendar_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_skcalendar_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "sk_calendar";	// The extension key.
	
	/**
	 * This is only an example output of the data, so other ways of displaying data will be developed on demand.
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		debug($conf);
		// prepare typolinks
		$this->allowCaching = 0;
		$link_conf = $this->typolink_conf;
		
		$link_conf['parameter'] = $GLOBALS["TSFE"]->id;
		$link_conf["parameter."]["wrap"] = "|,".$GLOBALS["TSFE"]->type;
		$link_conf["useCacheHash"]=$this->allowCaching;
		$link_conf["no_cache"]=!$this->allowCaching;
		
		
		require_once(t3lib_extMgm::extPath($this->extKey).'class.skcalendar.class');
		$calendar = new sk_calendar;
		if (!$this->conf['templateFile']) $this->conf['templateFile'] = 'typo3conf/ext/sk_calendar/pi1/template.tmpl';
$this->listTemplateCode = $this->cObj->fileResource($this->conf["templateFile"]);
$tmpl_listwrap = $this->cObj->getSubpart($this->listTemplateCode, "###LISTWRAP###");
$tmpl_row =  $this->cObj->getSubpart($this->listTemplateCode, "###ROW###");
$tmpl_detail =  $this->cObj->getSubpart($this->listTemplateCode, "###DETAIL###");
if ($GLOBALS['HTTP_GET_VARS']['tx_skcalendar_pi1']['skevent']) {
	// detail view
	$uid = explode('_',$GLOBALS['HTTP_GET_VARS']['tx_skcalendar_pi1']['skevent']);
	$ovrride_date = substr($uid[1],2);
	$uid = $uid[0];
	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_skcalendar_events',"uid='$uid'");
	$event = mysql_fetch_array($result,MYSQL_ASSOC);
       $event['date'] = date('Y-m-d',$event['date']); // convert date
	if ($ovrride_date) $event['date'] = $ovrride_date;
	$event = $calendar->decodeEvent($event);
	// fe editing
	
	
	$markerArray["###TITLE###"] = $event['title'];
	$markerArray["###DURATION###"] = $event['duration'];
	$markerArray["###LINK###"] = $event['link'];
	$markerArray["###COST###"] = $event['cost'];
	$markerArray["###DESCRIPTION###"] = $event['description'];
	$markerArray["###IMAGE###"] = $event['image'];
	$markerArray["###PAGES###"] = $event['pages'];
	$markerArray["###HIGHLIGHT###"] = $event['highlight'];
	$markerArray["###CATEGORY###"] = $event['category']['title'];
	$markerArray["###ORGANIZER###"] = $event['organizer']['name'];
	$markerArray["###TARGETGROUP###"] = $event['targetgroup']['title'];
	$markerArray["###LOCATION###"] = $event['location']['title'];
	$content = $this->cObj->substituteMarkerArrayCached($tmpl_detail,$markerArray,array(),array());
}
else
{
// listview
	// get data
	$offset = date('m-d');
		
		$calendar->setCalendar($offset);
		$calendar->queryCalendar();
		
		if ($calendar->events) {
		while (list(,$event) = each ($calendar->events)) 
		{
			$event = $calendar->decodeEvent($event);
			$link_conf["additionalParams"] = '&tx_skcalendar_pi1[skevent]='.$event['uid'];
			$markerArray["###FE_EDIT###"] = $this->pi_getEditPanel($event,"tx_skcalendar_events");
			$markerArray["###DETAIL_LINK###"] = $this->cObj->typolink($this->pi_getLL('more'),$link_conf);
			$markerArray["###TITLE###"] = $this->cObj->typolink($event['title'],$link_conf);
			$markerArray["###DURATION###"] = $event['duration'];
			$markerArray["###LINK###"] = $event['link'];
			$markerArray["###COST###"] = $event['cost'];
			$markerArray["###DESCRIPTION###"] = $event['description'];
			$markerArray["###IMAGE###"] = $event['image'];
			$markerArray["###PAGES###"] = $event['pages'];
			$markerArray["###HIGHLIGHT###"] = $event['highlight'];
			$markerArray["###CATEGORY###"] = $event['category']['title'];
			$markerArray["###ORGANIZER###"] = $event['organizer']['name'];
			$markerArray["###TARGETGROUP###"] = $event['targetgroup']['title'];
			$markerArray["###LOCATION###"] = $event['location']['title'];
			$row = $this->cObj->substituteMarkerArrayCached($tmpl_row,$markerArray,array(),array());
			$cache['###LIST###'] .= $row;
		}
		}
		else $cache['###LIST###'] = ' '; // in case there are no events
		// wrapit
		$content = $this->cObj->substituteMarkerArrayCached($tmpl_listwrap,$cache,array(),array());
	}
	return $this->pi_wrapInBaseClass($content);
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_pi1.php"]);
}

?>