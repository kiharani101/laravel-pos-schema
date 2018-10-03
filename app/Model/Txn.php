<?php

namespace App\Model;
use App\User;
use App\Model\Sale;

use Illuminate\Database\Eloquent\Model;

class Txn extends Model
{
	protected $fillable = [
    'user_id', 'total_cost', 'mdpayment',
  ];

  public function user()
  {
  	return $this->belongsTo(User::class);
  }

  public function sales()
  {
  	return $this->hasMany(Sale::class);
  }
}
