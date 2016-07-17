<?php
namespace App\Classes;
  
use App\Flavor;
use App\Tier;
use App\TierDescription;
use App\Product;
use App\ProductTier;
use App\ProductSupplier;
use App\Supplier;
use App\ProductVariation;

class DataImporter
{
  function import(\SplFileInfo $file)
  {
    $fp = $file->openFile('r');
    $header = $fp->fgetcsv();
    $types = [
      'Flavour Name.Image'=>[
        'callback'=>'import_flavor',
        'cols'=>['name', 'image_url'],
        'required'=>['name'],
        'suspicious'=>['image_url'],
      ],
      'Ref.Supplier Name'=>[
        'callback'=>'import_supplier',
        'cols'=>['ref', 'name'],
        'required'=>['ref', 'name'],
        'suspicious'=>[],
      ],
      'Ref.Type'=>[
        'callback'=>'import_tier',
        'cols'=>['ref', 'type', 'name', 'banner_url', 'title_1', 'content_1', 'title_2', 'content_2', 'title_3', 'content_3', 'se_title', 'se_description', 'url_slug'],
        'required'=>['ref', 'type'],
        'suspicious'=>['name', 'banner_url', 'title_1', 'content_1', 'se_title', 'se_description'],
      ],
      'Master Sku.Product Name.Brand'=>[
        'callback'=>'import_product',
        'cols'=>['sku', 'name', 'is_featured', 'brand', 'categories', 'ingredients', 'goals', 'suppliers', 'banner_url', 'is_active'],
        'required'=>['sku', 'name', 'brand', ],
        'suspicious'=>['banner_url', 'categories', 'ingredients', 'goals', 'suppliers'],
      ],
      'Master Sku.Product Variation Name'=>[
        'callback'=>'import_product_variation',
        'cols'=>['sku', 'name', 'subsku', 'size', 'flavor', 'rrp', 'price', 'servings', 'cs', 'stock', 'weight', 'width', 'length', 'depth', 'is_active', 'image_url'],
        'required'=>['sku', 'name', 'subsku', 'price',],
        'suspicious'=>['rrp', 'size', 'servings', 'cs', 'weight', 'image_url'],
      ],
      'T&F Title'=>[
        'callback'=>'import_product_content',
        'cols'=>['sku', 'name', 'mixability', 'tf_title', 'tf_text', 'tf_image_url', 'tf_reviews_enabled', 'vfm_score', 'vfm_text', 'hiw_ingredients', 'hiw_active_ingredient_count', 'benefit_1', 'benefit_2', 'benefit_3', 'hiw_text'],
        'required'=>['sku'],
        'suspicious'=>[],
      ],
    ];
    $type = null;
    foreach($types as $type_key=>$type_info)
    {
      $keys = explode('.', $type_key);
      $common = array_intersect($keys, $header);
      if(count($common)==count($keys))
      {
        $type = $type_key;
        break;
      }
    }

    $this->idx = 1;
    $this->warnings = [];
    $this->errors = [];

    if(!($type && isset($types[$type])))
    {
      $this->errors[] = sprintf('Unrecognized file format %s', $file->getClientOriginalName());
      return;
    }
    
    $info = $types[$type];
    while($row = $fp->fgetcsv())
    {
      error_log(join('|', $row));
      $this->idx++;
      $this->o = $this->map_row($info['cols'], $row);
      foreach($info['required'] as $field_name)
      {
        if($this->o->$field_name) continue;
        $this->errors[] = sprintf("Required: Row %d is missing required field %s. Skipped.", $this->idx, $field_name);
        continue 2;
      }
      foreach($info['suspicious'] as $field_name)
      {
        if($this->o->$field_name) continue;
        $this->warnings[] = sprintf("Suspicious: Row %d is missing data for field %s. Processing anyway.", $this->idx, $field_name);
      }
    
      $this->$info['callback']();
    }
  }
  
  function import_flavor()
  {
    $o = $this->o;
    $f = Flavor::where('name', $o->name)->first();
    if(!$f)
    {
      $f = new Flavor();
    }
    $f->name = $o->name;
    $f->image = $this->process_image($o->image_url, 'flavours');
    $f->save();
  }
  
  function import_supplier()
  {
    $o = $this->o;
    $f = Supplier::where('ref', $o->ref)->first();
    if(!$f)
    {
      $f = new Supplier();
    }
    $f->ref = $o->ref;
    $f->name = $o->name;
    $f->save();
  }    
  
  function map_row($cols, $row)
  {
    $o = new \stdclass();
    foreach($cols as $idx=>$v)
    {
      $o->$v = trim($row[$idx]);
      if($o->$v=='') $o->$v = null;
    }
    return $o;
  }

  function process_image($new_url, $folder_name)
  {
    $image_url = trim($new_url);
    if(!$image_url) return '';
    $img_name = sprintf("%s-%s", md5($image_url), basename($image_url));
    $img_fpath = public_path("images/products/{$folder_name}/{$img_name}");
    if(!file_exists($img_fpath))
    {
      $img = @file_get_contents($image_url);
      if($img===false) return null;
      $img_dir = dirname($img_fpath);
      if (!file_exists($img_dir)) {
        mkdir($img_dir, 0777, true);
      }
      file_put_contents($img_fpath, $img);
    }      
    return $img_name;
  }
  
  function import_tier()
  {
    $o = $this->o;
    $pt = Tier::where('ref', $o->ref)->where('type', $o->type)->first();
    if(!$pt)
    {
      $pt = new Tier();
    }
    $pt->ref = $o->ref;
    $pt->type = $o->type;
    $pt->name = $o->name;
    $pt->banner = $this->process_image($o->banner_url, 'tier_banners');
    $pt->se_title = $o->se_title;
    $pt->se_description = $o->se_description;
    $pt->url_slug = $o->url_slug;
    $pt->save();
    
    TierDescription::where('tier_id', $pt->id)->delete();
    for($i=1;$i<=3;$i++)
    {
      $ptd = new TierDescription();
      $title_field = "title_{$i}";
      if(!$o->$title_field) continue;
      $ptd->title = $o->$title_field;
      $content_field = "content_{$i}";
      if(!$o->$content_field)
      {
        $this->errors[] = sprintf("Cannont have empty %s in Product Tier %s %s (row %d). Skipping attribute.", $content_field, $o->ref, $o->type, $this->idx);
        continue;
      }
      $ptd->content = $o->$content_field;
      $ptd->tier_id = $pt->id;
      $ptd->save();
    }
  }

  function parse($string)
  {
    $parts = explode('|', $string);
    $ret=[];
    foreach($parts as $part)
    {
      $part = trim($part);
      if(!$part) continue;
      $ret[] = $part;
    }
    return $ret;
  }
  
  function import_product_tier($product, $refs, $type, $sku)
  {
    $refs = $this->parse($refs);
    foreach($refs as $ref)
    {
      $t = Tier::type($type)->where('ref', $ref)->first();
      if(!$t)
      {
        $this->errors[] = sprintf("%s %s in product %s (row %d) is missing from Product Tiers. Skipping this %s association.", $type, $ref, $sku, $this->idx, $type);
        continue;
      }
      ProductTier::create(['product_id'=>$product->id, 'tier_id'=>$t->id]);
    }          
  }
  
  function import_product_supplier($product, $refs, $sku)
  {
    $refs = $this->parse($refs);
    foreach($refs as $ref)
    {
      $t = Supplier::where('ref', $ref)->first();
      if(!$t)
      {
        $this->errors[] = sprintf("%s in product %s (row %d) is missing from Suppliers. Skipping this Supplier association.", $ref, $sku, $this->idx);
        continue;
      }
      ProductSupplier::create(['product_id'=>$product->id, 'supplier_id'=>$t->id]);
    }          
  }    

  function import_product()
  {
    $o = $this->o;
    $p = Product::where('sku', $o->sku)->first();
    if(!$p)
    {
      $p = new Product();
    }
    
    $p->sku = $o->sku;
    $p->name = $o->name;
    $p->is_featured = (strtoupper($o->is_featured)=='Y'); // Anything except Y means no
    $p->is_active = !(strtoupper($o->is_active)=='N'); // Anything except N means yes
    $b = Tier::brand()->where('ref', $o->brand)->first();
    if($b)
    {
      $p->brand_id = $b->id;
    } else {
      $this->errors[] = sprintf("Brand %s in product %s (row %d) is missing from Product Tiers. Skipping this product.", $o->brand, $o->sku, $this->idx);
      return;
    }
    $p->banner = $this->process_image($o->banner_url, 'product_banners');
    $p->save();
    
    ProductTier::where('product_id', $p->id)->delete();
    $this->import_product_tier($p, $o->categories, 'category', $o->sku);
    $this->import_product_tier($p, $o->ingredients, 'ingredient', $o->sku);
    $this->import_product_tier($p, $o->goals, 'goal', $o->sku);
    
    ProductSupplier::where('product_id', $p->id)->delete();
    $this->import_product_supplier($p, $o->suppliers, $o->sku);
  }   
  
  function import_product_variation()
  {
    $o = $this->o;
    
    $p = Product::where('sku', $o->sku)->first();
    if(!$p)
    {
      $this->errors[] = sprintf("Product %s not found for Product Variation row %s. Skipping this variation.", $o->sku, $this->idx);
      return;
    }
    
    $pv = ProductVariation::where('product_id', $p->id)->where('subsku', $o->subsku)->first();
    if(!$pv)
    {
      $pv = new ProductVariation();
    }
    $pv->product_id = $p->id;
    foreach(['name', 'subsku', 'size', 'rrp', 'price', 'servings', 'stock', 'weight', 'width', 'length', 'depth',] as $field_name)
    {
      $pv->$field_name = $o->$field_name;
    }
    if($o->flavor)
    {
      $f = Flavor::where('name', $o->flavor)->first();
      if(!$f)
      {
        $this->warnings[] = sprintf("Flavor %s for product %s (row %d) was not found in Flavors. Skipping flavor association.", $o->flavor, $o->sku, $this->idx);
      } else {
        $pv->flavor_id = $f->id;
      }
    }
    $pv->cs = (strtoupper($o->cs)=='Y'); // Anything except Y means no
    $pv->is_active = !(strtoupper($o->is_active)=='N'); // Anything except N means yes
    $pv->image = $this->process_image($o->image_url, 'product_variations');
    $pv->save();
  } 
  
  function import_product_content()
  {
    $o = $this->o;
    $p = Product::where('sku', $o->sku)->first();
    if(!$p)
    {
      $this->errors[] = sprintf("Product %s not found for Product Variation row %s. Skipping this variation.", $o->sku, $this->idx);
      return;
    }
    
    $p->mixability = $o->mixability;
    $p->tf_title = $o->tf_title;
    $p->tf_text = $o->tf_text;
    $p->tf_image = $this->process_image($o->tf_image_url, 'tf');
    $p->tf_reviews_enabled = ($o->tf_reviews_enabled=='Y'); // Anything except Y means no
    $p->vfm_score = $o->vfm_score;
    $p->vfm_text = $o->vfm_text;
    $p->hiw_ingredients = $o->hiw_ingredients;
    $p->hiw_active_ingredient_count = $o->hiw_active_ingredient_count;
    $p->hiw_text = $o->hiw_text;
    $p->benefit_1 = $o->benefit_1;
    $p->benefit_2 = $o->benefit_2;
    $p->benefit_3 = $o->benefit_3;
    $p->save();
  }  
  
}