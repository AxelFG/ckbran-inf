<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div 
<?php if(!empty($moduleclass_sfx)){?> class="<?php echo $moduleclass_sfx;?>"
<?php } ?>>
<h3 class="page-header"><span><?php echo $module->title; ?></span></h3>
<ul>
<?php foreach ($list as $item) :  ?>
	<li>
		<a href="<?php echo $item->link; ?>">
			<?php echo $item->title; ?></a>
            <?php echo $item->text; ?>
	</li>
<?php endforeach; ?>
</ul>
</div>