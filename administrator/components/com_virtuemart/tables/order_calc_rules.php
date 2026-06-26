<?php
/**
*
* Order item table
*
* @package	VirtueMart
* @subpackage Orders Order Calculation Rules
* @author valérie Isaksen
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: order_calc_rules.php 11116 2025-05-07 11:50:15Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Order calculation rules table class
 * The class is is used to manage the order items in the shop.
 *
 * @package	VirtueMart
 * @author Valerie Isaksen
 */
class TableOrder_calc_rules extends VmTable {

	/** @var int Primary key */
	var $virtuemart_order_calc_rule_id = 0;
	/** @var int Calculation ID */
	var $virtuemart_calc_id = null;
	/** @var int Order ID */
	var $virtuemart_order_id = null;

	/** @var int Vendor ID */
	var $virtuemart_vendor_id = null;
	/** @var int Product ID */
	var $virtuemart_order_item_id = null;
	/** @var string Calculation Rule name name */
	var $calc_rule_name = null;
	/** @var int Product Quantity */
	var $calc_kind = null;
	/** @var decimal Product item price */
	var $calc_amount = 0.00000;
	/** @var decimal Calculation Rule Result */
	var $calc_result = 0.00000;

	var $calc_mathop = null;
	var $calc_value = null;
	var $calc_currency = null;
	var $calc_params = null;

	/**
	 * @param $db Class constructor; connect to the database
	 */
	function __construct($db) {
		parent::__construct('#__virtuemart_order_calc_rules', 'virtuemart_order_calc_rule_id', $db);

		$this->setLoggable();
	}

}
// pure php no closing tag
