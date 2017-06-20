<?php
/**
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$helper = JnRadHelper;
extract($helper::radVars($this->jnrad_asset_singular));
$jnrad_fields = $jnrad_vars["$jnrad_assetL.view.fields"];
// --- rad ---
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
?>
<script type="text/javascript">
	Joomla.submitbutton = function () {
		if(document.formvalidator.isValid(document.id('<?php echo $jnrad_assetL; ?>-form')){
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
		}
	}
</script>
<form
	action=""
	method="post"
	enctype="multipart/form-data"
	name="adminForm"
	id="<?php echo $jnrad_assetL; ?>-form"
	class="form-validate form-horizontal"
	>
	<!-- form actions -->
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
	<!-- custom fields -->
	<?php foreach ($jnrad_fields as $jnrad_field) : ?>
		<?php echo $this->form->renderField($jnrad_field); ?>
	<?php endforeach; ?>
	<!-- system fields -->
	<input type="hidden" name="option" value="<?php echo "com_$jnrad_nameL"; ?>"/>
	<input type="hidden" name="task" value="<?php echo $jnrad_assetL; ?>.apply"/>
	<input type="hidden" name="id" value="<?php echo $this->item->id; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
	<!-- form actions -->
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
