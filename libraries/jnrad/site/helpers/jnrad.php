<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * JnRadHelper class.
 */
class JnRadHelper
{

	/**
	 * Generates an array with case variations of a name
	 *
	 * @param   string  $name    Name in camel case format.
	 * @param   string  $prefix  Name variation prefix.
	 *
	 * @return    Array
	 */
	public static function nameVariations ($name, $prefix)
	{
		$vars[$prefix] = $name;
		$vars[$prefix.'L'] = strtolower($name);
		$vars[$prefix.'U'] = strtoupper($name);

		return $vars;
	}


	/**
	 * Load and return the array of RAD vars
	 *
	 * @param   string  $jnrad_asset [optional]  Asset name in camel case format.
	 *
	 * @return    Array
	 */
	public static function radVars($jnrad_asset = "")
	{
		include JPATH_COMPONENT.'/jnrad_vars.php';

		// asset name variations
		if($jnrad_asset != "")
		{
			$jnrad_vars = array_merge($jnrad_vars, self::nameVariations($jnrad_asset , 'jnrad_asset'));
		}

		return $jnrad_vars;
	}


	/**
	 * Merge arrays and remove duplicates
	 *
	 * @param   string  $jnrad_asset [optional]  Asset name in camel case format.
	 *
	 * @return    Array
	 */
	public static function arrayMergeUnique(...$arrays_)
	{
		$myArray = array();
		foreach ($arrays_ as $array_)
		{
			if(!isset($array_)) continue;
			$myArray = array_merge($myArray, $array_);
		}
		$myArray = array_unique($myArray);
		return $myArray;
	}


	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_jntracker/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_jntracker/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'JntrackerModel');
		}

		return $model;
	}
}


