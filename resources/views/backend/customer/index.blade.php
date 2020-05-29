@extends('layouts.admin')
@section('content')
<?php

$error = Session::get('error');
if ($error) {
    echo '<input class="error" type="text" hidden value="' . $error . '"/>';
    Session::put('error', null);
}

$success = Session::get('success');
if ($success) {
    echo '<input class="success" type="text" hidden value="' . $success . '"/>';
    Session::put('success', null);
}
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Khách hàng </a></li>
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
    <tbody id="list-customer">
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

<div>

    <div class="col-3">
        <input type="text" id="date_selected" name="date_selected" class="form-control bg-lightlight border-0 small"
            data-toggle="datepicker" placeholder="Sinh nhật KH">
    </div>

    <div class="pt-3 pb-5">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Name</th>
                    <th scope="col">Birthday</th>
                    <th scope="col"> Action </th>
                </tr>
            </thead>
            <tbody id="birthday-customer">

            </tbody>
        </table>
    </div>
    <div class="pt-3 pb-5">
        <h5>KH thường xuyên đến quán.</h5>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Name</th>
                    <th scope="col">SDT</th>
                    <th scope="col">Số lần order</th>
                    <th scope="col">Tổng tiền đã tiêu</th>
                    <th scope="col"> Thao tác</th>
                </tr>
            </thead>
            <tbody id="top-customer">

            </tbody>
        </table>
    </div>
    @endsection
    @section('script')
    <link href="{{ asset('datepicker/dist/datepicker.css') }}" rel="stylesheet">
    <script src="{{ asset('datepicker/dist/datepicker.js') }}"></script>
    <script>
    $('[data-toggle="datepicker"]').datepicker({
        format: 'yyyy-mm'
    });
    </script>

    <script>
    $('#searchCustomer').on('keyup', function() {
        $value = $(this).val();

        $.ajax({
            type: 'get',
            url: '{{ URL::to('search-customer') }}',
            data: {
                'search': $value,
            },
            success: function(data) {
                $('#list-customer').html(data);
            }
        });
    })
    $.ajaxSetup({
        headers: {
            'csrftoken': '{{ csrf_token() }}'
        }
    });
    </script>
    <script>
    $('#date_selected').on('change', function() {
        $date_selected = $(this).val();
        $.ajax({
            type: 'get',
            url: '{{ URL::to('get-birthday-for-month') }}',
            data: {
                'dateselected': $date_selected,
            },
            success: function(data) {
                const stringArr = Object.values(data).map((item, index) => {
                    const stt = index + 1;
                    return `
                                    <tr>
                                        <td>` + stt + `</td>
                                        <td>` + item.name + `</td>
                                        <td>` + item.birthday +`</td>
                                        <td> <a class="btn btn-info btn-circle"href="{{env('APP_URL')}}/customer/info/` +
                                                    item.id + `"
                                                title="Xem thông tin khách hàng">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                                `;
                });
                $('#birthday-customer').html(stringArr)
            }
        });
    })
    $.ajaxSetup({
        headers: {
            'csrftoken': '{{ csrf_token() }}'
        }
    });
    </script>

    <script>
    window.onload = (event) => {

        $.ajax({
            type: 'get',
            url: '{{ URL::to('get-customer-dear') }}',
            data: {},
            success: function(data) {
                const stringHTML = data.map((item, index) => {
                    const stt = index + 1;
                        return `
                    <tr>
                        <td>` + stt + `</td>
                        <td>` + item.name + `</td>
                        <td>` + item.phone + `</td>
                        <td>` + item.count + `</td>
                        <td>` + new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(item.total_price) + `</td>
                        <td> <a class="btn btn-info btn-circle"href="{{env('APP_URL')}}/customer/info/` + item.id + `"
                            title="Xem thông tin khách hàng">
                            <i class="fas fa-info-circle"></i>
                        </a>
                                            </td>
                    </tr>
                `
                })
                $('#top-customer').html(stringHTML)
            }
        });
    };
    $.ajaxSetup({
        headers: {
            'csrftoken': '{{ csrf_token() }}'
        }
    });
    </script>
    @endsection