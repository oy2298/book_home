<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "edu_course_type".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $school_id
 * @property integer $uid
 * @property string $name
 * @property string $description
 * @property integer $create_date
 */
class CourseType extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edu_course_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'school_id', 'uid', 'create_date'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'school_id' => 'School ID',
            'uid' => 'Uid',
            'name' => 'Name',
            'description' => 'Description',
            'create_date' => 'Create Date',
        ];
    }
    /**
     * ËÑË÷·µ»ØÌõ¼þ
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function search()
    {
		$searchModel    = new \app\modules\Search();
		$className = get_class();
		$ModelClass     = new $className();
		$search = $searchModel->search($ModelClass,['status']);
		return $search;
    }
	
	
}
