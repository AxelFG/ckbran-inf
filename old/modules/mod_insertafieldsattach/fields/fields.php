<?php
/*------------------------------------------------------------------------
# mod_insertfieldsattach
# ------------------------------------------------------------------------
# author    Cristian Grañó (percha.com)
# copyright Copyright (C) 2010 percha.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.percha.com
# Technical Support:  Forum - http://www.percha.com/
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.html.html');
jimport('joomla.form.formfield');//import the necessary class definition for formfield


/**
 * Supports an HTML select list of articles
 * @since  1.6
 */
class JFormFieldFields extends JFormField
{
	 /**
      * The form field type.
      *
      * @var  string
      * @since	1.6
      */
      protected $type = 'Fields'; //the form field type

            /**
      * Method to get content articles
      *
      * @return	array	The field option objects.
      * @since	1.6
      */
	protected function getInput()
	{
          // Initialize variables.
          $session = JFactory::getSession();
          $options = array();

          $attr = '';

          // Initialize some field attributes.
          $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';

          // To avoid user's confusion, readonly="true" should imply disabled="true".
          if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
           $attr .= ' disabled="disabled"';
          }

          $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
          $attr .= $this->multiple ? ' multiple="multiple"' : '';

          // Initialize JavaScript field attributes.
          $attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';


          //now get to the business of finding the articles

          $db = &JFactory::getDBO();
          $query = 'SELECT * FROM #__fieldsattach_groups WHERE published=1 ORDER BY title';
          $db->setQuery( $query );
          $groups = $db->loadObjectList();

          $fields=array();

          // set up first element of the array as all articles
          $fields[0]->id = '';
          $fields[0]->title = JText::_("ALLARTICLES");
          
            /*if((int)$this->value>0)
            {
                    $query = 'SELECT title FROM #__content WHERE id='.$this->value;
                     $db->setQuery( $query );
            }*/
  
            //loop through categories
            foreach ($groups as $group) {
                 $optgroup = JHTML::_('select.optgroup',$group->title,'id','title');
                 $query = 'SELECT id,title FROM #__fieldsattach WHERE groupid='.$group->id;
                 $db->setQuery( $query );
                 $results = $db->loadObjectList();
                 if(count($results)>0)
                 {
                        array_push($fields,$optgroup);
                        foreach ($results as $result) {
                             array_push($fields,$result);
                        }
                }
            }

            
 
            if ($this->value) {
                    $query = 'SELECT id,title FROM #__fieldsattach WHERE id='.$this->value;
                    $db->setQuery( $query );
                    $field = $db->loadObject();
            }else {
                    $field->title = JText::_('COM_CONTENT_SELECT_AN_ARTICLE');
            } 
          $link	= 'index.php?option=com_fieldsattach&amp;view=fieldsattachunidades&amp;layout=modal&amp;tmpl=component&amp;function=jSelectFields_'.$this->id;
          // Output
          $js = "
            function jSelectFields(id, title, object) {
                    document.getElementById(object + '_id').value = id;
                    document.getElementById(object + '_name').value = title;
                    document.getElementById('sbox-window').close();
            }";

            // Build the script.
            $script = array();
            $script[] = '	function jSelectFields_'.$this->id.'(id, title, catid, object) {';
            $script[] = '		document.id("'.$this->id.'_id").value = id;';
            $script[] = '		document.id("'.$this->id.'_name").value = title;';
            $script[] = '		SqueezeBox.close();';
            $script[] = '	}';

	  // Add the script to the document head.
	  JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
          //$fieldName	= $control_name.'['.$name.']';
          JHTML::_('behavior.modal', 'a.modal');
          $html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$this->id.'_name" value="'.htmlspecialchars($field->title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
          //		$html .= "\n &nbsp; <input class=\"inputbox modal-button\" type=\"button\" value=\"".JText::_('Select')."\" />";
          $html .= '<div class="button2-left"><div class="blank btn"><a class="modal" title="'.JText::_('Select an Article').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('Select').'</a></div></div>'."\n";
          $html .= "\n".'<input type="hidden" id="'.$this->id.'_id" name="'.$this->name.'" value="'.(int)$this->value.'" />'   ;


        return $html;
        //return JHTML::_('select.genericlist',  $articles, $this->name, trim($attr), 'id', 'title', $this->value );
  
  }
} 