<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController as JControllerAdmin;

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
	
	
	
	
	/**
	 * Removes an item.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function delete()
	{
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		parent::delete();
	}
	
	
	/**
	 * Method to publish a list of items
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function publish()
	{
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		parent::publish();
	}
	
	
	/**
	 * Changes the order of one or more records.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.6
	 */
	public function reorder()
	{
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		parent::reorder();
	}
	
	
	/**
	 * Method to save the submitted ordering values for records.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.6
	 */
	public function saveorder()
	{
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		parent::saveorder();
	}
	
	
	/**
	 * Check in of one or more records.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.6
	 */
	public function checkin()
	{
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		parent::checkin();
	}
	
	
	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		parent::saveOrderAjax();
	}
	
	
	
	
}


