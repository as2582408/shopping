<html>
<head>
    <title>後台</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
    <nav class="navbar navbar-default  navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/admin/center') }}">後台</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                      @if(!Auth::user())
                          <li><a href="{{ url('/admin/login') }}">{{ __('shop.signin') }}</a></li>
                      @else
                          <li><a href="{{ url('/admin/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('shop.signout') }}&emsp;{{ __(Auth::user()->name) }}</a>
                          <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form></li>
                      @endif
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
<hr>
    <div class="container">
      <div class="row">
        <div class="col-md-6 h-100 " style="width:200px;">
          <div class="list-group">
            <a href="{{ url('/admin/account') }}" class="list-group-item list-group-item-action">{{ __('shop.Account Management') }}</a>
            <a href="{{ url('/admin/detail') }}" class="list-group-item list-group-item-action">{{ __('shop.Order Management') }}</a>
            <a href=" " class="list-group-item list-group-item-action">{{ __('shop.Refund Management') }}</a>
            <a href="{{ url('/admin/products') }}" class="list-group-item list-group-item-action">{{ __('shop.Stock Management') }}</a>
            <a href="{{ url('/admin/category') }}" class="list-group-item list-group-item-action">{{ __('shop.Category Management') }}</a>
            <a href=" " class="list-group-item list-group-item-action">{{ __('shop.Reply Management') }}</a>
            <a href="{{ url('/admin/discount') }}" class="list-group-item list-group-item-action">{{ __('shop.Offer Management') }}</a>
            <a href="{{ url('/admin/level') }}" class="list-group-item list-group-item-action">{{ __('shop.Level Management') }}</a>
          </div>
        </div>
        <div class="col-md-8">
          <div class="row justify-content-end offset-2">
            <form action="{{ url('/admin/productsSearch') }}" method="GET" class="search-form">
                <input type="text" name="query" id="query" value="{{ request()->input('query') }}" class="search-box"
                    placeholder="{{__('shop.Product Name')}}">
                <button type="submit" class="fa fa-search btn btn-info btn-sm"></button>
            </form>
          </div>
          <div class="row">
            <a class="btn btn-primary" href="{{ url('/admin/addProducts') }}" role="button">新增商品</a>
          </div>
          <div class="row">
          <table class="table table-sm">
				<thead>
				<tr>
					<th scope="col">id</th>
					<th scope="col">圖片</th>
					<th scope="col">商品名</th>
					<th scope="col">價格</th>
					<th scope="col">數量</th>
					<th scope="col">上架</th>
					<th scope="col">上架時間</th>
					<th scope="col">商品分類</th>
          <th scope="col">修改</th>
          <th scope="col"></th>
					<th scope="col">快速刪除</th>
				</tr>
				</thead>
				<tbody>
          @foreach ($products as $product)
          <tr>
            <th scope="row">{{ $product->product_id }}</th>
            <td><img src="{{asset("storage/$product->product_img")}}" class="img-responsive" width="200" height="100"></td>
						<td>{{ $product->product_name }}</td>
						<td>{{ $product->product_price }}</td>
						<td>{{ $product->product_amount }}</td>
            <td>{{ $product->product_status }}</td>
            <td>{{ $product->product_create_time }}</td>
            <td>{{ $product->category_name }}</td>
            <td><a href='{{ url("/admin/editProducts/{$product->product_id}") }}' class="alert-link">修改</a></td>
            <td></td>
						<td><a href='{{ url("/admin/delProducts/{$product->product_id}") }}' class="alert-link">刪除</a></td>
					</tr>
          @endforeach  
				</tbody>
			  </table>
          </div>
        </div>
      </div>
    </div>

