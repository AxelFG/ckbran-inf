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
?> 
<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>

<?php 

// get the parameter values
$moduleclass_sfx = $params->get('moduleclass_sfx'); 
$function = $params->get('function');
if (!empty($function)) {
?>
<?php if(!empty($moduleclass_sfx)){?>
<div class="<?php echo $moduleclass_sfx;?>">
<?php } ?>
<?php eval($function); ?>
<?php if(!empty($moduleclass_sfx)){?>
</div>
<?php
	}
}
?>