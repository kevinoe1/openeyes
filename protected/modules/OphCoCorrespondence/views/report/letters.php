<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */
?>
<h2>Letters report</h2>

<div class="row divider">
		<?php
		$form = $this->beginWidget('BaseEventTypeCActiveForm', array(
			'id'=>'report-form',
			'enableAjaxValidation'=>false,
			'layoutColumns'=>array('label'=>2,'field'=>10),
			'action' => Yii::app()->createUrl('/OphCoCorrespondence/report/downloadReport'),
		))?>

		<input type="hidden" name="report-name" value="Letters" />

  <table class="standard cols-full">
    <colgroup>
      <col class="cols-2">
    </colgroup>
    <tbody>
      <tr>
        <td>Phrases:</td>
        <td>
            <?php echo CHtml::textField('OphCoCorrespondence_ReportLetters[phrases][]','')?>
        </td>
        <td>
          <button type="submit" class="button green hint" id="add_letter_phrase">
            <span class="button-span button-span-blue">Add</span>
          </button>
        </td>
      </tr>
    </tbody>
  </table>

  <table class="standard cols-full">
    <colgroup>
      <col class="cols-2">
    </colgroup>
    <tbody>
    <tr>
      <td>Search method:</td>
      <td>
        <ul>
          <li>
            <input type="radio" name="OphCoCorrespondence_ReportLetters[condition_type]" id="condition_or" value="or" checked="checked" />
            <label for="condition_or">
              Must contain <strong>any</strong> of the phrases
            </label>
          </li>
          <li>
            <input type="radio" name="OphCoCorrespondence_ReportLetters[condition_type]" id="condition_and" value="and" />
            <label for="condition_and">
              Must contain <strong>all</strong> of the phrases
            </label>
          </li>
          <li>
            <input type="hidden" name="OphCoCorrespondence_ReportLetters[match_correspondence]" value="0" />
            <input type="checkbox" id="match_correspondence" name="OphCoCorrespondence_ReportLetters[match_correspondence]" value="1" checked="checked" />
            <label for="match_correspondence">
              Match correspondence
            </label>
          </li>
          <li>
            <input type="hidden" name="OphCoCorrespondence_ReportLetters[match_legacy_letters]" value="0" />
            <input type="checkbox" id="match_legacy_letters" name="OphCoCorrespondence_ReportLetters[match_legacy_letters]" value="1" checked="checked" />
            <label for="match_legacy_letters">
              Match legacy letters
            </label>
          </li>
        </ul>
      </td>
    </tr>
    </tbody>
  </table>

  <table class="standard  cols-full">
    <colgroup>
      <col class="cols-2">
      <col class="cols-4">
      <col class="cols-2">
      <col class="cols-4">
    </colgroup>
    <tbody>
    <tr>
      <td>Date from:</td>
      <td>
          <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
              'name' => 'OphCoCorrespondence_ReportLetters[start_date]',
              'options' => array(
                  'showAnim' => 'fold',
                  'dateFormat' => Helper::NHS_DATE_FORMAT_JS
              ),
              'value' => date('j M Y',strtotime('-1 year')),
          ))?>
      </td>
      <td>Date to:</td>
      <td>
          <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
              'name' => 'OphCoCorrespondence_ReportLetters[end_date]',
              'options' => array(
                  'showAnim' => 'fold',
                  'dateFormat' => Helper::NHS_DATE_FORMAT_JS
              ),
              'value' => date('j M Y'),
          ))?>
      </td>
    </tr>
    </tbody>
  </table>
  <table class="standard cols-full">
    <colgroup>
      <col class="cols-2">
      <col class="cols-4">
      <col class="cols-2">
      <col class="cols-4">
    </colgroup>
    <tr>
      <td>Author</td>
      <td>
          <?php if ( Yii::app()->getAuthManager()->checkAccess('Report', Yii::app()->user->id) ):?>
              <?php echo CHtml::dropDownList(
                  'OphCoCorrespondence_ReportLetters[author_id]',
                  '',
                  CHtml::listData(User::model()->findAll(
                      array('order' => 'first_name asc,last_name asc')),
                      'id',
                      'fullName'),
                  array('empty' => '--- Please select ---'))?>
          <?php else: ?>
              <?php
              $user = User::model()->findByPk(Yii::app()->user->id);
              echo CHtml::dropDownList(null, '',
                  array(Yii::app()->user->id => $user->fullName),
                  array('disabled' => 'disabled', 'readonly' => 'readonly', 'style'=>'background-color:#D3D3D3;') //for some reason the chrome doesn't gray out
              );
              echo CHtml::hiddenField('OphCoCorrespondence_ReportLetters[author_id]', Yii::app()->user->id);
              ?>
          <?php endif ?>
      </td>
      <td>Site</td>
      <td>
          <?php echo CHtml::dropDownList(
              'OphCoCorrespondence_ReportLetters[site_id]',
              '',
              Site::model()->getListForCurrentInstitution(),
              array('empty' => '--- Please select ---'))?>
      </td>
    </tr>
  </table>
  <table class="standard cols-full">
    <colgroup>
      <col class="cols-2">
    </colgroup>
    <tbody>
    <tr>
      <td>Status</td>
      <td>
          <?php
          $htmlOptions['template'] = '<span style="margin-right:15px;">{input} {label}</span>';
          $htmlOptions['separator'] = '';
          $htmlOptions['style'] = 'vertical-align: middle';
          echo CHtml::checkBoxList('OphCoCorrespondence_ReportLetters[statuses]',null,
              ['DRAFT' => 'Draft', 'PENDING' => 'Pending', 'COMPLETE' => 'Complete', 'FAILED' => 'Failed'], $htmlOptions); ?>
      </td>
    </tr>
    </tbody>

  </table>

    <?php $this->endWidget()?>

	<div class="errors alert-box alert with-icon" style="display: none">
		<p>Please fix the following input errors:</p>
		<ul>
		</ul>
	</div>

  <table class="standard cols-full">
    <tbody>
    <tr>
      <td>
        <div class="row flex-layout flex-right">
          <button type="submit" class="button green hint display-report" name="run">
            <span class="button-span button-span-blue">Display report</span>
          </button>
          &nbsp;
          <button type="submit" class="button green hint download-report" name="run">
            <span class="button-span button-span-blue">Download report</span>
          </button>
          <img class="loader" style="display: none;"
               src="<?php echo Yii::app()->assetManager->createUrl('img/ajax-loader.gif')?>" alt="loading..." />&nbsp;
        </div>
      </td>
    </tr>
    </tbody>
  </table>

	<div class="report-summary" style="display: none;">
	</div>
</div>
<script type="text/javascript">
	$('input[name="OphCoCorrespondence_ReportLetters[phrases][]"]').focus();
</script>