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

?>
<script language="javascript" type="text/javascript">
	<?php
		if (version_compare(JVERSION, '1.6.0', 'ge')) {
		?>
		Joomla.submitbutton = function(pressbutton)
		{
			var form = document.adminForm;
			if (pressbutton == 'field.cancel') {
				Joomla.submitform(pressbutton, form);
				return;				
			} else {
				if (form.name.value == "") {
					alert("<?php echo JText::_('OSM_ENTER_CUSTOM_FIELD_NAME'); ?>");
					form.name.focus();
					return ;
				}
				if (form.title.value == "") {
					alert("<?php echo JText::_("OSM_ENTER_CUSTOM_FIELD_TITLE"); ?>");
					form.title.focus();
					return ; 
				}
				if (form.field_type.value == -1) {
					alert("<?php echo JText::_("OSM_CHOOSE_CUSTOM_FIELD_TYPE") ; ?>");
					return ; 
				}					
				//Validate the entered data before submitting									
				Joomla.submitform(pressbutton, form);
			}								
		}	
		<?php	
		} else {
		?>
			function submitbutton(pressbutton) {
				var form = document.adminForm;
				if (pressbutton == 'field.cancel') {
					submitform( pressbutton );
					return;				
				} else {
					//Should validate the information here
					if (form.name.value == "") {
						alert("<?php echo JText::_('OSM_ENTER_CUSTOM_FIELD_NAME'); ?>");
						form.name.focus();
						return ;
					}
					if (form.title.value == "") {
						alert("<?php echo JText::_("OSM_ENTER_CUSTOM_FIELD_TITLE"); ?>");
						form.title.focus();
						return ; 
					}
					if (form.field_type.value == -1) {
						alert("<?php echo JText::_("OSM_CHOOSE_CUSTOM_FIELD_TYPE") ; ?>");
						return ; 
					}			
					
					submitform( pressbutton );
				}
			}	
		<?php			
		}
	?>	
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-95" style="float:left">			
	<table class="admintable adminform" width="100%">		
		<tr>
			<td class="key" valign="top"  width="25%"> 
				<?php echo JText::_('HDP_CATEGORY'); ?>
			</td>
			<td>
				<?php echo $this->lists['category_id'] ; ?>
			</td>
			<td>
				&nbsp;
			</td>
		</tr>			
		<tr>
			<td class="key">
				<?php echo  JText::_('HDP_NAME'); ?>				
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->item->name;?>" onchange="checkFieldName();" />
			</td>
			<td>
				<?php echo JText::_('HDP_FIELD_NAME_REQUIREMNET'); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo  JText::_('HDP_TITLE'); ?>				
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="50" maxlength="250" value="<?php echo $this->item->title;?>" />
			</td>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo JText::_('HDP_FIELD_TYPE'); ?>
			</td>
			<td>
				<?php echo $this->lists['field_type']; ?>
			</td>
			<td>
				&nbsp;
			</td>
		</tr>		
		<tr>
			<td class="key">
				<?php echo JText::_('HDP_DESCRIPTION'); ?>
			</td>
			<td>
				<textarea rows="5" cols="50" name="description"><?php echo $this->item->description;?></textarea>
			</td>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo JText::_('HDP_REQUIRED'); ?>
			</td>
			<td>
				<?php echo $this->lists['required']; ?>
			</td>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo JText::_('HDP_VALUES'); ?>
			</td>
			<td>
				<textarea rows="5" cols="50" name="values"><?php echo $this->item->values; ?></textarea>
			</td>
			<td>
				<?php echo JText::_('HDP_EACH_ITEM_IN_ONELINE'); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo JText::_('HDP_DEFAULT_VALUES'); ?>
			</td>
			<td>
				<textarea rows="5" cols="50" name="default_values"><?php echo $this->item->default_values; ?></textarea>
			</td>
			<td>
				<?php echo JText::_('HDP_EACH_ITEM_IN_ONELINE'); ?>
			</td>
		</tr>			
		<tr>
			<td class="key">
				<?php echo JText::_('HDP_DATATYPE_VALIDATION') ; ?>
			</td>
			<td>
				<?php echo $this->lists['datatype_validation']; ?>
			</td>
			<td>
				&nbsp;
			</td>
		</tr>								
		<tr>
			<td class="key">
				<?php echo  JText::_('HDP_ROWS'); ?>
			</td>
			<td>
				<input class="text_area" type="text" name="rows" id="rows" size="10" maxlength="250" value="<?php echo $this->item->rows;?>" />
			</td>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo  JText::_('HDP_COLS'); ?>
			</td>
			<td>
				<input class="text_area" type="text" name="cols" id="cols" size="10" maxlength="250" value="<?php echo $this->item->cols;?>" />
			</td>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo  JText::_('HDP_SIZE'); ?>
			</td>
			<td>
				<input class="text_area" type="text" name="size" id="size" size="10" maxlength="250" value="<?php echo $this->item->size;?>" />
			</td>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo  JText::_('HDP_CSS_CLASS'); ?>
			</td>
			<td>
				<input class="text_area" type="text" name="css_class" id="css_class" size="10" maxlength="250" value="<?php echo $this->item->css_class;?>" />
			</td>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo  JText::_('HDP_EXTRA'); ?>
			</td>
			<td>
				<input class="text_area" type="text" name="extra" id="extra" size="40" maxlength="250" value="<?php echo $this->item->extra;?>" />
			</td>
			<td>
				&nbsp;
			</td>
		</tr>			
		<tr>
			<td class="key">
				<?php echo JText::_('HDP_PUBLISHED'); ?>
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
			<td>
				&nbsp;
			</td>
		</tr>		
	</table>			
</div>		
<div class="clr"></div>
	<input type="hidden" name="option" value="com_helpdeskpro" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />	
	<?php echo JHTML::_( 'form.token' ); ?>	
	<script type="text/javascript" language="javascript">
		function checkFieldName() {
			var form = document.adminForm ;
			var name = form.name.value ;
			var oldValue = name ;			
			name = name.replace('hdp_', '');
			while(name.indexOf('  ') >=0)
			 	name = name.replace('  ', ' ');											
			while(name.indexOf(' ') >=0)
			 	name = name.replace(' ', '_');
		 	name = name.replace(/[^a-zA-Z0-9_]*/ig, '');
			form.name.value='hdp_' + name;						
		}
	</script>	
</form>