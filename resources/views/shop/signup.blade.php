@extends('layouts.master')
@section('title')
註冊    
@endsection
@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
    <h1>{{__('shop.registered')}}</h1>
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        @foreach ($errors->all() as $errors)
            <p>{{ $errors }}</p>    
        @endforeach
        
    </div>
    @endif 
    <form action="{{ url('signup')}}" method="post">
        <div class="form-group">
            <label for="name"">{{__('shop.name')}}</label>
            <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label for="email">{{__('shop.account')}}</label>
            <input type="text" id="email" name="email" class="form-control" placeholder="XXX@gmail.com" required value="{{ old('email') }}">
        </div>
        <div class="form-group">
            <label for="email">{{__('shop.phone')}}</label>
            <input type="text" id="phone"" name="phone" class="form-control" placeholder="0912345678" required oninput = "value=value.replace(/[^\d]/g,'')" value="{{ old('phone') }}">
        </div>
        <div class="form-group">
            <label for="email">{{__('shop.address')}}</label>
            <input type="text" id="address" name="address" class="form-control" placeholder="台中市...." required value="{{ old('address') }}">
        </div>
        <div class="form-group">
            <label for="password">{{__('shop.password')}}</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <span class="">{{__('shop.passwordrule')}}</span>
        </div>
        <div class="form-group">
            <label for="password">{{__('shop.confirmpassword')}}</label>
            <input type="password" id="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">{{__('shop.registered')}}</button>
        {{csrf_field()}}
    </form>
    </div>
</div>
@endsection

