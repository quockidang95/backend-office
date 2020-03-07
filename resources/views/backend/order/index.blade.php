@extends('layouts.admin')
@section('content')
<?php

    $error = Session::get('error');
    if($error){
        echo '<input class="error" type="text" hidden value="'.$error.'"/>';
        Session::put('error', null);
    }

    $success = Session::get('success');
    if($success){
        echo '<input class="success" type="text" hidden value="'.$success.'"/>';
        Session::put('success', null);
    }
?>
<div class="row">
  <div class="col-lg-12">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
                <li class="breadcrumb-item active font-weight-bold" aria-current="page">Danh sách đơn hàng chưa xử lí</li>
          </ol>
      </nav>
  </div>
</div>

<div class="col-lg-3 mb-5">
    <a href="{{  route('order.admin') }}" class="btn btn-primary">Tạo đơn hàng trực tiếp</a>
</div>

<div class="row">
@foreach ($orders as $item)
<?php
    $customer = App\User::find($item->customer_id);
?>
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{'Bàn: ' . $item->table}}</div>
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{'Khách: ' . $customer->name}}</div>
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{'SĐT: ' . $customer->phone}}</div>
          <?php
            if($item->status == 1){
                echo '<span class="badge badge-pill badge-danger">Chưa xử lí</span>';
            }else if($item->status == 2){
                echo '<span class="badge badge-pill badge-warning">Đã tiếp nhận</span>';
            }
          ?>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{number_format($item->total_price) . '  VNĐ'}} </div>

          </div>
          <div class="col-auto">
          <a href="{{route('order.details', ['id' => $item->id])}}"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach
</div>

@endsection()
@section('script')
@endsection()
