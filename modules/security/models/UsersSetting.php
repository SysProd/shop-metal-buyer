<?php

namespace app\modules\security\models;

use Yii;

/**
 * This is the model class for table "users_setting".
 *
 * @property integer $user_id
 * @property integer $notice_email
 * @property integer $notice_sms
 *
 * @property User $user
 */
class UsersSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notice_email', 'notice_sms'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id'       => Yii::t('app', 'ID - User'),
            'notice_email'  => Yii::t('app', 'Notice Email'),
            'notice_sms'    => Yii::t('app', 'Notice Sms'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
