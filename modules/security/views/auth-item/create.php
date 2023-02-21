<?php

use yii\helpers\Html;
use SysProd\adminlte\widgets\Box;
use kartik\widgets\AlertBlock;


/* @var $this yii\web\View */
/* @var $model app\modules\security\models\AuthItem */
/* @var array $items */
/* @var array $children */

$this->title = \Yii::t('app', 'Add «{attribute}»', ['attribute' => \Yii::t('app', 'Access rights')]);
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Security')];
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Access rights'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="auth-item-update">

    <?= AlertBlock::widget([
        'useSessionFlash' => true,
        'type' => AlertBlock::TYPE_GROWL,
    ]);
    ?>

    <?php Box::begin([
        'type' => Box::TYPE_SUCCESS,
        'title' => Yii::t('app', 'Access rights'),
        'footer' => false
    ]); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
        'children' => $children,
    ]) ?>

    <?php Box::end(); ?>

</div>