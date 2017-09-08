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
class JnTrackerViewEmployee extends JnRadItemSiteView
{
	public $jnrad = array(
		"jnrad_asset" => "Employee",
		"jnrad_asset_singular" => "Employee",
		"jnrad_vars" => array(
			"fields" => array(
				"enable",
				"name",
				"type",
				"notes",
			),
		)
	);
}


