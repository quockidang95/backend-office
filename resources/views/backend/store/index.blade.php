@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Cửa hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách cửa hàng</li>
    </ol>
</nav>
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
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Tên cửa hàng</th>
            <th scope="col">Mã cửa hàng</th>
            <th scope="col">Địa chỉ</th>
            <th scope="col">Pass Wifi</th>
            <th scope="col">Giờ mở cửa</th>
            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stores as $item)
        <tr>
        <th scope="row">{{$item->name}}</th>
        <td scope="row">{{$item->store_code}}</td>
        <td>{{$item->address}}</td>
        <td> {{$item->pass_wifi}}</td>
        <td> {{$item->open_hours}}</td>
            <td>
                 <!--Infor admin -->
                 <button class="btn btn-warning btn-circle" title="Xem thông tin tài khoảng" data-toggle="modal"
                 data-target="#infoadmin{{$item->id}}">
                 <i class="fas fa-pen"></i>
             </button>
             <div class="modal fade" id="infoadmin{{$item->id}}" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                 <div class="modal-dialog" role="document">
                     <div class="modal-content">
                         <form action="{{ route('store.update', [ 'id' => $item->id]) }}" method="POST">
                             @csrf
                         <div class="modal-header">
                             <h5 class="modal-title" id="exampleModalLabel">Cửa Hàng: {{$item->name}}
                             </h5>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                             </button>
                         </div>
                         <div class="modal-body">
                            <div class="form-group ">
                                <label for="">Tên cửa hàng</label>
                                <input type="text" class="form-control  form-control-sm" name="name" value="{{$item->name}}"
                                    >
                            </div>
                             <div class="form-group ">
                                 <label for="">Mã cửa hàng</label>
                                 <input type="text" class="form-control  form-control-sm" value="{{$item->store_code}}"
                                     disabled>
                             </div>

                             <div class="form-group ">
                                 <label for="">Địa chỉ</label>
                                 <input type="text" class="form-control  form-control-sm" name="address" value="{{$item->address}}"
                                     >
                             </div>

                             <div class="form-group ">
                                 <label for="">Passwifi</label>
                                 <input type="text" class="form-control  form-control-sm" name="pass_wifi" value="{{$item->pass_wifi}}"
                                     >
                             </div>
                             <div class="form-group ">
                                <label for="">Giờ mở cửa</label>
                                <input type="time" class="form-control  form-control-sm" name="open_hours" value="{{$item->open_hours}}"
                                    >
                            </div>
                         </div>
                         <div class="modal-footer">
                         <button type="submit" class="btn btn-warning">Cập nhật</button>
                         </div>
                         </form>
                     </div>
                 </div>
             </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
@section('script')

@endsection
