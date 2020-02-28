@extends('layouts.admin')
@section('content')
<?php

    $error = Session::get('error');
    if($error){
        echo '<input id="error" type="text" hidden value="'.$error.'"/>';
        Session::put('error', null);
    }

    $success = Session::get('success');
    if($success){
        echo '<input id="success" type="text" hidden value="'.$success.'"/>';
        Session::put('success', null);
    }
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Thông báo  </a></li>
        <li class="breadcrumb-item active" aria-current="page">Gửi thông báo đến khách hàng</li>
    </ol>
</nav>
<div class="col-6">
<form class="needs-validation" novalidate action="{{ route('notification.sendall') }}" method="POST">
    @csrf
    <div class="form-group ">
        <input type="text" name="title" class="form-control  form-control-sm" required
            placeholder="Tiêu đề">
    </div>
    <div class="form-group ">
        <textarea type="text" name="body" class="form-control"  rows="2"  class="form-control-sm" required
            placeholder="Nội dung...."></textarea>
        
    </div>
    <button class="btn btn-warning" type="submit">Gửi thông báo</button>
</form>
</div>
@endsection