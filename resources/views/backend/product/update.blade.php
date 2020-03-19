@extends('layouts.admin')
@section('content')
<?php
    $categories = App\Category::all();
?>
<nav aria-label="breadcrumb col-8">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Products</a></li>
        <li class="breadcrumb-item active" aria-current="page">Update Product</li>
    </ol>
</nav>


<form class="col-8 pb-5" action="{{route('product.update', ['id' => $item->id])}}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <div class="form-group ">
        <input type="text" name="name" class="form-control  form-control-sm" required value="{{$item->name}}"
            placeholder="Tên Sản Phẩm">
        <div class="valid-feedback">
            Looks good!
        </div>
        <div class="invalid-feedback">
            Doesn't look good!
        </div>
    </div>
    <div class="form-group">
        <select name="category_id" class="form-control form-control-sm" required aria-placeholder="Chọn danh mục">
            <option value="{{$item->category->id}}" selected>{{$item->category->name}}
            </option>
            @foreach ($categories as $key => $item1)
            <option value="{{$item1->id}}">{{$item1->name}}</option>
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
        <input type="text" name="description" required value="{{$item->description}}"
            class="form-control form-control-sm" placeholder="Mô tả ngắn">
        <div class="valid-feedback">
            Looks good!
        </div>
        <div class="invalid-feedback">
            Doesn't look good!
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">Hình ảnh</label>
        <div>
            <img class="w-25" src="{{asset('/source/images/'.$item->image)}}" alt="Ảnh sản phẩm" srcset="">
        </div>
        Chọn hình ảnh(nếu muốn thay đổi ảnh sản phẩm):
        <input type="file" class="form-control" name="image" />
    </div>
    <label for="">Price</label>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="inputCity">Size M(default)</label>
            <input type="text" class="form-control" id="inputCity" required name="price" value="{{$item->price}}">
        </div>

        <div class="form-group col-md-4">
            <label for="inputZip">Size L</label>
            <input type="text" class="form-control" id="inputZip" name="price_L" value="{{$item->price_L}}">
        </div>
        <div class="form-group col-md-4">
            <label >Mang đi</label>
        <input type="number" class="form-control" name="price_delivery" value="{{ $item->price_delivery }}">
        </div>
    </div>
    <label for="">Is Report</label>
    @if($item->is_report == 1)
    <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
          <input type="radio" value="1" checked name="is_report">
          </div>
        </div>
        <input type="text" class="form-control" aria-label="Text input with radio button" value="TRUE" readonly>
      </div>

      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
          <input type="radio" value="2" name="is_report">
          </div>
        </div>
        <input type="text" class="form-control" value="FALSE" readonly>
      </div>
    @else
    <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
          <input type="radio" value="1"  name="is_report">
          </div>
        </div>
        <input type="text" class="form-control" aria-label="Text input with radio button" value="TRUE" readonly>
      </div>

      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
          <input type="radio" value="2" checked name="is_report">
          </div>
        </div>
        <input type="text" class="form-control" value="FALSE" readonly>
      </div>
    @endif
    
    <div class="form-group">
        <label for="">Công thức hiện tại</label>
        @foreach($product_recipe as $item)
        <li>{{$item->name}}</li>
        @endforeach
    </div>
    <label for="">Chọn công thức cho món</label>
    @foreach ($recipes as $recipe)
    <div class="form-check">
    <input type="checkbox" name="recipe[]" value="{{$recipe->id}}" class="form-check-input" id="{{$recipe->id . 'check'}}">
    <label class="form-check-label" for="{{$recipe->id . 'check'}}">{{ $recipe->name }}</label>
      </div>
    @endforeach
   
    <button class="btn btn-primary fa-pull-right mr-5 w-60" type="submit">Update Product</button>
</form>
@endsection
@section('script')
<script>
    CKEDITOR.replace('updateProduct1', {

filebrowserBrowseUrl : 'ckfinder/ckfinder.html',
     filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?type=Images',
     filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?type=Flash',
     filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
     filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
     filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
</script>
@endsection()
