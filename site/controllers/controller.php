<?php

// No direct access
defined('_JEXEC') or die;


/**
 * Main controller class
 */
class JnRadSiteController extends JnRadBaseController
{
	public $jnrad = array();


	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   mixed    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return   JController This object to support chaining.
	 *
	 */
	public function display($cachable = false, $urlparams = false)
	{
		extract(JnRadHelper::prepare($this->jnrad));

		$defautlView = $jnrad_vars["default_view"];

		$view = $this->input->getCmd('view', $defautlView);
		$this->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}

