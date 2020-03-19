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
<div class="row">
<div class="col-8" >
    <div class="row" id="product">

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
                <div class="bg-warning justify-content-center" style="width: 25px; height: 25px; border-radius: 4px">
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
                <a href="{{route('admin.cart.delete', ['rowID' => $item->rowId])}}"><i class="fas fa-backspace"></i></a>
            </div>
        </div>
        @endforeach
       
    </div>
    <div class="container mt-5">
        <form id="frmCheckOut" action="{{route('admin.cart.checkout')}}" method="post">
            @csrf
            <div class="form-group ">
                <label for="sothe">Số thẻ</label>
                <input type="text" class="form-control" name="table" />
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_delivery" id="is_delivery" value="1">
                <label class="form-check-label" for="is_delivery">
                  Mang đi
                </label>
              </div>
            <div class="form-group">
                <input type="text" name="note" required="" class="form-control form-control-user p-4" placeholder="Bạn có muốn dặn dò gì không">
            </div>
        </form>
    </div>
    
    
    
    @endif
    <div class="container cart mt-5">
        <input type="text" id="cart_count" value="{{Cart::count()}}" hidden>
        <button class="btn btn-warning" disabled style="width: 100%" data-toggle="modal" data-target="#exampleModal"
            id="cart_checkout">
            <div class="row  font-weight-bolder  text-white" style="padding-top: 3px; font-size: 0.875rem; ">
                <div class="col-3">
                    {{Cart::count() . ' MÓN'}}
                </div>
                <div class="col-6" style="text-align: center">
                    Đặt hàng
                </div>
                <div class="col-3" style="padding-left: inherit">
    
                    <?php
                    $temp_array = explode(".", Cart::subtotal());
                        echo $temp_array[0] . ' ₫';
                    ?>
                </div>
            </div>
        </button>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><img src="{{asset('img/office_logo_1.png')}}"
                            class="rounded-circle" alt="" width="14%" height="auto" style="background-color: #f6c23e"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center"> Xác nhận đặt hàng</p>
                </div>
                <div class="modal-footer">
    
                    <button class="btn btn-warning" id="check_out" style="color: white" href="">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
</div>
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
                    if(products[i].price_L == null){
                        
                        output = output + `
                            <div class="col-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <form action="{{env('APP_URL')}}/admin/cart/add/` + products[i].id +`" method="get">
                                            @csrf
                                            <div class="row no-gutters align-items-center">
                                                <div class="col">
                                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-2">` + products[i].name  + `</div>
                                                    <div>
                                                        <img src="{{asset('source/images/`+ products[i].image +`')}}" with="50" height="50"/>
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" checked id="`+ products[i].price + products[i].id + '' + `"
                                                                name="price_`+ products[i].id +`" value="`+ products[i].price +` ">
                                                            <label class="custom-control-label" for="`+ products[i].price + products[i].id + '' + `"><span
                                                                    class="badge badge-warning">` +products[i].price + `</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus style rounded"><input type="number"
                                                            style="width: 25%; border: 0px solid #f6c23e; text-align: center" step="1" min="1" max=""
                                                            name="quantity_`+ products[i].id +`" id="quantity_`+ products[i].id +`" value="1" title="Qty" class="input-text qty text rounded" size="4"
                                                            pattern="" inputmode=""><input type="button" value="+" class="plus style rounded">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <button class="btn btn-warning" type="submit">Add</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                       
                        `
                    }else{
                        output = output + `
                        <div class="col-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <form action="{{env('APP_URL')}}/admin/cart/add/` + products[i].id +`" method="get">
                                            @csrf
                                            <div class="row no-gutters align-items-center">
                                                <div class="col">
                                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">` + products[i].name  + `</div>
                                                    <div>
                                                        <img src="{{asset('source/images/`+ products[i].image +`')}}" with="50" height="50"/>
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" checked id="`+ products[i].price + products[i].id + '' + `"
                                                                name="price_`+ products[i].id +`" value="`+ products[i].price +` ">
                                                            <label class="custom-control-label" for="`+ products[i].price + products[i].id + '' + `"><span
                                                                    class="badge badge-warning">` +products[i].price + `</span></label>
                                                        </div>

                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" id="`+ products[i].price_L + products[i].id + '' + `"
                                                                name="price_`+ products[i].id +`" value="`+ products[i].price_L +` ">
                                                            <label class="custom-control-label" for="`+ products[i].price_L + products[i].id + '' + `"><span
                                                                    class="badge badge-warning">` +products[i].price_L + `</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="quantity buttons_added">
                                                        <input type="button" value="-" class="minus style rounded"><input type="number"
                                                            style="width: 25%; border: 0px solid #f6c23e; text-align: center" step="1" min="1" max=""
                                                            name="quantity_`+ products[i].id +`" id="quantity_`+ products[i].id +`" value="1" title="Qty" class="input-text qty text rounded" size="4"
                                                            pattern="" inputmode=""><input type="button" value="+" class="plus style rounded">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <button class="btn btn-warning" type="submit">Add</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                       `
                    }
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
    $(document).ready(function (){
        $('#check_out').on('click', function(){
            $('#frmCheckOut').submit();
        });
    });

</script>
@endsection

