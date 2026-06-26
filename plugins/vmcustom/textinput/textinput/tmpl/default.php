<?php
defined('_JEXEC') or die();
$class='no-vm-bind vmcustom-textinput';

/** @var TYPE_NAME $viewData */
$product = $viewData[0];
$params = $viewData[1];
$nameJs = 'customProductData['.$product->virtuemart_product_id.']['.$params->virtuemart_custom_id.']';
$name = 'customProductData['.$product->virtuemart_product_id.']['.$params->virtuemart_custom_id.']['.$params->virtuemart_customfield_id .'][comment]';

$recalculate = '';
if(!empty($params->customfield_price)){

	$recalculate = 'Virtuemart.setproducttype(formProduct,virtuemart_product_id);';
}

?>

    <input class="<?php echo $class ?>"
           type="text" value=""
           size="<?php echo $params->custom_size ?>"
           name="<?php echo $name?>"
    ><br />
<?php

$checkMin = '';
$ButtonTexts = '';
if(!empty($params->required_letters)){
	$ButtonTexts = 'Virtuemart.BtncartAddToo = "'.vmText::_( 'COM_VIRTUEMART_CART_ADD_TO' ).'";
                    Virtuemart.BtncartEnterText = "'.vmText::_( 'VMCUSTOM_TEXTINPUT_ENTER_TEXT' ).'";';
	$checkMin = 'Virtuemart.checkCharCount($(this), event, formProduct);';
	vmJsApi::addJScript('toggleCartButton');
}
//javascript to update price
$j = 'var test = function($) {
'.$ButtonTexts.'
	jQuery(".vmcustom-textinput").keyup(function(event) {
			formProduct = jQuery(this).parents("form.product");
			virtuemart_product_id = formProduct.find(\'input[name="virtuemart_product_id[]"]\').val();
		'.$recalculate.'
		'.$checkMin.'
		});
		Virtuemart.checkCharCount = function(obj, event){
		    var charCount; 
		    
		    var addToCart = true;
		    
		    formProduct.find("[name$=\'[comment]\']").each(function (){
		        charCount = jQuery(this).val().length;
		        console.log("My charcount ",charCount,this);
		        if(charCount<'.$params->required_letters.'){
		            addToCart = false;
		            jQuery(this).css("border-color", "red");
		            jQuery(this).removeClass("valid");
		            jQuery(this).addClass("required invalid");
		        } else {
		            jQuery(this).removeClass("required invalid");
		            jQuery(this).addClass("valid");
		            jQuery(this).css("border-color", "green");
		            console.log("Set Green ",this);
		        }
		    });
		    
		    var button;

			event.data = {};
		    event.data.cartform = formProduct;

            var oldText = "";
            button = iStraxx.toggleAddToCartButton(addToCart, event);
            console.log("My Button",button);
            if(typeof button[0] !== "undefined"){
                if(addToCart){
                    oldText = button[0].innerHTML;
                    button[0].innerHTML=Virtuemart.BtncartAddToo;
                    console.log("Enable AddToCartButton");
                } else {
                button[0].innerHTML=oldText;
                    console.log("Disable AddToCartButton");
                }
            }
		        
		};
};
jQuery("body").on("updateVirtueMartProductDetail", test);
jQuery(document).ready(test);';
vmJsApi::addJScript('textinput',$j);

?>