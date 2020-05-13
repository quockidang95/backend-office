@extends('layouts.customer')
@section('categories')
<div class="nav-scroller bg-white shadow-sm scrollTOP" id="scrollID">
    <div id="myProgress">
        <div id="myBar"></div>
    </div>
    <nav class="nav nav-underline smooth-scroll">
        @foreach ($categories as $item)
        <a class="nav-link " href="{{'#' . $item->id}}">{{$item->name}}</a>
        @endforeach
    </nav>
</div>
@endsection

@section('customer')
<div data-spy="scroll" data-target="#scrollID" data-offset="0">
    @foreach ($data_array as $item)

    <div class="hidden" id="{{$item['category']->id}}">
    </div>
    <label>{{$item['category']->name}}</label>
    @foreach ($item['list_product'] as $product)
<a href="{{route('frontend.product.details', ['id' => $product->id])}}" style="text-decoration: none">
    <div class="my-1 p-2 bg-white">
        <div class="media text-muted pt-3">
            <img class="pr-2 align-self-start" src="{{asset('source/images/' . $product->image)}}" alt="" width="80"
                height="80">

            <div class="media-body pb-3 mb-0 small lh-125">
                <b class="font-weight-bold">{{$product->name}}</b>
                <strong class="d-block text-gray-dark"></strong>
                {{$product->description}}
                <br>
                <b> {{number_format($product->price) . 'VNĐ'}}</b>
                <i class="fas fa-plus-circle fa-2x" style="margin-left: 140px; color: #f6c23e"></i>
            </div>
        </div>
    </div>
</a>
    @endforeach
    @endforeach
@endsection

@section('showcart')
<div class="container-fluid cart">
    <button style="text-decoration: none; width: 100%" id="show_cart" data-toggle="modal" data-target="#exampleModal">
        <div class="row  font-weight-bolder  text-white text-center" style="font-size: 0.875rem; ">
            <div class="col-3">
            {{ Cart::count() . ' MÓN'}}
            </div>
            <div class="col-6">
                Xem Giỏ Hàng
            </div>
            <div class="col-3">

             <?php
                $temp_array = explode(".", Cart::subtotal());
                    echo $temp_array[0] .'₫';
                ?>

            </div>
        </div>
    </button>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Chọn phương thức order.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body row">
        <div class="col-6">
            <a href="{{ route('cart.show')}}" class="btn btn-warning">Order Tại Quán</a>
        </div>
        <div class="col-6">
            <a href="{{ route('cart.show.delivery')}}" class="btn btn-warning">Giao hàng</a>
        </div>
        <div class="col-6"></div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>

</script>
@endsection
