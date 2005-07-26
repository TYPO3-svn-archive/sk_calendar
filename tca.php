<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");

// Sysfolder-Mode?
$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sk_calendar']);

if ($confArr['centralStoragePid']) { // special sysfolder for cats, etc.
    $dataSource = $confArr['centralStoragePid'];
}
else {
	$dataSource = '###CURRENT_PID###';
}

$fTableWhere['cat'] = 'AND tx_skcalendar_category.pid='. $dataSource . ' ';
$fTableWhere['org'] = 'AND tx_skcalendar_organizer.pid='. $dataSource . ' ';
$fTableWhere['loc'] = 'AND tx_skcalendar_location.pid='. $dataSource . ' ';
$fTableWhere['tar'] = 'AND tx_skcalendar_targetgroup.pid='. $dataSource . ' ';

$TCA["tx_skcalendar_category"] = Array (
	"ctrl" => $TCA["tx_skcalendar_category"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,title,icon,color"
	),
	"feInterface" => $TCA["tx_skcalendar_category"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"title" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_category.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "48",	
				"max" => "50",	
				"eval" => "required,trim",
			)
		),
		"icon" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_category.icon",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 100,	
				"uploadfolder" => "uploads/tx_skcalendar",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"color" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_category.color",		
			"config" => Array (
				"type" => "input",	
				"size" => "7",	
				"max" => "7",	
				"wizards" => Array(
					"_PADDING" => 2,
					"color" => Array(
						"title" => "Color:",
						"type" => "colorbox",
						"dim" => "12x12",
						"tableStyle" => "border:solid 1px black;",
						"script" => "wizard_colorpicker.php",
						"JSopenParams" => "height=300,width=250,status=0,menubar=0,scrollbars=1",
					),
				),
				"eval" => "trim,nospace",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, title;;;;2-2-2, icon;;;;3-3-3, color")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "fe_group")
	)
);



$TCA["tx_skcalendar_organizer"] = Array (
	"ctrl" => $TCA["tx_skcalendar_organizer"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,name,link,logo,phone,email"
	),
	"feInterface" => $TCA["tx_skcalendar_organizer"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"name" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_organizer.name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
				"eval" => "required",
			)
		),
		"link" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_organizer.link",		
			"config" => Array (
				"type" => "input",		
				"size" => "15",
				"max" => "255",
				"checkbox" => "",
				"eval" => "trim",
				"wizards" => Array(
					"_PADDING" => 2,
					"link" => Array(
						"type" => "popup",
						"title" => "Link",
						"icon" => "link_popup.gif",
						"script" => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"logo" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_organizer.logo",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 100,	
				"uploadfolder" => "uploads/tx_skcalendar",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"phone" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_organizer.phone",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"email" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_organizer.email",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, name, link, logo, phone, email")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "fe_group")
	)
);



$TCA["tx_skcalendar_location"] = Array (
	"ctrl" => $TCA["tx_skcalendar_location"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,title,link,street,zip,city"
	),
	"feInterface" => $TCA["tx_skcalendar_location"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"title" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_location.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "48",	
				"max" => "50",	
				"eval" => "required,trim",
			)
		),
		"link" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_location.link",		
			"config" => Array (
				"type" => "input",		
				"size" => "15",
				"max" => "255",
				"checkbox" => "",
				"eval" => "trim",
				"wizards" => Array(
					"_PADDING" => 2,
					"link" => Array(
						"type" => "popup",
						"title" => "Link",
						"icon" => "link_popup.gif",
						"script" => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"street" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_location.street",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"zip" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_location.zip",		
			"config" => Array (
				"type" => "input",	
				"size" => "5",	
				"max" => "5",	
				"eval" => "trim",
			)
		),
		"city" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_location.city",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, title;;;;2-2-2, link;;;;3-3-3, street, zip, city")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "fe_group")
	)
);



$TCA["tx_skcalendar_targetgroup"] = Array (
	"ctrl" => $TCA["tx_skcalendar_targetgroup"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,title,icon"
	),
	"feInterface" => $TCA["tx_skcalendar_targetgroup"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"title" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_targetgroup.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "48",	
				"max" => "50",	
				"eval" => "required,trim",
			)
		),
		"icon" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_targetgroup.icon",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 100,	
				"uploadfolder" => "uploads/tx_skcalendar",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, title;;;;2-2-2, icon;;;;3-3-3")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "fe_group")
	)
);



$TCA["tx_skcalendar_events"] = Array (
	"ctrl" => $TCA["tx_skcalendar_events"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,title,date,start_time,end_time,wholeday,link,cost,description,image,highlight,fe_owner,pages,exeptions,recurring,recurr_until,category,organizer,targetgroup,location"
	),
	"feInterface" => $TCA["tx_skcalendar_events"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"fe_group" => Array (		
			"exclude" => 1,	
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.fe_group",
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					Array("", 0),
					Array("LLL:EXT:lang/locallang_general.php:LGL.hide_at_login", -1),
					Array("LLL:EXT:lang/locallang_general.php:LGL.any_login", -2),
					Array("LLL:EXT:lang/locallang_general.php:LGL.usergroups", "--div--")
				),
				"foreign_table" => "fe_groups"
			)
		),
		"title" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "48",	
				"max" => "50",	
				"eval" => "required,trim",
			)
		),
		"date" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.date",		
			"config" => Array (
				"type" => "input",
				"size" => "8",
				"max" => "20",
				"eval" => "required,date",
				"checkbox" => "0",
				"default" => "0"
			)
		),
		"duration" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.duration",		
			"config" => Array (
				"type" => "none" // not sure if this is the best type for just a palette header
				)
		),
		"start_time" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.start_time",		
			"config" => Array (
				"type" => "input",	
				"size" => "5",	
				"max" => "5",	
				"eval" => "time",
				"checkbox" => "0",
				"default" => "0"
			)
		),
		"end_time" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.end_time",		
			"config" => Array (
				"type" => "input",	
				"size" => "5",	
				"eval" => "time",
				"max" => "5",	
				"checkbox" => "0",
				"default" => "0"
			)
		),
		"wholeday" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.wholeday",		
			"config" => Array (
				"type" => "check",
			)
		),
		"link" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.link",		
			"config" => Array (
				"type" => "input",		
				"size" => "15",
				"max" => "255",
				"checkbox" => "",
				"eval" => "trim",
				"wizards" => Array(
					"_PADDING" => 2,
					"link" => Array(
						"type" => "popup",
						"title" => "Link",
						"icon" => "link_popup.gif",
						"script" => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"cost" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.cost",		
			"config" => Array (
				"type" => "input",	
				"size" => "5",	
				"eval" => "double2",
			)
		),
		"description" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.description",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"image" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.image",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 500,	
				"uploadfolder" => "uploads/tx_skcalendar",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"highlight" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.highlight",		
			"config" => Array (
				"type" => "check",
			)
		),
		"fe_owner" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.fe_owner",		
			"config" => Array (
				"type" => "group",	
				"internal_type" => "db",	
				"allowed" => "fe_users",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"pages" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.pages",		
			"config" => Array (
				"type" => "group",	
				"internal_type" => "db",	
				"allowed" => "pages",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"recurring" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.recurring",		
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.recurring.I.0", "0"),
					Array("LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.recurring.I.1", "1"),
					Array("LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.recurring.I.2", "2"),
					Array("LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.recurring.I.3", "3"),
					Array("LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.recurring.I.4", "4"),
					Array("LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.recurring.I.5", "5"),
				),
			)
		),
		"recurr_until" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.recurr_until",		
			"config" => Array (
				"type" => "input",
				"size" => "8",
				"max" => "20",
				"eval" => "date",
				"checkbox" => "0",
				"default" => "0"
			)
		),
		"category" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.category",		
			"config" => Array (
				"type" => "select",
				"items" => array (
					Array("LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.choose_cat",0),
					),
				"foreign_table" => "tx_skcalendar_category",
				"foreign_table_where" => $fTableWhere['cat'] . "ORDER BY tx_skcalendar_category.title",
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
						"table"=>"tx_skcalendar_category",
						"pid" =>$dataSource,
						"setValue" => "set"
						),
					"script" => "wizard_add.php",
					),
					"edit" => Array(
					"type" => "popup",
					"title" => "Edit",
					"script" => "wizard_edit.php",
					"popup_onlyOpenIfSelected" => 1,
					"icon" => "edit2.gif",
					"JSopenParams" => "height=350,width=580,status=0,menubar=0,scrollbars=1",
				)
			)
			)
		),
		"organizer" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.organizer",		
			"config" => Array (
				"type" => "select",
				"items" => array (
					Array("LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.choose_orga",0),
					),

				"foreign_table" => "tx_skcalendar_organizer",
				"foreign_table_where" => $fTableWhere['org'] . "ORDER BY tx_skcalendar_organizer.name",
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
						"table"=>"tx_skcalendar_organizer",
						"pid" => $dataSource,
						"setValue" => "set"
						),
					"script" => "wizard_add.php",
					),
					"edit" => Array(
					"type" => "popup",
					"title" => "Edit",
					"script" => "wizard_edit.php",
					"popup_onlyOpenIfSelected" => 1,
					"icon" => "edit2.gif",
					"JSopenParams" => "height=350,width=580,status=0,menubar=0,scrollbars=1",
				)
			)
			)
		),
		"targetgroup" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.targetgroup",		
			"config" => Array (
				"type" => "select",
				"items" => array (
					Array("LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.choose_target",0),
					),

				"foreign_table" => "tx_skcalendar_targetgroup",
				"foreign_table_where" => $fTableWhere['tar'] . "ORDER BY tx_skcalendar_targetgroup.title",
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
						"table"=>"tx_skcalendar_targetgroup",
						"pid" => $dataSource,
						"setValue" => "set"
						),
					"script" => "wizard_add.php",
					),
					"edit" => Array(
					"type" => "popup",
					"title" => "Edit",
					"script" => "wizard_edit.php",
					"popup_onlyOpenIfSelected" => 1,
					"icon" => "edit2.gif",
					"JSopenParams" => "height=350,width=580,status=0,menubar=0,scrollbars=1",
				)
			)
			)
		),
		"location" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.location",		
			"config" => Array (
				"type" => "select",
				"items" => array (
					Array("LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_events.choose_loc",0),
					),

				"foreign_table" => "tx_skcalendar_location",
				"foreign_table_where" => $fTableWhere['loc'] . "ORDER BY tx_skcalendar_location.title",
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
						"table"=>"tx_skcalendar_location",
						"pid" => $dataSource,
						"setValue" => "set"
						),
					"script" => "wizard_add.php",
					),
					"edit" => Array(
					"type" => "popup",
					"title" => "Edit",
					"script" => "wizard_edit.php",
					"popup_onlyOpenIfSelected" => 1,
					"icon" => "edit2.gif",
					"JSopenParams" => "height=350,width=580,status=0,menubar=0,scrollbars=1",
				)
			)
			)

		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, title, date,duration;;2, recurring;;3, link;;;;1-1-1, cost, description;;;richtext[paste|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], image, highlight, fe_owner, pages, organizer;;;;1-1-1, location, category, targetgroup")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "fe_group"),
		"2" => Array("showitem" => "start_time, end_time, wholeday"),
		"3" => Array("showitem" => "recurr_until, exeptions")
	)
);

$TCA["tx_skcalendar_exeptions"] = Array (
	"ctrl" => $TCA["tx_skcalendar_exeptions"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => ""
	),
	"feInterface" => $TCA["tx_skcalendar_exeptions"]["feInterface"],
	"columns" => Array (
		"event" => Array (
			"exclude" => 0,
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_exeptions.event",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_skcalendar_events",
				"foreign_table_where" => "ORDER BY tx_skcalendar_events.uid",
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			)

		),
		"exeptdate" => Array (
		"exclude" => 0,
		"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_exeptions.exeptdate",
		"config" => Array (
			"type" => "input",
			"size" => "8",
			"max" => "20",
			"eval" => "date",
			"checkbox" => "0",
			"default" => "0"
			)
		),

		"substitute_event" => Array (
			"exclude" => 0,
			"label" => "LLL:EXT:sk_calendar/locallang_db.php:tx_skcalendar_exeptions.substitute_event",
			"config" => Array (
				"type" => "select",
				"foreign_table" => "tx_skcalendar_events",
				"foreign_table_where" => "ORDER BY tx_skcalendar_events.uid",
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
				)

			),

		),
	"types" => Array (
		"0" => Array("showitem" => "")
		),
	"palettes" => Array (
	"1" => Array("showitem" => "")
	)
);

?>