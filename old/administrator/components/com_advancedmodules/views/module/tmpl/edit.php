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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.combobox');
JHtml::_('formbehavior.chosen', 'select');

$hasContent = empty($this->item->module) || $this->item->module == 'custom' || $this->item->module == 'mod_custom';

// Get Params Fieldsets
$this->fieldsets = $this->form->getFieldsets('params');
$this->hidden_fields = '';

$script = "
Joomla.submitbutton = function(task)
{
	if (task == 'module.cancel' || document.formvalidator.isValid(document.id('module-form'))) {";
if ($hasContent) {
	$script .= $this->form->getField('content')->save();
}
$script .= "		var f = document.getElementById('module-form');
		if (self != top) {
			if ( task == 'module.cancel' || task == 'module.save' ) {
				f.target = '_top';
			} else {
				f.action += '&tmpl=component';
			}
		}
		Joomla.submitform(task, f);
	} else {
		alert('" . $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')) . "');
	}
}
";
if (JFactory::getUser()->authorise('core.admin')) {
	$script .= "
jQuery(document).ready(function()
{
	// add alert on remove assignment buttons
	jQuery('button.nn_remove_assignment').click(function()
	{
		if(confirm('" . $this->escape(str_replace('<br />', '\n', JText::_('AMM_DISABLE_ASSIGNMENT'))) . "')) {
			jQuery('div#toolbar-options button').click();
		}
	});
});
";
}

JFactory::getDocument()->addScriptDeclaration($script);
JHtml::script('nnframework/script.min.js', false, true);
JHtml::script('nnframework/toggler.min.js', false, true);

$tmpl = JFactory::getApplication()->input->get('tmpl');
if ($tmpl == 'component') : ?>
	<?php
	JFactory::getDocument()->addStyleDeclaration('html{ overflow-y: auto !important; }body{ overflow-y: auto !important; padding: 0; }');
	$bar = JToolBar::getInstance('toolbar');
	$bar = str_replace('href="#"', 'href="javascript://"', $bar->render());
	?>
	<header class="header">
		<div class="container-fluid">
			<h1 class="page-title"><?php echo JHtml::_('string.truncate', JFactory::getApplication()->JComponentTitle, 0, false, false); ?></h1>
		</div>
	</header>
	<a class="btn btn-subhead" data-toggle="collapse" data-target=".subhead-collapse">Toolbar
		<i class="icon-wrench"></i></a>
	<div class="subhead-collapse">
		<div class="subhead">
			<div class="container-fluid">
				<div id="container-collapse" class="container-collapse"></div>
				<div class="row-fluid">
					<div class="span12">
						<?php echo $bar; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid container-main">
	<section id="content">
<?php endif; ?>
	<form action="<?php echo JRoute::_('index.php?option=com_advancedmodules&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="module-form" class="form-validate form-horizontal">
		<fieldset>
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#basic" data-toggle="tab"><?php echo JText::_('COM_MODULES_BASIC_FIELDSET_LABEL'); ?></a>
				</li>
				<?php if ($hasContent) : ?>
					<li>
						<a href="#custom" data-toggle="tab"><?php echo JText::_('COM_MODULES_CUSTOM_OUTPUT'); ?></a>
					</li>
				<?php endif; ?>
				<?php foreach ($this->fieldsets as $fieldset) : ?>
					<?php if (!in_array($fieldset->name, array('description', 'basic'))) : ?>
						<?php $label = !empty($fieldset->label) ? JText::_($fieldset->label) : JText::_('COM_MODULES_' . $fieldset->name . '_FIELDSET_LABEL'); ?>
						<li>
							<a href="#options-<?php echo $fieldset->name; ?>" data-toggle="tab"><?php echo $label ?></a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if ($this->item->client_id == 0) : ?>
					<li>
						<a href="#assignment" data-toggle="tab"><?php echo JText::_('AMM_MODULE_ASSIGNMENT'); ?></a>
					</li>
				<?php endif; ?>
				<?php if (JFactory::getUser()->authorise('core.admin', 'com_advancedmodules')) : ?>
					<li>
						<a href="#permissions" data-toggle="tab"><?php echo JText::_('JCONFIG_PERMISSIONS_LABEL'); ?></a>
					</li>
				<?php endif; ?>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="basic">
					<div class="row-fluid">
						<div class="span6">
							<?php if ((string) $this->item->xml->name != 'Login Form'): ?>
								<div class="control-group">
									<div class="control-label">
										<?php echo $this->form->getLabel('published'); ?>
									</div>
									<div class="controls">
										<?php echo $this->form->getInput('published'); ?>
									</div>
								</div>
							<?php endif; ?>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('title'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('title'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('showtitle'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('showtitle'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('position'); ?>
								</div>
								<div class="controls">
									<?php echo $this->loadTemplate('positions'); ?>
								</div>
							</div>

							<?php if ($this->item->client_id == 0 && $this->config->show_hideempty) : ?>
								<?php echo $this->render($this->assignments, 'hideempty'); ?>
							<?php endif; ?>

							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('access'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('access'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('ordering'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('ordering'); ?>
								</div>
							</div>
							<?php if ($this->item->client_id != 0): ?>
								<?php if ((string) $this->item->xml->name != 'Login Form'): ?>
									<div class="control-group">
										<div class="control-label">
											<?php echo $this->form->getLabel('publish_up'); ?>
										</div>
										<div class="controls">
											<?php echo $this->form->getInput('publish_up'); ?>
										</div>
									</div>
									<div class="control-group">
										<div class="control-label">
											<?php echo $this->form->getLabel('publish_down'); ?>
										</div>
										<div class="controls">
											<?php echo $this->form->getInput('publish_down'); ?>
										</div>
									</div>
								<?php endif; ?>

								<div class="control-group">
									<div class="control-label">
										<?php echo $this->form->getLabel('language'); ?>
									</div>
									<div class="controls">
										<?php echo $this->form->getInput('language'); ?>
									</div>
								</div>
							<?php endif; ?>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('note'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('note'); ?>
								</div>
							</div>

							<?php if ($this->config->show_color) : ?>
								<?php echo $this->render($this->assignments, 'color'); ?>
							<?php endif; ?>
						</div>
						<div class="span6">
							<?php if ($this->item->xml) : ?>
								<h4>
									<?php echo ($text = (string) $this->item->xml->name) ? JText::_($text) : $this->item->module; ?>
									<span class="label"><?php echo $this->item->client_id == 0 ? JText::_('JSITE') : JText::_('JADMINISTRATOR'); ?></span>
									<?php if ($this->item->id) : ?>
										<span class="label label-info"><?php echo JText::_('JGRID_HEADING_ID'); ?>
											: <?php echo $this->item->id; ?></span>
									<?php endif; ?>
								</h4>
								<?php if (isset($this->fieldsets['description'])) : ?>
									<?php if ($fields = $this->form->getFieldset('description')) : ?>
										<hr />
										<div>
											<?php foreach ($fields as $field) : ?>
												<?php if (!$field->hidden) : ?>
													<div class="control-group">
														<div class="control-label">
															<?php echo $field->label; ?>
														</div>
														<div class="controls">
															<?php echo $field->input; ?>
														</div>
													</div>
												<?php else : ?>
													<?php $this->hidden_fields .= $field->input; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								<?php else : ?>
									<hr />
									<div>
										<?php echo JText::_(trim($this->item->xml->description)); ?>
									</div>
								<?php endif; ?>
								<?php if (isset($this->fieldsets['basic'])) : ?>
									<?php if ($fields = $this->form->getFieldset('basic')) : ?>
										<hr />
										<?php foreach ($fields as $field) : ?>
											<?php if (!$field->hidden) : ?>
												<div class="control-group">
													<div class="control-label">
														<?php echo $field->label; ?>
													</div>
													<div class="controls">
														<?php echo $field->input; ?>
													</div>
												</div>
											<?php else : ?>
												<?php $this->hidden_fields .= $field->input; ?>
											<?php endif; ?>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>
							<?php else : ?>
								<div class="alert alert-error"><?php echo JText::_('COM_MODULES_ERR_XML'); ?></div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php if ($hasContent) : ?>
					<div class="tab-pane" id="custom">
						<?php echo $this->form->getInput('content'); ?>
					</div>
				<?php endif; ?>

				<?php echo $this->loadTemplate('options'); ?>

				<?php if ($this->item->client_id == 0) : ?>
					<div class="tab-pane" id="assignment">
						<?php echo $this->loadTemplate('assignment'); ?>
					</div>
				<?php endif; ?>

				<?php if (JFactory::getUser()->authorise('core.admin', 'com_advancedmodules')) : ?>
					<div class="tab-pane" id="permissions">
						<fieldset>
							<?php echo $this->form->getInput('rules'); ?>
						</fieldset>
					</div>
				<?php endif; ?>

			</div>
		</fieldset>
		<?php echo $this->hidden_fields; ?>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
		<?php echo $this->form->getInput('module'); ?>
		<?php echo $this->form->getInput('client_id'); ?>
	</form>

<?php if ($this->config->show_switch) : ?>
	<a style="float:right;" href="<?php echo JRoute::_('index.php?option=com_modules&force=1&task=module.edit&id=' . (int) $this->item->id); ?>"><?php echo JText::_('AMM_SWITCH_TO_CORE'); ?></a>
	<div style="clear:both;"></div>
<?php endif; ?>
<?php if ($tmpl == 'component') : ?>
	</div>
<?php endif; ?>
<?php
// Copyright
require_once JPATH_PLUGINS . '/system/nnframework/helpers/versions.php';
echo NNVersions::getInstance()->getCopyright('ADVANCED_MODULE_MANAGER', '', 10307, 'advancedmodules', 'component');
