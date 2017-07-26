<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
extract(JnRadHelper::prepare($this->jnrad));
// --- rad ---

$columns = $jnrad_vars["grid"]["columns"];

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = "index.php?option=com_$jnrad_nameL&task=$jnrad_asset_pluralL.saveOrderAjax&tmpl=component";
	JHtml::_('sortablelist.sortable', "{$jnrad_asset_singularL}List", "adminForm", strtolower($listDirn), $saveOrderingUrl);
}
?>
<form action="<?php echo JRoute::_("index.php?option=com_$jnrad_nameL&view=$jnrad_asset_pluralL"); ?>" method="post"
	name="adminForm" id="adminForm">
	<!-- sidebar -->
	<?php if (!empty($this->sidebar)): ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
	<?php endif; ?>
	<!-- main container -->
	<div id="j-main-container" <?php if (!empty($this->sidebar)) echo 'class="span10"' ?>>

		<!-- search tools -->
		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));?>

		<!-- grid -->
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="<?php echo "{$jnrad_asset_singularL}List"; ?>">
				<!-- grid head -->
				<thead>
					<tr>
						<?php foreach($columns as $column) : ?>
							<?php
							$field = $column['field'];
							$fieldU = strtoupper($field);
							if(!isset($column['header'])) $column['header'] = $field;
							$header = $column['header'];
							$headerU = strtoupper($header);
							$attribs = $column['th.attribs'];
							?>
							<!-- col: <?php echo $header; ?> -->
							<th <?php echo $attribs; ?>>
								<?php if($field == 'ordering') : ?>
									<?php //ordering ?>
									<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_header_ORDERING', 'icon-menu-2'); ?>
								<?php elseif($field == 'checkbox') : ?>
									<?php //checkbox ?>
									<?php echo JHtml::_('grid.checkall'); ?>
								<?php else : ?>
									<?php //custom ?>
									<?php echo JHtml::_('searchtools.sort', "COM_{$jnrad_nameU}_COLUMN_{$headerU}", "a.{$field}", $listDirn, $listOrder); ?>
								<?php endif; ?>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<!-- grid body -->
				<tbody>
					<?php foreach ($this->items as $i => $item) :
						$item->max_ordering = 0;
						$ordering   = ($listOrder == 'a.ordering');
						$canCreate  = $user->authorise('core.create', "com_$jnrad_nameL");
						$canEdit    = $user->authorise('core.edit', "com_$jnrad_nameL");
						$canCheckin = $user->authorise('core.manage', "com_$jnrad_nameL");
						$canEditOwn = $user->authorise('core.edit.own', "com_$jnrad_nameL") && $item->created_by == $userId;
						$canChange  = $user->authorise('core.edit.state', "com_$jnrad_nameL") && $canCheckin;
						?>
						<tr sortable-group-id="1">
							<?php foreach($columns as $column) : ?>
								<?php
								$field = $column['field'];
								$fieldU = strtoupper($field);
								if(!isset($column['header'])) $column['header'] = $field;
								$header = $column['header'];
								$headerU = strtoupper($header);
								$attribs = $column['td.attribs'];
								$translateRows = $column['translateRows'];
								$translateRowsPrefix = $column['translateRowsPrefix'];
								$add_checkout = $column['add_checkout'];
								$add_edit_link = $column['add_edit_link'];
								$translate = $column['td.translate'];
								$translatePrefix = $column['td.translatePrefix'];
								?>
								<!-- col: <?php echo $header; ?> -->
								<td <?php echo $attribs; ?>>
									<?php if($field == 'ordering') : ?>
										<?php //ordering ?>
										<?php
										$iconClass = '';
										if (!$canChange)
										{
											$iconClass = ' inactive';
										}
										elseif (!$saveOrder)
										{
											$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
										}
										?>
										<span class="sortable-handler<?php echo $iconClass ?>">
											<span class="icon-menu"></span>
										</span>
										<?php if ($canChange && $saveOrder) : ?>
											<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
										<?php endif; ?>
									<?php elseif($field == 'checkbox') : ?>
										<?php //checkedout ?>
										<?php echo JHtml::_('grid.id', $i, $item->id); ?>
									<?php elseif($field == 'enable') : ?>
										<?php //enable ?>
										<?php
										$fieldText = $this->escape($item->{$field});
										$fieldText = JText::_("COM_{$jnrad_nameU}_VALUEMAP_ENABLE_".$fieldText);
										if($item->{$field} == 1)
										{
											echo "<span class=\"badge badge-success\">$fieldText</span>";
										}
										else if($item->{$field} == 0)
										{
											echo "<span class=\"badge badge-important\">$fieldText</span>";
										}
										?>
									<?php else : ?>
										<?php //custom ?>
										<?php if ($add_checkout && isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
											<?php //add checkout ?>
											<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, "$jnrad_asset_pluralL.", $canCheckin); ?>
										<?php endif; ?>
										<?php
										$fieldText = $this->escape($item->{$field});
										if($translate){
											//translate
											$fieldText = strtoupper("COM_{$jnrad_nameU}_{$translatePrefix}{$fieldText}");
											$fieldText = JText::_($fieldText);
										}
										?>
										<?php if ($add_edit_link && ($canEdit || $canEditOwn)) : ?>
											<?php //add edit link ?>
											<a href="<?php echo JRoute::_("index.php?option=com_$jnrad_nameL&task=$jnrad_asset_singularL.edit&id=".(int) $item->id); ?>"
												title="<?php echo JText::_('JACTION_EDIT'); ?>"
											>
												<?php echo $fieldText; ?>
											</a>
										<?php else : ?>
											<?php //plain text ?>
											<?php echo $fieldText; ?>
										<?php endif; ?>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<!-- grid footer -->
				<tfoot>
					<tr>
						<td colspan="<?php echo count($columns); ?>">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
			</table>
		<?php endif; ?>

		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0"/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
