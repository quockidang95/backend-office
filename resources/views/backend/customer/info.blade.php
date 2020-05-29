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
<nav aria-label="breadcrumb col-8">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Khách hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Thông tin khách hàng</li>
    </ol>
</nav>
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{$user->name}}</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                    {{'Số điện thoại: ' .$user->phone . '.'}}</div>

                                <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                    {{number_format($user->wallet) . ' '}}
                                    <i class="fas fa-money-check-alt"></i>
                                </div>
                                <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">
                                    {{$user->point . ' '}}
                                    <i class="fas fa-palette"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-info" id="rechagePriceHide">
                            Nạp tiền</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4" style="display: none" id="frmRechage">
        <form action="{{route('naptien', ['id' => $user->id])}}" method="post" onsubmit="return validateForm()" class="mt-3">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Nạp tiền cho khách hàng...." name="money"
                    aria-label="Nạp tiền cho khách hàng...." aria-describedby="basic-addon2" id="checkNumber" required>
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2">VNĐ</span>
                </div>
            </div>
            <div><span id='newNum'></span></div>
            <div class="input-group pt-3">
                <input type="text" class="form-control" placeholder="Tiền thưởng nếu có...." name="money_discount"
                aria-label="Nạp tiền cho khách hàng...." aria-describedby="basic-addon3" id="checkNumber1" >
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon3">VNĐ</span>
                </div>
            </div>
            <div><span id='newNum1'></span></div>
            <div class="input-group pt-3">
                <input type="text" class="form-control" placeholder="Điểm thưởng nếu có nếu có...." name="point_discount"
                aria-label="Điểm thưởng nếu có...." aria-describedby="basic-addon4" id="checkNumber2">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon4">Điểm</span>
                </div>
            </div>
            <div><span id='newNum2'></span></div>
            <button class="btn btn-info mt-3 mr-2"  type="button" data-toggle="modal" style="margin-left: 255px"
                data-target="#confirmDeposit">Xác nhận</button>

            <!-- Modal -->
            <div class="modal fade" id="confirmDeposit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title font-weight-bold" id="exampleModalLabel">Xác nhận nạp tiền</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="font-weight-bold">Khách hàng: {{$user->name}}.</p>
                            <p class="font-weight-bolder text-danger" id="money"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-info">Nạp tiền</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="row">
    <div class="col-2 mb-5">
        <button type="button" onclick="showRechages()" class="btn btn-success">Lịch sử nạp tiền</button>
    </div>
</div>
<table class="table table-bordered table-sm" style="display: none">
    <thead class="thead-dark">
        <tr>
            <th scope="col">STT</th>
            <th scope="col">Số tiền</th>
            <th scope="col">Tiền thưởng</th>
            <th scope="col">Điểm thưởng</th>
            <th scope="col">Cửa hàng</th>
            <th scope="col">Thời gian</th>
            <th scope="col">Nhân viên</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rechages as $key => $item)
        <tr>
            <th scope="row">{{$key + 1}}</th>
            <td>{{ number_format($item->price). ' VNĐ'}}</td>
            <td>{{ number_format($item->money_discount). ' VNĐ'}}</td>
            <td>{{ $item->point_discount. ' Point'}}</td>
            <td>{{ $item->store_code}}</td>
            <td>{{ $item->created_at}}</td>
            <td>{{ $item->created_by}}</td>
            @endforeach
    </tbody>
</table>

<div class="mt-3">
<table class="table table-bordered table-sm">
    <thead class="thead-dark">
        <tr>
            <th scope="col">STT</th>
            <th scope="col">Mã hoá đơn</th>
            <th scope="col">Mã Cửa hàng</th>
            <th scope="col">Tổng tiền</th>
            <th scope="col">Ngày tạo</th>
            <th scope="col">Nhân viên xử lí</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ordersHistory as $key => $item)
        <tr>
            <th scope="row">{{$key + 1}}</th>
            <td>{{ $item->order_code }}</td>
            <td>{{ $item->store_code }}</td>
            <td>{{ number_format($item->price) . ' VNĐ' }}</td>
            <td>{{ $item->order_date }}</td>
            <td>{{ $item->created_by}}</td>
            <td>
                <a class="btn btn-info btn-circle" href="{{ route('order.view', ['id' => $item->id]) }}"
                    title="Xem thông tin khách hàng">
                    <i class="fas fa-info-circle"></i>
                </a>
            </td>
            @endforeach
    </tbody>
</table>
</div>
@endsection
@section('script')
<script>
    $('#checkNumber').on('keyup', function () {
        var number = $(this).val();
         if (isNaN(number) === false && number % 5000 == 0 && number > 20000) {

           var newNum = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(number);
           $('#newNum').html(newNum.toString()).css('color', 'green').addClass('font-weight-bold');
           $('#money').html( 'Xác nhận nạp' + ' ' + newNum.toString() + ' ' + 'vào tài khoản.');
         }else{
           $('#newNum').html('Số tiền nạp không hợp lệ').css('color', 'red').removeClass('font-weight-bold');
           $('#money').html( 'Số tiền nạp không hợp lệ');
         }
       });

       $('#checkNumber1').on('keyup', function () {
        var number1 = $(this).val();
         if (isNaN(number1) === false && number1 % 5000 == 0) {

           var newNum1 = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(number1);
           $('#newNum1').html(newNum1.toString()).css('color', 'green').addClass('font-weight-bold');

         }else{
           $('#newNum1').html('Số tiền nạp không hợp lệ').css('color', 'red').removeClass('font-weight-bold');
         }
       });

       $('#checkNumber2').on('keyup', function(){
            var number2 = $(this).val();
            if(isNaN(number2) == false){
                $('#newNum2').html(number2.toString()).css('color', 'green').addClass('font-weight-bold');
            }else{
                $('#newNum2').html('Điểm không hợp lệ').css('color', 'red').removeClass('font-weight-bold');
            }
       });
</script>
<script>
    function validateForm() {
            var x = $('#checkNumber').val();
            var y = $('#checkNumber1').val();
            var z = $('#checkNumber2').val();
            var check = false;
            if( (Number(z) === z && z % 1 === 0) || (Number(z) === z && z % 1 !== 0) || z == null || (isNaN(y) === false && y % 5000 === 0) || y === null ){
                check = true;
            }
            if (isNaN(x) === false && x % 5000 == 0 && x > 20000 &&  && check == true) {
                return true;
            }
            alert('Tiền nạp không hợp lệ');
            return false;
       }
</script>
<script>
    $("#rechagePriceHide").click(function(){
  $("#frmRechage").show(500);
  $(this).removeAttr('id').attr('id', 'rechagePriceShow');
});
</script>
<script>
    $(function(){
        var success = $('.success').val();
        var error = $('.error').val();
        if(success){
            toastr.success(success, 'Hệ thống thông báo: ', {timeOut: 3000});
        }
        if(error){
            toastr.error(error, 'Hệ thống thông báo: ', {timeOut: 3000});
        }
    });
</script>
<script>
    function showRechages(){
        $('table').show(500);
    }
</script>
@endsection
