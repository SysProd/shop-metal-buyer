<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

use kartik\icons\Icon;

use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SendData;


class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionSendData()
    {
        $model = new SendData();
        $post = \Yii::$app->request->post();

        /** Проверка модуля enableAjaxValidation */
        if (Yii::$app->request->isAjax && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load($post)) {
            if ($valid = $model->validate()) {
                if($model->sendEmailContact()){
                    \Yii::$app->session->setFlash('success', '<h4>' . Icon::show('check-circle', ['class' => 'fa-lg kv-alert-title']) . 'Спасибо! </h4><hr class="kv-alert-separator" /><p> Ваше сообщение успешно отправлено!</p>');
                    return $this->redirect('index.php');
                }
            }

            \Yii::$app->session->setFlash('error', '<h4>' . Icon::show('times-circle', ['class' => 'fa-lg kv-alert-title']) . 'Ошибка! </h4><hr class="kv-alert-separator" /><p> Ваше сообщение не отправлено из-за непредвиденной ошибки!</p>');
            return $this->redirect('index.php');
        }

        return $this->renderAjax('send-data', [
            'model' => $model,
        ]);

    }

}
