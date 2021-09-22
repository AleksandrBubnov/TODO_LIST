<?php

namespace controllers;

use core\BaseController;
use models\TaskModel;
use models\ListModel;

class TaskController extends BaseController
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
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $user_id = $_SESSION['Auth'];
            $list_id = $_GET['list_id'];
            $order = null;
            if (!empty($_GET['order'])) {
                $order = ['order' => $_GET['order']];
            }
            $tasks = TaskModel::find()->where(['user_id' => $user_id, 'list_id' => $list_id])->order($order)->all();
            $list = ListModel::find()->where(['id' => $list_id])->one();
            $this->render('index', ['tasks' => $tasks, 'list' => $list]);
        }
    }

    public function actionPosition() // get // id - value_position
    {
        $order = null;
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
            $order = "&order=$order";
        }

        $list_id = $_GET['list_id'];
        $task_id = $_GET['task_id'];
        $direction = $_GET['direction']; //rArr//lArr

        $task = TaskModel::find()->where(['id' => $task_id])->one();
        if ($task->loadGet() && $task->validate()) {
            switch ($direction) {
                case 'rArr':
                    $tmp = intval($task->position);
                    $tmp++;
                    $task->position = "$tmp";
                    break;
                case 'lArr':
                    $tmp = intval($task->position);
                    if ($tmp > 1) {
                        $tmp--;
                        $task->position = "$tmp";
                    } else {
                        $task->position = "DEFAULT";
                    }
                    break;
            }
            if (!$task->completed) {
                $task->completed = "DEFAULT";
            }

            if ($task->save()) {
                // $_SESSION['success'] = 'Update success.';
                $this->redirect("/task/index/?list_id=$list_id{$order}");
            } else {
                if (!isset($_SESSION)) session_start();
                $_SESSION['error'] = 'Update Error.';
            }
        }
    }
    public function actionCompleted() // ajax // send id_task and status
    {
        $task_id = $_GET['task_id'];
        $task = TaskModel::find()->where(['id' => $task_id])->one();
        $task->completed = $_GET['completed'];
        if ($task->completed) {
            $task->completed_at = date("Y-m-d H:i:s");
            $task->completed = "1";
        } else {
            $task->completed = "0";
            $task->completed_at = NULL;
        }
        if ($task->loadGet() && $task->validate()) {
            $result_upd = $task->update();
            if ($result_upd) {
                echo json_encode($result_upd);
                // $_SESSION['success'] = 'Update success.';
            } else {
                // if (!isset($_SESSION)) session_start();
                // $_SESSION['error'] = 'Update Error.';
            }
        }
    }
    public function actionCreate() // два поля // name - position
    {
        $order = null;
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
            $order = "&order=$order";
        }

        $task = new TaskModel;
        if (!isset($_SESSION)) session_start();

        $task->user_id = $_SESSION['Auth'];
        $task->list_id = $_GET['list_id'];

        if ($task->loadPost() && $task->validate()) {
            if (!$task->position) {
                $task->position = "DEFAULT";
            }
            if ($task->save()) {
                $_SESSION['success'] = 'Create success.';
                $this->redirect("/task/index/?list_id=$task->list_id{$order}");
            } else {
                if (!isset($_SESSION)) session_start();
                $_SESSION['error'] = 'Create Error.';
            }
        }

        $this->render('create', ['task' => $task]);
    }
    public function actionUpdate() // name - position - complete
    {
        $order = null;
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
            $order = "&order=$order";
        }

        $list_id = $_GET['list_id'];
        $task_id = $_GET['task_id'];
        $task = TaskModel::find()->where(['id' => $task_id])->one();

        if ($task->loadPost() && $task->validate()) {
            if ($task->completed) {
                $task->completed_at = date("Y-m-d H:i:s");
            } else {
                $task->completed = "DEFAULT";
            }
            if (!$task->position) {
                $task->position = "DEFAULT";
            }

            if ($task->save()) {
                $_SESSION['success'] = 'Update success.';
                // $this->redirect("/task/index/?list_id=$list_id");
                $this->redirect("/task/index/?list_id=$list_id{$order}");
            } else {
                if (!isset($_SESSION)) session_start();
                $_SESSION['error'] = 'Update Error.';
            }
        }

        $this->render('update', ['task' => $task]);
    }
    public function actionDelete()
    {
        $order = null;
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
            $order = "&order=$order";
        }

        $list_id = $_GET['list_id'];
        $task_id = $_GET['task_id'];
        if (TaskModel::delete(['id' => $task_id])) {
            $_SESSION['success'] = 'Delete success.';
        } else {
            if (!isset($_SESSION)) session_start();
            $_SESSION['error'] = 'Delete Error.';
        }

        $this->redirect("/task/index/?list_id=$list_id{$order}");
    }
}
