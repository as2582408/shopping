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
                <a class="navbar-brand" href="{{ url('shop') }}">商店</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
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
      @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $errors)
                        <p>{{ $errors }}</p>    
                    @endforeach
                </div>

                @elseif (isset($success))
                <div class="alert alert-success">
                        <p>{{ $success }}</p>    
                </div>
                @endif 
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
        <div class="col-md-6">
          <div class="row">
            <div class="text-center">
              <div class="col-sm-5 col-xs-6 tital ">{{__('shop.name')}}:</div>
              <div class="col-sm-7 col-xs-6 ">{{__($user->name)}}</div>
              <div class="clearfix"></div>
              <div class="bot-border"></div>
              <hr>
              <div class="col-sm-5 col-xs-6 tital ">{{__('shop.email')}}:</div>
              <div class="col-sm-7">{{__($user->email)}}</div>
              <div class="clearfix"></div>
              <div class="bot-border"></div>
              <hr>
              <div class="col-sm-5 col-xs-6 tital ">{{__('shop.level')}}:</div>
              <div class="col-sm-7">{{__($user->level)}}</div>
              <div class="clearfix"></div>
              <div class="bot-border"></div>
              <hr>
              <div class="col-sm-5 col-xs-6 tital ">{{__('shop.phone')}}:</div>
              <div class="col-sm-7">{{__($user->phone)}}</div>
              <hr>
              <div class="clearfix"></div>
              <div class="bot-border"></div>
              <hr>
              <div class="col-sm-5 col-xs-6 tital ">{{__('shop.address')}}:</div>
              <div class="col-sm-7">{{__($user->address)}}</div>
              <hr>
              <div class="clearfix"></div>
              <div class="bot-border"></div>
              <hr>
              <div class="col-sm-5 col-xs-6 tital ">{{__('shop.ShoppingPoint')}}:</div>
              <div class="col-sm-7"><a href='{{ url("/point") }}' class="alert-link">{{__($user->point)}}</a></div>
              <hr>
              <!-- /.box-body -->
          </div>
          </div>
        </div>
      </div>
    </div>

