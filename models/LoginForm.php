<?php

namespace app\models\user;

use Yii;
use app\validators\TwoFactorCodeValidator;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    /**
     * @var string User's authentication code
     */
    public $authenticationCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // required fields
            [['email', 'password'], 'required', 'on' => 'login'],

            // remember me boolean
            [['rememberMe'], 'boolean'],

            // validate password
            ['password', 'validatePassword'],

            // valid email address
            [['email'], 'email'],

            // required field for 2fa
            [['authenticationCode'], 'required', 'on' => '2fa'],

            // trim 2fa code
            [['authenticationCode'], 'trim'],

            // validate 2fa code
            ['authenticationCode', function ($attribute, $params, $validator) {
                $code = strtoupper(str_replace(' ', '', $this->$attribute));

                if (!(new TwoFactorCodeValidator($this->user, $code))->validate()) {
                    $validator->addError($this, $attribute, 'Invalid {attribute}');
                }
            }],
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

            if (!$user || !$user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Incorrect sign in details.');
            }
        }
    }

    /**
     * Validates form and logs the user in.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days or 0

            return Yii::$app->getUser()->login($this->user, $duration);
        }

        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->username);
        }

        return $this->_user;
    }
}
