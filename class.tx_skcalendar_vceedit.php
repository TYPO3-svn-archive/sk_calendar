<?PHP
include_once('calendar_functions.php');
require_once(t3lib_extMgm::extPath('sk_calendar').'pi1/class.tx_skcalendar_feengine.php');

class tx_skcalendar_vceedit  extends tx_skcalendar_htmlview   {

	function tx_skcalendar_vceedit($container,$conf) {
	// constructor Class
	$this->container = $container;
	$this->events = $container->result;
	if (!$this->events) $this->events = Array();
	$this->year = date('Y',$conf['offset']);
	
	}

	function parseCalendar()
	{
		
		$content = '<style type="text/css">
<!--
td.holiday {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #CC3300; background-color: #CCCCCC}
td.past_weekday {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; font-style: normal; font-weight: lighter}
td.calendarheading {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; font-weight: bold}

-->
</style>';
		$content .= $this->makelinks();
		$content .= '<table width="100%" border="1" cellspacing="0" cellpadding="0"><tr valign=\"middle\">';
		for ($month=date('m',$this->container->filters['startdate']); $month<=(date('m',$this->container->filters['startdate'])+5); $month++) // monthline
		{
			$content .= '<td class=calendarheading>&nbsp;' . $this->getMonthName($month) . '<br><img src=""clear.gif"" width="160" height="1"></td>';
		}
		$content .= '</tr>';
		for ($day=1; $day<=31; $day++)
		{
			$content .= '<tr valign="middle">';
			for ($month=intval(date('m',$this->container->filters['startdate'])); $month<=(date('m',$this->container->filters['startdate'])+5); $month++)
			{
				$content .= '<td>';

				$content .= $this->makeMyDay($this->calendarArray[$month][$day]);

				$content .= '</td>';
			}
			$content .= '</tr>';
		}
		$content .= '</table>';
		$content .= $this->makelinks();
		return $content;
	}
	
	function pi_getLL($id) {
	return; // not needed
	}
	
	function makelinks () {
	if (date('m',$this->container->filters['startdate']) == 1) {
	$year = date('Y',$this->container->filters['startdate'])-1;
	$links = '<a href="index.php?id=' . $this->container->filters['pid'] . '&offset=' . mktime(0,0,0,7,1,$year) . '">Jul - Dec ' . $year . '</a>&nbsp;&nbsp;';
	$year++;
	$links.='<a href="index.php?id=' . $this->container->filters['pid'] . '&offset=' . mktime(0,0,0,7,1,$year) . '">Jul - Dec ' . $year . '</a>';
}
else {
$year = date('Y',$this->container->filters['startdate']);
	$links = '<a href="index.php?id=' . $this->container->filters['pid'] . '&offset=' . mktime(0,0,0,1,1,$year) . '">Jan - Jul ' . $year. '</a>&nbsp;&nbsp;';
	$year++;
	$links.='<a href="index.php?id=' . $this->container->filters['pid'] . '&offset=' . mktime(0,0,0,1,1,$year) . '">Jan - Jul ' . $year. '</a>';
}
return $links;
}
	function makeMyDay($day_array) {
		
		$content = '<table border="0" width=100% cellspacing="0" cellpadding="0">'
		.'<tr>';
		
		$content .= '<td class=' . $day_array['style'] . '>&nbsp;' . $day_array['d_name']['no'] . '</td><td class=' . $day_array['style'] . '><img src="clear.gif" width=20></td>'
		.'<td class=' . $day_array['style'] . '>';
		
		if ($day_array['events']) //is there an event?
		{
			
			$content .= '<table border="0" cellspacing="0" cellpadding="0" align="right"><tr valign=bottom>';
			while (list(, $event) = each($day_array['events']))
			{
				if (!$event['category']) $event['category'] = '../images/cat_fallback.gif';
				else {
				$event['category'] = $this->getCategory($event['category'],'icon');
				$event['category'] = '../../../../uploads/tx_skcalendar/' . $event['category'];
				}
				
				$content .= '<td>';
				$params = '&edit[tx_skcalendar_events][' . $event['uid'] . ']=edit';
				$backPath = '../../../../typo3/';
				$requestUri=substr(t3lib_extMgm::extPath("sk_calendar"),strlen(PATH_site)).'cm1/index.php';
				$content .= '<a href=# onclick="' . t3lib_BEfunc::editOnClick($params,$backPath) . '"><img src="' . $event['category'] . '" border=0 width=10 alt="' . $event['title'] . '"></a><br></td>';
			}
			$content .= '</tr></table>';
		}
		$content .= '</td>';

		$content .= '</tr></table>';
		return $content;
	}

	
	}
	?>
	