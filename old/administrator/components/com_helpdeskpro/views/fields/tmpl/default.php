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
	
$fieldTypes = array(
	0 => 'Textbox' ,
	1 => 'Textarea' ,
	2 => 'Dropdown' ,
	3 => 'Checkbox List' ,
	4 => 'Radio List' ,
	5 => 'Date Time',
	6 => 'Heading',		
	7 => 'Message',
	8 => 'MultiSelect',
	9 => 'File upload'				
);
$ordering = ($this->lists['order'] == 'a.ordering');
?>
<div class="row-fluid">
<div class="span12">
<form action="index.php?option=com_helpdeskpro&view=fields" method="post" name="adminForm" id="adminForm">
<table width="100%">
<tr>
	<td align="left" width="60%">
		<?php echo JText::_( 'HDP_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->state->search;?>" class="text_area search-query input-medium" onchange="document.adminForm.submit();" />		
		<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'HDP_GO' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'HDP_RESET' ); ?></button>		
	</td>	
	<td style="text-align: right;">		
		<?php echo $this->lists['category_id']; ?>
		<?php echo $this->lists['filter_state']; ?>
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
			<th style="text-align: left;">
				<?php echo JHTML::_('grid.sort',  'HDP_NAME', 'a.name', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<th style="text-align: left;">
				<?php echo JHTML::_('grid.sort',  'HDP_TITLE', 'a.title', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<th style="text-align: left;">
				<?php echo JHTML::_('grid.sort',  'HDP_FIELD_TYPE', 'a.field_type', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>						
			<th class="title">
				<?php echo JHTML::_('grid.sort',  'HDP_PUBLISHED', 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>			
			<th width="8%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JHTML::_('grid.order',  $this->items , 'filesave.png', 'field.save_order' ); ?>
			</th>			  					
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'ID', 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
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
	$ordering = ($this->lists['order'] == 'a.ordering');
	if (version_compare(JVERSION, '1.6.0', 'ge')) {
	    $j15 = false ;
	} else {
	    $j15 = true ;
	}
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$link 	= JRoute::_( 'index.php?option=com_helpdeskpro&task=field.edit&cid[]='. $row->id );
		$checked 	= JHTML::_('grid.id',   $i, $row->id );		
		$published = JHTML::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'field.' );							
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
					<?php echo $row->name; ?>
				</a>
			</td>	
			<td>
				<a href="<?php echo $link; ?>">
					<?php echo $row->title; ?>
				</a>
			</td>
			<td>
				<?php					
					echo $fieldTypes[$row->field_type] ;								
			 	?>
			</td>												
			<td align="center">
				<?php echo $published ; ?>
			</td>			
			<td class="order">
				<span><?php echo $this->pagination->orderUpIcon( $i, true,'field.orderup', 'Move Up', $ordering ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'field.orderdown', 'Move Down', $ordering ); ?></span>
				<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area input-mini" style="text-align: center" />
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