<?php

namespace Mabrouk\Mediable\Tests\Models;

use Illuminate\Auth\Authenticatable;
use Mabrouk\Mediable\Traits\Mediable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use Mediable, Authorizable, Authenticatable;

    protected $guarded = [];

    protected $table = 'users';
}