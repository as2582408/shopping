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
                <a class="navbar-brand" href="{{ url('/admin/center') }}">{{ __('shop.Administrator') }}</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                      @if(App::getLocale() == 'zh') 
                        <li><a href="/lang/en">英文</a></li>
                    @else
                        <li><a href="/lang/zh">chinese</a></li>
                    @endif
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
    <div class="col-md-6 h-100" style="width:200px;">
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
      <th scope="col">{{ __('shop.Product Name') }}</th>
      <th scope="col">{{ __('shop.unit') }}</th>
      <th scope="col">{{ __('shop.quantity') }}</th>
      <th scope="col">{{ __('shop.subtotal') }}</th>
    </tr>
    </thead>
    <tbody>
      @foreach ($itmes as $itme)
      <tr>
        <th scope="row">{{$itme['name']}}</th>
        <td>${{$itme['price']}}</td>
        <td>{{$itme['amount']}}</td>
        <td>${{$itme['price']*$itme['amount']}}</td>
      </tr>
      @endforeach
    </tbody>
    </table>
    @if (!empty($return->return_reply))
    <table class="table table-sm">
      <thead>
        <tr>
          <th scope="col">{{ __('shop.reasonRejection') }}</th>
        </tr>
      </thead>
        <tbody>
          <tr>
            <th scope="row">@php echo $return->return_reply; @endphp</th>
          </tr>
        </tbody>
      </table>
    @endif
    <a class="btn btn-sm btn-default" href="{{ url('/admin/return') }}">{{__('shop.Back')}}</a>
  </div>
    </div>
  </div>
</div>
