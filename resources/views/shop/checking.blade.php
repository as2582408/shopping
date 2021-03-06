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
        <hr>
        <div class="col-sm-5 col-xs-6 tital ">{{__('shop.name')}}:</div>
        <div class="col-sm-4 pull-right">
            <input id="anthername" name="anthername" type="text"  class="form-control  " value="{{__($user->name)}}" required="">
            <span id="uidt3" style="margin-left: 100px"></span>    
        </div>
        <div class="clearfix"></div>
        <div class="bot-border"></div>
        <hr>
        <div class="col-sm-5 col-xs-6 tital ">{{__('shop.phone')}}:</div>
        <div class="col-sm-4 pull-right">
            <input id="phone" name="phone" type="text"  class="form-control  " value="{{__($user->phone)}}" required="">
            <span id="uidt1" style="margin-left: 100px"></span>    
        </div>
        <div class="clearfix"></div>
        <div class="bot-border"></div>
        <hr>
        <div class="col-sm-5 col-xs-6 tital ">{{__('shop.address')}}:</div>
        <div class="col-sm-4 pull-right">
            <input id="address" name="address" type="text"  class="form-control  " value="{{__($user->address)}}" required=""> 
            <span id="uidt2" style="margin-left: 100px"></span>    
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
                    <a class="btn btn-sm btn-default" href="{{ url('shop/cart') }}">{{__('shop.Back')}}</a>
                </td>
                <td>
                    <button id="submit" name="submit" class="btn btn-sm btn-default">
                        {{ __('shop.confirm') }}
                    </button>
                </td>
        </tr>
        </table>
        </div>
    </form> 
    </div>
<script type="text/javascript">
phone.onblur = function() {
    if(!(/^09\d{8}$/.test(phone.value))) {
      document.getElementById('uidt1').innerHTML = '請輸入正確的手機號';
      phone.focus();
    } else {
      document.getElementById('uidt1').innerHTML = '';

    }
}
address.onblur = function() {
    if(!(/^[a-zA-Z0-9\u4e00-\u9fa5]+$/.test(address.value))) {
      document.getElementById('uidt2').innerHTML = '請輸入正確地址';
      address.focus();
    } else {
      document.getElementById('uidt2').innerHTML = '';

    }
}
anthername.onblur = function() {
    console.log(anthername.value);
    if(!(/^[a-zA-Z0-9\u4e00-\u9fa5]+$/.test(anthername.value))) {
      document.getElementById('uidt3').innerHTML = '請輸入正確名稱';
      anthername.focus();
    } else {
     document.getElementById('uidt3').innerHTML = '';

    }
}

</script>
@endsection