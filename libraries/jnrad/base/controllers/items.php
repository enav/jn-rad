<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;


/**
 * Items controller base class.
 */
class JnRadItemsBaseController extends JControllerAdmin
{
	public $jnrad = array();


	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = '', $prefix = '', $config = array())
	{
		extract(JnRadHelper::prepare($this->jnrad));
		// --- rad ---

		if(empty($name)){
			$name = $jnrad_asset_singularL;
		}
		if(empty($prefix)){
			$prefix = "{$jnrad_name}Model";
		}
		if(empty($config)){
			$config = array('ignore_request' => true);
		}

		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}


