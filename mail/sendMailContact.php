<?php

use yii\helpers\Html;
use app\modules\security\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model app\models\SendData */

?>

<div class="password-reset" style="width:100%;padding:24px 0 16px 0;background-color:#f5f5f5;text-align:center">
    <div style="display:inline-block;width:90%;max-width:680px;min-width:280px;text-align:left;font-family:Roboto,Arial,Helvetica,sans-serif" >

        <div style="height:50px;background-color:rgb(112, 158, 183);padding-left:32px;font:bold 21px Arial,'Helvetica Neue',Helvetica,sans-serif;color:#fff" align="left">
            <b><?= Html::encode(\Yii::$app->name ) ?></b>
        </div>
        <div style="border-left:1px solid #f0f0f0;border-right:1px solid #f0f0f0">


            <div style="padding:24px 32px 32px 32px;background:#fff;border-right:1px solid #eaeaea;border-left:1px solid #eaeaea" dir="ltr">
                <div style="font-size:14px;line-height:18px;color:#444">
                    <p>
                    Привет, <b>Пользователь</b> системы!
                    </p>
                    <p>
                        Поступила новая заявка на звонок.
                    </p>
                    <p>
                        Данные нового пользователя:
                    <ul>
                        <li style="list-style-type: none;">Имя и Отчество: <b> <?= Html::encode($model->full_name )  ?> </b></li>
                        <?php if(!empty($model->phone)){ ?>
                            <li style="list-style-type: none;">Телефон: <b> <?= Html::a(Html::encode(Yii::$app->formatter->asPhoneFormatter($model->phone)),'tel:8'.Html::encode($model->phone)) ?></b></li>
                        <?php } ?>
                        <?php if(!empty($model->email)){ ?>
                            <li style="list-style-type: none;">E-mail: <b><?= Html::encode($model->email ) ?></b></li>
                        <?php } ?>
                    </ul>
                    </p>


                    <div style="min-height:32px;"></div>
                    <div  style="font-size:10px;" >
                        <p>
                            С уважением,<br />
                            Администрация информационной системы.
                        </p>
                    </div>
                </div>
            </div>

            <div style="font:12px Arial,'Helvetica Neue',Helvetica,sans-serif;color:#758188;text-align:center">
                <p style="font:12px Arial,'Helvetica Neue',Helvetica,sans-serif;color:#a0a0a0;margin:0 0 15px">
                <strong>Copyright &copy; <?= date('Y') ?> <?=yii\helpers\Html::a(\Yii::$app->name, \Yii::$app->urlManager->createAbsoluteUrl(['site/index']));?>.</strong>
                    <br />All rights reserved.
                </p>
            </div>

        </div>

    </div>
</div>
