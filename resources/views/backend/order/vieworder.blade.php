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
                <li class="breadcrumb-item active font-weight-bold" aria-current="page"><a
                        href="{{route('order.byday')}}"> Đơn hàng</a></li>
                <li class="breadcrumb-item active font-weight-bold" aria-current="page"><a href="{{route('order.revenue')}}"> Doanh thu theo ngày</a></li>
                <li class="breadcrumb-item active font-weight-bold" aria-current="page">Chi tiết hóa đơn</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <?php
            $customer = App\User::find($order->customer_id);
        ?>
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'Bàn: ' . $order->table}}</div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'Khách: ' . $customer->name}}</div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'SĐT: ' . $customer->phone}}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{number_format($order->total_price) . '  VNĐ'}} </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<table class="table table-striped table-hover table-sm">
    <thead class="thead">
        <tr>
            <th scope="col">STT</th>
            <th scope="col">Sản Phẩm</th>
            <th scope="col">Hình ảnh</th>
            <th scope="col">Số lượng</th>
            <th scope="col">Giá</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orderItems as $key => $item)
        <tr>
            <th class="align-middle" scope="row">{{$key + 1}}</th>
            <td class="align-middle">{{$item->product_id->name}}</th>

            <td class="align-middle">
                <img src="{{asset('source/images/' . $item->product_id->image)}}" width="60" height="60">
            </td>
            <td class="align-middle">{{$item->quantity}}</td>
            <td class="align-middle">{{number_format($item->price) . ' VNĐ'}}</td>
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
