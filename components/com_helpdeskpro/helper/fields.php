<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	Helpdesk Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
 
defined('_JEXEC') or die('');

define('NUMBER_OPTION_PER_LINE', 3) ;
define('FIELD_TYPE_TEXTBOX', 0);
define('FIELD_TYPE_TEXTAREA', 1);
define('FIELD_TYPE_DROPDOWN', 2) ;
define('FIELD_TYPE_CHECKBOXLIST', 3) ;
define('FIELD_TYPE_RADIOLIST', 4) ;
define('FIELD_TYPE_DATETIME', 5) ;
define('FIELD_TYPE_HEADING', 6) ;
define('FIELD_TYPE_MESSAGE', 7) ;
define('FIELD_TYPE_MULTISELECT', 8) ;
class JCFields {
	/**
	 * List of custom fields used in the system
	 *
	 * @var array
	 */
	var $_fields = null ;
	/**
	 * Constructor function
	 *
	 * @return JCFields
	 */
	function JCFields() {		
		$db = JFactory::getDbo() ;
		$sql = 'SELECT * FROM #__helpdeskpro_fields WHERE published=1 ORDER BY ordering';
		$db->setQuery($sql);
		$this->_fields = $db->loadObjectList();			
	}	
	/**
	 * Get total custom fields
	 *
	 * @return int
	 */	
	function getTotal() {
		return count($this->_fields);		
	}
	/**
	 * Get array of item in assoc list
	 *
	 * @return array
	 */
	function getAssoc() {			   
		$db = JFactory::getDbo() ;
	    //Get fields which belong to all categories	    
	    $sql = 'SELECT id FROM #__helpdeskpro_fields WHERE category_id = -1';
	    $db->setQuery($sql) ;
	    $rows = $db->loadObjectList() ;
	    for ($i = 0 , $n = count($rows) ; $i < $n ; $i++) {
	        $row = $rows[$i] ;
	        $array[0][] = $row->id ;
	    } 
	    //Fields which belong to specific category
	    $sql = 'SELECT * FROM #__helpdeskpro_field_categories' ;
	    $db->setQuery($sql) ;
	    $rows = $db->loadObjectList() ;
	    for ($i = 0 , $n = count($rows) ; $i < $n ; $i++) {
	        $row = $rows[$i] ;
	        $array[$row->category_id][] = $row->field_id ;    
	    }
	    	    
	    return $array ;
	}
	/**
	 * Render a textbox
	 *
	 * @param object $row
	 */
	function _renderTextBox($row, $style) {
		$postedValue = JRequest::getVar($row->name,  $row->default_values) ;		
	?>	
		<div class="control-group" id="field_<?php echo $row->id;?>"<?php echo $style; ?>>
			<label class="control-label" for="<?php echo $row->name ; ?>"><?php echo $row->title ; ?></label>
			<div class="controls"><input type="text" id="<?php echo $row->name ; ?>" name="<?php echo $row->name ; ?>" class="<?php echo $row->css_class; ?>" size="<?php echo $row->size ; ?>" value="<?php echo $postedValue ; ?>" /></div>
		</div>		
	<?php
	}		
	/**
	 * Gender validation for textbox 
	 *
	 * @param object $row
	 */
	function _renderTextBoxValidation($row) {
	?>
		var trId = "field_<?php echo $row->id; ?>" ;
		var tr = document.getElementById(trId);
		if (tr.style.display != 'none') {
			if (form.<?php echo $row->name; ?>.value == "") {
				alert("<?php echo $row->title ;?> <?php echo JText::_('HDP_IS_REQUIRED') ; ?>");
				form.<?php echo $row->name; ?>.focus();
				return ;
			}
		}		
	<?php		
	}		
	/**
	 * Render textarea object
	 *
	 * @param object $row
	 */
	function _renderTextarea($row, $style) {
		$postedValue = JRequest::getVar($row->name,  $row->default_values) ;		
	?>
		<div class="control-group" id="field_<?php echo $row->id;?>"<?php echo $style; ?>>
			<label class="control-label" for="<?php echo $row->name ; ?>">
				<?php 
					echo $row->title ; 
					if ($row->required)
						echo '<span class="required">*</span>';
				?>				
			</label>
			<div class="controls"><textarea id="<?php echo $row->name ;?>" name="<?php echo $row->name ; ?>" rows="<?php echo $row->rows; ?>" cols="<?php echo $row->cols ; ?>" class="<?php echo $row->css_class; ?>"><?php echo $postedValue; ?></textarea></div>
		</div>			
	<?php	
	}	
	/**
	 * Gender validation for textarea 
	 *
	 * @param object $row
	 */
	function _renderTextAreaValidation($row) {
	?>
		var trId = "field_<?php echo $row->id; ?>" ;
		var tr = document.getElementById(trId);
		if(tr.style.display != 'none') {
			if (form.<?php echo $row->name; ?>.value == "") {
				alert("<?php echo $row->title ;?> <?php echo JText::_('HDP_IS_REQUIRED') ; ?>");
				form.<?php echo $row->name; ?>.focus();
				return ;
			}
		}		
	<?php		
	}	
	/**
	 * Render dropdown field type
	 *
	 * @param object $row
	 */
	function _renderDropdown($row, $style) {
		$postedValue = JRequest::getVar($row->name, $row->default_values, 'post') ;				
		$options = array() ;
		$options[] = JHTML::_('select.option', '', JText::_('HDP_SELECT'));		
		$values = explode("\r\n", $row->values) ;
		for ($i = 0 , $n = count($values) ; $i < $n ; $i++) {
			$options[] = JHTML::_('select.option', $values[$i], $values[$i]) ;
		}
	?>
		<div class="control-group" id="field_<?php echo $row->id;?>"<?php echo $style; ?>>
			<label class="control-label" for="<?php echo $row->name ; ?>">
				<?php 
					echo $row->title ; 
					if ($row->required)
						echo '<span class="required">*</span>';
				?>				
			</label>
			<div class="controls"><?php echo JHTML::_('select.genericlist', $options, $row->name, '', 'value', 'text', $postedValue); ?></div>
		</div>					
	<?php									
	}		
	/**
	 * Gender validation for dropdown 
	 *
	 * @param object $row
	 */
	function _renderDropdownValidation($row) {
	?>
		var trId = "field_<?php echo $row->id; ?>" ;
		var tr = document.getElementById(trId);
		if(tr.style.display != 'none') {
			if (form.<?php echo $row->name; ?>.selectedIndex == 0) {
				alert("<?php echo $row->title ;?> <?php echo JText::_('HDP_IS_REQUIRED') ; ?>");
				form.<?php echo $row->name; ?>.focus();
				return ;
			}
		}		
	<?php		
	}			
	/**
	 * Render dropdown field type
	 *
	 * @param object $row
	 */
	function _renderMultiSelect($row, $style) {		
		if (isset($_POST[$row->name])) {
			$selectedValues = $_POST[$row->name] ;	
		} else {
			$selectedValues = explode("\r\n", $row->default_values) ;
		}			
		$options = array() ;
		//$options[] = JHTML::_('select.option', '', JText::_('JD_SELECT'));		
		$values = explode("\r\n", $row->values) ;
		for ($i = 0 , $n = count($values) ; $i < $n ; $i++) {
			$options[] = JHTML::_('select.option', $values[$i], $values[$i]) ;
		}		
		$selectedOptions = array() ;
		for ($i = 0 , $n = count($selectedValues); $i < $n; $i++) {
			$selectedOptions[] = JHTML::_('select.option', $selectedValues[$i], $selectedValues[$i]) ;
		}	 
	?>
		<div class="control-group" id="field_<?php echo $row->id;?>"<?php echo $style; ?>>
			<label class="control-label" for="<?php echo $row->name ; ?>">
				<?php 
					echo $row->title ; 
					if ($row->required)
						echo '<span class="required">*</span>';
				?>				
			</label>
			<div class="controls"><?php echo JHTML::_('select.genericlist', $options, $row->name.'[]', ' multiple="multiple" size="4" ', 'value', 'text', $selectedValues);?></div>
		</div>			
	<?php									
	}		
	/**
	 * Gender validation for dropdown 
	 *
	 * @param object $row
	 */
	function _renderMultiSelectValidation($row) {
	?>
		var trId = "field_<?php echo $row->id; ?>" ;
		var tr = document.getElementById(trId);
		if(tr.style.display != 'none') {
			var selectTag =  document.getElementById('<?php echo $row->name; ?>');
			if (selectTag.selectedIndex == -1) {
				alert("<?php echo $row->title ;?> <?php echo JText::_('HDP_IS_REQUIRED') ; ?>");
				form.<?php echo $row->name; ?>.focus();
				return ;
			}
		}			
	<?php		
	}		
	/**
	 * Render checkbox list
	 *
	 * @param object $row
	 */
	function _renderCheckboxList($row, $style) {		
		$values = explode("\r\n", $row->values);
		if (isset($_POST[$row->name])) {
			$defaultValues = $_POST[$row->name] ;	
		} else {
			$defaultValues = explode("\r\n", $row->default_values) ;
		}
		?>
		<div class="control-group" id="field_<?php echo $row->id;?>"<?php echo $style; ?>>
			<label class="control-label" for="<?php echo $row->name ; ?>">
				<?php 
					echo $row->title ; 
					if ($row->required)
						echo '<span class="required">*</span>';
				?>				
			</label>
			<div class="controls">
				<table cellspacing="3" cellpadding="3" width="100%">
					<?php
					    $optionPerRow = $row->option_per_rows > 0 ? $row->option_per_rows : NUMBER_OPTION_PER_LINE ;
						for ($i = 0 , $n = count($values) ; $i < $n ; $i++) {
							$value = $values[$i] ;
							if ($i % $optionPerRow == 0) {
							?>
								<tr>
							<?php	
							}					
							?>
								<td>
									<input class="inputbox" value="<?php echo $value; ?>" type="checkbox" name="<?php echo $row->name; ?>[]" <?php if (in_array($value, $defaultValues)) echo ' checked="checked" ' ; ?>><?php echo $value;?>
								</td>	
							<?php	
							if (($i+1) % $optionPerRow == 0) {
							?>
								</tr>
							<?php	
							}					
						}
						if ($i % $optionPerRow != 0) {
							$colspan = $optionPerRow - $i % $optionPerRow ;
						?>
								<td colspan="<?php echo $colspan; ?>">&nbsp;</td>
							</tr>
						<?php	
						}				
					?>
				</table>											
			</div>
		</div>				
	<?php			
	}		
	/**
	 * Gender validation for textbox 
	 *
	 * @param object $row
	 */
	function _renderCheckBoxListValidation($row) {				
	?>
		var trId = "field_<?php echo $row->id; ?>" ;
		var tr = document.getElementById(trId);
		if(tr.style.display != 'none') {
			var checked = false ;		
			if (form["<?php echo $row->name; ?>[]"].length) {
				for (var i=0; i < form["<?php echo $row->name; ?>[]"].length; i++) {
					if (form["<?php echo $row->name; ?>[]"][i].checked == true) {
						checked = true ;
						break ;
					}
				}
			} else {
				if (form["<?php echo $row->name; ?>[]"].checked) {
					checked = true ;
				}
			}
			if (!checked) {
				alert("<?php echo $row->title ;?> <?php echo JText::_('OST_IS_REQUIRED') ; ?>");
				form.<?php echo $row->name; ?>.focus();
				return ;
			}
		}		
	<?php		
	}		
	/**
	 * Reder radio list
	 *
	 * @param object $row
	 */
	function _renderRadioList($row, $style) {		
		$postedValue = JRequest::getVar($row->name, $row->default_values, 'post') ;
		$values = explode("\r\n",  $row->values);
		$options = array();
		for ($i = 0 , $n = count($values) ; $i < $n; $i++) {
			$options[] = JHTML::_('select.option', $values[$i] , $values[$i]) ;			
		}
		?>
			<div class="control-group" id="field_<?php echo $row->id;?>"<?php echo $style; ?>>
				<label class="control-label" for="<?php echo $row->name ; ?>">
					<?php 
						echo $row->title ; 
						if ($row->required)
							echo '<span class="required">*</span>';
					?>				
				</label>
				<div class="controls"><?php echo JHTML::_('select.radiolist', $options, $row->name , '', 'value', 'text', $postedValue); ?></div>
			</div>					
		<?php
	}		
	/**
	 * Gender validation for RadioList 
	 *
	 * @param object $row
	 */
	function _renderRadioListValidation($row) {
	?>
		var trId = "field_<?php echo $row->id; ?>" ;
		var tr = document.getElementById(trId);
		if(tr.style.display != 'none') {
			var checked = false ;
			if (form.<?php echo $row->name; ?>.length) {
				for (var i=0 ; i < form.<?php echo $row->name; ?>.length ; i++) {
					if (form.<?php echo $row->name; ?>[i].checked == true) {
						checked = true ;
						break ;
					}
				}
			} else {
				if (form.<?php echo $row->name; ?>.checked == true)
					checked = true ;
			}
			if (!checked) {
				alert("<?php echo $row->title . ' '.JText::_('HDP_IS_REQUIRED');?>");
				return ;
			}
		}		
	<?php		
	}			
	/**
	 * 
	 *
	 * @param string $row
	 */
	function _renderDateTime($row, $style) {				
		?>
			<div class="control-group" id="field_<?php echo $row->id;?>"<?php echo $style; ?>>
				<label class="control-label" for="<?php echo $row->name ; ?>">
					<?php 
						echo $row->title ; 
						if ($row->required)
							echo '<span class="required">*</span>';
					?>				
				</label>
				<div class="controls"><?php echo JHTML::_('calendar', $row->default_values, $row->name, $row->name) ; ?>	</div>
			</div>				
		<?php
	}	
	/**
	 * Gender validation for RadioList 
	 *
	 * @param object $row
	 */
	function _renderDateTimeValidation($row) {
	?>
		var trId = "field_<?php echo $row->id; ?>" ;
		var tr = document.getElementById(trId);
		if (tr.style.display != 'none') {
			if (form.<?php echo $row->name;?>.value == "") {
				alert("<?php echo $row->title.' '.JText::_('HDP_IS_REQUIRED'); ?>");
				form.<?php echo $row->name; ?>.focus();
				return ;	
			}
		}		
	<?php		
	}					
	/**
	 * Render published custom fields
	 *
	 */
	function renderCustomFields($categoryId, $fieldArray = array()) {	    	   		
		ob_start();								
		for ($i = 0 , $n = count($this->_fields) ; $i < $n ; $i++) {
			$row = $this->_fields[$i];										
			if (($row->category_id != -1) && !in_array($row->id, $fieldArray[$categoryId])) {			    			      
				$style = ' style = "display:none;" ';
			} else {
				$style = '';
			}			
			switch ($row->field_type) {
				case FIELD_TYPE_HEADING :
					?>
						<div class="control-group" id="field_<?php echo $row->id;?>"<?php echo $style; ?>>
							<label class="control-label">
								<?php 
									echo $row->title ; 									
								?>				
							</label>							
						</div>							
					<?php	
					break ;
				case FIELD_TYPE_MESSAGE :
					?>	
						<div class="control-group" id="field_<?php echo $row->id;?>"<?php echo $style; ?>>							
							<div class="controls"><?php echo $row->description; ?></div>
						</div>												
					<?php 	
					break ;
				 case FIELD_TYPE_TEXTBOX :						 							    	
					$this->_renderTextBox($row, $style);
					break ;
				 case FIELD_TYPE_TEXTAREA :								
					$this->_renderTextarea($row, $style);
					break ;
				 case FIELD_TYPE_DROPDOWN :									    	
					$this->_renderDropdown($row, $style);				
					break ;
				 case FIELD_TYPE_CHECKBOXLIST :							
					$this->_renderCheckboxList($row, $style);
					break ;
				 case FIELD_TYPE_RADIOLIST :							
					$this->_renderRadioList($row, $style);
					break ;
				 case FIELD_TYPE_DATETIME :							
					$this->_renderDateTime($row, $style);
					break ;	
				 case FIELD_TYPE_MULTISELECT :
				 	$this->_renderMultiSelect($row, $style);
				 	break ;		
			}
			?>				
		<?php														
		}
		$output = ob_get_contents() ;				
		ob_end_clean();
		return $output ;
	}			
	/**
	 * Render js validation code
	 *
	 */
	function renderJSValidation() {
		ob_start();		
		for($i=0, $n = count($this->_fields) ; $i < $n ; $i++) {
			$row = $this->_fields[$i] ;
			if ($row->required) {
				switch ($row->field_type) {
					case FIELD_TYPE_TEXTBOX :				
						$this->_renderTextBoxValidation($row);
						break ;
					case FIELD_TYPE_TEXTAREA :						
						$this->_renderTextAreaValidation($row);
						break ;
					case FIELD_TYPE_DROPDOWN :					
						$this->_renderDropdownValidation($row);				
						break ;
					case FIELD_TYPE_CHECKBOXLIST :					
						$this->_renderCheckBoxListValidation($row);
						break ;
					case FIELD_TYPE_RADIOLIST :				
						$this->_renderRadioListValidation($row);
						break ;
					case FIELD_TYPE_DATETIME :					
						$this->_renderDateTimeValidation($row);
						break ;	
					case FIELD_TYPE_MULTISELECT :
						$this->_renderMultiSelectValidation($row);
						break ;			
				}
			}				 	
		}
		$output = ob_get_contents();
		ob_end_clean();
		return $output ;
	}		
	/**
	 * Save Field Value 
	 *
	 * @param int $id
	 * @return boolean
	 */
	function saveFieldValues($ticket) {		
		$db = JFactory::getDbo() ;
		$rowFieldValue = JTable::getInstance('helpdeskpro', 'fieldvalue');
		$sql  = 'SELECT * FROM #__helpdeskpro_fields WHERE category_id = -1 OR id IN (SELECT field_id FROM #__helpdeskpro_field_categories WHERE category_id='.$ticket->category_id.')';
		$db->setQuery($sql) ;
		$rows = $db->loadObjectList() ;		
		for ($i = 0, $n = count($rows) ; $i < $n ; $i++) {
			$row = $rows[$i];
			if ($row->field_type == FIELD_TYPE_HEADING || $row->field_type == FIELD_TYPE_MESSAGE)
				continue ;
			$name = $row->name ;			
			$rowFieldValue->id = 0 ;
			$rowFieldValue->ticket_id = $ticket->id ;
			$rowFieldValue->field_id = $row->id ;
			$postedValue = JRequest::getVar($name, '', 'post');						
			if (is_array($postedValue))
				$postedValue = implode(',', $postedValue);
			$rowFieldValue->field_value = $postedValue ;
			$rowFieldValue->store();	
		}
				
		return true ;
	}			
}
?>