<?php
########################################################################
# Calendarfunctions for ext: "sk_calendar"
# by Volker Biberger info@sitekick.de 2004
# 
# 
########################################################################

function addRecurringEvents($event_arr) {
	$temp_arr = array();
	$recurr_until_fallback = date('Y-m-d');
	$recurr_until_fallback = explode('-', $recurr_until_fallback);

	$recurr_until_fallback = mktime(0,0,0,$recurr_until_fallback [1]+12,$recurr_until_fallback [2],$recurr_until_fallback [0]);
	$count = 1;
	
	// get exeptions
	$sql = "SELECT * FROM tx_skcalendar_exeptions";
	$result = mysql_query($sql);
	
	while ($data = mysql_fetch_array($result))
	{
		$exept_arr[$data['event']][] = trim($data['exeptdate']);
	}
	
	// arrayforming
	if($event_arr) {
	foreach($event_arr as $row) {
		// Exeption Shootout
		
		$exeptions = ARRAY();
		if ($exept_arr[$row['uid']]) $exeptions = $exept_arr[$row['uid']];
		
		$sortfield = $row['date'] . '_' . $count; // so multiple events per Day are possible
		if (!$row['recurr_until']) $row['recurr_until'] = $recurr_until_fallback;
		switch ($row['recurring']) {
			case 1: // daily
			$uid = $row['uid'];
			while ($row['date'] <= $row['recurr_until'])
			{
				if (!in_array($row['date'],$exeptions)) // ignore exepted dates
				{
					$row['uid'] = $uid .  '_re'.$row['date']; // distinction between different Ghostcopies	
					$sortfield = $row['date'] . '_' . $count; // so multiple events per Day are possible
					$temp_arr[$sortfield] = $row;
				}
				$date = date('Y-m-d', $row['date']);
				$date = explode('-',$date);
				$row['date'] = mktime(0,0,0,$date[1],$date[2]+1,$date[0]);
			}			
			break;
			
			case 2: // weekly
			$uid = $row['uid'];
			while ($row['date'] <= $row['recurr_until'])
			{
				if (!in_array($row['date'],$exeptions)) // ignore exepted dates
				{
					$row['uid'] = $uid .  '_re'.$row['date']; // distinction between different Ghostcopies	
					$sortfield = $row['date'] . '_' . $count; // so multiple events per Day are possible
					$temp_arr[$sortfield] = $row;
				}
				$row['date'] = $row['date'] + 604800; // one week
				if (date('H',$row['date']) ==23) $row['date'] = $row['date'] + 3600; // stupid DST
				elseif (date('H',$row['date']) ==1) $row['date'] = $row['date'] - 3600; // stupid DST
			}			
			break;
						
			case 3: // monthly
			$uid = $row['uid'];
			while ($row['date'] <= $row['recurr_until'])
			{
				if (!in_array($row['date'],$exeptions)) // ignore exepted dates
				{
					$row['uid'] = $uid .  '_re'.$row['date']; // distinction between different Ghostcopies	
					$sortfield = $row['date'] . '_' . $count; // so multiple events per Day are possible
					$temp_arr[$sortfield] = $row;
				}
				// bit tricky for months have differnt amounts of days
				$date = date('Y-m-d', $row['date']);
				$date = explode('-',$date);
				$row['date'] = mktime(0,0,0,$date[1]+1,$date[2],$date[0]);
				
			}
			break;
			
			case 4: // yearly
			$uid = $row['uid'];
			while ($row['date'] <= $row['recurr_until'])
			{
				if (!in_array($row['date'],$exeptions)) // ignore exepted dates
				{
					$row['uid'] = $uid .  '_re'.$row['date']; // distinction between different Ghostcopies	
					$sortfield = $row['date'] . '_' . $count; // so multiple events per Day are possible
					$temp_arr[$sortfield] = $row;
				}
				// bit tricky for months have differnt amounts of days
				$date = date('Y-m-d', $row['date']);
				$date = explode('-',$date);
				$row['date'] = mktime(0,0,0,$date[1],$date[2],$date[0]+1);
				
			}
			
			case 5: // monthly on a certain weekday
			$uid = $row['uid'];
			while ($row['date'] <= $row['recurr_until'])
			{
				if (!in_array($row['date'],$exeptions)) // ignore exepted dates
				{
					$row['uid'] = $uid .  '_re'.$row['date']; // distinction between different Ghostcopies	
					$sortfield = $row['date'] . '_' . $count; // so multiple events per Day are possible
					$temp_arr[$sortfield] = $row;
				}
				// find next date
				$date = date('Y-m-w', $row['date']); // all we need to know: weekday, month and Year
				$date = explode('-',$date);
				if ($date[2] == 0) $date[2] = 7; // week starts monday
				$weekday_fnm = date('w',mktime(0,0,0,$date[1]+1,1,$date[0])); // fnm = first next month
				if ($weekday_fnm == 0) $weekday_fnm =7;
				if ($date[2] < $weekday_fnm) $add = 5*604800; // need an aditional week to jump the month
				else $add = 4*604800;
				
				$row['date'] = $row['date']  + $add; // ta da ...
				if (date('H',$row['date']) ==23) $row['date'] = $row['date'] + 3600; // stupid DST
				elseif (date('H',$row['date']) ==1) $row['date'] = $row['date'] - 3600; // stupid DST
				
			}

			break;
			default:
			
			$temp_arr[$sortfield] = $row; // just write into temp array
			break;
		}
		$count++;
	} 
	
	ksort($temp_arr); // Sort events.
	}
	return $temp_arr;
	
}

function addToExeptions($exeptions, $exeption)
{
	$exept_arr = ARRAY();
	$exeptions = explode(',',$exeptions);
	while (list($void,$value) = each($exeptions))
	{
		$value = trim($value);
		if ($value) $exept_arr[] = $value;
	}
	if (!in_array($exeption,$exept_arr)) $exept_arr[] = $exeption;
	$exept_string = implode(', ',$exept_arr);
	return $exept_string;
}

function filterRange($events,$filters=false) {
	
	if ($filters['startdate'] && $events) {
	reset($events);
	while (list(,$data) = each ($events)) {
		if ($data['date'] >= $filters['startdate']) $event_arr[] = $data;
		}
			$events = $event_arr;
			unset($event_arr);
		}
		
	if ($filters['enddate'] && $events) {
		reset($events);
		while (list(,$data) = each ($events)) {
			
		if ($data['date'] <= $filters['enddate']) $event_arr[] = $data;
			}
			$events = $event_arr;
			}
		return $events;
	
	}

?>