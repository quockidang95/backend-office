@extends('layouts.admin')

@section('content')
<nav aria-label="breadcrumb" class="col-md-6">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Công thức</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách các công thức</li>
    </ol>
</nav>

<a class="btn btn-success btn-icon-split fa-pull-right mb-2" href="{{  route('recipe.store') }}">
    <span class="icon text-white-50">
        <i class="fas fa-plus"></i>
    </span>
    <span class="text">Thêm CT mới</span>
</a>
<table class="table table-striped table-hover table-sm">
    <thead class="thead">
        <tr>
            <th scope="col">STT</th>
            <th scope="col">Tên Công thức</th>
            <th scope="col">Đơn vị tính</th>

            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($recipes as $key =>$item)
        <tr>
            <th class="align-middle" scope="row">{{$key + 1}}</th>
            <td class="align-middle">{{$item->name}}</th>
            <td class="align-middle">{{$item->unit}}</td>
            <td class="align-middle">Some Action</td>


        </tr>
        @endforeach
    </tbody>
</table>
@endsection
