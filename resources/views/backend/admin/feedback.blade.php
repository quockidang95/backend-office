@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Quản trị viên</a></li>
        <li class="breadcrumb-item active" aria-current="page">Phản hồi của khách hàng</li>
    </ol>
</nav>

<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Tên khách hàng</th>
            <th scope="col">Nội dung</th>
            <th scope="col">Ngày tạo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($feedbacks as $item)
        <tr>
            <th scope="row">
                <?php
                    $id = $item->customer_id;
                    $customer = App\User::find($id);
                    if(isset($customer)){
                        echo $customer->name;
                    }
                ?>
            </th>
            <td>
                {{$item->body}}
            </td>
        <td>{{$item->created_at}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection