@extends('layouts.master')

@section('商品列表', 'Page Title')

@section('sidebar')
    @parent
@endsection

@section('content')
    @if (session()->has('success_message'))
        <div class="alert alert-success">
		{{ session()->get('success_message') }}
        </div>
    @endif
    <div class="container">
        <div class="row">
        <form id="logout-form" action="{{url("/shop/checkout")}}" method="POST" >
            {{ csrf_field() }}
        <table class="table table-sm">
        <thead>
            <tr>
                <th scope="col">{{ __('shop.checking') }}</th>
            </tr>
    	</thead>
		<tbody>
            @foreach ($checkout as $id => $value)
            <tr>
                <td>{{$name[$id]}}:</td>
                <td>{{$value}}</td>
            </tr>
            @endforeach
        </tbody>
        </table>
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
        <table class="table table-sm">
        <tr>
                @foreach ($checkout as $id => $value)
                <td>
                    <input id="{{$id}}" name="{{$id}}" type="hidden"  class="form-control" value="{{$value}}" required="">
                </td>
                @endforeach
                <td>
                    <input id="discountId" name="discountId" type="hidden"  class="form-control" value="{{$discountId}}" required="">
                </td>
                <td>
                    <input id="amount" name="amount" type="hidden"  class="form-control" value="{{$amount}}" required="">
                </td>
                <td>
                    <button class="btn btn-sm btn-default" onclick="history.back()">
                    {{__('shop.Back')}}
                    </button>
                </td>
                <td>
                    <button id="submit" name="submit" class="btn btn-sm btn-default">
                        {{ __('shop.confirm') }}
                    </button>
                </td>
            </form> 
        </tr>
        </table>
        </div>
    </div>

@endsection