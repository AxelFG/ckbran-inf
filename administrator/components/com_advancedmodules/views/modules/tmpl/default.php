<?php
/**
 * @package         Advanced Module Manager
 * @version         4.4.2
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2012 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$clientid = (int) $this->state->get('filter.client_id');
$client = $clientid ? 'administrator' : 'site';
$user = JFactory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$trashed = $this->state->get('filter.published') == -2 ? true : false;
$canOrder = $user->authorise('core.edit.state', 'com_advancedmodules');
$saveOrder = $listOrder == 'ordering';
if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option=com_advancedmodules&task=modules.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$showcolors = ($client == 'site' && $this->config->show_color);
if ($showcolors) {
	require_once JPATH_PLUGINS . '/system/nnframework/fields/colorpicker.php';
	$script = "
		function setColor(id, el)
		{
			var f = document.getElementById('adminForm');
			f.setcolor.value = jQuery(el).val();
			listItemTask(id, 'modules.setcolor');
		}
	";
	JFactory::getDocument()->addScriptDeclaration($script);
}
$sortFields = $this->getSortFields();

JHtml::_('behavior.modal', 'a.modal', array('closable' => 0, 'closeBtn' => 0));
JFactory::getDocument()->addStyleDeclaration('#sbox-btn-close { display: none; }');

JHtml::stylesheet('nnframework/style.min.css', false, true);

// Version check
require_once JPATH_PLUGINS . '/system/nnframework/helpers/versions.php';
echo NNVersions::getInstance()->getMessage('advancedmodules', '', '', 'component');
?>
	<script type="text/javascript">
		Joomla.orderTable = function()
		{
			table = document.getElementById("sortTable");
			direction = document.getElementById("directionTable");
			order = table.options[table.selectedIndex].value;
			if (order != '<?php echo $listOrder; ?>') {
				dirn = 'asc';
			} else {
				dirn = direction.options[direction.selectedIndex].value;
			}
			Joomla.tableOrdering(order, dirn, '');
		}
	</script>
	<form action="<?php echo JRoute::_('index.php?option=com_advancedmodules'); ?>" method="post" name="adminForm" id="adminForm">
		<?php if (!empty($this->sidebar)): ?>
			<div id="j-sidebar-container" class="span2">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>
		<div id="j-main-container"<?php echo empty($this->sidebar) ? '' : ' class="span10"'; ?>>
			<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_BANNERS_SEARCH_IN_TITLE'); ?></label>
					<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_MODULES_MODULES_FILTER_SEARCH_DESC'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_MODULES_MODULES_FILTER_SEARCH_DESC'); ?>" />
				</div>
				<div class="btn-group pull-left hidden-phone">
					<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
						<i class="icon-search"></i></button>
					<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();">
						<i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
					<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
						<option value="asc" <?php echo $listDirn == 'asc' ? 'selected="selected"' : ''; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
						<option value="desc" <?php echo $listDirn == 'desc' ? 'selected="selected"' : ''; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
					</select>
				</div>
				<div class="btn-group pull-right">
					<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
					<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
						<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php $cols = 10; ?>
			<table class="table table-striped nn_tablelist" id="articleList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
						</th>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="1%" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
						</th>
						<?php if ($showcolors) : ?>
							<?php $cols++; ?>
							<th width="1%" class="nowrap center hidden-phone">
								<?php echo JHtml::_('grid.sort', '<img src="' . JURI::root(true) . '/media/advancedmodules/images/color.png" alt="Color" />', 'color', $listDirn, $listOrder, null, 'asc', 'AMM_COLOR'); ?>
							</th>
						<?php endif; ?>
						<th class="title">
							<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<?php if ($this->config->show_note == 3) : ?>
							<?php $cols++; ?>
							<th class="title">
								<?php echo JHtml::_('grid.sort', 'JFIELD_NOTE_LABEL', 'a.note', $listDirn, $listOrder); ?>
							</th>
						<?php endif; ?>
						<th width="15%" class="nowrap hidden-phone">
							<?php echo JHtml::_('grid.sort', 'COM_MODULES_HEADING_POSITION', 'position', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('grid.sort', 'COM_MODULES_HEADING_MODULE', 'name', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('grid.sort', 'NN_MENU_ITEMS', 'pages', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap hidden-phone">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language_title', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="<?php echo $cols; ?>">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach ($this->items as $i => $item) :
						$ordering = ($listOrder == 'ordering');
						$canCreate = $user->authorise('core.create', 'com_advancedmodules');
						$canEdit = $user->authorise('core.edit', 'com_advancedmodules');
						$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
						$canChange = $user->authorise('core.edit.state', 'com_advancedmodules') && $canCheckin;
						?>
						<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->position; ?>">
							<td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel = '';
									if (!$saveOrder) :
										$disabledLabel = JText::_('JORDERINGDISABLED');
										$disableClassName = 'inactive tip-top';
									endif; ?>
									<span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
										<i class="icon-menu"></i>
									</span>
									<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
								<?php else : ?>
									<span class="sortable-handler inactive">
										<i class="icon-menu"></i>
									</span>
								<?php endif; ?>
							</td>
							<td class="center hidden-phone">
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							</td>
							<td class="center">
								<?php echo JHtml::_('modules.state', $item->published, $i, $canChange, 'cb'); ?>
							</td>
							<?php if ($showcolors) : ?>
								<td class="center">
									<?php
									$color = (isset($item->params->color) && $item->params->color) ? $color = $item->params->color : '';
									$colorpicker = new nnFieldColorPicker;
									$action = 'setColor(\'cb' . $i . '\', this)';
									echo $colorpicker->getInput('color', 'cb' . $i, $color, array('inlist' => 1, 'action' => $action));
									?>
								</td>
							<?php endif; ?>
							<td class="has-context">
								<div class="pull-left">
									<?php if ($item->checked_out) : ?>
										<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'modules.', $canCheckin); ?>
									<?php endif; ?>
									<?php
									$title = $this->escape($item->title);
									$tooltip = '<strong>' . JText::_('AMM_EDIT_MODULE') . '</strong><br />' . htmlspecialchars($title);
									if (!empty($item->note) && $this->config->show_note == 1) {
										$tooltip .= '<br /><em>' . htmlspecialchars(JText::sprintf('JGLOBAL_LIST_NOTE', $this->escape($item->note))) . '</em>';
									}
									$title = '<span rel="tooltip" title="' . $tooltip . '">' . $title . '</span>';
									?>
									<?php if ($canEdit) : ?>
										<a href="<?php echo JRoute::_('index.php?option=com_advancedmodules&task=module.edit&id=' . (int) $item->id); ?>">
											<?php echo $title; ?></a>
									<?php else : ?>
										<?php echo $title; ?>
									<?php endif; ?>
									<?php if (!empty($item->note) && $this->config->show_note == 2) : ?>
										<div class="small">
											<?php echo JText::sprintf('JGLOBAL_LIST_NOTE', $this->escape($item->note)); ?>
										</div>
									<?php endif; ?>
								</div>
								<div class="pull-left">
									<?php
									// Create dropdown items
									JHtml::_('dropdown.edit', $item->id, 'module.');
									JHtml::_('dropdown.addCustomItem', JText::_('AMM_OPEN_IN_MODAL_WINDOW'), 'index.php?option=com_advancedmodules&task=module.edit&id=' . (int) $item->id . '&tmpl=component', 'class="modal" rel="{handler: \'iframe\', size: {x:window.getSize().x-100, y: window.getSize().y-100}, }"');
									JHtml::_('dropdown.divider');
									if ($item->published) {
										JHtml::_('dropdown.unpublish', 'cb' . $i, 'modules.');
									} else {
										JHtml::_('dropdown.publish', 'cb' . $i, 'modules.');
									}

									JHtml::_('dropdown.divider');

									if ($item->checked_out) {
										JHtml::_('dropdown.checkin', 'cb' . $i, 'modules.');
									}

									if ($trashed) {
										JHtml::_('dropdown.untrash', 'cb' . $i, 'modules.');
									} else {
										JHtml::_('dropdown.trash', 'cb' . $i, 'modules.');
									}

									// Render dropdown list
									echo JHtml::_('dropdown.render');
									?>
								</div>
							</td>
							<?php if ($this->config->show_note == 3) : ?>
								<td class="has-context">
									<?php echo $this->escape($item->note); ?>
								</td>
							<?php endif; ?>
							<td class="small hidden-phone">
								<?php if ($item->position) : ?>
									<span class="label label-info">
										<?php echo $item->position; ?>
									</span>
								<?php else : ?>
									<span class="label">
										<?php echo JText::_('JNONE'); ?>
									</span>
								<?php endif; ?>
							</td>
							<td class="small hidden-phone">
								<?php echo $item->name; ?>
							</td>
							<td class="small hidden-phone">
								<?php echo $item->pages; ?>
							</td>

							<td class="small hidden-phone">
								<?php echo $this->escape($item->access_level); ?>
							</td>
							<td class="small hidden-phone">
								<?php if ($item->language == ''): ?>
									<?php echo JText::_('JDEFAULT'); ?>
								<?php elseif ($item->language == '*'): ?>
									<?php echo JText::alt('JALL', 'language'); ?>
								<?php else: ?>
									<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
								<?php endif; ?>
							</td>
							<td class="center hidden-phone">
								<?php echo (int) $item->id; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<?php //Load the batch processing form. ?>
			<?php echo $this->loadTemplate('batch'); ?>

			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="setcolor" value="" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>

<?php if ($this->config->show_switch) : ?>
	<a style="float:right;" href="<?php echo JRoute::_('index.php?option=com_modules&force=1'); ?>"><?php echo JText::_('AMM_SWITCH_TO_CORE'); ?></a>
<?php endif; ?>
<?php
// PRO Check
require_once JPATH_PLUGINS . '/system/nnframework/helpers/licenses.php';
echo NNLicenses::getInstance()->getMessage('ADVANCED_MODULE_MANAGER', 0);

// Copyright
echo NNVersions::getInstance()->getCopyright('ADVANCED_MODULE_MANAGER', '', 10307, 'advancedmodules', 'component');
