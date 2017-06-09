<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Landing page view class.
 */
class JnRadLandingView extends JViewLegacy
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$helper = JnRadHelper;
		extract($helper::radVars("Landing"));
		// --- rad ---

		$this->addToolbar();
		$helper::addSidebar($this);
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		$helper = JnRadHelper;
		extract($helper::radVars('Landing'));
		// --- rad ---

		$canDo = JnRadHelper::getActions();

		JToolBarHelper::title(JText::_("COM_{$jnrad_nameU}"), 'cube');

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences("com_{$jnrad_nameL}");
		}
	}
}
