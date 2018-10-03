<?php

namespace App\Model;

use App\User;
use App\Model\Category;
use App\Model\Sale;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

  protected $fillable = [
    'name', 'price', 'about', 'category_id', 'qty', 'cover_img', 'user_id',
  ];

  public function user()
  {
  	return $this->belongsTo(User::class);
  }

  public function category()
  {
  	return $this->belongsTo(Category::class);
  }

  public function sales()
  {
    return $this->hasMany(Sale::class);
  }
}
