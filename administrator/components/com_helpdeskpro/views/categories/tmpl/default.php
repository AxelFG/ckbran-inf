<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;
JHTML::_('behavior.tooltip');
$ordering = ($this->lists['order'] == 'a.ordering');
?>
<div class="row-fluid">
<div class="span12">
<form action="index.php?option=com_helpdeskpro&view=categories" method="post" name="adminForm" id="adminForm">
<table width="100%">
<tr>
	<td align="left">
		<?php echo JText::_( 'HDP_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->state->search;?>" class="text_area search-query input-medium" onchange="document.adminForm.submit();" />		
		<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'HDP_GO' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'HDP_RESET' ); ?></button>		
	</td>
	<td style="text-align: right;">
		<?php echo $this->lists['parent_id']; ?>
		<?php echo $this->lists['filter_state'] ; ?>
	</td>	
</tr>
</table>
<div id="editcell">
	<table class="adminlist table table-striped">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th class="title" style="text-align: left;">
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_TITLE'), 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>											
			<th class="title" width="10%">
				<?php echo JText::_('HDP_NUMBER_TICKETS'); ?>
			</th>			
			<th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_ORDER'), 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JHTML::_('grid.order',  $this->items , 'filesave.png', 'category.save_order' ); ?>
			</th>
			<th width="5%">
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_PUBLISHED'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="2%">
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>													
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="8">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$link 	= JRoute::_( 'index.php?option=com_helpdeskpro&task=category.edit&cid[]='. $row->id );
		$checked 	= JHTML::_('grid.id',   $i, $row->id );				
		$published 	= JHTML::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'category.' );			
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>	
			<td>
				<a href="<?php echo $link; ?>">
					<?php echo $row->treename; ?>
				</a>
			</td>									
			<td style="text-align: center;">
				<?php echo $row->total_tickets; ?>
			</td>												
			<td class="order">
				<span><?php echo $this->pagination->orderUpIcon( $i, ($row->parent_id==0 || $row->parent_id == @$this->items[$i-1]->parent_id), 'category.orderup', 'Move Up', $ordering ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n, ($row->parent_id ==0 || $row->parent_id == @$this->items[$i+1]->parent_id), 'category.orderdown', 'Move Down', $ordering ); ?></span>
				<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>				
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" class="text_area input-mini" style="text-align: center" <?php echo $disabled; ?> />
			</td>			
			<td align="center">
				<?php echo $published; ?>
			</td>
			<td align="center">
				<?php echo $row->id; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>
	</div>
	<input type="hidden" name="option" value="com_helpdeskpro" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />	
	<?php echo JHTML::_( 'form.token' ); ?>			
</form>
</div>
</div>