@extends('layouts.admin')
@section('content')
<nav aria-label="breadcrumb" class="col-md-6">
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('product.index')}}">Products</a></li>
    <li class="breadcrumb-item active" aria-current="page">List Products by Cateory: {{$category->name}}</li>
    </ol>
</nav>




<table class="table table-striped table-hover table-sm">
    <thead class="thead">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Category</th>
            <th scope="col">Image</th>
            <th scope="col">Price</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $item)
        <tr>
            <th class="align-middle" scope="row">{{$item->id}}</th>
            <td class="align-middle">{{$item->name}}</th>
            <td class="align-middle">{{$item->category->name}}</td>
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
                                <p>Bạn có chắn chắn muốn xóa sản phẩm này?</p>
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
