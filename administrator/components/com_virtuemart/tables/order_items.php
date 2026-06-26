<?php
/**
*
* Order item table
*
* @package	VirtueMart
* @subpackage Orders
* @author RolandD
* @link https://virtuemart.net
* @copyright Copyright (c) 2004 - 2020 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: order_items.php 11116 2025-05-07 11:50:15Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Order item table class
 * The class is is used to manage the order items in the shop.
 *
 * @package	VirtueMart
 * @author RolandD
 */
class TableOrder_items extends VmTable {

	/** @var int Primary key */
	var $virtuemart_order_item_id = 0;
	/** @var int User ID */
	var $virtuemart_order_id = null;

	/** @var int Vendor ID */
	var $virtuemart_vendor_id = null;
	/** @var int Product ID */
	var $virtuemart_product_id = null;
	/** @var string Order item SKU */
	var $order_item_sku = null;
	/** @var string Order item name */
	var $order_item_name = null;
	/** @var int Product Quantity */
	var $product_quantity = null;
	/** @var decimal Product item price */
	var $product_item_price = 0.00000;
	/** @var decimal Product Base price with tax*/
	var $product_basePriceWithTax = 0.00000;
	/** @var decimal Product discounted price without tax*/
	var $product_discountedPriceWithoutTax = 0.00000;
	/** @var decimal Product final price without tax*/
	var $product_priceWithoutTax = 0.00000;
	/** @var tax amount */
	var $product_tax = 0.00000;
	/** @var decimal Product final price */
	var $product_final_price = 0.00000;
	/** $product_discount_amount */
	var $product_subtotal_discount = 0.00000;
	/** $product_discount_amount */
	var $product_subtotal_with_tax = 0.00000;
	/** @var string Order item currency */
	var $order_item_currency = null;
	/** @var char Order status */
	var $order_status = null;
	/** @var text Product attribute */
	var $product_attribute = null;
	var $paid = 0;
	var $oi_hash = null;

	/**
	 * @param $db Class constructor; connect to the database
	 */
	function __construct($db) {
		parent::__construct('#__virtuemart_order_items', 'virtuemart_order_item_id', $db);

		$this->setLoggable();

		$this->setHashable('oi_hash');
		$this->setOmittedHashFields(array('virtuemart_order_item_id','modified_on','modified_by','locked_on','locked_by','paid'));
		$this->setConvertDecimal(array('paid','product_item_price','product_basePriceWithTax','product_discountedPriceWithoutTax',
			'product_priceWithoutTax','product_tax','product_final_price','product_subtotal_discount','product_subtotal_with_tax'));
		$this->_genericVendorId = false;
	}

}
// pure php no closing tag
