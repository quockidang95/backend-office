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
                        href="{{route('order.byday')}}">Đơn hàng</a></li>
                <li class="breadcrumb-item active font-weight-bold" aria-current="page">Chi tiết đơn hàng</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">


    <div class="col-xl-3 col-md-6 mb-4">
        <?php
            $customer = App\User::find($order->customer_id);
            $payment_method;
            if($order->payment_method == 1){
                $payment_method = 'Thanh toán qua ví';
            }else if($order->payment_method == 2){
                $payment_method = 'Thanh toán bằng tiền mặt';
            }
        ?>
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        @if(!is_null($order->table))
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'Bàn: ' . $order->table}}</div>
                        @else
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'Delivery: ' . $order->address}}</div>
                        @endif
                        @if($order->order_here == 2)
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'Đơn hàng mang đi'}}</div>
                        @endif
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'Khách: ' . $customer->name}}</div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'SĐT: ' . $customer->phone}}</div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{'Phương thức thanh toán: ' . $payment_method}}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{number_format($order->price) . '  VNĐ'}} </div>
                        <?php
                           if($order->status == 1){
                                echo '<span class="badge badge-pill badge-danger">Chưa xử lí</span>';
                            }else if($order->status == 2){
                                echo '<span class="badge badge-pill badge-warning">Đã tiếp nhận</span>';
                            }
                        ?>
                    </div>
                    <div class="col-auto">
                        <a target="_blank" href="{{ route('printbill', ['id' => $order->id])}}"> <i
                                class="fas fa-print fa-2x .bg-gradient-success"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4 mb-4">
        <div class="card shadow" style="width: 100%; height: 100%">
            <div class="card-body">
                <h5 class="card-title text-bold">Ghi chú:</h5>

                <p class="card-text">{{ $order->note }}.</p>

            </div>
        </div>
    </div>
    <div class="col-2" style="position: relative">
    <a href="{{ route('order.product', ['id' => $order->id]) }}" target="_blank" class="btn btn-warning" style="text-decoration: none; color: white; position: absolute; top: 80px;">In DS Món</a>
    </div>
</div>

<table class="table table-striped table-hover table-sm">
    <thead class="thead table-dark">
        <tr>
            <th scope="col">STT</th>
            <th scope="col">Sản Phẩm</th>
            <th scope="col">Hình ảnh</th>
            <th scope="col">Số lượng</th>
            <th scope="col">Size </th>
            <th  scope="col">Ghi chú</th>
            <th scope="col">Giá</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orderItems as $key => $item)
        <tr>
            <th class="align-middle" scope="row">{{$key + 1}}</th>
            <td class="align-middle">{{$item->product_id->name}}</th>

            <td class="align-middle">
                <img src="{{asset('source/images/' . $item->product_id->image)}}" width="40" height="40">
            </td>
            <td class="align-middle">{{$item->quantity}}</td>
            <td class="align-middle">{{$item->size}}</td>
        <td class="align-middle">{{ $item->recipe}}</td>
            <td class="align-middle">{{number_format($item->price) . ' VNĐ'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="col-xl-10 col-md-12 mb-4">
    <a href="{{ route('order.success', ['id' => $order->id]) }}" class="btn btn-outline-success">Xác nhận đơn hàng</a>
    <a href="{{ route('order.next', ['id' => $order->id]) }}" class="btn btn-outline-warning">Tiếp nhận đơn hàng</a>
    <a href="{{ route('order.error', ['id' => $order->id]) }}" class="btn btn-outline-danger">Hủy đơn hàng</a>

</div>
@endsection()
@section('script')
@endsection()
