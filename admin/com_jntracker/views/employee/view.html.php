<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// No direct access
defined("_JEXEC") or die;

/**
 * Employee view class.
 */
class JnTrackerViewEmployee extends JnRadItemAdminView
{
	public $jnrad = array(
		"jnrad_asset" => "Employee",
		"jnrad_asset_singular" => "Employee",
		"jnrad_asset_plural" => "Employees",
		"jnrad_vars" => array(
			"toolbar" => array(
				"icon" => "cube",
				"buttons" => array(
					"save",
					"save-and-close",
					"close",
				),
			),
			"fields" => array(
				"enable",
				"name",
				"type",
				"notes",
			),
		),
	);
}


