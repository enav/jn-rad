<?php
/**
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
extract(JnRadHelper::prepare($this->jnrad));
// --- rad ---

$fields = $jnrad_vars["fields"];

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tabstate');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {

	});

	Joomla.submitbutton = function (task) {
		if (task == '<?php echo $jnrad_asset_singularL; ?>.cancel') {
			Joomla.submitform(task, document.getElementById('<?php echo $jnrad_asset_singularL; ?>-form'));
		}
		else {

			if (task != '<?php echo $jnrad_asset_singularL; ?>.cancel' && document.formvalidator.isValid(document.id('<?php echo $jnrad_asset_singularL; ?>-form'))) {

				Joomla.submitform(task, document.getElementById('<?php echo $jnrad_asset_singularL; ?>-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action=""
	method="post"
	enctype="multipart/form-data"
	name="adminForm"
	id="<?php echo $jnrad_asset_singularL; ?>-form"
	class="form-validate form-horizontal"
	>
	<!-- regular fields -->
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'basic')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'basic', JText::_("COM_{$jnrad_nameU}_TAB_BASIC", true)); ?>
			<?php
			foreach ($fields as $field){
				$type = $this->form->getField($field)->getAttribute("type");
				if($type == "hidden") continue;
				echo $this->form->renderField($field);
			}
			?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<!-- system fields -->
	<input type="hidden" name="option" value="<?php echo "com_$jnrad_nameL"; ?>"/>
	<input type="hidden" name="layout" value="edit"/>
	<input type="hidden" name="task" value="<?php echo $jnrad_asset_singularL; ?>.apply"/>
	<input type="hidden" name="id" value="<?php echo $this->item->id; ?>"/>
	<?php echo JHtml::_('form.token'); ?>

	<!-- hidden fields -->
	<?php
	foreach ($fields as $field){
		$type = $this->form->getField($field)->getAttribute("type");
		if($type != "hidden") continue;
		echo $this->form->renderField($field);
	}
	?>
</form>
