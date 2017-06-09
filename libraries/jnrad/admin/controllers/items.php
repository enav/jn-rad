<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Items controller class.
 */
class JnRadItemsController extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = '', $prefix = '', $config = array())
	{
		$helper = JnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		// --- rad ---

		if(empty($name)){
			$name = $jnrad_assetL;
		}
		if(empty($prefix)){
			$prefix = $jnrad_name."Model";
		}
		if(empty($config)){
			$config = array('ignore_request' => true);
		}
		//getModel($name = 'employee', $prefix = 'JntrackerModel', $config = array())
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}


