@extends('layouts.customer')
<div class="card">
    <div class="card-body">
        <p class="card-text">{{'Cửa hàng: ' . Session::get('store_code')}}</p>
        <p class="card-text">{{'Bàn: ' . Session::get('table')}}</p>

    </div>
</div>
@if(Cart::count() != 0)
<?php $content = Cart::content();

    $wallet = App\User::find(intval(Cookie::get('id')))->wallet;
    echo '<input type="number" id="wallet" name="wallet" value="' . $wallet . '" hidden>';
?>
<div class="container mt-5 shadow-lg">
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
            <a href="{{route('cart.delete', ['id' => $item->rowId])}}"><i class="fas fa-backspace"></i></a>
        </div>
    </div>
    @endforeach
</div>



<form id="frmCheckOut" action="{{route('cart.checkout')}}" method="post">
    @csrf

    <input type="text" hidden name="discount_promotion" value="0" id="discount_promotion" />
    <input type="text" hidden name="discount_price" value="0" id="discount_price" />

    <fieldset id="foobar" class="col-md-4 order-md-2 mt-3">
    <label for="" class="font-weight-bold">Phương thức thanh toán</label>
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input input-checked" name="payment_method" value="1" id="vi" checked
          >
        <label for="vi" class="custom-control-label" ><span
                class="badge badge-warning">Dùng ví </span></label>
    </div>
    <br>
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input input-checked" name="payment_method" value="2" id="tienmat">
        <label for="tienmat" class="custom-control-label" ><span
                class="badge badge-warning">Thanh toán bằng tiền mặt</span></label>
    </div>
    <br>
</fieldset>

<input type="text" name="note" required="" class="form-control form-control-user p-4" placeholder="Bạn có muốn dặn dò gì không">

</form>

<div class="row mb-4 ml-2">
    <div class="col-6">
        <label>Bạn có mã giảm giá</label>
        <input type="text" class="form-control" id="promotion_code" />
        <span id="status" class="mt-2"><span>
    </div>

    <div class="col-3" style="padding-top: 31px;">
        <button class="btn btn-warning" id="btn_confirm_promotion">Confirm</button>
    </div>
</div>

<div class="col-md-4 border-md-2 mb-5 mt-0">
    <ul class="list-group mb-3">
        <li class="list-group-item d-flex justify-content-between">
            <span>Tạm tính (VNĐ)</span>
        <strong>{{Cart::subtotal() . ' ₫'}}</strong>
        </li>
        <li class="list-group-item d-flex justify-content-between">
            <span>Chiết khấu (%)</span>
        <strong id="total_discount">{{$setting->discount_user . ' %'}}</strong>
        </li>
        <li class="list-group-item d-flex justify-content-between">
            <span>Tổng cộng (VNĐ)</span>
        <strong id="total_price">
          <?php
            $temp = explode(".", Cart::subtotal());
            $temp1 = explode(",", $temp[0]);
            $price = $temp1[0] . $temp1[1];
            $price_int = intval($price);
            ?>
        </strong>
        </li>
        <li class="list-group-item d-flex justify-content-between">
            <span>Điểm tích lũy</span>
        <strong id="point">
    
        </strong>
        </li>
    </ul>
</div>
@endif
<div class="container-fluid cart">
    <input type="text" id="cart_count" value="{{Cart::count()}}" hidden>
    <button class="btn btn-warning" disabled style="width: 100%" data-toggle="modal" data-target="#exampleModal"
        id="cart_checkout">
        <div class="row  font-weight-bolder  text-white" style="padding-top: 3px; font-size: 0.875rem; ">
            <div class="col-3">
              
            </div>
            <div class="col-6" style="text-align: center">
                Đặt hàng
            </div>
            <div class="col-3" style="padding-left: inherit">

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

@section('script')
<script>
    $(document).ready(function (){

        let count = $('#cart_count').val();

        if(count === '0'){
            $('#cart_checkout').attr('disabled', true);
        }else{
            $('#cart_checkout').attr('disabled', false);
        }

        const total_discount = @json($setting->discount_user);
        $('#discount_promotion').val(total_discount) ;

        
        const total_price = @json($price_int) - @json($price_int) * total_discount / 100;
        $('#discount_price').val(total_price);
        $('#total_price').text(new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(total_price));
        const point = total_price / @json($setting->discount_point);
        $('#point').text(point + ' điểm');
    });

</script>
<script>
    $(document).ready(function (){
        $('#check_out').on('click', function(){
            var wallet = $('#wallet').val();
            var price_final = $('#discount_price').val();
            var radioValue = $("input[name='payment_method']:checked").val();
            if(radioValue === '2'){
                $('#frmCheckOut').submit();
            }else{
                if(Number(wallet) > Number(price_final)){
                $('#frmCheckOut').submit();
                }else{
                    alert('Số dư không đủ');
                }
            }
        });
    });

</script>

<script>
        $('#btn_confirm_promotion').on('click' , function(){
            $promotionCode = $('#promotion_code').val();

            $.ajax({
                        type: 'get',
                        url: '{{ URL::to('confirm-promotion-code') }}',
                        data: {
                            'promotionCode': $promotionCode
                        },
                        success:function(data){
                            if ( data.error )
                            {
                                $('#status').text(data.error).addClass("badge badge-danger");
                                return;
                            } else {
                                $('#status').text('thành công').removeClass("badge badge-danger").addClass("badge badge-success");
                            }
                            
                            const total_discount = data.adjuster || @json($setting->discount_user);
                            $('#total_discount').text(total_discount + ' %')

                            $('#discount_promotion').val(total_discount) ;
                         
                            
                            const total_price = @json($price_int) - @json($price_int) * total_discount / 100;
                            $('#total_price').text(new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(total_price));
                            $('#discount_price').val(total_price);
                            const point = total_price / @json($setting->discount_point);
                            $('#point').text(point + ' điểm');
                            
                        }
                    });
        });
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>

@endsection
