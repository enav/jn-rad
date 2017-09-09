<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Items view admin class
 */
class JnRadItemAdminView extends JnRadItemBaseView
{

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		extract(JnRadHelper::prepare($this->jnrad));
		// --- rad ---

		JnRadHelper::addToolbar($this);

		parent::display($tpl);
	}
}