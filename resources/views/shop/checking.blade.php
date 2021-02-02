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

        <table class="table table-sm">
        <tr>
            <td>
                <button class="btn btn-sm btn-default" onclick="history.back()">
                {{__('shop.Back')}}
                </button>
            </td>
            <form id="logout-form" action="{{url("/shop/checkout")}}" method="POST" >
                {{ csrf_field() }}
                @foreach ($checkout as $id => $value)
                <td>
                    <input id="{{$id}}" name="{{$id}}" type="hidden"  class="form-control" value="{{$value}}" required="">
                </td>
                @endforeach
                <td>
                    <input id="discountId" name="discountId" type="hidden"  class="form-control" value="{{$discountId}}" required="">
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