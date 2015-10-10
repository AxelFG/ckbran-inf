<?php
/**
 * @version		3.0
 * @package		Joomla
 * @subpackage	Payment Form
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2010 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
if (version_compare(JVERSION, '3.0', 'ge')) {
	class LegacyModel extends JModelLegacy {
		
	}
} else {
	jimport( 'joomla.application.component.model' );
	class LegacyModel extends JModel {
				
	}
}