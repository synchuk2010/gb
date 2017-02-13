<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Представляет объект настроек пользователя
 * */
class Settings extends Model
{
    /**
     * @var $rows integer количество записей на страницу
     * */
    public $rows;
    /**
     * @var $theme string Bootstrap-тема гостевой книги, выбранная пользователем
     * */
    public $theme;
    /**
     * @var $name string отображаемое имя
     * */
    public $name;

    /**
     * @inheritdoc
     * */
    public function __construct(array $config = [])
    {
        // Инициализируем модель начальными настройками

        // Ищем пользователя
        $user = User::find()
            ->where(['id' => Yii::$app->user->id])
            ->one();

        // Задаём настройки из БД
        $this->name = $user->name;
        $this->theme = $user->theme;
        $this->rows = $user->rows;

        // Вызываем родительский конструктор
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     * */
    public function rules()
    {
        // Правила валидации
        return [
            // Оба поля обязательны
            [['rows', 'theme', 'name'], 'required'],
            // Количество записей должно быть числом
            ['rows', 'integer'],
            // Длина имени - не больше 150 символов
            [['name'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     * */
    public function attributeLabels()
    {
        // Переопределяем метки атрибутов
        return [
            'theme' => 'Тема',
            'rows' => 'Количество записей',
            'name' => 'Отображаемое имя'
        ];
    }

    /**
     * Сохраняет настройки пользователя
     *
     * @return boolean успешность результата сохранения
     * */
    public function save()
    {
        // Если валидация модели проходит успешно
        if($this->validate())
        {
            $user = User::findOne(Yii::$app->user->id);

            $user->rows = $this->rows;
            $user->theme = $this->theme;
            $user->name = $this->name;

            return $user->save();
        }
        return false;
    }
}