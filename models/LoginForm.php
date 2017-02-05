<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Модель для формы входа пользователя
 * */
class LoginForm extends Model
{
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
     * @inheritdoc
     * */
    public function rules()
    {
        return [
            // Email и пароль обязательны
            [['email', 'password'], 'required'],
            // Email должен быть в правильном формате
            ['email', 'email'],
            // rememberMe должна быть логическим значением
            ['rememberMe', 'boolean'],
            // Валидацией пароля занимается validatePassword()
            ['password', 'validatePassword'],
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

    /**
     * Аутентифицирует пользователя по заданным email и паролю.
     *
     * @return bool истина/ложь, в зависимости от успеха
     */
    public function login()
    {
        // Если модель проходит валидацию
        if ($this->validate()) {
            // Аутентифицируем пользователя (если установлен флаг "Запомнить меня", запоминаем пользователя)
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        // Возвращаем ложь
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