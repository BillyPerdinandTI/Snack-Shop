<?php
/**
 *
 * Handle the Product Custom Fields
 *
 * @package    VirtueMart
 * @subpackage Product
 * @author RolandD, Patrick khol
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - Copyright (C) 2004 - 2022 Virtuemart Team. All rights reserved. VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product_edit_custom.php 11200 2025-09-29 17:40:53Z Milbo $
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

?>


<?php
$relatedcategories=array();
$relatedproducts=array();
$customcfs=array();
$i = 0;

if (isset($this->product->customfields)) {
	$customfieldsModel = VmModel::getModel('customfields');


	$i = 0;

	foreach ($this->product->customfields as $k => $customfield) {

		$checkValue = $customfield->virtuemart_customfield_id;
		$title = '';
		$text = '';
		$customfield->display = $customfieldsModel->displayProductCustomfieldBE($customfield, $this->product, $i);

		$checkValue = $customfield->virtuemart_customfield_id;
		if ($customfield->override != 0 or $customfield->disabler != 0) {

			if (!empty($customfield->disabler)) {
				$checkValue = $customfield->disabler;
			}
			if (!empty($customfield->override)) {
				$checkValue = $customfield->override;
			}
			//$title = vmText::sprintf('COM_VIRTUEMART_CUSTOM_OVERRIDE', $checkValue) . '</br>';
			if ($customfield->disabler != 0) {
				$title = vmText::sprintf('COM_VIRTUEMART_CUSTOM_DISABLED', $checkValue) . '</br>';
			}

			if ($customfield->override != 0) {
				$title = vmText::sprintf('COM_VIRTUEMART_CUSTOM_OVERRIDE', $checkValue) . '</br>';
			}

		} else {
			if ($customfield->virtuemart_product_id == $this->product->product_parent_id) {
				$title = vmText::_('COM_VIRTUEMART_CUSTOM_INHERITED') . '</br>';
			}
		}
		$disableDerivedCheckbox='';
		$nonInheritableCheckbox='';

		if (!empty($title)) {

			if($customfield->virtuemart_product_id != $this->product->product_parent_id){
				$tip = 'COM_VIRTUEMART_CUSTOMFLD_DIS_DER_TIP';
			} else {
				$tip = 'COM_VIRTUEMART_CUSTOMFLD_DIS_TIP';
			}
			vmdebug('!empty($title)', $tip);
		} else {

			if($customfield->virtuemart_product_id != $this->product->product_parent_id){

				$tip = 'COM_VIRTUEMART_CUSTOMFLD_NON_INH_TIP';
				//vmdebug('Customfield from product', $customfield);
			} else {
				$tip = 'COM_VIRTUEMART_CUSTOMFLD_DIS_TIP';
				//vmdebug('Customfield derived', $customfield);
			}

			$nonInheritableCheckbox = VmHtml::checkbox('field[' . $i . '][noninheritable]', $customfield->noninheritable, $checkValue);
		}

		$disableDerivedCheckbox = VmHtml::checkbox('field[' . $i . '][disabler]', $customfield->disabler, $checkValue);
		$disableDerived = '<span style="white-space: nowrap;" uk-tooltip="' . htmlentities(vmText::_($tip)) . '">d:' . $disableDerivedCheckbox . '</span>';
		//vmdebug('Customfield derived '.$customfield->field_type, $tip);

		if ($customfield->is_cart_attribute) {
			$cartIcone = 'default';
		} else {
			$cartIcone = 'default-off';
		}
		if ($customfield->field_type == 'Z') {
			// R: related categories
			$relatedcategory= new stdClass();
			$relatedcategory->displayHTML=$customfield->display;
			$relatedcategory->hiddenHTML=VirtueMartModelCustomfields::setEditCustomHidden($customfield, $i);
			$relatedcategory->title=$title;
			$relatedcategory->disableDerivedCheckbox=$disableDerivedCheckbox;
			$relatedcategory->nonInheritableCheckbox=$nonInheritableCheckbox;
			$relatedcategories[] =$relatedcategory;

		} elseif ($customfield->field_type == 'R') {
			// R: related products
			$relatedproduct= new stdClass();
			$relatedproduct->displayHTML=$customfield->display;
			$relatedproduct->hiddenHTML=VirtueMartModelCustomfields::setEditCustomHidden($customfield, $i);
			$relatedproduct->title=$title;
			$relatedproduct->disableDerivedCheckbox=$disableDerivedCheckbox;
			$relatedproduct->nonInheritableCheckbox=$nonInheritableCheckbox;
			$relatedproducts[] =$relatedproduct;
		} else {
			$customcf= new stdClass();
			if (isset($this->fieldTypes[$customfield->field_type])) {
				$type = $this->fieldTypes[$customfield->field_type];
			} else {
				$type = 'deprecated';
			}
			$customcf->type=$type;
			$colspan = '';

			if ($customfield->field_type == 'C' or $customfield->field_type == 'RC') {
				$colspan = 'colspan="2" ';
			}
			$customcf->overrideCheckbox='';

			$customcf->disableDerivedCheckbox=$disableDerivedCheckbox;

			if (!empty($title)) {
				$overrideCheckbox =  VmHtml::checkbox('field[' . $i . '][override]', $customfield->override, $checkValue) ;
				$customcf->overrideCheckbox=$overrideCheckbox;

			} else {
				$customcf->nonInheritableCheckbox=$nonInheritableCheckbox;
			}


			$customcf->type=vmText::_($type) ;
			$customcf->title=vmText::_($customfield->custom_title) ;
			$customcf->is_cart_attribute=(int)$customfield->is_cart_attribute;
			$customcf->canMove=false;
			$customcf->canRemove=false;
			$customcf->searchable=(int)$customfield->searchable;
			$customcf->layout_pos=$customfield->layout_pos;

			if (($customfield->virtuemart_product_id == $this->product->virtuemart_product_id or $customfield->override != 0) and $customfield->disabler == 0) {
				$customcf->canMove=true;
				$customcf->canRemove=true;
			}

			$customcf->hiddenHTML=VirtueMartModelCustomfields::setEditCustomHidden($customfield, $i);
			$customcf->displayHTML=$customfield->display;
			$customcfs[]=$customcf;
		}

		$i++;
	}
}


$this->relatedcategories=$relatedcategories;

?>
<div class="uk-grid-small uk-child-width-1-1" uk-grid>
	<div>
		<?php
		$this->relatedType="categories";
		$this->virtuemart_custom_id = '1';
		$this->relatedDatas=$relatedcategories;
		$this->relatedIcon='category';
		echo $this->loadTemplate('custom_relatedcf');
		$this->relatedType="";
		$this->relatedDatas=array();
		?>
	</div>
	<div>
		<?php
		$this->relatedType="products";
		$this->virtuemart_custom_id = '2';
		$this->relatedDatas=$relatedproducts;
		$this->relatedIcon='product';
		echo $this->loadTemplate('custom_relatedcf') ;
		$this->relatedType="";
		$this->relatedDatas=array();
		?>
	</div>

	<div>
		<?php
		$this->customcfs=$customcfs;
		echo $this->loadTemplate('custom_customs')
		?>
	</div>
</div>



