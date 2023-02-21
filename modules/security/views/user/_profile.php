<?php

/* @var $this yii\web\View */
/* @var $profile app\modules\staff\models\Staff */
/* @var $phonesForUser app\modules\data\models\Phone */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use yii\widgets\MaskedInput;

use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\icons\Icon;

use wbraganca\dynamicform\DynamicFormWidget;

use app\modules\counterparty\models\Company;
use app\modules\counterparty\models\Counterparty;
use app\modules\counterparty\models\Functions;
use app\modules\counterparty\models\Departments;

$defaultPhoneMSG = \Yii::t('app','It should be noted the default phone for the user');
$script = <<< JS


jQuery("form#{$profile->formName()} .dynamicform_wrapper").on("afterInsert", function(e, item) {

        var \$hasInputmask = $(this).find('[data-plugin-inputmask]');
        if (\$hasInputmask.length > 0) {
            \$hasInputmask.each(function() {
                $(this).inputmask('remove');
                $(this).inputmask(eval($(this).attr('data-plugin-inputmask')));
            });
        }

});

jQuery("form#{$profile->formName()} .dynamicform_wrapper").on("change", ".default_phone_check", function(e, item) {

    // разрешить выделить только один checkbox
    if( this.checked ){
    \$checked = $(".default_phone_check").filter('input:checked');
                if(\$checked.length >= 2){
                // поиск всех элементов "input:checked" и убрать все "checked"
                \$checked.each(function(indx) {
                    this.checked = false;
                });
                 this.checked = true;   // отметить выбранный элмент
                 }
                    }else{
            alert('{$defaultPhoneMSG}');
            }
});


JS;

$this->registerJs($script);
?>

<div class="panel-body"> <!-- start:panel-body-->
    <?php $form = ActiveForm::begin([
        'id'=>$profile->formName(),
        'enableClientValidation' => true
    ]); ?>
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $form->field($profile, 'last_name', ['showLabels'=>false])->textInput(['maxlength' => true]) ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $form->field($profile, 'first_name', ['showLabels'=>false])->textInput(['maxlength' => true]) ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $form->field($profile, 'patronymic', ['showLabels'=>false])->textInput(['maxlength' => true]) ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $form->field($profile, 'gender', ['showLabels'=>false])->widget( Select2::classname(), [ 'data' => $profile->genderList, 'hideSearch' => true,]) ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
<!--            --><?//= $form->field($profile, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?php
            // показывать кнопку только администраторам
            if( Yii::$app->user->can('selected-company')){
                ?>
                <?= $form->field($profile, 'company_id', ['showLabels'=>false])->widget(
                Select2::classname(), [
                'data' => ArrayHelper::map(Company::find()->all(), 'id_company', 'name'),
//                'disabled' => false,
                'options' => [
                    'placeholder'   => $profile->getAttributeLabel("company_id").' ...',
                    'title'         => $profile->getAttributeLabel("company_id"),
                    'onchange' => '
                $.post( "'.Yii::$app->urlManager->createUrl("/counterparty/counterparty/lists?id=").'"+$(this).val(), function(data){
                    $("select#staff-counterparty_id").html( data );
                    $("#select2-staff-counterparty_id-container").attr("title","'.$profile->getAttributeLabel("counterparty_id").' ...").text("'.$profile->getAttributeLabel("counterparty_id").' ...");

                    $("#select2-staff-department_id-container").attr("title","'.$profile->getAttributeLabel("department_id").' ...").text("'.$profile->getAttributeLabel("department_id").' ...");
                    $("#staff-department_id").html("<option value=\'\'></option><option> - </option>");

                    $("#select2-staff-function_id-container").attr("title","'.$profile->getAttributeLabel("function_id").' ...").text("'.$profile->getAttributeLabel("function_id").' ...");
                    $("#staff-function_id").html("<option value=\'\'></option><option> - </option>");
                });',
                ],
                'pluginOptions' => [
//                    'allowClear' => true,
//                    'minimumInputLength' => 3,
                ],

            ]);
                ?>
            <? } ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $form->field($profile, 'counterparty_id', ['showLabels'=>false])->widget(
                Select2::classname(), [
                'data' => ArrayHelper::map(Counterparty::find()->where(['company_id' => $profile->company_id])->all(), 'id', 'short_name'),
                'options' => [
                    'placeholder'   => $profile->getAttributeLabel("counterparty_id").' ...',
                    'title'         => $profile->getAttributeLabel("counterparty_id"),
                    'onchange' => '
                $.post( "'.Yii::$app->urlManager->createUrl("/counterparty/departments/lists?id=").'"+$(this).val(), function(data){
                    $("select#staff-department_id").html( data );
                    $("#select2-staff-department_id-container").attr("title","'.$profile->getAttributeLabel("department_id").' ...").text("'.$profile->getAttributeLabel("department_id").' ...");

                    $("#select2-staff-function_id-container").attr("title","'.$profile->getAttributeLabel("function_id").' ...").text("'.$profile->getAttributeLabel("function_id").' ...");
                    $("#staff-function_id").html("<option value=\'\'></option><option> - </option>");
                });',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],

            ]); ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $form->field($profile, 'department_id', ['showLabels'=>false])->widget(
                Select2::classname(), [
                'data' => ArrayHelper::map(Departments::find()->where(['counterparty_id' => $profile->counterparty_id])->all(), 'id_department', 'name_department'),
                'options' => [
                    'placeholder'   => $profile->getAttributeLabel("department_id").' ...',
                    'title'         => $profile->getAttributeLabel("department_id"),
                    'onchange' => '
                $.post( "'.Yii::$app->urlManager->createUrl("/counterparty/functions/lists?id=").'"+$(this).val(), function(data){
                    $("select#staff-function_id").html( data );
                    $("#select2-staff-function_id-container").attr("title","'.$profile->getAttributeLabel("function_id").' ...").text("'.$profile->getAttributeLabel("function_id").' ...");
                });',
                ],
                'pluginOptions' => [
                    'allowClear' => true,

                ],

            ]); ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $form->field($profile, 'function_id', ['showLabels'=>false])->widget(
                Select2::classname(), [
                'data' => ArrayHelper::map(Functions::find()->where(['department_id' => $profile->department_id])->all(),'id_function','name_function'),
                'options' => [
                    'placeholder'   => $profile->getAttributeLabel("function_id").' ...',
                    'title'         => $profile->getAttributeLabel("function_id"),
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],

            ]); ?>
        </div>
    </div><!-- end:row  -->


    <div class="row"> <!-- start:row -->
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => 10, // the maximum times, an element can be cloned (default 999)
        'min' => 1, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $phonesForUser[0],
        'formId' => $profile->formName(),
        'formFields' => [
            'id_phone',
            'type_phone',
            'status_phone',
            'phone_reference',
            'default_phone',
        ],
    ]); ?>

    <div class="panel-body">
        <div class="panel panel-success">
            <div class="panel-heading">
                <i class="fa fa-phone-square"></i> <?=\Yii::t('app', 'Phone numbers') ?>
                <?= Html::submitButton( Icon::show('plus', ['class' => 'fa-lg']), ['name' => 'addRow', 'title' => \Yii::t('app', 'Add «{attribute}»',['attribute' => \Yii::t('app', 'Phone')]), 'value' => 'true', 'class' => 'add-item btn btn-success btn-xs pull-right']) ?>
            </div>
            <div class="panel-body container-items"><!-- start:panel-body for tangibles-new-data -->

                <?php foreach ($phonesForUser as $index => $phone) : ?>

                    <div class="item panel panel-default"><!-- start:item for tangibles-new-data -->

                        <div class="panel-body">
                            <?php
                            // necessary for update action.
                            if (!$phone->isNewRecord) {
                                echo Html::activeHiddenInput($phone, "[{$index}]id");
                            }
                            ?>

                            <div class="row"> <!-- .row -->

                                <div class="col-sm-3">
                                    <!--    Select search type_phone-->
                                    <?= $form->field($phone, "[{$index}]type_phone", ['showLabels'=>false])->widget(
                                        Select2::classname(), [
                                        'data' => $phone->getTypeList(),
                                        'hideSearch' => true,
                                        'options' => [
                                            'placeholder' => $phone->getAttributeLabel("type_phone").' ...',
                                        ],
                                        'pluginOptions' => [
//                                            'allowClear' => true
                                        ],
                                    ]); ?>
                                </div>

                                <div class="col-sm-3">
                                    <!--    Select search status_phone-->
                                    <?= $form->field($phone, "[{$index}]status_phone", ['showLabels'=>false])->widget(
                                        Select2::classname(), [
                                        'data' =>$phone->getStatusList(),
                                        'hideSearch' => true,
                                        'options' =>
                                            [
                                                'class'=>'status_phone_id',
                                                'placeholder' => $phone->getAttributeLabel("status_phone").' ...',
                                            ],
                                        'pluginOptions' => [
//                                            'allowClear' => true
                                        ],
                                    ]); ?>
                                </div>

                                <div class="col-sm-3">
                                    <!--    Select search phone_reference-->
                                    <?= $form->field($phone, "[{$index}]phone_reference", ['showLabels'=>false])->widget(MaskedInput::className(), ['mask' => '+7(999) 999-99-99', 'clientOptions' => [ 'removeMaskOnSubmit' => true, ], 'options' => ['class' => 'form-control', 'placeholder' => $phone->getAttributeLabel("phone_reference").' ...',]])->label(false)  ?>
                                </div>

                                <div class="col-sm-2"  >
                                    <!--    Select search phone_reference-->
                                    <?= $form->field($phone, "[{$index}]default_phone")->checkBox([ 'class' => 'default_phone_check', 'label' => '', 'title' => \Yii::t('app', 'Basic phone')], false)->label(\Yii::t('app', 'Basic')); ?>
                                </div>

                                <div class="col-sm-1">
                                    <!--    Select button remove-->
                                    <?= Html::button(Icon::show('minus', ['class' => 'fa']), ['class' => 'remove-item btn btn-danger btn-xs', 'data-target' => "receipt-detail-".$index, 'title' => \Yii::t('app', 'Remove this item')]) ?>
                                </div>

                            </div><!-- end:row -->
                        </div>
                    </div><!-- end:item for tangibles-new-data -->
                <?php endforeach; ?>
            </div><!-- end:panel-body for tangibles-new-data -->
        </div>
    </div>
    <?php DynamicFormWidget::end(); ?>
    </div><!-- end:row  -->

    <div class="box-footer">
        <div class="pull-left">
            <?= Html::a(Icon::show('arrow-circle-left', ['class' => 'fa-lg']).\Yii::t('app', 'Back'), [ "/security/user/index", ], ['class' => 'btn btn-danger']) ?>
        </div>
        <div class="pull-right">
            <?= Html::submitButton($profile->isNewRecord ? Icon::show('save', ['class' => 'fa-lg']).\Yii::t('app', 'Save and go back') : Icon::show('save', ['class' => 'fa-lg']).\Yii::t('app', 'Edit and go back'), ['class' => 'btn btn-warning', 'name' => 'action', 'value' => 'next',]) ?>
        <?= Html::submitButton($profile->isNewRecord ? Icon::show('save', ['class' => 'fa-lg']).\Yii::t('app', 'Save').' «'.\Yii::t('app', 'Profile').'»' : Icon::show('save', ['class' => 'fa-lg']).\Yii::t('app', 'Edit').' «'.\Yii::t('app', 'Profile').'»', ['class' => $profile->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'action', 'value' => 'save',]) ?>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>  <!-- .panel-body for tangibles-new-dock-->
