<?php

namespace controllers;

use core\BaseController;
use models\UserModel;
use service\SendEmail;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->layot = true;
    }
    public function actionIndex()
    {
        // require_once './views/_shared/header.php';
        // require_once './views/user/index.php';
        // require_once './views/_shared/footer.php';

        // $this->redirect('/list/index'); 
        // $this->redirect('/user/create');

        $this->render('index', ['model' => ['id' => 1, 'task' => 'mytask']]);
    }
    public function actionLogin()
    {
        // post
        // email, password

        $user = new UserModel;
        if ($user->loadPost() && $user->validate()) {

            $user->password = $this->passwordHasher($user->password);
            $user = UserModel::find()->where(['email' => $user->email, 'password' => $user->password])->one();
            if ($user) {
                if ($user->confirm_email == "1") {
                    if (!isset($_SESSION)) session_start();
                    $this->redirect('/list/index');
                } else {
                    if (!isset($_SESSION)) session_start();
                    $_SESSION['error'] = 'You need to confirm your registration (by clicking on the link in the email).';
                    $this->render('index');
                }
            } else {
                if (!isset($_SESSION)) session_start();
                $_SESSION['error'] = "The user is not registered. Check the email or password.";
                $this->render('index');
            }
        } else {
            $this->render('index');
        }
    }

    public function actionRegister()
    {
        $user = new UserModel;
        if ($user->loadPost() && $user->validate()) {
            // проверка существует ли такой юзер
            if (!UserModel::find()->where(['email' => $user->email])->one()) {
                $user->password = $this->passwordHasher($user->password);
                if ($user->save()) {
                    if (!isset($_SESSION)) session_start();
                    if (SendEmail::send($user->email, $user->id)) {
                        $_SESSION['success'] = 'User is registered. Confirm registration by following the link in the email.';
                        $this->render('index');
                    } else {
                        $_SESSION['error'] = 'Error is user`s register.';
                        $this->render('index');
                        // удалить пользователя
                        // 
                    }
                } else {
                    if (!isset($_SESSION)) session_start();
                    $_SESSION['error'] = 'Error is user`s register.';
                    $this->render('index');
                }
            } else {
                if (!isset($_SESSION)) session_start();
                $_SESSION['error'] = "User with " . $user->email . "has already registered.";
                $this->render('index');
            }
        } else {
            $this->render('index');
        }
    }

    public function actionConfirm()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $id = $_GET['id'];
            $user = UserModel::find()->where(['id' => $id])->one();
            $user->confirm_email = true;
            if ($user->save()) {
                $_SESSION['success'] = 'Registration is confirmed.';
                $this->redirect('/list/index');
            } else {
                if (!isset($_SESSION)) session_start();
                $_SESSION['error'] = 'Error is confirming the email.';
                $this->render('index');
                // $this->redirect('/user/index');
            }
        }
    }
}
