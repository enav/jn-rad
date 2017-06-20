<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/jnrad_vars.php';

// Include dependencies
require_once "loader.php";
JLoader::register($jnrad_vars["jnrad_name"].'Helper', JPATH_COMPONENT."/helpers/{$jnrad_vars["jnrad_nameL"]}.php");
jimport('joomla.application.component.controller');
JLoader::registerPrefix($jnrad_vars["jnrad_name"], JPATH_COMPONENT);
JLoader::register($jnrad_vars["jnrad_name"].'Controller', JPATH_COMPONENT . '/controller.php');

// Execute task
$controller = JControllerLegacy::getInstance($jnrad_vars["jnrad_name"]);
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

