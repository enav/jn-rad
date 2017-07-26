<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// No direct access
defined('_JEXEC') or die;

/**
 * Employee model class.
 */
class JnTrackerModelEmployee extends JnRadItemSiteModel
{
	public $jnrad = array(
		"jnrad_asset" => "Employee",
		"jnrad_asset_singular" => "Employee",
		"jnrad_asset_plural" => "Employees",
		"jnrad_vars" => array(
			"db_table_name" => "employees",
			"j_table_name" => "employee",
		),
	);
}


