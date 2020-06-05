@extends('layouts.admin')

@section('css')
    <style>
        .card_product {
            max-width: max-content;
            width: 50%;
            margin: 0;
            padding-right: 8px;
            float: left;
        }

        .both{
            clear: both;
        }
    </style>
@endsection
@section('content')
<?php

$error = Session::get('error');
if ($error) {
    echo '<input class="error" type="text" hidden value="' . $error . '"/>';
    Session::put('error', null);
}

$success = Session::get('success');
if ($success) {
    echo '<input class="success" type="text" hidden value="' . $success . '"/>';
    Session::put('success', null);
}
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href=" {{ route('order.byday') }} ">Order </a></li>
        <li class="breadcrumb-item active" aria-current="page">Thêm món</li>
    </ol>
</nav>
<div class="row">
    <div class="col-8">
        <!--
    <div class="form-group">
        <label for="exampleFormControlSelect1">Chọn danh mục</label>
        <select class="form-control" id="category_selected">

        @foreach($categories as $category)
            <option value="{{ $category->id }}" selected>  {{ $category->name }}</option>
        @endforeach
        </select>
    </div> -->
        <div class="nav-scroller bg-white shadow-sm scrollTOP" id="scrollID">
            <div id="myProgress">
                <div id="myBar"></div>
            </div>
            <nav class="nav nav-underline smooth-scroll">
                @foreach ($categories as $item)
                <a class="nav-link " href="{{'order/view/add/' . $order->id . '/#abc' . $item->id}}">{{$item->name}}</a>
                @endforeach
            </nav>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-8 pt-3">
        <div class="row" id="product">
            <div data-spy="scroll" data-target="#scrollID" data-offset="0">
                @foreach ($data_array as $item)
                <div style="display: inline-block">
                <div class="hidden" id="{{'abc' . $item['category']->id}}"></div>
                <p style="display: block">{{$item['category']->name}}</p>
                @foreach ($item['list_product'] as $product)
                @if ($product->price_L == null)
                <div class="col-10 mb-4 card_product ">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body" style="height:200px;">
                            <form action="{{ route('admin.cart.update.add', ['id' => $product->id]) }}" method="get">
                                @csrf
                                <div class="row no-gutters align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-2">
                                            {{ $product->name}}
                                        </div>
                                        <div>
                                            <img src="{{asset('source/images/' . $product->image)}}" with="50"
                                                height="50" />
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" checked
                                                    id="{{$product->price . $product->id . ''}}"
                                                    name="{{ 'price_' . $product->id }}" value="{{ $product->price }}">
                                                <label class="custom-control-label"
                                                    for="{{ $product->price . $product->id . '' }}"><span
                                                        class="badge badge-warning">{{$product->price}}</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display: flex; margin-top: 10px;">
                                        <div class="quantity buttons_added">
                                            <input type="button" value="-" class="minus style rounded"><input
                                                type="number"
                                                style="width: 25%; border: 0px solid #f6c23e; text-align: center"
                                                step="1" min="1" max="" name="{{ 'quantity_' .  $product->id }}"
                                                id="{{'quantity_' . $product->id}}" value="1" title="Qty"
                                                class="input-text qty text rounded" size="4" pattern=""
                                                inputmode=""><input type="button" value="+" class="plus style rounded">
                                        </div>
                                    
                                        <button class="btn btn-warning" type="submit">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-10 mb-4 card_product">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body" style="height:200px;">
                            <form action="{{ route('admin.cart.update.add', ['id' => $product->id]) }}" method="get">
                                @csrf
                                <div class="row no-gutters align-items-center">
                                    <div class="col">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            {{ $product->name}}</div>
                                        <div>
                                            <img src="{{asset('source/images/' . $product->image)}}" with="50"
                                                height="50" />
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" checked
                                                    id="{{$product->price . $product->id . ''}}"
                                                    name="{{ 'price_' . $product->id }}" value="{{ $product->price }}">
                                                <label class="custom-control-label"
                                                    for="{{ $product->price . $product->id . '' }}"><span
                                                        class="badge badge-warning">{{$product->price}}</span></label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" checked
                                                    id="{{$product->price_L . $product->id . ''}}"
                                                    name="{{ 'price_' . $product->id }}"
                                                    value="{{ $product->price_L }}">
                                                <label class="custom-control-label"
                                                    for="{{ $product->price_L . $product->id . '' }}"><span
                                                        class="badge badge-warning">{{$product->price_L}}</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display: flex; margin-top: 10px;">
                                        <div class="quantity buttons_added">
                                            <input type="button" value="-" class="minus style rounded"><input
                                                type="number"
                                                style="width: 25%; border: 0px solid #f6c23e; text-align: center"
                                                step="1" min="1" max="" name="{{ 'quantity_' .  $product->id }}"
                                                id="{{'quantity_' . $product->id}}" value="1" title="Qty"
                                                class="input-text qty text rounded" size="4" pattern=""
                                                inputmode=""><input type="button" value="+" class="plus style rounded">
                                        </div>
                                    
                                        <button class="btn btn-warning" type="submit">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                </div>
                @endforeach
                <div class=""></div>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="text-center font-weight-bold h4">Đơn hàng</div>
        @if(Cart::count() != 0)
                <?php $content = Cart::content();
                ?>
        <div class="container mt-5 shadow-lg">
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @foreach ($content as $item)
            <div class="row pb-3 pt-3" style="border-bottom: 1px solid #8080802b">
                <div class="col-1">
                    <div class="bg-warning justify-content-center"
                        style="width: 25px; height: 25px; border-radius: 4px">
                        <p style="    text-align: center;
                    color: white;">{{$item->qty}}</p>
                    </div>
                </div>
                <div class="col-6 ">
                    <p class="text-capitalize font-weight-bold text-center">{{$item->name}}</p>
                </div>
                <div class="col-4">
                    {{number_format($item->price) . ' ₫'}}
                </div>
                <div class="col-1">
                    <a href="{{route('admin.cart.update.delete', ['rowID' => $item->rowId])}}"><i
                            class="fas fa-backspace"></i></a>
                </div>
            </div>
            @endforeach
    
        </div>
        <div class="container mt-5">
            <form id="frmCheckOut" action="{{route('admin.cart.update.checkout')}}" method="post">
                @csrf
                
            </form>
        </div>
    
    
    
        @endif
        <div class="container cart mt-3">
            <input type="text" id="cart_count" value="{{Cart::count()}}" hidden>
            <button class="btn btn-warning" disabled style="width: 100%"
                id="cart_checkout">
                <div class="row  font-weight-bolder  text-white" style="padding-top: 3px; font-size: 0.875rem; ">
                    <div class="col-3">
                        {{Cart::count() . ' MÓN'}}
                    </div>
                    <div class="col-6" style="text-align: center">
                        Thêm món
                    </div>
                    <div class="col-3" style="padding-left: inherit" id="total">
    
                        <?php
                            $temp_array = explode(".", Cart::subtotal());
                            echo $temp_array[0] . ' ₫';
                        ?>
                    </div>
                </div>
            </button>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    function wcqib_refresh_quantity_increments() {
        jQuery("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").each(function(a, b) {
            var c = jQuery(b);
            c.addClass("buttons_added"), c.children().first().before('<input type="button" value="-" class="minus" />'), c.children().last().after('<input type="button" value="+" class="plus" />')
        })
        }
        String.prototype.getDecimals || (String.prototype.getDecimals = function() {
        var a = this,
            b = ("" + a).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
        return b ? Math.max(0, (b[1] ? b[1].length : 0) - (b[2] ? +b[2] : 0)) : 0
        }), jQuery(document).ready(function() {
        wcqib_refresh_quantity_increments()
        }), jQuery(document).on("updated_wc_div", function() {
        wcqib_refresh_quantity_increments()
        }), jQuery(document).on("click", ".plus, .minus", function() {
        var a = jQuery(this).closest(".quantity").find(".qty"),
            b = parseFloat(a.val()),
            c = parseFloat(a.attr("max")),
            d = parseFloat(a.attr("min")),
            e = a.attr("step");
        b && "" !== b && "NaN" !== b || (b = 0), "" !== c && "NaN" !== c || (c = ""), "" !== d && "NaN" !== d || (d = 0), "any" !== e && "" !== e && void 0 !== e && "NaN" !== parseFloat(e) || (e = 1), jQuery(this).is(".plus") ? c && b >= c ? a.val(c) : a.val((b + parseFloat(e)).toFixed(e.getDecimals())) : d && b <= d ? a.val(d) : b > 0 && a.val((b - parseFloat(e)).toFixed(e.getDecimals())), a.trigger("change")
        });
</script>
<script>
    $(document).ready(function (){
        let count = $('#cart_count').val();
        if(count === '0'){
            $('#cart_checkout').attr('disabled', true);
        }else{
            $('#cart_checkout').attr('disabled', false);
        }
    });
</script>
<script>
  
    $('#cart_checkout').on('click', function(){
        $('#frmCheckOut').submit();
    });

    $('#discount').on('keyup', function(){
        if(this.value != null || this.value !== undefined)
        {
            const total = parseInt(@json(Cart::subtotal()));
            const number_total = total - (total * this.value / 100.0);
            console.log(total);
            const sub_total = number_total * 1000;
            $('#total').html(new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(sub_total));
        }else{
            const total = parseInt(@json(Cart::subtotal()));
            const sub_total = total * 1000;
            $('#total').html(new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(@json(Cart::subtotal())));
        }
      
    });
</script>
@endsection