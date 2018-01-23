<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

$config = [
		   
    'id' => 'app-home',
    'basePath' => dirname(__DIR__),
    //'viewPath' => '@app/MobileViews',//用户函数配置手机端视图$Mobile->isMobile()
    'bootstrap' => ['log'],
    'modules' => [],
    'defaultRoute'=>'index',
    'components' => [
/*        'view' => [
            'renderers' => [
                'tpl' => [
                    'class' => 'yii\smarty\ViewRenderer',                    
					'cachePath' => '@runtime/Smarty/cache',  
					'compilePath' => '@runtime/Smarty/compile',
					'options' => [
					'php_handling'=>3,
                    ],
                    //'cachePath' => '@runtime/Smarty/cache',
                ],
            ],
        ],*/
        'request' => [
            'csrfParam' => '_csrf-app',
            'cookieValidationKey' => 'yii2_4007530_red-*15252077025',
        ],
    	'urlManager' => [
    			'enablePrettyUrl' => true,
    			'showScriptName' => false,
    			'rules' =>  require(__DIR__ . '/rules.php'),
    	],
    	'authManager'=>[
    			'class'=>'yii\rbac\DbManager',//'yii\rbac\PhpManager'//,
    	],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        	'loginUrl'=>NULL,
            'identityCookie' => ['name' => '_identity_Cookie', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'session-name',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		
        'errorHandler' => [
            'errorAction' => 'index/error',
            'maxSourceLines'=>'20'
        ],  
    	'i18n' => require(__DIR__ . '/i18n.php'),   
    ],
    'params' => $params, 

];


return $config;
