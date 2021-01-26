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
            <a href="{{ url('/admin/return') }}" class="list-group-item list-group-item-action">{{ __('shop.Refund Management') }}</a>
            <a href="{{ url('/admin/products') }}" class="list-group-item list-group-item-action">{{ __('shop.Stock Management') }}</a>
            <a href="{{ url('/admin/category') }}" class="list-group-item list-group-item-action">{{ __('shop.Category Management') }}</a>
            <a href="{{ url('/admin/report') }}" class="list-group-item list-group-item-action">{{ __('shop.Reply Management') }}</a>
            <a href="{{ url('/admin/discount') }}" class="list-group-item list-group-item-action">{{ __('shop.Offer Management') }}</a>
            <a href="{{ url('/admin/level') }}" class="list-group-item list-group-item-action">{{ __('shop.Level Management') }}</a>
          </div>
        </div>
        <div class="col-md-8">
          <div class="row">
          <table class="table table-sm">
				<thead>
				<tr>
					<th scope="col">訂單ID</th>
					<th scope="col">會員ID</th>
					<th scope="col">會員名稱</th>
					<th scope="col">訂單金額</th>
					<th scope="col">訂單狀態</th>
					<th scope="col">出貨狀態</th>
					<th scope="col">訂單成立時間</th>
          <th scope="col">詳細/修改</th>
          <th scope="col"></th>
          <th scope="col">取消訂單</th>
          <th scope="col">出貨</th>
					<th scope="col">完成</th>
				</tr>
				</thead>
				<tbody>
          @foreach ($details as $detail)
          <tr>
            <th scope="row">{{ $detail->detail_id }}</th>
						<td>{{$detail->user_id}}</td>
            <td>{{$detail->name}}</td>
            <td>{{$detail->detail_totail_price}}</td>
						<td>{{$status[ $detail->detail_status ]}}</td>
            <td>{{$shipment[ $detail->detail_shipment ]}}</td>
            <td>{{$detail->detail_create_time}}</td>
            <td><a href='{{ url("/admin/editdetail/{$detail->detail_id}") }}' class="alert-link">修改</a></td>
            <td></td>
            <td>@if($detail->detail_shipment == 1 && $detail->detail_status == 0)<a href='{{ url("/admin/deldetail/{$detail->detail_id}") }}' class="alert-link">刪除</a>@endif</td>
            <td>@if($detail->detail_shipment == 1 && $detail->detail_status == 0)<a href='{{ url("/admin/shipmentdetail/{$detail->detail_id}") }}' class="alert-link">出貨</a>@endif</td>
            <td>@if($detail->detail_status == 0 && $detail->detail_shipment == 2)<a href='{{ url("/admin/enddetail/{$detail->detail_id}") }}' class="alert-link">完成</a>@endif</td>

					</tr>
          @endforeach  
				</tbody>
			  </table>
          </div>
        </div>
      </div>
    </div>

