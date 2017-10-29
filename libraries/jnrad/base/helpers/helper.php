<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * JnRad Helper class.
 */
class JnRadHelper
{
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
		$names[$prefix] = $name;
		$names[$prefix.'L'] = strtolower($name);
		$names[$prefix.'U'] = strtoupper($name);
		return $names;
	}


	/**
	 * Prepare jnrad array for posterior extraction
	 *
	 * @param   array  $jnrad  jnrad var
	 *
	 */
	public static function prepare($jnrad = array())
	{
		// rename this jnrad array to avoid conflic if a jnrad file is loaded
		// TODO: use something more elegant in the future
		$jnrad2 = $jnrad;
		$jnrad = null;

		// load jnrad file if exists
		$file = JPATH_COMPONENT.'/jnrad.php';
		if(file_exists($file))
		{
			include $file;
		}

		// add jnrad vars arrays
		$jnrad["jnrad_vars"] = (array)$jnrad["jnrad_vars"];
		$jnrad2["jnrad_vars"] = (array)$jnrad2["jnrad_vars"];
		$jnrad = array_merge_recursive($jnrad, $jnrad2);

		// extension name variations
		if(empty($jnrad["jnrad_name"]))
		{
			$name = JFactory::getApplication()->input->get('option');
			$name = strtolower($name);
			$name = str_replace("com_", "", $name);
			$name = ucfirst($name);
			$jnrad["jnrad_name"] = $name;
		}
		$jnrad["jnrad_nameL"] = strtolower($jnrad["jnrad_name"]);
		$jnrad["jnrad_nameU"] = strtoupper($jnrad["jnrad_name"]);

		// asset name variants
		if(!empty($jnrad["jnrad_asset"]))
		{
			$jnrad["jnrad_assetL"] = strtolower($jnrad["jnrad_asset"]);
			$jnrad["jnrad_assetU"] = strtoupper($jnrad["jnrad_asset"]);
		}

		// asset singular name variants
		if(!empty($jnrad["jnrad_asset_singular"]))
		{
			$jnrad["jnrad_asset_singularL"] = strtolower($jnrad["jnrad_asset_singular"]);
			$jnrad["jnrad_asset_singularU"] = strtoupper($jnrad["jnrad_asset_singular"]);
		}

		// asset plural name variants
		if(!empty($jnrad["jnrad_asset_plural"]))
		{
			$jnrad["jnrad_asset_pluralL"] = strtolower($jnrad["jnrad_asset_plural"]);
			$jnrad["jnrad_asset_pluralU"] = strtoupper($jnrad["jnrad_asset_plural"]);
		}

		// helper reference, helps to write generic code
		$jnrad["jnrad_helper"] = JnRadHelper;

		return $jnrad;
	}


	/**
	 * Adds the sidebar.
	 *
	 * @param   JView  $view  Views object.
	 *
	 * @return  void
	 */
	public static function addSidebar(&$view)
	{
		extract(self::prepare($view->jnrad));

		$items = $jnrad_vars["sidebar"]["items"];

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
	 * @param   JView  $view  Views object.
	 *
	 * @return  void
	 *
	 */
	public static function getActions($view)
	{
		extract(self::prepare($view->jnrad));

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
	 * Add the page title and toolbar.
	 *
	 * @param   JView  $view  Views object.
	 *
	 * @return  void
	 *
	 */
	public static function addToolbar($view)
	{
		extract(self::prepare($view->jnrad));

		$buttons = $jnrad_vars["toolbar"]["buttons"];
		$icon = $jnrad_vars["toolbar"]["icon"];

		$canDo = self::getActions($view);

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

		JToolBarHelper::title(JText::_("COM_{$jnrad_nameU}_PAGETITLE_{$jnrad_assetU}"), $icon);

		if($buttons){
			foreach ($buttons as $button){
				switch ($button){
					case 'divider':
						JToolBarHelper::divider();
						break;
					case 'add':
						if ($canDo->get("core.create"))
						{
							JToolBarHelper::addNew("$jnrad_asset_singularL.add", "JTOOLBAR_NEW");
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
		}

		// TODO: i have no idea what is this for :(
		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction("index.php?option=com_$jnrad_nameL&view=$jnrad_assetL");
	}


	/**
	 * Merge two array, remove duplicates and update the first array if needed
	 *
	 * @param    array  $array1  An array
	 * @param    array  $array2  An array
	 * @param    array  $update  If true stores result in $array1
	 *
	 * @return   Array
	 *
	 */
	public static function arrayMerge(&$array1, $array2, $update = false){
		$array = array();

		if(!isset($array1)) $array1 = array();
		if(!isset($array2)) $array2 = array();

		$array = array_unique(array_merge($array1, $array2));

		if($update){
			$array1 = $array;
		}

		return $array;
	}


	/**
	 * Sets default DB table name if needed
	 *
	 * @param   array  $jnrad  jnrad var
	 *
	 * @return   void
	 *
	 */
	public static function setDefaultDBTable(&$jnrad){
		if(empty($jnrad["jnrad_vars"]["db_table_name"])){
			$jnrad["jnrad_vars"]["db_table_name"] = strtolower($jnrad["jnrad_asset_singular"]."s");
		}
	}


	/**
	 * Sets default JTable name if needed
	 *
	 * @param   array  $jnrad  jnrad var
	 *
	 */
	public static function setDefaultJTable(&$jnrad){
		if(empty($jnrad["jnrad_vars"]["j_table_name"])){
			$jnrad["jnrad_vars"]["j_table_name"] = strtolower($jnrad["jnrad_asset_singular"]);
		}
	}


	/**
	 * Creates an array of field names from a form group
	 *
	 * @param   JForm   $form       Form object
	 * @param   string  $groupName  (optional) Form group name. If not set return all fields
	 * @param   array   $exclude    (Optional) Array of names to exclude
	 *
	 * @return    Array
	 *
	 */
	public static function formGroupToArray($form, $groupName = "", $exclude = array()){
		$array = array();
		$fields = $form->getGroup($groupName);

		foreach ($fields as $field){
			$name = $field->getAttribute('name');

			if(in_array($name, $exclude)){
				continue;
			}

			$array[] = $field->getAttribute('name');
		}

		return $array;
	}












}


