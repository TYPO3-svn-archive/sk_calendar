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
//INIT
include_once(t3lib_extMgm::extPath('sk_calendar').'fpdf152/fpdf.php');
define('FPDF_FONTPATH',t3lib_extMgm::extPath('sk_calendar').'fpdf152/font/');//FŸr FPDF
define('FPDF_CACHE',t3lib_extMgm::extPath('sk_calendar').'fpdf152/cache/');//FŸr FPDF

// Manage HTML-View of Data

class tx_skcalendar_yearview extends tx_skcalendar_htmlview {

	function tx_skcalendar_yearview($container,$conf) {
		// calls mothership
		$this->tx_skcalendar_feengine($container,$conf);
	}

	function parseCalendar() {
		reset ($this->events);
		$offset = 1;
		$pdf=new FPDF('L', 'mm', 'A4');
		$pdf->Open();
		$pdf->SetAutoPageBreak(0);

		$pdf->AddPage();

		// include PDF_template
		include(t3lib_extMgm::extPath('sk_calendar').'/pi1/pdf_template/pdf_example_template.php');

		//Generate Months
		$pdf->SetFont('Arial','B',8);
		$xcoord = 7; // horizontaler Offset
		$ycoord=42;
		for ($month=$offset; $month<=($offset+5); $month++)
		{
			$pdf->setxy($xcoord,$ycoord);
			$pdf->Cell(47,5,$this->getMonthName($month),1);
			$xcoord = $xcoord+47;
		}

		//Generate days

		$xcoord = 7;
		$pdf->setFillColor(204,204,204);
		for ($month=$offset; $month<=($offset+5); $month++)
		{
			$ycoord=47; // vertical Offset +5 (second line)
			for ($day=1; $day<=31; $day++)
			{
				$pdf->setxy($xcoord,$ycoord);
				$pdf->SetFont('Arial','',8);
				if ($this->calendarArray[$month][$day][isholiday])
				{
					$pdf->SetTextColor(255,0,0);
					$pdf->Cell(47,5,$this->calendarArray[$month][$day][d_name][no] . ' ' . $this->calendarArray[$month][$day][d_name][short],1,0,'l',1);
				}
				else {
					$pdf->SetTextColor(0,0,0);
					$pdf->Cell(47,5,$this->calendarArray[$month][$day][d_name][no] . ' ' . $this->calendarArray[$month][$day][d_name][short],1);
				}
				$pdf->setxy($xcoord+8,$ycoord);
				if ($this->calendarArray[$month][$day][events]) {
					unset($event_string);
					unset($first);
					while(list(,$content) = each($this->calendarArray[$month][$day][events])) {
						if($first) $event_string .= ' || ';
						$event_string .= $content['title'];
						$first = 1;
					}
					$maxchar = 20;
					if (strlen($event_string)>$maxchar)
					{
						$break = strpos($event_string, ' ',$maxchar); // find first space after $maxchar threshold
						if ($break>$maxchar) // can we split?
						{
							$string[0]=substr_replace($event_string,'',$break);
							$string[1]=substr($event_string, $break+1);

							$pdf->setxy($xcoord+8,$ycoord);
							$pdf->Cell(39,3,$string[0]);
							$pdf->setxy($xcoord+8,$ycoord+2);
							$pdf->Cell(39,3,$string[1]);
						}
						else $pdf->Cell(39,5,$event_string);
					}
					else $pdf->Cell(39,5,$event_string);
				}
				else $pdf->Cell(39,5,$this->calendarArray[$month][$day][hname]);
				$ycoord = $ycoord+5;
			}
			$xcoord = $xcoord+47;
		}


		// Seite 2
		$offset=7;
		$pdf->AddPage();

		// include PDF_template
		include(t3lib_extMgm::extPath('sk_calendar').'/pi1/pdf_template/pdf_example_template.php');

		// months
		$pdf->SetFont('Arial','B',8);
		$pdf->SetTextColor(0,0,0);
		$xcoord = 7; // horizontaler Offset
		$ycoord=42;
		for ($month=$offset; $month<=($offset+5); $month++)
		{
			$pdf->setxy($xcoord,$ycoord);
			$pdf->Cell(47,5,$this->getMonthName($month),1);
			$xcoord = $xcoord+47;
		}

		// dates
		$pdf->SetFont('Arial','',8);
		$xcoord = 7;
		$pdf->setFillColor(204,204,204);
		$pdf->SetTextColor(255,0,0);
		for ($month=$offset; $month<=($offset+5); $month++)
		{
			$ycoord=47; // vertical Offset +5 (second line)
			for ($day=1; $day<=31; $day++)
			{
				$pdf->setxy($xcoord,$ycoord);
				$pdf->SetFont('Arial','',8);
				if ($this->calendarArray[$month][$day][isholiday])
				{
					$pdf->SetTextColor(255,0,0);
					$pdf->Cell(47,5,$this->calendarArray[$month][$day][d_name][no] . ' ' . $this->calendarArray[$month][$day][d_name][short],1,0,'l',1);
				}
				else {
					$pdf->SetTextColor(0,0,0);
					$pdf->Cell(47,5,$this->calendarArray[$month][$day][d_name][no] . ' ' . $this->calendarArray[$month][$day][d_name][short],1);
				}
				$pdf->setxy($xcoord+8,$ycoord);
				if ($this->calendarArray[$month][$day][events]) {
					unset($event_string);
					unset($first);
					while(list(,$content) = each($this->calendarArray[$month][$day][events])) {
						if($first) $event_string .= ' || ';
						$event_string .= $content['title'];
						$first = 1;
					}
					$maxchar = 20;
					if (strlen($event_string)>$maxchar)
					{
						$break = strpos($event_string, ' ',$maxchar); // find first space after $maxchar threshold
						if ($break>$maxchar) // can we split?
						{
							$string[0]=substr_replace($event_string,'',$break);
							$string[1]=substr($event_string, $break+1);

							$pdf->setxy($xcoord+8,$ycoord);
							$pdf->Cell(39,3,$string[0]);
							$pdf->setxy($xcoord+8,$ycoord+2);
							$pdf->Cell(39,3,$string[1]);
						}
						else $pdf->Cell(39,5,$event_string);
					}
					else $pdf->Cell(39,5,$event_string);
				}
				else $pdf->Cell(39,5,$this->calendarArray[$month][$day][hname]);
				$ycoord = $ycoord+5;
			}
			$xcoord = $xcoord+47;
		}

		// Output
		$rand = $this->random();
		$file=$rand . time() . '.pdf'; //$rand + Tstamp as name
		$path = FPDF_CACHE;
		$path .= $file;

		$pdf->Output($path, 'F');
		$this->content = 'Der Kalender wurde erzeugt und der Download wird gestartet. Sollte der Download nicht funktionieren klicken Sie bitte <a href="' . t3lib_extMgm::extRelPath('sk_calendar') . 'fpdf152/cache/' . $file . '">hier</a>.';
		header ('Location: typo36/' . t3lib_extMgm::extRelPath('sk_calendar') . 'fpdf152/cache/' . $file); // This has proven to be best behind paranoid firewalls,
	}

	function random($length=3)
	{
		$code = "";
		$arr = array();

		// Generate 62 element array with all posiible chars
		$arr = array_merge(range(0,9),range('a','z'),range('A','Z'));

		// srand not needed for php 4.2.0 and up
		srand ((float) microtime() * 10000000);

		foreach (range(1,$length) as $tmp) {
			$code .= $arr[rand(0,61)];
		}
		return $code;
	}
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_yearview.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sk_calendar/pi1/class.tx_skcalendar_yearview.php"]);
}
