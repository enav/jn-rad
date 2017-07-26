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
?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
	<h2><?php echo JText::_("COM_{$jnrad_nameU}") ?></h2>
	<p><?php echo JText::_("COM_{$jnrad_nameU}_XML_DESCRIPTION") ?></p>
	<hr>
	<h3><?php echo JText::_("COM_{$jnrad_nameU}_{$jnrad_assetU}_BASIC_USAGE_TITLE") ?></h3>
	<?php echo JText::_("COM_{$jnrad_nameU}_{$jnrad_assetU}_BASIC_USAGE_TEXT") ?>
</div>
