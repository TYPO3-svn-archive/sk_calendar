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

	$recurr_until_fallback = date('Y-m-d',$recurr_until_fallback);
	$count = 1;
	
	// arrayforming
	if($event_arr) {
	foreach($event_arr as $row) {
		// Exeption Shootout
		
		$exept_arr = array();
		
		$exeptions = explode(',',$row['exeptions']);
		while (list($void, $exeption) = each ($exeptions))
		{
			$exeption = trim($exeption);
			if ($exeption) $exept_arr[] = $exeption ;
		}
	
		$row['date'] = date('Y-m-d',$row['date']); // convert date
			
		if ($row['recurr_until']) $row['recurr_until'] = date('Y-m-d',$row['recurr_until']); // convert $row['recurr_until']
		$sortfield = $row['date'] . '_' . $count; // so multiple events per Day are possible
		if (!$row['recurr_until']) $row['recurr_until'] = $recurr_until_fallback;
		switch ($row['recurring']) {
			case 1: // daily
			$uid = $row['uid'];
			while ($row['date'] <= $row['recurr_until'])
			{
				if (!in_array($row['date'],$exept_arr)) // ignore exepted dates
				{
					$row['uid'] = $uid .  '_re'.$row['date']; // distinction between different Ghostc	opies	
					$sortfield = $row['date'] . '_' . $count; // so multiple events per Day are possible
					$temp_arr[$sortfield] = $row;
				}
				$date =$row['date'];
				$date = explode('-', $date);
				$date = mktime(0,0,0,$date [1],$date [2]+1,$date [0]);
				$date = date('Y-m-d',$date);
				$row['date']  = $date;
			}			
			break;
			
			case 2: // weekly
			$uid = $row['uid'];
			while ($row['date'] <= $row['recurr_until'])
			{
				if (!in_array($row['date'],$exept_arr)) // ignore exepted dates
				{
					$row['uid'] = $uid .  '_re'.$row['date']; // distinction between different Ghostc	opies	
					$sortfield = $row['date'] . '_' . $count; // so multiple events per Day are possible
					$temp_arr[$sortfield] = $row;
				}
				$date =$row['date'];
				$date = explode('-', $date);
				$date = mktime(0,0,0,$date [1],$date [2]+7,$date [0]);
				$date = date('Y-m-d',$date);
				$row['date']  = $date;
			}			
			break;
						
			case 3: // monthly
			$uid = $row['uid'];
			while ($row['date'] <= $row['recurr_until'])
			{
				if (!in_array($row['date'],$exept_arr)) // ignore exepted dates
				{
					$row['uid'] = $uid .  '_re'.$row['date']; // distinction between different Ghostc	opies	
					$sortfield = $row['date'] . '_' . $count; // so multiple events per Day are possible
					$temp_arr[$sortfield] = $row;
				}
				
				$date =$row['date'];
				$date = explode('-', $date);
				$date = mktime(0,0,0,$date [1]+1,$date [2],$date [0]);
				$date = date('Y-m-d',$date);
				$row['date']  = $date;
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

?>