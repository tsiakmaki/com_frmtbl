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

jimport('joomla.application.component.controller');

/**
 * 
 * Controller for handling delivetables 
 * (a) save
 * (b) edit 
 * (c) delete 
 *
 */
class FrmtblControllerDeliverable extends JController {


	/**
	 * 
	 * Saves the deliverable in the database 
	 * and echos the ajax response as a json encoded string. 
	 */
	function savedeliverable() {

		// users must be logged in, not a quess
		$user =& JFactory::getUser();
		if ($user->guest) {
			return $this->_authenticationAjaxResponse();
		}

		$deliverable =& JTable::getInstance('deliverable');

		// Bind the post array to the user object
		if (!$deliverable->bind(JRequest::get('post'))) {
			return $this->_databaseErrorAjaxResponse($deliverable->getError());
		}

		$deliverable->user_id = $user->id;
		
		// update the currency format
		$deliverable->cost = str_replace(",", ".", $deliverable->cost);

		// commit deliverable
		if(!$deliverable->store()) {
			return $this->_databaseErrorAjaxResponse($deliverable->getError());
		}

		// construct the response
		$response['deliverable_id'] = $deliverable->deliverable_id;
		$response['user_id'] = $deliverable->user_id;
		$response['title'] = $deliverable->title;
		$response['description'] = $deliverable->description;
		$response['type'] = JText::_(FrmtblHelperFrmtbl::$DELIVERABLE_TYPES_ARRAY[$deliverable->type]);
		$response['cost'] = $deliverable->cost;
		$response['deadline_date'] = $deliverable->deadline_date;

		echo json_encode($response);

		return true;
	}

	
	/**
	 * Updates a deliverable in the database 
     * and echos the ajax response as a json encoded string.
	 */
	function editdeliverable() {

		// get user
		$user =& JFactory::getUser();
		// users must be logged in, not a quess
		if ($user->guest) {
			return $this->_authenticationAjaxResponse();
		}

		$deliverable =& JTable::getInstance('deliverable');

		$deliverable_id = JRequest::getInt('deliverable_id');

		// load current deliverable
		if (!$deliverable->load($deliverable_id)) {
			return $this->_databaseErrorAjaxResponse(
				$deliverable->getError() . "[deliverable: " . $deliverable->deliverable_id . "]");
		}

		// check if user can edit
		if(!$this->_canEdit($deliverable, $user->id)) {
			// the user can edit only his own deliverables
			return $this->_permitionErrorAjaxResponse();
		}

		// construct the response
		$response['deliverable_id'] = $deliverable->deliverable_id;
		$response['user_id'] = $deliverable->user_id;
		$response['title'] = $deliverable->title;
		$response['type'] = $deliverable->type;
		$response['description'] = $deliverable->description;
		// format the currency
		$deliverable->cost = str_replace(".", ",", $deliverable->cost);
		$response['cost'] = $deliverable->cost;

		$response['deadline_date'] = $deliverable->deadline_date;

		echo json_encode($response);

		return true;
	}



	/**
	 *
	 * Deletes the deliverable
	 */
	function deletedeliverable() {

		// check if user already has a distinction submitted
		$user =& JFactory::getUser();
		// users must be logged in, not a quess
		if ($user->guest) {
			return $this->_permitionErrorAjaxResponse();
		}

		$deliverable_id = JRequest::getInt('id');

		$deliverable =& JTable::getInstance('deliverable');

		// load the deliverable from the database
		if (!$deliverable->load($deliverable_id)) {
			return $this->_databaseErrorAjaxResponse(
				$deliverable->getError() . "[deliverable: " . $deliverable->deliverable_id . "]");
		}

		// only the owner can delete the deliverable
		if(!$this->_canEdit($deliverable, $user->id)) {
			return $this->_permitionErrorAjaxResponse();
		}

		// delete 
		if(!$deliverable->delete()) {
			return $this->_databaseErrorAjaxResponse(
				$deliverable->getError() . "[deliverable: " . $deliverable->deliverable_id . "]");
		}

		return true;
	}




	/**
	 * Returns true if the user created this deliverable, false otherwise.
	 *
	 * @param int(6) $user_id
	 */
	function _canEdit($deliverable, $user_id) {
		// check if the user is the creator of the deliverable
		return ($deliverable->user_id == $user_id) ? true : false;
	}


	function _authenticationAjaxResponse() {
		$response['error'] = "Please login first";
		$response['error_details'] = "quest user";
		echo json_encode($response);
		return false;
	}

	function _databaseErrorAjaxResponse($error) {
		$response['error'] = "Cannot load resource";
		$response['error_details'] = $error;
		echo json_encode($response);
		return false;
	}

	function _permitionErrorAjaxResponse($error = "") {
		$response['error'] = "Permition error";
		$response['error_details'] = "User is not authorised to view this resource. " . $error;
		echo json_encode($response);
		return false;
	}


}
?>
