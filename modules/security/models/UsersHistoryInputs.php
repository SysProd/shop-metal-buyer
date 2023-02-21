<?php

namespace app\modules\security\models;

use Yii;

/**
 * This is the model class for table "users_history_inputs".
 *
 * @property integer $id_action
 * @property integer $user_id
 * @property integer $ip_adress
 * @property integer $date_history_input
 * @property string $country
 * @property string $city
 * @property string $region
 * @property double $latitude
 * @property double $longitude
 * @property string $time_zone
 * @property string $zip_code
 *
 * @property User $user
 */
class UsersHistoryInputs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_history_inputs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'ip_adress', 'date_history_input'], 'integer'],
            [['ip_adress', 'date_history_input'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['country', 'city', 'region'], 'string', 'max' => 45],
            [['time_zone'], 'string', 'max' => 6],
            [['zip_code'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_action'             => Yii::t('app', 'ID - Action'),    // 'ID - Историй посещений'
            'user_id'               => Yii::t('app', 'User ID'),        // 'ID - пользователя'
            'ip_adress'             => Yii::t('app', 'Ip Adress'),      // 'IP - адрес с которого зашел пользователь'
            'date_history_input'    => Yii::t('app', 'Date History Input'), // 'Дата входа в систему'
            'country'               => Yii::t('app', 'Country'),        // 'Страна входа'
            'city'                  => Yii::t('app', 'City'),           // 'Город входа'
            'region'                => Yii::t('app', 'Region'),         // 'Регион входа'
            'latitude'              => Yii::t('app', 'Latitude'),       // 'Широта входа'
            'longitude'             => Yii::t('app', 'Longitude'),      // 'Долгота Входа'
            'time_zone'             => Yii::t('app', 'Time Zone'),      // 'Часовой пояс входа'
            'zip_code'              => Yii::t('app', 'Zip Code'),       // 'Почтовый индекс входа'
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
