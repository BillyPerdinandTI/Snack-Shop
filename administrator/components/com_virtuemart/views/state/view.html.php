<?php
/**
*
* State View
*
* @package	VirtueMart
* @subpackage State
* @author RickG, Max Milbers
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: view.html.php 11101 2025-01-03 11:25:45Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for maintaining the list of states
 *
 * @package	VirtueMart
 * @subpackage State
 * @author Max Milbers
 */
class VirtuemartViewState extends VmViewAdmin {

	function display($tpl = null) {

		$this->SetViewTitle();
		$model = VmModel::getModel();

		$virtuemart_state_id = vRequest::getInt('virtuemart_state_id', 0);
		$this->state = $model->getSingleState($virtuemart_state_id);

		$this->virtuemart_country_id = vRequest::getInt('virtuemart_country_id', $this->state->virtuemart_country_id);

		$isNew = (empty($this->state->virtuemart_state_id));

		if(empty($this->virtuemart_country_id) and $isNew){
			vmWarn('Country id is 0');
			return false;
		}

		$countryM = VmModel::getModel('country');
		$this->country_name = $countryM->getData($this->virtuemart_country_id)->country_name;

		$layoutName = vRequest::getCmd('layout', 'default');
		if ($layoutName == 'edit') {

			$zoneModel = VmModel::getModel('Worldzones');
			$this->worldZones = $zoneModel->getWorldZonesSelectList();
			$this->addStandardEditViewCommands();

		} else {

			$this->addStandardDefaultViewCommands();
			$this->addStandardDefaultViewLists($model);

			$this->states = $model->getStates($this->virtuemart_country_id);
			$this->pagination = $model->getPagination();

		}

		parent::display($tpl);
	}

}
// pure php no closing tag
