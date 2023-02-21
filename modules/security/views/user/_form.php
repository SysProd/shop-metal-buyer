<?php

/* @var $this yii\web\View */
/* @var $model app\modules\security\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var array $items */

/* @var array $role */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use yii\helpers\Url;
use kartik\icons\Icon;
use app\widgets\MultiSelect;
use kartik\checkbox\CheckboxX;
use app\modules\security\models\User;


?>

<div class="panel-body"> <!-- start:panel-body-->
    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
        'enableClientValidation' => true
    ]); ?>
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $model->isNewRecord ? $form->field($model, 'password')->passwordInput() : '' ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $model->isNewRecord ? $form->field($model, 'password_repeat')->passwordInput() : '' ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= Yii::$app->user->can('users-changes_status') ?
                $form->field($model, "status_system")->widget(Select2::classname(), ['data' => $model->statusSystemList, 'hideSearch' => true,])
                : ' ';
            ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $form->field($model, 'email')->widget(MaskedInput::className(), ['clientOptions' => ['alias' => 'email',],]) ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?=
            //          показывать элемент формы, только в форме создания
            $model->isNewRecord ?
                $form->field($model, "send_mail", ['showLabels' => false, 'options' => ['class' => 'form-group has-error']])->widget(
                    CheckboxX::classname(),
                    [
                        'initInputType' => CheckboxX::INPUT_CHECKBOX,
                        'autoLabel' => true,
                        'options' =>
                            [
                                'value' => $model->send_mail,
                                'title' => $model->getAttributeLabel("send_mail") . ' ...',
                            ],
                        'labelSettings' => [
                            'position' => CheckboxX::LABEL_LEFT
                        ],
                        'pluginOptions' => [
                            'useNative' => false,
                            'enclosedLabel' => true,
                            'threeState' => false,
                        ],
                    ])
                : ' ';
            ?>
        </div>
    </div><!-- end:row  -->
    <div class="row"> <!-- start:row -->
        <div class="col-sm-12">
            <?= $form->field($model, 'role')->widget(MultiSelect::className(), [
                'items' => $items,
                'selectedItems' => $role,
                'ajax' => false,
            ]) ?>
        </div>
    </div><!-- end:row  -->

    <div class="box-footer">
        <div class="pull-left">
            <?= Html::a(Icon::show('arrow-circle-left', ['class' => 'fa-lg']) . \Yii::t('app', 'Back'), ["/security/user/index",], ['class' => 'btn btn-danger']) ?>
        </div>
        <div class="pull-right">
            <?= Html::submitButton($model->isNewRecord ? Icon::show('save', ['class' => 'fa-lg']) . \Yii::t('app', 'Save and go back') : Icon::show('save', ['class' => 'fa-lg']) . \Yii::t('app', 'Edit and go back'), ['class' => 'btn btn-warning', 'name' => 'action', 'value' => 'next',]) ?>
            <?= Html::submitButton($model->isNewRecord ? Icon::show('save', ['class' => 'fa-lg']) . \Yii::t('app', 'Save') . ' «' . \Yii::t('app', 'Authentication data') . '»' : Icon::show('save', ['class' => 'fa-lg']) . \Yii::t('app', 'Edit') . ' «' . \Yii::t('app', 'Authentication data') . '»', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'action', 'value' => 'save',]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>  <!-- .panel-body for tangibles-new-dock-->