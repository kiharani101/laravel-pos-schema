<?php

namespace App\Model;

use App\Model\Rm_class;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  protected $guarded = [];

  public function rm_class()
  {
  	return $this->belongsTo(Rm_class::class);
  }

}
