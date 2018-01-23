<?php

namespace app\modules;

use Yii;
use yii\web\Response;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\BaseHtml;//添加表单类
use common\models\BaseModel;
/**
 * Search represents the model behind the search form about `app\models\Stock`.
 */
class Search extends BaseModel
{
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
	
    /**
     * textInput,dropDownList,WdateInput  
     *
	 * $searchModel    = new \app\models\Search();//用法
	 * $searchModel->viewSearch(['url'=>'index'],['abcd'=>'textInput'],[],[],'Wdate')
     * @return ActiveDataProvider
     */
	 
    public function viewSearch($searchModel,$paramsForm, $nameInputType,$options = NULL,$items = NULL,$data = NULL)
    {
		
		$data = count($data) ? $data : Yii::$app->request->get() + Yii::$app->request->post();
		 
		$Css = ['textInput'    => 'input-text',
				'dropDownList' => 'select-box',
				];
		
		$htmlForm = BaseHtml::beginForm(Url::toRoute($paramsForm['url']),@$paramsForm['method'],@$paramsForm['options']);
		foreach($nameInputType as $name=>$InputType){
			
			@$options[$name]['class'] = $Css[$InputType] . ' ' . $options[$name]['class'];
			$options[$name]['placeholder'] = is_array($searchModel) ? $searchModel[$name] : $searchModel->attributeLabels()[$name];
			
			
			if($InputType == 'dropDownList'){
				$htmlForm .= BaseHtml::$InputType($name,@$data[$name],$items[$name],$options[$name]).'&nbsp;&nbsp;';
			}else{
				$htmlForm .= BaseHtml::$InputType($name,@$data[$name],$options[$name]).'&nbsp;&nbsp;';
			}
		}
		$htmlForm .= BaseHtml::submitButton('<i class="Hui-iconfont">&#xe665;</i>搜索',['class'=>"btn btn-success"]);
		$htmlForm .= BaseHtml::endForm();
		return $htmlForm;
	}
	
	

    /**
     * Creates data provider instance with search query applied
     *
	 * $searchModel    = new \app\models\Search();//用法
	 * search = $searchModel->search(new AdminUser(),['status','like'=>['username','full_name','mobile'],'Relation'=>[new \app\models\Factory(),'One','factory_id','id',['name','address']]]);
     * @return ActiveDataProvider
     */
    public function search($classModel,$searchFilter,$data = array())
    {
		
		$data = count($data) ? $data : Yii::$app->request->get() + Yii::$app->request->post();
        $query = $classModel::find();
		foreach($searchFilter as $key=>$filterData){
				if(!empty($filterData)){
					
					if(is_numeric($key)){
						if(!is_array($filterData))$filterData = [$filterData];
						foreach($filterData as $filter){
							if(isset($data[$filter])&&$data[$filter]!='')$query->andWhere([$classModel->tableName().'.'.$filter => $data[$filter]]);
						}
					}elseif($key == 'Relation'){//关联查询数组[模型名,关联当前模型字段,关联模型字段,关联方法(一对一一对多),返回字段]
						if($filterData[1]=='Many'){
							$query->leftJoin($filterData[0]->tableName(),$filterData[0]->tableName().'.'.$filterData[3].' = '.$classModel->tableName().'.'.$filterData[2])
							      ->select([$classModel->tableName().'.*'] + [$filterData[0]->tableName().'.*']);//还没做反回字段，用着再做
							
						}elseif($filterData[1]=='One'){
							$query->innerJoin($filterData[0]->tableName(),$filterData[0]->tableName().'.'.$filterData[3].' = '.$classModel->tableName().'.'.$filterData[2])
							      ->select([$classModel->tableName().'.*']);//还没做反回字段，用着再做 + [$filterData[0]->tableName().'.*']
						}
						
					}elseif($key == 'like'){
						foreach($filterData as $filter){
							if(isset($data[$filter])&&$data[$filter]!='')$query->andWhere(['like', $classModel->tableName().'.'.$filter,$data[$filter]]);
						}
					}
					
				}
		}
        return $query;
    }
	
	
	
}
