@extends('layouts.master')
@section('title', 'WishList')
@section('content')

<div class="features_items">
<h2 class="title text-center">Wish List</h2>
@if (count($wishlist) ==0)
    <div class="col-md-12 text-danger">
        Not found products!
    </div>
@else
<div class="table-responsive">
<table class="table box table-bordered">
    <thead>
      <tr  style="background: #eaebec">
        <th style="width: 50px;">No.</th>
        <th style="width: 100px;">Brand</th>
        <th>Name</th>
        <th>Price</th>
        <th>Remove</th>
      </tr>
    </thead>
    <tbody>
    @foreach($wishlist as $love)
    <tr class="row_cart">
        <td >{{ $love->product_id }}</td>
        <td>{{ $love->product->brand->name }}</td>
        <td>
            {{ $love->product->name }}<br>
            <a href="{{route('product.details',$love->product_id)}}"><img width="100" src="/{{$love->product->imageproducts->first()->image_url}}" alt=""></a>
        </td>
        <td>{!! $love->product->price !!}</td>
        <td>
            <a onClick="return confirm('Confirm')" title="Remove Item" alt="Remove Item" class="cart_quantity_delete" href="{{}}"><i class="fa fa-times"></i></a>
        </td>
    </tr>
    @endforeach
    </tbody>
  </table>
  </div>
@endif
</div>

@endsection

