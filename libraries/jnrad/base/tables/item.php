<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined("_JEXEC") or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Item table base class
 */
class JnRadItemBaseTable extends JTable
{
	public $jnrad = array();


	/**
	 * Constructor
	 */
	public function __construct(&$db)
	{
		extract(JnRadHelper::prepare($this->jnrad));
		// --- rad ---

		$dbTableName = $jnrad_vars["db_table_name"];

		parent::__construct("#__{$jnrad_nameL}_{$dbTableName}", "id", $db);
	}


	/**
	 * Overloaded bind function to pre-process the params.
	 */
	public function bind($array, $ignore = "")
	{
		extract(JnRadHelper::prepare($this->jnrad));
		// --- rad ---

		$dbTableName = $jnrad_vars["db_table_name"];

		if ($array["id"] == 0)
		{
			$array["created_by"] = JFactory::getUser()->id;
		}

		if ($array["id"] == 0)
		{
			$array["modified_by"] = JFactory::getUser()->id;
		}

		if (isset($array["params"]) && is_array($array["params"]))
		{
			$registry = new JRegistry;
			$registry->loadArray($array["params"]);
			$array["params"] = (string) $registry;
		}

		if (isset($array["metadata"]) && is_array($array["metadata"]))
		{
			$registry = new JRegistry;
			$registry->loadArray($array["metadata"]);
			$array["metadata"] = (string) $registry;
		}

		if (!JFactory::getUser()->authorise("core.admin", "$jnrad_nameL.$jnrad_asset_singularL" . $array["id"]))
		{
			$actions = JAccess::getActionsFromFile(
				JPATH_ADMINISTRATOR . "/components/com_$jnrad_nameL/access.xml",
				"/access/section[@name=\"$jnrad_asset_singularL\"]/"
			);
			$default_actions = JAccess::getAssetRules("$jnrad_nameL.$jnrad_asset_singularL" . $array["id"])->getData();
			$array_jaccess   = array();

			foreach ($actions as $action)
			{
				$array_jaccess[$action->name] = $default_actions[$action->name];
			}

			$array["rules"] = $this->JAccessRulestoArray($array_jaccess);
		}

		// Bind the rules for ACL where supported.
		if (isset($array["rules"]) && is_array($array["rules"]))
		{
			$this->setRules($array["rules"]);
		}

		return parent::bind($array, $ignore);
	}


	/**
	 * This function convert an array of JAccessRule objects into an rules array.
	 */
	private function JAccessRulestoArray($jaccessrules)
	{
		$rules = array();

		foreach ($jaccessrules as $action => $jaccess)
		{
			$actions = array();

			if ($jaccess)
			{
				foreach ($jaccess->getData() as $group => $allow)
				{
					$actions[$group] = ((bool)$allow);
				}
			}

			$rules[$action] = $actions;
		}

		return $rules;
	}


	/**
	 * Overloaded check function
	 */
	public function check()
	{
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, "ordering") && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}

		return parent::check();
	}


	/**
	 * Define a namespaced asset name for inclusion in the #__assets table
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return "com_$jnrad_nameL.".$jnrad_assetL.".".(int)$this->$k;
	}


	/**
	 * Returns the parent asset"s id. If you have a tree structure, retrieve the parent"s id using the external key field
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = JTable::getInstance("Asset");

		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();

		// The item has the component as asset-parent
		$assetParent->loadByName("com_$jnrad_nameL");

		// Return the found asset-parent-id
		if ($assetParent->id)
		{
			$assetParentId = $assetParent->id;
		}

		return $assetParentId;
	}


	/**
	 * Delete a record by id
	 */
	public function delete($pk = null)
	{
		$this->load($pk);
		$result = parent::delete($pk);

		return $result;
	}
}
