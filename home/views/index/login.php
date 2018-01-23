<?php
// +----------------------------------------------------------------------
// | TITLE: this to do?
// +----------------------------------------------------------------------
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>
<?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => Url::toRoute('index/login')]); ?>
<div class="loader" style="display:none">
		<div class="la-ball-clip-rotate">
			<div></div>
		</div>
	</div>
<div class="system-login"  style="background-image: url('./publics/resource/images/bg-login.png');" >

	<div class="head">
		<a href="/" class="logo-version">
			<img src="./publics/resource/images/logo/logo.png" class="logo">
			<span class="version hidden">1.6.4</span>
		</a>
			</div>
	<div class="login-panel">
		<div class="title">
			<a href="#" class="active" >账号密码</a>
					</div>
		<form action="" method="post" role="form" id="form1" onsubmit="return formcheck();" class="we7-form">
						<div class="input-group-vertical">
                        
								<?= $form->field($model, 'username')
                                ->textInput([
                                    'autofocus' => true,
                                    'class' => 'form-control',
                                    'placeholder' => '请输入用户名登录'
                                ])
                                ->label(false) ?> 
								<?= $form->field($model, 'password')
                                ->textInput([
                                    'autofocus' => true,
                                    'class' => 'form-control password',
                                    'placeholder' => '请输入登录密码'
                                ])
                                ->label(false) ?> 
                        
							</div>
									<div class="form-inline" style="margin-bottom: 15px;">
				<div class="pull-right">
					<a href="./index.php?c=user&a=find-password&" target="_blank" class="color-default">忘记密码？</a>
				</div>
				<div class="checkbox">
            		<input type="checkbox" name='loginForm[rememberMe]' value="1">
					<label for="rember">记住用户名</label>
				</div>
			</div>
            <div class="login-submit text-center">
            <input type="submit" id="submit" name="submit" value="登录" class="btn btn-primary btn-block" />
            <div class="text-right">
            <a href="./index.php?c=user&a=register&" class="color-default">立即注册</a>
                            </div>
            </div>
 <?php ActiveForm::end(); ?>
	</div>
</div>
</div>
<div class="container-fluid footer text-center" role="footer">	
<div class="friend-link">
                <a href="#">开发</a>
    <a href="#">应用</a>
    <a href="#">论坛</a>
    <a href="#">联系客服</a>
        </div>
<div class="copyright">Powered by </div>
<div></div>
</div>
</div>