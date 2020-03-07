@extends('layouts.admin')
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
    <li class="breadcrumb-item"><a href=" {{ route('order.admin') }} ">Tạo Order </a></li>
        <li class="breadcrumb-item active" aria-current="page">Xem giỏ hàng</li>
    </ol>
</nav>
@if(Cart::count() != 0)
<?php $content = Cart::content();
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
            <a href="{{route('admin.cart.delete', ['rowID' => $item->rowId])}}"><i class="fas fa-backspace"></i></a>
        </div>
    </div>
    @endforeach
   
</div>
<div class="container mt-5">
    <form id="frmCheckOut" action="{{route('admin.cart.checkout')}}" method="post">
        @csrf
        <input type="text" name="note" required="" class="form-control form-control-user p-4" placeholder="Bạn có muốn dặn dò gì không">
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
@endsection
@section('script')
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
