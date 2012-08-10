<?php
/**
 * default.php
 * 
 * 
 * @version		0.1 - Summer 2012
 *
 * @license		GNU General Public License
 * @author		m.tsiakmaki [at] gmail [dot] com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
$user =& JFactory::getUser();
?>

<div class="message" id="msg_div"></div>

<h1><?php echo $this->title; ?></h1>


<table id="deliverablestable" class="sectiontableheader">
<thead>
	<tr>
		<th class="tableheader" id="title"><?php echo JText::_('COM_FRMTBL_DELIVERABLE_TITLE'); ?></th>
		<th class="tableheader" id="description"><?php echo JText::_('COM_FRMTBL_DELIVERABLE_DESCRIPTION'); ?></th>
		<th class="tableheader" id="type"><?php echo JText::_('COM_FRMTBL_DELIVERABLE_TYPE'); ?></th>
		<th class="tableheader" id="deadline_date"><?php echo JText::_('COM_FRMTBL_DELIVERABLE_DEADLINE'); ?></th>
		<th class="tableheader" id="cost"><?php echo JText::_('COM_FRMTBL_DELIVERABLE_COST'); ?></th>
		<th class="tableheader" width="40pt"></th>
		<th class="tableheader" width="40pt"></th>
	</tr>
</thead>
<tbody>

<?php if(!empty($this->deliverables)) : ?>


<?php foreach ($this->deliverables as $d) : ?>
	<tr id="tr_d_<?php echo $d->deliverable_id; ?>">
		<td headers="title"><?php echo $d->title; ?></td>
		<td headers="description"><?php echo $d->description; ?></td>
		<td headers="type"><?php echo JText::_(FrmtblHelperFrmtbl::$DELIVERABLE_TYPES_ARRAY[$d->type]); ?></td>
		<td headers="deadline_date"><?php echo $d->deadline_date; ?></td>
		<td headers="cost"><?php echo number_format($d->cost, 2, ',', '.'); ?><span>&#128;</span></td>
		<td><a href="#" onclick="javascript:deleteObject('<?php echo $d->deliverable_id;?>', 'deliverable', 'd');" ><?php echo JText::_('COM_FRMTBL_DEL_ACTION'); ?></a></td>
		<td><a href="#deliverablestable" id="edit_deliverable_<?php echo $d->deliverable_id; ?>" rel="<?php echo $d->deliverable_id; ?>">
			<?php echo JText::_('COM_FRMTBL_EDIT_ACTION'); ?></a></td>	
	</tr>
<?php endforeach; ?>

<?php endif; ?>

</tbody>
</table>


<br /><br />

<fieldset>

<legend><?php echo JText::_('COM_FRMTBL_DELIVERABLE'); ?></legend>

<form id="deliverableform" action="#" method="post" class="form-validate">

<div id="deliverableformdiv" class="formdiv">

	<div id="deliverabletitlediv">
	<p><?php echo JText::_('COM_FRMTBL_DELIVERABLE_TITLE'); ?></p>
	<input type="text" name="title" value="" size="30" class="inputbox required" /> *
	</div>
	
	
	<div id="deliverabledescdiv">
	<p><?php echo JText::_('COM_FRMTBL_DELIVERABLE_DESCRIPTION'); ?></p>
	<input type="text" name="description" id="del_description" value="" size="30" class="inputbox required"/> *
	</div>
	
	
	<div>
	<p><?php echo JText::_('COM_FRMTBL_DELIVERABLE_TYPE'); ?></p>
	<?php echo JHTML::_('select.genericlist', $this->deliverable_types_list, 'type', 'size="1" class="inputbox"', 'value', 'text', null); ?>
	</div>

	<div>
	<p><?php echo JText::_('COM_FRMTBL_DELIVERABLE_DEADLINE'); ?></p>
	<?php echo JHTML::_('calendar', '', 'deadline_date', 'deadline_date_form', '%Y-%m-%d', 
		array('class'=>'inputbox required', 'size'=>'10', 'maxlength'=>'10')); ?> *
	</div>
		
	<div>
	<p><?php echo JText::_('COM_FRMTBL_DELIVERABLE_COST'); ?></p>
	<input type="text" name="cost" value="" size="6" class="inputbox"/><span>&#128;</span> 
	<?php echo JHTML::tooltip(JTEXT::_('COM_FRMTBL_DELIVERABLE_COST_TIP'), 
		JText::_('COM_FRMTBL_DELIVERABLE_COST'), 'tooltip.png', null, null); ?>
	</div>
	
	
	<input type="hidden" name="deliverable_id" value=""/>
	<input type="hidden" name="user_id" value="<?php echo $user->id; ?>"/>
		
	<input type="hidden" name="option" value="com_frmtbl"/>
	<input type="hidden" name="controller" value="deliverable"/>
	<input type="hidden" name="task" value="savedeliverable"/>
	<input type="hidden" name="view" value="deliverable"/>
	<input type="hidden" name="format" value="raw"/>
	
	<div class="hr"><!-- spanner --></div>
	
	<input type="button" class="button validate" name="deliverableformbutton" id="deliverableformbutton" value="<?php echo JText::_('COM_FRMTBL_SUBMIT_ACTION'); ?>"/>
	<input type="button" onClick="this.form.reset();clearHiddenIds('deliverableform', 'deliverable_id')" class="button" name="deliverableformreset"  value="<?php echo JText::_('COM_FRMTBL_RESET_ACTION');?>"/>
</div>

</form>

</fieldset>
