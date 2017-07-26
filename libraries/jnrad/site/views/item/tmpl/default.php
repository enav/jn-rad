<?php
/**
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
extract(JnRadHelper::prepare($this->jnrad));
// --- rad ---
?>
<ul>
	<?php foreach ($this->item as $key => $val) : ?>
		<li>
			<strong><?php echo $key; ?></strong> : <?php echo $val; ?>
		</li>
	<?php endforeach; ?>
</ul>