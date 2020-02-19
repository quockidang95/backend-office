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
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Khách hàng </a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách khách hàng</li>
    </ol>
</nav>
<a class="btn btn-success btn-icon-split fa-pull-right mb-2" href="{{ route('customer.view.store') }}">
    <span class="icon text-white-50">
        <i class="fas fa-plus"></i>
    </span>
    <span class="text">Thêm Tài khoản</span>
</a>
<div class="row justify-content-center">
<div class="mb-3 col-6">
    <input class="form-control mr-sm-2" name="search" id="searchCustomer" type="search"
        placeholder="Tìm kiếm khách hàng.....">
</div>
</div>
<table class="table table-sm table-striped table-hover table-bordered">
    <thead class="thead-dark">
        <tr>
            <th scope="col">STT</th>
            <th scope="col">Tên Khách hàng</th>
            <th scope="col">Số điện thoại</th>
            <th scope="col">Điểm</th>
            <th scope="col">Số dư trong ví</th>
            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $key => $item)
        <tr>
            <th scope="row">{{$key + 1}}</th>
            <th>{{$item->name}}</th>
            <td>{{$item->phone}}</td>
            <td>{{$item->point . ' Point'}}</td>
            <td>{{number_format($item->wallet) . ' VNĐ'}}</td>
            <td>
                <!-- Delete category -->
            <a class="btn btn-info btn-circle" href="{{ route('customer.info', ['id' => $item->id]) }}"
                    title="Xem thông tin khách hàng">
                    <i class="fas fa-info-circle"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div style="margin-left: 500px">{!! $users->links() !!}</div>
@endsection
@section('script')
<script>
    $('#searchCustomer').on('keyup',function(){
                $value = $(this).val();

                $.ajax({
                    type: 'get',
                    url: '{{ URL::to('search-customer') }}',
                    data: {
                        'search': $value,
                    },
                    success:function(data){
                        $('tbody').html(data);
                    }
                });
            })
            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>
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
@endsection
