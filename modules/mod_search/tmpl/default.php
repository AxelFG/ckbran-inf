<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_search
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<div class="searchblock<?php echo $moduleclass_sfx ?>">
<form id="searchForm" action="/poisk/" method="post">

	<div class="btn-toolbar">
		<div class="btn-group pull-left">
			<input type="text" name="searchword" id="search-searchword" size="30" maxlength="20" placeholder="Поиск" value="" class="inputbox">
		</div>
		<div class="btn-group pull-left">
			<button name="Search" onclick="this.form.submit()" class="btn hasTooltip" data-original-title="Искать"><span class="icon-search"></span></button>
		</div>
		<input type="hidden" name="task" value="search">
		<div class="clearfix"></div>
	</div>

</form></div>
