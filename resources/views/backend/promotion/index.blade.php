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
<nav aria-label="breadcrumb col-8">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Khuyến mãi</li>
    </ol>
</nav>

<a class="btn btn-success btn-icon-split fa-pull-right mb-2" href="{{ route('promotion.viewadd') }}">
    <span class="icon text-white-50">
        <i class="fas fa-plus"></i>
    </span>
    <span class="text">Thêm Coupon</span>
</a>


<table class="table table-striped table-hover table-sm">
    <thead class="thead">
        <tr>
            <th scope="col">STT</th>
            <th scope="col">Title</th>
            <th scope="col">Ngày bắt đầu</th>
            <th scope="col">Mgày hết hạn</th>
            <th scope="col"> Trạng thái </th>
            <th scope="col">Mã giảm giá</th>
            <th scope="col">Phần % giảm</th>
            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($promotions as $key => $item)
        <tr>
            <th class="align-middle" scope="row">{{$key + 1}}</th>
            <td class="align-middle">{{$item->title}}</td>
            <td class="align-middle">{{$item->start_date}}</td>
            <td class="align-middle">{{$item->end_date}}</td>
            <td class="align-middle">
                @if($item->status == 'expired')
                    <span class="badge badge-danger">Không còn áp dụng</span>
                @else
                    <span class="badge badge-success">Trong thời gian áp dụng</span>
                @endif
            </td>
            <td class="align-middle">{{ $item->promotion_code }}</td>
            <td class="align-middle">{{ $item->adjuster }}</td>
            <td class="align-middle">
                <!-- Update category -->
                <a title="update product" href="{{route('promotion.viewupdate', ['id' => $item->id])}}"
                    class="btn btn-warning btn-circle">
                    <i class="fas fa-pen"></i>
                </a>


                <!-- Delete category -->
                <button class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteProduct{{$item->id}}"
                    title="delete category">
                    <i class="fas fa-trash"></i>
                </button>
                <!-- Modal -->
                <div class="modal fade" id="deleteProduct{{$item->id}}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Xoá Coupon</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Bạn có chắn chắn muốn xoá capoun này?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <a href="{{route('promotion.delete', ['id' => $item->id])}}"
                                    class="btn btn-primary">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end delete category -->
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div style="margin-left: 500px">{!! $promotions->links() !!}</div>
@endsection
@section('script')
<link  href="{{ asset('datepicker/dist/datepicker.css') }}" rel="stylesheet">
<script src="{{ asset('datepicker/dist/datepicker.js') }}"></script>
<script>
    $('[data-toggle="datepicker"]').datepicker(
        {format: 'yyyy-mm-dd'}
    );
</script>
    <script>
        $('.input-daterange input').each(function() {
    $(this).datepicker('clearDates');
});
    </script>
@endsection