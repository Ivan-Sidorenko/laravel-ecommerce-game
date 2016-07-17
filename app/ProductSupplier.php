<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
  protected $fillable = ['product_id', 'supplier_id'];
}
