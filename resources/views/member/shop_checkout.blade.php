@extends('layouts.master')
@section('title', 'Check out')
@section('content')
<section>
    <div class="container">
      <div class="row">
<h2 class="title text-center">Check out</h2>
    <style>
    .shipping_address td{
        padding: 3px !important;
    }
    .shipping_address textarea,.shipping_address input[type="text"]{
        width: 100%;
        padding: 7px !important;
    }
    .row_cart>td{
        vertical-align: middle !important;
    }
    input[type="number"]{
        text-align: center;
        padding:2px;
    }
</style>
<div class="table-responsive">
<table class="table box table-bordered">
    <thead>
      <tr  style="background: #eaebec">
        <th style="width: 50px;">No.</th>
        <th style="width: 100px;">{{ trans('product.no') }}</th>
        <th>{{ trans('product.name') }}</th>
        <th>{{ trans('product.price') }}</th>
        <th >{{ trans('product.quantity') }}</th>
        <th>{{ trans('product.total_price') }}</th>
      </tr>
    </thead>
    <tbody>
    @foreach(\Session::get('cart') as $product)
        @php
            $n = (isset($n)?$n:0);
            $n++;
        @endphp
    <tr class="row_cart">
        <td >{{ $n }}</td>
        <td>{{ $product['id'] }}</td>
        <td>
            {{ $product['name'] }}<br>
            <a href="{{route('product.details',$product['id']) }}"><img width="100" src="\{{$product['img']}}" alt=""></a>
        </td>
        <td>$ {!! $product['price'] !!}</td>
        <td>{{$product['qty']}}</td>
        <td align="right">$ {{$product['price']*$product['qty']}}</td>
    </tr>
    @endforeach
    </tbody>
  </table>
  </div>
  @php
    $order=\Session::get('order');
  @endphp
    <div class="row">
    <div class="col-md-6">
        <h3 class="control-label"><i class="fa fa-credit-card-alt"></i> {{ trans('cart.shipping_address') }}:<br></h3>
        <table class="table box table-bordered" id="showTotal">
            <tr>
                <th>{{ trans('cart.to_name') }}:</td>
                <td>{{ $order['customer_name'] }}</td>
            </tr>
            <tr>
                <th>{{ trans('cart.phone') }}:</td>
                <td>{{ $order['customer_phone'] }}</td>
            </tr>
             <tr>
                <th>{{ trans('cart.address') }}:</td>
                <td>{{ $order['address'] }}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
{{-- Total --}}
        <div class="row">
            <div class="col-md-12">
                <table class="table box table-bordered" id="showTotal">
                        <tr class="showTotal" >
                            <th>Sub total</th>
                            <td style="text-align: right" id="">$ {{$order['order_total']}}</td>
                        </tr>
                        <tr class="showTotal" >
                            <th>Shipping</th>
                            <td style="text-align: right" id="">$ 0</td>
                        </tr>
                        <tr class="showTotal" style="background:#f5f3f3;font-weight: bold;">
                            <th>Total</th>
                            <td style="text-align: right" id="">$ {{$order['order_total']}}</td>
                        </tr>
                </table>
            </div>
        </div>
{{-- End total --}}

        <div class="row" style="padding-bottom: 20px;">
            <div class="col-md-12 text-center">
             <div class="pull-left">
                <button class="btn btn-default" type="button" style="cursor: pointer;padding:10px 30px" onClick="location.href='{{ route('product.cart') }}'"><i class="fa fa-arrow-left"></i>{{ trans('cart.back_to_cart') }}</button>
                </div>
                    <div class="pull-right">
                        <a href="{{route('member.checkoutconfirm') }}" ><button class="btn btn-success" id="submit-order" onClick="return confirm('Confirm ?')" style="cursor: pointer;padding:10px 30px"><i class="fa fa-check"></i> {{ trans('cart.confirm') }}</button></a>
                    </div>
            </div>
        </div>

    </div>
</div>
        </div>
    </div>
</section>
@endsection

