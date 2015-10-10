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

$editor = & JFactory::getEditor(); 	
?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel_category') {
			submitform( pressbutton );
			return;				
		} else {
			submitform( pressbutton );
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div style="float:left; width: 100%;">	
			<table class="admintable" width="100%">
				<tr>
					<td width="100" class="key">
						<?php echo  JText::_('HDP_TITLE'); ?>
					</td>
					<td>
						<input class="text_area" type="text" name="title" id="title" size="40" maxlength="250" value="<?php echo $this->item->title;?>" />
					</td>
				</tr>			
				<tr>
					<td class="key">
						<?php echo  JText::_('HDP_PARENT_CATEGORY'); ?>
					</td>
					<td>
						<?php echo $this->lists['parent_id']; ?>	
					</td>				
				</tr>				
				<tr>
					<td class="key">
						<?php echo  JText::_('HDP_ACCESS_LEVEL'); ?>
					</td>
					<td>
						<?php echo $this->lists['access']; ?>	
					</td>				
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('HDP_MANAGERS'); ?>
					</td>
					<td>
						<input type="text" name="managers" class="inputbox input-xxlarge" value="<?php echo $this->item->managers; ?>" placeholder="<?php echo JText::_('HDP_MANAGERS_EXPLAIN'); ?>" />
					</td>
				</tr>											
				<tr>
					<td class="key">
						<?php echo JText::_('HDP_DESCRIPTION'); ?>
					</td>
					<td>
						<?php echo $editor->display( 'description',  $this->item->description , '100%', '250', '75', '10' ) ; ?>
					</td>
				</tr>				
				<tr>
					<td class="key">
						<?php echo JText::_('HDP_PUBLISHED'); ?>
					</td>
					<td>
						<?php echo $this->lists['published']; ?>
					</td>
				</tr>
		</table>										
</div>		
<div class="clr"></div>	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_helpdeskpro" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
</form>