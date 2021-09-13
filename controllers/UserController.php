<?php

namespace controllers;

use core\BaseController;
use models\UserModel;

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
        var_dump($_POST);
        die();
    }

    public function actionRegister()
    {
        $user = new UserModel;
        if ($user->loadPost() || $user->validate()) {
            // проверка существует ли такой юзер
            if ($user->save()) {
                if (!isset($_SESSION)) session_start();

                $_SESSION['success'] = 'User is registered';
                $this->render('index');
                // var_dump($user);
                // die();
            } else {
                if (!isset($_SESSION)) session_start();
                $_SESSION['error'] = 'Error is user`s register';
                $this->render('index');
            }
        } else {
            $this->render('index');
        }
    }
}
