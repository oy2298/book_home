<?php
defined('YII_ENV_TEST') or define('YII_ENV_TEST', false);
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');


require(__DIR__ . '/../../publics/function.php');//公共函数
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
										 require(__DIR__ . '/../../common/config/main.php'),
										 require(__DIR__ . '/../config/main.php')
										 );



$config = Mobile($config,'Mobile');//判断手机版
$application = new yii\web\Application($config);



$cookies = Yii::$app->request->cookies;
$language = 'zh-CN';
if ($cookies->has('language'))$language = $cookies->getValue('language');

$application->language = $language;

$application->run();
