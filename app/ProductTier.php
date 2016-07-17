<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTier extends Model
{
  protected $fillable = ['product_id', 'tier_id'];
}
