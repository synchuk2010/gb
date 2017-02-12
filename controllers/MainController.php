<?php

namespace app\controllers;

use app\models\Entry;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\AuthForm;
use app\models\ContactForm;
use yii\web\NotFoundHttpException;

class MainController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Действие логина.
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
     * Действие регистрации
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
     * Подтверждает email пользователя
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
     * Действие выхода из приложения.
     *
     * @return string результат
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Действие добавления новой статьи
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
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
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
        if(($user = User::findOne(Yii::$app->user->id)) !== null)
        {
            return $user;
        }
        else
        {
            throw new NotFoundHttpException('Страница, которую вы запрашиваете, не существует.');
        }
    }
}
