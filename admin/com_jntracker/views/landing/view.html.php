<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;


/**
 * landing view class.
 */
class JnTrackerViewLanding extends JnRadLandingAdminView
{
	public $jnrad = array(
		"jnrad_asset" => "Landing",
		"jnrad_vars" => array(
			"toolbar" => array(
				"icon" => "cube",
				"buttons" => array(
					"admin",
				),
			),
		),
	);
}

