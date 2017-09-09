<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;


/**
 * Item model base class.
 */
class JnRadItemBaseModel extends JModelAdmin
{
	public $jnrad = array();

	protected $item = null;


	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 * @since   12.2
	 */
	public function __construct($config = array())
	{
		// set some default values
		JnRadHelper::setDefaultDBTable($this->jnrad);
		JnRadHelper::setDefaultJTable($this->jnrad);

		parent::__construct($config);
	}


	/**
	 * Returns a reference to the a Table object, always creating it.
	 */
	public function getTable($type = '', $prefix = '', $config = array())
	{
		extract(JnRadHelper::prepare($this->jnrad));
		// --- rad ---

		if(empty($type)) $type = $jnrad_vars["j_table_name"];
		if(empty($prefix)) $prefix = "{$jnrad_name}Table";

		return JTable::getInstance($type, $prefix, $config);
	}


	/**
	 * Method to get the record form.
	 */
	public function getForm($data = array(), $loadData = true)
	{
		extract(JnRadHelper::prepare($this->jnrad));
		// --- rad ---

		// Get the form.
		$form = $this->loadForm(
			"com_$jnrad_nameL.$jnrad_asset_singularL",
			$jnrad_asset_singularL,
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
		extract(JnRadHelper::prepare($this->jnrad));
		// --- rad ---

		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState(
			"com_$jnrad_nameL.edit.$jnrad_asset_singularL.data",
			array()
		);

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
		extract(JnRadHelper::prepare($this->jnrad));
		// --- rad ---

		$dbTableName = $jnrad_vars["db_table_name"];

		jimport('joomla.filter.output');

		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (@$table->ordering === '')
			{
				$db = JFactory::getDbo();
				$db->setQuery("SELECT MAX(ordering) FROM #__{$jnrad_nameL}_{$dbTableName}");
				$max             = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}
}

