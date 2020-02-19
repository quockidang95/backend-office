@extends('layouts.admin')
@section('content')
<?php

    $error = Session::get('error');
    if($error){
        echo '<input class="error" type="text" hidden value="'.$error.'"/>';
        Session::put('error', null);
    }
  //  $priceBox = Session::get('price_box');

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
                <li class="breadcrumb-item active font-weight-bold" aria-current="page">Doanh thu theo ngày</li>
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
                            {{'Doanh thu trên đơn hàng: ' . number_format($totalPrice) . ' VNĐ'}}</div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'Doanh thu thực tế: ' . number_format($price) . ' VNĐ'}}</div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            {{'Tiền mặt: ' . number_format($tienmat) . ' VNĐ'}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="pt-5">
        <button class="btn btn-primary" data-toggle="modal" data-target="#chotca"> Chốt ca</button>
        <!-- Modal -->
            <div class="modal fade" id="chotca" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Chốt ca</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('shiftwork') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <select class="form-control" name="name_shift">
                                    <option value="ca sáng" selected>Ca 1</option>
                                    <option value="ca chiều">Ca 2</option>
                                    <option value="ca tối"> Ca 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="name_admin" type="text" name="name" placeholder="Tên nhân viên" required>
                            </div>
                            <div class="input-group pt-3">
                            <input type="text" class="form-control" placeholder="Doanh thu của ca" name="price_box" value="{{ session('price_box') }}" aria-label="" aria-describedby="basic-addon3" id="checkNum" readonly required>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon3">VNĐ</span>
                                </div>
                            </div>
                            <div><span>{{ number_format(session('price_box')) . 'VND'}}</span></div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                    </div>
                </form>
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
            <th scope="col">Khách hàng</th>
            <th scope="col">SĐT</th>
            <th scope="col">Tổng giá</th>
            <th scope="col">Trạng thái</th>
            <th scope="col">Thời gian tạo</th>
            <th scope="col">Tạo bởi</th>
            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $key => $item)
        <tr>
            <th class="align-middle" scope="row">{{$key + 1}}</th>
            <td class="align-middle">{{$item->customer->name}}</th>
            <td class="align-middle">{{$item->customer->phone}}</th>
            <td class="align-middle">
                {{number_format($item->total_price) . ' VNĐ'}}
            </td>
            <td class="align-middle">
                <?php
                    if($item->status == 3){
                        echo '<span class="badge badge-success">Đơn hàng đã hoàn tất</span>';
                    }else if($item->status == 4){
                        echo '<span class="badge badge-danger">Đơn hàng đã hủy</span>';
                    }
                    ?>
            </td>
            <td class="align-middle">{{$item->order_date}}</td>
            <td class="align-middle">{{$item->created_by}}</td>
            <td class="align-middle">
                <a title="Xem thông tin đơn hàng" href="{{ route('order.view', ['id' => $item->id])}}"
                    class="btn btn-info btn-circle">
                    <i class="fas fa-info-circle"></i>
                </a>
                <a title="In hóa đơn" class="btn btn-circle btn-outline-warning" target="_blank"
                    href="{{ route('printbill', ['id' => $item->id])}}"> <i class="fas fa-print"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection()
@section('script')
<script>
    $(document).ready(function(){
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
