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
 * sk_calendar module cm1
 *
 * @author	Volker Biberger <info@sitekick.de>
 */



	// DEFAULT initialization of a module [BEGIN]
unset($MCONF);	
require ("conf.php");
require ($BACK_PATH."init.php");
require ($BACK_PATH."template.php");
include ("locallang.php");
require_once (PATH_t3lib."class.t3lib_scbase.php");
	// ....(But no access check here...)
	// DEFAULT initialization of a module [END] 

class tx_skcalendar_cm1 extends t3lib_SCbase {
	/**
	 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
	 */
	function menuConfig()	{
		global $LANG;
		$this->MOD_MENU = Array (
			"function" => Array (
				"1" => $LANG->getLL("function1"),
				"2" => $LANG->getLL("function2"),
				"3" => $LANG->getLL("function3"),
			)
		);
		parent::menuConfig();
	}

	/**
	 * Main function of the module. Write the content to $this->content
	 */
	function main()	{
		global $AB,$BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$HTTP_GET_VARS,$HTTP_POST_VARS,$CLIENT,$TYPO3_CONF_VARS;
		
			// Draw the header.
		$this->doc = t3lib_div::makeInstance("mediumDoc");
		$this->doc->backPath = $BACK_PATH;
		$this->doc->form='<form action="" method="POST">';

			// JavaScript
		$this->doc->JScode = '
			<script language="javascript">
				script_ended = 0;
				function jumpToUrl(URL)	{
					document.location = URL;
				}
			</script>
		';

		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;
		if (($this->id && $access) || ($BE_USER->user["admin"] && !$this->id))	{
			if ($BE_USER->user["admin"] && !$this->id)	{
				$this->pageinfo=array("title" => "[root-level]","uid"=>0,"pid"=>0);
			}

			$headerSection = $this->doc->getHeader("pages",$this->pageinfo,$this->pageinfo["_thePath"])."<br>".$LANG->php3Lang["labels"]["path"].": ".t3lib_div::fixed_lgd_pre($this->pageinfo["_thePath"],50);

			$this->content.=$this->doc->startPage($LANG->getLL("title"));
			$this->content.=$this->doc->header($LANG->getLL("title"));
			$this->content.=$this->doc->spacer(5);
			//$this->content.=$this->doc->section("",$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,"SET[function]",$this->MOD_SETTINGS["function"],$this->MOD_MENU["function"])));
		//	$this->content.=$this->doc->divider(5);

				
			// Render content:
			$this->moduleContent();

			
			// ShortCut
			if ($BE_USER->mayMakeShortcut())	{
				$this->content.=$this->doc->spacer(20).$this->doc->section("",$this->doc->makeShortcutIcon("id",implode(",",array_keys($this->MOD_MENU)),$this->MCONF["name"]));
			}
		}				
		$this->content.=$this->doc->spacer(10);
	}
	function printContent()	{
		global $SOBE;

		$this->content.=$this->doc->middle();
		$this->content.=$this->doc->endPage();
		echo $this->content;
	}
	
	function moduleContent()	{
	global $LANG;
	
	require_once(t3lib_extMgm::extPath('sk_calendar').'class.tx_skcalendar_vceedit.php');
	require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_selection.php');
	if($GLOBALS["HTTP_GET_VARS"]['offset']) $offset = $GLOBALS["HTTP_GET_VARS"]['offset'];
	else $offset=mktime();
	$this->conf['offset'] = $offset;
	
	$offset_y = date('Y',$offset);
	$offset_m = date('m',$offset);
	if ($offset_m >6) $offset_m = 7;
	else $offset_m = 1;
	$start = mktime(0,0,0,$offset_m,1,$offset_y);
	$end = mktime(23,59,59,$offset_m+5,31,$offset_y);
	$filters['startdate'] = $start;
	$filters['enddate'] = $end;
	$filters['pid'] = $this->id;
	$selection = new tx_skcalendar_internal();
	$selection->setFilters($filters);
	$selection->getResults();
		
	$calendar = new tx_skcalendar_vceedit($selection,$this->conf);
	$calendar->createHolidays('de');
	$calendar->setRange($filters['startdate'],$filters['enddate']);
	$calendar->createCalendar();
	$content = $calendar->parseCalendar();

	$this->content.=$this->doc->section($LANG->getLL("ovr_cm1"),$content,0,1);
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/cm1/index.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/cm1/index.php"]);
}




// Make instance:
$SOBE = t3lib_div::makeInstance("tx_skcalendar_cm1");
$SOBE->init();


$SOBE->main();
$SOBE->printContent();

?>