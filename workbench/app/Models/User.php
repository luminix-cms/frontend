<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Luminix\Backend\Model\LuminixModel;
use Workbench\Database\Factories\UserFactory;

class User extends Authenticatable
{
    use LuminixModel, HasFactory;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
