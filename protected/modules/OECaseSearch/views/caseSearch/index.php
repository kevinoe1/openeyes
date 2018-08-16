<?php
/* @var $this CaseSearchController
 * @var $fixedParams array
 * @var $params array
 * @var $paramList array
 * @var $patients CActiveDataProvider
 * @var $trial Trial
 * @var $form CActiveForm
 */
$this->pageTitle = 'OpenEyes - Case Search';
?>
<h1 class="badge"><?php echo $this->trialContext === null ? 'Advanced Search' : 'Adding Participants to Trial: ' . $this->trialContext->name; ?></h1>

<main class="main-event">

    <div class="element">
        <div>
            <?php $form = $this->beginWidget('CActiveForm', array('id' => 'search-form')); ?>
            <div class="sub-element">
                <?= $form->errorSummary($params) ?>
                <table id="param-list" class="cols-full">
                    <tbody>
                    <?php
                    if (isset($params)):
                        foreach ($params as $id => $param):?>
                            <?php $this->renderPartial('parameter_form', array(
                                'model' => $param,
                                'id' => $id,
                            )); ?>
                        <?php endforeach;
                    endif; ?>
                    </tbody>
                </table>
                <?php foreach ($fixedParams as $id => $param):
                    $this->renderPartial('fixed_parameter_form', array(
                        'model' => $param,
                        'id' => $id,
                    ));
                endforeach; ?>
            </div>
        </div>
        <div class="sub-element">
            <div class="new-param row field-row">
                <div class="cols-3 column">
                    <?php echo CHtml::dropDownList(
                        'Add Parameter: ',
                        null,
                        $paramList,
                        array('empty' => '- Add a parameter -', 'id' => 'param'));
                    ?>
                </div>
            </div>
            <div class="search-actions flex-layout flex-left">
                <div class="column">
                    <?php echo CHtml::submitButton('Search'); ?>
                </div>
                <div class="column end" style="padding-left: 5px">
                    <?php echo CHtml::button('Clear',
                        array('id' => 'clear-search', 'class' => 'button event-action cancel')) ?>
                </div>
            </div>
        </div>
        <?php $this->endWidget('search-form'); ?>
    </div>
    <div class="element">
        <div class="sub-element">
            <?php
            if ($patients->itemCount > 0):
                //Just create the widget here so we can render it's parts separately
                /** @var $searchResults CListView */
                $searchResults =
                    $this->createWidget(
                        'zii.widgets.CListView',
                        array(
                            'dataProvider' => $patients,
                            'itemView' => 'search_results',
                            'emptyText' => 'No patients found',
                        )
                    );
                $searchResults->pagerCssClass = 'oe-pager';
                $searchResults->renderPager();
                ?>
                <table id="case-search-results" class="cols-10">
                    <tbody class=" cols-full">
                    <?= $searchResults->renderItems(); ?>
                    </tbody>
                </table>
                <?php $searchResults->renderPager();
            endif;
            ?>
            <style>
                .oe-pager .page a {
                    color: white;
                };
                .oe-pager .next a {
                    color: white;
                };
                .oe-pager .prev a {
                    color: white;
                };
                .oe-pager .page a:visited {
                    color: white;
                };
                .oe-pager .next a:visited {
                    color: white;
                };
                .oe-pager .prev a:visited {
                    color: white;
                };
            </style>
        </div>
    </div>
    <?php if ($this->trialContext !== null): ?>
        <div class="cols-4 column end">
            <div class="box generic">
                <p><?php echo CHtml::link('Back to Trial',
                        Yii::app()->createUrl('/OETrial/trial/view/' . $this->trialContext->id)); ?></p>
            </div>
        </div>
    <?php endif; ?>
</main>

<script type="text/javascript">
    function addPatientToTrial(patient_id, trial_id) {
        var addSelector = '#add-to-trial-link-' + patient_id;
        var removeSelector = '#remove-from-trial-link-' + patient_id;
        $.ajax({
            url: '<?php echo Yii::app()->createUrl('/OETrial/trial/addPatient'); ?>',
            data: {id: trial_id, patient_id: patient_id, YII_CSRF_TOKEN: $('input[name="YII_CSRF_TOKEN"]').val()},
            type: 'POST',
            success: function (response) {
                $(addSelector).hide();
                $(removeSelector).show();
                $(removeSelector).parent('.result').css('background-color', '#fafad2');
            },
            error: function (response) {
                new OpenEyes.UI.Dialog.Alert({
                    content: "Sorry, an internal error occurred and we were unable to add the patient to the trial.\n\nPlease contact support for assistance."
                }).open();
            },
        });
    }

    function removePatientFromTrial(patient_id, trial_id) {
        var addSelector = '#add-to-trial-link-' + patient_id;
        var removeSelector = '#remove-from-trial-link-' + patient_id;
        $.ajax({
            url: '<?php echo Yii::app()->createUrl('/OETrial/trial/removePatient'); ?>',
            data: {id: trial_id, patient_id: patient_id, YII_CSRF_TOKEN: $('input[name="YII_CSRF_TOKEN"]').val()},
            type: 'POST',
            success: function (response) {
                $(removeSelector).hide();
                $(addSelector).show();
                $(addSelector).parent('.result').css('background-color', '#fafafa');
            },
            error: function (response) {
                new OpenEyes.UI.Dialog.Alert({
                    content: "Sorry, an internal error occurred and we were unable to remove the patient from the trial.\n\nPlease contact support for assistance."
                }).open();
            }
        });
    }

    function removeParam(elm) {
        $(elm).parents('.parameter').remove();
    }

    function refreshValues(elm) {
        if ($(elm).val() === 'BETWEEN') {
            // Display the two text fields.
            $(elm).closest('.js-case-search-param').find('.single-value').find('input').val('');
            $(elm).closest('.js-case-search-param').find('.dual-value').show();
            $(elm).closest('.js-case-search-param').find('.dual-value').css("display", "inline-block");
            $(elm).closest('.js-case-search-param').find('.single-value').hide();
        }
        else {
            // Display the single text field
            $(elm).closest('.js-case-search-param').find('.dual-value').find('input').val('');
            $(elm).closest('.js-case-search-param').find('.dual-value').hide();
            $(elm).closest('.js-case-search-param').find('.single-value').show();
            $(elm).closest('.js-case-search-param').find('.single-value').css("display", "inline-block");
        }
    }


    $(document).ready(function () {
        //null coallese the id of the last parameter
        var parameter_id_counter = $('.parameter').last().attr('id') || -1;
        $('#param').on('change', function () {
            var dropDown = this;
            if (!dropDown.value) {
                return;
            }
            parameter_id_counter++;
            $.ajax({
                url: '<?php echo $this->createUrl('caseSearch/addParameter')?>',
                data: {param: dropDown.value, id: parameter_id_counter},
                type: 'GET',
                success: function (response) {
                    $('#param-list tbody').append(response);
                    dropDown.value = '';
                }
            });
        });

        $('#clear-search').click(function () {
            $.ajax({
                url: '<?php echo $this->createUrl('caseSearch/clear')?>',
                type: 'GET',
                success: function () {
                    $('#case-search-results').children().remove();
                    $('#param-list').children().remove();
                }
            });
        });
    });
</script>


<?php
$assetPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.assets'), false, -1);
Yii::app()->clientScript->registerScriptFile($assetPath . '/js/toggle-section.js');
?>

