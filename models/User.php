<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

/**
 * Класс, представляющий пользователя
 *
 *
 * Получает информацию из таблицы с пользователями в БД
 * */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @var $authKey string ключ авторизации (для cookie-авторизации)
     * */
    public $authKey;

    /**
     * @inheritdoc
     * */
    public static function tableName()
    {
        // Имя таблицы с пользователями
        return 'users';
    }

    /**
     * @inheritdoc
     * */
    public function beforeSave($insert)
    {
        // Вызываем родительское событие
        if(parent::beforeSave($insert)) {
            // Если пользователь только что вошёл
            if($this->isNewRecord) {
                // Создаём ключ аутентификации
                $this->authKey = Yii::$app->security->generateRandomString();
            }
            // Обязательно делаем return true, иначе пользователь не будет сохраняться
            return true;
        }
        return false;
    }

    /*
     * Переопределяемые методы (необходимы для корректной работы IdentityInterface)
     * */

    /**
     * Находит личность по заданному идентификатору
     * @param string|integer $id Идентификатор, по которому производится поиск
     * @return IdentityInterface Личность, представленный этим идентификатором
     * Данный метод должен возвращать null, если личность не найдена,
     * или она в неактивном состоянии (удалена, заблокирована и т.д.)
     */
    public static function findIdentity($id)
    {
        // Возвращаем родительский метод Active Record для поиска записи
        return static::findOne($id);
    }

    /**
     * Находит личность по заданному токену (для работы аутентификации через сессии)
     * @param mixed $token токен, по которому производится поиск
     * @param mixed $type тип токена. Значение данного параметра зависит от реализации.
     * К примеру, [[\yii\filters\auth\HttpBearerAuth]] установит этот параметр в `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface объект личности, соответствующий заданному токену.
     * Данный метод должен возвращать null, если личность не найдена,
     * или она в неактивном состоянии (удалена, заблокирована и т.д.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Ищем личность по токену
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Возвращает идентификатор, который может однозначно определить личность пользователя
     *
     * @return string|integer идентификатор, однозначно определающий личность пользователя.
     */
    public function getId()
    {
        // Возвращаем идентификатор в таблице пользователей
        return $this->id;
    }

    /**
     * Возвращает ключ, который может проверить валидность переданной личности по её идентификатору.
     *
     * (Необходимо для авторизации по cookie)
     *
     * Ключ должен быть уникальным для каждого пользователя, а таекже обновляемым,
     * так он может быть использован для проверки валидности личности пользователя.
     *
     * Размер таких ключей должен быть достаточно большим, чтобы предотвратить потенциальные атаки.
     *
     * Необходимо, если [[User::enableAutoLogin]] активировано.
     * @return string ключ, проверяющий валидность переданного идентификатора личности.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // Вовращаем ключ
        return $this->authKey;
    }

    /**
     * Проверяет переданный ключ аутентификации.
     *
     * Необходимо, если [[User::enableAutoLogin]] активировано.
     * @param string $authKey полученный ключ аутентификации
     * @return boolean флаг, означаюший валидность переданного ключа.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // Сравниваем переданный ключ с существующим
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     * */
    public function save($runValidation = true, $attributeNames = null)
    {
        // Если запись только добавлена
        if($this->isNewRecord)
        {
            // Шифруем пароль
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }
        // Вызываем родительский метод
        return parent::save($runValidation, $attributeNames);
    }
}