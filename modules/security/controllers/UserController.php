<?php

namespace app\modules\security\controllers;

use Yii;

use app\models\Model;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use yii\widgets\MaskedInput;
use yii\widgets\MaskedInputAsset;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use kartik\icons\Icon;

use app\modules\staff\models\Staff;
use app\modules\data\models\Phone;
use app\modules\security\models\User;
use app\modules\security\models\search\UserSearch;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Root-Admin', 'Администраторы'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Регистрация сотрудника в систему
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionReg($id)
    {

        if(\Yii::$app->user->can('staff-reg_add')            //    Проверка разрешения доступа к странице
            && Staff::findOne($id)                      //    Проверка, существует ли пришедший профиль
        ) {
            if( !($profile = Staff::getCheckRegStaff($id)) ) {    //    Проверка, зарегистрированный ли этот сотрудник
                $user = new User();
                $user->scenario = User::SCENARIO_ADD_USER;

                $user->profile_id = $id;
                $user->confirmed_reg = \Yii::$app->user->identity->id;
                $prof = $user->profile;

                $user->email = !isset($user->email) ? $prof->email : null;     // проверка есть ли email в профиле у сотрудника

                if ($user->load(\Yii::$app->request->post())) {
                    $user->setPassword($user->password);    // Создать хэш пароля
                    $user->generateAuthKey();               // Создать ключ авторизации

                    if ( $valid = $user->validate() ) {
                        $transaction = \Yii::$app->db->beginTransaction();
                        try {
                            if ($flag = $user->save()) {
                                $roles = isset(\Yii::$app->request->post('User')['role']) ? \Yii::$app->request->post('User')['role'] : null;
                                //  Запись ролей в бд
                                if (!empty($roles)) {
                                    $user->role = $roles;
                                    // Проверка на наличие ошибок при сохранении роли пользователя
                                    if (!($user->createRoles()) && strlen($user->getErrorMessage()) > 0) {
//                                        \Yii::$app->session->addFlash('error', $user->getErrorMessage());
                                        $transaction->rollBack();
                                        throw new \Exception($user->getErrorMessage());   // Присвоение ошибки
                                    }

                                    $prof->email = $user->email;   // Обновить почту сотрудника в его профиле
                                    if(!($prof->save(false,  ['email']))){
                                        $transaction->rollBack();
                                        throw new \Exception(\Yii::t('app','Unexpected error'));   // Присвоение ошибки
                                    }
                                }
                            }
                            if ($flag) {
                                if($user->send_mail){ $user->sendEmail(); }     // отправить письмо с уведомлением о регистрации в системе
                                \Yii::$app->session->addFlash('success', '<h4>'.Icon::show('check-circle', ['class' => 'fa-lg kv-alert-title']).\Yii::t('app','Successful').  '</h4> <p> '.\Yii::t('app','{nameType} <strong>{fullName}</strong> registered in the system',    ['nameType' => \Yii::t('app', 'The employee'), 'fullName' => Html::encode($user->profile->fullName)] ).'.</p>');
                                $transaction->commit();
                                return $this->redirect(['index']);
                            }
                        } catch (\Exception $e) {
                            \Yii::$app->session->addFlash('error',   '<h4>'.Icon::show('times-circle', ['class' => 'fa-lg kv-alert-title']).\Yii::t('app','Unsuccessful').'</h4> <p> '.\Yii::t('app','{nameType} <strong>{fullName}</strong> not registered in the system',['nameType' => \Yii::t('app', 'The employee'), 'fullName' => Html::encode($user->profile->fullName)] ).'.</p>');
                            $transaction->rollBack();
                            return $this->redirect(['reg', 'id' => $id]);
                        }
                    }
                }

                $items = $user->arrayListRole;

                return $this->render('create', [
                    'model' => $user,
                    'role' => [],
                    'items' => $items,
                ]);
            }
                return $this->redirect(['update', 'id' => $profile]);

        }else{
            throw new NotFoundHttpException( \Yii::t('app', 'Page not found or are restricted for you.') );
        }

    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // Аутентификационные данные пользователя
        $users              = $this->findModel($id);
        $users->scenario    = User::SCENARIO_UPDATE_USER;

        // Профиль пользователя
        $profiles       = $users->profile;
        $phonesForUser  = $users->phonesForUser;

        foreach ($phonesForUser as $id => $val){
            // id for update
            $val->id = $val->id_phone;
        }

//      Проверка существования профиля
        if($profiles != null){
            $profiles->scenario         = Staff::SCENARIO_ADD_STAFF;
            $profiles->company_id       = $profiles->company->id_company;
            $profiles->counterparty_id  = $profiles->counterparty->id;
            $profiles->department_id    = $profiles->departments->id_department;
        }

        /**
         * ####  Обработка "Профиля" пользователя  ####
         * @var $phonesForUser \app\modules\data\models\Phone[]
         */
        if ($profiles != null && $profiles->load(\Yii::$app->request->post())) {

            $oldIDs         = ArrayHelper::map($phonesForUser, 'id', 'id');
            $phonesForUser  = Model::createMultiple(Phone::classname(), $phonesForUser);
            Model::loadMultiple($phonesForUser, \Yii::$app->request->post());

            $deletedIDs     = array_diff($oldIDs, array_filter(ArrayHelper::map($phonesForUser, 'id', 'id')));

            $count_default_phone = 0;
            foreach ($phonesForUser as $id_new => $new){
                if($new->default_phone){ $count_default_phone++; }  // счетчик количества отмеченых телефонов по умолчанию
                if($new->id == null){   // обработка новых телефонов
                    $new->created_by = $users->profile_id;
                    $new->user_id    = $users->profile_id;
                }else{                  // обработка обновленных телефонов
                    $new->updated_at = time();
                    $new->updated_by = $users->profile_id;
                }
            }

//      запись ошибки, если не отмечен общий номер телефона
            if($count_default_phone != 1){
                foreach ($phonesForUser as $val) {
                    $val->count_default_phone = true;
                }
            }

            // validate all models
            $valid = $profiles->validate();
            $valid = Model::validateMultiple($phonesForUser) && $valid;

            if ($valid) {

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $profiles->save(false)) {
                        //  Удаление позиций
                        if (!empty($deletedIDs)) {
                            Phone::deleteAll(['id_phone' => $deletedIDs]);
                        }
                        // Добавление новых позиций
                        foreach ($phonesForUser as $phone) {
                            if (!($flag = $phone->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
//                        return $this->redirect(['view', 'id' => $users->id]);

                        switch (Yii::$app->request->post('action', 'save')) {
                            case 'next':
                                return $this->redirect(
                                    [
                                        'index',
                                    ]
                                );
                            default:
                                return $this->redirect(
                                    [
                                        'update',
                                        'id' => $users->id,
                                        'tab' => \Yii::$app->request->get('tab')
                                    ]
                                );
                        }

                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            // Вывод ошибки
            }else{
//                echo 'error';
                \Yii::$app->session->setFlash('error', '<h4>'.Icon::show('times-circle', ['class' => 'fa-lg kv-alert-title']).\Yii::t('app','Unsuccessful').'</h4> <p> '.\Yii::t('app','When processing your request an error occurred.').'.</p>');
            }

        }

        /**
         * ####  Обработка "Аутентификационные Данных" пользователя  ####
         */
        if ($users->load(\Yii::$app->request->post())) {
            $roles = isset(\Yii::$app->request->post('User')['role']) ? \Yii::$app->request->post('User')['role'] : null;
            $rolesDB = $users->authAssignments;

            if ($roles != null or !empty($rolesDB)) {
                $users->role = $roles;
                $users->updateRoles();
                if (strlen($users->getErrorMessage()) > 0) {
                    \Yii::$app->getSession()->setFlash('error', $users->getErrorMessage());
                    return $this->redirect(['update', 'id' => $users->id]);
                }
            }

            $users->save();

            switch (\Yii::$app->request->post('action', 'save')) {
                case 'next':
                    return $this->redirect(
                        [
                            'index',
                        ]
                    );
                default:
                    return $this->redirect(
                        [
                            'update',
                            'id' => $users->id,
                            'tab' => \Yii::$app->request->get('tab')
                        ]
                    );
            }
        }

//            $items = User::ArrayListRole();
            $items = $users->arrayListRole;
            $roles = $users->authAssignments;
            $selected = [];

            foreach ($roles as $role) {
                $selected[] = $role->item_name;
            }
            return $this->render('update', [
                'model' => $users,
                'role' => $selected,
                'items' => $items,
                'profile' => $profiles,
                'phonesForUser' => (empty($phonesForUser)) ? [new Phone()] : $phonesForUser,
            ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return \yii\web\Response|mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {

        $user = $this->findModel($id);
        $fullName = isset($user->profile) ? $user->profile->fullName : $user->username;

        /**
         * Разрешить полное удаление "Пользователей" для Операции "users-absolute_delete"
         */
        if(\Yii::$app->user->can('users-absolute_delete')) {
            if ($user->delete()) {
                \Yii::$app->session->setFlash('success', '<h4>'.Icon::show('check-circle', ['class' => 'fa-lg kv-alert-title']).\Yii::t('app','Successful').  '</h4> <p> '.\Yii::t('app','{nameType} <strong>{fullName}</strong> removed from the system',    ['nameType' => \Yii::t('app', 'The User'), 'fullName' => Html::encode($fullName)]).'.</p>');
            } else {
                \Yii::$app->session->setFlash('error',   '<h4>'.Icon::show('times-circle', ['class' => 'fa-lg kv-alert-title']).\Yii::t('app','Unsuccessful').'</h4> <p> '.\Yii::t('app','{nameType} <strong>{fullName}</strong> not removed from the system',['nameType' => \Yii::t('app', 'The User'), 'fullName' => Html::encode($fullName)]).'.</p>');
            }

        /**
         * Разрешить удаление "Пользователей" для Операции "users-delete"
        */
        }elseif(\Yii::$app->user->can('users-delete')){
            $user->status_system = User::STATUS_SYSTEM_DELETED;
            if ($user->save(false,  ['status_system'])) {
                \Yii::$app->session->setFlash('success', '<h4>'.Icon::show('check-circle', ['class' => 'fa-lg kv-alert-title']).\Yii::t('app','Successful').  '</h4> <p> '.\Yii::t('app','{nameType} <strong>{fullName}</strong> removed from the system',    ['nameType' => \Yii::t('app', 'The User'), 'fullName' => Html::encode($fullName)]).'.</p>');
            } else {
                \Yii::$app->session->setFlash('error',   '<h4>'.Icon::show('times-circle', ['class' => 'fa-lg kv-alert-title']).\Yii::t('app','Unsuccessful').'</h4> <p> '.\Yii::t('app','{nameType} <strong>{fullName}</strong> not removed from the system',['nameType' => \Yii::t('app', 'The User'), 'fullName' => Html::encode($fullName)]).'.</p>');
            }
        }else{
            throw new NotFoundHttpException( \Yii::t('app', 'Page not found or are restricted for you.') );
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id)
    {
        /**
         * @var $model \app\modules\security\models\User
         */
        $model = User::findOne($id);

//      Запретить вывод пользователя со статусом "Удаленный" и с ролью "Root-Admin"
        if( !\Yii::$app->user->can('Root-Admin') ){
            if ($model === null or $model->rootRole or !($model->status_system)) {
                throw new NotFoundHttpException( \Yii::t('app', 'Page not found or are restricted for you.') );
            }
        }

        if ($model === null) {
            throw new NotFoundHttpException( \Yii::t('app', 'Page not found.') );
        }

        return $model;

    }
}
