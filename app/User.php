<?php

namespace App;

use app\Model\Category;
use app\Model\Product;
use app\Model\Txn;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
      'name', 'email', 'password',
    ];

    protected $hidden = [
      'password', 'remember_token',
    ];

    public function categories()
    {
      return $this->hasMany(Category::class);
    }

    public function products()
    {
      return $this->hasMany(Product::class);
    }

    public function txns()
    {
      return $this->hasMany(Txn::class);
    }
}
