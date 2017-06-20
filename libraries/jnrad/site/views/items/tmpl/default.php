<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
$helper = JnRadHelper;
extract($helper::radVars($this->jnrad_asset_singular));
$jnrad_columns = $jnrad_vars[$jnrad_assetL."s.view.columns"];
// --- rad ---
?>
<ul>
	<?php foreach ($this->items as $item) : ?>
		<li>
			<?php
			$id = (int) $item->id;
			$link = JRoute::_("index.php?option=com_$jnrad_nameL&view=$jnrad_assetL&id=$id");
			$editLink = JRoute::_("index.php?option=com_$jnrad_nameL&view=$jnrad_assetL&id=$id&layout=edit");
			?>
			<a href="<?php echo $link; ?>"><?php echo $item->name; ?></a>
			-
			<a href="<?php echo $editLink; ?>">[Edit]</a>
		</li>
	<?php endforeach; ?>
</ul>
<?php echo $this->pagination->getListFooter(); ?>


