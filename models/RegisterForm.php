<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 04.02.17
 * Time: 12:50
 */

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Форма для регистрации пользователя
 * */
class RegisterForm extends Model
{
    /**
     * @const определяет сценарий входа пользователя
     * */
    const SCENARIO_LOGIN = 'login';
    /**
     * @const определяет сценарий регистрации пользователя
     * */
    const SCENARIO_REGISTER = 'register';
    /**
     * @var $email string переданный email
     * */
    public $email;
    /**
     * @var $password string переданный пароль
     * */
    public $password;
    /**
     * @var $rememberMe bool флаг "Запомнить меня"
     * */
    public $rememberMe = true;
    /**
     * @var $_user User|false найденный пользователь (false, если пользователь не найден)
     * */
    private $_user = false;
    /**
     * @var $verifyCode string код подтверждения для капчи
     * */
    public $verifyCode;

    /**
     * @inheritdoc
     * */
    public function scenarios()
    {
        // Возвращаем правила работы сценариев
        return [
            // В сценарии для логина нужен только email и пароль
            self::SCENARIO_LOGIN => ['email', 'password'],
            // В сценарии для регистрации, нужен email, пароль и код подтверждения
            self::SCENARIO_REGISTER => ['email', 'password', 'verifyCode'],
        ];
    }

    /**
     * @inheritdoc
     * */
    public function rules()
    {
        // Указываем правило валидации для капчи
        return [
            // Логин и пароль обязательны
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'validateEmail', 'on' => self::SCENARIO_REGISTER],
            // rememberMe должна быть логическим значением
            ['rememberMe', 'boolean'],
            // Валидацией пароля занимается validatePassword()
            ['password', 'validatePassword', 'on' => self::SCENARIO_LOGIN],
            // Правило для капчи
            ['verifyCode', 'captcha', 'captchaAction' => 'main/captcha'],
        ];
    }

    /**
     * Производит валидацию пароля.
     * Метод, осуществляющий валидацию пароля, введённого пользователем.
     *
     * @param string $attribute аттрибут, который подлежит валидации
     */
    public function validatePassword($attribute)
    {
        // Если в модели нет ошибок
        if (!$this->hasErrors()) {
            // Ищем пользователя в источнике данных
            $user = $this->getUser();

            // Если пользователь не найден, или его пароль не совпадает с хранящимся в базе
            if (!$user || !Yii::$app->getSecurity()->validatePassword($this->password, $user->password)) {
                // Добавляем ошибку к данному атрибуту модели
                $this->addError($attribute, 'Неверный логин или пароль.');
            }
        }
    }

    /*
     *
     * */
    public function validateEmail($attribute)
    {
        if(!$this->hasErrors())
        {
            $user = $this->getUser();

            if($user != false)
            {
                $this->addError($attribute, 'Пользователь с таким email уже существует.');
            }
        }
    }

    /**
     * Аутентифицирует пользователя по заданным логину и паролю.
     *
     * @param $type string тип аутентификации (login/register)
     *
     * @return bool истина/ложь, в зависимости от успеха
     */
    public function auth($type)
    {
        if($this->validate())
        {
            if($type == 'login')
            {
                // Аутентифицируем пользователя (если установлен флаг "Запомнить меня", запоминаем пользователя)
                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
            }
            else
            {
                $model = new User();
                $model->email = $this->email;
                $model->password = $this->password;
                $model->name = $this->email;
                $model->hash = Yii::$app->security->generateRandomString(32);
                return $model->save();
            }
        }
        return false;
    }

    /**
     * Находит пользователя по [[email]]
     *
     * @return User|null если пользователь не найден, вернётся false
     */
    public function getUser()
    {
        // Если пользователь не установлен
        if ($this->_user === false) {
            // Пытаемся найти его в таблице
            $this->_user = User::findOne(['email' => $this->email]);
        }
        // Возвращаем пользователя
        return $this->_user;
    }
}