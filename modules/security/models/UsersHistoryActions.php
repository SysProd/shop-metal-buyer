<?php

namespace app\modules\security\models;

use Yii;

/**
 * This is the model class for table "users_history_actions".
 *
 * @property integer $id_history
 * @property integer $user_id
 * @property integer $date_action
 * @property string $type_action
 *
 * @property User $user
 */
class UsersHistoryActions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_history_actions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'date_action'], 'integer'],
            [['date_action', 'type_action'], 'required'],
            [['type_action'], 'string', 'max' => 100],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_history'    => Yii::t('app', 'ID - History'),
            'user_id'       => Yii::t('app', 'User'),
            'date_action'   => Yii::t('app', 'Date Action'),
            'type_action'   => Yii::t('app', 'Type Action'),
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
