<?
/***************************************************************
*  Copyright notice
*
*  (c) 2003 Volker Biberger (info@sitekick.de)
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

/**
 * Interface for storagebackends of the sk_calendar.
 *
 * @author Sven Wilhelm <wilhelm@icecrash.com>
 */


class istoragebackend {
	
	var $searchFilter;

	/**
	 * 
	 */
	function istoragebackend($pool){
		die('This is an interface. It is not instanceable.');		
	}
	
	/**
	 * 
	 */
	function setSearchFilter($searchfilter) {
		//check for class search_filter and set object
		$this->searchFilter = $searchFilter;
	}
	
	/**
	 * 
	 */
	function getDates() {
		
	}	
}
?>