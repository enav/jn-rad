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
class JnTrackerModelEmployees extends JnRadItemsSiteModel
{
	public $jnrad = array(
		"jnrad_asset" => "Employees",
		"jnrad_asset_singular" => "Employee",
		"jnrad_vars" => array(
			"ordering_fields" => array(
				"name",
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


