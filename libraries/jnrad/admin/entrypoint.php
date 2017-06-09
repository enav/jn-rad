<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/jnrad_vars.php';

// Access check.
if (!JFactory::getUser()->authorise('core.manage', "com_{$jnrad_vars["jnrad_nameL"]}"))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependencies
require_once "loader.php";
JLoader::register($jnrad_vars["jnrad_name"].'Helper', JPATH_COMPONENT."/helpers/{$jnrad_vars["jnrad_nameL"]}.php");

// Execute task
$controller = JControllerLegacy::getInstance($jnrad_vars["jnrad_name"]);
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();


