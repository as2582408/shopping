<html>
<head>
    <title>商店</title>
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
                <a class="navbar-brand" href="{{ url('poi') }}">商店</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    @if(!Auth::user())
                        <li><a href="{{ url('signin') }}">{{ __('shop.signin') }}</a></li>
                        <li><a href="{{ url('signup') }}">{{ __('shop.signup') }}</a></li>
                    @else
                        <li><a href="{{ url('mycenter') }}">{{ __('shop.mycenter') }} <span class="fa fa-briefcase"></span></a></li>
                        <li><a href="/cart">{{ __('shop.ShoppingCart') }} <span class="fa fa-shopping-cart"></span></a></li>
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('shop.signout') }}&emsp;{{ __(Auth::user()->name) }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form></li>
                    @endif
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
<hr>

    <div class="container">
      <div class="row">
        <div class="col-md-6 h-100" style="width:300px;">
          <div class="list-group">
            <a href="{{ url('mycenter') }}" class="list-group-item list-group-item-action">{{ __('shop.myprodile') }}</a>
            <a href="{{ url('profile') }}" class="list-group-item list-group-item-action">{{ __('shop.Revise personal info') }}</a>
            <a href="{{ url('password') }}" class="list-group-item list-group-item-action">{{ __('shop.editpassword') }}</a>
            <a href="{{ url('detail') }}" class="list-group-item list-group-item-action">訂單資訊</a>
            <a href="#" class="list-group-item list-group-item-action">我的退貨</a>
            <a href="#" class="list-group-item list-group-item-action">客訴</a>
          </div>
        </div>
        <div class="col-md-8">
          <div class="row">
          <table class="table table-sm">
				<thead>
				<tr>
					<th scope="col">訂單ID</th>
					<th scope="col">訂單金額</th>
					<th scope="col">訂單狀態</th>
					<th scope="col">出貨狀態</th>
					<th scope="col">訂單成立時間</th>
          <th scope="col">詳細/修改</th>
          <th scope="col"></th>
          <th scope="col">取消訂單</th>
          <th scope="col">退貨</th>

				</tr>
				</thead>
				<tbody>
          @foreach ($details as $detail)
          <tr>
            <th scope="row">{{ $detail->detail_id }}</th>
            <td>{{$detail->detail_totail_price}}</td>
						<td>{{$status[ $detail->detail_status ]}}</td>
            <td>{{$shipment[ $detail->detail_shipment ]}}</td>
            <td>{{$detail->detail_create_time}}</td>
            <td><a href='{{ url("/editdetail/{$detail->detail_id}") }}' class="alert-link">修改</a></td>
            <td></td>
            <td>@if($detail->detail_shipment == 1 && $detail->detail_status == 0)<a href='{{ url("/deldetail/{$detail->detail_id}") }}' class="alert-link">取消</a>@endif</td>
            <td>@if($detail->detail_shipment == 2 && $detail->detail_status == 1)<a href='{{ url("/returndetail/{$detail->detail_id}") }}' class="alert-link">退貨</a>@endif</td>
					</tr>
          @endforeach  
				</tbody>
			  </table>
          </div>
        </div>
      </div>
    </div>

