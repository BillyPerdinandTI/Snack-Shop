<?php
/**
 *
 *
 * @package    VirtueMart
 * @subpackage state
 * @author Valerie Isaksen, Max Milbers
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - Copyright (C) 2004 - 2024 Virtuemart Team. All rights reserved. VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 10686 2022-07-01 13:01:24Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$adminTemplate = VMPATH_ROOT . '/administrator/templates/vmadmin/html/com_virtuemart/';
JLoader::register('vmuikitAdminUIHelper', $adminTemplate . 'helpers/vmuikit_adminuihelper.php');
vmuikitAdminUIHelper::startAdminArea($this);

$states = vmText::_('COM_VIRTUEMART_STATE_S');
?>

	<form action="index.php?option=com_virtuemart&view=state" method="post" name="adminForm" id="adminForm">


		<div id="filterbox" class="filter-bar">
			<?php
			echo adminSublayouts::renderAdminVmSubLayout('filterbar',
				array(
					'search'=>array(
						'label'=>'COM_VIRTUEMART_STATE',
						'name'=>'filter_state',
						'value'=>vRequest::getVar('filter_state')
					),
					'resultsCounter'=>$this->pagination->getResultsCounter()
				));


			?>

		</div>


		<div id="editcell">
			<table class="uk-table uk-table-small uk-table-striped uk-table-responsive" >
				<thead>
				<tr>
					<th class="uk-table-shrink">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
					</th>
					<th>
						<?php echo $this->sort('state_name') ?>
					</th>
					<?php /* TODO not implemented				    <th>
				<?php echo vmText::_('COM_VIRTUEMART_ZONE_ASSIGN_CURRENT_LBL'); ?>
				</th> */ ?>
					<th>
						<?php echo $this->sort('state_2_code') ?>
					</th>
                    <th>
						<?php echo $this->sort('state_3_code') ?>
					</th>
					<th class="uk-table-shrink uk-text-center@m">
						<?php echo $this->sort('published' , 'COM_VIRTUEMART_PUBLISHED') ?>
					</th>
					<th class="uk-table-shrink uk-text-center@m">
						<?php echo $this->sort('virtuemart_state_id', 'COM_VIRTUEMART_ID') ?>
					</th>
				</tr>
				</thead>
				<?php
				$k = 0;
				for ($i=0, $n=count( $this->states ); $i < $n; $i++) {
					$row = $this->states[$i];

					$checked = JHtml::_('grid.id', $i, $row->virtuemart_state_id);
					$published = $this->gridPublished( $row, $i );
					$editlink = JROUTE::_('index.php?option=com_virtuemart&view=state&task=edit&virtuemart_state_id[]=' . $row->virtuemart_state_id);
					?>
					<tr class="row<?php echo $k ; ?>">
						<td >
							<?php echo $checked; ?>
						</td>
						<td >
							<?php
							$prefix="COM_VIRTUEMART_STATE_";
							$state_string= vmText::_($prefix.$row->state_3_code); ?>
							<a href="<?php echo $editlink; ?>"><?php echo $row->state_name ?> </a>&nbsp;
							<?php
							$lang =vmLanguage::getLanguage();
							if ($lang->hasKey($prefix.$row->state_3_code)) {
								echo "(".$state_string.") ";
							}
							?>


						</td>
						<?php /* TODO not implemented				<td align="left">
			<?php echo $row->virtuemart_worldzone_id; ?>
		</td> */ ?>
						<td>
							<?php echo $row->state_2_code; ?>
						</td>
						<td>
							<?php echo $row->state_3_code ; ?>
						</td>

						<td class="uk-text-center@m">
							<?php echo $published; ?>
						</td>
						<td class="uk-text-center@m">
							<?php echo $row->virtuemart_state_id; ?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
        <input type="hidden" name="virtuemart_country_id" value="<?php echo $this->virtuemart_country_id; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['filter_order_Dir']; ?>"/>
		<input type="hidden" name="filter_order" value="<?php echo $this->lists['filter_order']; ?>"/>
		<input type="hidden" name="option" value="com_virtuemart"/>
		<input type="hidden" name="controller" value="state"/>
		<input type="hidden" name="view" value="state"/>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0"/>
		<?php echo JHtml::_('form.token'); ?>
	</form>


<?php vmuikitAdminUIHelper::endAdminArea(); ?>