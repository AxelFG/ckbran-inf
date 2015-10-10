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


$editor_tiny = JFactory::getEditor(tinymce);

?>

<div>

<?php if ($this->canAccess && !JFactory::getUser()->guest) { ?>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

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
          
<!--
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

	   	<?php 

	   		if ($this->isCustomer && !$this->item->rating) {

	   		?>

	   			<div class="btn-group">

			    	<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('HDP_TICKET_RATING'); ?> <span class="caret"></span></button>

					<ul class="dropdown-menu">

						<li><a href="javascript:ticketRating(1)"><?php echo JText::_('HDP_VERY_POOR'); ?></a></li>

						<li><a href="javascript:ticketRating(2)"><?php echo JText::_('HDP_FAIR'); ?></a></li>

						<li><a href="javascript:ticketRating(3)"><?php echo JText::_('HDP_AVERAGE'); ?></a></li>

						<li><a href="javascript:ticketRating(4)"><?php echo JText::_('HDP_GOOD'); ?></a></li>

						<li><a href="javascript:ticketRating(5)"><?php echo JText::_('HDP_EXCELLENT'); ?></a></li>                

			    	</ul>

			   	</div>	

	   		<?php	

	   		}

	   	?>	   		  	          
-->
	</div>

</div> 

<?php } ?>

<div class="row-fluid">

	<div id="hdp_container">						
		
		<div class="qna">

					<span>						

						<?php echo JHTML::_('date', $this->item->created_date, $this->dateFormat); ?>

						<!-- , <?php echo $this->item->priority ; ?>. -->
				
						| <strong><?php echo $this->item->name; ?></strong><?php if ($this->canAccess) { ?> <a href="mailto:<?php echo $this->item->email; ?>"><?php echo $this->item->email; ?></a><?php } ?>

					</span>

					<?php if ($this->canAccess) echo "<span><br />#". $this->item->id ." <strong>".JText::_('HDP_CATEGORY')."</strong>: ". $this->item->category_title .", <strong>". JText::_('HDP_TICKET_STATUS')."</strong>: ". $this->item->status ."</span>"; ?>

					<hr />

					<h2 class="hdp_title"><?php echo $this->item->subject; ?></h2>

					<?php 

						if ($this->item->attachments) {

							$className = 'hdp_ticket_message_left' ;

						} else {

							$className = 'hdp_ticket_message' ;

						}

					?>	

					<div class="<?php echo $className; ?>">

						<?php echo $this->item->message; ?>

					</div>

					<?php

						if ($this->item->attachments) {							

							$originalFileNames = explode('|', $this->item->original_filenames);							

							$attachments = explode('|', $this->item->attachments);;

						?>

						<div class="attachment_lists">	

							<ul>

								<?php

									$i =  0 ;

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
											

			if (count($this->messages)) {

			?>
						<div class="response">

							<h2 class="hdp_heading hdp_comments_heading"><?php echo JText::_('HDP_COMMENTS'); ?></h2>

							<?php

								foreach($this->messages as $message) {

								?>
											<span>
											<?php 

												if ($message->user_id == 0) {

													echo JHtml::_('date', $message->date_added, $this->dateFormat);

												} else {

													echo JHtml::_('date', $message->date_added, $this->dateFormat)." | <strong>".$message->name."</strong>";

												}

											?>
											</span>

											<?php  ?>											


											<?php

												if ($message->attachments) {

													$className = 'hdp_ticket_message_left' ;

												} else {

													$className = 'hdp_ticket_message' ;

												}

											?>

											<div class="<?php echo $className; ?>">

												<?php echo $message->message; ?>

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
									
									<hr/>

								<?php	

								}	

							?>


						</div>

			<?php	

			}

			if ($this->canAccess && !JFactory::getUser()->guest) {

			?>			

						<?php 
							echo $editor_tiny->display( 'comment',  '' , '300', '250', '75', '10', false) ;
						?>

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

		        		<div class="clearfix"></div>

		        		<input type="button" name="btnSubmit" class="btn btn-primary" value="<?php echo JText::_('HDP_SUBMIT_COMMENT'); ?>" onclick="addComment(this.form);" />	

			<?php	

			}					


		?>

	</div>

</div>

	<input type="hidden" name="option" value="com_helpdeskpro" />

	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="task" value="" />				

	<input type="hidden" name="new_value" value="0" />

	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />

	

	<?php

		if ($this->item->is_ticket_code) {

		?>

			<input type="hidden" name="ticket_code" value="<?php echo $this->item->ticket_code ?>" />

		<?php	

		}

	?>		

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

			var form = document.adminForm ;

			form.new_value.value = newCategoryId ;

			form.task.value = 'ticket.update_category';

			form.submit();

		}	



		function changeTicketStatus(newStatus) {

			var form = document.adminForm ;

			form.new_value.value = newStatus ;

			form.task.value = 'ticket.update_status';

			form.submit();

		}

		function changeTicketPriority(newPriority) {

			var form = document.adminForm ;

			form.new_value.value = newPriority ;

			form.task.value = 'ticket.update_priority';

			form.submit();

		}	

				

		function ticketRating(rating) {

			var form = document.adminForm ;

			form.new_value.value = rating ;

			form.task.value = 'ticket.save_rating';

			form.submit();

		}			

	</script>	

</form>

	<div class="sidebar-form">
	
		<?php include JPATH_ROOT.'/components/com_helpdeskpro/views/ticket/tmpl/form.php'; ?>
	
	</div>							

</div>		


</div>

</div>

<div id="aside" style="float: left; display: block; margin: 0 !important;" class="span3">

	<div class="sidebar-nav">

		<div class="moduletable">
	
			<?php echo $this->lists['cat']; ?>

		</div>

	</div>