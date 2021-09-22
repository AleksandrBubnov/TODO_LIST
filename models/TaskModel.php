<?php

namespace models;

use core\BaseModel;

class TaskModel extends BaseModel
{
    public  $id;
    public  $name;
    public  $user_id;
    public  $list_id;
    public  $completed;
    public  $position;
    public  $created_at;
    public  $completed_at;

    static $table = 'tasks';

    public function rules(): array
    {
        return [
            'string' => ['name'],
            'integer' => ['user_id'],
            'integer' => ['list_id'],
            'required' => ['name', 'user_id', 'list_id']
        ];
    }
}
