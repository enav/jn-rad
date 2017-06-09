<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Item controller class.
 */
class JnRadItemController extends JControllerForm
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$helper = JnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		// --- rad ---

		$this->view_list = $jnrad_assetL."s";
		parent::__construct();
	}
}
