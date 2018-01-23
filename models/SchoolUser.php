<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "edu_school_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $full_name
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $role_id
 * @property string $mobile
 * @property integer $gender
 * @property string $login_ip
 * @property string $login_time
 * @property integer $login_num
 */
class SchoolUser extends VerifyUser
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%school_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'email', 'role_id'], 'required'],
            [['status', 'created_at', 'updated_at', 'role_id', 'gender', 'login_num'], 'integer'],
            [['login_time'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['full_name'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['mobile'], 'string', 'max' => 15],
            [['login_ip'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'full_name' => 'Full Name',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'role_id' => 'Role ID',
            'mobile' => 'Mobile',
            'gender' => 'Gender',
            'login_ip' => 'Login Ip',
            'login_time' => 'Login Time',
            'login_num' => 'Login Num',
        ];
    }
}
