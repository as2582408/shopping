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
                <a class="navbar-brand" href="{{ url('shop') }}">商店</a>
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
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $errors)
                    <p>{{ $errors }}</p>    
                @endforeach
            </div>
            @elseif (session()->has('success_message'))
                <div class="alert alert-danger">
					      {{ session()->get('success_message') }}
                </div>
            @endif 
    <div class="col-md-6 h-100 " style="width:200px;">
      <div class="list-group">
        <a href="{{ url('mycenter') }}" class="list-group-item list-group-item-action">{{ __('shop.myprodile') }}</a>
        <a href="{{ url('profile') }}" class="list-group-item list-group-item-action">{{ __('shop.Revise personal info') }}</a>
        <a href="{{ url('password') }}" class="list-group-item list-group-item-action">{{ __('shop.editpassword') }}</a>
        <a href="{{ url('detail') }}" class="list-group-item list-group-item-action">訂單資訊</a>
        <a href="{{ url('return') }}" class="list-group-item list-group-item-action">我的退貨</a>
        <a href="{{ url('report') }}" class="list-group-item list-group-item-action">客訴</a>
      </div>
    </div>
    <div class="col-md-8">
      <div class="row">
        <div class="text-center">
          <form action="{{ url('/returndetail') }}" method="post">
            {!! csrf_field() !!}
            <table class="table mb-0">
              <thead>
                <tr class="text-center">
                  
                  <th class="text-left">{{__('shop.productcontent')}}</th>
                  <th>{{__('shop.unit')}}</th>
                  <th>{{__('shop.quantity')}}</th>
                  <th class="text-right item-amount">{{__('shop.subtotal')}}</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($products as $product)
              <tr class="border">
                <td class="table-image ">
					@if(($product->product_amount - $product->product_retrun_amount) != 0)
				 	<input type="checkbox" name="product[]" value="{{$product->item_id}}" >
				  	@else
				  	<input type="checkbox" name="product[]" value="{{$product->item_id}}" disabled="disabled" >
					  @endif
                  <a target="_blank" href="{{ url('/shop') }}">{{$product->product_name}}</a>
                </td>
                <td class=" align-middle">{{$product->product_price}}</td>
                <td class=" align-middle">
				  <select name="{{$product->item_id}}" id="refundQuantity">
					          <option>0</option>
                    @for($i = 1;$i <= ($product->product_amount - $product->product_retrun_amount) ;$i++ )
                    <option {{($i == ($product->product_amount - $product->product_retrun_amount))? 'selected' : ''}} value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </td>
                <td class="text-right align-middle">{{($product->product_amount - $product->product_retrun_amount) * $product->product_price}}</td>
              </tr>
              @endforeach
          </tbody>
        </table>
            <div class="clearfix"></div>
            <div class="bot-border"></div>
		  <hr>
            <div class="btn-group pull-right">
				@if ($check != 0)
					<button id="submit" name="submit" class="btn btn-sm btn-default">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>{{__('shop.saveedit')}}
				 	</button>
				@endif
              </form>
            </div>
            <div>
              <button class="btn btn-sm btn-default" onclick="history.back()">返回</button>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>