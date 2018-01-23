<?php
//标准路由
$rules = [			 
		'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
		'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
		'<controller:\w+>/<action:\w+>/<id:\d+>/<data:\w+>'=>'<controller>/<action>',
		'<controller:\w+>/<action:\w+>/<id:.+>'=>'<controller>/<action>',		
];

return $rules;