<?PHP
include_once('calendar_functions.php');
class sk_calendar {

	var $startdate;
	var $enddate;
	var $targetgroups;
	var $categories;
	var $allcategories;
	var $alltargetgroups;
	var $alllocations;
	var $allorganizers;
	var $locations;
	var $organizer;
	var $events = Array();
	var $calendar;
	var $holidays;
	var $year;
	var $limit;
	
	function sk_calendar() 
	{
		$this->allcategories = $this->getCategories(); // extra functions for convenient manipulation by extending class
		$this->alltargetgroups = $this->getTargetgroups();
		$this->alllocations = $this->getLocations();
		$this->allorganizers = $this->getOrganizers();
	}
		
	
	function setCalendar($startdate='01-01', $enddate='12-31', $limit='')
	{
		$this->year = date('Y');
		$startdate = explode('-',$startdate);
		$enddate = explode('-',$enddate);
		$this->startdate = mktime(0,0,0,$startdate[0],$startdate[1],$this->year);
		$this->enddate = mktime(0,0,0,$enddate[0],$enddate[1],$this->year);
		
	}

	function setYear($year)
	{
		$this->year = intval($year);
	}

	function setTargetgroup($targetgroups)
	{
		if (is_array($targetgroups)) $this->targetgroups = $targetgroups;
	}

	function setCategories($categories)
	{
		if (is_array($categories)) $this->categories = $categories;
	}

	function setLocations($locations)
	{
		if (is_array($locations)) $this->locations = $locations;
	}

	function setOrganzier($organizer)
	{
		$this->organizer = intval($organizer);
	}

	function queryCalendar ()
	{
		$sql = "SELECT * FROM tx_skcalendar_events WHERE NOT deleted AND NOT hidden";
		if (is_array($this->targetgroups))
		{
			$sql .= 'AND targetgroup in (' . $this->targetgroups[0];
			next ($this->targetgroups);
			while (list($id, $value) = each ($this->targetgroups)) $sql .= ',' . $value;
			$sql .= ') ';
		}
		if (is_array($this->categories))
		{
			$sql .= 'AND category in (' . $this->categories[0];
			next ($this->categories);
			while (list($id, $value) = each ($this->categories)) $sql .= ',' . $value;
			$sql .= ') ';
		}
		if (is_array($this->locations))
		{
			$sql .= 'AND category in (' . $this->locations[0];
			next ($this->locations);
			while (list($id, $value) = each ($this->locations)) $sql .= ',' . $value;
			$sql .= ') ';
		}
		if (intval($this->organizer)) $sql .= 'AND organizer =  ' . $this->organizer . ' ';
		if (intval($this->limit)) $sql .= 'LIMIT ' . $this->limit;

		// Query Database
		$result = mysql_query($sql);
		while ($event = mysql_fetch_array($result,MYSQL_ASSOC))
		{
			$this->events[] = $event;
			
		}

		$this->events = addRecurringEvents($this->events);
		
		// now filter start & enddate
		while (list($key, $value) = each($this->events)) {
		$date = substr($key,0,10);

		if ($date>=$this->startdate && $date <=$this->enddate) $filtered_events[$key] = $value;
		}
		$this->events = $filtered_events;
		

	}
	
	function decodeEvent($event) 
	{
		// decode Time & Date
		if ($event['wholeday']==0 && $event['start_time'] && $event['end_time']) {
			$val = $event['start_time'];
			$val = $val / 60;
			$h = $val/60; // hours
			$h =  floor($h);
			$m = $val-($h*60);
			if ($m < 10) $m = '0'.$m;
			$event['start_time'] = $h . ':' . $m;		
			$val = $event['end_time'];
			$val = $val / 60;
			$h = $val/60; // hours
			$h =  floor($h);
			$m = $val-($h*60);
			if ($m < 10) $m = '0'.$m;
			$event['end_time'] = $h . ':' . $m;
			$event['duration'] = $event['start_time'] . ' - ' . $event['end_time'] . ' ';
		}
		if ($event['date']) $event['date'] = date('d.m.Y',$event['date']);
		$event['duration'] .= $event['date'];
		// Link
		$event['link'] = '<a href="http://' . $event['link'] . '">http://' . $event['link'] . '</a>';
		// cost
		$event['cost'] = str_replace('.',',',$event['cost']);
		// image
		if ($event['image']) $event['image'] = '<img src="uploads/tx_skcalendar/' . $event['image'] . '" align=right>';
		// categories
		$event['category'] = $this->allcategories[$event['category']];
		// Organizer
		$event['organizer'] = $this->allorganizers[$event['organizer']];
		// targetgroup
		$event['targetgroup'] = $this->alltargetgroups[$event['targetgroup']];
		// location
		$event['location'] = $this->alllocations[$event['location']];
		return $event;	
		}

	function makeHolidays($country='de')
	{
		switch ($country) {
			case 'de': // german holidays
			$this->holidays = array(
			'neujahr' => "1.1.",
			'3koenig' => "6.1.",
			'mai' => "1.5.",
			'mariaehf' => "15.8.", // Bavaria should be added by Typoscript later
			'dteinheit' => "3.10.",
			'allerheilig' => "1.11.",
			'weihnacht25' => "25.12.",
			'weihnacht26' => "26.12."
			);

			// Easter
			// Sometimes easter_date() is not available, that is why we use a fallback here.
			// I hope that easter_date() will be standardfunction on all PHP installations.
			// Also there is a offset from 50 Seconds here. Believe it or not, some PHP-Versions produce wrong
			// dates by adding a second here and there.
			$easter_arr = array(
			2004 => 1081634422,
			2005 => 1111878050,
			2006 => 1145138450,
			2007 => 1175983250,
			2008 => 1206226850,
			2009 => 1239487250,
			2010 => 1270332050
			);

			if (!function_exists('easter_date')) $easter = $easter_arr[$this->year];
			else $easter = easter_date($this->year);
			$this->holidays['ostern'] = date("j.n.", $easter);
			$this->holidays['karfr'] = date("j.n.", $easter-172800);
			$this->holidays['ostermo'] = date("j.n.", $easter+86400);

			//Pfingsten
			$pfingsten['day'] = date("d", $easter);
			$pfingsten['month'] = date("m", $easter);
			$pfingsten_date = mktime(0,0,0,$pfingsten['month'],$pfingsten['day']+49,$this->year);
			$this->holidays['pfingsten'] = date("j.n.", $pfingsten_date);
			$this->holidays['pfingstmo'] = date("j.n.", ($pfingsten_date+86400));

			// Christi Himmelfahrt
			$himmelfahrt = mktime(0,0,0,$pfingsten['month'],$pfingsten['day']+39,$this->year);
			$this->holidays['christihf'] = date("j.n.", $himmelfahrt);

			// Fronleichnam
			$fronleichnam['day'] = date("d", $easter);
			$fronleichnam['month'] = date("m", $easter);
			$fronleichnam_date = mktime(0,0,0,$fronleichnam['month'],$fronleichnam['day']+60,$this->year);
			$this->holidays['fronleich'] = date("j.n.", $fronleichnam_date);

			// Advent
			$start_date = mktime(0,0,0,12,24,$this->year);
			$weekday = date("w", $start_date);
			if ($weekday == 0) $weekday=6;
			$this->holidays['advent1'] = date("j.n.", ($start_date-($weekday*86400)));
			$this->holidays['advent2'] = date("j.n.", ($start_date-($weekday*86400)-604800));
			$this->holidays['advent3'] = date("j.n.", ($start_date-($weekday*86400)-1209600));
			$this->holidays['advent4'] = date("j.n.", ($start_date-($weekday*86400)-1814400));
			break;
		}
	}
	
	function getCategories()
	{
		$sql = "SELECT * FROM tx_skcalendar_category WHERE NOT deleted AND NOT hidden";
		$ergebnis = mysql_query($sql);
		while ($category = mysql_fetch_array($ergebnis,MYSQL_ASSOC)) {
			if ($category['icon']) $cat[$category['uid']] = ARRAY ('title' => $category['title'], 'icon' => 'uploads/tx_skcalendar/' . $category['icon'], 'color'=>$category['color']);
			else $cat[$category['uid']] = ARRAY ('title' => $category['title'], 'icon' => 'uploads/tx_skcalendar/cat_fallback.gif', 'color'=>'000000'); // fallback
		}
		return $cat;
	}
	
		function getLocations()
	{
		$sql = "SELECT * FROM tx_skcalendar_location WHERE NOT deleted AND NOT hidden";
		$ergebnis = mysql_query($sql);
		while ($location = mysql_fetch_array($ergebnis,MYSQL_ASSOC)) {
			$loc[$location['uid']] = ARRAY ('title' => $location['title'], 'link'=>$location['link'], 'street'=>$location['street'], 'zip'=>$location['zip'], 'city'=>$location['city']);
		}
		return $loc;
	}
	
	function getTargetgroups()
	{
		$sql = "SELECT * FROM tx_skcalendar_targetgroup WHERE NOT deleted AND NOT hidden";
		$ergebnis = mysql_query($sql);
		while ($target = mysql_fetch_array($ergebnis,MYSQL_ASSOC)) {
			$tar[$target['uid']] = ARRAY ('title' => $target['title'], 'icon'=>$target['icon']);
		}
		return $tar;
	}
	
	function getOrganizers()
	{
		$sql = "SELECT * FROM tx_skcalendar_organizer WHERE NOT deleted AND NOT hidden";
		$ergebnis = mysql_query($sql);
		while ($organizer = mysql_fetch_array($ergebnis,MYSQL_ASSOC)) {
			$org[$organizer['uid']] = ARRAY ('name' => $organizer['name'], 'link'=>$organizer['link'], 'logo'=>$organizer['logo'], 'phone'=>$organizer['phone'], 'email'=>$organizer['email']);
		}
		return $org;
	}


}

class htmlView extends sk_calendar {

	var $calendarArray;
	var $offset;
	
	function htmlView() {
		$this->sk_calendar();
		}
	
	function setOffset($offset)
	{
		$this->offset = intval($offset);
	}

	function makeCalendarArray()
	{
		for ($temp=$this->offset; $temp<=($this->offset+5); $temp++)
		{
			$date_unix = mktime(0,0,0,$temp,1,$this->year);
			$count_days = date("t",$date_unix);
			for ($day=1; $day<=$count_days; $day++)
			{
				$date_unix = mktime(0,0,0,$temp,$day,$this->year);
				$d_name = strftime("%d %a",$date_unix);
				$d_name[strlen($d_name)-1] = '';
				$this->calendarArray[$temp][$day]['d_name'] = $d_name;
				if (date("w",$date_unix)==0) $this->calendarArray[$temp][$day]['isholiday']='1'; // sunday
			}
		}
		
		while (list($name,$date) = each($this->holidays))
		{
			$date = explode('.',$date);
			$this->calendarArray[$date[1]][$date[0]]['hname'] = $name;
			$this->calendarArray[$date[1]][$date[0]]['isholiday'] = 1;
		}
		if (!$this->events) $this->events=array();
		while (list($id, $event) = each($this->events))
		{
			$date = date('Y-m-d',$event['date']);
			$date = explode('-',$date);
			$this->calendarArray[intval($date[1])][intval($date[2])]['events'][] = $event;
		}
	}

	function parseCalendar()
	{
		$content = $this->header();

		$content .= '<table width="100%" border="1" cellspacing="0" cellpadding="0"><tr valign=\"middle\">';

		for ($month=$this->offset; $month<=($this->offset+5); $month++) // monthline
		{
			$content .= '<td class=calendarheading>&nbsp;' . $this->getmonth($month) . '<br><img src=""clear.gif"" width="160" height="1"></td>';
		}
		$content .= '</tr>';
		for ($day=1; $day<=31; $day++)
		{
			$content .= '<tr valign="middle">';
			for ($month=$this->offset; $month<=($this->offset+5); $month++)
			{
				$content .= '<td>';

				$content .= $this->makeMyDay($this->calendarArray[$month][$day]);

				$content .= '</td>';
			}
			$content .= '</tr>';
		}
		$content .= '</table>';
		$content .= $this->footer();
		return $content;
	}

	function makeMyDay($day_array) {
		
		$content = '<table border="0" width=100% cellspacing="0" cellpadding="0">'
		.'<tr>';
		if ($day_array['isholiday']) $style='holiday';
		else $style='date';

		$content .= '<td class=' . $style . '>&nbsp;' . $day_array['d_name'] . '</td><td class=' . $style . '><img src="clear.gif" width=20></td>'
		.'<td class=' . $style . '>';

		if ($day_array['events']) //is there an event?
		{
			
			$content .= '<table border="0" cellspacing="0" cellpadding="0" align="right"><tr valign=bottom>';
			while (list($void, $event) = each($day_array['events']))
			{
				if (!$event['category']) $event['category'] = 'fallback';
				$content .= '<td>';
				$params = '&edit[tx_skcalendar_events][' . $event['uid'] . ']=edit';
				$backPath = '../../../../typo3/';
				$requestUri=substr(t3lib_extMgm::extPath("sk_calendar"),strlen(PATH_site)).'cm1/index.php';
				$content .= '<a href=# onclick="' . t3lib_BEfunc::editOnClick($params,$backPath) . '"><img src="../../../../' . $this->allcategories[$event['category']]['icon'] . '" border=0></a><br></td>';
			}
			$content .= '</tr></table>';
		}
		$content .= '</td>';

		$content .= '</tr></table>';
		return $content;
	}

	function getmonth($month)
	{
		$date_unix = mktime(0,0,0,$month,1,$this->year);
		$name = strftime("%B",$date_unix);
		return $name;
	}
	


	// Dummyfunctions for your convenience.
	function header()
	{
		// this should be handled properly, by typoscript even
		$content = '<style type="text/css">
<!--
td.holiday {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #CC3300; background-color: #CCCCCC}
td.date {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; font-style: normal; font-weight: lighter}
td.calendarheading {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; font-weight: bold}

-->
</style>';
		return $content;
	}

	function footer ()
	{
		return $content;
	}
}
?>
	