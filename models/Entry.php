<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\HtmlPurifier;

/**
 * Класс, представляющий модель записи в таблице.
 *
 * @property integer $id номер записи
 * @property string $name имя автора
 * @property string $email email автора
 * @property string $message содержание записи
 * @property string $created дата создания
 * @property integer $user пользователь, добавивший запись
 * @property string $verifyCode код подтверждения для капчи
 *
 * @property User $userLink - пользователь
 */
class Entry extends ActiveRecord
{
    /**
     * @const определяет сценарий анонимного добавления записи
     * */
    const SCENARIO_ANONYMOUS = 'anonymous';
    /**
     * @const определяет сценарий добавления записи зарегистрированным пользователем
     * */
    const SCENARIO_REGISTERED = 'registered';

    /**
     * Поле для хранения капчи
     * */
    public $verifyCode;

    /**
     * @inheritdoc
     * */
    public function scenarios()
    {
        // Возвращаем правила для сценариев
        return [
            // Если запись добавляется анонимно - нужны дополнительные данные
            self::SCENARIO_ANONYMOUS => ['name', 'email', 'message', 'verifyCode'],
            // Если пользователь аутентифицирован - нужен только текст сообщения
            self::SCENARIO_REGISTERED => ['message'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        // Правила валидации
        return [
            // Имя, email и сообщение - обязательны для заполнения
            [['name', 'email', 'message'], 'required'],
            // Email должен быть в правильном формате
            ['email', 'email'],
            // Сообщение
            [['message'], 'string'],
            // Дата создания
            [['created'], 'safe'],
            // Поле пользователя должно быть идентификатором
            [['user'], 'integer'],
            // Длина полей
            [['name'], 'string', 'max' => 150],
            [['email'], 'string', 'max' => 100],
            [
                ['user'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user' => 'id']
            ],
            // Правило для капчи
            ['verifyCode', 'captcha', 'captchaAction' => 'main/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        // Метки атрибутов
        return [
            'id' => 'Номер',
            'name' => 'Автор',
            'email' => 'Email автора',
            'message' => 'Сообщение',
            'created' => 'Дата создания',
            'user' => 'Пользователь',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLink()
    {
        // Связь с таблице пользователей
        return $this->hasOne(User::className(), ['id' => 'user']);
    }

    /**
     * @inheritdoc
     * */
    public function save($runValidation = true, $attributeNames = null)
    {
        // Перед сохранением - очищаем входной html от вредоносных тегов
        $this->message = HtmlPurifier::process($this->message);
        // Вызываем родительский метод сохранения
        return parent::save($runValidation, $attributeNames);
    }
}