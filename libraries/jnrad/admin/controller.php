<?php

// No direct access
defined('_JEXEC') or die;

/**
 * Controller class for main controller
 */
class JnRadController extends JControllerLegacy
{
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
		$helper = JnRadHelper;
		extract($helper::radVars());
		$jnrad_default_view = $jnrad_vars["default_view"];
		// -- rad --

		$view = JFactory::getApplication()->input->getCmd('view', $jnrad_default_view);
		JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}