<?php
/**
 * @version		$Id: fieldsattachement.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @articlerelated		http://joomlacode.org/gf/project/fieldsattach_1_6/
 *  		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

// require helper file

global $sitepath;
$sitepath = JPATH_BASE ;
$sitepath = str_replace ("administrator", "", $sitepath);  
JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
JLoader::register('fieldsattachHelper',   $sitepath.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');
 
class plgfieldsattachment_articlerelated extends JPlugin
{
        protected $name;
        /**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
        function plgfieldsattachment_articlerelated(& $subject, $config)
	{
		parent::__construct($subject, $config);
                 
        }
	function construct( )
	{
            $name = "articlerelated";
            if(isset($this)) $this->name = $name;
             
            
            $lang   =&JFactory::getLanguage();
            $lang->load( 'plg_fieldsattachment_articlerelated' );
           
            
             
	}
	  
        function getName()
        {  
                return  $this->name ;
        }

        function renderHelpConfig(  )
        { 
            $return = "" ;
            $form = $this->form->getFieldset("percha_articlerelated");
            $return .= JHtml::_('sliders.panel', JText::_( "JGLOBAL_FIELDSET_articlerelated_OPTIONS"), "percha_".$this->params->get( 'name', "" ).'-params');
            $return .=   '<fieldset class="panelform" >
			<ul class="adminformlist" style="overflow:hidden;">';
           // foreach ($this->param as $name => $fieldset){
            foreach ($form as $field) {
                $return .=   "<li>".$field->label ." ". $field->input."</li>";
            }
             $return .='</ul> ';
            if(count($form)>0){
            $return .=  '<div><input type="button" value="'.JText::_("Update Config").'" onclick="controler_percha_articlerelated()" /></div>';
            }
            $return .=  ' </fieldset>';
            //$return .=  '<script src="'. JURI::base().'../plugins/fieldsattachment/articlerelated/controler.js" type="text/javascript"></script> ';
            
            
            return  $return;
        }



        function renderInput($articleid, $fieldsid, $value, $extras = null)
        {  
            $db = &JFactory::getDBO(  );
            $required="";
            $size=""; 
            
            global $sitepath; 
            JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
           
            $boolrequired = fieldattach::isRequired($fieldsid);
            if($boolrequired) $required="required";
            
            
            
            //Extra config
            if(!empty($extras))
            {
                //$lineas = explode('":"',  $field->params);
                //$tmp = substr($lineas[1], 0, strlen($lineas[1])-2);
                $tmp = $extras;
                $lineas = explode(chr(13),  $tmp); 
               
                foreach ($lineas as $linea)
                {
                    $tmp = explode('|',  $linea);
                    if(!empty( $tmp[0])) $size = $tmp[0];
                    
                     
                    
                }
            }
            
            
              //Add CSS ***********************
            $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/articlerelated/articlerelated.css" type="text/css" />'; 
            $app = JFactory::getApplication();
            $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
            $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/articlerelated.css";
            $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/articlerelated.css"; 
            if(file_exists($css)){ $str .=  '<articlerelated rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 

            $list_articlerelated = explode(',',  $value);

            $str .=  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/articlerelated/js/Autocompleter.css" type="text/css" />'; 
            $str .=  '<script src="'.JURI::root() .'plugins/fieldsattachment/articlerelated/js/editarticlerelated.js.php" type="text/javascript"></script>';
           
            
           $str .= ' <script type="text/javascript">';
           $str .= 'var objarticlerelated_'.$fieldsid.'; ';
           $str .= ' window.addEvent("domready", function() { ';
           $str .= 'objarticlerelated_'.$fieldsid.' = new Objeditarticlerelated();'; 
           $str .= 'objarticlerelated_'.$fieldsid.'.init('.$fieldsid.' );';
           
           //ADD LI
           if ($value != '')
           $query = 'SELECT DISTINCT a.title, a.id  FROM #__content as a WHERE  a.id IN ('.$value.')';
			else
           $query = 'SELECT DISTINCT a.title, a.id  FROM #__content as a WHERE  a.id IN (0)';
             
             
            $db->setQuery( $query );
	    $qresult = $db->loadObjectList(); 
            $result = array();
            $strtmp = '';
            if(count($qresult)>0){
                foreach ($qresult as $row)
                {
                    $strtmp .= 'objarticlerelated_'.$fieldsid.'.addId('.$row->id.', "'.$row->title.'" );';;
                }
            }
             
           $str .=  $strtmp;
           
           $str .= '});';
            
           $str .=' 
            function jform_selectarticle_'.$fieldsid.'(id, title, catid, object) {
                objarticlerelated_'.$fieldsid.'.addId(id, title);
		SqueezeBox.close();
                }';
           
           

            $str .= "</script>";
           
			if($fieldsid == 15 || $fieldsid == 18)
				$filter_category_id = 12;
			elseif($fieldsid == 16 || $fieldsid == 19)
				$filter_category_id = 91;
			elseif($fieldsid == 17 || $fieldsid == 20)
				$filter_category_id = 97;
			else
				$filter_category_id = '';
 
            $str .='<div style="overflow:hidden;"><div class="button2-left"><div class="blank">
                <a class="modal btn" title="COM_FIELDSATTACH_SELECT_ARTICLE" href="index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=jform_selectarticle_'.$fieldsid.'&amp;filter_category_id='.$filter_category_id.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-save-new ">
</i> Добавить</a>
            </div> </div></div>';
           
            $str .= '<input  name="field_'.$fieldsid.'" id="field_'.$fieldsid.'" size="'.$size.'" type="hidden" value="'.$value.'" class="'.$required.'" />';
            $str .= '<ul id="field_'.$fieldsid.'_text"> </ul> ';
            
            
            return $str ;
        }
 
        function getoptionConfig($valor)
        {
             $name = $this->name;
             $return ='<option value="articlerelated" ';
             if("articlerelated" == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        }

        function getHTML($articleid, $fieldid, $category = true, $write=false)
        {
             
             
              global $globalreturn;

			    $db = &JFactory::getDBO(  );
	
	            $query = 'SELECT positionarticle FROM #__fieldsattach WHERE id = '.$fieldid;
	
	            $db->setQuery( $query );
	
			    $category = $db->loadResult();

              $html ='';
              $valorhtml="";
              $valor = fieldattach::getValue( $articleid,  $fieldid, $category  );
              $title = fieldattach::getName( $articleid,  $fieldid, $category  );
              $published = plgfieldsattachment_articlerelated::getPublished( $fieldid  );
              
              /*
                Templating IMAGEGLLERY *****************************
               
                [URL] - Link to article
                [TITLE] - title 
                [TEXT] - text 
                [ARTICLE_ID] - id article 
                [FIELD_ID] - Fieldsid
               
              */  
              if(!empty($valor) && $published)
              { 
                  $listids = explode(",", $valor);
                  $html = plgfieldsattachment_articlerelated::getTemplate($fieldid);
                  $line = plgfieldsattachment_articlerelated::getLineTemplate($fieldid);
                  $lines ="";
                  foreach($listids as $id)
                  {
                      $obj = plgfieldsattachment_articlerelated::getArticle($id);
                      $title_article = $obj->title;
                      $text = $obj->introtext;
                      $slug= $obj->id.":".$obj->alias;
                      $catid = $obj->catid; 
                      $url ="";
                      $url = JRoute::_(ContentHelperRoute::getArticleRoute( $slug, $catid));

                    
                      $tmp = $line; 
                      $tmp = str_replace("[URL]", $url, $tmp);
                      $tmp = str_replace("[TITLE]", $title_article, $tmp);
                      $tmp = str_replace("[TEXT]", $text, $tmp);
                      $tmp = str_replace("[ARTICLE_ID]", $articleid, $tmp);
                      $tmp = str_replace("[FIELD_ID]", $fieldid, $tmp); 
                      
                      $lines.= $tmp;
                  }
                  
                  if(fieldattach::getShowTitle(   $fieldid  )) $html = str_replace("[TITLE]", $title, $html); 
                  else $html = str_replace("[TITLE]", "", $html);
                  
                  $html = str_replace("[ARTICLE_ID]", $articleid, $html);
                   
                  $html = str_replace("[FIELD_ID]", $fieldid, $html);
                  $html = str_replace("[LINES]", $lines, $html);
                 
              }
           
            //WRITE THE RESULT
           if($write)
           {
                echo $html;
           }else{
                 $globalreturn=$html;
                return $html; 
           }
        }
        
        
        
        /**
	 * getTemplate
	 *
	 * @access	public 
	 * @return  	html of field
	 * @since	1.0
	 */
        function getTemplate($fieldsids)
        {
             //Search field template GENERIC *****************************************************************
              $templateDir =  dirname(__FILE__).'/tmpl/articlerelated.tpl.php'; 
              $html = file_get_contents ($templateDir);
              
              //Search field template in joomla Template  ******************************************************  
              $app = JFactory::getApplication();
              $templateDir =  JPATH_BASE . '/templates/' . $app->getTemplate().'/html/com_fieldsattach/fields/articlerelated.tpl.php';
              
              if(file_exists($templateDir))
              {
                   
                  $html = file_get_contents ($templateDir);
              }
              
              //Search a specific field template in joomla Template  *********************************************  
              $app = JFactory::getApplication();
              $templateDir =  JPATH_BASE . '/templates/' . $app->getTemplate().'/html/com_fieldsattach/fields/'.$fieldsids.'_articlerelated.tpl.php';
              
              if(file_exists($templateDir))
              { 
                  $html = file_get_contents ($templateDir);
              }
              
              return $html;
        }
        
        /**
	 * getTemplate
	 *
	 * @access	public 
	 * @return  	html of field
	 * @since	1.0
	 */
        function getLineTemplate($fieldsids)
        {
             //Search field template GENERIC *****************************************************************
              $templateDir =  dirname(__FILE__).'/tmpl/articlerelated_line.tpl.php'; 
              $html = file_get_contents ($templateDir);
              
              //Search field template in joomla Template  ******************************************************  
              $app = JFactory::getApplication();
              $templateDir =  JPATH_BASE . '/templates/' . $app->getTemplate().'/html/com_fieldsattach/fields/articlerelated_line.tpl.php';
              
              if(file_exists($templateDir))
              {
                   
                  $html = file_get_contents ($templateDir);
              }
              
              //Search a specific field template in joomla Template  *********************************************  
              $app = JFactory::getApplication();
              $templateDir =  JPATH_BASE . '/templates/' . $app->getTemplate().'/html/com_fieldsattach/fields/'.$fieldsids.'_articlerelated_line.tpl.php';
              
              if(file_exists($templateDir))
              { 
                  $html = file_get_contents ($templateDir);
              }
              
              return $html;
        }
         

        /**
	 * getPublish
	 *
	 * @access	public
	 * @param	fieldsids	Id of fields
	 * @return  	published       published or not
	 * @since	1.0
	 */
        
        function getPublished( $fieldsids  )
        { 
             
            
	    $db = &JFactory::getDBO(  );

	    $query = 'SELECT  a.published  FROM #__fieldsattach  as a WHERE a.id = '.$fieldsids;
            $return="true|true";
            
            $db->setQuery( $query );
	    $published = $db->loadResult();  
            
            return $published;
        }
        
        /**
	 * getPublish
	 *
	 * @access	public
	 * @param	fieldsids	Id of fields
	 * @return  	published       published or not
	 * @since	1.0
	 */
        
        function getArticle( $id  )
        { 
             
            
	    $db = &JFactory::getDBO(  );

	    $query = 'SELECT a.id, a.introtext,  a.title, a.alias, a.catid   FROM #__content  as a WHERE a.id = '.$id;
             
            
            $db->setQuery( $query );
	    $obj = $db->loadObject();  
            
            return $obj;
        }



        function action()
        {

        }
        
        
       

}
