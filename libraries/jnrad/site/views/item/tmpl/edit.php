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

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
?>
<script type="text/javascript">
	Joomla.submitbutton = function () {
		if(document.formvalidator.isValid(document.id('<?php echo $jnrad_asset_singularL; ?>-form')){
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
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
	<!-- form actions -->
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>

	<!-- regular fields -->
	<?php
	foreach ($fields as $field){
		$type = $this->form->getField($field)->getAttribute("type");
		if($type == "hidden") continue;
		echo $this->form->renderField($field);
	}
	?>

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

	<!-- form actions -->
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>