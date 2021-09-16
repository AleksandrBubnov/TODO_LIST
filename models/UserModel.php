<?php

namespace models;

use core\BaseModel;

class UserModel extends BaseModel
{
    public  $id;
    public  $email;
    public  $password;
    public  $confirm_email;

    static $table = 'users';

    public function rules(): array
    {
        return [
            'string' => ['password'],
            'email' => ['email'],
            'required' => ['password', 'email']
        ];
    }
}
