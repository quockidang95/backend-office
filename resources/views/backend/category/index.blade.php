@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb col-8">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Danh mục sản phẩm</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách danh mục</li>
    </ol>
</nav>
<button class="btn btn-success btn-icon-split fa-pull-right mb-2" data-toggle="modal" data-target="#addCategory">
    <span class="icon text-white-50">
        <i class="fas fa-plus"></i>
    </span>
    <span class="text">Thêm danh mục</span>
</button>
<!-- Modal -->
<div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm mới danh mục</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('category.add') }}" method="POST">
                    @csrf
                    <div class="form-group ">
                        <input type="text" name="name" required class="form-control form-control-user"
                            placeholder="Tên Danh mục">
                        <div class="invalid-feedback">
                            Vui lòng nhập tên danh mục
                        </div>
                    </div>
                    <button class="btn btn-primary fa-pull-right" type="submit">Thêm Danh mục</button>
                </form>
            </div>
        </div>
    </div>
</div>
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Tên danh mục</th>
            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $item)
        <tr>
            <th scope="row">{{$item->id}}</th>
            <td scope="row">{{$item->name}}</th>

            <td>
                <!-- Update category -->
                <button data-toggle="modal" data-target="#updateCategory{{$item->id}}" title="update category"
                    class="btn btn-warning btn-circle">
                    <i class="fas fa-pen"></i>
                </button>
                <!-- Modal -->
                <div class="modal fade" id="updateCategory{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Hiệu chỉnh danh mục</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ URL::to('category/update/' . $item->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group ">
                                    <input type="text" name="name" required class="form-control form-control-user" value="{{$item->name}}"
                                            placeholder="Tên Danh mục">
                                        <div class="invalid-feedback">
                                            Vui lòng nhập tên danh mục
                                        </div>
                                    </div>
                                    <button class="btn btn-primary fa-pull-right" type="submit">Hiệu chỉnh</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end update category -->

                 <!-- Delete category -->
            <button class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteCategory{{$item->id}}" title="delete category">
                        <i class="fas fa-trash"></i>
                 </button>
                  <!-- Modal -->
                <div class="modal fade" id="deleteCategory{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Xóa danh mục</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                <p>Bạn có chắn chắn muốn xóa danh mục này?</p>
                        </div>
                        <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a href="{{route('category.delete', ['id' => $item->id])}}" class="btn btn-primary">Delete</a>
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
