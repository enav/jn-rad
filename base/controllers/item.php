<?php
/**
 * @copyright   Copyright (C) 2017 jnilla.com, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die();

use Joomla\CMS\MVC\Controller\FormController as JControllerForm;

/**
 * Item controller base class.
 */
class JnRadItemBaseController extends JControllerForm{
	public $jnrad = array();
	
	/**
	 * Constructor
	 */
	public function __construct(){
		extract(JnRadHelper::prepare($this->jnrad));
		
		$this->view_list = $jnrad_asset_plural;
		
		parent::__construct();
	}
	
	
	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * TODO: Factory this single taks to separated actions for create and update to follow the CRUD generic standard naming convention
	 */
	public function save($key = null, $urlVar = null){
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		// Check for request forgeries.
		\JSession::checkToken() or jexit(\JText::_('JINVALID_TOKEN'));
		
		$app = \JFactory::getApplication();
		$model = $this->getModel();
		$table = $model->getTable();
		$data = $this->input->post->get('jform', array(), 'array');
		$checkin = property_exists($table, $table->getColumnAlias('checked_out'));
		$context = "$this->option.edit.$this->context";
		$task = $this->getTask();
		
		// Determine the name of the primary key for the data.
		if(empty($key)){
			$key = $table->getKeyName();
		}
		
		// To avoid data collisions the urlVar may be different from the primary key.
		if(empty($urlVar)){
			$urlVar = $key;
		}
		
		$recordId = $this->input->getInt($urlVar);
		
		// Populate the row id from the session.
		$data[$key] = $recordId;
		
		// The save2copy task needs to be handled slightly differently.
		if($task === 'save2copy'){
			// Check-in the original row.
			if($checkin && $model->checkin($data[$key]) === false){
				// Check-in failed. Go back to the item and display a notice.
				$this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
				$this->setMessage($this->getError(), 'error');
				
				$this->setRedirect(\JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId, $urlVar), false));
				
				return false;
			}
			
			// Reset the ID, the multilingual associations and then treat the request as for Apply.
			$data[$key] = 0;
			$data['associations'] = array();
			$task = 'apply';
		}
		
		// Access check.
		if(!$this->allowSave($data, $key)){
			$this->setError(\JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');
			
			//TODO: Raw implementation of intended functionality
			
			$redirectTo = $jnrad_vars["redirects"]["create"]["on_unautorized"];
			
			if($redirectTo){
				$this->setRedirect(\JRoute::_($redirectTo, false));
			}else{
				$this->setRedirect(\JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend(), false));
			}
			
			
			
			return false;
		}
		
		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);
		
		if(!$form){
			$app->enqueueMessage($model->getError(), 'error');
			
			return false;
		}
		
		// Test whether the data is valid.
		$validData = $model->validate($form, $data);
		
		// Check for validation errors.
		if($validData === false){
			// Get the validation messages.
			$errors = $model->getErrors();
			
			// Push up to three validation messages out to the user.
			for($i = 0, $n = count($errors); $i < $n && $i < 3; $i++){
				if($errors[$i] instanceof \Exception){
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}else{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
			
			// Save the data in the session.
			$app->setUserState($context . '.data', $data);
			
			// Redirect back to the edit screen.
			$this->setRedirect(\JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId, $urlVar), false));
			
			return false;
		}
		
		if(!isset($validData['tags'])){
			$validData['tags'] = null;
		}
		
		// Attempt to save the data.
		if(!$model->save($validData)){
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);
			
			// Redirect back to the edit screen.
			$this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');
			
			$this->setRedirect(\JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId, $urlVar), false));
			
			return false;
		}
		
		// Save succeeded, so check-in the record.
		if($checkin && $model->checkin($validData[$key]) === false){
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);
			
			// Check-in failed, so go back to the record and display a notice.
			$this->setError(\JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');
			
			$this->setRedirect(\JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId, $urlVar), false));
			
			return false;
		}
		
		$langKey = $this->text_prefix . ($recordId === 0 && $app->isClient('site') ? '_SUBMIT' : '') . '_SAVE_SUCCESS';
		$prefix = \JFactory::getLanguage()->hasKey($langKey) ? $this->text_prefix : 'JLIB_APPLICATION';
		
		$this->setMessage(\JText::_($prefix . ($recordId === 0 && $app->isClient('site') ? '_SUBMIT' : '') . '_SAVE_SUCCESS'));
		
		// Redirect the user and adjust session state based on the chosen task.
		switch($task){
			case 'apply':
				// Set the record data in the session.
				$recordId = $model->getState($this->context . '.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				$model->checkout($recordId);
				
				// Redirect back to the edit screen.
				$this->setRedirect(\JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId, $urlVar), false));
				break;
			
			case 'save2new':
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				
				// Redirect back to the edit screen.
				$this->setRedirect(\JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend(null, $urlVar), false));
				break;
			
			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				
				$url = 'index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend();
				
				// Check if there is a return value
				$return = $this->input->get('return', null, 'base64');
				
				if(!is_null($return) && \JUri::isInternal(base64_decode($return))){
					$url = base64_decode($return);
				}
				
				// Redirect to the list screen.
				$this->setRedirect(\JRoute::_($url, false));
				break;
		}
		
		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $validData);
		
		return true;
	}
	
	
	/**
	 * Method to add a new record.
	 */
	public function add()
	{
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		parent::add();
	}
	
	
	/**
	 * Method to run batch operations.
	 */
	public function batch($model)
	{
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		parent::batch($model);
	}
	
	
	/**
	 * Method to cancel an edit.
	 */
	public function cancel($key = null)
	{
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		parent::cancel($key = null);
	}
	
	
	/**
	 * Method to edit an existing record.
	 */
	public function edit($key = null, $urlVar = null)
	{
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		parent::edit($key = null, $urlVar = null);
	}
	
	
	/**
	 * Method to load a row from version history
	 */
	public function loadhistory()
	{
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		parent::loadhistory();
	}
	
	
	/**
	 * Method to reload a record.
	 */
	public function reload($key = null, $urlVar = null)
	{
		extract(JnRadHelper::prepare($this->jnrad));
		JnRadHelper::checkEnabledApi($jnrad_vars, __FUNCTION__);
		
		parent::reload($key = null, $urlVar = null);
	}
	
}


