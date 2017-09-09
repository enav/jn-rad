<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined("_JEXEC") or die;

/**
 * Items view admin class.
 */
class JnRadItemsAdminView extends JnRadItemsBaseView
{
	protected $items;

	protected $pagination;

	protected $state;

	protected $params;


	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		extract(JnRadHelper::prepare($this->jnrad));
		// --- rad ---

		$model = $this->getModel();
		$this->items         = $model->getItems();
		$this->pagination    = $model->getPagination();
		$this->state         = $model->getState();
		$this->filterForm    = $model->getFilterForm();
		$this->activeFilters = $model->getActiveFilters();

		// Check for errors.
		if (count($errors = $model->getErrors()))
		{
			throw new Exception(implode("\n", $errors));
		}

		JnRadHelper::addToolbar($this);

		JnRadHelper::addSidebar($this);

		parent::display($tpl);
	}
}