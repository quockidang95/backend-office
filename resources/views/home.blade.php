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
    <a href="{{route('cart.show')}}" style="text-decoration: none;">
        <div class="row  font-weight-bolder  text-white" style="padding-top: 13px; font-size: 0.875rem; ">
            <div class="col-3">
            {{ Cart::count() . ' MÓN'}}
            </div>
            <div class="col-6" style="text-align: center">
                Xem Giỏ Hàng
            </div>
            <div class="col-3 pl-2">

             <?php
                $temp_array = explode(".", Cart::subtotal());
                    echo $temp_array[0] .  ' ₫';
                ?>

            </div>
        </div>
    </a>
    </div>
@endsection

@section('script')

@endsection
