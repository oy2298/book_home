<?php
// +----------------------------------------------------------------------
// | TITLE: this to do?
// +----------------------------------------------------------------------

namespace common\models;

use Yii;

use yii\db\ActiveRecord;
use yii\web\Session;
use common\helps\Tree;

class BaseModel extends ActiveRecord
{


    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public  function enumeration($field='',$key)//枚举类设定对像
    {
        $Values = $this->attributeValues();
        return $Values[$field][intval($key)];
    }
    public  function enumerationData($field,$key,$ClassModel=NULL)//枚举类表对像
    {
		$ClassModel = empty($ClassModel) ? self::find() : $ClassModel::find();
        $Data = $ClassModel->select($field)->where(['id' => $key])->asArray()->one();
        return $Data[$field];
    }

	//关联一对多
    /**
     * 当前Model,需要关联Model,sql操作,关联条件,关联表条件
     * @return array|null|\yii\db\ActiveRecord
     */
    public function gethasMany($ThisModel,$RelationModel,$RelatioWhere = ['user_id' => 'id'],$where='1=1')
    {
		$Model = $ThisModel->hasMany($RelationModel::className(),$RelatioWhere);
		$Model->where($where);
		return $Model;
		
    }
	//关联一对一
    /**
     * 当前Model,需要关联Model,关联条件,关联表条件
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getHasOne($ThisModel,$RelationModel,$RelatioWhere = ['user_id' => 'id'],$where='1=1')
    {
        $Model = $ThisModel->hasOne($RelationModel::className(), $RelatioWhere);
		$Model->where($where);
		return $Model->asArray()->one();
    }
	

	//共用树结构数据读取[共用静态方法]
    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    protected static function getDataTree($ClassModel,$where=[],$select = ['id','pid','name'])
    {
			foreach($where as $k=>$v){
				if(empty($v)&&!is_numeric($v))unset($where[$k]);
			}
			$all = $ClassModel::find()->select($select)->where($where)->orderBy('id')->asArray()->all();//企业性质
			
			if(in_array('pid',$select))$all = Tree::getTreeTow($all);
			
			return array_column($all,'name','id');
    }
	
	
	
	
	
}











