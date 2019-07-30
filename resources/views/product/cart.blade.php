@extends('layouts.master')
@section('title','Cart')
@section('content')
	<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="#">Home</a></li>
				  <li class="active">Shopping Cart</li>
				</ol>
			</div>
			@if (!\Session::has('cart'))
			    <div class="col-md-12 text-danger">
			        Not found products!
			    </div>
			@else
			<div class="table-responsive cart_info">
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Item</td>
							<td class="description"></td>
							<td class="price">Price</td>
							<td class="quantity">Quantity</td>
							<td class="total">Total</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						@foreach(\Session::get('cart') as $cart)
							<tr>
								<td class="cart_product">
									<a href="{{route('product.details',$cart['id'])}}" ><img src="/{{$cart['img']}}" alt="" style="height: 100px;weight: 100px;"></a>
								</td>
								<td class="cart_description">
									<h4><a href="{{route('product.details',$cart['id'])}}">{{$cart['name']}}</a></h4>
									<p>Web ID: {{$cart['id']}}</p>
								</td>
								<td class="cart_price">
									<p>${{$cart['price']}}</p>
								</td>
								<td class="cart_quantity">
									<div class="cart_quantity_button">
										<a class="cart_quantity_up" href="{{route('product.updatecart',[$cart['id'],1])}}"> + </a>
										<input class="cart_quantity_input" type="text" name="quantity" value="{{$cart['qty']}}" autocomplete="off" size="2">
										<a class="cart_quantity_down" href="{{route('product.updatecart',[$cart['id'],-1])}}"> - </a>
									</div>
								</td>
								<td class="cart_total">
									<p class="cart_total_price">${{$cart['price']*$cart['qty']}}</p>
								</td>
								<td class="cart_delete">
									<a onClick="return confirm('Confirm ?')" class="cart_quantity_delete" href="{{route('product.updatecart',[$cart['id'],-$cart['qty']])}}"><i class="fa fa-times"></i></a>
								</td>
							</tr>
						@endforeach
					</tbody>
					<tfoot>
				        <tr  style="background: #eaebec">
				            <td colspan="7">
				                 <div class="pull-left">
				                <button class="btn btn-default" type="button" style="cursor: pointer;padding:10px 30px" onClick="location.href='{{ route('home') }}'"><i class="fa fa-arrow-left"></i>{{ trans('cart.back_to_shop') }}</button>
				                </div>
				                 <div class="pull-right">
				                <a onClick="return confirm('Confirm ?')" href="{{route('product.clearcart')}}"><button class="btn" type="button" style="cursor: pointer;padding:10px 30px">{{ trans('cart.remove_all') }}</button></a>
				                </div>
				            </td>
				        </tr>
				    </tfoot>
				</table>
				<form class="shipping_address" id="form-order" role="form" method="POST" action="{{ route('member.checkout') }}">
					<div class="row">
					    <div class="col-md-6">
					            {{ csrf_field() }}
					            <table class="table  table-bordered table-responsive">
					                <tr>
					                    <td class="form-group{{ $errors->has('toname') ? ' has-error' : '' }}">
					                        <label for="phone" class="control-label"><i class="fa fa-user"></i> {{ trans('cart.to_name') }}:</label> <input name="toname" type="text" placeholder="{{ trans('cart.to_name') }}" value="{{(old('toname'))}}">
					                            @if($errors->has('toname'))
					                                <span class="help-block">{{ $errors->first('toname') }}</span>
					                            @endif
					                        </td>
					                    <td class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
					                        <label for="phone" class="control-label"><i class="fa fa-volume-control-phone"></i> {{ trans('cart.phone') }}:</label> <input name="phone" type="text" placeholder="{{ trans('cart.phone') }}" value="{{(old('phone'))}}">
					         
					                        </td>
					                </tr>
					            

					                <tr>
					                    <td class="form-group{{ $errors->has('address1') ? ' has-error' : '' }}"><label for="address1" class="control-label"><i class="fa fa-home"></i> {{ trans('cart.address1') }}:</label> <input name="address1" type="text" placeholder="{{ trans('cart.address1') }}" value="{{ (old('address1'))}}">
					                            @if($errors->has('address1'))
					                                <span class="help-block">{{ $errors->first('address1') }}</span>
					                            @endif</td>
					
					                </tr>
					             
					            </table>

					    </div>
					    <div class="col-md-6">
					{{-- Total --}}
					        <div class="row">
					            <div class="col-md-12">
					                <table class="table box table-bordered" id="showTotal">
					                	@php
					                		$total=0;
					                    @endphp
					                    @foreach (\Session::get('cart') as $cart)
					                    	@php
					                    	$total+=$cart['price']*$cart['qty'];
					                    	@endphp
					                    @endforeach
					                    <tr class="showTotal" style="background:#f5f3f3;font-weight: bold;">
				                    		<th>Total</th>
				                            <td style="text-align: right" id="total">{{$total }}</td>
				                        </tr>
					                </table>
					            </div>
					        </div>
					{{-- End total --}}
					        <div class="row" style="padding-bottom: 20px;">
					            <div class="col-md-12 text-center">
					                    <div class="pull-right">
					                        <button class="btn btn-success" id="submit-order" type="submit" style="cursor: pointer;padding:10px 30px"><i class="fa fa-check"></i> {{ trans('cart.checkout') }}</button>
					                    </div>
					            </div>
					        </div>



					    </div>
					</div>
					</form>
			</div>
			@endif
		</div>
	</section> <!--/#cart_items-->
			
@stop