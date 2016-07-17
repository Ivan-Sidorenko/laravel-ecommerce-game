<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    /**
     * @return BelongsTo
     */
    public function flavor()
    {
        return $this->belongsTo(Flavor::class);
    }

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return string
     */
    public function getViewProductUrlAttribute()
    {
        return route('products.view', ['slug'=>$this->product->slug, 'vid'=>$this->id]);
    }

    /**
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return  "/images/products/product_variations/".$this->image;
    }

    /**
     * @return string
     */
    public function getSummaryAttribute()
    {
        return $this->summary(true);
    }

    /**
     * @param bool|true $with_price
     * @return string
     */
    public function summary($with_price=true)
    {
        $s = sprintf("%s %s", $this->size, $this->flavor ? " {$this->flavor->name} " : '');
        if($with_price)
        {
            $s .= sprintf(" - £%4.2f ea", $this->price);
        }
        return $s;
    }
}
