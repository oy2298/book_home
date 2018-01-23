<?php
// +----------------------------------------------------------------------
// | TITLE: this to do?
// +----------------------------------------------------------------------

namespace app\modules;

use Yii;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use common\models\Role;
use common\helps\Tree;

class Rbac extends Behavior
{
    /**
     * 用户路由
     * @var array
     */
    public $allowUrl = [
        'index/index',
        'index/login',
    ];

    /**
     * 验证
     * @param $route 当前路由
     * @return bool
     */
    public function verifyRule($route)
    {
		
        $this->allowUrl = array_merge(Yii::$app->params['allowUrl'], $this->allowUrl);
		
		
		if (in_array($route, $this->allowUrl) == true && Yii::$app->user->isGuest) {
			return true;
		}else{
        	if (!Yii::$app->user->isGuest) {
				//菜单调用 
					$menu = Yii::$app->cache->get('menu_'.Yii::$app->user->identity->role_id);
				//整理菜单后改为缓存
				if(!@$menu){
					$menu  = Role::getRule(Yii::$app->user->identity->role_id);//菜单读取
					Yii::$app->cache->set('menu_'.Yii::$app->user->identity->role_id,$menu);
				}
				
				$rules = ArrayHelper::map($menu, 'id', 'route');
				
				foreach($menu as $key=>$list){
						if($route == $list['route']){//判断路由选项
							$menu[$key]['active'] = 'class="active"';
						}
				}
				
				$menu = Tree::ListToTree($menu);
				
				$this->allowUrl = array_merge($rules, $this->allowUrl);
				
				return  in_array($route, $this->allowUrl) == true ? $menu : false;
			}
			
			return false;
		}
		
		
		
		
		
		

		

		

		
    }


}