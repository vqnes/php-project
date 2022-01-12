<?php

namespace app\Models;

use core\base\Model;

class User extends Model
{
    public string $table = 'users';
    protected string $primaryKey = 'name';
}
