<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Nicolaslopezj\Searchable\SearchableTrait;


class Product extends Model implements SluggableInterface
{
  use SluggableTrait;
  use SearchableTrait;

  protected $searchable = [
      'columns' => [
        'products.name' => 10,
        'tiers.name'=>5,
        'td.title'=>5,
        'btd.title'=>5,
        'flavors.name'=>5,
        'td.content'=>4,
        'btd.content'=>4,
        'tiers.se_title'=>3,
        'bt.name'=>3,
        'tiers.se_description'=>2,
        'products.tf_title'=>1,
        'products.tf_text'=>1,
        'product_variations.size'=>1,
        'products.vfm_text'=>1,
        'products.hiw_ingredients'=>1,
        'products.hiw_text'=>1,
        'products.benefit_1'=>1,
        'products.benefit_2'=>1,
        'products.benefit_3'=>1,
      ],
      'joins' => [
        'product_variations' => ['products.id','product_variations.product_id', 'product_variations.is_active', 1],
        'product_tiers' => ['product_tiers.product_id', 'products.id'],
        'tiers as bt' => ['bt.id', 'products.id'],
        'tiers'=>['tiers.id', 'product_tiers.tier_id'],
        'tier_descriptions as td'=>['td.tier_id', 'tiers.id'],
        'tier_descriptions as btd'=>['btd.tier_id', 'bt.id'],
        'flavors'=>['flavors.id', 'product_variations.flavor_id'],
      ],
  ];

  protected $sluggable = [
    'build_from' => 'name',
    'save_to'    => 'slug',
  ];
  
  
  public function scopeActive($query)
  {
    return $query->where('products.is_active', '=', 1);
  }
  
  public function variations()
  {
    return $this->hasMany(ProductVariation::class);
  }
  
  public function flavors()
  {
    return $this->belongsToMany(Flavor::class, 'product_variations');
  }
  
  public function lowest_cost_variation()
  {
    return $this->variations()->orderBy('price', 'asc')->first();
  }

  /**
   * @return HasOne
   */
  public function brand()
  {
    return $this->hasOne(Tier::class, 'id', 'brand_id');
  }
}
