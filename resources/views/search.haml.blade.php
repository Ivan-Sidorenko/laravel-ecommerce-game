@extends('app')

@section('content')
.container
  -foreach($products as $product)
    .product
      %a{:href=>route('products.view', [$product->slug])}
        =$product->name
        ="({$product->relevance})"
@stop