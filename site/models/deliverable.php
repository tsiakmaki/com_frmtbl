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

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.components.model');

/**
 * 
 * Deliverables model 
 * 
 *
 */
class FrmtblModelDeliverable extends JModel {
	
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct() {
		parent::__construct();
		
		// in case a deliverable is edited
		$this->_deliverable_id = (int)JRequest::getVar('deliverable_id', 0);
	}
	
	
	/**
	 * 
	 * Returns a list of deliverable objects, given the user id
	 */
	function getDeliverables() {
		$user =& JFactory::getUser();
		$user_id = $user->get('id');
		
		$query = "SELECT d.*
				  FROM #__proposal_deliverable d
				  WHERE d.user_id = " . $user_id;
		
		$this->_db->setQuery($query);
		$this->_deliverables = $this->_db->loadObjectList();
		
		return $this->_deliverables;
	}
	
	/**
	 * Returns the deliverable object given the deliverable_id of the request
	 */
	function getDeliverable() {
		if($this->_deliverable_id == '0') { 
			return array(); 
		}
		
		$query = "SELECT * 
				  FROM #__proposal_deliverable  
				  WHERE deliverable_id = " . $this->_deliverable_id;
		
		$this->_db->setQuery($query);
		$this->_deliverable = $this->_db->loadObject();
		
		return $this->_deliverable;
	}

	
}

?> 