<?php
/**
 * @package		DigiCom
 * @author 		ThemeXpert http://www.themexpert.com
 * @copyright	Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined('_JEXEC') or die;

class DigiComControllerCustomer extends JControllerForm {
	var $_model = null;

	function __construct () {
		
		parent::__construct();
		$this->registerTask ("apply", "save");
		$this->_model = $this->getModel('Customer');
		
	}
	
	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   1.7
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('Customer', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_digicom&view=customers' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   JModelLegacy  $model      The data model object.
	 * @param   array         $validData  The validated data.
	 *
	 * @return	void
	 *
	 * @since	1.6
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		$task = $this->getTask();

		if ($task == 'save')
		{
			$this->setRedirect(JRoute::_('index.php?option=com_digicom&view=customers', false));
		}
	}

	function save($key = NULL, $urlVar = NULL){
		$error = "";
		$username = JRequest::getVar("username", "");
		if($this->_model->existUser($username) !== TRUE){
			if($this->_model->store($error)){
				$msg = JText::_('COM_DIGICOM_CUSTOMER_NOTICE_CUSTOMER_SAVE_SUCCESSFUL');
				$keyword = JRequest::getVar("keyword", "", "request");
				if(JRequest::getVar('task','') == 'save'){
					$link = "index.php?option=com_digicom&view=customers".(strlen(trim($keyword)) > 0 ? "&keyword=".$keyword:"");
				}
				else{
					$cust_id = JRequest::getVar('id','');
					$link = "index.php?option=com_digicom&view=customer&task=customer.edit&id=" . $cust_id;
				}
			}
			else{
				$msg = JText::_('COM_DIGICOM_CUSTOMER_NOTICE_CUSTOMER_SAVE_FAILED');
				$msg .= " " . JText::_($error);
				$link = "index.php?option=com_digicom&view=customer&task=customer.add&id=" . $cust_id;
			}
			$this->setRedirect($link, $msg);
		}
		else{
			$link = "index.php?option=com_digicom&view=customer&task=customer.add";
			$msg = JText::_("DIGI_USER_IN_JOOMLA_EXIST");
			$this->setRedirect($link, $msg, "notice");
		}
	}

}