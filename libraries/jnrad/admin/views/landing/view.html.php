<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;


/**
 * Landing page view class.
 */
class JnRadLandingAdminView extends JViewLegacy
{
	public $jnrad = array();


	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		extract(JnRadHelper::prepare($this->jnrad));
		// --- rad ---

		$jnrad_helper::addToolbar($this);

		$jnrad_helper::addSidebar($this);

		parent::display($tpl);
	}
}
