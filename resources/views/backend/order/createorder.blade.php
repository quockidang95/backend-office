@extends('layouts.admin')

@section('css')
@endsection
@section('content')
<?php

    $error = Session::get('error');
    if($error){
        echo '<input class="error" type="text" hidden value="'.$error.'"/>';
        Session::put('error', null);
    }

    $success = Session::get('success');
    if($success){
        echo '<input class="success" type="text" hidden value="'.$success.'"/>';
        Session::put('success', null);
    }
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href=" {{ route('order.byday') }} ">Order </a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo Order tại quầy</li>
    </ol>
</nav>
<div class="row">
<div class="col-4">
<div class="form-group">
    <label for="exampleFormControlSelect1">Chọn danh mục</label>
    <select class="form-control" id="category_selected">
        <option> CHỌN DANH MỤC </option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}">  {{ $category->name }}</option>
      @endforeach
    </select>
  </div>
</div>
<div class="col-3" style="margin-top: 30px">
<a class="btn btn-warning" href="{{ route('admin.cart.show') }}">Xem giỏ hàng</a>
</div>
</div>
<div class="row" id="product">

</div>
@endsection

@section('script')
<script>
    $('#category_selected').on('change', () => {
        const id = $('#category_selected option:selected').val()
        $.ajax({
            type: 'get',
            url: '{{ URL::to('get-product-by-categoryid') }}',
            data: {
                    'category_id': id
            },
            success:function(data){
                const products = JSON.parse(data)
                console.log(products)
                let output = '';
                for(let i = 0; i < products.length; i++){
                        output = output + `
                    <div class="col-xl-3 col-md-6 mb-4" data-toggle="modal" data-target="#abc` + products[i].id+ `">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">` + products[i].name +  `</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">` +
                                    new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(products[i].price)
                                 +`</div>
                                </div>
                                <div class="col-auto">
                                    <img class="img-responsive" src="{{asset('source/images/` + products[i].image + `')}}" width="60" height="60"/>
                                    <a class="btn btn-warning ml-2" href="{{env('APP_URL')}}/admin/order/`+ products[i].id + `">Details</a>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                        `
                    }
                $('#product').html(output);
            }   
        })
    })
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>

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
@endsection
