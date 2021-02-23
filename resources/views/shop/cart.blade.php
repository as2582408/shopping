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
        <form id="checking" action="{{url("/shop/checking")}}" method="POST" >
        @foreach ($products as $product)
        <tr>
			<td><a href='{{url("/shop/show/{$product->product_id}")}}' target="_blank">{{$product->product_name}}</a></td>
            <td id="{{'product_'.$product->product_id}}">${{ $product->product_price }}</td>
            <td>
                <select name="amount[]" class="amount">
                    @for ($i = 1; $i <= $product->product_amount; $i++ )
                        <option id="{{$product->product_id.'_'.$i}}" value="{{$product->product_id.'_'.$i}}" @if ($product->cart_product_amount == $i) selected
                        @endif>{{$i}}</option>
                    @endfor
                </select>
            </td>
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
            <td class="text-danger" id ='total'>{{ __('shop.orderTotal') }}：${{$totalPrice}}</td>
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
                    <option value="0">不使用</option>
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
         <td>{{ __('shop.cart clear') }}</td>
         @endif
         
         </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            var data = document.getElementsByClassName("amount");
            var NewMoney = 0

            $('.amount').on('change', function() {
                for(var i = 0; i < data.length; i++) {
                    var value = data[i].value;
                    var Money
                    //value[0]為商品ID
                    //value[1]為選擇的數量
                    value = value.split('_');
                    Money = document.getElementById("product_"+value[0]).innerHTML
                    Money = Money.replace('$','')

                    NewMoney =  NewMoney + parseInt(Money * value[1]);
                }
                document.getElementById("total").innerHTML = '{{ __('shop.orderTotal')}}'+'：$' + NewMoney;
                NewMoney = 0;
            });
        });
    </script>
@endsection