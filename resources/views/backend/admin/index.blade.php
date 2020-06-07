@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Quản lí tài khoảng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách tài khoản</li>
    </ol>
</nav>
<?php
    $stores = App\Store::all();
?>

<button class="btn btn-success btn-icon-split fa-pull-right mb-2" data-toggle="modal" data-target="#addAdmin">
    <span class="icon text-white-50">
        <i class="fas fa-plus"></i>
    </span>
    <span class="text">Thêm Tài khoản</span>
</button>
<input style="width: 20%" class="form-control mr-sm-2" name="search" id="searchProduct" type="search"
    placeholder="Tìm kiếm....">

<!-- Modal -->
<div class="modal fade" id="addAdmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm mới tài khoản</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate action="{{ route('admin.add') }}" method="POST">
                    @csrf
                    <div class="form-group ">
                        <input type="text" name="name" class="form-control  form-control-sm" required
                            placeholder="Tên người dùng">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Doesn't look good!
                        </div>
                    </div>
                    <div class="form-group ">
                        <input type="email" name="email" class="form-control  form-control-sm" required
                            placeholder="Email người dùng">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Doesn't look good!
                        </div>
                    </div>
                    <div class="form-group ">
                        <input type="password" name="password" class="form-control  form-control-sm" required
                            placeholder="Password">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Doesn't look good!
                        </div>
                    </div>
                    <div class="form-group ">
                        <input type="password" name="c_password" class="form-control  form-control-sm" required
                            placeholder="Confirm password">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Doesn't look good!
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="store_code" class="form-control form-control-sm" required
                            aria-placeholder="Chọn danh mục">
                            <option value="" disabled selected>Chọn Cửa hàng</option>
                            @foreach ($stores as $item)
                            <option value="{{$item->store_code}}">{{$item->name}}</option>
                            @endforeach
                        </select>
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
        </div>
    </div>
</div>

<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Tên tài khoản</th>
            <th scope="col">Trạng thái</th>
            <th scope="col">Mã cửa hàng</th>
            <th scope="col"> Câp bậc </th>
            <th scope="col">Thao tác</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($admins as $item)
        <tr>
            <th scope="row">{{$item->name}}</th>
            <td>
                <?php
                    if($item->status == 1){
                        echo '<span class="badge badge-success">Kích hoạt</span>';
                    }else {
                        echo '<span class="badge badge-danger">Khóa</span>';
                    }
                ?>
            </td>
            <td>
                <?php
                    if($item->store_code != null){
                        echo $item->store_code;
                    }
                ?>

            </td>
            <td>
                @if($item->role_id  == 1)
                    Quản trị viên
                @elseif($item->role_id == 2)
                Nhân viên
                @endif
            </td>
            <td>
                <!--Infor admin -->
                <button class="btn btn-info btn-circle" title="Xem thông tin tài khoảng" data-toggle="modal"
                    data-target="#infoadmin{{$item->id}}">
                    <i class="fas fa-info-circle"></i>
                </button>
                <div class="modal fade" id="infoadmin{{$item->id}}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tài khoảng {{$item->name}}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group ">
                                    <label for="">Tên tài khoảng</label>
                                    <input type="text" class="form-control  form-control-sm" value="{{$item->name}}"
                                        disabled>
                                </div>

                                <div class="form-group ">
                                    <label for="">Email</label>
                                    <input type="text" class="form-control  form-control-sm" value="{{$item->email}}"
                                        disabled>
                                </div>

                                <div class="form-group ">
                                    <label for="">Số điện thoại</label>
                                    <input type="text" class="form-control  form-control-sm" value="{{$item->phone}}"
                                        disabled>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>



                <button title="đổi mật khẩu" data-toggle="modal" data-target="#changepassword{{$item->id}}"
                    class="btn btn-warning btn-circle">
                    <i class="fas fa-pen"></i>
                </button>
                <!-- Modal -->
                <div class="modal fade" id="changepassword{{$item->id}}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Đổi mật khẩu cho user {{$item->name}}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('admin.update', ['id' => $item->id])}}" method="POST">
                                    @csrf
                                    <div class="form-group ">
                                        <input type="password" name="password" class="form-control  form-control-sm"
                                            required placeholder="Password">
                                    </div>
                                    <div class="form-group ">
                                        <input type="password" name="c_password" class="form-control  form-control-sm"
                                            required placeholder="Confirm Password">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                                </form>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- end delete category -->

                <!-- Delete category -->
                <button class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteAdmin{{$item->id}}"
                    title="Xóa tài khoảng">
                    <i class="fas fa-trash"></i>
                </button>
                <!-- Modal -->
                <div class="modal fade" id="deleteAdmin{{$item->id}}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Khóa tài khoảng</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Bạn có chắn chắn muốn khóa tài khoản này?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <a href="{{route('admin.delete', ['id' => $item->id])}}"
                                    class="btn btn-primary">KHóa tài khoản</a>
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
@endsection
@section('script')

@endsection
