<?php

namespace common\models;

use Yii;
use common\helps\Tree;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "edu_school_rule".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $route
 * @property string $title
 * @property string $icon
 * @property integer $type
 * @property string $condition
 * @property integer $order
 * @property string $tips
 * @property integer $is_show
 * @property integer $status
 */
class SchoolRule extends BaseModel
{
    public static $firstMenu = ['0' => '顶级菜单'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%school_rule}}';
    } 
	
	public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['pid', 'route', 'title', 'icon', 'type', 'status', 'condition', 'is_show', 'order', 'tips'];
        $scenarios[self::SCENARIO_UPDATE] = ['pid', 'route', 'title', 'icon', 'type', 'status', 'condition', 'is_show', 'order', 'tips'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['pid', 'default', 'value' => '0'],
            ['route', 'required', 'message' => '路由必须'],
            ['title', 'required', 'message' => '名称必须'],
            [['icon'], 'string', 'max' => 255],
            [['order'], 'string', 'max' => 11],
            [['tips'], 'string', 'max' => 255],
            ['type', 'required', 'message' => '类型必须'],
            ['status', 'required', 'message' => '状态必须'],
            ['is_show', 'default', 'value' => '0'],
            ['condition', 'string', 'max' => 255],
        ];
    }

	

    public function attributeValues()
    {
        return [
            'type' => [
                '1' => '权限和菜单',
                '2' => '权限',
                '3' => '菜单',
            ],
            'status' => [
                0 => '关闭',
                1 => '开启'
            ],
            'is_show' => [
                0 => '隐藏',
                1 => '显示'
            ]
        ];
    }

    public function attributeLabels()
    {

        return [
            'id' => '主键',
            'pid' => '上级ID',
            'route' => '路由',
            'title' => '名称',
            'icon' => '图标',
            'type' => '类型',
            'status' => '状态',
            'condition' => '描述',
            'is_show' => '显示',
            'order' => '排序',
            'tips' => '提示',

        ];


    }
	
    /**
     * 获取菜单
     * @param string $id
     * @return array
     *
     */
    public static function getAllMenu($id = '')
    {
        $all = self::find()
            ->andWhere(['status'=>1])
            ->orderBy('order')->asArray()->all();
        if ($id) {
            $all = self::find()
                ->andWhere(['status'=>1])
                ->where(['<>', 'id', $id])->asArray()->all();
        }
        $dataList = array();
        if ($all) {
            //生成线性结构, 便于HTML输出
            $dataList = Tree::makeTreeForHtml($all);
			
            $dataList = array_map(function ($item) {
                $nbsp = '';
                for ($i = 1; $i <= $item['level']; $i++) {
                    $nbsp .= '─';//制表符
                }
                $nbsp .= '╊';//制表符
                $item['title'] = $nbsp . $item['title'];
                return $item;
            }, $dataList);
            $dataList = ArrayHelper::map($dataList, 'id', 'title');
        }
		$dataList = self::$firstMenu + $dataList;
        //$dataList = array_merge(, $dataList);
        return $dataList;
    }
    /**
     * 搜索返回条件
     */
    public static function search()
    {
		$searchModel    = new \app\modules\Search();
		$className = get_class();
		$ModelClass     = new $className();
		$search = $searchModel->search($ModelClass,['status','type','is_show',
														'like'=>['title','route'],
														]);
		return $search;
    }
    /**
     * 搜索视图条
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function viewSearch()
    {
		$searchModel    = new \app\modules\Search();
		$className      = get_class();
		$ModelClass     = new $className();
		$searchViews    = $searchModel->viewSearch($ModelClass,['url'=>Yii::$app->controller->action->id],//提交地址
									   ['title'		=>'textInput',
										'route'		=>'textInput',
										'type'		=>'dropDownList',
										'is_show'	=>'dropDownList',
										'status'	=>'dropDownList',
										],//名称=>类型
									   ['title'		 =>['style'=>'width:150px;padding-right:5px'],
										'route'		 =>['style'=>'width:150px;padding-right:5px'],
										'type'    	 =>['style'=>'width:150px;padding-right:5px','class'=>'select'],
										'is_show'    =>['style'=>'width:150px;padding-right:5px','class'=>'select'],
										'status'     =>['style'=>'width:150px;padding-right:5px','class'=>'select'],
										],//名称=>配置
									   
									   ['type'    	 =>[''=>'类型'] + $ModelClass->attributeValues()['type'],
										'is_show'    =>[''=>'是否显示'] + $ModelClass->attributeValues()['is_show'],
										'status'     =>[''=>'状态'] + $ModelClass->attributeValues()['status'],
										]//名称=>原数据
									 );

		return $searchViews;

    }
	
	
	
	
	
}
