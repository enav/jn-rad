<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


// No direct access
defined("_JEXEC") or die;

/**
 * Employees view class.
 */
class JnTrackerViewEmployees extends JnRadItemsAdminView
{
	public $jnrad = array(
		"jnrad_asset" => "Employees",
		"jnrad_asset_singular" => "Employee",
		"jnrad_asset_plural" => "Employees",
		"jnrad_vars" => array(
			"toolbar" => array(
				"icon" => "cube",
				"buttons" => array(
					"add",
					"delete",
					"checkin",
					"admin",
				),
			),
			"grid" => array(
				"columns" => array(
					array(
						"field" => "ordering",
						"th.attribs" => 'width="1%" class="nowrap center hidden-phone"',
						"td.attribs" => 'class="order nowrap center hidden-phone"',
					),
					array(
						"field" => "checkbox",
						"th.attribs" => 'width="1%" class="hidden-phone""',
						"td.attribs" => 'class="hidden-phone"',
					),
					array(
						"field" => "enable",
						"heading" => "enable",
						"th.attribs" => 'width="1%" class="nowrap center"',
						"td.attribs" => 'class="center"',
					),
					array(
						"field" => "name",
						"th.attribs" => 'class="left"',
						"add_checkout" => true,
						"add_edit_link" => true,
					),
					array(
						"field" => "type",
						"th.attribs" => 'class="left"',
						"td.translate" => true,
						"td.translatePrefix" => "VALUEMAP_EMPLOYEE_TYPE_",
					),
					array(
						"field" => "id",
						"th.attribs" => 'width="1%" class="left"',
					),
				),
			),
		),
	);
}

