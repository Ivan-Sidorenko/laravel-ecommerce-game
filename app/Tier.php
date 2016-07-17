<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tier extends Model
{
  static function type($t)
  {
    return call_user_func('App\Tier::'.$t);
  }
  
  static function brand()
  {
    return Tier::where('type', 'brand');
  }

  static function category()
  {
    return Tier::where('type', 'cat');
  }

  static function ingredient()
  {
    return Tier::where('type', 'Ingred');
  }

  static function goal()
  {
    return Tier::where('type', 'Goal');
  }

  
  function scopeCategory($query)
  {
    $query->whereType('Cat');
  }

}
