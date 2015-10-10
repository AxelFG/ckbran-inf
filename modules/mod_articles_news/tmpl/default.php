<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="newsflash module <?php echo $moduleclass_sfx; ?>">
<h3 class="page-header"><span><?php echo $module->title; ?></span><a href="/ckb/novosti" class="showall">все новости</a></h3>
<ul>
<?php
foreach ($list as $item) :
	require JModuleHelper::getLayoutPath('mod_articles_news', '_item');
endforeach;
?>
</ul>
</div>
