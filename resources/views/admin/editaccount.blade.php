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
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $errors)
                        <p>{{ $errors }}</p>    
                    @endforeach
                </div>
                @endif
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
            <div class="text-center">
              <form action="{{ url('/admin/accountedit') }}" method="post">
                {!! csrf_field() !!}
                <input id="id" name="id" type="hidden"  class="form-control  " value=" {{$users_data->id}} " required="">
                <div class="col-sm-5 col-xs-6 tital ">{{__('shop.name')}}:</div>
                <div class="col-sm-4 col-xs-3  pull-right" >
                     <input id="name" name="name" type="text"  class="form-control  " value="{{$users_data->name}}" required="" @if($users_data->status == 'D'){{'readonly="readonly"'}}@endif>
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">{{__('shop.email')}}:</div>
                <div class="col-sm-4 pull-right"">
                    <input id="email" name="email" type="text"  class="form-control  " value="{{$users_data->email}}" required="" @if($users_data->status == 'D'){{'readonly="readonly"'}}@endif>    
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">{{__('shop.phone')}}:</div>
                <div class="col-sm-4 pull-right"">
                    <input id="phone" name="phone" type="text"  class="form-control  " value="{{$users_data->phone}}" required="" @if($users_data->status == 'D'){{'readonly="readonly"'}}@endif>    
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">{{__('shop.address')}}:</div>
                <div class="col-sm-4 pull-right"">
                    <input id="address" name="address" type="text"  class="form-control  " value="{{$users_data->address}}" required="" @if($users_data->status == 'D'){{'readonly="readonly"'}}@endif> 
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">{{ __('shop.level') }}:</div>
                <div class="col-sm-4 pull-right"">
                  <select id="level" name="level" class="form-select" aria-label="Default select example">
                    @foreach ($levels as $level)
                      <option value="{{$level->level_rank}}" @if($users_data->level == $level->level_rank) {{'SELECTED'}} @endif>{{$level->level_rank}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">{{ __('shop.ShoppingPoint') }}:</div>
                <div class="col-sm-4 pull-right"">
                    <input id="point" name="point" type="text"  class="form-control  " value="{{$users_data->point}}" required="" @if($users_data->status == 'D'){{'readonly="readonly"'}}@endif> 
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">{{ __('shop.status') }}:</div>
                <div class="col-sm-4 pull-right"">
                  <select id="status" name="status" class="form-select" aria-label="Default select example">
                    <option value="Y" @if($users_data->status == 'Y') {{'SELECTED'}} @endif>{{__('shop.Enable')}}</option>
                    <option value="N" @if($users_data->status == 'N') {{'SELECTED'}} @endif>{{__('shop.Disable')}}</option>
                    <option value="D" @if($users_data->status == 'D') {{'SELECTED'}} @endif>{{__('shop.Delete')}}</option>
                  </select>
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <!-- /.box-body -->
                <hr>
                <div class="btn-group pull-right">
                    <button id="submit" name="submit" class="btn btn-sm btn-default">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>{{__('shop.saveedit')}}
                    </button>
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

