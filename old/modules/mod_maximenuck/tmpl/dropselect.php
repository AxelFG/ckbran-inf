<?php
/**
 * @copyright	Copyright (C) 2012 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * Module Maximenu CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<!-- debut maximenu CK, par cedric keiflin -->
<select name="maximenuckdropselect" style="width: auto" onChange="top.location.href=this.options[this.selectedIndex].value">
<?php
foreach ($items as $i => &$item) {
	echo "<option value=\"".$item->flink."\">".$item->ftitle."</option>";
}
?>
</select>
<!-- fin maximenuCK -->
