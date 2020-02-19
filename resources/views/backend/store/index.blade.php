@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Cửa hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách cửa hàng</li>
    </ol>
</nav>

<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Tên cửa hàng</th>
            <th scope="col">Mã cửa hàng</th>
            <th scope="col">Địa chỉ</th>
            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stores as $item)
        <tr>
        <th scope="row">{{$item->name}}</th>
        <td scope="row">{{$item->store_code}}</td>
        <td>{{$item->address}}</td>
            <td>...</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
@section('script')

@endsection
