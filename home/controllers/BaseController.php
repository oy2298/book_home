<?php
// +----------------------------------------------------------------------
// | TITLE:基础类
// +----------------------------------------------------------------------

namespace app\controllers;
use app\modules\Rbac;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * Class BaseController
 * @package app\controllers
 */
class BaseController extends Controller
{
	

    public $controllerID;
    public $actionID;
	
    public $userMain;
	
    public $mainHead;
    public $mainLeft;
    public $mainFoot;
	
    public function behaviors()
    {
        $behaviors = [
            Rbac::className(),//权限控制[]
        ];
        return array_merge( parent::behaviors(),$behaviors);
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);
		$this->userMain = $this->verifyRule($this->route);
        if ($this->userMain == false) {//游客
                $loginUrl = Url::toRoute('index/login');
                header("Location: $loginUrl");
                exit();
        }
		
		$this->controllerID = Yii::$app->controller->id;
		$this->actionID 	= Yii::$app->controller->action->id;
		
		
		
        return true;
    }

	

}

