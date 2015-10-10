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

$editor = JFactory::getEditor();
?>
<div class="container-fluid">
<form class="form form-horizontal" name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">	
      			<input type="hidden" id="id" name="id" value="<?php echo $this->item->id; ?>" />      			
	<div class="control-group">
		<label class="control-label" for="name"><?php echo JText::_('HDP_NAME'); ?><span class="required">*</span></label>
			<div class="controls">
      			<input type="text" id="name" name="name" placeholder="<?php echo JText::_('HDP_CUSTOMER_NAME'); ?>" value="<?php echo $this->item->name; ?>" />      			
    		</div>			
		</div>
		<div class="control-group">
			<label class="control-label" for="email"><?php echo JText::_('HDP_EMAIL'); ?><span class="required">*</span></label>
			<div class="controls">
      			<input type="text" id="email" name="email" placeholder="<?php echo JText::_('HDP_CUSTOMER_EMAIL'); ?>" value="<?php echo $this->item->email; ?>" />      			
    		</div>			
	</div>	
	<div class="control-group">
			<label class="control-label" for="category_id"><?php echo JText::_('HDP_CATEGORY'); ?><span class="required">*</span></label>
			<div class="controls">
      			   <?php echo $this->lists['category_id'] ; ?> 			
    		</div>			
	</div>
	<div class="control-group">
			<label class="control-label" for="subject"><?php echo JText::_('HDP_SUBJECT'); ?><span class="required">*</span></label>
			<div class="controls">
      			   <input type="text" id="subject" name="subject" class="text_area" value="<?php echo $this->item->subject; ?>" size="50" /> 			
    		</div>			
	</div>
	<div class="control-group">
			<label class="control-label" for="priority_id"><?php echo JText::_('HDP_PRIORITY'); ?><span class="required">*</span></label>
			<div class="controls">
      			   <?php echo $this->lists['priority_id'] ; ?> 			
    		</div>			
	</div>
	<?php
		if ($this->customField) {
			echo $this->fields ;
		}
	?>
	<div class="control-group">
			<label class="control-label" for="message"><?php echo JText::_('HDP_MESSAGE'); ?><span class="required">*</span></label>
			<div class="controls">
				  <?php echo $editor->display( 'message',  $this->item->message , '100%', '250', '75', '10', false) ; ?>		   			    		
    		</div>						
	</div>	
	<?php 
		if ($this->config->enable_attachment) {
		?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('HDP_ATTACHMENTS'); ?></label>
				<div class="controls">
						<ul id="hdp_attachment_list">
							<?php
	        				$maxNumberOfFiles = $this->config->max_number_attachments ? $this->config->max_number_attachments : 1 ;
	        				for ($i = 0 ; $i < $maxNumberOfFiles; $i++) {
	        					if ($i == 0) {
	        						$style = "" ;
	        					} else {
	        						$style = 'style="display:none;"';
	        					}
	        				?>
		            			<li <?php echo $style; ?> id="<?php echo 'hdp_attachment_'.$i; ?>">	                			
		                			<input type="file" name="attachment[]" class="inputbox" size="60" />	                			
		            			</li>  	
	        				<?php            				            				
							}	
							if ($maxNumberOfFiles > 1) {
							?>
	        				    <li>
	                					<input type="button" name="btnAdd" class="btn" onclick="addAttachment();" value="<?php echo JText::_("HDP_ADD_ATTACHMENT"); ?>" />
	                					<input type="button" name="btnDelete" class="btn" onclick="removeAttachment();" value="<?php echo JText::_("HDP_REMOVE_ATTACHMENT"); ?>" />
	                			</li>	                			
	        				<?php    
	        				}
	        			?>     
						</ul>				  			    	
	    		</div>						
			</div>	
		<?php	
		}
	?>	
	<div class="form-actions">
		<input type="button" name="btnSubmit" class="btn btn-primary" value="<?php echo JText::_('HDP_CANCEL'); ?>" onclick="ticketList();" />
		<input type="button" name="btnSubmit" class="btn btn-primary" value="<?php echo JText::_('HDP_SUBMIT_TICKET'); ?>" onclick="submitTicket(this.form);" />
	</div>
<script  type="text/javascript">
	<?php echo $this->fieldJs ; ?>
	var maxAttachment = <?php echo (int)$maxNumberOfFiles ; ?> ;
	var  currentCategory = 0 ;
	var currentNumberAttachment = 1 ;	
	function addAttachment() {					
		if (currentNumberAttachment >= maxAttachment) {
			alert("You cannot add more attachment to ticket. Maximum Attachment per ticket is : " + maxAttachment) ;
		} else{			
			var attachmentId = '#hdp_attachment_' + currentNumberAttachment;					
			jQuery(attachmentId).show(''); 						
			currentNumberAttachment++ ;
		}						
	}
	
	function removeAttachment() {				
		if (currentNumberAttachment > 1) {
			var attachmentId = '#hdp_attachment_' + (currentNumberAttachment - 1);					
			jQuery(attachmentId).hide(''); 						
			currentNumberAttachment-- ;			
		} else {
			alert("<?php echo JText::_("There are no remaining attachment to remove"); ?>");
		}		
	}	
	function submitTicket(form) {				
		if (form.category_id.value == 0) {
			alert("<?php echo JText::_("HDP_CHOOSE_TICKET_CATEGORY"); ?>");
			form.category_id.focus() ;
			return ;	
		}	

		if (form.subject.value == "") {
			alert("<?php echo JText::_("HDP_ENTER_SUBJECT"); ?>") ;
			form.subject.focus();
			return ;
		}
		<?php
            /*if ($config->enable_custom_field && $customField) {
                echo $validations ;    
            }*/
		?>		

				
		form.submit() ;		
	}
			
	function showFields(form) {				
		var newCategoryId = form.category_id.value ;				
		var allFields = fields[0] ;
		if (allFields) {
			for (var i = 0 ; i < allFields.length ; i++) {						
				if (allFields[i]) {
					var trId = '#field_' + allFields[i] ;																	
					//document.getElementById(trId).style.display = '';
					//jQuery.show(trId);
					jQuery(trId).show();
				}													
			}							
		}			
		if(currentCategory) {
			var oldFields = fields[currentCategory];
			//Hide the old fields					
			if (oldFields) {
				for (var i = 0 ; i < oldFields.length ; i++) {						
					if (oldFields[i]) {
						var trId = '#field_' + oldFields[i] ;					
						//jQuery.hide(trId);
						jQuery(trId).hide();							
						//document.getElementById(trId).style.display = 'none';
					}													
				}		
			}	
		}																		
		var newFields = fields[newCategoryId];
		if (newFields) {
			for (var i = 0 ; i < newFields.length ; i++) {						
				if (newFields[i]) {
					var trId = '#field_' + newFields[i] ;																	
					//document.getElementById(trId).style.display = '';
					jQuery(trId).show();
				}								
			}
		}													
		currentCategory = newCategoryId ;
	}		
	function ticketList() {
    	location.href = "index.php?option=com_helpdeskpro&view=tickets&Itemid=<?php echo (int) $this->Itemid; ?>" ;	
	}	  		
</script>
		<input type="hidden" name="option" value="com_helpdeskpro" />
		<input type="hidden" name="task" value="ticket.save" />	
		<?php echo JHTML::_( 'form.token' ); ?>	
</form>	
</div>