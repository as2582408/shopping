<html>
<head>
    <title>{{ __('shop.Administrator') }}</title>
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
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $errors)
                        <p>{{ $errors }}</p>    
                    @endforeach
                </div>
                @endif
        <div class="col-md-6 h-100 " style="width:200px;">
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
            <div class="text-center">
              <form action="{{ url('/editdetail') }}" method="post">
                {!! csrf_field() !!}
                <input id="id" name="id" type="hidden"  class="form-control  " value="{{$detail->detail_id}}" required="">
      
                <div class="col-sm-5 col-xs-6 tital ">{{ __('shop.User Name') }}:</div>
                <div class="col-sm-4 pull-right">
                    <input id="name" name="name" type="text"  class="form-control  " value="{{$detail->name}}" required="" readonly="readonly"> 
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
				<hr>
				<div class="col-sm-5 col-xs-6 tital ">{{ __('shop.phone') }}:</div>
                <div class="col-sm-4 pull-right">
                    <input id="phone" name="phone" type="text"  class="form-control  " value="{{$detail->user_phone}}" required="" @if($detail->detail_shipment == '2' || $detail->detail_status != '0'){{'readonly="readonly"'}}@endif> 
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
				<hr>
				<div class="col-sm-5 col-xs-6 tital ">{{ __('shop.address') }}:</div>
                <div class="col-sm-4 pull-right">
                    <input id="address" name="address" type="text"  class="form-control  " value="{{$detail->user_address}}" required="" @if($detail->detail_shipment == '2' || $detail->detail_status != '0'){{'readonly="readonly"'}}@endif> 
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
				<hr>
				<div class="col-sm-5 col-xs-6 tital ">{{ __('shop.orderMoney') }}:</div>
                <div class="col-sm-4 pull-right">
                    <input id="price" name="price" type="text"  class="form-control  " value="{{$detail->detail_totail_price}}" required="" readonly="readonly"> 
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
				<hr>
				<div class="col-sm-5 col-xs-6 tital ">{{ __('shop.orderPoint') }}:</div>
                <div class="col-sm-4 pull-right">
                    <input id="point" name="point" type="text"  class="form-control  " value="{{$detail->detail_shopping_point}}" required="" readonly="readonly"> 
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
				<hr>
				<div class="col-sm-5 col-xs-6 tital ">{{ __('shop.orderGift') }}:</div>
                <div class="col-sm-4 pull-right">
                    <input id="gift" name="gift" type="text"  class="form-control  " value="{{$detail->detail_gift_money}}" required="" readonly="readonly"> 
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
				<hr>
			<label for="exampleFormControlTextarea1">{{ __('shop.orderContent') }}</label>
			  
			<table class="table table-sm">
				<thead>
					<tr>
						<th scope="col">{{ __('shop.Product Name') }}</th>
						<th scope="col">{{ __('shop.unit') }}</th>
						<th scope="col">{{ __('shop.quantity') }}</th>
						<th scope="col">{{ __('shop.subtotal') }}</th>
					</tr>
					<tbody>
						@foreach ($products as $product)
						<tr>
						  <th scope="row">{{$product->product_name}}</th>
						  <td>${{$product->product_price}}</td>
							<td>{{$product->product_amount}}</td>
						 	<td>${{$product->product_amount * $product->product_price}}</td>
						</tr>
						@endforeach
					  </tbody>
				</thead>
			</table>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
              <hr>
              <div class="form-group">
                  <label for="exampleFormControlTextarea1">備註</label><br>
                  <th scope="row">@php echo $detail->detail_remarks; @endphp</th>
              </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="btn-group pull-right">
                  @if($detail->detail_shipment == '1' && $detail->detail_status == '0')
                    <button id="submit" name="submit" class="btn btn-sm btn-default">
                      <i class="fa fa-pencil-square-o" aria-hidden="true"></i>{{__('shop.saveedit')}}
                    </button>
                  @endif
                  </form>
                </div>
                <div>
                  <button class="btn btn-sm btn-default" onclick="history.back()">{{__('shop.Back')}}</button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>

