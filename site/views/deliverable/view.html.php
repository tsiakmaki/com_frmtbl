<?php
/**
* view.html.php
* 
* 
 * @version		0.1 - Summer 2012
 *
 * @license		GNU General Public License
 * @author		m.tsiakmaki [at] gmail [dot] com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
/**
 * Display the table of deliveratbles and the form. 
 * 
 * More features: 
 * (a) form validation 
 * (b) an autocomplete field 
 * (c) tootip
 * (d) date chooser 
 * (e) 
 * 
 *
 */
class FrmtblViewDeliverable extends JView { 
	
	function display ($tpl = null) {
		
		// add javascript, css 
		$this->_addScript();
		
		// get the user's deliverables
		$deliverables =& $this->get('Deliverables');
		
		// Build the page title string
		$title = JText::_('COM_FRMTBL_DELIVERABLES_TITLE');
		
		// build the dropdown lists
		$deliverable_types_list = array();
		foreach (FrmtblHelperFrmtbl::$DELIVERABLE_TYPES_ARRAY as $i => $value) {
			$deliverable_types_list[$i] = JHTML::_('select.option', $i, JText::_($value));
		}
		
		// assing references for the template
		$this->assignRef('title', $title);
		
		$this->assignRef('deliverables', $deliverables);
		
		$this->assignRef('deliverable_types_list', $deliverable_types_list);
		
		// call template 
		parent::display($tpl);
	}
	
	/**
	 * Add the javascript and css  
	 */
	function _addScript() {
		
		JHTML::_('behavior.formvalidation');
		
		JHTML::_('behavior.tooltip');

		$document =& JFactory::getDocument();
		
		// add custom javascript function 
		// for autocomplete
		$document->addScript('components/com_frmtbl/assets/js/Observer.js');		
		$document->addScript('components/com_frmtbl/assets/js/Autocompleter.js');
		$document->addScript('components/com_frmtbl/assets/js/autocomplete_data.js');
		
		// for form submition and editing 
		$document->addScript('components/com_frmtbl/assets/js/frmtbl.js');
		
		// add css
		$document->addStyleSheet('components/com_frmtbl/assets/css/frmtbl.css');
	}
	
	
}
?> 