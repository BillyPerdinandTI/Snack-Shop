<?php // no direct access
defined('_JEXEC') or die('Restricted access');



?>
<!--BEGIN Search Box -->
<form action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&search=true&limitstart=0&virtuemart_category_id='.$category_id.$set_Itemid ); ?>" method="get">
<div class="search<?php echo $params->get('moduleclass_sfx'); ?>">
	<?php $output = '<input name="keyword" id="mod_virtuemart_search" maxlength="'.$maxlength.'" placeholder="'.$text.'" class="inputbox'.$moduleclass_sfx.'" type="text" size="'.$width.'" />';
 $image = JURI::base() . $imagepath;

			if ($button) :
			    if ($imagebutton && $imagepath) :
			        $button = '<input style="vertical-align:middle" type="image" value="'.$button_text.'" class="button'.$moduleclass_sfx.'" src="'.$image.'" onclick="this.form.keyword.focus();"/>';
			    else :
			        $button = '<input type="submit" value="'.$button_text.'" class="button'.$moduleclass_sfx.'" onclick="this.form.keyword.focus();"/>';
			    endif;
		

			switch ($button_pos) :
			    case 'top' :
				    $button = $button.'<br />';
				    $output = $button.$output;
				    break;

			    case 'bottom' :
				    $button = '<br />'.$button;
				    $output = $output.$button;
				    break;

			    case 'right' :
				    $output = $output.$button;
				    break;

			    case 'left' :
			    default :
				    $output = $button.$output;
				    break;
			endswitch;
			endif;
			
			echo $output;
?>
</div>
</form>

<!-- End Search Box -->