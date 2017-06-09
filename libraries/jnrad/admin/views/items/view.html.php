<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined("_JEXEC") or die;

/**
 * Items view class.
 */
class JnRadItemsView extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$helper = JnRadHelper;
		extract($helper::radVars($this->jnrad_asset_singular));
		// --- rad ---

		$model = $this->getModel();
		$this->items         = $model->getItems();
		$this->pagination    = $model->getPagination();
		$this->state         = $model->getState();
		$this->filterForm    = $model->getFilterForm();
		$this->activeFilters = $model->getActiveFilters();

		// Check for errors.
		if (count($errors = $this->get("Errors")))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->addToolbar();

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction("index.php?option=com_$jnrad_nameL&view=$jnrad_assetL");

		$helper::addSidebar($this);

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		$helper = JnRadHelper;
		// --- rad ---

		$helper::addToolbar($this->jnrad_asset_singular."s");
	}

}