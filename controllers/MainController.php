<?php

namespace app\controllers;

use app\models\Entry;
use app\models\Settings;
use app\models\User;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\AuthForm;
use yii\web\NotFoundHttpException;
/**
 * Основной контроллер приложения
 * */
class MainController extends Controller
{
    /**
     * @var $rows integer количество записей, отображаемых на странице
     * */
    private $rows = 10;

    /**
     * @inheritdoc
     * */
    public function init()
    {
        // Если пользователь аутентифицирован
        if(!Yii::$app->user->isGuest)
        {
            // Получаем его запись из БД
            $user = $this->findUser();

            // Устанавливаем ему тему bootstrap, которую он выбрал
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap\BootstrapAsset' => [
                    // Задаём источник файлов bootstrap (важно скопировать весь дистрибутив, иначе могут полететь glyphicons)
                    'sourcePath' => '@app/themes/' . $user->theme,
                    // Указываем путь к нашему css
                    'css' => [$user->theme . '.css'],
                ],
            ];
        }
        // Вызываем родительский метод
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        // Правила доступа
        return [
            // Фильтры доступа
            'access' => [
                'class' => AccessControl::className(),
                /*
                 * Действия выхода и действий пользователя
                 * доступны только тем, кто аутентифицирован
                 * */
                'only' => ['logout', 'my-entries', 'settings'],
                'rules' => [
                    [
                        'actions' => ['logout', 'my-entries', 'settings'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            // Фильтр по типам запроса
            'verbs' => [
                'class' => VerbFilter::className(),
                // Выход осуществляется только через post
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Главная страница
     *
     * @return string результат
     */
    public function actionIndex()
    {
        // Если пользователь аутентифицирован
        if(!Yii::$app->user->isGuest)
        {
            // Находим его и получаем число отображаемых записей
            $user = $this->findUser();
            $this->rows = $user->rows;
        }

        // Находим записи в БД
        $entries = Entry::find();

        // Добавляем пагинацию
        $pagination = new Pagination([
            'defaultPageSize' => $this->rows,
            'totalCount' => $entries->count(),
        ]);

        // Делаем запрос
        $model = $entries->orderBy('created DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        // Отображаем главную
        return $this->render('index', [
            'model' => $model,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Аутентификация пользователя.
     *
     * @return string результат
     */
    public function actionLogin()
    {
        // Если пользователь аутентифицирован
        if (!Yii::$app->user->isGuest) {
            // Отправляем его на главную
            return $this->goHome();
        }

        // Создаём экземпляр модели
        $model = new AuthForm();
        // Задаём модели сценарий входа
        $model->scenario = AuthForm::SCENARIO_LOGIN;

        // Если вход удался
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Возвращаем пользователя на предыдущую страницу
            return $this->goBack();
        }
        // Отображаем форму входа
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Регистрация пользователя
     *
     * @return string результат
     * */
    public function actionRegister()
    {
        // Если пользователь аутентифицирован
        if (!Yii::$app->user->isGuest) {
            // Отправляем его на главную
            return $this->goHome();
        }

        // Создаём модель формы входа
        $model = new AuthForm();
        // Устанавливаем сценарием регистрацию пользователя
        $model->scenario = AuthForm::SCENARIO_REGISTER;

        // Если удалось зарегистрироваться
        if($model->load(Yii::$app->request->post()) && $model->register())
        {
            // Добавляем уведомление
            Yii::$app->session->setFlash('alert', 'Вы успешно зарегистрировались! Для подтверждения регистрации 
                                                        перейдите по ссылке, указанной в email');
            // Возвращаем пользователя на главную
            return $this->goHome();
        }
        // Отображаем форму регистрации
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Подтверждение email пользователя
     *
     * @param $hash string случайная строка символов, идентифицирующая данного пользователя
     *
     * @return string результат
     * @throws NotFoundHttpException 404-е исключение, если пользователь не найден
     * */
    public function actionConfirmEmail($hash)
    {
        // Если пользователь найден
        if(($user = User::findOne(['hash' => $hash])) !== null)
        {
            // Добавляем уведомление
            Yii::$app->session->setFlash('alert',
                'Ваш email подтверждён! Теперь вы можете войти в приложение.' );

            // Устанавливаем hash в null
            $user->hash = null;
            // Сохраняем запись
            $user->save();
            // Отправляем пользователя на домашнюю страницу
            return $this->goHome();
        }
        // Иначе - выбрасываем исключение
        else
        {
            throw new NotFoundHttpException('Страница, которую вы запрашиваете, не существует.');
        }
    }

    /**
     * Выход их приложения
     *
     * @return string результат
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Добавление новой статьи
     *
     * @return string результат
     * */
    public function actionAddEntry()
    {
        // Заводим новый экземпляр модели
        $model = new Entry();
        // По-умолчанию сценарием будет создание анонимной записи
        $model->scenario = Entry::SCENARIO_ANONYMOUS;
        // Если пользователь аутентифицирован
        if(!Yii::$app->user->isGuest)
        {
            $user = $this->findUser();
            // Меняем сценарий
            $model->scenario = Entry::SCENARIO_REGISTERED;
            $model->user = $user->id;
            $model->email = $user->email;
            $model->name = $user->name;
        }
        // Если модель загружена через post и сохранена
        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            // Устанавливаем уведомление
            Yii::$app->session->setFlash('alert', 'Ваша запись успешно добавлена!');
            // И возвращаем пользователя на главную
            return $this->goHome();
        }
        // Возвращаем форму с новой записью
        return $this->render('add-entry', [
            'model' => $model,
        ]);
    }

    /**
     * Записи текущего пользователя
     *
     * @return string результат
     * */
    public function actionMyEntries()
    {
        // Если пользователь аутентифицирован
        if(!Yii::$app->user->isGuest)
        {
            // Находим его и получаем число отображаемых записей
            $user = $this->findUser();
            $this->rows = $user->rows;
        }

        // Находим записи текущего пользователя
        $entries = Entry::find()->where(['user' => Yii::$app->user->id]);

        // Создаём пагинацию
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $entries->count(),
        ]);

        // Делаем запрос
        $model = $entries->orderBy('created DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        // Отображаем записи
        return $this->render('my-entries', [
            'model' => $model,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Настройки пользователя
     * */
    public function actionSettings()
    {
        // Заводим объект настроек
        $model = new Settings();

        // Если удалось загрузить данные из запроса и сохранить их
        if($model->load(Yii::$app->request->post()) && $model->save())
        {
            // Добавляем уведомление
            Yii::$app->session->setFlash('alert', 'Ваши настройки успешно сохранены');
            // Перенаправляем на главную страницу
            return $this->goHome();
        }
        // Отображаем страницу с настройками
        return $this->render('settings', [
            'model' => $model
        ]);
    }

    /**
     * Находит запись в БД, соответствующий текущему пользователю системы
     *
     * @return User найденный пользователь
     *
     * @throws NotFoundHttpException исключение, если пользователь не найден
     * */
    private function findUser()
    {
        // Если пользователь найден
        if(($user = User::findOne(Yii::$app->user->id)) !== null)
        {
            // Возвращаем его
            return $user;
        }
        // Иначе - выбрасываем исключение
        else
        {
            throw new NotFoundHttpException('Страница, которую вы запрашиваете, не существует.');
        }
    }
}
