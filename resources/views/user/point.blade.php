@extends('layouts.master')

@section('content')
    <div class="container">
      <div class="row">
        <div class="col-md-6 h-100 " style="width:200px;">
			<div class="list-group">
				<a href="{{ url('mycenter') }}" class="list-group-item list-group-item-action">{{ __('shop.myprodile') }}</a>
				<a href="{{ url('profile') }}" class="list-group-item list-group-item-action">{{ __('shop.Revise personal info') }}</a>
				<a href="{{ url('password') }}" class="list-group-item list-group-item-action">{{ __('shop.editpassword') }}</a>
				<a href="{{ url('detail') }}" class="list-group-item list-group-item-action">訂單資訊</a>
				<a href="{{ url('return') }}" class="list-group-item list-group-item-action">我的退貨</a>
				<a href="{{ url('report') }}" class="list-group-item list-group-item-action">客訴</a>
			</div>
        </div>
        <div class="col-md-8">
          <div class="row">
          <table class="table table-sm">
			<thead>
			<tr>
				<th scope="col">訂單ID</th>
				<th scope="col">時間</th>
				<th scope="col">類型</th>
				<th scope="col">變動購物金</th>
				<th scope="col">變動後購物金</th>
			</tr>
			</thead>
			<tbody>
			@foreach ($pointLog as $log)
          	<tr>
            	<th scope="row">{{$log->log_detail}}</th>
				<td>{{$log->log_time}}</td>
            	<td>{{$type[$log->log_type]}}</td>
            	<td>{{$log->log_change_gold}}</td>
				<td>{{$log->log_new_gold}}</td>
        	</tr>
			@endforeach
			</tbody>
			</table>
          </div>
        </div>
      </div>
    </div>
@endsection
