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
<div class="row">
  <div class="col-lg-12">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
                <li class="breadcrumb-item active font-weight-bold" aria-current="page">Danh sách đơn hàng chưa xử lí</li>
          </ol>
      </nav>
  </div>
</div>
@if(auth()->user()->is_surplus_box !== 1)
<button type="button" id="surplus_box_1"  class="btn btn-primary" hidden data-toggle="modal" data-target="#surplus_box">
  Launch demo modal
</button> 

<div class="modal fade" id="surplus_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nhập số dư đầu</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{ route('surplus.box') }}" method="POST">
            @csrf
            <p>Lưu ý: Số này chỉ nhập một lần nếu nhập sai vui lòng đăng xuất</p>
            <div class="form-group">
              <label for="">Nhập số dư đầu</label>
                <input type="number" id="checkNumber" name="surplus_box" class="form-control" required>
                <span id="showNumber"></span>
            </div>
            <button type="submit" class="btn btn-warning"> Xác nhận </button>
          </form>
      </div>
    </div>
  </div>
</div>
@endif
<div class="col-lg-3 mb-5">
    <a href="{{  route('order.admin') }}" class="btn btn-primary">Tạo đơn hàng trực tiếp</a>
</div>

<div class="row">
@foreach ($orders as $item)
<?php
    $customer = App\User::find($item->customer_id);
?>
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{'Bàn: ' . $item->table}}</div>
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{'Khách: ' . $customer->name}}</div>
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{'SĐT: ' . $customer->phone}}</div>
          <?php
            if($item->status == 1){
                echo '<span class="badge badge-pill badge-danger">Chưa xử lí</span>';
            }else if($item->status == 2){
                echo '<span class="badge badge-pill badge-warning">Đã tiếp nhận</span>';
            }
          ?>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{number_format($item->total_price) . '  VNĐ'}} </div>

          </div>
          <div class="col-auto">
          <a href="{{route('order.details', ['id' => $item->id])}}"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach
</div>

@endsection()
@section('script')
<script>
  const surplus_box = $('#surplus_box_1');
  if(surplus_box){
    surplus_box.click()
  }
</script>
<script>
  $(document).ready(function (){
    $('#checkNumber').on('keyup', function(){
      const num = $(this).val();
      console.log(num);
      const newNum = new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'VND' }).format(num);
      $('#showNumber').html(newNum.toString()).css('color', 'green').addClass('font-weight-bold');
    })
  })
</script>
@endsection()
