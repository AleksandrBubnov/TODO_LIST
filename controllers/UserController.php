<?php

namespace controllers;

use core\BaseController;

class UserController extends BaseController
{
    public function actionIndex()
    {
        // require_once './views/_shared/header.php';
        // require_once './views/user/index.php';
        // require_once './views/_shared/footer.php';

        // $this->redirect('/list/index'); 
        // $this->redirect('/user/create');
        $this->layot = true;
        $this->render('index', ['model' => ['id' => 1, 'task' => 'mytask']]);
    }
    public function actionCreate()
    {
        echo 'create';
    }
}
