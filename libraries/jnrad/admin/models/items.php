<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;


/**
 * Items model class.
 *
 */
class JnRadItemsModel extends JModelList
{
	/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		$helper = JnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		$jnrad_filters = $helper::arrayMergeUnique(
			$jnrad_vars["{$jnrad_assetL}s.model.filters.ordering"],
			$jnrad_vars["{$jnrad_assetL}s.model.filters.field"]
		);
		// --- rad ---

		foreach($jnrad_filters as $jnrad_filter)
		{
			$config["filter_fields"][] = $jnrad_filter;
			$config["filter_fields"][] = "a.$jnrad_filter";
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
		$helper = JnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		$jnrad_filters_ordering_default_field = $jnrad_vars["{$jnrad_assetL}s.model.filters.ordering_default"]['field'];
		$jnrad_filters_ordering_default_direction = $jnrad_vars["{$jnrad_assetL}s.model.filters.ordering.default"]['direction'];
		$jnrad_filters = $jnrad_vars["{$jnrad_assetL}s.model.filters.field"];
		// --- rad ---

		// init vars
		$app = JFactory::getApplication('administrator');

		// load filed states
		foreach($jnrad_filters as $jnrad_filter)
		{
			$filterState = $this->getUserStateFromRequest(
				$this->context.".filter.$jnrad_filter", "filter_$jnrad_filter");
			$this->setState("filter.$jnrad_filter", $filterState);
		}

		// load component params.
		$params = JComponentHelper::getParams("com_$jnrad_nameL");
		$this->setState('params', $params);

		// populate state
		parent::populateState(
			$jnrad_filters_ordering_default_field, $jnrad_filters_ordering_default_direction
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
		$helper = JnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		$jnrad_filters = $helper::arrayMergeUnique(
			$jnrad_vars["{$jnrad_assetL}s.model.filters.ordering"],
			$jnrad_vars["{$jnrad_assetL}s.model.filters.field"]
		);
		// --- rad ---

		// adds search field to list
		$jnrad_filters[] = "search";

		// compile id
		foreach($jnrad_filters as $jnrad_filter)
		{
			$id .= ':' . $this->getState("filter.$jnrad_filter");
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
		$helper = JnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		$jnrad_search_in_fields = $jnrad_vars["{$jnrad_assetL}s.model.search_in_fields"];
		$jnrad_filters_ordering = $jnrad_vars["{$jnrad_assetL}s.model.filters.ordering"];
		$jnrad_filters_field = $jnrad_vars["{$jnrad_assetL}s.model.filters.field"];
		// --- rad ---

		// In this case the 'search' field is special and needs to be removed from the list
		unset($jnrad_filters_field[array_search('search',$jnrad_filters_field)]);

		// get dbo
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// select
		$query->select(
			$this->getState('list.select', 'DISTINCT a.*')
		);

		// from
		$query->from("#__{$jnrad_nameL}_{$jnrad_assetL}s AS a");

		// join users table for row checkout support (id)
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// join users table for row checkout support (created_by)
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// join users table for row checkout support (modified_by)
		$query->select('modified_by.name AS modified_by');
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

		// field filters
		foreach ($jnrad_filters_field as $jnrad_filter_field)
		{
			$filterState = $this->getState("filter.$jnrad_filter_field");
			if ($filterState == '') continue;

			$filterState = $db->Quote('%'.$db->escape($filterState, true).'%');
			$query->where("a.$jnrad_filter_field LIKE $filterState");
		}

		// search filter
		$filterState = $this->getState('filter.search');
		if (!empty($filterState))
		{
			if (stripos($filterState, 'id:') === 0)
			{
				// search field unique id search support
				$query->where('a.id = ' . (int) substr($filterState, 3));
			}
			else
			{
				if(isset($jnrad_search_in_fields))
				{
					$filterState = $db->Quote('%' . $db->escape($filterState, true) . '%');
					$where = '';
					foreach ($jnrad_search_in_fields as $jnrad_search_in_field)
					{
						if($where == "")
						{
							$where = "a.$jnrad_search_in_field LIKE $filterState";
						}
						else
						{
							$where .= " OR a.$jnrad_search_in_field LIKE $filterState";
						}
					}
					$query->where("($where)");
				}
			}
		}

		// ordering
		$orderCol  = $this->getState("list.ordering");
		$orderDirn = $this->getState("list.direction");
		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

}
