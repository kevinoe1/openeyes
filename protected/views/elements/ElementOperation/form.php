<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

if (!$model->site_id) {
	$model->site_id = Yii::app()->request->cookies['site_id']->value;
}

if (!$model->eye_id) {
	$model->eye_id = 1;
}
?>
					<script type="text/javascript" src="/js/element_operation.js"></script>
					<h4>Operation details</h4>

					<?php echo $form->radioButtons($model, 'eye_id', 'eye');?>

					<?php $form->widget('application.widgets.ProcedureSelection',array(
						'element' => $model,
						'newRecord' => $newRecord,
						'durations' => true
					));
					?>

					<?php echo $form->radioBoolean($model, 'consultant_required')?>
					<?php echo $form->radioButtons($model, 'anaesthetic_type_id', 'anaesthetic_type');?>
					<?php echo $form->radioBoolean($model, 'overnight_stay')?>
					<?php echo $form->dropDownList($model, 'site_id', CHtml::listData(Site::model()->findAll(array('order' => 'short_name')), 'id', 'short_name'))?>
					<?php echo $form->radioButtons($model, 'priority_id', 'priority')?>
					<?php echo $form->datePicker($model, 'decision_date', array('maxDate' => 'today'), array('style'=>'width: 110px;'))?>
					<?php echo $form->textArea($model, 'comments', array('rows'=>4,'cols'=>50))?>
