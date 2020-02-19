@extends('layouts.admin')

@section('content')
<nav aria-label="breadcrumb" class="col-md-6">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Công thức</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm Công thức mới</li>
    </ol>
</nav>
<div class="col-6">
<form class="needs-validation" novalidate action="{{ route('recipe.store') }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <div class="form-group ">
        <input type="text" name="name" class="form-control  form-control-sm" required placeholder="Tên công thức">
        <div class="valid-feedback">
            Looks good!
        </div>
        <div class="invalid-feedback">
            Doesn't look good!
        </div>
    </div>

    <div class="form-group ">
        <input type="text" name="unit" required class="form-control form-control-sm" placeholder="Đơn vị tính">
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
