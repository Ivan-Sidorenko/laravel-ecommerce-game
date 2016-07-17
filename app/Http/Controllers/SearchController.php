<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $search = $request->input('s');
      $products = Product::active()->search($search,0)->groupBy('id')->orderBy('relevance', 'desc')->get();
      return view('search')->with(['products'=>$products]);
    }

}
