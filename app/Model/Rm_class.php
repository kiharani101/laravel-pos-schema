<?php

namespace App\Model;

use App\Model\Room;
use Illuminate\Database\Eloquent\Model;

class Rm_class extends Model
{
  protected $guarded = [];

  public function rooms()
  {
    return $this->hasMany(Room::class);
  }

}
