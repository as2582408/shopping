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
    <div class="row">
        <div class="col-md-3">
            <form action="{{url("/shop/search")}}" method="GET" class="search-form">
                <input type="text" name="query" id="query" value="" class="search-box"
                    placeholder="{{ __('shop.search') }}">
                <button type="submit" class="fa fa-search search-icon btn btn-primary btn-sm"></button>
            </form>
        </div>

        @if ($categoryId == '' && $search == '')
        <div class="col-md-3 pull-right">
            <strong>Price: </strong>
            <a href="{{url("/shop/orderby/asc/price")}}">{{__('shop.lowtohigh') }}</a>
            <a href="{{url("/shop/orderby/desc/price")}}">{{__('shop.hightolow') }}</a>
        </div>
        @elseif(!empty($categoryId) && $search == '')
        <div class="col-md-3 pull-right">
            <strong>Price: </strong>
            <a href="{{url("/shop/orderByCategory/asc/price/{$categoryId}")}}">{{__('shop.lowtohigh') }}</a>
            <a href="{{url("/shop/orderByCategory/desc/price/{$categoryId}")}}">{{__('shop.hightolow') }}</a>
        </div>
        @elseif(!empty($search) && $categoryId == '')
        <div class="col-md-3 pull-right">
            <strong>Price: </strong>
            <a href="{{url("/shop/orderBySearch/asc/price/{$search}")}}">{{__('shop.lowtohigh') }}</a>
            <a href="{{url("/shop/orderBySearch/desc/price/{$search}")}}">{{__('shop.hightolow') }}</a>
        </div>
        @endif

        @if ($categoryId == '' && $search == '')
        <div class="col-md-3 pull-right">
            <strong>上架時間: </strong>
            <a href="{{url("/shop/orderby/asc/time")}}">時間遠到近</a>
            <a href="{{url("/shop/orderby/desc/time")}}">時間近到遠</a>
        </div>
        @elseif(!empty($categoryId) && $search == '')
        <div class="col-md-3 pull-right">
            <strong>上架時間: </strong>
            <a href="{{url("/shop/orderByCategory/asc/time/{$categoryId}")}}">時間遠到近</a>
            <a href="{{url("/shop/orderByCategory/desc/time/{$categoryId}")}}">時間近到遠</a>
        </div>
        @elseif(!empty($search) && $categoryId == '')
        <div class="col-md-3 pull-right">
            <strong>上架時間: </strong>
            <a href="{{url("/shop/orderBySearch/asc/time/{$search}")}}">時間遠到近</a>
            <a href="{{url("/shop/orderBySearch/desc/time/{$search}")}}">時間近到遠</a>
        </div>
        @endif
    </div>
    <hr>
    <ul class="nav nav-pills">
        <a type="button" class="btn {{($categoryId == '') ? "btn-primary" : "btn-light"}}" href="{{ url('shop') }}">{{__('shop.All Product')}}</a>
        @foreach ($categories as $category)
            <a type="button" class="btn {{($categoryId == $category->id) ? "btn-primary" : "btn-light"}}" href="{{ url("shop/category/{$category->id}") }}">{{ $category->category_name }}</a>
        @endforeach
    </ul>
    <hr>
        <div class="row">
        <table class="table table-sm">
    	</thead>
		<tbody>
        @foreach ($products as $product)
        <tr>
            <td><a href='{{url("/shop/show/{$product->product_id}")}}'><img src="{{asset("storage/$product->product_img")}}" class="img-responsive" width="200" height="100"></a></td>
            <td>{{ $product->product_create_time }}</td>
			<td><a href='{{url("/shop/show/{$product->product_id}")}}'>{{ $product->product_name }}</a></td>
			<td>{{ $product->product_price }}$</td>
            <td><a href='{{url("/shop/show/{$product->product_id}")}}' class="alert-link">詳細</a></td>
            <td></td>
			<td><a href='{{url("/shop/quicklyadd/{$product->product_id}/{$product->product_price}")}}' class="alert-link">快速加入購物車</a></td>
		</tr>
        @endforeach  
		</tbody>
        </table>
         </div>
        </div>
    </div>

@endsection