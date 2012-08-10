<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * FrmTbl controller 
 * 
 * @version		0.1 - Summer 2012
 *
 * @license		GNU General Public License
 * @author		m.tsiakmaki [at] gmail [dot] com
 */
class FrmtblControllerFrmtbl extends JController {
	
	/**
	 * The default task just calls the parent 
	 * @see JController::display()
	 */
	function display() {
		// check if user already has a distinction submitted
		$user =& JFactory::getUser();
		// users must be logged in, not a quess
		if ($user->guest) {
			$this->setredirect('index.php?option=com_user&view=login');
			return false;
		}
		
        parent::display();
    }
	
}

