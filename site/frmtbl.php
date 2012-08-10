<?php
/**
 * 
 * A sample view that contains: 
 * (a) a table that lists the submitted (deliverables) objects 
 *     and enables delete and edit actions through Ajax requests
 * (b) a form that submits (deliverables) objects, through ajax 
 *     request, and appends rows to the above table.
 * 
 * @version		0.1 - Summer 2012
 *
 * @license		GNU General Public License
 * @author		m.tsiakmaki [at] gmail [dot] com
 */
 
// No direct access (a security check)
defined( '_JEXEC' ) or die( 'Restricted access' );

// add the table path
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_frmtbl'.DS.'tables');

// add the helpers
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'frmtbl.php');

// Require the controller, default is FrmtblControllerFrmtbl
$controller = JRequest::getWord('controller', 'frmtbl');
require_once(JPATH_COMPONENT.DS.'controllers' . DS . $controller . '.php');
 
// Create the controller
$classname = 'FrmtblController' . ucfirst($controller);
$controller = new $classname();
 
// Perform the Request task
// If no task is set, the default task 'display' will be assumed
$controller->execute(JRequest::getWord('task'));
 
// Redirect if set by the controller
$controller->redirect();

