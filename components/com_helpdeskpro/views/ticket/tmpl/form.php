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

<div class="moduletable">

<h3 class="hdp_title title"><?php echo JText::_('HDP_NEW_TICKET');?></h3>

<form class="form form-horizontal" name="hdp_form" id="hdp_form" action="index.php" method="post" enctype="multipart/form-data" onsubmit="return checkForm(this);">

<?php 

	if (!$this->userId) {

	?>

		<div>

			<label for="name"><?php echo JText::_('HDP_NAME'); ?><span class="required">*</span></label>

			<div>

      			<input type="text" id="name" name="name" />      			

    		</div>			

		</div>

		<div>

			<label for="email"><?php echo JText::_('HDP_EMAIL'); ?><span class="required">*</span></label>

			<div>

      			<input type="text" id="email" name="email" />      			

    		</div>			

		</div>

	<?php	

	}

?>

	<div>

			<label for="category_id"><?php echo JText::_('HDP_CATEGORY'); ?><span class="required">*</span></label>

			<div>

      			   <?php echo $this->lists['category_id'] ; ?> 			

    		</div>			

	</div>

	<div>

			<label for="subject"><?php echo JText::_('HDP_SUBJECT'); ?><span class="required">*</span></label>

			<div>

      			   <input type="text" id="subject" name="subject" class="text_area" value="" size="50" /> 			

    		</div>			

	</div>
<!--
	<div>

			<label for="priority_id"><?php echo JText::_('HDP_PRIORITY'); ?><span class="required">*</span></label>

			<div>

      			   <?php echo $this->lists['priority_id'] ; ?> 			

    		</div>			

	</div>
-->
					<input type="hidden" name="priority_id" value="3" />
	<?php

		if ($this->customField) {

			echo $this->fields ;

		}

	?>

	<div>

			<label for="message"><?php echo JText::_('HDP_MESSAGE'); ?><span class="required">*</span></label>

			<div>

				  <textarea aria-hidden="false" name="message" id="message" cols="75" rows="10" style="width: 200px; height: 120px;"></textarea>

    		</div>	

	</div>	
<?php

print '<script type="text/javascript"> function checkForm(form) { if(!form.captcha.value.match(/^\d{5}$/)) { alert("Please enter the CAPTCHA digits in the box provided"); form.captcha.focus(); return false; }  return true; } </script>';


print '<div><label for="captcha">' . JText::_('HDP_CAPTCHA_TEXT') . '</li><div><input class="inputbox ' . $mod_class_suffix . '" type="text" style="width:60px" maxlength="3" name="captcha" value="" />&nbsp;<img src="/libraries/captcha.php" width="120" height="30" border="1" alt="CAPTCHA" style="margin-bottom: 6px;" /></div></div>';					
?>
	<?php 

		if ($this->config->enable_attachment) {

		?>

			<div>

				<label><?php echo JText::_('HDP_ATTACHMENTS'); ?></label>

				<div>

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

	<div class="moduletable">

		<input type="button" name="btnSubmit" class="btn btn-primary" value="<?php echo JText::_('HDP_SUBMIT_TICKET'); ?>" onclick="submitTicket(this.form);" />

		<input type="button" name="btnSubmit" class="btn btn-primary" value="<?php echo JText::_('HDP_CANCEL'); ?>" onclick="this.form.reset()" />

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

		if (form.name.value == "") {

			alert("<?php echo JText::_("HDP_ENTER_NAME"); ?>") ;

			form.name.focus();

			return ;

		}	
	

		if (form.email.value == "") {

			alert("<?php echo JText::_("HDP_ENTER_EMAIL"); ?>") ;

			form.email.focus();

			return ;

		}	
	

		if (form.subject.value == "") {

			alert("<?php echo JText::_("HDP_ENTER_SUBJECT"); ?>") ;

			form.subject.focus();

			return ;

		}	

		if (form.message.value == "") {

			alert("<?php echo JText::_("HDP_ENTER_MESSAGE"); ?>") ;

			form.message.focus();

			return ;

		}

		if (form.captcha.value == "") {

			alert("<?php echo JText::_("HDP_ENTER_CAPTCHA"); ?>") ;

			form.capthca.focus();

			return ;

		}
		<?php

            if ($this->customField) {

                echo $this->validations ;    

            }            

            

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

					jQuery(trId).show('');

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

						jQuery(trId).hide('');							

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

		<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />

		<input type="hidden" name="referer" value="<?php echo base64_encode(@$_SERVER['HTTP_REFERER']); ?>" /> 

			

		<?php echo JHTML::_( 'form.token' ); ?>	

</form>	

</div>