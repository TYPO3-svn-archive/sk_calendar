<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Sven Wilhelm / Icecrash.com <wilhelm@icecrash.com>
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
/*
 * $Id$
 */
 
require_once("../../lib/class.tx_skcalendar_event.php");
require_once("PHPUnit.php");

class tx_skcalendar_eventTest extends PHPUnit_TestCase {

	var $event;
	
	function tx_skcalendar_eventTest($name) { 
		$this->PHPUnit_TestCase($name);
	}

	
	/* called before test functions will be executed */
	function setUp() {
		/* create new instance of event class */
		$this->event = new tx_skcalendar_event();
	}
    
	/* called after test functions are executed, cleanup */
	function tearDown() {
		/* delete instance event */
		unset($this->event);
	}
	
	function testSetIdFalse() {
		$id = '1|25';
		$result = $this->event->setId($id);
		$this->assertEquals('tx_skcalendar_error', get_class($result));
	}
	function testSetIdRight() {
		$id = '1#25';
		$result = $this->event->setId($id);
		$this->assertEquals($id, $result);
	}
	
	function testsetTitle() {
		$title = 'Event Title';
		$result = $this->event->setTitle($title);
		$this->assertEquals($title, $result);
	}
	
	function testSetStartTimeFalse() {
		$timestamp = 'abc';
		$result = $this->event->setStartTime($timestamp);
		$this->assertEquals('tx_skcalendar_error', get_class($result));
	}
	
	function testSetStartTimeRight() {
		$timestamp = time();
		$result = $this->event->setStartTime($timestamp);
		$this->assertEquals($timestamp, $result);
	}
	function testSetEndTime() {
		$timestamp = time();
		$result = $this->event->setEndTime($timestamp);
		$this->assertEquals($timestamp, $result);
	}
	function testSetLocation() {
		$location = 'Darmstadt';
		$result = $this->event->setLocation($location);
		$this->assertEquals($location, $result);
	}
	function testSetCycle() {
		$cycle = 'Not yet implemented';
		$this->assertEquals($cyle, '');
	}
}


$suite = new PHPUnit_TestSuite('tx_skcalendar_eventTest');

/* for calling a single test inside an TestCase */
//$suite->addTest(new tx_skcalendar_eventTest('testName'));
$result = PHPUnit::run($suite);
print($result->toString());
?>
