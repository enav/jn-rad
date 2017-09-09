<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Items view base class
 */
class JnRadItemBaseView extends JViewLegacy
{
	public $jnrad = array();

	protected $state;

	protected $item;

	protected $form;


	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		extract(JnRadHelper::prepare($this->jnrad));
		// --- rad ---

		$model = $this->getModel();
		$this->state = $model->getState();
		$this->item  = $model->getItem();
		$this->form  = $model->getForm();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		// feed jnrad_vars with the form xml file content
		// merge and update form fields
		$array = JnRadHelper::formGroupToArray($this->form);
		JnRadHelper::arrayMerge(
			$this->jnrad["jnrad_vars"]["fields"],
			$array,
			true
			);
		unset($array);

		parent::display($tpl);
	}
}