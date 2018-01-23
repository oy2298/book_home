<?php
use yii\helpers\Url;
foreach($userMain as $key=>$list){
	
?>
<li  <?=$list['active']?>><a href="<?=Url::toRoute($list['route'])?>" ><?=$list['title']?></a></li>
<?php }?>