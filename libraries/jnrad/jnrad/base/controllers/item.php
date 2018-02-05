<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController as JControllerForm;

/**
 * Item controller base class.
 */
class JnRadItemBaseController extends JControllerForm
{
	public $jnrad = array();


	/**
	 * Constructor
	 */
	public function __construct()
	{
		extract(JnRadHelper::prepare($this->jnrad));

		$this->view_list = $jnrad_asset_plural;

		parent::__construct();
	}
}


