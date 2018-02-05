<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel as JModelList;

/**
 * Items model base class.
 */
class JnRadItemsBaseModel extends JModelList
{
	public $jnrad = array();


	/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	*/
	public function __construct($config = array())
	{
		// populate jnrad_vars with filter form content

		// merge and update filter fields
		$form = $this->getFilterForm(null, false);
		$array = JnRadHelper::formGroupToArray($form, "filter", array("search"));
		JnRadHelper::arrayMerge(
			$this->jnrad["jnrad_vars"]["filter_fields"],
			$array,
			true
		);
		unset($array);

		// merge and update populate state fields
		JnRadHelper::arrayMerge(
			$this->jnrad["jnrad_vars"]["populate_state_fields"],
			$this->jnrad["jnrad_vars"]["filter_fields"],
			true
		);

		// merge and update whitelist fields
		JnRadHelper::arrayMerge(
			$this->jnrad["jnrad_vars"]["whitelist_fields"],
			$this->jnrad["jnrad_vars"]["filter_fields"],
			true
		);
		JnRadHelper::arrayMerge(
			$this->jnrad["jnrad_vars"]["whitelist_fields"],
			$this->jnrad["jnrad_vars"]["populate_state_fields"],
			true
		);
		JnRadHelper::arrayMerge(
			$this->jnrad["jnrad_vars"]["whitelist_fields"],
			$this->jnrad["jnrad_vars"]["ordering_fields"],
			true
		);

		// set some default values
		JnRadHelper::setDefaultDBTable($this->jnrad);
		JnRadHelper::setDefaultJTable($this->jnrad);

		extract(JnRadHelper::prepare($this->jnrad));

		// pass whitelist fields to parent constructor
		$fields = $jnrad_vars["whitelist_fields"];

		foreach($fields as $field)
		{
			$config["filter_fields"][] = $field;
			$config["filter_fields"][] = "a.$field";
		}

		parent::__construct($config);
	}


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		extract(JnRadHelper::prepare($this->jnrad));

		$fields = $jnrad_vars["populate_state_fields"];

		// load fields states
		foreach($fields as $field)
		{
			$state = $this->getUserStateFromRequest(
				$this->context.".filter.$field",
				"filter_$field"
			);
			$this->setState("filter.$field", $state);
		}

		// load component params.
		$state = JComponentHelper::getParams("com_$jnrad_nameL");
		$this->setState('params', $state);

		// populate state
		parent::populateState(
			$jnrad_vars["ordering_default"]["field"],
			$jnrad_vars["ordering_default"]["direction"]
		);
	}


	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		extract(JnRadHelper::prepare($this->jnrad));

		$fields = $jnrad_vars["whitelist_fields"];

		// compile id
		foreach($fields as $field)
		{
			$id .= ":".$this->getState("filter.$field");
		}

		return parent::getStoreId($id);
	}


	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		extract(JnRadHelper::prepare($this->jnrad));

		$searchFields = $jnrad_vars["search_fields"];
		$filterFields = $jnrad_vars["filter_fields"];
		$dbTableName = $jnrad_vars["db_table_name"];

		// get dbo
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// select
		$query->select(
			$this->getState('list.select', 'DISTINCT a.*')
		);

		// from
		$query->from("#__{$jnrad_nameL}_{$dbTableName} AS a");

		// join users table for row checkout support (id)
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// join users table for row checkout support (created_by)
		$query->select('created_by.name AS created_by_name');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// join users table for row checkout support (modified_by)
		$query->select('modified_by.name AS modified_by_name');
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

		// filter fields
		foreach ($filterFields as $filterField)
		{
			$state = $this->getState("filter.$filterField");
			if ($state == '') continue;

			$state = $db->Quote('%'.$db->escape($state, true).'%');
			$query->where("a.$filterField LIKE $state");
		}

		// search filter
		$state = $this->getState('filter.search');
		if (!empty($state))
		{
			if (stripos($state, 'id:') === 0)
			{
				// search field unique id search support
				$query->where('a.id = ' . (int) substr($state, 3));
			}
			else
			{
				if(isset($searchFields))
				{
					$state = $db->Quote('%' . $db->escape($state, true) . '%');
					$where = '';
					foreach ($searchFields as $searchField)
					{
						if($where == "")
						{
							$where = "a.$searchField LIKE $state";
						}
						else
						{
							$where .= " OR a.$searchField LIKE $state";
						}
					}
					$query->where("($where)");
				}
			}
		}

		// ordering
		$orderingField  = $this->getState("list.ordering");
		$orderDirection = $this->getState("list.direction");
		if ($orderingField && $orderDirection)
		{
			$query->order($db->escape($orderingField . ' ' . $orderDirection));
		}

		return $query;
	}
}









