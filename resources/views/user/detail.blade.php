<html>
<head>
    <title>{{ __('shop.shop') }}</title>
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
                <a class="navbar-brand" href="{{ url('shop') }}">{{ __('shop.shop') }}</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                      @if(App::getLocale() == 'zh') 
                        <li><a href="/lang/en">英文</a></li>
                    @else
                        <li><a href="/lang/zh">chinese</a></li>
                    @endif
                    @if(!Auth::user())
                        <li><a href="{{ url('signin') }}">{{ __('shop.signin') }}</a></li>
                        <li><a href="{{ url('signup') }}">{{ __('shop.signup') }}</a></li>
                    @else
                        <li><a href="{{ url('mycenter') }}">{{ __('shop.mycenter') }} <span class="fa fa-briefcase"></span></a></li>
                        <li><a href="{{ url('shop/cart') }}">{{ __('shop.ShoppingCart') }} <span class="fa fa-shopping-cart"></span></a></li>
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
        <div class="col-md-6 h-100" style="width:200px;">
          <div class="list-group">
            <a href="{{ url('mycenter') }}" class="list-group-item list-group-item-action">{{ __('shop.myprodile') }}</a>
            <a href="{{ url('profile') }}" class="list-group-item list-group-item-action">{{ __('shop.Revise personal info') }}</a>
            <a href="{{ url('password') }}" class="list-group-item list-group-item-action">{{ __('shop.editpassword') }}</a>
            <a href="{{ url('detail') }}" class="list-group-item list-group-item-action">{{ __('shop.myorder') }}</a>
            <a href="{{ url('return') }}" class="list-group-item list-group-item-action">{{ __('shop.myRuturn') }}</a>
            <a href="{{ url('report') }}" class="list-group-item list-group-item-action">{{ __('shop.report') }}</a>
          </div>
        </div>
        <div class="col-md-8">
          <div class="row">
          <table class="table table-sm">
				<thead>
				<tr>
					<th scope="col">{{ __('shop.ordernumber') }}</th>
					<th scope="col">{{ __('shop.orderTotal') }}</th>
					<th scope="col">{{ __('shop.orderstatus') }}</th>
					<th scope="col">{{ __('shop.orderSend') }}</th>
          <th scope="col">{{ __('shop.Order Time') }}</th>
          <th scope="col">{{ __('shop.End Time') }}</th>
          <th scope="col">{{ __('shop.orderDetail') }}</th>
          <th scope="col"></th>
          <th scope="col">{{ __('shop.cancel') }}</th>
          <th scope="col">{{ __('shop.RefundStatus') }}</th>
					<th scope="col">{{ __('shop.End') }}</th>

				</tr>
				</thead>
				<tbody>
          @foreach ($details as $detail)
          <tr>
            <th scope="row">{{ $detail->detail_id }}</th>
            <td>{{$detail->detail_totail_price + $detail->detail_shopping_point}}$</td>
						<td>{{$status[ $detail->detail_status ]}}</td>
            <td>{{$shipment[ $detail->detail_shipment ]}}</td>
            <td>{{$detail->detail_create_time}}</td>
            <td>{{$detail->detail_end_time}}</td>
            <td><a href='{{ url("/editdetail/{$detail->detail_id}") }}' class="alert-link">{{ __('shop.Edit') }}</a></td>
            <td></td>
            <td>@if($detail->detail_shipment == 1 && $detail->detail_status == 0)<a href='{{ url("/deldetail/{$detail->detail_id}") }}' class="alert-link">{{ __('shop.cancel') }}</a>@endif</td>
            <td>@if(($detail->detail_shipment != 1 && $detail->detail_status == 1) && (strtotime(date('Y-m-d H:i:s')) <= strtotime(date('Y-m-d H:i:s', strtotime($detail->detail_end_time."+7 day")))))<a href='{{ url("/returndetail/{$detail->detail_id}") }}' class="alert-link">{{ __('shop.RefundStatus') }}</a>@endif</td>
            <td>@if($detail->detail_status == 0 && $detail->detail_shipment == 2)<a href='{{ url("/enddetail/{$detail->detail_id}") }}' class="alert-link">{{ __('shop.End') }}</a>@endif</td>
					</tr>
          @endforeach  
				</tbody>
			  </table>
          </div>
        </div>
      </div>
    </div>

