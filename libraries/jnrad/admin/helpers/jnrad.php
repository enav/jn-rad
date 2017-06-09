<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * JnRadHelper class.
 */
class JnRadHelper
{
	/**
	 * Adds the sidebar.
	 *
	 * @param   string  $view  Active view name.
	 *
	 * @return  void
	 */
	public static function addSidebar(&$view)
	{
		extract(self::radVars());
		$items = $jnrad_vars['sidebar.items'];

		foreach ($items as $item)
		{
			$itemU = strtoupper($item);
			JHtmlSidebar::addEntry(
				JText::_("COM_{$jnrad_nameU}_SIDEBAR_ITEM_$itemU"),
				"index.php?option=com_$jnrad_nameL&view=$item",
				$item == $view->getName()
			);
		}

		$view->sidebar = JHtmlSidebar::render();
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 */
	public static function getActions()
	{
		extract(self::radVars());
		$user    = JFactory::getUser();
		$result  = new JObject;
		$xml     = JFactory::getXml(JPATH_COMPONENT."/access.xml", true);
		$actions = $xml->section->action;

		foreach ($actions as $action){
			$action = (string)$action['name'];
			$result->set($action, $user->authorise($action, $jnrad_nameL));
		}

		return $result;
	}


	/**
	 * Generates an array with case variations of a name
	 *
	 * @param   string  $name    Name in camel case format.
	 * @param   string  $prefix  Name variation prefix.
	 *
	 * @return    Array
	 */
	public static function nameVariations ($name, $prefix)
	{
		$vars[$prefix] = $name;
		$vars[$prefix.'L'] = strtolower($name);
		$vars[$prefix.'U'] = strtoupper($name);

		return $vars;
	}

	/**
	 * Load and return the array of RAD vars
	 *
	 * @param   string  $jnrad_asset [optional]  Asset name in camel case format.
	 *
	 * @return    Array
	 */
	public static function radVars($jnrad_asset = "")
	{
		include JPATH_COMPONENT.'/jnrad_vars.php';

		// asset name variations
		if($jnrad_asset != "")
		{
			$jnrad_vars = array_merge($jnrad_vars, self::nameVariations($jnrad_asset , 'jnrad_asset'));
		}

		return $jnrad_vars;
	}

	/**
	 * Merge arrays and remove duplicates
	 *
	 * @param   string  $jnrad_asset [optional]  Asset name in camel case format.
	 *
	 * @return    Array
	 */
	public static function arrayMergeUnique(...$arrays_)
	{
		$myArray = array();
		foreach ($arrays_ as $array_)
		{
			if(!isset($array_)) continue;
			$myArray = array_merge($myArray, $array_);
		}
		$myArray = array_unique($myArray);
		return $myArray;
	}


	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 */
	public static function addToolbar($assetName, $view = NULL)
	{
		extract(self::radVars($assetName));
		$jnrad_toolbar_buttons = $jnrad_vars["$jnrad_assetL.view.toolbar.buttons"];
		// --- rad ---

		$canDo = self::getActions($jnrad_assetL);

		if(!$view)
		{
			$user  = JFactory::getUser();
			$isNew = ($view->item->id == 0);
			if (isset($view->item->checked_out))
			{
				$checkedOut = !($view->item->checked_out == 0 || $view->item->checked_out == $user->get('id'));
			}
			else
			{
				$checkedOut = false;
			}
		}

		JToolBarHelper::title(JText::_("COM_{$jnrad_nameU}_PAGETITLE_$jnrad_assetU"), $jnrad_vars["$jnrad_assetL.view.toolbar.icon"]);

		foreach ($jnrad_toolbar_buttons as $jnrad_toolbar_button){
			switch ($jnrad_toolbar_button){
				case 'divider':
					JToolBarHelper::divider();
					break;
				case 'add':
					if ($canDo->get("core.create"))
					{
						JToolBarHelper::addNew("$jnrad_assetL.add", "JTOOLBAR_NEW");
					}
					break;
				case 'edit':
					if ($canDo->get("core.edit"))
					{
						JToolBarHelper::editList("$jnrad_assetL.edit", "JTOOLBAR_EDIT");
					}
					break;
				case 'delete':
					if ($canDo->get("core.edit"))
					{
						JToolBarHelper::deleteList("", "$jnrad_assetL.delete", "JTOOLBAR_DELETE");
					}
					break;
				case 'checkin':
					if ($canDo->get("core.edit"))
					{
						JToolBarHelper::custom("$jnrad_assetL.checkin", "checkin.png", "checkin_f2.png", "JTOOLBAR_CHECKIN", true);
					}
					break;
				case 'admin':
					if ($canDo->get("core.admin"))
					{
						JToolBarHelper::preferences("com_$jnrad_nameL");
					}
					break;
				case 'save':
					if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
					{
						JToolBarHelper::apply("$jnrad_assetL.apply", 'JTOOLBAR_APPLY');
					}
					break;
				case 'save-and-close':
					if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
					{
						JToolBarHelper::save("$jnrad_assetL.save", 'JTOOLBAR_SAVE');
					}
					break;
				case 'save-and-new':
					if (!$checkedOut && ($canDo->get('core.create')))
					{
						JToolBarHelper::custom("$jnrad_assetL.save2new", 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
					}
					break;
				case 'close':
					if (empty($view->item->id))
					{
						JToolBarHelper::cancel("$jnrad_assetL.cancel", 'JTOOLBAR_CANCEL');
					}
					else
					{
						JToolBarHelper::cancel("$jnrad_assetL.cancel", 'JTOOLBAR_CLOSE');
					}
					break;
			}
		}


		// TODO: no idea what is this for ?
		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction("index.php?option=com_$jnrad_nameL&view=$jnrad_assetL");
	}
}
