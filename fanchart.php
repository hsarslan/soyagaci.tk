<?php
namespace Fisharebest\Webtrees;

/**
 * webtrees: online genealogy
 * Copyright (C) 2015 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

use Zend_Session;

define('WT_SCRIPT_NAME', 'fanchart.php');
require './includes/session.php';

$controller = new FanchartController;

if (Filter::getBool('img')) {
	Zend_Session::writeClose();
	header('Content-Type: image/png');
	echo $controller->generateFanChart('png');

	return;
}

$controller
	->pageHeader()
	->addExternalJavascript(WT_AUTOCOMPLETE_JS_URL)
	->addInlineJavascript('
		autocomplete();
		var WT_FANCHART = (function() {
			jQuery("area")
				.click(function (e) {
					e.stopPropagation();
					e.preventDefault();
					var target = jQuery(this.hash);
					target
						// position the menu centered immediately above the mouse click position and
						// make sure it doesn’t end up off the screen
						.css({
							left: Math.max(0 ,e.pageX - (target.outerWidth()/2)),
							top:  Math.max(0, e.pageY - target.outerHeight())
						})
						.toggle()
						.siblings(".fan_chart_menu").hide();
				});
			jQuery(".fan_chart_menu")
				.on("click", "a", function(e) {
					e.stopPropagation();
				});
			jQuery("#fan_chart")
				.click(function(e) {
					jQuery(".fan_chart_menu").hide();
				});
			return "' . strip_tags($controller->root->getFullName()) . '";
		})();
	');

?>
<div id="page-fan">
	<h2><?php echo $controller->getPageTitle(); ?></h2>
	<form name="people" method="get" action="?">
		<input type="hidden" name="ged" value="<?php echo Filter::escapeHtml(WT_GEDCOM); ?>">
		<table class="table-options">
			<tbody>
				<tr>
					<th>
						<label for="rootid">
							<?php echo I18N::translate('Individual'); ?>
						</label>
					</th>
					<td>
						<input class="pedigree_form" data-autocomplete-type="INDI" type="text" name="rootid" id="rootid" size="3" value="<?php echo $controller->root->getXref(); ?>">
						<?php echo print_findindi_link('rootid'); ?>
					</td>
					<th>
						<label for="fan_style">
							<?php echo I18N::translate('Layout'); ?>
							</label>
					</th>
					<td>
						<?php echo select_edit_control('fan_style', $controller->getFanStyles(), null, $controller->fan_style); ?>
					</td>
					<th rowspan="2">
						<input type="submit" value="<?php echo I18N::translate('View'); ?>">
					</th>
				</tr>
				<tr>
					<th>
						<label for="generations">
							<?php echo I18N::translate('Generations'); ?>
						</label>
					</th>
					<td>
						<?php echo edit_field_integers('generations', $controller->generations, 2, 9); ?>
					</td>
					<th>
						<label for="fan_width">
							<?php echo I18N::translate('Zoom'); ?>
						</label>
					</th>
					<td>
						<input type="text" size="3" id="fan_width" name="fan_width" value="<?php echo $controller->fan_width; ?>"> %
					</td>
				</tr>
			</tbody>
		</table>
	</form>
<?php

if ($controller->error_message) {
	echo '<p class="ui-state-error">', $controller->error_message, '</p>';
	
	return;
}

if ($controller->root) {
	echo '<div id="fan_chart">', $controller->generateFanChart('html'), '</div>';
}
echo '</div>';
