<?php

namespace app\modules\security\models\search;

use app\modules\security\models\AuthItem;
use app\modules\staff\models\Staff;
use Yii;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\modules\security\models\User;

/**
 * UserSearch represents the model behind the search form about `app\modules\user\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'profile_id'], 'integer'],
            [['email'], 'email'],

            [['username', 'confirmed_reg', 'roleName', 'auth_key', 'password_hash', 'password_reset_token', 'created_by', 'updated_by'], 'string', 'max' => 30],

            [['created_at', 'updated_at'], 'date', 'format'=>'dd-mm-yy'],
            [['status_system'], 'in', 'range' => array_keys($this->statusSystemList)],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()
            ->joinWith(['roleNameArray'])                                                                                               // Подключение ролей Пользователей
            ->leftJoin( Staff::tableName().' as `profile`   on `profile`  .`id_profile`    = '.User::tableName().'.`profile_id`')       // Профиль пользователя
            ->leftJoin( Staff::tableName().' as `confirmed` on `confirmed`.`id_profile`    = '.User::tableName().'.`confirmed_reg`')    // Проверил
            ->leftJoin( Staff::tableName().' as `create`    on `create`   .`id_profile`    = '.User::tableName().'.`created_by`')       // Создал
            ->leftJoin( Staff::tableName().' as `update`    on `update`   .`id_profile`    = '.User::tableName().'.`updated_by`');      // Обновил

        // Скрыть пользователей с ролью "Root-Admin"
        // Показать пользователей без Роли
        if( !(Yii::$app->user->can('Root-Admin')) ){
            $query  ->where  (['<>', AuthItem::tableName().'.name', 'Root-Admin'])
                    ->orWhere([      AuthItem::tableName().'.name' => null]);
        }

        //  Скрыть пользователей со статусом "Удаленные"
        if( !(Yii::$app->user->can('users-absolute_delete')) ){ $query  ->andFilterWhere (['<>', User::tableName().'.status_system', User::STATUS_SYSTEM_DELETED]); }

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 20
            ]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'username',
                'email',
                'auth_key',
                'status_system',
                'created_at',
                'updated_at',
                'roleName' =>
                    [
                        'asc'  => [ AuthItem::tableName().'.name' => SORT_ASC ],
                        'desc' => [ AuthItem::tableName().'.name' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],
                'created_by' =>
                    [
                        'asc'       => [ 'create.last_name' => SORT_ASC,  'create.first_name' => SORT_ASC, 'create.patronymic' => SORT_ASC  ],
                        'desc'      => [ 'create.last_name' => SORT_DESC, 'create.first_name' => SORT_DESC,'create.patronymic' => SORT_DESC ],
                        'default'   => SORT_ASC
                    ],
                'updated_by' =>
                    [
                        'asc'       => [ 'update.last_name' => SORT_ASC,  'update.first_name' => SORT_ASC, 'update.patronymic' => SORT_ASC  ],
                        'desc'      => [ 'update.last_name' => SORT_DESC, 'update.first_name' => SORT_DESC,'update.patronymic' => SORT_DESC ],
                        'default'   => SORT_ASC
                    ],
                'profile_id' =>
                    [
                        'asc'       => [ 'profile.last_name' => SORT_ASC,  'profile.first_name' => SORT_ASC, 'profile.patronymic' => SORT_ASC  ],
                        'desc'      => [ 'profile.last_name' => SORT_DESC, 'profile.first_name' => SORT_DESC,'profile.patronymic' => SORT_DESC ],
                        'default'   => SORT_ASC
                    ],
                'confirmed_reg' =>
                    [
                        'asc'       => [ 'confirmed.last_name' => SORT_ASC,  'confirmed.first_name' => SORT_ASC, 'confirmed.patronymic' => SORT_ASC  ],
                        'desc'      => [ 'confirmed.last_name' => SORT_DESC, 'confirmed.first_name' => SORT_DESC,'confirmed.patronymic' => SORT_DESC ],
                        'default'   => SORT_ASC
                    ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // Раскомментируйте следующую строку, если вы не хотите возвращать какие-либо записи при неудачной проверке
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            User::tableName().'.id' => $this->id,
            User::tableName().'.status_system' => $this->status_system,
            'DATE_FORMAT(FROM_UNIXTIME('.User::tableName().'.created_at),"%d-%m-%Y")' => $this->created_at,
            'DATE_FORMAT(FROM_UNIXTIME('.User::tableName().'.updated_at),"%d-%m-%Y")' => $this->updated_at,
        ]);

        $query  ->andFilterWhere(['like', 'username',                   $this->username])
                ->andFilterWhere(['like', User::tableName().'.email',   $this->email])
                ->andFilterWhere(['like', 'auth_key',                   $this->auth_key])
                ->andFilterWhere(['like', 'password_hash',              $this->password_hash])
                ->andFilterWhere(['like', 'password_reset_token',       $this->password_reset_token])
                ->andFilterWhere(['like', AuthItem::tableName().'.name',$this->roleName])
                ->andFilterWhere(['like', 'profile.last_name',          $this->profile_id])
                ->andFilterWhere(['like', 'confirmed.last_name',        $this->confirmed_reg])
                ->andFilterWhere(['like', 'create.last_name',           $this->created_by])
                ->andFilterWhere(['like', 'update.last_name',           $this->updated_by]);

        return $dataProvider;
    }
}
