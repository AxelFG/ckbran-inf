<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldCategoryList extends JFormField {

    protected $type = 'CategoryList';

    public function getInput() {

        $db = JFactory::getDBO();
        
        //GET THE AVAILABLE CATEGORIES
        $query = "SELECT c.id, c.parent_id, c.title, c.level FROM #__categories AS c WHERE c.extension = 'com_content' ORDER BY c.parent_id ASC, c.lft, c.id";
        $db->setQuery($query);
        $c = $db->loadAssocList();
        if(count($c)>0) foreach($c as $d) {
            $categories[$d['parent_id']][] = $d;
        } else {
            $categories = array(
                array(
                    'id' => 0,
                    'title' => 'Please define your categories'
                )
            );
        }
        if ($db->getErrorNum())	{
            echo $db->stderr();
            return false;
        }
        unset($c);
        
        $options = showCats($categories,$this->value);
        $this->checkInstall();
        return "<script type='text/javascript'>
document.getElementById('kz_popup').style.display = 'none';
Joomla.submitbutton = function(task) {
    if(task == 'plugin.save' || task == 'plugin.apply') {
        if(document.getElementById('jform_params_regenerate').value === '1') {
            document.getElementById('kz_popup').style.display = 'block';
            document.getElementById('blackout').style.display = 'block';
        }
    }
	if (task == 'plugin.cancel' || document.formvalidator.isValid(document.id('style-form'))) {
		Joomla.submitform(task, document.getElementById('style-form'));
    }
}
</script><style>label {width: 200px; }</style><select id='{$this->id}' name='{$this->name}[]' multiple='multiple' style='width:200px;height: 300px;'>{$options}</select>";
    }
    
    function checkInstall() {
        $db = JFactory::getDBO();
        //CHECK THE TABLE EXISTS
        $query = "SELECT `id` FROM `#__kazaam_menu` LIMIT 1";
        $db->setQuery($query);
        $db->loadAssoc();
        if($db->getErrorNum()) echo "<p style='top: 0; position: absolute; clear: both;background: #fcc;color:#f00;padding: 5px;'>This plugin is not correctly installed. Please uninstall and reinstall it!</p>";
        echo "
<link rel='stylesheet' href='../plugins/system/kazaam/css/styles.css' />
<div id='blackout'></div>
<div id='kz_popup'>
    <div id='kz_content'>
        <h1>Please enjoy this short break</h1>
        <p>That's all we need for now! Your Kazaam! menu is being regenerated to reflect your settings.</p>
        <p>Once the menu is fully generated, it will then automatically begin to reflect changes made to your articles and categories!</p>
        <p>Never manually create a menu item again!</p><p>All new articles and categories will automatically be included in the Kazaam! menu, so long as the category is included in the menu settings.</p>
        <p>Please leave this page open until the menu is fully regenerated. Thank you.</p>
        <div id='kz_spinner'></div>
        <!-- <p>Hey while you wait, how about a quick review?? If you use Kazaam! please leave a rating and a review at the Joomla! extensions database. You can safely click here to write a review.</p> -->
    </div>
</div>";
    }
}
    function showCats($cats, $selcats, $parent = 1, $lvl = 0, $j=0) {
        if(!is_array($cats[$parent])) return false;
        if(!is_array($selcats)) $selcats = array();
        $offset = $lvl * 5;
        if(!isset($ret)) $ret = '';
        foreach($cats[$parent] as $a=>$b) {
        $ret .= "<option value='{$b['id']}'";
        if(in_array($b['id'], $selcats)) $ret .= " selected='selected' ";
        $ret .= ">";
        if($offset > 0) for($i=0; $i<=$offset; $i++) $ret .= "&nbsp;";
        $ret .= "{$b['title']}</option>";
        $j++;
        if(@is_array($cats[$b['id']])) $ret .= showCats($cats, $selcats, $b['id'], $b['level'], $j);
        }
        return $ret;
    }