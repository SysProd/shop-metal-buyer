<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\security\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use yii\bootstrap\Modal;

use SysProd\grid\GridView;
use SysProd\dynagrid\DynaGrid;
//use kartik\grid\GridView;

use kartik\widgets\AlertBlock;
use kartik\icons\Icon;
use kartik\editable\Editable;
use kartik\depdrop\DepDrop;

use app\modules\security\models\User;
use app\modules\security\models\AuthItem;

$this->title = \Yii::t('app', 'User List');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Security')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <?php
    Modal::begin([
        'size' => Modal::SIZE_LARGE,
        'header' => '<h4 class="text-left" style="color: #000; font-size: 20px; font-weight: 500;"></h4>',
        'closeButton' => false,
        'toggleButton' => false,
        'options' => [
            'id' => 'modal-users',
            'tabindex' => false // important for Select2 to work properly
        ],
    ]);
    echo "<div id='modalContent-users'> </div>";
    Modal::end();

    echo AlertBlock::widget([
        'useSessionFlash' => true,
        'type' => AlertBlock::TYPE_GROWL,
    ]);
    ?>

    <?
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute'=>'username',
            'format'  => 'html',
            'value' => function($model){
                /* @var $model app\modules\security\models\User */
                return   Html::a(Icon::show('pencil-square', ['class' => 'fa-lg']).$model->username, [ "/security/user/update", "id" => $model->id], [ 'title' => 'Изменить данные Пользователя', ]);
            }
        ],
        [
            'attribute'=>'profile_id',
            'value'=>function ($model) {
                /* @var $model app\modules\security\models\User */
                return empty($model->profile) ? null :$model->profile->shortName;
            },
        ],
        [
            'attribute'=>'email',
            'format'=>'email'
        ],
        [
            'attribute'=>'roleName',
            'value'=>function ($model) {
                /* @var $model app\modules\security\models\User */
                return $model->roleNameString;
            },
        ],
        [
            'attribute'=>'confirmed_reg',
            'value'=>function ($model) {
                /* @var $model app\modules\security\models\User */
                return empty($model->confirmed_reg) ? null :$model->confirmedReg0->shortName;
            },
        ],
        [
            'attribute'=>'created_at',
            'format'=>'datetime',
            'filterWidgetOptions'=>
                [
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'format' => 'dd-mm-yyyy'
                    ]
                ],
            'filterType'=>GridView::FILTER_DATE,     // Фильтр для таблицы
            'visible' => \Yii::$app->user->can('users-show_createdAt'),
        ],
        [
            'attribute'=>'created_by',
            'value'=>function ($model) {
                /* @var $model app\modules\security\models\User */
                return empty($model->created_by) ? null :$model->createdBy->shortName;
            },
            'visible' => \Yii::$app->user->can('users-show_createdBy'),
        ],
        [
            'attribute'=>'updated_at',
            'format'=>'datetime',
            'filterWidgetOptions'=>
                [
                    'model' => $searchModel,
                    'attribute' => 'updated_at',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'format' => 'dd-mm-yyyy'
                    ]
                ],
            'filterType'=>GridView::FILTER_DATE,     // Фильтр для таблицы
            'visible' => \Yii::$app->user->can('users-show_updatedAt'),
        ],
        [
            'attribute'=>'updated_by',
            'value'=>function ($model) {
                /* @var $model app\modules\security\models\User */
                return empty($model->updated_by) ? null :$model->updatedBy->shortName;
            },
            'visible' => \Yii::$app->user->can('users-show_updatedBy'),
        ],
        [
            'attribute'=>'status_system',
            'options' => ['width' => '6%'],
            'format'=>'html',
            'filterWidgetOptions'=>
                [
                    // setting filter widget
                    'data' => $searchModel->statusSystemList,
                    'options' => ['placeholder' => ' ...'],
                    'hideSearch' => true,
                    'pluginOptions' =>
                        [
                            'allowClear' => true,
                            'autoUpdateInput' => false,
                        ]
                ],
            'filterType'=>GridView::FILTER_SELECT2,     // Фильтр для таблицы
            'value'=>function ($model)
            {
                /* @var $model app\modules\security\models\User */
                return $model->statusSystemStyle;
            },
            'visible' => \Yii::$app->user->can('users-show_status'),
        ],
        [
            'class' => '\yii\grid\ActionColumn',
            'options' => ['width' => '10'],
            'template' => '{delete}',
            'buttons'=>[
                'delete'=>function($url, $model){
                    /* @var $model app\modules\security\models\User */
                    if(Yii::$app->user->can('users-absolute_delete')){
                        return Html::a(Icon::show('user-times', ['class' => 'fa-lg']), ['delete', 'id' => $model->id], [
                            'data' => [
                                'confirm' =>  \Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                            'title' => \Yii::t('app', 'Complete delete of «{attribute}» #{item}', ['attribute' => \Yii::t('app', 'user'), 'item' => Html::encode($model->username)]),
                        ]);
                    }elseif(Yii::$app->user->can('users-delete')){
                        return Html::a(Icon::show('trash', ['class' => 'fa-lg']), ['delete', 'id' => $model->id], [
                            'data' => [
                                'confirm' => \Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                            'title' => \Yii::t('app', 'Delete of «{attribute}» #{item}', ['attribute' => \Yii::t('app', 'user'), 'item' => Html::encode($model->username)]),
                        ]);
                    }else{
                        return null;
                    }
                },
            ]

        ],
    ];

    echo DynaGrid::widget([
        'columns' => $columns,
        'storage' => DynaGrid::TYPE_DB,
        'showPersonalize' => true,
        'allowThemeSetting' => false,
        'allowFilterSetting' => false,
        'allowSortSetting' => false,
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'export' => false,
            'hover' => true,
            'striped' => false,
            'rowOptions'    => function($model){
                /* @var $model app\modules\security\models\User */
                if      ( $model->status_system == User::STATUS_SYSTEM_ACTUAL   ){
                    return ['class'=>'default'];
                }else if( $model->status_system == User::STATUS_SYSTEM_DELETED  ){
                    return ['class'=>'remove-select'];
                }elseif ($model->status_system == User::STATUS_SYSTEM_BLOCKED   ){
                    return ['class' => 'blocked-select'];
                }elseif ($model->status_system == User::STATUS_SYSTEM_IRRELEVANT){
                    return ['class' => 'irrelevant-select'];
                }
            },
            'panel' => [
                'type' => GridView::TYPE_INFO,
                'heading' => Icon::show('user', ['class' => 'fa-lg']) . Html::encode($this->title),
                'after' => false,
                'before' => false,
            ],
            'toolbar' => [
                ['content' =>
/*                    Html::button(Icon::show('plus-circle'), [
                        'id' => 'ButtonCreate',
                        'value' => Url::toRoute(['/security/user/create']),
                        'class' => 'btn btn-warning btn-sm',
                        'title' => \Yii::t('app', 'Add «{attribute}»', ['attribute' => Yii::t('app', 'Shop')]),
                        'onclick' =>
                            '   $("#modal-users").modal("show")
                                        .find(".modal-header h4").text("' . \Yii::t('app', 'Adding data of «{attribute}»', ['attribute' => \Yii::t('app', 'user')]) . '").end()
                                        .find(".modal-dialog").removeClass().addClass("modal-dialog modal-lg").end()
                                        .find("#modalContent-users")
                                        .load($(this).attr("value"));
                                            ',
                    ]) . ' ' .
                    Html::button(Icon::show('search'), [
                        'id' => 'ButtonSearch',
                        'value' => Url::toRoute(['/security/user/search']),
                        'class' => 'btn btn-' . GridView::TYPE_DANGER . ' btn-sm',
                        'title' => \Yii::t('app', 'Add «{attribute}»', ['attribute' => Html::encode($this->title)]),
                        'onclick' =>
                            '   $("#modal-users").modal("show")
                                        .find(".modal-header h4").text("' . \Yii::t('app', 'Reset password') . '").end()
                                        .find(".modal-dialog").removeClass().addClass("modal-dialog modal-sm").end()
                                        .find("#modalContent-users")
                                        .load($(this).attr("value"));
                                            ',
                    ]) . ' ' .*/
                    Html::a(Icon::show('repeat'), ['index'], [
                        'class' => 'btn btn-default btn-sm',
                        'id' => 'refreshButton',
                        'title' => \Yii::t('app', 'Update'),
                    ])
                ],
//                \Yii::$app->user->can(AuthItem::ROLE_Admin) ? ['content' => '{dynagrid}'] : '',
//                        '{export}',
//                ['content' => '{dynagrid}'],
                '{toggleData}',
            ]
        ],
        'options' => ['id' => 'dynagrid-1'] // a unique identifier is important
    ]);

//    echo GridView::widget([
//        'dataProvider'  => $dataProvider,
//        'filterModel'   => $searchModel,
//        'pjax'          => true,
//        'export'        => false,
//        'hover'         => true,
//        'striped'       => false,
//        'rowOptions'    => function($model){
//            /* @var $model app\modules\security\models\User */
//            if      ( $model->status_system == User::STATUS_SYSTEM_ACTUAL   ){
//                return ['class'=>'default'];
//            }else if( $model->status_system == User::STATUS_SYSTEM_DELETED  ){
//                return ['class'=>'remove-select'];
//            }elseif ($model->status_system == User::STATUS_SYSTEM_BLOCKED   ){
//                return ['class' => 'blocked-select'];
//            }elseif ($model->status_system == User::STATUS_SYSTEM_IRRELEVANT){
//                return ['class' => 'irrelevant-select'];
//            }
//        },
//        'columns' => $columns,
//        'toolbar' => [
////            Html::a(Icon::show('plus',      ['class' => 'fa-lg']), ['create'],  ['class' => 'btn btn-success', 'title' => \Yii::t('app', 'Add «{attribute}»', ['attribute' => \Yii::t('app', 'user')])]).' '.
//            Html::a(Icon::show('repeat',    ['class' => 'fa-lg']), ['index'],   [
//                'class' => 'btn btn-default',
//                'title' => \Yii::t('app', 'Update'),
//            ]),
//            '{toggleData}',
//        ],
//        'panel'=>[
//            'type'=>GridView::TYPE_INFO,
//            'heading'=>Icon::show('user', ['class' => 'fa-lg']).Html::encode($this->title),
//            'afterOptions' => false,
//        ],
//    ]);

    ?>
</div>
