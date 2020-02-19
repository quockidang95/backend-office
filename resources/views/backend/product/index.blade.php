@extends('layouts.admin')
@section('content')
<?php
    $categories = App\Category::all();
?>
<nav aria-label="breadcrumb" class="col-md-6">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Sản Phẩm</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách sản phẩm</li>
    </ol>
</nav>


<button class="btn btn-success btn-icon-split fa-pull-right mb-2" data-toggle="modal" data-target="#addProduct">
    <span class="icon text-white-50">
        <i class="fas fa-plus"></i>
    </span>
    <span class="text">Thêm Sản phẩm</span>
</button>

<input style="width: 20%" class="form-control mr-sm-2" name="search" id="searchProduct" type="search"
    placeholder="Tìm kiếm....">

<!-- Modal -->
<div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm mới sản phẩm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate action="{{ route('product.add') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group ">
                        <input type="text" name="name" class="form-control  form-control-sm" required
                            placeholder="Tên Sản Phẩm">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Doesn't look good!
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="category_id" class="form-control form-control-sm" required
                            aria-placeholder="Chọn danh mục">
                            <option value="" disabled>Chọn danh mục</option>
                            @foreach ($categories as $key => $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Doesn't look good!
                        </div>
                    </div>
                    <div class="form-group ">
                        <input type="text" name="description" required class="form-control form-control-sm"
                            placeholder="Mô tả ngắn">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Doesn't look good!
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Hình ảnh</label>
                        <input type="file" class="form-control" name="image" required />
                    </div>
                    <label for="">Price</label>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputCity">Size M(default)</label>
                            <input type="text" class="form-control" id="inputCity" required name="price">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="inputZip">Size L</label>
                            <input type="text" class="form-control" id="inputZip" name="price_L">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputCity1">Khuyến mãi (%)</label>
                            <input type="text" class="form-control" id="inputCity1" name="promotion_price">
                        </div>
                    </div>
                    <div class="form-group ">
                        <textarea rows="10" cols="80" type="text" id="addProduct1" name="content" placeholder="Content"
                            required></textarea>
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
<div class="dropdown pt-2 pb-2">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Danh mục sản phẩm
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        @foreach ($categories as $item)
    <a class="dropdown-item" href="{{ route('product.bycateogry', ['id' => $item->id])}}">{{$item->name}}</a>
        @endforeach
    </div>
  </div>
<table class="table table-striped table-hover table-sm">
    <thead class="thead">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Tên sản phẩm</th>
            <th scope="col">Danh mục</th>
            <th scope="col">Trạng thái</th>
            <th scope="col">Hình ảnh</th>
            <th scope="col">Giá</th>
            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $item)
        <tr>
            <th class="align-middle" scope="row">{{$item->id}}</th>
            <td class="align-middle">{{$item->name}}</th>
            <td class="align-middle">{{$item->category->name}}</td>
             <td class="align-middle">
                <?php
                    if($item->status == 1){
                        echo '<span class="badge badge-success">Kích hoạt</span>';
                    }else{
                        echo '<span class="badge badge-danger">Khóa</span>';
                    }
                    ?>
            </td>
            <td class="align-middle">
                <img src="{{asset('source/images/' . $item->image)}}" width="60" height="60">
            </td>
            <td class="align-middle">{{number_format($item->price) . ' VNĐ'}}</td>

            <td class="align-middle">
                <!-- Update category -->
                <a title="update product" href="{{route('product.viewupdate', ['id' => $item->id])}}"
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
                                <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Bạn có chắn chắn muốn khóa sản phẩm này?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <a href="{{route('product.delete', ['id' => $item->id])}}"
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
<div style="margin-left: 500px">{!! $products->links() !!}</div>
@endsection
@section('script')
<script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        }
        form.classList.add('was-validated');
        }, false);
        });
        }, false);
        })();
</script>

<script>
    CKEDITOR.replace('addProduct1', {

       filebrowserBrowseUrl : 'ckfinder/ckfinder.html',
			filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?type=Images',
			filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?type=Flash',
			filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
			filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
     });





jQuery.fn.modal.Constructor.prototype.enforceFocus = function () {
    modal_this = this
    jQuery(document).on('focusin.modal', function (e) {
        if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
                && !jQuery(e.target.parentNode).hasClass('cke_dialog_ui_input_select')
                && !jQuery(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
            modal_this.$element.focus()
        }
    })
};
</script>

<script>
        $('#searchProduct').on('keyup',function(){
                    $value = $(this).val();

                    $.ajax({
                        type: 'get',
                        url: '{{ URL::to('search-product') }}',
                        data: {
                            'search': $value,
                        },
                        success:function(data){
                            $('tbody').html(data);
                        }
                    });
                })
                $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>

@endsection
