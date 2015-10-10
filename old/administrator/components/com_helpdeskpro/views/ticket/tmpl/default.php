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
$editor = JFactory::getEditor(tinymce) ;
?>
<div id="hdp_container" class="container-fluid">
<form action="index.php" method="post" name="adminForm1" id="adminForm1" enctype="multipart/form-data">
<!--Toolbar buttons-->	
<div class="row-fluid admintable">
	<div class="btn-toolbar hdp_toolbar">
		<div class="btn-group">
        	<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('HDP_CHANGE_TICKET_CATEGORY'); ?> <span class="caret"></span></button>
	    	<ul class="dropdown-menu">		            	
                <?php
					foreach ($this->categories as $category) {
					?>
						<li><a href="javascript:changeTicketCategory(<?php echo $category->id; ?>)"><?php echo $category->treename; ?></a></li>	
					<?php	
					}
            	?>	
        	</ul>
		</div>
		<div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('HDP_CHANGE_TICKET_STATUS'); ?> <span class="caret"></span></button>
	            <ul class="dropdown-menu">
	            	<?php
						foreach ($this->rowStatuses as $rowStatus) {
						?>
							<li><a href="javascript:changeTicketStatus(<?php echo $rowStatus->id; ?>);"><?php echo $rowStatus->title; ?></a></li>	
						<?php	
						}
	            	?>		                  		                 
                </ul>
	    </div>	            
	    <div class="btn-group">
	    	<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('HDP_CHANGE_TICKET_PRIORITY'); ?> <span class="caret"></span></button>
			<ul class="dropdown-menu">
                <?php
					foreach ($this->rowPriorities as $rowPriority) {
					?>
						<li><a href="javascript:changeTicketPriority(<?php echo $rowPriority->id; ?>)"><?php echo $rowPriority->title; ?></a></li>	
					<?php	
					}
            	?>	
	    	</ul>
	   	</div>	   	 	          
	</div>
</div>
	<input type="hidden" name="option" value="com_helpdeskpro" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="new_value" value="0" />			
	<?php echo JHTML::_( 'form.token' ); ?>	
	<script type="text/javascript">
		var maxAttachment = <?php echo (int)$maxNumberOfFiles ; ?> ;
		var  currentCategory = 0 ;
		var currentNumberAttachment = 1 ;
		var currentStatus = 0 ;
		function showMessageBox() {
			if (currentStatus) {
				jQuery('#tr_message_id').hide();
				currentStatus = 0 ;
			} else {
				jQuery('#tr_message_id').show();
				currentStatus = 1 ;
			}				
		}		
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

		function addComment(form) {
			form.task.value = 'ticket.add_comment' ;			
			form.submit();
		}		

		function changeTicketCategory(newCategoryId) {
			var form = document.adminForm1 ;
			form.new_value.value = newCategoryId ;
			form.task.value = 'ticket.update_category';
			form.submit();
		}	

		function changeTicketStatus(newStatus) {
			var form = document.adminForm1 ;
			form.new_value.value = newStatus ;
			form.task.value = 'ticket.update_status';
			form.submit();
		}
		function changeTicketPriority(newPriority) {
			var form = document.adminForm1 ;
			form.new_value.value = newPriority ;
			form.task.value = 'ticket.update_priority';
			form.submit();
		}	
	</script>	
</form>
<div class="row-fluid">
	<div id="hdp_left_panel" class="span9">					
		<table class="adminform">	
			<tr>
				<td>
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
			<label class="control-label" for="subject"><?php echo JText::_('HDP_SUBJECT'); ?><span class="required">*</span></label>
			<div class="controls">
      			   <input type="text" id="subject" name="subject" class="text_area" value="<?php echo $this->item->subject; ?>" size="50" /> 			
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
				</td>
			</tr>
			<tr>
				<th>					
					<h2 class="hdp_heading hdp_comments_heading"><?php echo JText::_('HDP_COMMENTS'); ?>
						<a href="javascript:showMessageBox();"><span id="hdp_add_comment_link"><?php echo JText::_('HDP_ADD_COMMENT'); ?></span></a>						
					</h2>													
				</th>
			</tr>

			<form action="index.php" method="post" name="adminFormComment" id="adminFormComment" enctype="multipart/form-data">
			<tr id="tr_message_id" style="display: none;">
				<td>				
					<?php echo $editor->display( 'comment',  '' , '100%', '250', '75', '10', false) ; ?>
					<?php 
						if ($this->config->enable_attachment) {
						?>
							<div class="clearfix"></div>									
							<table>
								<tr>
									<th><?php echo JText::_('HDP_ATTACHMENTS'); ?></th>
								</tr>					
			        			<tr>
									<td>
										<ul id="hdp_attachment_list">
											<?php
						        				$maxNumberOfFiles = $this->config->max_number_attachments ? $this->config->max_number_attachments : 1;
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
									</td>
								</tr>					        				        				
			        		</table>		
						<?php	
						}
					?>					
	        		<div class="clr"></div>
	        		<input type="button" name="btnSubmit" class="btn btn-primary" value="<?php echo JText::_('HDP_SUBMIT_COMMENT'); ?>" onclick="addComment(this.form);" />	
				</td>
			</tr>
					<input type="hidden" name="option" value="com_helpdeskpro" />
					<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
					<input type="hidden" name="task" value="" />
					<input type="hidden" name="new_value" value="0" />			
					<?php echo JHTML::_( 'form.token' ); ?>	
			</form>
			<?php			
			if (count($this->messages)) {
			?>
				<tr>
					<td>						
						<table class="admintable table table-striped table-bordered table-condensed">
							<?php
								foreach($this->messages as $message) {
								?>
									<tr>
										<th>
											<?php 
												if ($message->user_id)
													echo JText::sprintf('HDP_MESSAGE_SUBMITTED_AT', $message->name, JHtml::_('date', $message->date_added, $this->dateFormat));
												else
													echo JText::sprintf('HDP_MESSAGE_SUBMITTED_AT', $this->item->name, JHtml::_('date', $message->date_added, $this->dateFormat));
											?>											
										</th>
									</tr>
									<tr>
										<td>		
											<?php
												if ($message->attachments) {
													$className = 'hdp_ticket_message_left' ;
												} else {
													$className = 'hdp_ticket_message' ;
												}
											?>
											<div class="<?php echo $className; ?>">

												<form action="index.php" method="post" name="adminFormComment<?php echo $message->id; ?>" id="adminFormComment<?php echo $message->id; ?>" enctype="multipart/form-data">
													<?php echo $editor->display( 'comment'.$message->id,  $message->message, '100%', '250', '75', '10', false) ; ?>					
									        		<div class="clr"></div>
													<input type="hidden" name="message_id" value="<?php echo $message->id; ?>" />
									        		<input type="button" name="btnSubmit" class="btn btn-primary" value="<?php echo JText::_('HDP_SUBMIT_COMMENT'); ?>" onclick="addComment(this.form);" />	
	
													<?php // echo $message->message; ?>
	
													<input type="hidden" name="option" value="com_helpdeskpro" />
													<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
													<input type="hidden" name="task" value="" />
													<input type="hidden" name="new_value" value="0" />			
													<?php echo JHTML::_( 'form.token' ); ?>	
												</form>
											</div>																																	
											<?php
												if ($message->attachments) {														
													$originalFileNames = explode('|', $message->original_filenames);
													$attachments = explode('|', $message->attachments);;
													?>
														<div class="attachment_lists">
															<ul>
																<?php
																	$i = 0 ;
																	foreach($originalFileNames as $fileName) {
																		$actualFileName = $attachments[$i++] ;
																	?>
																		<li class="icon-download"><a href="<?php echo JRoute::_('index.php?option=com_helpdeskpro&task=ticket.download_attachment&filename='.$actualFileName.'&original_filename='.$fileName); ?>"><?php echo $fileName; ?></a></li>
																	<?php	
																	}	
																?>													
															</ul>
														</div>
													<?php	
												}
											?>											
										</td>
									</tr>
								<?php	
								}	
							?>
						</table>
					</td>
				</tr>
			<?php	
			}
		?>				
	</table>			
</div>		
<div id="hdp_right_panel" class="span3">
	<table class="admintable table table-striped table-bordered table-condensed">		
		<tr>
			<th colspan="2"><?php echo JText::_('HPD_TICKET_DETAIL'); ?></th>
		</tr>		
		<tr>
			<td>
				<?php echo JText::_('HDP_TICKET_ID'); ?>
			</td>
			<td>
				<?php echo $this->item->id; ?>
			</td>
		</tr>	
		<tr>
			<td>
				<?php echo JText::_('HDP_CATEGORY'); ?>
			</td>
			<td>
				<?php echo $this->item->category_title ; ?>
			</td>
		</tr>
		<?php
			if ($this->item->user_id > 0) {
			?>
				<tr>
				<td>
					<?php echo JText::_('HDP_USER'); ?>
				</td>
				<td>
					<?php echo $this->item->username; ?>[<?php echo $this->item->user_id; ?>]
				</td>
			</tr>
			<?php		
			}
		?>		
		<tr>
			<td>
				<?php echo JText::_('HDP_NAME'); ?>
			</td>
			<td>
				<?php echo $this->item->name; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('HDP_EMAIl'); ?>
			</td>
			<td>
				<a href="mailto:<?php echo $this->item->email; ?>"><?php echo $this->item->email; ?></a>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('HDP_TICKET_STATUS'); ?>
			</td>
			<td>
				<?php echo $this->item->status ; ?>
			</td>
		</tr>
		<?php 
			if ($this->item->rating) {
				switch ($this->item->rating) {
					case 1:
						$img = 'unsatisfactory.png';
						$title = JText::_('HDP_VERY_POOR');
						break ;
					case 2:
						$img = 'poor.png';
						$title = JText::_('HDP_FAIR');
						break ;
					case 3:
						$img = 'average.png';
						$title = JText::_('HDP_AVERAGE');
						break ;
					case 4:
						$img = 'good.png';
						$title = JText::_('HDP_GOOD');
						break ;
					case 5:
						$img = 'great.png';
						$title = JText::_('HDP_EXCELLENT');
						break ;
				}
			?>
				<tr>
					<td>
						<?php echo JText::_('HDP_RATING'); ?>
					</td>
					<td>
						<img src="<?php echo JURI::root().'media/com_helpdeskpro/feedback/'.$img ; ?>" title="<?php echo $title; ?>" />
					</td>
				</tr>
			<?php	
			}
		?>
		<tr>
			<td>
				<?php echo JText::_('HDP_TICKET_PRIORITY'); ?>
			</td>
			<td>
				<?php echo $this->item->priority ; ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('HDP_CREATED_DATE'); ?>
			</td>
			<td>
				<?php echo JHTML::_('date', $this->item->created_date, $this->dateFormat); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('HDP_MODIFIED_DATE'); ?>
			</td>
			<td>
				<?php echo JHTML::_('date', $this->item->date, $this->dateFormat); ?>
			</td>
		</tr>
		<?php
			if (count($this->fields)) {
			?>
				<tr><th colspan="2"><?php echo JText::_('HPD_EXTRA_INFORMATION'); ?></th></tr>
			<?php	
				foreach($this->fields as $field) {
				?>
					<tr>
						<td>
							<?php echo $field->title ; ?>
						</td>
						<td>
							<?php echo @$this->fieldValues[$field->id]; ?>
						</td>
					</tr>
				<?php	
				}
			}
			if (count($this->results)) {
				foreach($this->results as $result) {
					if ($result) {
						?>
							<?php echo $result ; ?>				
						<?php	
					}
				}
			} 		
		?>
	</table>
</div>
</div>
</div>