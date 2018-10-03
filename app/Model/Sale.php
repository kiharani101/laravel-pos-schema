<?php

namespace App\Model;
use App\Model\Txn;
use App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
	protected $fillable = [
    'txn_id', 'product_id', 'qty', 'price',
  ];

  public $timestamps = false;
  
  public function txn()
  {
  	return $this->belongsTo(Txn::class);
  }

  public function product()
  {
  	return $this->belongsTo(Product::class);
  }
}
