<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<link
	rel="stylesheet" type="text/css"
	href="<?php echo JURI::base() ?>/modules/mod_joomdesk_tree/tmpl/treeview/assets/css/folders/tree.css"></link>

<style>
.joomdesk_tree h5,.joomdesk_tree h6 {
	margin: 0px;
	margin-bottom: 10px;
}

.joomdesk_tree a {
	text-decoration: none;
	color: #333333;
	font-weight: bold;
	font-size: 12px;
	background: none;
}

.joomdesk_tree td,.joomdesk_tree tr {
	border: none !important;
	border-collapse: 0px !important;
	background-color: transparent !important;
}


.joomdesk_tree table {
	border: none !important;
	border-collapse: 0px !important;
	background-color: transparent !important;
}

.ygtvlabel, .ygtvlabel:link, .ygtvlabel:visited, .ygtvlabel:hover {
    background:none;
    margin-bottom:20px;
}

</style>

<script type="text/javascript"
	src="<?php echo JURI::base() ?>/modules/mod_joomdesk_tree/tmpl/yahoo-dom-event/yahoo-dom-event.js">
        </script>
<script type="text/javascript"
	src="<?php echo JURI::base() ?>/modules/mod_joomdesk_tree/tmpl/treeview/treeview-min.js">
        </script>

<div id="joomdesk_tree" class="yui-skin-sam joomdesk_tree">
	<ul class="tree_mod<?php echo $moduleclass_sfx; ?>">
		<?php
		require JModuleHelper::getLayoutPath('mod_joomdesk_tree', $params->get('layout', 'default').'_items');
		?>
	</ul>
</div>

<script type="text/javascript">
//Declarations
var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

	Event.onContentReady("joomdesk_tree",function(){
		check = function(el) {
			return (Dom.hasClass(el, "expanded") && Dom.hasClass(el, "treeNode"));
		};

		var LIs = YAHOO.util.Dom.getElementsBy(check);

		for ( var x = 0; x < LIs.length; x++) {
			li = LIs[x];
			while(li.parentNode.tagName != "DIV"){
				li = li.parentNode;
				Dom.addClass(li,"expanded");
			}
		}

		
		var  tree = new YAHOO.widget.TreeView("joomdesk_tree");
		tree.render();
	});
</script>
