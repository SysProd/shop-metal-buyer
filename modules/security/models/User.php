<?php

namespace app\modules\security\models;


use Yii;

use yii\db\ActiveRecord;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

use yii\base\NotSupportedException;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

use kartik\icons\Icon;

use app\modules\data\models\Phone;
use app\modules\staff\models\Staff;
use app\modules\company\models\ContractorsData;
use app\modules\company\models\ProvidersData;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $profile_id
 * @property string $username
 * @property string $email
 * @property boolean $send_mail
 * @property integer $confirmed_reg
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password
 * @property string $password_repeat
 * @property string $password_reset_token
 * @property integer $status_system
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 * @property array $role
 * @property string $roleName
 * @property string $errorMessage
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItemChild[] $authItemChild
 * @property AuthItem[] $roleNameArray
 * @property AuthItem[] $roleNameString
 * @property AuthItem $rootRole
 * @property ContractorsData[] $contractorsData
 * @property ProvidersData[] $providersData
 * @property Phone $phoneDefault
 * @property Phone[] $phonesForUser
 * @property Staff $confirmedReg0
 * @property Staff $profile
 * @property UsersHistoryActions[] $usersHistoryActions
 * @property UsersHistoryInputs[] $usersHistoryInputs
 * @property UsersSetting $usersSetting
 * @property Staff $createdBy
 * @property Staff $updatedBy
 * @property User[] $statusSystemList
 * @property User $statusSystemStyle
 * @property User[] $arrayListRole
 *
 */
class User extends ActiveRecord implements IdentityInterface
{

    /**
     * Статус в системе "ТМЦ"
     */
    const   STATUS_SYSTEM_ACTUAL        = 'Actual',     // 'Актуальный'
            STATUS_SYSTEM_IRRELEVANT    = 'Irrelevant', // 'Неактуальный'
            STATUS_SYSTEM_BLOCKED       = 'Blocked',    // 'Заблокированный'
            STATUS_SYSTEM_DELETED       = 'Deleted';    // 'Удаленный'

    /**
     * Сценарии использования модуля "Пользователя"
     */
    const   SCENARIO_UPDATE_USER  = 'update',
            SCENARIO_RESET_PASS   = 'reset',
            SCENARIO_ADD_USER     = 'create',
            SCENARIO_SEARCH_USER  = 'search';

    public  $password,          // Пароль
            $password_repeat,   // Повторный ввод пароля
            $role = [],         // Роли пользователя в системе в массиве
            $roleName,          // Роли пользователя в системе в строке
            $send_mail = false; // отправка email

    private $errorMessage = '';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * Действие при Создании/Изменении в базе
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' =>
                    [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                    ],
//                'value' => time(),  // Атрибут типа даты
            ],
            [
                'class' => BlameableBehavior::className(),
                'attributes' =>
                    [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_by'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_by'],
                    ],
                'value' => empty( \Yii::$app->user->identity->profile_id) ? NULL : \Yii::$app->user->identity->profile_id,   // Изменить атрибут присвоения profile_id
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'status_system' ], 'required', 'on' => self::SCENARIO_UPDATE_USER],
            [['username', 'email', 'status_system', 'password', 'password_repeat', ], 'required', 'on' => self::SCENARIO_ADD_USER],

            [['id', 'profile_id', 'confirmed_reg', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['send_mail'], 'boolean'],

            [['profile_id',], 'filter', 'filter' => 'intval'], // фильтровать данные в числовой тип данных для правильной работы behaviors()

            [['username', 'email', 'password', 'password_repeat', 'auth_key', 'password_hash', 'password_reset_token'], 'trim'], // обрезать пробелы

            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'message' => \Yii::t('app','New passwords do not match')],   // проверка на введенные пароли

            [['send_mail',],     'default', 'value' => false ],
            [['status_system',], 'default', 'value' => self::STATUS_SYSTEM_ACTUAL ],
            [['status_system',], 'in', 'range' => array_keys($this->statusSystemList) ],
            [['email'],     'email'],
            [['email'],     'unique', 'targetClass' => User::className(),                                               'message' => \Yii::t('app', 'This «{attribute}» is already in use', ['attribute' => \Yii::t('app', 'E-mail')] )],  // проверка на уникальные «E-mail» в системе
            [['email'],     'unique', 'targetClass' => Staff::className(), 'filter' => ['<>', 'id_profile', $this->profile_id],    'message' => \Yii::t('app', 'This «{attribute}» is already in use', ['attribute' => \Yii::t('app', 'E-mail')] )],  // проверка на уникальные «E-mail» в системе
            [['username'],  'unique', 'targetClass' => User::className(),                                               'message' => \Yii::t('app', 'This «{attribute}» is already in use', ['attribute' => \Yii::t('app', 'Username')] )],// проверка на уникальные «Пользователя» в системе
            [['profile_id', 'username', 'email'], 'unique', 'targetAttribute' => ['profile_id', 'username', 'email'],   'message' => \Yii::t('app', 'This «{attribute}» has been added',    ['attribute' => \Yii::t('app', 'User')] )],    // проверка на уникальные элементы

            [['username', 'email', 'password_hash', 'password_reset_token'],    'string', 'max' => 100],
            [['auth_key'],                                                      'string', 'max' => 32],
            [['username'],                      'string',  'min' => 4, 'max' => 100],
            [['password', 'password_repeat'],   'string',  'min' => 6, 'max' => 100],

            [['role'],                          'each', 'rule' => ['string']],

//            ['role', 'each', 'rule' => ['string']],
//            [['role',], 'required', 'message' => 'Необходимо выбрать "Роль в системе"'],
//            [['profile_id'], 'unique'],
//            [['phone'], 'validatePhoneEmpty', 'skipOnEmpty'=> false],
//            [['phone'], 'exist', 'skipOnError' => true, 'targetClass' => Phone::className(), 'targetAttribute' => ['phone' => 'id_phone']],

            [['confirmed_reg'],     'exist', 'skipOnError' => true, 'targetClass' => Staff::className(),        'targetAttribute' => ['confirmed_reg'   => 'id_profile']],
            [['profile_id'],        'exist', 'skipOnError' => true, 'targetClass' => Staff::className(),        'targetAttribute' => ['profile_id'      => 'id_profile']],
            [['created_by'],        'exist', 'skipOnError' => true, 'targetClass' => Staff::className(),        'targetAttribute' => ['created_by'      => 'id_profile']],
            [['updated_by'],        'exist', 'skipOnError' => true, 'targetClass' => Staff::className(),        'targetAttribute' => ['updated_by'      => 'id_profile']],
        ];
    }

    /* Кастомная функция для проверки, что хотя бы одно из полей email или phone посетитель заполнил */
/*    public function validatePhoneEmpty()
    {
        if(empty($this->phone))
        {
            $errorMsg= 'Укажите ваш телефон';
            $this->addError('phone',$errorMsg);
        }
        if(!empty($this->phone) && (strlen($this->phone)<7))
        {
            $errorMsg= 'Слишком мало цифр в номере телефона';
            $this->addError('phone',$errorMsg);
        }
    }*/
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => Yii::t('app', 'ID - User'),
            'profile_id'            => Yii::t('app', 'Profile'),
            'username'              => Yii::t('app', 'Login'),
            'email'                 => Yii::t('app', 'E-mail'),
            'confirmed_reg'         => Yii::t('app', 'Confirmed By'),
            'auth_key'              => Yii::t('app', 'Auth Key'),
            'password'              => Yii::t('app', 'Password'),
            'password_repeat'       => Yii::t('app', 'Confirm password'),
            'password_hash'         => Yii::t('app', 'Hash password'),
            'password_reset_token'  => Yii::t('app', 'Token reset password'),
            'role'                  => Yii::t('app', 'Role in system'),
            'roleName'              => Yii::t('app', 'Role in system'),
            'rootRole'              => Yii::t('app', 'Root role'),
            'status_system'         => Yii::t('app', 'Status'),
            'created_at'            => Yii::t('app', 'Created At'),
            'created_by'            => Yii::t('app', 'Created By'),
            'updated_at'            => Yii::t('app', 'Updated At'),
            'updated_by'            => Yii::t('app', 'Updated By'),
            'send_mail'             => Yii::t('app', 'Notification to email'),
        ];
    }

    /**
     * Добавление сотового номера телефона
     * @return array|null $items
     */
/*    public function createPhone()
    {
        $phone = New Phone();

        $phone_intenget = $this->phone;
        $phone_string = mb_substr(Yii::$app->formatter->asPhoneFormatter($this->phone),1);

        $phone->user_id = $this->id;
        $phone->type_phone = Phone::TYPE_MOBILE;
        $phone->status_phone = Phone::STATUS_AVAILABLE;
        $phone->default_phone = Phone::PHONE_DEFAULT;
        $phone->phone_reference = $phone_intenget;
        $phone->phone_template = $phone_string;
        $phone->created_at = time();
        $phone->add_user = Yii::$app->user->identity->id;
        print_r($phone);

        try {
            $phone->save();
        } catch (\Exception $ex) {
            $this->errorMessage .= Yii::t('app', "Item <strong>{value}</strong> is not assigned:", ['value' => $phone_intenget]). " " . $ex->getMessage() . "<br />";

//            $this->errorMessage .= Yii::$app->session->addFlash('error', '<h4><span class="glyphicon glyphicon-ok-sign kv-alert-title"></span> Ошибка</h4>  <p> Телефон № <strong> '.$phone_intenget.' </strong> не сохранен. '). " " . $ex->getMessage() . "<br />";
        }
//        return $phone->id_phone;

    }*/


    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        if ($this->email) {
            return Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'new-users-in-system-html'],
                    ['model' => $this]
                )
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                ->setTo($this->email)
                ->setSubject(\Yii::t('app', 'Registration in the system'))
                ->send();
        }

        return false;
    }

    /**
     * Функция добаления Ролей для пользователя
     * @return bool
     */
    public function createRoles()
    {

        if(!empty($this->role) && !empty($this->id)){

            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $flag = null;

                foreach ($this->role as $value) {
                    $roles = New AuthAssignment();
                    $roles->user_id = $this->id;
                    $roles->item_name = $value;

                    if(!($flag = $roles->save()) or !($roles->validate())){
                        $transaction->rollBack();
                        throw new \Exception(\Yii::t('app', 'Unexpected error'));   // Присвоение ошибки
                    }
                }
                if($flag){
                    $transaction->commit();
                    return true;
                }

            } catch (\Exception $ex) {
                $this->errorMessage .= \Yii::t('app', 'Failed to save «{attribute}»', ['attribute' => \Yii::t('app', 'role of user')]).': '.$ex->getMessage().'<br />';
                $transaction->rollBack();
                return false;
            }
        }

        return false;
    }

    /**
     * Функция обновления Ролей для пользователя
     * @return bool
     */
    public function updateRoles()
    {

//       Выбрать Роли из базы для выбраного пользователя
        $authAssignments = $this->authAssignments;

        /**
         * Удалить все отмеченые роли пользователя
         **/
        if (empty($this->role)) {
            if(AuthAssignment::deleteAll(['user_id' => $this->id])){
                return true;
            }
            $this->errorMessage .= \Yii::t('app', 'Failed to remove «{attribute}»', ['attribute' => \Yii::t('app', 'role of user')]).'<br />';
            return false;
        }

        /**
        * Удаление из массива страх элементов
        * Удаление из базы элементов отмеченых для удаления пользователем
        **/
        foreach ($authAssignments as $value) {
            $key = array_search($value->item_name, $this->role);
            if ($key === false) {
                echo $value->item_name;
                AuthAssignment::deleteAll(['item_name' => $value->item_name, 'user_id' => $this->id]);
            } else {
                unset($this->role[$key]);
            }
        }

        /**
         * Добавление новых отмеченых элементов пользователем в базу
         * Вывод ошибки при неудачном сохранении
         **/
        foreach ($this->role as $value) {
            try {

                $roles = New AuthAssignment();

                $roles->user_id = $this->id;
                $roles->item_name = $value;
                $roles->created_at = time();

                $roles->save(false);

            } catch (\Exception $ex) {
                $this->errorMessage .= \Yii::t('app', 'Failed to save «{attribute}»', ['attribute' => \Yii::t('app', 'role of user')]).': '.$ex->getMessage().'<br />';
            }
        }

    }

    /**
     * Список ролей в системе выводимых для пользователя
     * @return array $items
     */
    public function getArrayListRole()
    {
                $date = AuthItem::find()
                    ->where(['type' => AuthItem::TYPE_ROLE])
                    ->all();
//              Запретить показывать роль "Root-Admin"
        if( !Yii::$app->user->can('Root-Admin') ) {
            $date = AuthItem::find()->where("name != 'Root-Admin'")->andWhere(['type' => AuthItem::TYPE_ROLE])->all();
        }

                $items = ArrayHelper::map(
                    $date,
                    'name',
                    function ($date) {
                        return $date->name.(strlen($date->description) > 0 ? ' ['.$date->description.']' : '');
                    }
                );
        return $items;
    }

    /**
     * Список ошибок
     * @return array|null errorMessage
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status_system' => self::STATUS_SYSTEM_ACTUAL]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException(\Yii::t('app', '«{attribute}» is not implemented.', ['attribute' => 'findIdentityByAccessToken']));
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status_system' => self::STATUS_SYSTEM_ACTUAL]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status_system' => self::STATUS_SYSTEM_ACTUAL,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * Вывод "Ролей" пользователя в системе
     * @return \yii\db\ActiveQuery
     */
    public function getRoleNameArray()
    {
        return $this->hasMany( AuthItem::className(),       ['name' => 'item_name'])
                    ->viaTable(AuthAssignment::tableName(), ['user_id' => 'id']);
    }

    /**
     * Вывод "Ролей" пользователя в системе
     * @return null|string
     */
    public function getRoleNameString()
    {
        return empty($this->roleNameArray) ? null : implode(", ", ArrayHelper::map($this->roleNameArray,'name','name'));;
    }

    /**
     * Вывод "Задач" и "Операций" пользователя в системе
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChild()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name'])
                    ->via('roleNameArray');   // вызов функции
    }

    /**
     * Проверка назначения роли "Root-Admin" для пользователя
     * Проверка связи роли "Root-Admin" с другими ролями в системе
     * @return true|false
     */
    public function getRootRole()
    {
        $roleAdmin = 'Root-Admin';

        if (isset($this->roleNameArray) && isset($this->authItemChild)) {
            // Проверка на наличие роли "Root-Admin" у пользователя
            if (in_array($roleAdmin, ArrayHelper::map($this->roleNameArray, 'name', 'name')))return true;
            // Проверка связанной роли "Root-Admin" у пользователя
            if (in_array($roleAdmin, ArrayHelper::map($this->authItemChild, 'parent', 'child'))) return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractorsData()
    {
        return $this->hasMany(ContractorsData::className(), ['add_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvidersData()
    {
        return $this->hasMany(ProvidersData::className(), ['add_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoneDefault()
    {
        return $this->hasOne(Phone::className(), ['user_id' => 'profile_id' ])
                    ->where(['type_phone' => Phone::TYPE_MOBILE, 'status_phone' => Phone::STATUS_AVAILABLE, 'default_phone' => Phone::PHONE_DEFAULT, ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhonesForUser()
    {
        return $this->hasMany(Phone::className(), ['user_id' => 'profile_id' ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConfirmedReg0()
    {
        return $this->hasOne(Staff::className(), ['id_profile' => 'confirmed_reg']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Staff::className(), ['id_profile' => 'profile_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersHistoryActions()
    {
        return $this->hasMany(UsersHistoryActions::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersHistoryInputs()
    {
        return $this->hasMany(UsersHistoryInputs::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersSetting()
    {
        return $this->hasOne(UsersSetting::className(), ['user_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Staff::className(), ['id_profile' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Staff::className(), ['id_profile' => 'updated_by']);
    }

    /**
     * Статус пользователя в системе
     * @return array
     */
    public function getStatusSystemList()
    {
        $array = [
            self::STATUS_SYSTEM_ACTUAL      => Yii::t('app', self::STATUS_SYSTEM_ACTUAL),
            self::STATUS_SYSTEM_IRRELEVANT  => Yii::t('app', self::STATUS_SYSTEM_IRRELEVANT),
            self::STATUS_SYSTEM_BLOCKED     => Yii::t('app', self::STATUS_SYSTEM_BLOCKED),
            self::STATUS_SYSTEM_DELETED     => Yii::t('app', self::STATUS_SYSTEM_DELETED),
                    ];

        // Скрыть статус "Удаленные"
        if ( !(Yii::$app->user->can('users-show-status_delete')) )      { ArrayHelper::remove($array, self::STATUS_SYSTEM_DELETED); }
//        // Скрыть статус "Заблокированные"
//        if ( !(Yii::$app->user->can('users-show-status_blocked')) )     { ArrayHelper::remove($array, self::STATUS_SYSTEM_BLOCKED); }
//        // Скрыть статус "Неактуальный"
//        if ( !(Yii::$app->user->can('users-show-status_irrelevant')) )  { ArrayHelper::remove($array, self::STATUS_SYSTEM_IRRELEVANT); }

        return $array;
    }

    /**
     * Функция вывода стилизованного "status_system"
     * @return string
     */
    public function  getStatusSystemStyle()
    {
              if( $this->status_system == self::STATUS_SYSTEM_ACTUAL )      {
            return '<span class="label label-success"   title="'. Yii::t('app', $this->status_system).'">'. Icon::show('user',          ['class' => 'fa-lg']) .'</span>';
        }else if( $this->status_system == self::STATUS_SYSTEM_IRRELEVANT )  {
            return '<span class="label label-warning"   title="'. Yii::t('app', $this->status_system).'">'. Icon::show('low-vision',    ['class' => 'fa-lg']) .'</span>';
        }else if( $this->status_system == self::STATUS_SYSTEM_BLOCKED )     {
            return '<span class="label label-fired"     title="'. Yii::t('app', $this->status_system).'">'. Icon::show('ban',           ['class' => 'fa-lg']) .'</span>';
        }else if( $this->status_system == self::STATUS_SYSTEM_DELETED )     {
            return '<span class="label label-danger"    title="'. Yii::t('app', $this->status_system).'">'. Icon::show('user-times',    ['class' => 'fa-lg']) .'</span>';
        }else{
            return '---';
        }
    }

}
