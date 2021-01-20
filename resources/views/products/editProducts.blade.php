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
            <a href=" " class="list-group-item list-group-item-action">{{ __('shop.Order Management') }}</a>
            <a href=" " class="list-group-item list-group-item-action">{{ __('shop.Refund Management') }}</a>
            <a href="{{ url('/admin/products') }}" class="list-group-item list-group-item-action">{{ __('shop.Stock Management') }}</a>
            <a href="{{ url('/admin/category') }}" class="list-group-item list-group-item-action">{{ __('shop.Category Management') }}</a>
            <a href=" " class="list-group-item list-group-item-action">{{ __('shop.Reply Management') }}</a>
            <a href=" " class="list-group-item list-group-item-action">{{ __('shop.Offer Management') }}</a>
            <a href="{{ url('/admin/level') }}" class="list-group-item list-group-item-action">{{ __('shop.Level Management') }}</a>
          </div>
        </div>
        <div class="col-md-8">
          <div class="row">
            <div class="text-center">
              <form action="{{ url('/admin/editProducts') }}" method="post" class="form-horizontal" enctype="multipart/form-data" role="form">
                {!! csrf_field() !!}
                <input id="id" name="id" type="hidden"  class="form-control  " value=" {{$product->product_id}} " required="">
                <label class="col-sm-5 col-xs-6 tital" for="file">{{__('shop.Upload Image')}}</label>
                      <div class="col-sm-4 pull-right">
                          <input id="img" name="img" class="input-file" type="file">
                      </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">商品名稱:</div>
                <div class="col-sm-4 pull-right"">
                    <input id="name" name="name" type="text"  class="form-control  " value="{{ $product->product_name }}" required="">    
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">商品描述:</div>
                <div class="col-sm-4 pull-right"">
                    <input id="description" name="description" type="text"  class="form-control  " value="{{ $product->product_description }}" required="">    
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">價格:</div>
                <div class="col-sm-4 pull-right"">
                    <input id="price" name="price" type="text"  class="form-control  " value="{{ $product->product_price }}" required=""> 
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">數量:</div>
                <div class="col-sm-4 pull-right"">
                    <input id="amount" name="amount" type="text"  class="form-control  " value="{{ $product->product_amount }}" required=""> 
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">分類:</div>
                @php
                    $category_arr = explode(",", $product->product_category);
                @endphp
                <div class="col-sm-4 pull-right"">
                  @for ($i = 0; $i < 3; $i++)
                  <select id="category{{$i+1}}" name="category{{$i+1}}" class="form-select" aria-label="Default select example">
                    <option value=""></option>
                    @foreach ($category as $categorise)
                    <option value="{{$categorise->id}}" @if($category_arr[$i] == $categorise->id) {{'SELECTED'}} @endif> {{$categorise->category_name}}</option>
                    @endforeach
                  </select>
                  @endfor
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">上架狀態:</div>
                <div class="col-sm-4 pull-right"">
                  <select id="status" name="status" class="form-select" aria-label="Default select example">
                    <option value="Y" @if($product->product_status == 'Y') {{'SELECTED'}} @endif>Y</option>
                    <option value="N" @if($product->product_status == 'N') {{'SELECTED'}} @endif>N</option>
                    <option value="D" @if($product->product_status == 'D') {{'SELECTED'}} @endif>D</option>
                  </select>                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <!-- /.box-body -->
                <hr>
                <div class="btn-group pull-right">
                    <button id="submit" name="submit" class="btn btn-sm btn-default">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>{{__('shop.Edit Product')}}
                    </button>
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

