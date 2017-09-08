<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;


/**
 * Employees model class.
 */
class JnTrackerModelEmployees extends JnRadItemsAdminModel
{
	public $jnrad = array(
		"jnrad_asset" => "Employees",
		"jnrad_asset_singular" => "Employee",
		"jnrad_asset_plural" => "Employees",
		"jnrad_vars" => array(
			"ordering_fields" => array(
				"enable",
				"name",
				"type",
				"id",
			),
			"ordering_default" => array(
				"field" => "name",
				"direction" => "asc",
			),
			"search_fields" => array(
				"name",
			),
		)
	);
}


