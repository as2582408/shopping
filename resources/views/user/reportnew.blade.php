@extends('layouts.master')

@section('content')
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
              <form action="{{ url('reportnew') }}" method="post">
                {!! csrf_field() !!}
                <div class="tital">{{ __('shop.Reply title') }}</div>
                <input id="title" name="title"  class="form-control"  required="">
                <div class="form-group">
                  <label for="exampleFormControlTextarea1">{{ __('shop.Content') }}</label>
                  <textarea id="reply" name="reply" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>
                <div class="clearfix"></div>
                <div class="bot-border"></div>
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
@endsection

