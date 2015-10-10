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
$ordering = ($this->lists['order'] == 'a.ordering');
?>
<div class="row-fluid">
<div class="span12">
<form action="index.php?option=com_helpdeskpro&view=tickets" method="post" name="adminForm" id="adminForm">
<table style="width:100%">
<tr>
	<td class="pull-left">
		<?php echo JText::_( 'HDP_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->state->search;?>" class="text_area search-query input-medium" onchange="document.adminForm.submit();" />		
		<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'HDP_GO' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'HDP_RESET' ); ?></button>		
	</td>
	<td class="pull-right">
		<?php echo $this->lists['category_id']; ?>
		<?php echo $this->lists['status_id']; ?>
		<?php echo $this->lists['priority_id']; ?>
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
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_TITLE'), 'a.subject', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>											
			<th class="title" style="text-align: left;">
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_CATEGORY'), 'a.category_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>										
			<th class="title" style="text-align: left;">
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_USER'), 'c.username', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_CREATED_DATE'), 'a.created_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_MODIFIED_DATE'), 'a.modified_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="8%">
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_STATUS'), 'a.status_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="8%">
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_PRIORITY'), 'a.priority_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="2%">
				<?php echo JHTML::_('grid.sort',  JText::_('HDP_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>													
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="10">
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
		$link 	= JRoute::_( 'index.php?option=com_helpdeskpro&task=ticket.edit&cid[]='. $row->id);		
		$checked 	= JHTML::_('grid.id',   $i, $row->id );						
		if ($row->user_id) {			
				$accountLink = 'index.php?option=com_users&task=user.edit&id='.$row->user_id ;			
		}			
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>	
			<td>																			
				<a href="<?php echo $link; ?>"><?php echo $row->subject ; ?></a>				
			</td>
			<td>
				<?php echo $row->category_title ; ?>
			</td>
			<td>																			
				<span class="submitter_name"><?php echo $row->name ; ?>
				<?php
					if ($row->username) {
					?>
						<a href="<?php echo $accountLink; ?>" title="View Profile"><span class="icon-user">[<strong><?php echo $row->username ; ?></strong>]</span></a>
					<?php	
					}
				?>	
				</span>
				<span class="submitter_email"><a href="mailto:<?php echo $row->email; ?>"><?php echo $row->email ; ?></a></span>						
			</td>
			<td align="center">
				<?php echo JHtml::_('date', $row->created_date, $this->dateFormat); ?>				
			</td>																									
			<td align="center">
				<?php echo JHtml::_('date', $row->modified_date, $this->dateFormat); ?>				
			</td>
			<td>				
				<?php echo @$this->statusList[$row->status_id]; ?>
			</td>					
			<td>
				<?php echo $this->priorityList[$row->priority_id]; ?>
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