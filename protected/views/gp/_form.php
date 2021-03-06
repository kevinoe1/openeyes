<?php
/* @var $this GpController */
/* @var $model Contact */
/* @var $form CActiveForm */
?>
<div class="form">
    <?php
    \Yii::app()->assetManager->RegisterScriptFile('js/Gp.js');
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'gp-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => true,
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <table class="standard row">
        <tbody>
        <tr>
            <td>
                <div class="column"><?php echo $form->labelEx($model, 'title'); ?></div>
            </td>
            <td>
                <?php echo $form->textField($model, 'title', array('size' => 30, 'maxlength' => 20)); ?>
                <?php echo $form->error($model, 'title'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model, 'first_name'); ?>
            </td>
            <td>
                <?php echo $form->textField($model, 'first_name', array('size' => 30, 'maxlength' => 100)); ?>
                <?php echo $form->error($model, 'first_name'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model, 'last_name'); ?>
            </td>
            <td>
                <?php echo $form->textField($model, 'last_name', array('size' => 30, 'maxlength' => 100)); ?>
                <?php echo $form->error($model, 'last_name'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model, 'primary_phone'); ?>
            </td>
            <td>
                <?php echo $form->telField($model, 'primary_phone', array('size' => 15, 'maxlength' => 20)); ?>
                <?php echo $form->error($model, 'primary_phone'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model, 'Role'); ?>
            </td>
            <td>
                <?php echo $form->error($model, 'contact_label_id'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'contact_label_id',
                    'id' => 'autocomplete_contact_label_id',
                    'source' => "js:function(request, response) {
                                $.getJSON('/gp/contactLabelList', {
                                   term : request.term
                                }, response);
                        }",
                    'options' => array(
                        'select' => "js:function(event, ui) {
                                removeSelectedContactLabel();
                                addItem('selected_contact_label_wrapper', ui);   
                                $('#autocomplete_contact_label_id').val('');
                                return false;
                                }",
                        'response' => 'js:function(event, ui){
                          if(ui.content.length === 0){
                            $("#no_contact_label_result").show();
                          } else {
                            $("#no_contact_label_result").hide();
                          }
                        }',
                    ),
                    'htmlOptions' => array(
                        'placeholder' => 'Search Roles',
                    ),
                ));
                ?>
            </td>
        </tr>
        <tr id="selected_contact_label_wrapper" style="display: <?php echo $model->label ? '' : 'none' ?>">
            <td></td>
            <td>
                <div>
                              <span class="name">
                                <?php echo isset($model->label) ? $model->label->name : ''; ?>
                              </span>
                    <?php echo CHtml::hiddenField('Contact[contact_label_id]',
                         $model->contact_label_id, array('class' => 'hidden_id')); ?>
                </div>
            </td>
            <td>
                <a href="javascript:void(0)" class="oe-i trash removeReading remove"></a>
            </td>
        </tr>
        <tr id="no_contact_label_result" style="display:none">
            <td></td>
            <td>
                <div>
                    <div class="selected_gp">No result</div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="align-right">
                <?php if ($context !== 'AJAX') {
                    echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save');
                } else {
                    echo CHtml::ajaxButton('Add',
                        Yii::app()->controller->createUrl('gp/create', array('context' => 'AJAX')),
                        array(
                            'type' => 'POST',
                            'error' => 'js:function(error){
                 new OpenEyes.UI.Dialog.Alert({
                 content: "First name and Last name cannot be blank."
                }).open();
              }',
                            'success' => 'js:function(event){
                 removeSelectedGP();
                 addGpItem("selected_gp_wrapper",event);
                 $("#gpdialog").closest(".ui-dialog-content").dialog("close");
              }',
                            'complete' => 'js:function(){
                  $("#gp_form")[0].reset();
            }',
                        )
                    );
                }
                ?>
            </td>
        </tr>
        </tbody>
    </table>
    <?php $this->endWidget(); ?>
</div><!-- form -->
