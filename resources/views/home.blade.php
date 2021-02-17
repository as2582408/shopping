@extends('layouts.master')

@section('sidebar')
    @parent
@endsection

@section('content')
    <div class="container">
        <div id="loginbox" style="margin-top:50px;" class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info" >
                <div class="panel-heading">
                    <div class="panel-title" align="center">{{ __('shop.signin') }}</div>
                </div>
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form method="POST" action="{{ url('/signin') }}" class="form-horizontal" role="form">
                        {!! csrf_field() !!}
                        <div style="margin-bottom: 25px" class="input-group">
							<label for="email">{{__('shop.account')}}</label>
                            <input id="email" type="text" class="form-control" name="email" value="" placeholder="{{ __('shop.email') }}">
                        </div>
                        <div style="margin-bottom: 25px" class="input-group">
							<label for="password">{{__('shop.password')}}</label>
                            <input id="password" type="password" class="form-control" name="password" placeholder="{{ __('shop.password') }}">
                        </div>
						@if(isset($error))
							<p>{{__($error)}}<p>
						@endif
                        <div style="margin-top:10px" class="form-group">
                            <div class="col-sm-12 controls">
                                <button type="submit" id="btn-login" href="#" class="btn btn-success">{{ __('shop.signin') }}</button> 
								<a href="{{ url('signup') }}" >&emsp;&emsp;&emsp;{{ __('shop.signup')}}</a>
								<a class="btn btn-link" href="{{ route('password.request') }}">
									{{__('shop.forgot')}}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection