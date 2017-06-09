<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Item model.
 */
class JnRadItemModel extends JModelAdmin
{

	/**
	 * @var   	string  	Alias to manage history control
	 */
	public $typeAlias = '';

	/**
	 * @var null  Item data
	 */
	protected $item = null;


	/**
	 * Constructor.
	 */
	public function __construct($config = array())
	{
		$helper = jnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		// --- rad ---

		// init
		$this->typeAlias = "$jnrad_nameL.$jnrad_assetL";
		parent::__construct($config);
	}


	/**
	 * Returns a reference to the a Table object, always creating it.
	 */
	public function getTable($type = '', $prefix = '', $config = array())
	{
		$helper = JnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		// --- rad ---

		if(empty($type)) $type = $this->jnrad_asset_singular;
		if(empty($prefix)) $prefix = $jnrad_name."Table";
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$helper = jnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		// --- rad ---

		// Get the form.
		$form = $this->loadForm(
			"com_$jnrad_nameL.$jnrad_assetL",
			$jnrad_assetL,
			array('control' => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 */
	protected function loadFormData()
	{
		$helper = jnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		// --- rad ---

		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState("com_$jnrad_nameL.edit.$jnrad_assetL.data", array());

		if (empty($data))
		{
			if ($this->item === null)
			{
				$this->item = $this->getItem();
			}

			$data = $this->item;
		}

		return $data;
	}


	/**
	 * Prepare and sanitise the table prior to saving.
	 */
	protected function prepareTable($table)
	{
		$helper = jnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		// --- rad ---

		jimport('joomla.filter.output');

		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (@$table->ordering === '')
			{
				$db = JFactory::getDbo();
				$db->setQuery("SELECT MAX(ordering) FROM #__{$jnrad_nameL}_{$jnrad_assetL}s");
				$max             = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}
}

