@extends('app')

@section('content')
.container
  Import a CSV file now. The system will automatically recognize its format.
  %ol
    %li Flavors
    %li Suppliers
    %li Product Tiers
    %li Product Master
    %li Product Content
    %li Product Variations
  {!! form($form) !!}
@stop