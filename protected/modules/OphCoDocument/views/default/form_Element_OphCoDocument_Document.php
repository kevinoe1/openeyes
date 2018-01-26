<div class="element-fields">
    <?php echo $form->dropDownList($element, 'event_sub_type',
        CHtml::listData(OphCoDocument_Sub_Types::model()->findAll(), 'id', 'name'),
        array(),
        array(),
        array(
            'label' => 4,
            'field' => 2,
        )); ?>
    <div class="row field-row">
        <div class="large-8 column">
            <label class="inline highlight">
                <input type="radio" name="upload_mode" value="single" <?php if($element->single_document_id >0){echo "checked";}?>>Single file
            </label>
            <label class="inline highlight">
                <input type="radio" name="upload_mode" value="double" <?php if($element->left_document_id > 0 || $element->right_document_id >0){echo "checked";}?>>Right/Left sides
            </label>
        </div>
    </div>
    <div class="row field-row" id="single_document_uploader">
        <div class="large-8 column" id="single_document_id_row">
            <?php $this->generateFileField($element, 'single_document'); ?>
        </div>
    </div>
    <div id="double_document_uploader">
        <div class="row field-row">
            <div class="large-6 column">
                <b>RIGHT</b>
            </div>
            <div class="large-6 column">
                <b>LEFT</b>
            </div>
        </div>
        <div class="row field-row" id="double_document_uploader">
            <div class="large-6 column" id="right_document_id_row">
                <?php $this->generateFileField($element, 'right_document'); ?>
            </div>
            <div class="large-6 column" id="left_document_id_row">
                <?php $this->generateFileField($element, 'left_document'); ?>
            </div>
        </div>
    </div>
    <div id="showUploadStatus" style="height:10px;color:#ffffff;font-size:8px;text-align:right;width:0px;background-color:#22b24c"></div>
    <?php
        foreach (array('single_document_id', 'left_document_id', 'right_document_id') as $index_key) {
            if($element->{$index_key} > 0)
            {
                echo "<input type=\"hidden\" name=\"Element_OphCoDocument_Document[".$index_key."]\" id=\"Element_OphCoDocument_Document_".$index_key."\" value=\"".$element->{$index_key}."\">";
            }
        }
    ?>
    <div class="upload-info" style="font-size:13px">
        <span class="has-tooltip fa fa-info-circle left" style="margin:3px 3px 0px 0px"></span> The following file types are accepted: <?php echo implode(', ', $this->getAllowedFileTypes()); ?>
        (Maximum size: <?=$this->getMaxDocumentSize();?> MB)
    </div>
    
    <div class="row field-row" style="padding-top:10px;">
        <div class="large-8 column">
            <label style="font-weight:bold">Comments:</label>
            <?php
                echo $form->textArea($element, 'comment', array('rows' => '5', 'cols' => '80', 'class' => 'autosize', 'nowrapper' => true), false);
            ?>
        </div>
    </div>
</div>
