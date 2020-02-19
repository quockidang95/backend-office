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
                <li class="breadcrumb-item active font-weight-bold" aria-current="page">Tổng tiền nạp trong ngày</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">


    <div class="col-xl-3 col-md-6 mb-4">
        <?php
          $user = auth()->user()->name;
        ?>
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'Cửa Hàng: ' .  auth()->user()->store_code}}</div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{'Nhân viên: ' . $user}}
                        </div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'Thời gian: ' . Carbon\Carbon::now('Asia/Ho_Chi_Minh')}}</div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'Tổng doanh thu hiện tại: ' . number_format($totalPrice) . ' VNĐ'}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<table class="table table-striped table-hover table-sm">
    <thead class="thead  table-dark">
        <tr>
            <th scope="col">STT</th>
            <th scope="col">Khách hàng</th>
            <th scope="col">Số điện thoại</th>
            <th scope="col">Số tiền</th>
            <th scope="col">Cửa hàng</th>
            <th scope="col">Thời gian</th>
            <th scope="col">Nhân viên</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rechages as $key => $item)
        <tr>
            <th class="align-middle" scope="row">{{$key + 1}}</th>
            <td class="align-middle">{{$item->customer->name}}</th>
            <td class="align-middle">{{$item->customer->phone}}</th>
            <td class="align-middle">
                {{number_format($item->price) . ' VNĐ'}}
            </td>
            <td class="align-middle">
                {{$item->store_code}}
            </td>
            <td class="align-middle">{{$item->created_at}}</td>
            <td class="align-middle">{{$item->created_by}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection()
@section('script')

<script>
    $(function(){
        var success = $('.success').val();
        var error = $('.error').val();
        if(success){
            toastr.success(success, 'Hệ thống thông báo: ', {timeOut: 3000});
        }
        if(error){
            toastr.error(error, 'Hệ thống thông báo: ', {timeOut: 3000});
        }
    });
</script>
@endsection()
