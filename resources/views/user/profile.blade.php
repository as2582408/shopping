@extends('layouts.master')

@section('content')
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
        
        <div class="col-md-6">
          <div class="row">
            <div class="box-body">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $errors)
                        <p>{{ $errors }}</p>    
                    @endforeach
                    
                </div>
                @endif
            <form action="{{ url('profile') }}" method="post">
                    {!! csrf_field() !!}
            <div class="text-center">
                
                <div class="col-sm-5 col-xs-6 tital ">{{__('shop.name')}}:</div>
                <div class="col-sm-4 col-xs-3  pull-right" >
                     <input id="name" name="name" type="text"  class="form-control  " value="{{__($user->name)}}" required="">
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">{{__('shop.email')}}:</div>
                <div class="col-sm-4 pull-right">
                    <input id="email" name="email" type="text"  class="form-control  " value="{{__($user->email)}}" required="">    
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">{{__('shop.phone')}}:</div>
                <div class="col-sm-4 pull-right">
                    <input id="phone" name="phone" type="text"  class="form-control  " value="{{__($user->phone)}}" required="">    
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <hr>
                <div class="col-sm-5 col-xs-6 tital ">{{__('shop.address')}}:</div>
                <div class="col-sm-4 pull-right">
                    <input id="address" name="address" type="text"  class="form-control  " value="{{__($user->address)}}" required=""> 
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
                <!-- /.box-body -->
                <hr>
                <div class="btn-group pull-right">
                    <button id="submit" name="submit" class="btn btn-sm btn-default">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>{{__('shop.saveedit')}}
                    </button>
                </div>
            </div>
        </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
