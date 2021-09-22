<?php

namespace models;

use core\BaseModel;

class ListModel extends BaseModel
{
    public  $id;
    public  $name;
    public  $user_id;
    public  $created_at;

    static $table = 'lists';

    public function rules(): array
    {
        return [
            'string' => ['name'],
            'integer' => ['user_id'],
            'required' => ['name', 'user_id']
        ];
    }
}
