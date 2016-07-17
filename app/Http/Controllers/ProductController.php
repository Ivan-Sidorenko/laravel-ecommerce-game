<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;

class ProductController extends Controller
{
  public function view(Request $request, $slug)
  {
    $p = Product::findBySlug($slug);
    if(!$p)
    {
      session()->flash('warning', "Product not found.");
      return redirect()->route('home');
    }
    if($request->input('vid'))
    {
      $v =  $p->variations()->whereId($request->input('vid'))->first();
    } else {
      $v = $p->lowest_cost_variation();
    }
    if(!$v)
    {
      session()->flash('warning', "That product is not available.");
      return redirect()->route('home');
    }
    return view('products.view')->with(['product'=>$p, 'variation'=>$v]);
  }
}
