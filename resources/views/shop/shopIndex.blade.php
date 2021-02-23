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
            <p id ='p1'><p>
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

        <div class="col-md-3 pull-right">
            <strong>{{__('shop.price') }}: </strong>
            <a id ='orderbyprice' href="{{url("/shop/orderby/asc/price")}}">{{__('shop.low') }}</a>
            <a id ='orderbyprice2' href="{{url("/shop/orderby/desc/price")}}">{{__('shop.high') }}</a>
        </div>

        <div class="col-md-3 pull-right">
            <strong>{{__('shop.Added time') }}: </strong>
            <a id ='orderbytime' href="{{url("/shop/orderby/asc/time")}}">{{__('shop.Time low') }}</a>
            <a id ='orderbytime2' href="{{url("/shop/orderby/desc/time")}}">{{__('shop.Time high') }}</a>
        </div>
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
			<td>${{ $product->product_price }}</td>
            <td><a href='{{url("/shop/show/{$product->product_id}")}}' class="alert-link">{{__('shop.Content') }}</a></td>
            <td></td>
			<td><a onclick="add({{$product->product_id}},{{$product->product_price}})" class="alert-link">{{__('shop.FastAdd') }}</a></td>
		</tr>
        @endforeach  
		</tbody>
        </table>
         </div>
        </div>
    </div>

<script type="text/javascript">
    $(document).ready(function() {
        var categoryId = '{{$categoryId}}';
        var search = '{{$search}}';
        if (categoryId != '') {
            document.getElementById("orderbyprice").href = "/shop/orderByCategory/asc/price/" + categoryId; 
            document.getElementById("orderbyprice2").href = "/shop/orderByCategory/asc/price/" + categoryId;
            document.getElementById("orderbytime").href = "/shop/orderByCategory/asc/time/" + categoryId; 
            document.getElementById("orderbytime2").href = "/shop/orderByCategory/desc/time/" + categoryId;
        }
        if (search != '') {
            document.getElementById("orderbyprice").href = "/shop/orderBySearch/asc/price/" + search; 
            document.getElementById("orderbyprice2").href = "/shop/orderBySearch/desc/price/" + search;
            document.getElementById("orderbytime").href = "/shop/orderBySearch/asc/time/" + search; 
            document.getElementById("orderbytime2").href = "/shop/orderBySearch/desc/time/" + search;
        }
    });

    function add(id,price){
        $.ajax({
            url: "/shop/quicklyadd/"+id+"/"+price,
            type: "GET",
            dataType: "text",
            cache: false,
            success: function(response) {
                document.getElementById("p1").innerHTML = "<div class='alert alert-success'> Success </div>";
            },
            error: function(){
                console.log('哪裡怪怪的');
        	    } 
            });
    }
</script>
@endsection