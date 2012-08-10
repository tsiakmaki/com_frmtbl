<?php
/**
* deliverable.php
* 
* 
 * @version		0.1 - Summer 2012
 *
 * @license		GNU General Public License
 * @author		m.tsiakmaki [at] gmail [dot] com
 */

class JTableDeliverable extends JTable {
	
	/**
	 * 
	 * pk
	 * @var int
	 */
	var $deliverable_id = null;
	

	/**
	 * 
	 * @var int (fk) 
	 */
	var $user_id = null;
	
	/**
	 * 
	 * title
	 * @var string
	 */
	var $title = null;

	/**
	 * 
	 * type
	 * @var int
	 */
	var $type = null;
	
	/**
	 * 
	 * @var string
	 */
	var $description = null;
	
	/**
	 * 
	 * @var float
	 */
	var $cost = null;
	
	/**
	 * 
	 * @var date
	 */
	var $deadline_date = null; 
	
	/**
	 * override 
	 * Object constructor to set table and key field
	 * @param $db
	 */
	function __construct(&$db) {
		parent::__construct('#__proposal_deliverable', 'deliverable_id', $db);
	}
}
?> 