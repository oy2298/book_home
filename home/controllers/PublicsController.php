<?php
namespace app\controllers;

use yii;
use yii\web\Controller;
use common\servers\VerifyServer;
use common\servers\FileUpload;



class PublicsController extends Controller
{
	
	
	public function beforeAction($action) {  
	  
		$currentaction = $action->id;  
		$novalidactions = ['upload'];
		if(in_array($currentaction,$novalidactions)) {  
			$action->controller->enableCsrfValidation = false;  
		}
		parent::beforeAction($action);  
		return true;  
	} 
	
    public function actionVerify()
    {
		$Verify = new VerifyServer();
		$Verify->displayVerify();
		exit;
    }
	
    public function actionUpload()
    {
		//上传
		$id = Yii::$app->request->get('id');
		$field = Yii::$app->request->get('field');
		$_csrf = Yii::$app->request->get('_csrf');
		$ClassModel = new \common\models\Upfile;
		
		if($id){//删除文件
			$file = $ClassModel::find()->where(['id'=>$id,'_csrf'=>$_csrf])->asArray()->One();
			if($file){
				
				//修改数据
				$className   = '\\app\models\\' . ucfirst($file['table']);
				if(@class_exists ($className)){
					$updataModel = new $className;
					$one = $updataModel::find()->where(['id'=>$file['rid']])->asArray()->One();
					$one[$file['field']] = explode(',',$one[$file['field']]);
					$key = array_search($id,$one[$file['field']]);
					if(is_numeric($key))unset($one[$file['field']][$key]);
					Yii::$app->db->createCommand()->update($file['table'], [ $file['field'] => join(',',$one[$file['field']]) ], 'id = '.$file['rid'])->execute();
				}
				
				
				@unlink($file['path'].$file['name']);
				@unlink($file['path_tmp'].$file['name']);
				return $ClassModel::deleteAll(['id'=>$id]);
			}
		}
		
		$path = 'upload/'.date('Ymd').'/';
		if (!file_exists($path)) {
			@mkdir($path);
		}
		$path_tmp = $path.'tmp/';
		if (!file_exists($path_tmp)) {
			@mkdir($path_tmp);
		}
		
		$up = new FileUpload();
		$up->set("path", $path);
		$up->set("allowtype",Yii::$app->params['file_upload']['extensions']);
		$up->set("maxsize", Yii::$app->params['file_upload']['max_size']);
		$up->set("israndname", true);
		if($up->upload("file")){
			$fileName = $up->getFileName();
			$result = @mkThumbnail($path.$fileName, 200, NULL, $path.'tmp/'.$fileName); 
			
			$data['field'] 		= $field;
			$data['_csrf'] 		= $_csrf;
			$data['name'] 		= $fileName;
			$data['path'] 		= $path;
			$data['path_tmp']  	= $path_tmp;
			$data['filetype'] 	= $up->getFileType();
			$data['add_time']  	= time();
			
			Yii::$app->db->createCommand()->insert($ClassModel->tableName(),$data)->execute();
			$id = Yii::$app->db->getLastInsertId();
			
			die('{"result" : "'.$fileName.'", "_csrf" : "'.$_csrf.'", "id" : "'.$id.'"}');
		}else{
			$errorMsg = getErrorMsg();
			die('{"error" : "'.$errorMsg.'", "_csrf" : "'.$_csrf.'"}');
		}
		
		exit;
    }
	



}
?>
