<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined("_JEXEC") or die;

use Joomla\CMS\MVC\View\HtmlView as JViewLegacy;
use Joomla\CMS\MVC\View\HtmlView;

/**
 * Items view base class.
 */
class JnRadItemsBaseView extends JViewLegacy
{
	public $jnrad = array();


	/**
	 * Constructor
	 */
	public function __construct($config = array())
	{
		extract(JnRadHelper::prepare($this->jnrad));

		/*
		 * Cheks if form filter exist
		 *
		 * Note: Seems like the object we are extending from need
		 * this form to exist even if we are not going to use it.
		 * Joomla throws an error if the form does not exist but the error
		 * details are not helpful. We created this guard to throw a
		 * better error message when needed.
		 */
		$formPath = JPATH_COMPONENT."/models/forms/filter_$jnrad_assetL.xml";
		if(!file_exists($formPath)){
			throw new Exception("Form file not found: $formPath");
		}

		parent::__construct($config);

	}

}