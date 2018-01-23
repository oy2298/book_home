<?php
namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\LoginForm;

/**
 * Class IndexController
 * @package app\controllers
 */
class IndexController extends BaseController
{


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
	

    /**
     * Login action.
     *
     * @return string
     */
    public function actionIndex()
    {	
		
		return $this->render($this->actionID,['mainHead' => $this->mainHead ,'mainLeft' => $this->mainLeft ,'mainFoot' => $this->mainFoot ]);
	}
	

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
		$this->layout = $this->actionID;
		
        if (!Yii::$app->user->isGuest) {
			//Yii::$app->user->logout();
    	}
		
        $model = new LoginForm();
        if (Yii::$app->request->post()){
			if($model->load(Yii::$app->request->post()) && $model->login()){
				return $this->goHome();
			}	
        }
		return $this->render($this->actionID,['model'=>$model]);
		
    }
	



}
