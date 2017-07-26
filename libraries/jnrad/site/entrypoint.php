<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Dependencies
require_once JPATH_LIBRARIES."/jnrad/base/helpers/helper.php";
require_once JPATH_LIBRARIES."/jnrad/base/models/items.php";
require_once JPATH_LIBRARIES."/jnrad/base/models/item.php";
require_once JPATH_LIBRARIES."/jnrad/base/views/items/view.html.php";
require_once JPATH_LIBRARIES."/jnrad/base/views/item/view.html.php";
require_once JPATH_LIBRARIES."/jnrad/base/controllers/controller.php";
require_once JPATH_LIBRARIES."/jnrad/base/controllers/items.php";
require_once JPATH_LIBRARIES."/jnrad/base/controllers/item.php";
require_once JPATH_LIBRARIES."/jnrad/base/tables/item.php";

require_once JPATH_LIBRARIES."/jnrad/site/models/items.php";
require_once JPATH_LIBRARIES."/jnrad/site/models/item.php";
require_once JPATH_LIBRARIES."/jnrad/site/views/items/view.html.php";
require_once JPATH_LIBRARIES."/jnrad/site/views/item/view.html.php";
require_once JPATH_LIBRARIES."/jnrad/site/controllers/controller.php";
require_once JPATH_LIBRARIES."/jnrad/site/controllers/items.php";
require_once JPATH_LIBRARIES."/jnrad/site/controllers/item.php";

extract(JnRadHelper::prepare());

JLoader::register($jnrad_nameL.'Helper', JPATH_COMPONENT."/helpers/$jnrad_nameL.php");
JLoader::registerPrefix($jnrad_nameL, JPATH_COMPONENT);
JLoader::register($jnrad_nameL.'Controller', JPATH_COMPONENT.'/controller.php');

// Execute task
$controller = JControllerLegacy::getInstance($jnrad_nameL);
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

