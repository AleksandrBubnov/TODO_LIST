<?php

namespace controllers;

use core\BaseController;
use models\ListModel;

class ListController extends BaseController
{
    public function __construct()
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['Auth'])) $this->redirect('/user/index');
        $this->layot = true;
    }
    public function actionIndex()
    {
        if (!isset($_SESSION)) session_start();
        $user_id = $_SESSION['Auth'];
        $lists = ListModel::find()->where(['user_id' => $user_id])->all();
        $this->render('index', ['lists' => $lists]);
    }

    public function actionCreate()
    {
        $list = new ListModel;
        if (!isset($_SESSION)) session_start();

        $list->user_id =  $_SESSION['Auth'];

        if ($list->loadPost() && $list->validate()) {
            if ($list->save()) {
                $_SESSION['success'] = 'Create success.';
                $this->redirect('/list/index');
            } else {
                if (!isset($_SESSION)) session_start();
                $_SESSION['error'] = 'Create Error.';
            }
        }

        $this->render('create', ['list' => $list]);
    }
    public function actionUpdate()
    {
        $list_id = $_GET['list_id'];
        $list = ListModel::find()->where(['id' => $list_id])->one();
        if ($list->loadPost() && $list->validate()) {
            if ($list->save()) {
                $_SESSION['success'] = 'Update success.';
                $this->redirect('/list/index');
            } else {
                if (!isset($_SESSION)) session_start();
                $_SESSION['error'] = 'Update Error.';
            }
        }

        $this->render('create', ['list' => $list]);
    }
    public function actionDelete()
    {
        $list_id = $_GET['list_id'];
        if (ListModel::delete(['id' => $list_id])) {
            $_SESSION['success'] = 'Delete success.';
        } else {
            if (!isset($_SESSION)) session_start();
            $_SESSION['error'] = 'Delete Error.';
        }

        $this->redirect('/list/index');
    }
}
