@extends('layouts.master')

@section('商品列表', 'Page Title')

@section('sidebar')
    @parent
@endsection

@section('content')
    @if (session()->has('success_message'))
        <div class="alert alert-success">
		{{ session()->get('success_message') }}
        </div>
    @endif
    <div class="container">
        <div class="row">
        <table class="table table-sm">
        <thead>
            <tr>
                <th scope="col">{{ __('shop.Product Name') }}</th>
                <th scope="col">{{ __('shop.unit') }}</th>
                <th scope="col">{{ __('shop.quantity') }}</th>
                <th scope="col"></th>
                <th scope="col">{{ __('shop.remove') }}</th>
            </tr>
    	</thead>
		<tbody>
        @foreach ($products as $product)
        <tr>
			<td><a href='{{url("/shop/show/{$product->product_id}")}}' target="_blank">{{$product->product_name}}</a></td>
            <td>{{ $product->product_price }}$</td>
            <td>{{ $product->cart_product_amount }}</td>
            <td></td>
			<td><a href='{{url("/shop/removecart/{$product->product_id}")}}' class="alert-link">{{ __('shop.remove') }}</a></td>
		</tr>
        @endforeach  
        </tbody>
        </table>
        @if($totalPrice > 0)
        <table class="table table-sm">
        <tr>
            <td></td>
            <td class="text-danger">{{ __('shop.orderTotal') }}：{{$totalPrice}}＄</td>
            <form id="checking" action="{{url("/shop/checking")}}" method="POST" >
            <td>{{ __('shop.usevirtual') }}
                <select name="point" id="point">
                    @if($userPoint > 0)
                    <option value="1">{{ __('shop.Yes') }}</option>
                    @endif
                    <option value="2">{{ __('shop.No') }}</option>
                </select> 
            </td>
            <td>{{ __('shop.dicount') }}
                <select name="discount" id="discount">
                    @foreach ($discounts as $discount)
                        <option value="{{$discount->discount_id}}">{{$discount->discount_name}}</option>
                    @endforeach
                </select>
            </td>
            <td>
            {{ csrf_field() }}
            <button id="submit" name="submit" class="btn btn-sm btn-default">
                {{ __('shop.checkout') }}
            </button>
            </td>
            </form> 
        </tr>
         </table>
         @else
         <td>{{ __('shop.signin') }}購物車尚無商品</td>
         @endif
         
         </div>
    </div>

@endsection