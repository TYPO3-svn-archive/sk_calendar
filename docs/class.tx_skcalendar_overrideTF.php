<?php
/* This is an examplefile for the Templateoverride function. In this case the dayname of the monthfew is altered. This example doesn't make much sence but you get the idea */

class tx_skcalendar_overrideTF extends tx_skcalendar_defaultTF  {

	function templatefunc_dayname() {
		$this->TempData['name'] = 'Hello';// Manipulates Dayname
		parent::templatefunc_dayname(); // calls the parentfunction for returning the value
	}

}

?>