<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

if (TYPO3_MODE=="BE")	{
	$GLOBALS["TBE_MODULES_EXT"]["xMOD_alt_clickmenu"]["extendCMclasses"][]=array(
		"name" => "tx_skcalendar_cm1",
		"path" => t3lib_extMgm::extPath($_EXTKEY)."class.tx_skcalendar_cm1.php"
	);
}

$TCA["tx_skcalendar_category"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_category",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY title",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",	
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_skcalendar_category.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, title, icon, color",
	)
);

$TCA["tx_skcalendar_organizer"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_organizer",		
		"label" => "name",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY name",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",	
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_skcalendar_organizer.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, name, link, logo, phone, email",
	)
);

$TCA["tx_skcalendar_location"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_location",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY title",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",	
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_skcalendar_location.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, title, link, street, zip, city",
	)
);

$TCA["tx_skcalendar_targetgroup"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_targetgroup",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY title",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",	
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_skcalendar_targetgroup.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, title, icon",
	)
);


t3lib_extMgm::allowTableOnStandardPages("tx_skcalendar_events");

$TCA["tx_skcalendar_events"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events",		
		"label" => "title",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"special_fields" => Array ("recurring", "recurr_until", "exeptions", "date"),
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY date",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",	
			"fe_group" => "fe_group",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_skcalendar_events.gif",
		// Manage the icons for different kinds of recurring events
		"typeicon_column" => "recurring",
		"typeicons" => Array(
			"1" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_skcalendar_events_d.gif",
			"2" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_skcalendar_events_w.gif",
			"3" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_skcalendar_events_m.gif",
			"4" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_skcalendar_events_y.gif",
			"5" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_skcalendar_events_m2.gif"
			),
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, title, date, start_time, end_time, wholeday, link, cost, description, image, highlight, fe_owner, pages, exeptions, recurring, recurr_until, category, organizer, targetgroup, location",
	)
);

t3lib_extMgm::allowTableOnStandardPages("tx_skcalendar_exeptions");

$TCA["tx_skcalendar_exeptions"] = Array (
	"ctrl" => Array (
		"title" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_exeptions",		
		"label" => "exeptdate",	
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"default_sortby" => "ORDER BY event",	
		"readOnly" => 1,
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_skcalendar_events_x.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "",
	)
);


t3lib_div::loadTCA("tt_content");
$TCA["tt_content"]["types"]["list"]["subtypes_excludelist"][$_EXTKEY."_pi1"]="layout,select_key";


t3lib_extMgm::addPlugin(Array("LLL:EXT:sk_calendar/locallang_db.php:tt_content.list_type", $_EXTKEY."_pi1"),"list_type");
?>