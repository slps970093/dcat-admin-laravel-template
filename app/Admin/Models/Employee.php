<?php

namespace App\Admin\Models;

use Dcat\Admin\Models\Administrator;

class Employee extends Administrator
{

    protected $fillable = ['username', 'password', 'name', 'avatar', 'is_superuser'];

    protected $casts = [
        'is_superuser' => 'boolean'
    ];
}
