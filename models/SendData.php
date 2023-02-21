<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 13.12.17
 * Time: 16:50
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * SendData is the model behind the contact form.
 *
 * @property string $full_name
 * @property string $email
 * @property string $phone
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */

class SendData extends \yii\base\Model
{
    public $full_name;
    public $email;
    public $phone;
    public $created_at;
    public $updated_at;
    public $created_by;
    public $updated_by;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['full_name'], 'required', 'message' => 'Введите ФИО'],
            [['phone'], 'required', 'message' => 'Введите Ваш Телефон'],

            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['email'], 'email', 'message' => 'Введите верный E-mail'],

            [['full_name', 'phone', 'email'], 'string', 'max' => 25],
        ];
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
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'full_name' => 'Ваше ФИО',
            'email' => 'Ваш E-mail',
            'phone' => 'Ваш телефон',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmailContact()
    {
           if(Yii::$app
               ->mailer
               ->compose(
                   ['html' => 'sendMailContact'],
                   ['model' => $this]
               )
               ->setFrom([\Yii::$app->params['adminEmail'] => '"' . \Yii::$app->name . '" <'.\Yii::$app->params['adminEmail'].'>'])
               ->setTo([\Yii::$app->params['activeUser']])
               ->setBcc([\Yii::$app->params['adminEmail']]) // скрытая копия
               ->setSubject('Новая заявка на звонок')
               ->send()){ return true; }

           return false;
    }

}