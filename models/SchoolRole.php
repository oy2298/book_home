<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "edu_school_role".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $des
 * @property string $create_user
 * @property string $create_date
 * @property string $update_user
 * @property string $update_date
 * @property integer $status
 * @property string $rule
 */
class SchoolRole extends BaseModel
{
    /**
     * 超级管理员分组[默认ID为1]
     */
    const ADMIN_ID = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%school_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['create_date', 'update_date'], 'safe'],
            [['status'], 'integer'],
            [['rule'], 'string'],
            [['code', 'name', 'create_user', 'update_user'], 'string', 'max' => 50],
            [['des'], 'string', 'max' => 400],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'des' => 'Des',
            'create_user' => 'Create User',
            'create_date' => 'Create Date',
            'update_user' => 'Update User',
            'update_date' => 'Update Date',
            'status' => 'Status',
            'rule' => 'Rule',
        ];
    }
	
    /**
     * 获取权限
     * @param $id 用户角色
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRule($id)
    {
        $SchoolRule = SchoolRule::find();
        $SchoolRule->where(['status' => 1]);
        $SchoolRule->andWhere(['is_show' => 1]);
        $SchoolRule->orderBy('order desc');
        if (self::ADMIN_ID != $id) {
            $roleOne = SchoolRole::findOne($id);
            $roleOne->rule = explode(',', $roleOne->rule);
            $SchoolRule->andWhere(['in', 'id', $roleOne->rule]);
        }
        return $SchoolRule->asArray()->all();
    }
	
	
	
}
