@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb col-8">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Khách hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm tài khoản </li>
    </ol>
</nav>
<div class="col-6 justify-content-center">
    <form class="needs-validation" novalidate action="{{ route('customer.store') }}" method="POST">
        @csrf
        <div class="form-group ">
            <input type="text" name="name" class="form-control  form-control-sm" required placeholder="Tên Khách Hàng">
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                Doesn't look good!
            </div>
        </div>

        <div class="form-group ">
            <input type="text" name="phone" class="form-control  form-control-sm" required placeholder="Số điện thoại">
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                Doesn't look good!
            </div>
        </div>
        <div class="form-group">
            <input type="text" name="point" class="form-control  form-control-sm" required placeholder="Điểm trong hệ thống">
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                Doesn't look good!
            </div>
        </div>
        <div class="form-group ">
            <input type="text" name="address" class="form-control  form-control-sm" required placeholder="Địa chỉ">
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                Doesn't look good!
            </div>
        </div>
        <button class="btn btn-primary fa-pull-right mr-5 w-60" type="submit">Thêm</button>
    </form>

</div>
@endsection
