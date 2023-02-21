<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 13.12.17
 * Time: 16:37
 */

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\SendData */

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use kartik\icons\Icon;
use yii\widgets\MaskedInput;


$this->title = 'Мы Вам перезвоним';
//$this->params['breadcrumbs'][] = $this->title;
?>

<h2 class="red"><?= Html::encode($this->title) ?></h2>
<p>Пожалуйста, отправьте Ваше Имя и номер телефона, мы с Вами свяжемся в ближайшее время.</p>
<div class="divider"></div>

<?php $form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
]); ?>

<h5>Контактная информация</h5>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('full_name'), 'class' => 'form-control input-custom'])->label(false) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'phone')->widget(MaskedInput::className(), ['mask' => '+7(999) 999-99-99', 'clientOptions' => [ 'removeMaskOnSubmit' => true, ], 'options' => ['class' => 'form-control input-custom', 'placeholder' => $model->getAttributeLabel("phone")]])->label(false)  ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'email')->widget(MaskedInput::className(), ['clientOptions' => ['alias' => 'email'], 'options' => ['class' => 'form-control input-custom', 'placeholder' => $model->getAttributeLabel("email")]])->label(false) ?>
        </div>
    </div>

    <div class="box-footer">
        <div class="pull-left">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'action', 'value' => 'save',]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
