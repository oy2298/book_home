<?php
namespace app\assets;
use yii\web\AssetBundle;
/**
 * @author 自动加载JS CSS
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
	
    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
	
     //定义按需加载JS方法，注意加载顺序在最后  
    public static function addScript($view, $jsfile , $setAsset = array()) {  
        $view->registerJsFile($jsfile, array_merge( [AppAsset::className(), 'depends' => 'app\assets\AppAsset'] ,  $setAsset ) );  
    }  
      
   //定义按需加载css方法，注意加载顺序在最后  
    public static function addCss($view, $cssfile) {  
        $view->registerCssFile($cssfile, [AppAsset::className(), 'depends' => 'app\assets\AppAsset']);  
    }  

	
}
