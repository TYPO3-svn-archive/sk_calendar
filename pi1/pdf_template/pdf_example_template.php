<?php
	$pdf->image(t3lib_extMgm::extPath('sk_calendar') . 'pi1/pdf_template/logo_sitekick.jpg',5,5,57);
	$pdf->SetFont('Arial','BU',25);
	$pdf->setxy(100,15);
	$pdf->Cell(136,12,'PDF Output of sk_calendar - ENJOY!');

	$pdf->settextcolor(0,0,0);
	$pdf->SetFont('Arial','',6);
	$pdf->setxy(7,198);
	$pdf->Cell(136,12,"(c) 2004 Sitekick");
	$pdf->setxy(245,198);
	$pdf->Cell(136,12,'Created by: Sitekick (http://www.sitekick.de)');
	$pdf->SetFont('Arial','',10);
	$pdf->setxy(55,199);
	$pdf->Cell(136,12,"Democalender by Sitekick. Edit apearance in pi1/pdf_template/pdf_example_template.php");
?>
