@extends('layouts.master')

@section('商品列表', 'Page Title')

@section('sidebar')
    @parent
@endsection

@section('content')
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    @if (session()->has('success_message'))
    <div class="alert alert-danger">
            <p>{{ session()->get('success_message') }}</p>    
    </div>
    @endif 
    <p><a href="{{ url('/shop') }}">{{__('shop.home')}}</a> / {{ $product->product_name }}</p>
        <h1>{{ $product->product_name }}</h1>

        <hr>

        <div class="row">
            <div class="col-md-4">
                <img src="{{asset('storage/'.$product->product_img.'')}}" alt="product" class="img-responsive">
            </div>

            <div class="col-md-8">
                <h3>{{__('shop.price') }}：${{ $product->product_price }}</h3> 
                <h4>{{__('shop.stock') }}：{{ $product->product_amount }}</h4>
                @if ($product->product_amount <= 0)
                <h4 class="text-danger">{{__('shop.nostock') }} </h4>   
                @endif
                @if ($product->product_status != 'Y')
                <h4 class="text-danger">{{__('shop.notyet') }} </h4>   
                @endif
                <form action='{{url("/shop/addcart")}}' method="POST" class="side-by-side">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id" value="{{ $product->product_id }}">
                    <input type="hidden" name="name" value="{{ $product->product_name }}">
                    <input type="hidden" name="price" value="{{ $product->product_price }}">
                    <div style="font-size:18px" >{{__('shop.quantity') }}:</div>
                    <div style="position:relative;">
                        <span style="margin-left:100px;width:18px;">
                        <select id='quantity' style="width:100px;margin-left:-100px;height:26px" onfocus="selectFocus(this)"  onchange="this.parentNode.nextSibling.value=this.value">
                            @for ($i = 1; $i <= (($product->product_amount >= 100) ? 100 : $product->product_amount ); $i++)
                            <option onclick="selectClick(this)" value="{{$i}}">{{$i}}</option>    
                            @endfor    
                        </select>
                        </span><input id='quantityinput'name="quantity" value="1" style="z-index:1;width:80px;position:absolute;left:0px;">
                    </div>
                    <br>
                    <br>
                    
                    <input {{($product->product_amount <= 0) ? 'disabled' : ""}} type="submit" class="btn btn-success btn-lg" value="{{__('shop.addcart') }}">
                </form>
                <br><br>

                {{ $product->product_description }}
            </div> <!-- end col-md-8 -->
        </div> <!-- end row -->

@endsection