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
                <th scope="col">商品名</th>
                <th scope="col">單價</th>
                <th scope="col">數量</th>
                <th scope="col"></th>
                <th scope="col">移出購物車</th>
            </tr>
    	</thead>
		<tbody>
        @foreach ($products as $product)
        <tr>
			<td><a href='{{url("/shop/show/{$product->product_id}")}}' target="_blank">{{$product->product_name}}</a></td>
            <td>{{ $product->product_price }}$</td>
            <td>{{ $product->cart_product_amount }}</td>
            <td></td>
			<td><a href='{{url("/shop/removecart/{$product->product_id}")}}' class="alert-link">移出購物車</a></td>
		</tr>
        @endforeach  
        </tbody>
        </table>
        @if($totalPrice > 0)
        <table class="table table-sm">
        <tr>
            <td></td>
            <td class="text-danger">總價：{{$totalPrice}}＄</td>
            <form id="checking" action="{{url("/shop/checking")}}" method="POST" >
            <td>使用禮金結帳
                <select name="point" id="point">
                    @if($userPoint > 0)
                    <option value="1">是</option>
                    @endif
                    <option value="2">否</option>
                </select> 
            </td>
            <td>使用優惠
                <select name="discount" id="discount">
                    @foreach ($discounts as $discount)
                        <option value="{{$discount->discount_id}}">{{$discount->discount_name}}</option>
                    @endforeach
                </select>
            </td>
            <td>
            {{ csrf_field() }}
            <button id="submit" name="submit" class="btn btn-sm btn-default">
                前往結帳
            </button>
            </td>
            </form> 
        </tr>
         </table>
         @else
         <td>購物車尚無商品</td>
         @endif
         
         </div>
    </div>

@endsection