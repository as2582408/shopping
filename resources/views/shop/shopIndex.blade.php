@extends('layouts.master')

@section('商品列表', 'Page Title')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="container">
        <div class="row">
    <div class="row">
        <div class="col-md-3">
            <form action="" method="GET" class="search-form">
                <input type="text" name="query" id="query" value="" class="search-box"
                    placeholder="{{ __('shop.search') }}">
                <button type="submit" class="fa fa-search search-icon btn btn-primary btn-sm"></button>
            </form>
        </div>

        <div class="col-md-3 pull-right">
            <strong>Price: </strong>
            <a href="">{{__('shop.lowtohigh') }}</a>
            <a href="">{{__('shop.hightolow') }}</a>
        </div>

        <div class="col-md-3 pull-right">
            <strong>Price: </strong>
            <a href="">{{__('shop.lowtohigh') }}</a>
            <a href="">{{__('shop.hightolow') }}</a>
        </div>

        
    
    </div>
    <hr>
    <ul class="nav nav-pills">
        <li class='active'}}><a href="">{{__('shop.All Product')}}</a></li>
       
        <li class='active'>
            <a href="">123</a>
        </li>
    
    </ul>
    <hr>
            <div class="col-md-12">
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail" >
                            <img src="{{asset('storage/shop.jpg')}}" class="img-responsive">
                            <div class="caption">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <h3>123</h3>
                                    </div>
                                    <div class="col-md-6 col-xs-6 price">
                                        <h3>
                                            <label>￥123</label></h3>
                                    </div>
                                </div>
                                <p>123</p>
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-3">
                                        <a href="/addProduct/" class="btn btn-success btn-product"><span class="fa fa-shopping-cart"></span> 购买</a></div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>

@endsection