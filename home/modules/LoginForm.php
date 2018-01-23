<?php
namespace app\modules;
use Yii;
use common\models\BaseModel;
use common\models\User;

/**
 * Login form
 */
class LoginForm extends BaseModel
{
    public $username;
    public $password;
    public $verify_code;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required','message'=>Yii::t('app','must')],
            ['password', 'validatePassword', 'message'=>'password'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute,Yii::t('app','passwordError'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
			$userData = $this->getUser();
			$model = User::findOne($userData->id);
			$model->login_ip   = $_SERVER["REMOTE_ADDR"];
			$model->login_time = date('Y-m-d H:i:s',time());
			$model->login_num  = $model->login_num + 1;
			$model->save();
            return Yii::$app->user->login($model, $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === NULL) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
